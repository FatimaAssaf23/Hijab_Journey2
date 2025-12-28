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
        $levels = \App\Models\Level::with(['lessons'])->get();
        // For each lesson, check if it's visible for students (by default locked)
        foreach ($levels as $level) {
            foreach ($level->lessons as $lesson) {
                $visibility = ClassLessonVisibility::where('lesson_id', $lesson->lesson_id)->first();
                $lesson->is_visible = $visibility ? $visibility->is_visible : false;
            }
        }
        return view('teacher.lessons', compact('levels'));
    }

    // Unlock lesson for students
    public function unlock($lesson_id)
    {
        $visibility = ClassLessonVisibility::firstOrCreate([
            'lesson_id' => $lesson_id
        ]);
        $visibility->is_visible = true;
        $visibility->save();
        return back()->with('success', 'Lesson unlocked for students!');
    }

    // Lock lesson for students
    public function lock($lesson_id)
    {
        $visibility = ClassLessonVisibility::firstOrCreate([
            'lesson_id' => $lesson_id
        ]);
        $visibility->is_visible = false;
        $visibility->save();
        return back()->with('success', 'Lesson locked for students!');
    }

    // View lesson content
    public function view($lesson_id)
    {
        $lesson = \App\Models\Lesson::findOrFail($lesson_id);
        return view('teacher.lesson-view', compact('lesson'));
    }
}
