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
        $lessons = Lesson::where('is_visible', true)->get();
        return view('lessons', compact('levels', 'lessons'));
    }
}
