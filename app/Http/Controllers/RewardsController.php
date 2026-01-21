<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Student;
use App\Models\ClassLessonVisibility;
use Carbon\Carbon;

class RewardsController extends Controller
{
    /**
     * Get student's class ID
     */
    private function getStudentClassId($studentId)
    {
        $student = Student::find($studentId);
        return $student ? $student->class_id : null;
    }

    /**
     * Get available activities for a student's class during a period
     */
    private function getAvailableActivities($classId, $startDate, $endDate)
    {
        if (!$classId) {
            return [
                'assignments' => 0,
                'quizzes' => 0,
                'lessons' => 0,
                'games' => 0,
            ];
        }

        // Count assignments assigned to the class during the period
        $assignmentsCount = DB::table('assignments')
            ->where('class_id', $classId)
            ->whereDate('due_date', '>=', $startDate)
            ->whereDate('due_date', '<=', $endDate)
            ->count();

        // Count quizzes assigned to the class during the period
        $quizzesCount = DB::table('quizzes')
            ->where('class_id', $classId)
            ->where('is_active', true)
            ->whereDate('due_date', '>=', $startDate)
            ->whereDate('due_date', '<=', $endDate)
            ->count();

        // Count visible/unlocked lessons for the class
        $visibleLessonIds = DB::table('class_lesson_visibilities')
            ->where('class_id', $classId)
            ->where('is_visible', true)
            ->pluck('lesson_id')
            ->toArray();
        $lessonsCount = count($visibleLessonIds);

        // Count available games (games associated with visible lessons)
        // Games are primarily stored in the games table linked to lessons
        $gamesCount = 0;
        if (count($visibleLessonIds) > 0) {
            $gamesCount = DB::table('games')
                ->whereIn('lesson_id', $visibleLessonIds)
                ->distinct('lesson_id')
                ->count();
        }

        return [
            'assignments' => $assignmentsCount,
            'quizzes' => $quizzesCount,
            'lessons' => $lessonsCount,
            'games' => $gamesCount,
        ];
    }

    /**
     * Calculate performance score for a student within a date range
     * Based on completion rates and grades relative to available activities
     */
    private function calculatePerformanceScore($studentId, $startDate, $endDate)
    {
        $student = Student::find($studentId);
        if (!$student) {
            return 0;
        }

        $classId = $student->class_id;
        $available = $this->getAvailableActivities($classId, $startDate, $endDate);
        
        // If no activities available, return 0
        $totalAvailable = $available['assignments'] + $available['quizzes'] + $available['lessons'] + $available['games'];
        if ($totalAvailable == 0) {
            return 0;
        }

        $score = 0;
        $completedCount = 0;
        $totalPoints = 0;

        // 1. ASSIGNMENTS - Count completed and calculate average grade
        if ($available['assignments'] > 0) {
            $completedAssignments = DB::table('assignment_submissions')
                ->join('assignments', 'assignment_submissions.assignment_id', '=', 'assignments.assignment_id')
                ->where('assignment_submissions.student_id', $studentId)
                ->where('assignments.class_id', $classId)
                ->whereDate('assignment_submissions.submitted_at', '>=', $startDate)
                ->whereDate('assignment_submissions.submitted_at', '<=', $endDate)
                ->whereNotNull('assignment_submissions.submitted_at')
                ->count();

            // Get assignment grades received in this period
            $assignmentGrades = DB::table('grades')
                ->join('assignment_submissions', 'grades.assignment_submission_id', '=', 'assignment_submissions.submission_id')
                ->join('assignments', 'assignment_submissions.assignment_id', '=', 'assignments.assignment_id')
                ->where('grades.student_id', $studentId)
                ->whereNull('grades.quiz_attempt_id')
                ->where('assignments.class_id', $classId)
                ->whereDate('grades.graded_at', '>=', $startDate)
                ->whereDate('grades.graded_at', '<=', $endDate)
                ->pluck('grades.percentage')
                ->toArray();

            $assignmentCompletionRate = $available['assignments'] > 0 ? ($completedAssignments / $available['assignments']) * 100 : 0;
            $assignmentAvgGrade = count($assignmentGrades) > 0 ? array_sum($assignmentGrades) / count($assignmentGrades) : 0;
            
            // Score: completion rate (0-100) + average grade (0-100) weighted by number of assignments
            $assignmentScore = ($assignmentCompletionRate * 0.5 + $assignmentAvgGrade * 0.5) * ($available['assignments'] / 10);
            $score += $assignmentScore;
            
            $completedCount += $completedAssignments;
            $totalPoints += array_sum($assignmentGrades);
        }

        // 2. QUIZZES - Count completed and calculate average score
        if ($available['quizzes'] > 0) {
            // Quizzes with grades
            $quizAttemptsWithGrades = DB::table('quiz_attempts')
                ->join('grades', 'quiz_attempts.attempt_id', '=', 'grades.quiz_attempt_id')
                ->join('quizzes', 'quiz_attempts.quiz_id', '=', 'quizzes.quiz_id')
                ->where('quiz_attempts.student_id', $studentId)
                ->where('quiz_attempts.status', 'completed')
                ->where('quizzes.class_id', $classId)
                ->whereNotNull('quiz_attempts.submitted_at')
                ->whereDate('grades.graded_at', '>=', $startDate)
                ->whereDate('grades.graded_at', '<=', $endDate)
                ->pluck('grades.percentage')
                ->toArray();

            // Quizzes without grades (use quiz score, assuming it's percentage)
            $quizAttemptsWithoutGrades = DB::table('quiz_attempts')
                ->leftJoin('grades', 'quiz_attempts.attempt_id', '=', 'grades.quiz_attempt_id')
                ->join('quizzes', 'quiz_attempts.quiz_id', '=', 'quizzes.quiz_id')
                ->where('quiz_attempts.student_id', $studentId)
                ->where('quiz_attempts.status', 'completed')
                ->where('quizzes.class_id', $classId)
                ->whereNull('grades.quiz_attempt_id')
                ->whereNotNull('quiz_attempts.submitted_at')
                ->whereDate('quiz_attempts.submitted_at', '>=', $startDate)
                ->whereDate('quiz_attempts.submitted_at', '<=', $endDate)
                ->pluck('quiz_attempts.score')
                ->toArray();

            $allQuizScores = array_merge($quizAttemptsWithGrades, $quizAttemptsWithoutGrades);
            $completedQuizzes = count($allQuizScores);
            
            $quizCompletionRate = $available['quizzes'] > 0 ? ($completedQuizzes / $available['quizzes']) * 100 : 0;
            $quizAvgScore = $completedQuizzes > 0 ? array_sum($allQuizScores) / $completedQuizzes : 0;
            
            // Score: completion rate + average score, weighted higher (2x) and by number of quizzes
            $quizScore = ($quizCompletionRate * 0.5 + $quizAvgScore * 0.5) * 2 * ($available['quizzes'] / 5);
            $score += $quizScore;
            
            $completedCount += $completedQuizzes;
            $totalPoints += array_sum($allQuizScores) * 2; // Quizzes weighted 2x
        }

        // 3. LESSONS - Count completed
        if ($available['lessons'] > 0) {
            $visibleLessonIds = DB::table('class_lesson_visibilities')
                ->where('class_id', $classId)
                ->where('is_visible', true)
                ->pluck('lesson_id')
                ->toArray();

            if (count($visibleLessonIds) > 0) {
                $completedLessons = DB::table('student_lesson_progresses')
                    ->where('student_id', $studentId)
                    ->whereIn('lesson_id', $visibleLessonIds)
                    ->where('status', 'completed')
                    ->whereNotNull('completed_at')
                    ->whereDate('completed_at', '>=', $startDate)
                    ->whereDate('completed_at', '<=', $endDate)
                    ->count();

                $lessonCompletionRate = ($completedLessons / $available['lessons']) * 100;
                
                // Get lesson scores
                $lessonScores = DB::table('student_lesson_progresses')
                    ->where('student_id', $studentId)
                    ->whereIn('lesson_id', $visibleLessonIds)
                    ->where('status', 'completed')
                    ->whereNotNull('completed_at')
                    ->whereDate('completed_at', '>=', $startDate)
                    ->whereDate('completed_at', '<=', $endDate)
                    ->pluck('score')
                    ->toArray();

                $avgLessonScore = count($lessonScores) > 0 
                    ? array_sum($lessonScores) / count($lessonScores) 
                    : 15; // Default score

                // Score: completion rate + average score, weighted by number of lessons
                $lessonScore = ($lessonCompletionRate * 0.6 + ($avgLessonScore / 15 * 100) * 0.4) * ($available['lessons'] / 10);
                $score += $lessonScore;
                
                $completedCount += $completedLessons;
            }
        }

        // 4. GAMES - Count completed
        if ($available['games'] > 0) {
            $visibleLessonIds = DB::table('class_lesson_visibilities')
                ->where('class_id', $classId)
                ->where('is_visible', true)
                ->pluck('lesson_id')
                ->toArray();

            if (count($visibleLessonIds) > 0) {
                // Get game IDs for visible lessons
                $gameIds = DB::table('games')
                    ->whereIn('lesson_id', $visibleLessonIds)
                    ->pluck('game_id')
                    ->toArray();

                if (count($gameIds) > 0) {
                    $completedGames = DB::table('student_game_progresses')
                        ->where('student_id', $studentId)
                        ->whereIn('game_id', $gameIds)
                        ->where('status', 'completed')
                        ->whereNotNull('completed_at')
                        ->whereDate('completed_at', '>=', $startDate)
                        ->whereDate('completed_at', '<=', $endDate)
                        ->count();

                    $gameCompletionRate = $available['games'] > 0 ? ($completedGames / $available['games']) * 100 : 0;
                    
                    // Get game scores
                    $gameScores = DB::table('student_game_progresses')
                        ->where('student_id', $studentId)
                        ->whereIn('game_id', $gameIds)
                        ->where('status', 'completed')
                        ->whereNotNull('completed_at')
                        ->whereDate('completed_at', '>=', $startDate)
                        ->whereDate('completed_at', '<=', $endDate)
                        ->pluck('score')
                        ->toArray();

                    $avgGameScore = count($gameScores) > 0 
                        ? array_sum($gameScores) / count($gameScores) 
                        : 0;

                    // Score: completion rate + average score (normalized), weighted 1.5x
                    $normalizedGameScore = min(($avgGameScore / 100) * 100, 100); // Normalize to 0-100
                    $gameScore = ($gameCompletionRate * 0.6 + $normalizedGameScore * 0.4) * 1.5 * ($available['games'] / 10);
                    $score += $gameScore;
                    
                    $completedCount += $completedGames;
                }
            }
        }

        // Overall completion rate bonus (rewards students who complete more activities)
        $overallCompletionRate = $totalAvailable > 0 ? ($completedCount / $totalAvailable) * 100 : 0;
        $completionBonus = $overallCompletionRate * 0.2; // Bonus for high completion rate
        $score += $completionBonus;

        return round($score, 2);
    }

    /**
     * Get all students with their performance scores
     */
    public function getStudentsWithScores($startDate, $endDate)
    {
        $students = Student::with('user')
            ->whereHas('user', function($query) {
                $query->where('role', 'student');
            })
            ->get();

        // Calculate performance score for each student
        $studentsWithScores = $students->map(function($student) use ($startDate, $endDate) {
            $student->performance_score = $this->calculatePerformanceScore(
                $student->student_id,
                $startDate,
                $endDate
            );
            return $student;
        });

        // Sort by performance score descending
        return $studentsWithScores->sortByDesc('performance_score')->values();
    }

    public function index()
    {
        $currentStudent = Auth::user()->student;
        
        if (!$currentStudent) {
            return redirect()->route('student.dashboard')->with('error', 'Student profile not found.');
        }

        // Calculate date ranges
        $todayStart = Carbon::today()->startOfDay();
        $todayEnd = Carbon::today()->endOfDay();
        $weekStart = Carbon::now()->startOfWeek()->startOfDay();
        $weekEnd = Carbon::now()->endOfWeek()->endOfDay();

        // Get all students with their daily performance scores
        $studentsWithDailyScores = $this->getStudentsWithScores($todayStart, $todayEnd);
        
        // Get top student of the day (based on today's performance)
        $topOfDay = $studentsWithDailyScores->where('performance_score', '>', 0)->first();

        // Get all students with their weekly performance scores
        $studentsWithWeeklyScores = $this->getStudentsWithScores($weekStart, $weekEnd);
        
        // Get top 3 students of the week (based on this week's performance)
        $topOfWeek = $studentsWithWeeklyScores->where('performance_score', '>', 0)->take(3);

        // Check if current student is the top of the day
        $isTopOfDay = false;
        if ($topOfDay && $currentStudent && $topOfDay->student_id === $currentStudent->student_id) {
            $isTopOfDay = true;
        }

        // Get current student's daily rank
        $currentStudentDailyRank = null;
        $currentStudentDailyIndex = $studentsWithDailyScores->search(function($student) use ($currentStudent) {
            return $student->student_id === $currentStudent->student_id;
        });
        if ($currentStudentDailyIndex !== false) {
            $currentStudentDailyRank = $currentStudentDailyIndex + 1;
        }

        // Get current student's weekly rank
        $currentStudentWeeklyRank = null;
        $currentStudentWeeklyIndex = $studentsWithWeeklyScores->search(function($student) use ($currentStudent) {
            return $student->student_id === $currentStudent->student_id;
        });
        if ($currentStudentWeeklyIndex !== false) {
            $currentStudentWeeklyRank = $currentStudentWeeklyIndex + 1;
        }

        // Get current student's position in top of week
        $currentStudentInTopWeek = null;
        foreach ($topOfWeek as $index => $student) {
            if ($student->student_id === $currentStudent->student_id) {
                $currentStudentInTopWeek = $index + 1;
                break;
            }
        }

        // Calculate current student's scores for display
        $currentStudentDailyScore = $this->calculatePerformanceScore(
            $currentStudent->student_id,
            $todayStart,
            $todayEnd
        );
        $currentStudentWeeklyScore = $this->calculatePerformanceScore(
            $currentStudent->student_id,
            $weekStart,
            $weekEnd
        );

        return view('student.rewards', compact(
            'topOfDay',
            'topOfWeek',
            'isTopOfDay',
            'currentStudent',
            'currentStudentDailyRank',
            'currentStudentWeeklyRank',
            'currentStudentInTopWeek',
            'currentStudentDailyScore',
            'currentStudentWeeklyScore'
        ));
    }
}
