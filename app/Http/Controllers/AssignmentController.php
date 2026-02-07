<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Assignment;
use App\Models\Level;
use App\Models\StudentClass;
use App\Models\TeacherScheduleEvent;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class AssignmentController extends Controller
{
    // Show upload form and list for teachers
    public function index(Request $request)
    {
        $query = Assignment::where('teacher_id', Auth::id());
        
        // Filter by class if provided
        if ($request->has('class_id') && $request->class_id) {
            $query->where('class_id', $request->class_id);
        }
        
        $assignments = $query->latest()->get();
        $levels = Level::all();
        $classes = StudentClass::where('teacher_id', Auth::id())->get();

        // For each assignment, get students in the class and their submissions
        foreach ($assignments as $assignment) {
            $class = $assignment->studentClass;
            $students = $class ? $class->students()->with('user')->get() : collect();
            $submissions = $assignment->submissions()->get();
            $submittedStudentIds = $submissions->pluck('student_id')->toArray();
            $assignment->submitted_students = $students->whereIn('student_id', $submittedStudentIds);
            $assignment->unsubmitted_students = $students->whereNotIn('student_id', $submittedStudentIds);
            $assignment->submissions = $submissions->keyBy('student_id');
        }

        return view('assignments.teacher', compact('assignments', 'levels', 'classes'));
    }

    // Show create assignment form
    public function create()
    {
        $levels = Level::all();
        $classes = StudentClass::where('teacher_id', Auth::id())->get();
        return view('assignments.create', compact('levels', 'classes'));
    }

    // Handle upload
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'file' => 'required|file',
            'level_id' => 'required|exists:levels,level_id',
            'class_id' => 'required|exists:student_classes,class_id',
            'due_date' => 'required|date|after_or_equal:today',
        ]);
        $path = $request->file('file')->store('assignments', 'public');
        $dueDate = Carbon::parse($request->due_date);
        
        $assignment = Assignment::create([
            'teacher_id' => Auth::id(),
            'title' => $request->title,
            'description' => $request->description,
            'file_path' => $path,
            'level_id' => $request->level_id,
            'class_id' => $request->class_id,
            'due_date' => $dueDate,
        ]);
        
        // Create schedule event for assignment deadline
        TeacherScheduleEvent::create([
            'teacher_id' => Auth::id(),
            'title' => 'Assignment Deadline: ' . $assignment->title,
            'description' => 'Assignment deadline for ' . $assignment->title . ($assignment->description ? ': ' . $assignment->description : ''),
            'event_date' => $dueDate->format('Y-m-d'),
            'event_time' => $dueDate->format('H:i'),
            'event_type' => 'assignment',
            'color' => '#4ECDC4', // Teal color for assignments
            'is_active' => true,
        ]);
        
        return redirect()->route('assignments.index')->with('success', 'Assignment uploaded!');
    }

    // Show all assignments for students
    public function studentIndex()
    {
        $user = Auth::user();
        $student = $user->student;
        
        // Only show assignments for the student's class
        if ($student && $student->class_id) {
            $assignments = Assignment::where('class_id', $student->class_id)
                ->with(['submissions' => function($q) use ($student) {
                    $q->where('student_id', $student->student_id);
                }])->latest()->get();
        } else {
            // If student doesn't have a class, show empty list
            $assignments = collect();
        }
        
        return view('assignments.student', compact('assignments', 'student'));
    }
    // Show edit form
    public function edit(Assignment $assignment)
    {
        // Ensure teacher owns this assignment
        if ($assignment->teacher_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $levels = Level::all();
        $classes = StudentClass::where('teacher_id', Auth::id())->get();
        return view('assignments.edit', compact('assignment', 'levels', 'classes'));
    }

    // Handle update
    public function update(Request $request, Assignment $assignment)
    {
        // Ensure teacher owns this assignment
        if ($assignment->teacher_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'file' => 'nullable|file',
            'level_id' => 'required|exists:levels,level_id',
            'class_id' => 'required|exists:student_classes,class_id',
            'due_date' => 'required|date|after_or_equal:today',
        ]);

        // Find existing schedule event for this assignment
        $existingEvent = TeacherScheduleEvent::where('teacher_id', Auth::id())
            ->where('title', 'LIKE', 'Assignment Deadline: ' . $assignment->title . '%')
            ->where('event_type', 'assignment')
            ->first();

        $dueDate = Carbon::parse($request->due_date);
        
        $updateData = [
            'title' => $request->title,
            'description' => $request->description,
            'level_id' => $request->level_id,
            'class_id' => $request->class_id,
            'due_date' => $dueDate,
        ];

        // Only update file if a new one is provided
        if ($request->hasFile('file')) {
            // Delete old file if exists
            if ($assignment->file_path && Storage::disk('public')->exists($assignment->file_path)) {
                Storage::disk('public')->delete($assignment->file_path);
            }
            $path = $request->file('file')->store('assignments', 'public');
            $updateData['file_path'] = $path;
        }

        $assignment->update($updateData);
        
        // Update or create schedule event for assignment deadline
        if ($existingEvent) {
            $existingEvent->update([
                'title' => 'Assignment Deadline: ' . $assignment->title,
                'description' => 'Assignment deadline for ' . $assignment->title . ($assignment->description ? ': ' . $assignment->description : ''),
                'event_date' => $dueDate->format('Y-m-d'),
                'event_time' => $dueDate->format('H:i'),
            ]);
        } else {
            TeacherScheduleEvent::create([
                'teacher_id' => Auth::id(),
                'title' => 'Assignment Deadline: ' . $assignment->title,
                'description' => 'Assignment deadline for ' . $assignment->title . ($assignment->description ? ': ' . $assignment->description : ''),
                'event_date' => $dueDate->format('Y-m-d'),
                'event_time' => $dueDate->format('H:i'),
                'event_type' => 'assignment',
                'color' => '#4ECDC4',
                'is_active' => true,
            ]);
        }
        
        return redirect()->route('assignments.index')->with('success', 'Assignment updated successfully!');
    }

    // Handle delete
    public function destroy(Assignment $assignment)
    {
        // Ensure teacher owns this assignment
        if ($assignment->teacher_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        // Delete associated file if exists
        if ($assignment->file_path && Storage::disk('public')->exists($assignment->file_path)) {
            Storage::disk('public')->delete($assignment->file_path);
        }

        // Delete associated submissions and their files
        foreach ($assignment->submissions as $submission) {
            if ($submission->submission_file_url && Storage::disk('public')->exists($submission->submission_file_url)) {
                Storage::disk('public')->delete($submission->submission_file_url);
            }
        }
        
        // Delete associated schedule event
        TeacherScheduleEvent::where('teacher_id', Auth::id())
            ->where('title', 'LIKE', 'Assignment Deadline: ' . $assignment->title . '%')
            ->where('event_type', 'assignment')
            ->delete();

        $assignment->delete();
        return redirect()->route('assignments.index')->with('success', 'Assignment deleted successfully!');
    }

    // Set Dead Time for an assignment
    public function setDeadTime(Request $request, Assignment $assignment)
    {
        $request->validate([
            'dead_time' => 'required|date|after_or_equal:now',
        ]);
        $assignment->dead_time = $request->dead_time;
        $assignment->save();
        return redirect()->back()->with('success', 'Dead time set successfully!');
    }
}
