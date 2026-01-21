<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Assignment;
use App\Models\Level;
use App\Models\StudentClass;
use Illuminate\Support\Facades\Auth;

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
        Assignment::create([
            'teacher_id' => Auth::id(),
            'title' => $request->title,
            'description' => $request->description,
            'file_path' => $path,
            'level_id' => $request->level_id,
            'class_id' => $request->class_id,
            'due_date' => $request->due_date,
        ]);
        return redirect()->back()->with('success', 'Assignment uploaded!');
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
