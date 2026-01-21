<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\StudentClass;
use App\Models\Grade;
use App\Models\Student;
use App\Models\QuizAttempt;
use App\Models\StudentGameProgress;
use App\Models\StudentAnswer;

class TeacherGradeController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        if (!$user || $user->role !== 'teacher') {
            return redirect()->route('teacher.dashboard')
                ->with('error', 'Unauthorized access.');
        }
        
        // Get all classes taught by this teacher
        // IMPORTANT: Only load students with role 'student' to exclude teachers
        $classes = StudentClass::where('teacher_id', $user->user_id)
            ->with(['students' => function($query) {
                $query->whereHas('user', function($q) {
                    $q->where('role', 'student');
                });
            }, 'students.user', 'students.studentClass'])
            ->get();
        
        // Get all students from teacher's classes
        // Double-check to ensure only actual students are included
        $studentIds = [];
        $studentData = [];
        foreach ($classes as $class) {
            if ($class->students) {
                foreach ($class->students as $student) {
                    // Ensure the user associated with this student exists and has role 'student'
                    // This prevents teachers from appearing in the student grades list
                    if ($student->user && $student->user->role === 'student' && $student->student_id) {
                        $studentIds[] = $student->student_id;
                        $className = 'N/A';
                        if ($student->studentClass) {
                            $className = $student->studentClass->class_name ?? 'N/A';
                        } elseif ($student->class_id) {
                            // Fallback: try to get class name directly if relationship not loaded
                            $studentClass = StudentClass::find($student->class_id);
                            $className = $studentClass ? $studentClass->class_name : 'N/A';
                        }
                        $studentData[$student->student_id] = [
                            'student' => $student,
                            'student_name' => $student->user->first_name . ' ' . $student->user->last_name,
                            'class_name' => $className,
                        ];
                    }
                }
            }
        }
        
        if (empty($studentIds)) {
            return view('teacher.grades', ['studentGrades' => [], 'classes' => $classes]);
        }
        
        // Get all assignment grades for these students
        $assignmentGrades = Grade::whereIn('student_id', $studentIds)
            ->whereNotNull('assignment_submission_id')
            ->with(['student.user', 'student.studentClass', 'assignmentSubmission.assignment'])
            ->orderBy('graded_at', 'desc')
            ->get();
        
        // Get all quiz attempt grades (if they exist in Grade table)
        $quizAttemptGrades = Grade::whereIn('student_id', $studentIds)
            ->whereNotNull('quiz_attempt_id')
            ->with(['student.user', 'student.studentClass', 'quizAttempt.quiz'])
            ->get()
            ->keyBy('quiz_attempt_id'); // Key by quiz_attempt_id for quick lookup
        
        // Get all quiz attempts for these students (even if they don't have Grade records)
        $quizAttempts = QuizAttempt::whereIn('student_id', $studentIds)
            ->whereNotNull('submitted_at')
            ->with(['student.user', 'student.studentClass', 'quiz.questions', 'answers'])
            ->orderBy('submitted_at', 'desc')
            ->get();
        
        // Get all game progress for these students
        $gameProgresses = StudentGameProgress::whereIn('student_id', $studentIds)
            ->where('status', 'completed')
            ->whereNotNull('score')
            ->with(['student.user', 'student.studentClass', 'game.lesson'])
            ->orderBy('completed_at', 'desc')
            ->get();
        
        // Organize all grades by student
        $studentGrades = [];
        
        // Initialize student entries
        foreach ($studentData as $studentId => $data) {
            $studentGrades[$studentId] = [
                'student' => $data['student'],
                'student_name' => $data['student_name'],
                'class_name' => $data['class_name'],
                'grades' => [],
                'total_grades' => 0,
                'average_percentage' => 0,
            ];
        }
        
        // Add assignment grades
        foreach ($assignmentGrades as $grade) {
            $studentId = $grade->student_id;
            if (isset($studentGrades[$studentId])) {
                // Calculate percentage and ensure it's within 0-100 range
                $assignmentPercentage = $grade->percentage ?? ($grade->max_grade > 0 ? ($grade->grade_value / $grade->max_grade) * 100 : 0);
                $assignmentPercentage = min(100, max(0, $assignmentPercentage));
                
                $studentGrades[$studentId]['grades'][] = (object)[
                    'type' => 'assignment',
                    'grade_value' => $grade->grade_value,
                    'max_grade' => $grade->max_grade ?? 100,
                    'percentage' => round($assignmentPercentage, 2),
                    'feedback' => $grade->feedback,
                    'graded_at' => $grade->graded_at,
                    'item_name' => $grade->assignmentSubmission && $grade->assignmentSubmission->assignment 
                        ? $grade->assignmentSubmission->assignment->title 
                        : 'N/A',
                    'assignment_submission_id' => $grade->assignment_submission_id,
                    'quiz_attempt_id' => null,
                ];
                $studentGrades[$studentId]['total_grades']++;
            }
        }
        
        // Add quiz attempts (use Grade record if exists, otherwise use QuizAttempt data)
        foreach ($quizAttempts as $attempt) {
            $studentId = $attempt->student_id;
            if (isset($studentGrades[$studentId])) {
                // Check if there's a Grade record for this quiz attempt
                $grade = $quizAttemptGrades->get($attempt->attempt_id);
                
                // If Grade record exists, use it (it has proper grade_value and max_grade)
                if ($grade) {
                    $gradeValue = $grade->grade_value ?? 0;
                    $maxGrade = $grade->max_grade ?? 100;
                    $percentage = $grade->percentage ?? ($maxGrade > 0 ? ($gradeValue / $maxGrade) * 100 : 0);
                    $percentage = min(100, max(0, $percentage));
                    
                    $studentGrades[$studentId]['grades'][] = (object)[
                        'type' => 'quiz',
                        'grade_value' => $gradeValue,
                        'max_grade' => $maxGrade,
                        'percentage' => round($percentage, 2),
                        'feedback' => $grade->feedback ?? null,
                        'graded_at' => $grade->graded_at ?? $attempt->submitted_at,
                        'item_name' => $attempt->quiz ? $attempt->quiz->title : 'N/A',
                        'assignment_submission_id' => null,
                        'quiz_attempt_id' => $attempt->attempt_id,
                    ];
                } else {
                    // Recalculate score from actual student answers for accuracy
                    // This ensures we always get the correct score regardless of what's stored in quiz_attempts.score
                    $totalQuestions = 0;
                    $correctAnswers = 0;
                    
                    // Get total number of questions from the quiz
                    if ($attempt->quiz && $attempt->quiz->questions) {
                        $totalQuestions = $attempt->quiz->questions->count();
                    }
                    
                    // Count correct answers from StudentAnswer records
                    if ($attempt->answers && $attempt->answers->count() > 0) {
                        $correctAnswers = $attempt->answers->where('is_correct', true)->count();
                    }
                    
                    // Calculate accurate score and percentage
                    if ($totalQuestions > 0) {
                        // Score is the number of correct answers (points)
                        $score = $correctAnswers;
                        $maxScore = $totalQuestions;
                        // Percentage is (correct / total) * 100
                        $percentage = ($correctAnswers / $totalQuestions) * 100;
                    } else {
                        // Fallback: use stored score if we can't count questions
                        // Note: According to QuizController line 336, score is stored as a percentage (0-100)
                        // So if we have stored score, it's already a percentage
                        $storedScore = $attempt->score ?? 0;
                        
                        // If stored score is 0-100, it's likely a percentage
                        // Otherwise, try to use max_score
                        if ($storedScore >= 0 && $storedScore <= 100) {
                            // Stored score is a percentage
                            $percentage = $storedScore;
                            $maxScore = $attempt->quiz->max_score ?? 100;
                            // Convert percentage to points for display
                            $score = ($percentage / 100) * $maxScore;
                        } else {
                            // Treat as points
                            $maxScore = $attempt->quiz->max_score ?? 100;
                            $score = $storedScore;
                            $percentage = $maxScore > 0 ? ($score / $maxScore) * 100 : 0;
                        }
                    }
                    
                    // Ensure percentage is within valid range
                    $percentage = min(100, max(0, round($percentage, 2)));
                    $score = round($score, 2);
                    
                    $studentGrades[$studentId]['grades'][] = (object)[
                        'type' => 'quiz',
                        'grade_value' => $score,
                        'max_grade' => $maxScore,
                        'percentage' => $percentage,
                        'feedback' => null,
                        'graded_at' => $attempt->submitted_at,
                        'item_name' => $attempt->quiz ? $attempt->quiz->title : 'N/A',
                        'assignment_submission_id' => null,
                        'quiz_attempt_id' => $attempt->attempt_id,
                    ];
                }
                $studentGrades[$studentId]['total_grades']++;
            }
        }
        
        // Add game progress
        foreach ($gameProgresses as $progress) {
            $studentId = $progress->student_id;
            if (isset($studentGrades[$studentId])) {
                $gameName = 'Game';
                if ($progress->game) {
                    if ($progress->game->lesson && $progress->game->lesson->title) {
                        $gameName = $progress->game->lesson->title . ' - Game';
                    } else {
                        $gameName = 'Game #' . $progress->game_id;
                    }
                }
                
                // For games, we'll use the score as both grade_value and max_grade for display
                // Since games don't have a max_score, we'll show the score as a percentage
                $score = $progress->score ?? 0;
                
                $studentGrades[$studentId]['grades'][] = (object)[
                    'type' => 'game',
                    'grade_value' => $score,
                    'max_grade' => 100, // Display as percentage
                    'percentage' => min(100, max(0, $score)), // Ensure percentage is between 0-100
                    'feedback' => null,
                    'graded_at' => $progress->completed_at,
                    'item_name' => $gameName,
                    'assignment_submission_id' => null,
                    'quiz_attempt_id' => null,
                ];
                $studentGrades[$studentId]['total_grades']++;
            }
        }
        
        // Sort grades by graded_at date (most recent first) for each student
        foreach ($studentGrades as $studentId => &$data) {
            usort($data['grades'], function($a, $b) {
                $dateA = $a->graded_at ? (is_string($a->graded_at) ? strtotime($a->graded_at) : $a->graded_at->timestamp) : 0;
                $dateB = $b->graded_at ? (is_string($b->graded_at) ? strtotime($b->graded_at) : $b->graded_at->timestamp) : 0;
                return $dateB - $dateA;
            });
        }
        
        // Calculate average percentage for each student
        // Only include assignments and quizzes in the average (exclude games as they use different scoring)
        $overallAverage = 0;
        $studentsWithGrades = 0;
        
        foreach ($studentGrades as $studentId => &$data) {
            $totalPercentage = 0;
            $countForAverage = 0;
            
            foreach ($data['grades'] as $grade) {
                // Only calculate average from assignments and quizzes (exclude games)
                if (isset($grade->type) && $grade->type !== 'game') {
                    $percentage = $grade->percentage ?? 0;
                    // Ensure percentage is reasonable (0-100)
                    if ($percentage >= 0 && $percentage <= 100) {
                        $totalPercentage += $percentage;
                        $countForAverage++;
                    }
                }
            }
            
            if ($countForAverage > 0) {
                $data['average_percentage'] = round($totalPercentage / $countForAverage, 2);
                // Add to overall average calculation
                $overallAverage += $data['average_percentage'];
                $studentsWithGrades++;
            } else {
                // If no assignments/quizzes, set average to 0
                $data['average_percentage'] = 0;
            }
        }
        
        // Calculate overall average grade across all students
        $overallAverageGrade = $studentsWithGrades > 0 ? round($overallAverage / $studentsWithGrades, 1) : 0;
        
        return view('teacher.grades', compact('studentGrades', 'classes', 'overallAverageGrade', 'studentsWithGrades'));
    }
}
