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
        // Filter out teachers and admins from the student list - only show actual students
        $classes = StudentClass::with(['students' => function($query) {
            $query->whereHas('user', function($q) {
                // Exclude users who are teachers (by role or by having a teacher record) or admins
                $q->where('role', '!=', 'teacher')
                  ->where('role', '!=', 'admin')
                  ->whereDoesntHave('teacher'); // Exclude users who have a teacher profile
            });
        }, 'students.user'])->where('teacher_id', $teacher->user_id)->get();
        
        return view('teacher.classes', compact('classes'));
    }
}
