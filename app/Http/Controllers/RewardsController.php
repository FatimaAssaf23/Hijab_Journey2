<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use App\Models\Student;
use App\Models\ClassLessonVisibility;
use Carbon\Carbon;

class RewardsController extends Controller
{
    /**
     * Base points per activity type (maximum points when 100% completion + 100% performance)
     * Balanced distribution: Quizzes (highest value, least frequent) > Assignments > Lessons > Games (most frequent)
     */
    private const BASE_POINTS = [
        'assignment' => 20,   // Assignments: 20 points max (moderate frequency, substantial work)
        'quiz' => 25,         // Quizzes: 25 points max (highest value, least frequent, summative assessment)
        'lesson' => 15,       // Lessons: 15 points max (foundational content)
        'game' => 10,         // Games: 10 points max (most frequent, reinforcement/practice)
    ];

    /**
     * Completion vs Performance ratio (how much completion matters vs quality)
     */
    private const COMPLETION_WEIGHT = 0.4;  // 40% completion, 60% performance
    private const PERFORMANCE_WEIGHT = 0.6;

    /**
     * Maximum lesson score for normalization
     */
    private const MAX_LESSON_SCORE = 15;

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
     * Calculate points for a single activity type
     * 
     * @param string $activityType The type of activity (assignment, quiz, lesson, game)
     * @param int $completed Number of completed activities
     * @param int $available Number of available activities
     * @param float $avgScore Average score/grade (0-100 scale)
     * @return float Points earned for this activity type
     */
    private function calculateActivityPoints($activityType, $completed, $available, $avgScore = 0)
    {
        if ($available == 0) {
            return 0;
        }

        $completionRate = ($completed / $available) * 100;
        
        // Normalize score to 0-100 if needed
        $normalizedScore = min(max($avgScore, 0), 100);
        
        // Calculate weighted score: completion rate + performance score
        $weightedScore = ($completionRate * self::COMPLETION_WEIGHT) + 
                        ($normalizedScore * self::PERFORMANCE_WEIGHT);
        
        // Get base points for this activity type
        $basePoints = self::BASE_POINTS[$activityType] ?? 10;
        
        // Calculate points: weighted score (0-100) * base points for this activity type
        // Example: 100% completion + 100% performance = 100 weighted score = full base points
        $points = ($weightedScore / 100) * $basePoints;
        
        return $points;
    }

    /**
     * Calculate maximum possible score for a student (100% completion + 100% performance)
     */
    private function calculateMaxPossibleScore($studentId, $startDate, $endDate)
    {
        $student = Student::find($studentId);
        if (!$student) {
            return 0;
        }

        $classId = $student->class_id;
        $available = $this->getAvailableActivities($classId, $startDate, $endDate);
        
        $totalAvailable = $available['assignments'] + $available['quizzes'] + 
                         $available['lessons'] + $available['games'];
        if ($totalAvailable == 0) {
            return 0;
        }

        $maxScore = 0;

        // Calculate max points for each activity type (assuming 100% completion and 100% performance)
        if ($available['assignments'] > 0) {
            $maxScore += $this->calculateActivityPoints(
                'assignment',
                $available['assignments'], // All completed
                $available['assignments'],
                100 // 100% average grade
            );
        }

        if ($available['quizzes'] > 0) {
            $maxScore += $this->calculateActivityPoints(
                'quiz',
                $available['quizzes'], // All completed
                $available['quizzes'],
                100 // 100% average score
            );
        }

        if ($available['lessons'] > 0) {
            $maxScore += $this->calculateActivityPoints(
                'lesson',
                $available['lessons'], // All completed
                $available['lessons'],
                100 // 100% normalized score
            );
        }

        if ($available['games'] > 0) {
            $maxScore += $this->calculateActivityPoints(
                'game',
                $available['games'], // All completed
                $available['games'],
                100 // 100% average score
            );
        }

        // Add maximum completion bonus (100% completion rate)
        // Calculate max bonus based on available activity types
        $maxPossibleBase = (
            (self::BASE_POINTS['assignment'] * ($available['assignments'] > 0 ? 1 : 0)) +
            (self::BASE_POINTS['quiz'] * ($available['quizzes'] > 0 ? 1 : 0)) +
            (self::BASE_POINTS['lesson'] * ($available['lessons'] > 0 ? 1 : 0)) +
            (self::BASE_POINTS['game'] * ($available['games'] > 0 ? 1 : 0))
        );
        $maxCompletionBonus = min($maxPossibleBase * 0.1, 10); // Max 10 point bonus
        $maxScore += $maxCompletionBonus;

        return round($maxScore, 2);
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
        $totalAvailable = $available['assignments'] + $available['quizzes'] + 
                         $available['lessons'] + $available['games'];
        if ($totalAvailable == 0) {
            return 0;
        }

        $totalScore = 0;
        $totalCompleted = 0;

        // 1. ASSIGNMENTS
        if ($available['assignments'] > 0) {
            $completedAssignments = DB::table('assignment_submissions')
                ->join('assignments', 'assignment_submissions.assignment_id', '=', 'assignments.assignment_id')
                ->where('assignment_submissions.student_id', $studentId)
                ->where('assignments.class_id', $classId)
                ->whereDate('assignment_submissions.submitted_at', '>=', $startDate)
                ->whereDate('assignment_submissions.submitted_at', '<=', $endDate)
                ->whereNotNull('assignment_submissions.submitted_at')
                ->count();

            // Get assignment grades
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

            $avgGrade = count($assignmentGrades) > 0 
                ? array_sum($assignmentGrades) / count($assignmentGrades) 
                : 0;

            $totalScore += $this->calculateActivityPoints(
                'assignment',
                $completedAssignments,
                $available['assignments'],
                $avgGrade
            );
            $totalCompleted += $completedAssignments;
        }

        // 2. QUIZZES
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

            // Quizzes without grades (use quiz score)
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
            
            $avgScore = $completedQuizzes > 0 
                ? array_sum($allQuizScores) / $completedQuizzes 
                : 0;

            $totalScore += $this->calculateActivityPoints(
                'quiz',
                $completedQuizzes,
                $available['quizzes'],
                $avgScore
            );
            $totalCompleted += $completedQuizzes;
        }

        // 3. LESSONS
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

                // Normalize lesson scores (0-15 scale to 0-100)
                $normalizedScores = array_map(function($score) {
                    return ($score / self::MAX_LESSON_SCORE) * 100;
                }, $lessonScores);

                $avgScore = count($normalizedScores) > 0 
                    ? array_sum($normalizedScores) / count($normalizedScores) 
                    : 0;

                $totalScore += $this->calculateActivityPoints(
                    'lesson',
                    $completedLessons,
                    $available['lessons'],
                    $avgScore
                );
                $totalCompleted += $completedLessons;
            }
        }

        // 4. GAMES
        if ($available['games'] > 0) {
            $visibleLessonIds = DB::table('class_lesson_visibilities')
                ->where('class_id', $classId)
                ->where('is_visible', true)
                ->pluck('lesson_id')
                ->toArray();

            if (count($visibleLessonIds) > 0) {
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

                    // Game scores are already on 0-100 scale (or should be)
                    $avgScore = count($gameScores) > 0 
                        ? array_sum($gameScores) / count($gameScores) 
                        : 0;

                    $totalScore += $this->calculateActivityPoints(
                        'game',
                        $completedGames,
                        $available['games'],
                        $avgScore
                    );
                    $totalCompleted += $completedGames;
                }
            }
        }

        // Overall completion bonus (encourages completing all activity types)
        // Bonus is a percentage of the max possible base points, capped at 10 points
        $overallCompletionRate = ($totalCompleted / $totalAvailable) * 100;
        $maxPossibleBase = (
            (self::BASE_POINTS['assignment'] * ($available['assignments'] > 0 ? 1 : 0)) +
            (self::BASE_POINTS['quiz'] * ($available['quizzes'] > 0 ? 1 : 0)) +
            (self::BASE_POINTS['lesson'] * ($available['lessons'] > 0 ? 1 : 0)) +
            (self::BASE_POINTS['game'] * ($available['games'] > 0 ? 1 : 0))
        );
        $completionBonus = ($overallCompletionRate / 100) * min($maxPossibleBase * 0.1, 10); // Max 10 point bonus
        $totalScore += $completionBonus;

        return round($totalScore, 2);
    }

    /**
     * Get all students with their performance scores (with caching)
     */
    public function getStudentsWithScores($startDate, $endDate)
    {
        // Cache key based on date range and calculation version (change version to clear cache)
        $cacheVersion = 'v3'; // Update this when calculation logic changes
        $cacheKey = 'students_scores_' . $cacheVersion . '_' . md5($startDate . $endDate);
        
        return Cache::remember($cacheKey, 60, function() use ($startDate, $endDate) {
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
        });
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

        // Calculate max possible scores for percentage calculation
        $currentStudentDailyMax = $this->calculateMaxPossibleScore(
            $currentStudent->student_id,
            $todayStart,
            $todayEnd
        );
        $currentStudentWeeklyMax = $this->calculateMaxPossibleScore(
            $currentStudent->student_id,
            $weekStart,
            $weekEnd
        );

        // Calculate percentages
        $currentStudentDailyPercentage = $currentStudentDailyMax > 0 
            ? round(($currentStudentDailyScore / $currentStudentDailyMax) * 100, 1) 
            : 0;
        $currentStudentWeeklyPercentage = $currentStudentWeeklyMax > 0 
            ? round(($currentStudentWeeklyScore / $currentStudentWeeklyMax) * 100, 1) 
            : 0;

        // Calculate max scores and percentages for top students
        if ($topOfDay) {
            $topOfDayMax = $this->calculateMaxPossibleScore(
                $topOfDay->student_id,
                $todayStart,
                $todayEnd
            );
            $topOfDay->max_score = $topOfDayMax;
            $topOfDay->percentage = $topOfDayMax > 0 
                ? round(($topOfDay->performance_score / $topOfDayMax) * 100, 1) 
                : 0;
        }

        foreach ($topOfWeek as $student) {
            $studentMax = $this->calculateMaxPossibleScore(
                $student->student_id,
                $weekStart,
                $weekEnd
            );
            $student->max_score = $studentMax;
            $student->percentage = $studentMax > 0 
                ? round(($student->performance_score / $studentMax) * 100, 1) 
                : 0;
        }

        return view('student.rewards', compact(
            'topOfDay',
            'topOfWeek',
            'isTopOfDay',
            'currentStudent',
            'currentStudentDailyRank',
            'currentStudentWeeklyRank',
            'currentStudentInTopWeek',
            'currentStudentDailyScore',
            'currentStudentWeeklyScore',
            'currentStudentDailyPercentage',
            'currentStudentWeeklyPercentage'
        ));
    }
}
