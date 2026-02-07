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
     * Weight/Importance of each activity type (used for weighted average)
     * Higher weight = more important in final score calculation
     * Used to calculate percentage-based scores (0-100 scale)
     */
    private const ACTIVITY_WEIGHTS = [
        'assignment' => 1.0,   // Standard weight
        'quiz' => 1.25,       // Quizzes are 25% more important
        'lesson' => 0.75,     // Lessons are 25% less important
        'game' => 0.5,        // Games are least important
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
     * Calculate weighted score (0-100) for a single activity type
     * Returns a percentage score based on completion and performance
     * 
     * @param string $activityType The type of activity (assignment, quiz, lesson, game)
     * @param int $completed Number of completed activities
     * @param int $available Number of available activities
     * @param float $avgScore Average score/grade (0-100 scale)
     * @return float Weighted score (0-100) for this activity type
     */
    private function calculateActivityScore($activityType, $completed, $available, $avgScore = 0)
    {
        if ($available == 0) {
            return 0;
        }

        $completionRate = ($completed / $available) * 100;
        
        // Normalize score to 0-100 if needed
        $normalizedScore = min(max($avgScore, 0), 100);
        
        // Calculate weighted score: completion rate + performance score
        // This gives a score from 0-100 for this activity type
        $weightedScore = ($completionRate * self::COMPLETION_WEIGHT) + 
                      ($normalizedScore * self::PERFORMANCE_WEIGHT);
        
        return $weightedScore; // Returns 0-100
    }

    /**
     * Calculate maximum possible score for a student (always 100)
     * Since we're using percentage-based scoring, max is always 100
     */
    private function calculateMaxPossibleScore($studentId, $startDate, $endDate)
    {
        // With percentage-based scoring, max is always 100
        return 100;
    }

    /**
     * Calculate performance score for a student within a date range
     * Returns a score from 0-100 (percentage-based)
     * FAIR: Considers each student's assigned activities proportionally
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

        $activityScores = [];
        $totalWeight = 0;

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

            $activityScore = $this->calculateActivityScore(
                'assignment',
                $completedAssignments,
                $available['assignments'],
                $avgGrade
            );

            // Weight by importance and count (more activities = more weight)
            $weight = self::ACTIVITY_WEIGHTS['assignment'] * $available['assignments'];
            $activityScores[] = [
                'score' => $activityScore,
                'weight' => $weight
            ];
            $totalWeight += $weight;
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

            $activityScore = $this->calculateActivityScore(
                'quiz',
                $completedQuizzes,
                $available['quizzes'],
                $avgScore
            );

            // Weight by importance and count
            $weight = self::ACTIVITY_WEIGHTS['quiz'] * $available['quizzes'];
            $activityScores[] = [
                'score' => $activityScore,
                'weight' => $weight
            ];
            $totalWeight += $weight;
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

                // Normalize lesson scores (0-15 scale to 0-100) with safety check
                $normalizedScores = array_map(function($score) {
                    // Safety: cap at 100% even if score exceeds MAX_LESSON_SCORE
                    return min(($score / self::MAX_LESSON_SCORE) * 100, 100);
                }, $lessonScores);

                $avgScore = count($normalizedScores) > 0 
                    ? array_sum($normalizedScores) / count($normalizedScores) 
                    : 0;

                $activityScore = $this->calculateActivityScore(
                    'lesson',
                    $completedLessons,
                    $available['lessons'],
                    $avgScore
                );

                // Weight by importance and count
                $weight = self::ACTIVITY_WEIGHTS['lesson'] * $available['lessons'];
                $activityScores[] = [
                    'score' => $activityScore,
                    'weight' => $weight
                ];
                $totalWeight += $weight;
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
                    // Safety: ensure scores are within 0-100 range
                    $gameScores = array_map(function($score) {
                        return min(max($score, 0), 100);
                    }, $gameScores);

                    $avgScore = count($gameScores) > 0 
                        ? array_sum($gameScores) / count($gameScores) 
                        : 0;

                    $activityScore = $this->calculateActivityScore(
                        'game',
                        $completedGames,
                        $available['games'],
                        $avgScore
                    );

                    // Weight by importance and count
                    $weight = self::ACTIVITY_WEIGHTS['game'] * $available['games'];
                    $activityScores[] = [
                        'score' => $activityScore,
                        'weight' => $weight
                    ];
                    $totalWeight += $weight;
                }
            }
        }

        // Calculate weighted average score (0-100)
        if ($totalWeight == 0) {
            return 0;
        }

        $weightedSum = 0;
        foreach ($activityScores as $activity) {
            $weightedSum += $activity['score'] * $activity['weight'];
        }

        $finalScore = $weightedSum / $totalWeight;
        
        // Ensure score is between 0-100 and round to nearest whole number
        $finalScore = min(max($finalScore, 0), 100);

        return round($finalScore);
    }

    /**
     * Get all students with their performance scores (with caching)
     */
    public function getStudentsWithScores($startDate, $endDate)
    {
        // Cache key based on date range and calculation version (change version to clear cache)
        $cacheVersion = 'v5'; // Update this when calculation logic changes - v5: Percentage-based scoring (0-100 scale)
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

        // Calculate percentages (rounded to whole numbers)
        $currentStudentDailyPercentage = $currentStudentDailyMax > 0 
            ? round(($currentStudentDailyScore / $currentStudentDailyMax) * 100) 
            : 0;
        $currentStudentWeeklyPercentage = $currentStudentWeeklyMax > 0 
            ? round(($currentStudentWeeklyScore / $currentStudentWeeklyMax) * 100) 
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
                ? round(($topOfDay->performance_score / $topOfDayMax) * 100) 
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
                ? round(($student->performance_score / $studentMax) * 100) 
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

