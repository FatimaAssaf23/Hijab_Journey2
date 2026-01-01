<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\StudentClass;

class TeacherClassesController extends Controller
{
    public function index()
    {
        $teacher = Auth::user();
        // Get classes assigned to this teacher, with students and their user info
        $classes = StudentClass::with(['students.user'])->where('teacher_id', $teacher->user_id)->get();
        return view('teacher.classes', compact('classes'));
    }
}
