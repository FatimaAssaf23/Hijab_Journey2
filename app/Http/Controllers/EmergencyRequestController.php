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
        $teacherId = Auth::id();
        $latestRequest = EmergencyRequest::where('teacher_id', $teacherId)->latest()->first();
        $allRequests = EmergencyRequest::where('teacher_id', $teacherId)
            ->orderBy('created_at', 'desc')
            ->get();
        return view('teacher.emergency.latest', [
            'request' => $latestRequest,
            'allRequests' => $allRequests
        ]);
    }

    // Store emergency request
    public function store(Request $request)
    {
        $request->validate([
            'start_date' => ['required', 'date', 'after:today'],
            'end_date' => ['required', 'date', 'after_or_equal:start_date'],
            'reason' => 'required|string|max:1000',
        ]);

        $teacherId = Auth::id();
        $startDate = $request->start_date;
        $endDate = $request->end_date;

        // Check for overlapping date ranges with ALL existing requests (pending, approved, reassigned)
        // We allow new requests to start on the same day an existing request ends
        // Overlap occurs when ranges share common days: existing_start <= new_end AND existing_end >= new_start
        // But we exclude the case where new starts exactly when existing ends (same day boundary allowed)
        // So overlap if: existing_start <= new_end AND existing_end > new_start
        $overlappingRequests = EmergencyRequest::where('teacher_id', $teacherId)
            ->whereIn('status', ['pending', 'approved', 'reassigned'])
            ->where(function($query) use ($startDate, $endDate) {
                // Check if ranges overlap (share common days)
                // Allow same-day boundaries: if existing ends on Jan 30, new can start on Jan 30
                $query->where('start_date', '<=', $endDate)
                      ->where('end_date', '>', $startDate); // Use > instead of >= to allow same-day start
            })
            ->orderBy('end_date', 'desc')
            ->get();

        if ($overlappingRequests->count() > 0) {
            // Find the latest end date among all overlapping requests
            // Since we allow same-day boundaries, new request can start on the same day existing ends
            $latestEndDate = $overlappingRequests->max('end_date');
            $nextAvailableDate = \Carbon\Carbon::parse($latestEndDate)->format('M j, Y');
            
            // Build error message showing all conflicting requests
            $conflictingDates = [];
            foreach ($overlappingRequests as $req) {
                $conflictingDates[] = \Carbon\Carbon::parse($req->start_date)->format('M j, Y') . ' - ' . 
                                     \Carbon\Carbon::parse($req->end_date)->format('M j, Y');
            }
            
            // Create a more helpful error message
            if ($overlappingRequests->count() === 1) {
                $errorMessage = 'Your request overlaps with an existing emergency request (' . 
                               $conflictingDates[0] . '). Please choose a start date on or after ' . 
                               $nextAvailableDate . ' to avoid overlap.';
            } else {
                $errorMessage = 'Your request overlaps with ' . $overlappingRequests->count() . 
                               ' existing emergency requests: ' . implode(', ', $conflictingDates) . 
                               '. Please choose a start date on or after ' . $nextAvailableDate . 
                               ' to avoid overlap.';
            }
            
            return back()
                ->withInput()
                ->withErrors([
                    'start_date' => $errorMessage,
                ]);
        }

        // Check if admin notifications for emergency requests are enabled
        $notifyAdmin = \App\Models\AdminSetting::get('notify_admin_on_emergency_requests', true);
        
        $emergencyRequest = EmergencyRequest::create([
            'teacher_id' => $teacherId,
            'start_date' => $startDate,
            'end_date' => $endDate,
            'reason' => $request->reason,
            'status' => 'pending',
            'is_read' => !$notifyAdmin, // If notifications enabled, mark as unread (false), otherwise read (true)
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
        
        // Get unread emergency requests count
        $unreadEmergencyRequestsCount = EmergencyRequest::where('is_read', false)
            ->where('status', 'pending')
            ->count();
        
        // Mark all pending unread requests as read when admin views the page
        if ($unreadEmergencyRequestsCount > 0) {
            EmergencyRequest::where('is_read', false)
                ->where('status', 'pending')
                ->update(['is_read' => true]);
        }
        
        return view('admin.emergency.index', [
            'requests' => $requests,
            'unreadEmergencyRequestsCount' => $unreadEmergencyRequestsCount,
        ]);
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

        return redirect()->route('admin.emergency')->with('success', 'Classes successfully reassigned and the teacher has been notified.');
    }

    // Show edit form for teachers (only for pending requests)
    public function edit($id)
    {
        $emergencyRequest = EmergencyRequest::findOrFail($id);
        
        // Check if the request belongs to the current teacher
        if ($emergencyRequest->teacher_id !== Auth::id()) {
            abort(403, 'Unauthorized: You can only edit your own requests.');
        }
        
        // Only allow editing pending requests
        if ($emergencyRequest->status !== 'pending') {
            return redirect()->route('teacher.emergency.create')
                ->with('error', 'You can only edit pending requests. This request has already been ' . $emergencyRequest->status . '.');
        }
        
        return view('teacher.emergency.edit', ['request' => $emergencyRequest]);
    }

    // Update emergency request (only for pending requests)
    public function update(Request $request, $id)
    {
        $emergencyRequest = EmergencyRequest::findOrFail($id);
        
        // Check if the request belongs to the current teacher
        if ($emergencyRequest->teacher_id !== Auth::id()) {
            abort(403, 'Unauthorized: You can only edit your own requests.');
        }
        
        // Only allow editing pending requests
        if ($emergencyRequest->status !== 'pending') {
            return redirect()->route('teacher.emergency.create')
                ->with('error', 'You can only edit pending requests. This request has already been ' . $emergencyRequest->status . '.');
        }
        
        $request->validate([
            'start_date' => ['required', 'date', 'after:today'],
            'end_date' => ['required', 'date', 'after_or_equal:start_date'],
            'reason' => 'required|string|max:1000',
        ]);

        $teacherId = Auth::id();
        $startDate = $request->start_date;
        $endDate = $request->end_date;

        // Check for overlapping date ranges with ALL existing requests (excluding the current request being edited)
        // We allow new requests to start on the same day an existing request ends
        $overlappingRequests = EmergencyRequest::where('teacher_id', $teacherId)
            ->where('id', '!=', $id) // Exclude the current request being edited
            ->whereIn('status', ['pending', 'approved', 'reassigned'])
            ->where(function($query) use ($startDate, $endDate) {
                // Check if ranges overlap (share common days)
                // Allow same-day boundaries: if existing ends on Jan 30, new can start on Jan 30
                $query->where('start_date', '<=', $endDate)
                      ->where('end_date', '>', $startDate); // Use > instead of >= to allow same-day start
            })
            ->orderBy('end_date', 'desc')
            ->get();

        if ($overlappingRequests->count() > 0) {
            // Find the latest end date among all overlapping requests
            $latestEndDate = $overlappingRequests->max('end_date');
            $nextAvailableDate = \Carbon\Carbon::parse($latestEndDate)->format('M j, Y');
            
            // Build error message showing all conflicting requests
            $conflictingDates = [];
            foreach ($overlappingRequests as $req) {
                $conflictingDates[] = \Carbon\Carbon::parse($req->start_date)->format('M j, Y') . ' - ' . 
                                     \Carbon\Carbon::parse($req->end_date)->format('M j, Y');
            }
            
            // Create a more helpful error message
            if ($overlappingRequests->count() === 1) {
                $errorMessage = 'Your request overlaps with an existing emergency request (' . 
                               $conflictingDates[0] . '). Please choose a start date on or after ' . 
                               $nextAvailableDate . ' to avoid overlap.';
            } else {
                $errorMessage = 'Your request overlaps with ' . $overlappingRequests->count() . 
                               ' existing emergency requests: ' . implode(', ', $conflictingDates) . 
                               '. Please choose a start date on or after ' . $nextAvailableDate . 
                               ' to avoid overlap.';
            }
            
            return back()
                ->withInput()
                ->withErrors([
                    'start_date' => $errorMessage,
                ]);
        }

        // Update the request
        $emergencyRequest->start_date = $startDate;
        $emergencyRequest->end_date = $endDate;
        $emergencyRequest->reason = $request->reason;
        $emergencyRequest->save();

        return redirect()->route('teacher.emergency.create')
            ->with('success', 'Emergency request updated successfully!');
    }
}
