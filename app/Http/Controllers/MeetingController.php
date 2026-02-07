<?php

namespace App\Http\Controllers;

use App\Models\Meeting;
use App\Models\MeetingAttendance;
use App\Models\StudentClass;
use App\Services\MeetingEnrollmentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class MeetingController extends Controller
{
    protected $enrollmentService;
    
    public function __construct(MeetingEnrollmentService $enrollmentService)
    {
        $this->enrollmentService = $enrollmentService;
    }
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
            'date' => 'required|date|after_or_equal:today',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'google_meet_link' => ['required', 'url', 'regex:#^https://(meet\.google\.com|.*\.google\.com/.*meet)#i'],
            'description' => 'nullable|string',
        ], [
            'google_meet_link.regex' => 'Please enter a valid Google Meet link (must start with https://meet.google.com)',
            'date.after_or_equal' => 'The meeting date must be today or a future date.',
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
            
            // Additional validation: ensure the combined datetime is in the future
            $now = new \DateTime();
            if ($startDateTime < $now) {
                return redirect()->back()
                    ->withErrors(['date' => 'The meeting date and time must be in the future.'])
                    ->withInput();
            }
            
            // Combine date and time for end_time
            $endDateTime = new \DateTime(
                $request->date . ' ' . $request->end_time
            );

            // Calculate duration in minutes
            $durationMinutes = $startDateTime->diff($endDateTime)->i + 
                              ($startDateTime->diff($endDateTime)->h * 60);

            // Create meeting
            $meeting = Meeting::create([
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

            // Automatically sync enrollments for all students in the class
            $this->enrollmentService->syncEnrollmentsForMeeting($meeting);

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
        $stats = [];

        if ($user->role === 'teacher') {
            // Teachers see meetings they created
            $meetings = Meeting::where('teacher_id', $user->user_id)
                ->with(['studentClass', 'teacher'])
                ->orderBy('start_time', 'desc')
                ->get();
            
            // Calculate statistics for teachers
            $stats = [
                'total' => $meetings->count(),
                'upcoming' => $meetings->filter(function($m) {
                    return $m->start_time && $m->start_time->isFuture();
                })->count(),
                'completed' => $meetings->filter(function($m) {
                    return $m->end_time && $m->end_time->isPast();
                })->count(),
                'today' => $meetings->filter(function($m) {
                    return $m->start_time && $m->start_time->isToday();
                })->count(),
            ];
        } elseif ($user->role === 'student') {
            // Use the enrollment service to get meetings - handles all logic internally
            $meetings = $this->enrollmentService->getMeetingsForStudent($user);
        }

        return view('meetings.index', compact('meetings', 'stats'));
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

        // Ensure meeting has enrollments (for backward compatibility)
        $this->enrollmentService->ensureMeetingHasEnrollments($meeting);

        // Ensure meeting has a verification code (generate if missing)
        if (empty($meeting->verification_code)) {
            $meeting->verification_code = strtoupper(substr(str_shuffle('ABCDEFGHJKLMNPQRSTUVWXYZ23456789'), 0, 6));
            $meeting->save();
        }

        // Get attendance for student if they're a student
        $attendance = null;
        if ($user->role === 'student' && $user->student) {
            // Try enrollment system first (with error handling)
            try {
                $enrollment = \App\Models\MeetingEnrollment::where('meeting_id', $meeting->meeting_id)
                    ->where('student_id', $user->user_id)
                    ->first();
            } catch (\Exception $e) {
                $enrollment = null; // Fall back to old system
            }
            
            if ($enrollment) {
                // Use enrollment data, but also check MeetingAttendance for verification data
                $meetingAttendance = MeetingAttendance::where('meeting_id', $meeting->meeting_id)
                    ->where('student_id', $user->student->student_id)
                    ->first();
                
                $attendance = (object)[
                    'status' => $enrollment->attendance_status,
                    'joined_at' => $enrollment->joined_at,
                    'join_time' => $enrollment->joined_at,
                    'leave_time' => $enrollment->left_at ?? ($meetingAttendance ? $meetingAttendance->leave_time : null),
                    'is_verified' => $meetingAttendance ? $meetingAttendance->is_verified : false,
                    'entered_code' => $meetingAttendance ? $meetingAttendance->entered_code : null,
                ];
            } else {
                // Fallback to old system
                $attendance = MeetingAttendance::where('meeting_id', $meeting->meeting_id)
                    ->where('student_id', $user->student->student_id)
                    ->first();
                
                // Fix: If student is currently in meeting (has join_time but no leave_time)
                // but status is 'absent', reset to 'pending'
                if ($attendance && $attendance->join_time && !$attendance->leave_time && $attendance->status === 'absent') {
                    $attendance->status = 'pending';
                    $attendance->save();
                }
            }
        }

        // Get all attendance records for teachers
        // Use MeetingAttendance for verification system
        $attendances = MeetingAttendance::where('meeting_id', $meeting->meeting_id)
            ->with('student.user')
            ->get();
        
        // Get all students in the class
        $allStudents = collect([]);
        if ($meeting->class_id) {
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

        // Check if enrollment exists (for enrollment system)
        $enrollment = null;
        try {
            $enrollment = \App\Models\MeetingEnrollment::where('meeting_id', $meeting->meeting_id)
                ->where('student_id', $user->user_id)
                ->first();
        } catch (\Exception $e) {
            // Enrollment system not available, continue with old system
        }

        // Always create or update MeetingAttendance record (required for verification)
        // This ensures the record exists even if they already joined
        $joinTime = now();
        
        // Use updateOrCreate to ensure record exists
        $attendance = MeetingAttendance::updateOrCreate(
            [
                'meeting_id' => $meeting->meeting_id,
                'student_id' => $student->student_id,
            ],
            [
                'join_time' => $joinTime,
                'status' => 'pending',
                'is_verified' => false,
                'entered_code' => null,
                'leave_time' => null,
                'verification_attempts' => 0,
            ]
        );
        
        // Always update join_time and reset verification status
        $attendance->join_time = $joinTime;
        $attendance->leave_time = null;
        $attendance->is_verified = false;
        $attendance->entered_code = null;
        // Reset verification attempts only if not already marked as absent
        if ($attendance->status !== 'absent') {
            $attendance->verification_attempts = 0;
        }
        
        // Determine if student is late or on time (compare join_time with meeting start_time)
        // Only set status if not already verified as 'present'
        if ($meeting->start_time && $attendance->status !== 'present') {
            // Allow 10 minutes grace period
            $gracePeriod = 10;
            $lateThreshold = $meeting->start_time->copy()->addMinutes($gracePeriod);
            
            if ($attendance->join_time <= $lateThreshold) {
                $attendance->status = 'on_time';
            } else {
                $attendance->status = 'late';
            }
        } else {
            // If no start_time or already present, set to pending
            $attendance->status = 'pending';
        }
        
        $attendance->save();
        
        // Get fresh instance from database to ensure it's saved
        $attendance = MeetingAttendance::find($attendance->attendance_id);

        // Update enrollment if exists (for enrollment system)
        if ($enrollment) {
            $enrollment->joined_at = $joinTime;
            $enrollment->left_at = null;
            $enrollment->save();
        }

        // Update enrollment if exists (for enrollment system)
        if ($enrollment) {
            $enrollment->joined_at = $joinTime;
            $enrollment->left_at = null;
            $enrollment->save();
        }

        return response()->json([
            'success' => true,
            'message' => 'Successfully joined the meeting. You can now enter the verification code.',
            'attendance' => [
                'attendance_id' => $attendance->attendance_id,
                'meeting_id' => $attendance->meeting_id,
                'student_id' => $attendance->student_id,
                'join_time' => $attendance->join_time ? $attendance->join_time->toDateTimeString() : null,
                'status' => $attendance->status,
                'is_verified' => $attendance->is_verified,
            ]
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

        // Only update status if not already verified as 'present'
        // If student verified their code, keep status as 'present'
        if ($attendance->status !== 'present' && !$attendance->is_verified) {
            // Determine if on time or late (compare join_time with meeting start_time)
            if ($meeting->start_time) {
                // Allow 10 minutes grace period
                $gracePeriod = 10;
                $lateThreshold = $meeting->start_time->copy()->addMinutes($gracePeriod);
                
                if ($attendance->join_time <= $lateThreshold) {
                    $attendance->status = 'on_time';
                } else {
                    $attendance->status = 'late';
                }
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

    /**
     * Manually mark attendance for a student (teachers only)
     */
    public function markAttendance(Request $request, Meeting $meeting)
    {
        $user = Auth::user();
        
        // Only teachers can mark attendance
        if ($user->role !== 'teacher' || $meeting->teacher_id !== $user->user_id) {
            if ($request->wantsJson() || $request->expectsJson()) {
                return response()->json(['error' => 'Only the meeting teacher can mark attendance.'], 403);
            }
            abort(403, 'Only the meeting teacher can mark attendance.');
        }
        
        $request->validate([
            'student_id' => 'required|exists:students,student_id',
            'status' => 'required|in:present,absent,late',
        ]);
        
        $student = \App\Models\Student::findOrFail($request->student_id);
        
        // Check if student belongs to the meeting's class
        if ($student->class_id !== $meeting->class_id) {
            if ($request->wantsJson() || $request->expectsJson()) {
                return response()->json(['error' => 'Student does not belong to this class.'], 400);
            }
            return redirect()->back()->with('error', 'Student does not belong to this class.');
        }
        
        if ($request->status === 'absent') {
            // Remove attendance record if marking as absent
            MeetingAttendance::where('meeting_id', $meeting->meeting_id)
                ->where('student_id', $student->student_id)
                ->delete();
            
            $message = 'Student marked as absent.';
        } else {
            // Create or update attendance record
            $attendance = MeetingAttendance::updateOrCreate(
                [
                    'meeting_id' => $meeting->meeting_id,
                    'student_id' => $student->student_id,
                ],
                [
                    'join_time' => $request->status === 'present' || $request->status === 'late' ? now() : null,
                    'status' => $request->status === 'late' ? 'late' : ($request->status === 'present' ? 'on_time' : null),
                    'leave_time' => null, // Will be set when student leaves
                    'duration_minutes' => null, // Will be calculated when student leaves
                    'verification_attempts' => 0,
                ]
            );
            
            // If marking as late, ensure status is set correctly
            if ($request->status === 'late' && $meeting->start_time) {
                $attendance->status = 'late';
                $attendance->save();
            }
            
            $message = 'Attendance marked successfully.';
        }
        
        if ($request->wantsJson() || $request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => $message,
            ], 200);
        }
        
        return redirect()->back()->with('success', $message);
    }

    /**
     * Export attendance to CSV (teachers only)
     */
    public function exportAttendance(Meeting $meeting)
    {
        $user = Auth::user();
        
        if ($user->role !== 'teacher' || $meeting->teacher_id !== $user->user_id) {
            abort(403, 'Only the meeting teacher can export attendance.');
        }
        
        $attendances = MeetingAttendance::where('meeting_id', $meeting->meeting_id)
            ->with(['student.user'])
            ->orderBy('join_time', 'asc')
            ->get();
        
        $allStudents = \App\Models\Student::where('class_id', $meeting->class_id)
            ->with('user')
            ->get();
        
        $filename = 'attendance_' . str_replace(' ', '_', $meeting->title) . '_' . now()->format('Y-m-d') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];
        
        // Add BOM for UTF-8 to ensure Excel displays correctly
        $callback = function() use ($meeting, $attendances, $allStudents) {
            $file = fopen('php://output', 'w');
            
            // Add UTF-8 BOM for Excel compatibility
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
            
            // Header row
            fputcsv($file, ['Student Name', 'Status', 'Join Time', 'Leave Time', 'Duration (minutes)', 'Attendance Status']);
            
            // Attended students
            foreach ($attendances as $attendance) {
                $studentName = $attendance->student->user->first_name . ' ' . $attendance->student->user->last_name;
                fputcsv($file, [
                    $studentName,
                    'Present',
                    $attendance->join_time ? $attendance->join_time->format('Y-m-d H:i:s') : '',
                    $attendance->leave_time ? $attendance->leave_time->format('Y-m-d H:i:s') : 'Still in meeting',
                    $attendance->duration_minutes ?? ($attendance->leave_time ? $attendance->join_time->diffInMinutes($attendance->leave_time) : ''),
                    $attendance->status ? ucfirst(str_replace('_', ' ', $attendance->status)) : ''
                ]);
            }
            
            // Absent students
            $attendedIds = $attendances->pluck('student_id')->toArray();
            foreach ($allStudents as $student) {
                if (!in_array($student->student_id, $attendedIds)) {
                    $studentName = $student->user->first_name . ' ' . $student->user->last_name;
                    fputcsv($file, [
                        $studentName,
                        'Absent',
                        '',
                        '',
                        '',
                        ''
                    ]);
                }
            }
            
            fclose($file);
        };
        
        return response()->stream($callback, 200, $headers);
    }

    /**
     * Student submits verification code
     */
    public function verifyCode(Request $request, Meeting $meeting)
    {
        $user = Auth::user();

        // Only students can verify codes
        if ($user->role !== 'student') {
            return response()->json(['error' => 'Only students can verify codes.'], 403);
        }

        $student = $user->student;
        if (!$student) {
            return response()->json(['error' => 'Student profile not found.'], 404);
        }

        // Check if student belongs to the meeting's class
        if ($student->class_id !== $meeting->class_id) {
            return response()->json(['error' => 'You can only verify codes for your class meetings.'], 403);
        }

        $request->validate([
            'code' => 'required|string|max:10',
        ]);

        // Check if student has joined - check MeetingAttendance first (primary system)
        // Try multiple queries to ensure we find the record
        $attendance = MeetingAttendance::where('meeting_id', $meeting->meeting_id)
            ->where('student_id', $student->student_id)
            ->first();

        // If still not found, try with fresh query (bypass cache)
        if (!$attendance) {
            $attendance = MeetingAttendance::where('meeting_id', $meeting->meeting_id)
                ->where('student_id', $student->student_id)
                ->lockForUpdate()
                ->first();
        }

        // If no attendance found, check enrollment system as fallback
        if (!$attendance) {
            try {
                $enrollment = \App\Models\MeetingEnrollment::where('meeting_id', $meeting->meeting_id)
                    ->where('student_id', $user->user_id)
                    ->first();
                
                // If enrollment exists with join time, create attendance record
                if ($enrollment && $enrollment->joined_at) {
                    $attendance = MeetingAttendance::create([
                        'meeting_id' => $meeting->meeting_id,
                        'student_id' => $student->student_id,
                        'join_time' => $enrollment->joined_at,
                        'status' => 'pending',
                        'is_verified' => false,
                        'verification_attempts' => 0,
                    ]);
                }
            } catch (\Exception $e) {
                // Enrollment system not available, continue
            }
        }

        // Last resort: If still not found, create it now (they must have joined if they're trying to verify)
        if (!$attendance) {
            // Create attendance record with current time as join_time
            $attendance = MeetingAttendance::create([
                'meeting_id' => $meeting->meeting_id,
                'student_id' => $student->student_id,
                'join_time' => now(),
                'status' => 'pending',
                'is_verified' => false,
                'verification_attempts' => 0,
            ]);
        }

        // Must have join_time to verify - if missing, set it to now
        if (!$attendance->join_time) {
            $attendance->join_time = now();
            $attendance->status = 'pending';
            $attendance->save();
        }
        
        // Refresh to ensure we have latest data
        $attendance->refresh();

        // Check if already verified
        if ($attendance->is_verified) {
            return response()->json([
                'success' => true,
                'message' => 'Code already verified.',
                'attendance' => $attendance
            ], 200);
        }

        // Check if student has exceeded verification attempts (2 attempts allowed)
        $verificationAttempts = $attendance->verification_attempts ?? 0;
        if ($verificationAttempts >= 2) {
            // Mark as absent if not already marked
            if ($attendance->status !== 'absent') {
                $attendance->status = 'absent';
                $attendance->save();

                // Also update enrollment system if it exists
                try {
                    $enrollment = \App\Models\MeetingEnrollment::where('meeting_id', $meeting->meeting_id)
                        ->where('student_id', $user->user_id)
                        ->first();
                    
                    if ($enrollment) {
                        $enrollment->attendance_status = 'absent';
                        $enrollment->save();
                    }
                } catch (\Exception $e) {
                    // Enrollment system not available, continue
                }
            }

            return response()->json([
                'success' => false,
                'error' => 'You have exceeded the maximum number of verification attempts (2). You have been marked as absent.',
                'attendance' => $attendance,
                'marked_absent' => true
            ], 400);
        }

        // Ensure meeting has a verification code (generate if missing)
        if (empty($meeting->verification_code)) {
            $meeting->verification_code = strtoupper(substr(str_shuffle('ABCDEFGHJKLMNPQRSTUVWXYZ23456789'), 0, 6));
            $meeting->save();
        }

        // Compare codes (case-insensitive)
        $enteredCode = strtoupper(trim($request->code));
        $meetingCode = strtoupper(trim($meeting->verification_code));

        if (empty($enteredCode)) {
            return response()->json([
                'success' => false,
                'error' => 'Please enter a verification code.',
            ], 400);
        }

        if (empty($meetingCode)) {
            return response()->json([
                'success' => false,
                'error' => 'Meeting verification code not set. Please contact your teacher.',
            ], 400);
        }

        if ($enteredCode === $meetingCode) {
            // Correct code - reset attempts and mark as verified
            $attendance->entered_code = $enteredCode;
            $attendance->is_verified = true;
            $attendance->status = 'present'; // Mark as present when code is verified
            $attendance->verification_attempts = 0; // Reset attempts on success
            $attendance->save();

            // Also update enrollment system if it exists
            try {
                $enrollment = \App\Models\MeetingEnrollment::where('meeting_id', $meeting->meeting_id)
                    ->where('student_id', $user->user_id)
                    ->first();
                
                if ($enrollment) {
                    $enrollment->attendance_status = 'present';
                    $enrollment->save();
                }
            } catch (\Exception $e) {
                // Enrollment system not available, continue
            }

            return response()->json([
                'success' => true,
                'message' => 'Verification code accepted! Your presence has been confirmed.',
                'attendance' => $attendance
            ], 200);
        } else {
            // Wrong code - increment attempts
            $attendance->entered_code = $enteredCode;
            $attendance->verification_attempts = $verificationAttempts + 1;
            $attendance->save();

            $remainingAttempts = 2 - $attendance->verification_attempts;
            
            // If this was the second attempt, mark as absent
            if ($attendance->verification_attempts >= 2) {
                $attendance->status = 'absent';
                $attendance->save();

                // Also update enrollment system if it exists
                try {
                    $enrollment = \App\Models\MeetingEnrollment::where('meeting_id', $meeting->meeting_id)
                        ->where('student_id', $user->user_id)
                        ->first();
                    
                    if ($enrollment) {
                        $enrollment->attendance_status = 'absent';
                        $enrollment->save();
                    }
                } catch (\Exception $e) {
                    // Enrollment system not available, continue
                }

                return response()->json([
                    'success' => false,
                    'error' => 'Invalid verification code. You have exceeded the maximum number of attempts (2). You have been marked as absent.',
                    'remaining_attempts' => 0,
                    'marked_absent' => true,
                    'attendance' => $attendance
                ], 400);
            }

            return response()->json([
                'success' => false,
                'error' => 'Invalid verification code. Please check and try again.',
                'remaining_attempts' => $remainingAttempts,
            ], 400);
        }
    }

    /**
     * Teacher views or regenerates verification code
     */
    public function getVerificationCode(Request $request, Meeting $meeting)
    {
        $user = Auth::user();

        // Only teachers can view verification codes
        if ($user->role !== 'teacher' || $meeting->teacher_id !== $user->user_id) {
            return response()->json(['error' => 'Only the meeting teacher can view verification codes.'], 403);
        }

        // Regenerate code if requested
        if ($request->has('regenerate') && $request->regenerate === 'true') {
            $meeting->verification_code = strtoupper(substr(str_shuffle('ABCDEFGHJKLMNPQRSTUVWXYZ23456789'), 0, 6));
            $meeting->save();
        }

        return response()->json([
            'success' => true,
            'verification_code' => $meeting->verification_code,
        ], 200);
    }

}
