<?php

namespace App\Http\Controllers;

use App\Models\Meeting;
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

        return view('meetings.show', compact('meeting'));
    }
}
