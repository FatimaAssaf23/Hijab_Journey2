<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\EmergencyRequest;
use App\Models\StudentClass;
use App\Models\User;
use App\Models\TeacherSubstitution;
use Illuminate\Support\Facades\Auth;
use App\Notifications\TeacherReassignedNotification;
// use Illuminate\Support\Facades\Auth;
// use App\Models\User;

class EmergencyRequestController extends Controller
{
    // Show form for teachers
    public function create()
    {
        $latestRequest = EmergencyRequest::where('teacher_id', Auth::id())->latest()->first();
        return view('teacher.emergency.latest', ['request' => $latestRequest]);
    }

    // Store emergency request
    public function store(Request $request)
    {
        $request->validate([
            'start_date' => ['required', 'date', 'after:today'],
            'end_date' => ['required', 'date', 'after_or_equal:start_date'],
            'reason' => 'required|string|max:1000',
        ]);

        $emergencyRequest = EmergencyRequest::create([
            'teacher_id' => Auth::id(),
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'reason' => $request->reason,
            'status' => 'pending',
        ]);

        return view('teacher.emergency.confirmation', ['request' => $emergencyRequest]);
    }

    // Admin: List all requests
    public function adminIndex()
    {
        $requests = EmergencyRequest::with('teacher')->latest()->get();
        // For each request, get affected classes
        foreach ($requests as $request) {
            $request->affected_classes = \App\Models\StudentClass::where('teacher_id', $request->teacher_id)->pluck('class_name');
        }
        return view('admin.emergency.index', compact('requests'));
    }

    // Admin: Reassign classes to another teacher
    public function reassign(Request $request)
    {
        $request->validate([
            'emergency_request_id' => 'required|exists:emergency_requests,id',
            'teacher_id' => 'required|exists:users,user_id',
        ]);

        $emergencyRequest = EmergencyRequest::findOrFail($request->emergency_request_id);

        // Get all classes affected by this emergency (taught by the absent teacher)
        $affectedClasses = StudentClass::where('teacher_id', $emergencyRequest->teacher_id)->get();
        $substitute = User::findOrFail($request->teacher_id);
        $originalTeacher = User::findOrFail($emergencyRequest->teacher_id);

        foreach ($affectedClasses as $class) {
            // Create a TeacherSubstitution record (if not exists for this class/request)
            TeacherSubstitution::create([
                'class_id' => $class->class_id,
                'original_teacher_id' => $originalTeacher->user_id,
                'substitute_teacher_id' => $substitute->user_id,
                'requested_by_admin_id' => Auth::id(),
                'reason' => $emergencyRequest->reason,
                'start_date' => $emergencyRequest->start_date,
                'end_date' => $emergencyRequest->end_date,
                'status' => 'active',
            ]);

            // Notify and email the substitute teacher
            $substitute->notify(new \App\Notifications\TeacherReassignedNotification($class->class_name, $originalTeacher->first_name . ' ' . $originalTeacher->last_name));
        }

        // Optionally, update the emergency request status
        $emergencyRequest->status = 'reassigned';
        $emergencyRequest->save();

        return redirect()->route('admin.emergency.index')->with('success', 'Classes successfully reassigned and the teacher has been notified.');
    }
}
