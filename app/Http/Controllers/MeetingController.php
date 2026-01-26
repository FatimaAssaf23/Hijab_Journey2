<?php

namespace App\Http\Controllers;

use App\Models\Meeting;
use App\Models\MeetingAttendance;
use App\Models\StudentClass;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class MeetingController extends Controller
{
    /**
     * Show form to create a meeting (teachers only)
     */
    public function create()
    {
        $user = Auth::user();
        
        // Check if user is a teacher
        if ($user->role !== 'teacher') {
            abort(403, 'Only teachers can create meetings.');
        }

        // Get teacher's classes
        $classes = StudentClass::where('teacher_id', $user->user_id)->get();

        return view('meetings.create', compact('classes'));
    }

    /**
     * Store a new meeting
     */
    public function store(Request $request)
    {
        $user = Auth::user();

        // Validate user is teacher
        if ($user->role !== 'teacher') {
            return redirect()->back()->with('error', 'Only teachers can create meetings.');
        }

        // Validate input
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'class_id' => 'required|exists:student_classes,class_id',
            'date' => 'required|date',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'google_meet_link' => ['required', 'url', 'regex:#^https://(meet\.google\.com|.*\.google\.com/.*meet)#i'],
            'description' => 'nullable|string',
        ], [
            'google_meet_link.regex' => 'Please enter a valid Google Meet link (must start with https://meet.google.com)',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            // Combine date and time for start_time
            $startDateTime = new \DateTime(
                $request->date . ' ' . $request->start_time
            );
            
            // Combine date and time for end_time
            $endDateTime = new \DateTime(
                $request->date . ' ' . $request->end_time
            );

            // Calculate duration in minutes
            $durationMinutes = $startDateTime->diff($endDateTime)->i + 
                              ($startDateTime->diff($endDateTime)->h * 60);

            // Create meeting
            Meeting::create([
                'teacher_id' => $user->user_id,
                'class_id' => $request->class_id,
                'title' => $request->title,
                'description' => $request->description,
                'google_meet_link' => $request->google_meet_link,
                'start_time' => $startDateTime,
                'end_time' => $endDateTime,
                'scheduled_at' => $startDateTime, // Keep for backward compatibility
                'duration_minutes' => $durationMinutes,
                'status' => 'scheduled',
            ]);

            return redirect()->route('meetings.index')
                ->with('success', 'Meeting created successfully!');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to create meeting: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Display all meetings for authenticated user
     */
    public function index()
    {
        $user = Auth::user();
        $meetings = [];

        if ($user->role === 'teacher') {
            // Teachers see meetings they created
            $meetings = Meeting::where('teacher_id', $user->user_id)
                ->with(['studentClass', 'teacher'])
                ->orderBy('start_time', 'desc')
                ->get();
        } elseif ($user->role === 'student') {
            // Students see meetings for their class
            $student = $user->student;
            if ($student && $student->class_id) {
                $meetings = Meeting::where('class_id', $student->class_id)
                    ->with(['studentClass', 'teacher'])
                    ->orderBy('start_time', 'desc')
                    ->get();
            }
        }

        return view('meetings.index', compact('meetings'));
    }

    /**
     * Show a specific meeting
     */
    public function show(Meeting $meeting)
    {
        $user = Auth::user();

        // Authorization check
        if ($user->role === 'teacher' && $meeting->teacher_id !== $user->user_id) {
            abort(403, 'You can only view your own meetings.');
        }

        if ($user->role === 'student') {
            $student = $user->student;
            if (!$student || $student->class_id !== $meeting->class_id) {
                abort(403, 'You can only view meetings for your class.');
            }
        }

        $meeting->load(['studentClass', 'teacher']);

        // Get attendance for student if they're a student
        $attendance = null;
        if ($user->role === 'student' && $user->student) {
            $attendance = MeetingAttendance::where('meeting_id', $meeting->meeting_id)
                ->where('student_id', $user->student->student_id)
                ->first();
        }

        // Get all attendance records for teachers
        $attendances = collect([]);
        $allStudents = collect([]);
        if ($user->role === 'teacher') {
            $attendances = MeetingAttendance::where('meeting_id', $meeting->meeting_id)
                ->with(['student.user'])
                ->orderBy('join_time', 'asc')
                ->get();
            
            // Get all students in the class to show who hasn't attended
            $allStudents = \App\Models\Student::where('class_id', $meeting->class_id)
                ->with('user')
                ->get();
        }

        return view('meetings.show', compact('meeting', 'attendance', 'attendances', 'allStudents'));
    }

    /**
     * Student joins a meeting
     */
    public function join(Meeting $meeting)
    {
        $user = Auth::user();

        // Only students can join
        if ($user->role !== 'student') {
            return response()->json(['error' => 'Only students can join meetings.'], 403);
        }

        $student = $user->student;
        if (!$student) {
            return response()->json(['error' => 'Student profile not found.'], 404);
        }

        // Check if student belongs to the meeting's class
        if ($student->class_id !== $meeting->class_id) {
            return response()->json(['error' => 'You can only join meetings for your class.'], 403);
        }

        // Check if already joined
        $existingAttendance = MeetingAttendance::where('meeting_id', $meeting->meeting_id)
            ->where('student_id', $student->student_id)
            ->first();

        if ($existingAttendance) {
            return response()->json([
                'error' => 'You have already joined this meeting.',
                'attendance' => $existingAttendance
            ], 400);
        }

        // Create attendance record
        $attendance = MeetingAttendance::create([
            'meeting_id' => $meeting->meeting_id,
            'student_id' => $student->student_id,
            'join_time' => now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Successfully joined the meeting.',
            'attendance' => $attendance
        ], 200);
    }

    /**
     * Student leaves a meeting
     */
    public function leave(Request $request, Meeting $meeting)
    {
        $user = Auth::user();

        // Only students can leave
        if ($user->role !== 'student') {
            if ($request->wantsJson() || $request->expectsJson()) {
                return response()->json(['error' => 'Only students can leave meetings.'], 403);
            }
            return redirect()->back()->with('error', 'Only students can leave meetings.');
        }

        $student = $user->student;
        if (!$student) {
            if ($request->wantsJson() || $request->expectsJson()) {
                return response()->json(['error' => 'Student profile not found.'], 404);
            }
            return redirect()->back()->with('error', 'Student profile not found.');
        }

        // Find attendance record
        $attendance = MeetingAttendance::where('meeting_id', $meeting->meeting_id)
            ->where('student_id', $student->student_id)
            ->first();

        if (!$attendance) {
            if ($request->wantsJson() || $request->expectsJson()) {
                return response()->json(['error' => 'You have not joined this meeting.'], 404);
            }
            return redirect()->back()->with('error', 'You have not joined this meeting.');
        }

        if ($attendance->leave_time) {
            if ($request->wantsJson() || $request->expectsJson()) {
                return response()->json(['error' => 'You have already left this meeting.'], 400);
            }
            return redirect()->back()->with('error', 'You have already left this meeting.');
        }

        // Update leave time
        $attendance->leave_time = now();

        // Calculate duration in minutes
        $joinTime = $attendance->join_time;
        $leaveTime = $attendance->leave_time;
        $duration = $joinTime->diffInMinutes($leaveTime);
        $attendance->duration_minutes = $duration;

        // Determine if on time or late (compare join_time with meeting start_time)
        if ($meeting->start_time) {
            // Allow 5 minutes grace period
            $gracePeriod = 5;
            $lateThreshold = $meeting->start_time->copy()->addMinutes($gracePeriod);
            
            if ($attendance->join_time <= $lateThreshold) {
                $attendance->status = 'on_time';
            } else {
                $attendance->status = 'late';
            }
        }

        $attendance->save();

        // Return appropriate response based on request type
        if ($request->wantsJson() || $request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Successfully left the meeting.',
                'attendance' => $attendance
            ], 200);
        }

        return redirect()->back()->with('success', 'You have left the meeting.');
    }
}
