<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Lesson;
use App\Models\ClassLessonVisibility;
use Illuminate\Support\Facades\Auth;

class TeacherLessonController extends Controller
{
    // Show all lessons for management
    public function index()
    {
        $teacher_id = Auth::id();
        // Get all classes for this teacher
        $classes = \App\Models\StudentClass::where('teacher_id', $teacher_id)->get();

        // Eager load lessons for each level, and for each lesson, eager load class visibilities
        $levels = \App\Models\Level::with(['lessons.classLessonVisibilities' => function($q) use ($teacher_id) {
            $q->where('teacher_id', $teacher_id);
        }])->get();

        // Pass classes to the view as well
        return view('teacher.lessons', compact('levels', 'classes'));
    }

    // Unlock lesson for students
    public function unlock(Request $request, $lesson_id)
    {
        $request->validate([
            'class_id' => 'required|exists:student_classes,class_id',
        ]);
        $class_id = $request->input('class_id');
        $teacher_id = Auth::id();
        $visibility = ClassLessonVisibility::updateOrCreate(
            [
                'lesson_id' => $lesson_id,
                'class_id' => $class_id,
            ],
            [
                'teacher_id' => $teacher_id,
                'is_visible' => true,
            ]
        );
        return back()->with('success', 'Lesson unlocked for students!');
    }

    // Lock lesson for students
    public function lock(Request $request, $lesson_id)
    {
        $request->validate([
            'class_id' => 'required|exists:student_classes,class_id',
        ]);
        $class_id = $request->input('class_id');
        $teacher_id = Auth::id();
        $visibility = ClassLessonVisibility::updateOrCreate(
            [
                'lesson_id' => $lesson_id,
                'class_id' => $class_id,
            ],
            [
                'teacher_id' => $teacher_id,
                'is_visible' => false,
            ]
        );
        return back()->with('success', 'Lesson hidden from students!');
    }

    // View lesson content
    public function view($lesson)
    {
        // Get lesson by ID (route parameter)
        if (is_numeric($lesson)) {
            $lesson = \App\Models\Lesson::findOrFail($lesson);
        } elseif (!$lesson instanceof \App\Models\Lesson) {
            $lesson = \App\Models\Lesson::findOrFail($lesson);
        }
        
        return view('teacher.lesson-view', compact('lesson'));
    }
}
