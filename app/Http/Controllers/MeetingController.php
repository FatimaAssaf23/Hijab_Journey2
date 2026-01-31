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
            // Students see meetings for their class
            $student = $user->student;
            if ($student && $student->class_id) {
                $meetings = Meeting::where('class_id', $student->class_id)
                    ->with(['studentClass', 'teacher'])
                    ->orderBy('start_time', 'desc')
                    ->get();
            }
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

        // Get attendance for student if they're a student
        $attendance = null;
        if ($user->role === 'student' && $user->student) {
            $attendance = MeetingAttendance::where('meeting_id', $meeting->meeting_id)
                ->where('student_id', $user->student->student_id)
                ->first();
            
            // Fix: If student is currently in meeting (has joined_at but no leave_time)
            // but status is 'absent', reset to 'pending'
            if ($attendance && $attendance->joined_at && !$attendance->leave_time && $attendance->status === 'absent') {
                $attendance->status = 'pending';
                $attendance->save();
            }
        }

        // Get all attendance records for teachers
        $attendances = collect([]);
        $allStudents = collect([]);
        if ($user->role === 'teacher') {
            $attendances = MeetingAttendance::where('meeting_id', $meeting->meeting_id)
                ->with(['student.user'])
                ->orderByRaw('COALESCE(joined_at, join_time) ASC')
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

        // Check if already joined (and hasn't left yet)
        $existingAttendance = MeetingAttendance::where('meeting_id', $meeting->meeting_id)
            ->where('student_id', $student->student_id)
            ->first();

        if ($existingAttendance) {
            // If they have left (leave_time is set), allow them to rejoin
            if ($existingAttendance->leave_time) {
                // Reset attendance for re-joining
                $existingAttendance->joined_at = now();
                $existingAttendance->join_time = now(); // Keep for backward compatibility
                $existingAttendance->leave_time = null;
                $existingAttendance->duration_minutes = null;
                $existingAttendance->status = 'pending'; // Reset to pending when rejoining
                $existingAttendance->last_confirmed_at = null;
                $existingAttendance->save();
                
                // Delete old check responses when rejoining
                $existingAttendance->checkResponses()->delete();
                
                return response()->json([
                    'success' => true,
                    'message' => 'Successfully rejoined the meeting.',
                    'attendance' => $existingAttendance
                ], 200);
            } else {
                // They're already in the meeting
                return response()->json([
                    'error' => 'You have already joined this meeting.',
                    'attendance' => $existingAttendance
                ], 400);
            }
        }

        // Create attendance record with automatic attendance system
        $attendance = MeetingAttendance::create([
            'meeting_id' => $meeting->meeting_id,
            'student_id' => $student->student_id,
            'join_time' => now(), // Keep for backward compatibility
            'joined_at' => now(), // New field for automatic attendance
            'status' => 'pending', // Start with pending status
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
        $joinTime = $attendance->joined_at ?? $attendance->join_time;
        $leaveTime = $attendance->leave_time;
        $duration = $joinTime->diffInMinutes($leaveTime);
        $attendance->duration_minutes = $duration;

        // Handle automatic attendance system status
        // If student is using automatic attendance (has joined_at)
        if ($attendance->joined_at) {
            // Calculate final status based on all check responses
            $finalStatus = $attendance->calculateFinalStatus();
            
            // Only update status if there are check responses
            // If no check responses, keep as 'pending' (they left before any checks)
            if ($attendance->checkResponses()->count() > 0) {
                $attendance->status = $finalStatus;
            } else {
                // No check responses - they left before any checks occurred
                // Keep as 'pending' rather than marking as 'absent'
                // This allows them to rejoin and have a fresh start
                $attendance->status = 'pending';
            }
        } else {
            // Legacy system: Determine if on time or late (compare join_time with meeting start_time)
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
     * Confirm student presence (for automatic attendance system)
     */
    public function confirmPresence(Meeting $meeting)
    {
        $user = Auth::user();

        // Only students can confirm presence
        if ($user->role !== 'student') {
            return response()->json(['error' => 'Only students can confirm presence.'], 403);
        }

        $student = $user->student;
        if (!$student) {
            return response()->json(['error' => 'Student profile not found.'], 404);
        }

        // Check if student belongs to the meeting's class
        if ($student->class_id !== $meeting->class_id) {
            return response()->json(['error' => 'You can only confirm presence for meetings in your class.'], 403);
        }

        // Find or create attendance record
        $attendance = MeetingAttendance::where('meeting_id', $meeting->meeting_id)
            ->where('student_id', $student->student_id)
            ->first();

        if (!$attendance) {
            // Create attendance record if it doesn't exist
            $attendance = MeetingAttendance::create([
                'meeting_id' => $meeting->meeting_id,
                'student_id' => $student->student_id,
                'joined_at' => now(),
                'last_confirmed_at' => now(),
                'status' => 'pending',
            ]);
        }

        // Get the next check number
        $nextCheckNumber = $attendance->checkResponses()->max('check_number') ?? 0;
        $nextCheckNumber += 1;

        // Record this check response
        \App\Models\AttendanceCheckResponse::create([
            'attendance_id' => $attendance->attendance_id,
            'check_number' => $nextCheckNumber,
            'response' => 'present',
            'checked_at' => now(),
        ]);

        // Update last confirmed time
        $attendance->last_confirmed_at = now();
        
        // Calculate and update final status based on all checks
        $finalStatus = $attendance->calculateFinalStatus();
        $attendance->status = $finalStatus;
        $attendance->save();

        return response()->json([
            'success' => true,
            'message' => 'Presence confirmed.',
            'attendance' => $attendance,
            'check_number' => $nextCheckNumber,
            'final_status' => $finalStatus
        ], 200);
    }

    /**
     * Mark student as absent or no response (for automatic attendance system)
     */
    public function markAbsent(Meeting $meeting, Request $request)
    {
        $user = Auth::user();

        // Only students can be marked absent (or system can mark them)
        if ($user->role !== 'student') {
            return response()->json(['error' => 'Only students can be marked absent.'], 403);
        }

        $student = $user->student;
        if (!$student) {
            return response()->json(['error' => 'Student profile not found.'], 404);
        }

        // Check if student belongs to the meeting's class
        if ($student->class_id !== $meeting->class_id) {
            return response()->json(['error' => 'You can only be marked absent for meetings in your class.'], 403);
        }

        // Find attendance record
        $attendance = MeetingAttendance::where('meeting_id', $meeting->meeting_id)
            ->where('student_id', $student->student_id)
            ->first();

        if (!$attendance) {
            // Create attendance record with absent status
            $attendance = MeetingAttendance::create([
                'meeting_id' => $meeting->meeting_id,
                'student_id' => $student->student_id,
                'joined_at' => now(),
                'status' => 'pending',
            ]);
        }

        // Get the next check number
        $nextCheckNumber = $attendance->checkResponses()->max('check_number') ?? 0;
        $nextCheckNumber += 1;

        // Determine response type: 'absent' (student clicked button) or 'no_response' (auto-closed)
        $responseType = $request->input('response_type', 'absent'); // 'absent' or 'no_response'
        if (!in_array($responseType, ['absent', 'no_response'])) {
            $responseType = 'absent';
        }

        // Record this check response
        \App\Models\AttendanceCheckResponse::create([
            'attendance_id' => $attendance->attendance_id,
            'check_number' => $nextCheckNumber,
            'response' => $responseType,
            'checked_at' => now(),
        ]);

        // Calculate and update final status based on all checks
        $finalStatus = $attendance->calculateFinalStatus();
        $attendance->status = $finalStatus;
        $attendance->save();

        return response()->json([
            'success' => true,
            'message' => 'Marked as absent for this check.',
            'attendance' => $attendance,
            'check_number' => $nextCheckNumber,
            'final_status' => $finalStatus
        ], 200);
    }
}
