<?php

namespace App\Http\Controllers;

use App\Models\Meeting;
use App\Models\MeetingEnrollment;
use App\Models\AttendanceConfirmation;
use App\Services\AttendanceTrackingService;
use App\Services\MeetingEnrollmentService;
use Illuminate\Http\Request;

class StudentMeetingController extends Controller
{
    protected $enrollmentService;
    
    public function __construct(MeetingEnrollmentService $enrollmentService)
    {
        $this->enrollmentService = $enrollmentService;
    }
    public function index()
    {
        // Ensure all class meetings have enrollments for this student
        $user = auth()->user();
        $student = $user->student;
        
        if ($student && $student->class_id) {
            // Auto-sync enrollments for all class meetings
            $meetings = Meeting::where('class_id', $student->class_id)
                ->whereDoesntHave('enrollments', function($query) use ($user) {
                    $query->where('student_id', $user->user_id);
                })
                ->get();
            
            foreach ($meetings as $meeting) {
                $this->enrollmentService->syncEnrollmentsForMeeting($meeting);
            }
        }
        
        $enrollments = MeetingEnrollment::where('student_id', auth()->id())
            ->with('meeting.teacher')
            ->latest()
            ->paginate(20);
        return view('student.meetings.index', compact('enrollments'));
    }
    
    public function join($id)
    {
        $enrollment = MeetingEnrollment::with('meeting')
            ->where('meeting_id', $id)
            ->where('student_id', auth()->id())
            ->firstOrFail();
        
        return view('student.meetings.join', compact('enrollment'));
    }
    
    public function recordJoin(Request $request, $id)
    {
        $enrollment = MeetingEnrollment::where('meeting_id', $id)
            ->where('student_id', auth()->id())
            ->firstOrFail();
        
        if (!$enrollment->joined_at) {
            $enrollment->update(['joined_at' => now()]);
            
            $service = new AttendanceTrackingService();
            $service->createConfirmationSchedule($enrollment);
        }
        
        return response()->json(['success' => true]);
    }
    
    public function confirmAttendance(Request $request, $confirmationId)
    {
        $confirmation = AttendanceConfirmation::findOrFail($confirmationId);
        
        // Verify the confirmation belongs to the authenticated student
        if ($confirmation->enrollment->student_id !== auth()->id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }
        
        $service = new AttendanceTrackingService();
        $service->recordConfirmation($confirmation, $request->input('is_confirmed', true));
        
        return response()->json(['success' => true]);
    }
}
