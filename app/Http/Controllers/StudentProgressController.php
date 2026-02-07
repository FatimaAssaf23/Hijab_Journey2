<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Level;
use App\Models\Lesson;
use App\Models\StudentLessonProgress;
use App\Models\StudentGameProgress;
use App\Models\QuizAttempt;
use App\Models\AssignmentSubmission;
use App\Models\Assignment;
use App\Models\Grade;
use App\Models\Game;
use App\Models\ClassLessonVisibility;

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

    /**
     * Track video watch progress for a lesson
     * 
     * @param Request $request
     * @param int $lessonId
     * @return \Illuminate\Http\JsonResponse
     */
    public function trackVideoProgress(Request $request, $lessonId)
    {
        $request->validate([
            'watched_seconds' => 'required|integer|min:0',
            'watched_percentage' => 'required|numeric|min:0|max:100',
            'current_position' => 'nullable|numeric|min:0',
            'max_watched_time' => 'nullable|numeric|min:0',
            'is_completed' => 'nullable|boolean',
        ]);

        $user = Auth::user();
        $student = $user->student;

        if (!$student) {
            return response()->json([
                'success' => false,
                'message' => 'Student profile not found.'
            ], 404);
        }

        $lesson = Lesson::find($lessonId);
        if (!$lesson) {
            return response()->json([
                'success' => false,
                'message' => 'Lesson not found.'
            ], 404);
        }

        // Find or create progress record
        $progress = StudentLessonProgress::firstOrCreate(
            [
                'student_id' => $student->student_id,
                'lesson_id' => $lessonId,
            ],
            [
                'status' => 'in_progress',
                'started_at' => now(),
                'last_activity_at' => now(),
            ]
        );

        // Get video duration to cap watched seconds and percentage
        $videoDuration = $lesson->video_duration_seconds ?? 0;
        
        // Recalculate percentage based on max_watched_time (most accurate) or capped seconds
        // max_watched_time represents the furthest point reached in the video
        // CRITICAL: Always use the MAXIMUM value between request and existing database value
        // This ensures progress never decreases, even if user watches video again from start
        $requestMaxWatchedTime = $request->max_watched_time ?? 0;
        $existingMaxWatchedTime = $progress->max_watched_time ?? 0;
        
        // Always use the maximum (furthest point ever reached)
        $maxWatchedTime = max($requestMaxWatchedTime, $existingMaxWatchedTime);
        
        $cappedMaxWatchedTime = $videoDuration > 0 
            ? min($maxWatchedTime, $videoDuration)
            : $maxWatchedTime;
        
        // CRITICAL: watched_seconds should also never decrease
        // It should be at least equal to max_watched_time to ensure consistency
        // Use the maximum between request value and existing value to prevent progress from decreasing
        $requestWatchedSeconds = $request->watched_seconds ?? 0;
        $existingWatchedSeconds = $progress->watched_seconds ?? 0;
        
        // Always use the maximum watched_seconds (prevents progress from decreasing)
        // Also ensure it's at least equal to max_watched_time for consistency
        $maxWatchedSeconds = max($requestWatchedSeconds, $existingWatchedSeconds, $cappedMaxWatchedTime);
        
        // Cap watched seconds at video duration (prevent >100% completion)
        $cappedWatchedSeconds = $videoDuration > 0 
            ? min($maxWatchedSeconds, $videoDuration) 
            : $maxWatchedSeconds;
        
        // Use max_watched_time for percentage calculation (more accurate than watched_seconds)
        // This ensures percentage reflects the actual furthest point reached
        $cappedPercentage = $videoDuration > 0 
            ? min(100, round(($cappedMaxWatchedTime / $videoDuration) * 100, 2))
            : min(100, round($request->watched_percentage, 2));

        // Update video tracking fields
        // Use max_watched_time as the source of truth for percentage calculation
        // CRITICAL: watched_seconds should never decrease - always use maximum value
        $progress->watched_seconds = $cappedWatchedSeconds;
        $progress->watched_percentage = $cappedPercentage;
        $progress->last_position = $request->current_position ?? $progress->last_position;
        
        // Update max_watched_time - ALWAYS use the maximum value (furthest point ever reached)
        // This ensures progress never decreases when watching video again
        $progress->max_watched_time = $cappedMaxWatchedTime;
        $progress->last_watched_at = now();
        
        // Update last activity timestamp to track student activity on the website
        $progress->last_activity_at = now();

        // Update time_spent_minutes (convert seconds to minutes, rounded)
        $progress->time_spent_minutes = round($cappedWatchedSeconds / 60);

        // RULE 1: If watched_percentage >= 80%, mark video_completed = true AND mark lesson as completed
        // Use capped percentage for completion check
        $isVideoCompleted = $cappedPercentage >= 80;
        $wasVideoCompleted = $progress->video_completed ?? false;
        $wasLessonCompleted = $progress->status === 'completed';
        $progress->video_completed = $isVideoCompleted;

        // If video reaches 80%, mark lesson as completed and unlock the game
        if ($isVideoCompleted) {
            // Unlock the game when 80% is reached
            if (!$wasVideoCompleted) {
                $this->unlockLessonGame($student->student_id, $lessonId);
            }
            
            // Mark lesson as completed when 80% is reached
            if (!$wasLessonCompleted) {
                $progress->status = 'completed';
                if (!$progress->completed_at) {
                    $progress->completed_at = now();
                }
                
                // RULE 4: Unlock next lesson when current lesson is completed
                $this->unlockNextLesson($student->student_id, $lesson->level_id, $lessonId);
            }
        } else if ($progress->status === 'not_started') {
            // Video not yet at 80% - mark as in_progress
            $progress->status = 'in_progress';
            if (!$progress->started_at) {
                $progress->started_at = now();
            }
        }
        
        // SAFEGUARD: If lesson status is 'completed' (from any source), ensure games are unlocked
        // This handles edge cases where lesson was completed but games weren't unlocked
        if ($progress->status === 'completed' && !$wasLessonCompleted) {
            // Only unlock if we just marked it as completed (to avoid unnecessary calls)
            $this->unlockLessonGame($student->student_id, $lessonId);
        }

        // CRITICAL: Save and refresh to ensure data is committed to database
        $progress->save();
        
        // Refresh the model to ensure we have the latest database values
        $progress->refresh();
        
        // Double-check that max_watched_time and watched_percentage are saved correctly
        // Recalculate to ensure consistency
        $finalVideoDuration = $lesson->video_duration_seconds ?? 0;
        $finalMaxWatchedTime = $progress->max_watched_time ?? 0;
        $finalPercentage = 0;
        
        if ($finalVideoDuration > 0 && $finalMaxWatchedTime > 0) {
            $finalPercentage = round(($finalMaxWatchedTime / $finalVideoDuration) * 100, 2);
        } else {
            $finalPercentage = $progress->watched_percentage ?? 0;
        }
        
        // CRITICAL: Ensure watched_seconds is at least equal to max_watched_time
        // This prevents progress from appearing to decrease
        $finalWatchedSeconds = $progress->watched_seconds ?? 0;
        if ($finalWatchedSeconds < $finalMaxWatchedTime) {
            $finalWatchedSeconds = $finalMaxWatchedTime;
            if ($finalVideoDuration > 0) {
                $finalWatchedSeconds = min($finalWatchedSeconds, $finalVideoDuration);
            }
            $progress->watched_seconds = $finalWatchedSeconds;
        }
        
        // Ensure watched_percentage matches the calculated value
        if (abs(($progress->watched_percentage ?? 0) - $finalPercentage) > 0.01) {
            $progress->watched_percentage = $finalPercentage;
        }
        
        // CRITICAL: Ensure video_completed and status are set correctly based on final percentage
        // This ensures completion status persists even if there were calculation discrepancies
        if ($finalPercentage >= 80) {
            if (!($progress->video_completed ?? false)) {
                $progress->video_completed = true;
            }
            if ($progress->status !== 'completed') {
                $progress->status = 'completed';
                if (!$progress->completed_at) {
                    $progress->completed_at = now();
                }
                // Unlock games if lesson just became completed
                $this->unlockLessonGame($student->student_id, $lessonId);
                // Unlock next lesson
                $this->unlockNextLesson($student->student_id, $lesson->level_id, $lessonId);
            }
        }
        
        // Save if any changes were made
        if ($progress->isDirty()) {
            $progress->save();
            $progress->refresh();
        }

        return response()->json([
            'success' => true,
            'message' => 'Video progress updated successfully.',
            'data' => [
                'watched_seconds' => $progress->watched_seconds,
                'watched_percentage' => $progress->watched_percentage,
                'last_position' => $progress->last_position,
                'max_watched_time' => $progress->max_watched_time,
                'status' => $progress->status,
                'video_completed' => $progress->video_completed,
                'is_completed' => $progress->status === 'completed',
            ]
        ]);
    }

    /**
     * Unlock all lesson games when lesson is completed (via video or game)
     * This method handles all game types: clock, scramble, mcq, word_search, matching_pairs, etc.
     * 
     * @param int $studentId
     * @param int $lessonId
     * @return void
     */
    public function unlockLessonGame($studentId, $lessonId)
    {
        // RULE 2: When lesson is completed (video or game), unlock all lesson games
        $student = \App\Models\Student::find($studentId);
        if (!$student || !$student->class_id) {
            return;
        }
        
        $studentClassId = $student->class_id;
        $unlockedGames = [];
        
        // Helper to ensure a games table entry exists and unlock it
        $ensureAndUnlockGame = function (int $lessonId, int $classId, string $gameType) use ($studentId, &$unlockedGames) {
            $game = \App\Models\Game::firstOrCreate(
                [
                    'lesson_id' => $lessonId,
                    'class_id' => $classId,
                    'game_type' => $gameType,
                ],
                [
                    'description' => ucfirst(str_replace('_', ' ', $gameType)) . ' Game for Lesson ' . $lessonId,
                    'max_score' => 100,
                ]
            );
            
            // Create or update game progress record to allow access (only if not already completed)
            $existingProgress = \App\Models\StudentGameProgress::where('student_id', $studentId)
                ->where('game_id', $game->game_id)
                ->first();
            
            if (!$existingProgress) {
                \App\Models\StudentGameProgress::create([
                    'student_id' => $studentId,
                    'game_id' => $game->game_id,
                    'status' => 'not_started',
                    'score' => 0,
                    'attempts' => 0,
                ]);
                $unlockedGames[] = $gameType;
            }
            
            return $game;
        };
        
        // 1. Clock Game
        $clockGame = \App\Models\ClockGame::where('lesson_id', $lessonId)
            ->where('class_id', $studentClassId)
            ->first();
        if ($clockGame) {
            $ensureAndUnlockGame($lessonId, $studentClassId, 'clock');
        }
        
        // 2. Scrambled Clocks Game
        $scrambledClocksGame = \App\Models\Game::where('lesson_id', $lessonId)
            ->where('class_id', $studentClassId)
            ->where('game_type', 'scrambled_clocks')
            ->first();
        if ($scrambledClocksGame) {
            $ensureAndUnlockGame($lessonId, $studentClassId, 'scrambled_clocks');
        }
        
        // 3. Word Clock Arrangement Game
        $wordClockArrangementGame = \App\Models\Game::where('lesson_id', $lessonId)
            ->where('class_id', $studentClassId)
            ->where('game_type', 'word_clock_arrangement')
            ->first();
        if ($wordClockArrangementGame) {
            $ensureAndUnlockGame($lessonId, $studentClassId, 'word_clock_arrangement');
        }
        
        // 4. Word Search Game
        $wordSearchGame = \App\Models\WordSearchGame::where('lesson_id', $lessonId)
            ->where('class_id', $studentClassId)
            ->first();
        if ($wordSearchGame) {
            $ensureAndUnlockGame($lessonId, $studentClassId, 'word_search');
        }
        
        // 5. Matching Pairs Game
        $matchingPairsGame = \App\Models\MatchingPairsGame::where('lesson_id', $lessonId)
            ->where('class_id', $studentClassId)
            ->first();
        if ($matchingPairsGame) {
            $ensureAndUnlockGame($lessonId, $studentClassId, 'matching_pairs');
        }
        
        // 6. Scramble Game
        $scramblePairs = \App\Models\GroupWordPair::where('lesson_id', $lessonId)
            ->where('class_id', $studentClassId)
            ->where('game_type', 'scramble')
            ->get();
        if ($scramblePairs->isNotEmpty()) {
            $ensureAndUnlockGame($lessonId, $studentClassId, 'scramble');
        }
        
        // 7. MCQ Game
        $mcqPairs = \App\Models\GroupWordPair::where('lesson_id', $lessonId)
            ->where('class_id', $studentClassId)
            ->where('game_type', 'mcq')
            ->get();
        if ($mcqPairs->isNotEmpty()) {
            $ensureAndUnlockGame($lessonId, $studentClassId, 'mcq');
        }
        
        if (!empty($unlockedGames)) {
            \Log::info("Games unlocked for student {$studentId}, lesson {$lessonId}: " . implode(', ', $unlockedGames));
        }
    }

    /**
     * Initialize games for a new student when they join a class
     * This ensures all games assigned to visible lessons are accessible to the student
     * 
     * @param int $studentId
     * @return void
     */
    public function initializeGamesForNewStudent($studentId)
    {
        try {
            $student = \App\Models\Student::find($studentId);
            if (!$student) {
                \Log::warning("Cannot initialize games: Student {$studentId} not found");
                return;
            }
            
            if (!$student->class_id) {
                \Log::warning("Cannot initialize games: Student {$studentId} has no class_id");
                return;
            }
            
            $studentClassId = $student->class_id;
            
            // Get all visible lessons for this class
            $visibleLessonIds = ClassLessonVisibility::where('class_id', $studentClassId)
                ->where('is_visible', true)
                ->pluck('lesson_id')
                ->unique();
            
            if ($visibleLessonIds->isEmpty()) {
                \Log::info("No visible lessons found for class {$studentClassId}, skipping game initialization", [
                    'student_id' => $studentId,
                    'class_id' => $studentClassId
                ]);
                return;
            }
            
            \Log::info("Initializing games for new student", [
                'student_id' => $studentId,
                'class_id' => $studentClassId,
                'visible_lessons_count' => $visibleLessonIds->count(),
                'visible_lesson_ids' => $visibleLessonIds->toArray()
            ]);
            
            $gamesInitialized = 0;
            $totalGamesCreated = 0;
            
            // Process each visible lesson
            foreach ($visibleLessonIds as $lessonId) {
                try {
                    // Count games before initialization
                    $gamesBefore = \App\Models\StudentGameProgress::where('student_id', $studentId)->count();
                    
                    // Use the existing unlockLessonGame logic to initialize games for this lesson
                    // This ensures consistency with how games are unlocked
                    $this->unlockLessonGame($studentId, $lessonId);
                    
                    // Count games after initialization
                    $gamesAfter = \App\Models\StudentGameProgress::where('student_id', $studentId)->count();
                    $gamesCreatedForLesson = $gamesAfter - $gamesBefore;
                    $totalGamesCreated += $gamesCreatedForLesson;
                    
                    \Log::info("Initialized games for lesson", [
                        'student_id' => $studentId,
                        'lesson_id' => $lessonId,
                        'games_created' => $gamesCreatedForLesson
                    ]);
                    
                    $gamesInitialized++;
                } catch (\Exception $e) {
                    \Log::error("Error initializing games for lesson {$lessonId}: " . $e->getMessage(), [
                        'student_id' => $studentId,
                        'lesson_id' => $lessonId,
                        'trace' => $e->getTraceAsString()
                    ]);
                    // Continue with next lesson even if this one fails
                }
            }
            
            \Log::info("Games initialization completed for student {$studentId}", [
                'lessons_processed' => $gamesInitialized,
                'total_visible_lessons' => $visibleLessonIds->count(),
                'total_games_created' => $totalGamesCreated
            ]);
        } catch (\Exception $e) {
            \Log::error("Critical error in initializeGamesForNewStudent: " . $e->getMessage(), [
                'student_id' => $studentId,
                'trace' => $e->getTraceAsString()
            ]);
        }
    }

    /**
     * Check if game is completed (passed)
     * 
     * @param int $studentId
     * @param int $lessonId
     * @return bool
     */
    private function checkGameCompletion($studentId, $lessonId)
    {
        $game = Game::where('lesson_id', $lessonId)->first();
        
        if (!$game) {
            // If no game exists, consider it "passed" (video completion is enough)
            return true;
        }

        $gameProgress = StudentGameProgress::where('student_id', $studentId)
            ->where('game_id', $game->game_id)
            ->first();

        // Game is considered passed if status is 'completed'
        return $gameProgress && $gameProgress->status === 'completed';
    }

    /**
     * Unlock next lesson when current lesson is completed
     * 
     * @param int $studentId
     * @param int $levelId
     * @param int $currentLessonId
     * @return void
     */
    public function unlockNextLesson($studentId, $levelId, $currentLessonId)
    {
        // RULE 4: Unlock next lesson when lesson is completed
        $student = \App\Models\Student::find($studentId);
        if (!$student || !$student->class_id) {
            return;
        }

        // Get current lesson to find its order
        $currentLesson = Lesson::find($currentLessonId);
        if (!$currentLesson) {
            return;
        }

        // Find next lesson in the same level
        $nextLesson = Lesson::where('level_id', $levelId)
            ->where('lesson_order', '>', $currentLesson->lesson_order)
            ->orderBy('lesson_order', 'asc')
            ->first();

        if ($nextLesson) {
            // Create visibility record to unlock the next lesson for this student's class
            // Note: This assumes the student's class has a teacher assigned
            $class = $student->studentClass;
            if ($class && $class->teacher_id) {
                ClassLessonVisibility::firstOrCreate(
                    [
                        'class_id' => $student->class_id,
                        'lesson_id' => $nextLesson->lesson_id,
                    ],
                    [
                        'teacher_id' => $class->teacher_id,
                        'is_visible' => true,
                    ]
                );
                
                \Log::info("Next lesson unlocked: Lesson {$nextLesson->lesson_id} for student {$studentId}");
            }
        }
    }

    /**
     * Get video watch progress for a lesson
     * 
     * @param int $lessonId
     * @return \Illuminate\Http\JsonResponse
     */
    public function getVideoProgress($lessonId)
    {
        $user = Auth::user();
        $student = $user->student;

        if (!$student) {
            return response()->json([
                'success' => false,
                'message' => 'Student profile not found.'
            ], 404);
        }

        $progress = StudentLessonProgress::where('student_id', $student->student_id)
            ->where('lesson_id', $lessonId)
            ->first();

        if (!$progress) {
            return response()->json([
                'success' => true,
                'data' => [
                    'watched_seconds' => 0,
                    'watched_percentage' => 0,
                    'last_position' => 0,
                    'max_watched_time' => 0,
                ]
            ]);
        }

        // Calculate accurate percentage from max_watched_time (most reliable)
        $lesson = Lesson::find($lessonId);
        $videoDuration = $lesson->video_duration_seconds ?? 0;
        $maxWatchedTime = $progress->max_watched_time ?? 0;
        $accuratePercentage = 0;
        
        if ($videoDuration > 0 && $maxWatchedTime > 0) {
            $accuratePercentage = round(($maxWatchedTime / $videoDuration) * 100, 2);
        } else {
            $accuratePercentage = $progress->watched_percentage ?? 0;
        }
        
        // Ensure watched_percentage is updated to match accurate calculation
        if (abs(($progress->watched_percentage ?? 0) - $accuratePercentage) > 0.01) {
            $progress->watched_percentage = $accuratePercentage;
        }
        
        // Ensure video_completed and status are set correctly based on accurate percentage
        $needsSave = false;
        if ($accuratePercentage >= 80) {
            if (!($progress->video_completed ?? false)) {
                $progress->video_completed = true;
                $needsSave = true;
            }
            if ($progress->status !== 'completed') {
                $progress->status = 'completed';
                if (!$progress->completed_at) {
                    $progress->completed_at = now();
                }
                $needsSave = true;
                
                // Also unlock games if not already unlocked
                $this->unlockLessonGame($student->student_id, $lessonId);
                // Unlock next lesson
                $this->unlockNextLesson($student->student_id, $lesson->level_id, $lessonId);
            }
        }
        
        // Save if any changes were made
        if ($needsSave || $progress->isDirty()) {
            $progress->save();
            $progress->refresh();
        }
        
        // Refresh progress to ensure we have latest values
        $progress->refresh();
        
        // Recalculate percentage one more time after refresh
        $finalVideoDuration = $lesson->video_duration_seconds ?? 0;
        $finalMaxWatchedTime = $progress->max_watched_time ?? 0;
        $finalPercentage = 0;
        
        if ($finalVideoDuration > 0 && $finalMaxWatchedTime > 0) {
            $finalPercentage = round(($finalMaxWatchedTime / $finalVideoDuration) * 100, 2);
        } else {
            $finalPercentage = $progress->watched_percentage ?? 0;
        }
        
        // Ensure video_completed reflects the actual state
        $isVideoCompleted = ($progress->video_completed ?? false) || 
                           ($progress->status === 'completed') || 
                           ($finalPercentage >= 80);
        
        return response()->json([
            'success' => true,
            'data' => [
                'watched_seconds' => $progress->watched_seconds ?? 0,
                'watched_percentage' => $finalPercentage,
                'last_position' => $progress->last_position ?? 0,
                'max_watched_time' => $finalMaxWatchedTime,
                'video_completed' => $isVideoCompleted,
                'status' => $progress->status,
            ]
        ]);
    }
}
