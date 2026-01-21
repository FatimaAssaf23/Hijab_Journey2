<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Level;
use App\Models\Lesson;
use App\Models\StudentLessonProgress;
use App\Models\StudentGameProgress;
use App\Models\QuizAttempt;
use App\Models\AssignmentSubmission;
use App\Models\Assignment;
use App\Models\Grade;

class StudentProgressController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $student = $user->student;
        
        if (!$student) {
            return redirect()->route('student.dashboard')
                ->with('error', 'Student profile not found.');
        }
        
        // Get levels with their lessons ordered by level_number and lesson_order
        $levels = Level::with(['lessons' => function($query) {
            $query->orderBy('lesson_order', 'asc');
        }])
        ->orderBy('level_number', 'asc')
        ->get();
        
        // Get student progress for all lessons
        $studentProgress = StudentLessonProgress::where('student_id', $student->student_id)
            ->get()
            ->keyBy('lesson_id');
        
        // Calculate overall progress
        $totalLessons = 0;
        $completedLessons = 0;
        
        // Add progress status to each lesson
        foreach ($levels as $level) {
            foreach ($level->lessons as $lesson) {
                $totalLessons++;
                $progress = $studentProgress->get($lesson->lesson_id);
                $lesson->progress_status = $progress ? $progress->status : 'not_started';
                $lesson->is_completed = $progress && $progress->status === 'completed';
                if ($lesson->is_completed) {
                    $completedLessons++;
                }
            }
        }
        
        $overallProgress = $totalLessons > 0 ? round(($completedLessons / $totalLessons) * 100) : 0;
        
        // Get Games Statistics
        $gamesProgress = StudentGameProgress::where('student_id', $student->student_id)->get();
        $gamesStats = [
            'total' => $gamesProgress->count(),
            'completed' => $gamesProgress->where('status', 'completed')->count(),
            'in_progress' => $gamesProgress->where('status', 'in_progress')->count(),
            'average_score' => $gamesProgress->where('status', 'completed')->avg('score') ?? 0,
            'total_score' => $gamesProgress->where('status', 'completed')->sum('score'),
        ];
        
        // Get Quizzes Statistics
        $quizAttempts = QuizAttempt::where('student_id', $student->student_id)->get();
        $quizzesStats = [
            'total_attempts' => $quizAttempts->count(),
            'completed' => $quizAttempts->whereNotNull('submitted_at')->count(),
            'average_score' => $quizAttempts->whereNotNull('score')->avg('score') ?? 0,
            'highest_score' => $quizAttempts->max('score') ?? 0,
        ];
        
        // Get Assignments Statistics
        $assignments = Assignment::where('class_id', $student->class_id)->get();
        $submissions = AssignmentSubmission::where('student_id', $student->student_id)->get();
        $submittedAssignmentIds = $submissions->pluck('assignment_id')->toArray();
        
        // Get grades for assignment submissions
        $submissionIds = $submissions->pluck('submission_id')->toArray();
        $assignmentGrades = Grade::where('student_id', $student->student_id)
            ->whereNotNull('assignment_submission_id')
            ->whereIn('assignment_submission_id', $submissionIds)
            ->get();
        
        // Calculate average grade (using percentage if available, otherwise calculate from grade_value/max_grade)
        $averageGrade = 0;
        if ($assignmentGrades->count() > 0) {
            $totalPercentage = 0;
            $count = 0;
            foreach ($assignmentGrades as $grade) {
                if ($grade->percentage !== null) {
                    $totalPercentage += $grade->percentage;
                    $count++;
                } elseif ($grade->max_grade > 0) {
                    $totalPercentage += ($grade->grade_value / $grade->max_grade) * 100;
                    $count++;
                }
            }
            $averageGrade = $count > 0 ? round($totalPercentage / $count, 1) : 0;
        }
        
        $assignmentsStats = [
            'total' => $assignments->count(),
            'submitted' => $submissions->count(),
            'pending' => max(0, $assignments->count() - $submissions->count()),
            'completed_percentage' => $assignments->count() > 0 
                ? round(($submissions->count() / $assignments->count()) * 100) 
                : 0,
            'average_grade' => $averageGrade,
        ];
        
        return view('student.progress', compact('levels', 'student', 'gamesStats', 'quizzesStats', 'assignmentsStats', 'totalLessons', 'completedLessons', 'overallProgress'));
    }
}
