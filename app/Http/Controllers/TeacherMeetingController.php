<?php

namespace App\Http\Controllers;

use App\Models\Meeting;
use App\Models\MeetingEnrollment;
use App\Models\User;
use App\Services\AttendanceTrackingService;
use App\Services\MeetingEnrollmentService;
use Illuminate\Http\Request;

class TeacherMeetingController extends Controller
{
    protected $enrollmentService;
    
    public function __construct(MeetingEnrollmentService $enrollmentService)
    {
        $this->enrollmentService = $enrollmentService;
    }
    public function index()
    {
        $meetings = Meeting::where('teacher_id', auth()->id())
            ->with('enrollments.student')
            ->latest()
            ->paginate(20);
        return view('teacher.meetings.index', compact('meetings'));
    }
    
    public function create()
    {
        $students = User::where('role', 'student')->get();
        return view('teacher.meetings.create', compact('students'));
    }
    
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'google_meet_link' => 'required|url|regex:/meet\.google\.com/',
            'scheduled_at' => 'required|date|after:now',
            'duration_minutes' => 'required|integer|min:10|max:480',
            'student_ids' => 'required|array|min:1',
        ]);
        
        $meeting = Meeting::create([
            'teacher_id' => auth()->id(),
            'title' => $validated['title'],
            'google_meet_link' => $validated['google_meet_link'],
            'scheduled_at' => $validated['scheduled_at'],
            'duration_minutes' => $validated['duration_minutes'],
        ]);
        
        foreach ($validated['student_ids'] as $studentId) {
            MeetingEnrollment::create([
                'meeting_id' => $meeting->meeting_id,
                'student_id' => $studentId,
            ]);
        }
        
        return redirect()->route('teacher.meetings.index')->with('success', 'Meeting created successfully');
    }
    
    public function show($meeting)
    {
        $meeting = Meeting::where('meeting_id', $meeting)->firstOrFail();
        $meeting->load(['enrollments.student', 'enrollments.confirmations']);
        
        // Ensure teacher owns this meeting
        if ($meeting->teacher_id !== auth()->id()) {
            abort(403, 'Unauthorized access to this meeting.');
        }
        
        // Ensure enrollments exist (for backward compatibility)
        $this->enrollmentService->ensureMeetingHasEnrollments($meeting);
        
        // Reload to get fresh enrollments
        $meeting->load(['enrollments.student', 'enrollments.confirmations']);
        
        // Get all students in the class
        $allStudents = collect();
        if ($meeting->class_id) {
            $allStudents = \App\Models\Student::where('class_id', $meeting->class_id)
                ->with('user')
                ->get();
        }
        
        // Get all attendance records for this meeting
        $attendances = \App\Models\MeetingAttendance::where('meeting_id', $meeting->meeting_id)
            ->with('student.user')
            ->get()
            ->keyBy('student_id');
        
        return view('teacher.meetings.show', compact('meeting', 'allStudents', 'attendances'));
    }
    
    public function edit($meeting)
    {
        $meeting = Meeting::where('meeting_id', $meeting)->firstOrFail();
        $meeting->load('enrollments');
        
        // Ensure teacher owns this meeting
        if ($meeting->teacher_id !== auth()->id()) {
            abort(403, 'Unauthorized access to this meeting.');
        }
        
        // Prevent editing if meeting is completed or cancelled
        if (in_array($meeting->status, ['completed', 'cancelled'])) {
            return redirect()->route('teacher.meetings.show', $meeting->meeting_id)
                ->with('error', 'Cannot edit a meeting that is already completed or cancelled.');
        }
        
        $students = User::where('role', 'student')->get();
        $selectedStudentIds = $meeting->enrollments->pluck('student_id')->toArray();
        
        return view('teacher.meetings.edit', compact('meeting', 'students', 'selectedStudentIds'));
    }
    
    public function update(Request $request, $meeting)
    {
        $meeting = Meeting::where('meeting_id', $meeting)->firstOrFail();
        
        // Ensure teacher owns this meeting
        if ($meeting->teacher_id !== auth()->id()) {
            abort(403, 'Unauthorized access to this meeting.');
        }
        
        // Prevent editing if meeting is completed or cancelled
        if (in_array($meeting->status, ['completed', 'cancelled'])) {
            return redirect()->route('teacher.meetings.show', $meeting->meeting_id)
                ->with('error', 'Cannot edit a meeting that is already completed or cancelled.');
        }
        
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'google_meet_link' => 'required|url|regex:/meet\.google\.com/',
            'scheduled_at' => 'required|date|after_or_equal:today',
            'duration_minutes' => 'required|integer|min:10|max:480',
            'student_ids' => 'required|array|min:1',
        ]);
        
        // Update meeting details
        $meeting->update([
            'title' => $validated['title'],
            'google_meet_link' => $validated['google_meet_link'],
            'scheduled_at' => $validated['scheduled_at'],
            'duration_minutes' => $validated['duration_minutes'],
        ]);
        
        // Update enrollments - remove old ones and add new ones
        $currentStudentIds = $meeting->enrollments->pluck('student_id')->toArray();
        $newStudentIds = $validated['student_ids'];
        
        // Remove enrollments for students no longer selected
        $studentsToRemove = array_diff($currentStudentIds, $newStudentIds);
        if (!empty($studentsToRemove)) {
            MeetingEnrollment::where('meeting_id', $meeting->meeting_id)
                ->whereIn('student_id', $studentsToRemove)
                ->delete();
        }
        
        // Add enrollments for newly selected students
        $studentsToAdd = array_diff($newStudentIds, $currentStudentIds);
        foreach ($studentsToAdd as $studentId) {
            MeetingEnrollment::firstOrCreate([
                'meeting_id' => $meeting->meeting_id,
                'student_id' => $studentId,
            ]);
        }
        
        // Redirect back to the old meetings system (/meetings) after updating
        return redirect()->route('meetings.index')
            ->with('success', 'Meeting updated successfully');
    }
    
    public function destroy($meeting)
    {
        $meeting = Meeting::where('meeting_id', $meeting)->firstOrFail();
        
        // Ensure teacher owns this meeting
        if ($meeting->teacher_id !== auth()->id()) {
            abort(403, 'Unauthorized access to this meeting.');
        }
        
        // Prevent deletion if meeting is ongoing
        if ($meeting->status === 'ongoing') {
            return redirect()->route('meetings.index')
                ->with('error', 'Cannot delete a meeting that is currently ongoing.');
        }
        
        // Delete related enrollments and attendances
        MeetingEnrollment::where('meeting_id', $meeting->meeting_id)->delete();
        \App\Models\MeetingAttendance::where('meeting_id', $meeting->meeting_id)->delete();
        
        $meeting->delete();
        
        return redirect()->route('meetings.index')
            ->with('success', 'Meeting deleted successfully');
    }
}
