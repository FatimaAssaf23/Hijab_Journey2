<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Lesson;
use App\Models\Level;

class LessonPublicController extends Controller
{
    public function index()
    {
        $levels = Level::all();
        $student = auth()->user();
        $studentClassId = $student->studentClass->class_id ?? null;
        $teacherId = $student->studentClass->teacher_id ?? null;
        $lessons = collect();
        if ($studentClassId && $teacherId) {
            $visibleLessonIds = \App\Models\ClassLessonVisibility::where('class_id', $studentClassId)
                ->where('teacher_id', $teacherId)
                ->where('is_visible', true)
                ->pluck('lesson_id');
            $lessons = \App\Models\Lesson::whereIn('lesson_id', $visibleLessonIds)->get();
        }
        return view('lessons', compact('levels', 'lessons'));
    }
}
