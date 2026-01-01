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
        $teacher = Auth::user();
        $teacherClasses = \App\Models\StudentClass::where('teacher_id', $teacher->user_id)->get();
        $levels = \App\Models\Level::all();
        // For each level, get all lessons assigned to that level (regardless of uploader)
        foreach ($levels as $level) {
            $level->lessons = \App\Models\Lesson::where('level_id', $level->level_id)->get();
            foreach ($level->lessons as $lesson) {
                $visibility = ClassLessonVisibility::where('lesson_id', $lesson->lesson_id)->first();
                $lesson->is_visible = $visibility ? $visibility->is_visible : false;
            }
        }
        return view('teacher.lessons', compact('levels', 'teacherClasses'));
    }

    // Unlock lesson for students
    public function unlock(Request $request, $lesson_id)
    {
        $request->validate([
            'class_id' => 'required|exists:student_classes,class_id',
        ]);
        $class_id = $request->input('class_id');
        $teacher_id = Auth::id();
        $visibility = ClassLessonVisibility::firstOrCreate([
            'lesson_id' => $lesson_id,
            'class_id' => $class_id,
            'teacher_id' => $teacher_id,
        ]);
        $visibility->is_visible = true;
        $visibility->save();
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
        $visibility = ClassLessonVisibility::firstOrCreate([
            'lesson_id' => $lesson_id,
            'class_id' => $class_id,
            'teacher_id' => $teacher_id,
        ]);
        $visibility->is_visible = false;
        $visibility->save();
        return back()->with('success', 'Lesson hidden from students!');
    }

    // View lesson content
    public function view($lesson_id)
    {
        $lesson = \App\Models\Lesson::findOrFail($lesson_id);
        return view('teacher.lesson-view', compact('lesson'));
    }
}
