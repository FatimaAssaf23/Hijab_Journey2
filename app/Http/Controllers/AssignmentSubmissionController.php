<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AssignmentSubmission;
use Illuminate\Support\Facades\Auth;

class AssignmentSubmissionController extends Controller
{
    public function destroy($submissionId)
    {
        $user = Auth::user();
        $student = $user->student;
        $submission = AssignmentSubmission::where('submission_id', $submissionId)
            ->where('student_id', $student ? $student->student_id : 0)
            ->firstOrFail();
        // Delete the file from storage
        if ($submission->submission_file_url) {
            \Storage::disk('public')->delete($submission->submission_file_url);
        }
        $submission->delete();
        return redirect()->back()->with('success', 'Submission deleted successfully!');
    }
    public function store(Request $request)
    {
        $request->validate([
            'assignment_id' => 'required|exists:assignments,assignment_id',
            'submission_file' => 'required|file',
        ]);

        $user = Auth::user();
        $student = $user->student;
        if (!$student) {
            abort(403, 'Only students can submit assignments.');
        }

        $path = $request->file('submission_file')->store('assignment_submissions', 'public');

        AssignmentSubmission::updateOrCreate(
            [
                'assignment_id' => $request->assignment_id,
                'student_id' => $student->student_id,
            ],
            [
                'submission_file_url' => $path,
                'submitted_at' => now(),
                'status' => 'submitted',
                'is_late' => false, // You can add logic to check if late
            ]
        );

        return redirect()->back()->with('success', 'Assignment submitted successfully!');
    }
}
