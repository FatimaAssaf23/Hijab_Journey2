<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Grade;

class GradeController extends Controller
{
    public function store(Request $request, $submissionId)
    {
        $request->validate([
            'grade_value' => 'required|numeric|min:0',
            'max_grade' => 'required|numeric|min:1',
            'feedback' => 'nullable|string',
        ]);

        $submission = \App\Models\AssignmentSubmission::findOrFail($submissionId);
        $teacherId = auth()->id();
        $grade = Grade::updateOrCreate(
            [
                'assignment_submission_id' => $submission->submission_id,
            ],
            [
                'student_id' => $submission->student_id,
                'teacher_id' => $teacherId,
                'grade_value' => $request->grade_value,
                'max_grade' => $request->max_grade,
                'percentage' => ($request->grade_value / $request->max_grade) * 100,
                'feedback' => $request->feedback,
                'graded_at' => now(),
            ]
        );

        return redirect()->back()->with('success', 'Grade saved successfully!');
    }
}
