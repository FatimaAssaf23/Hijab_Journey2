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
        $assignmentsStats = [
            'total' => $assignments->count(),
            'submitted' => $submissions->count(),
            'pending' => max(0, $assignments->count() - $submissions->count()),
            'completed_percentage' => $assignments->count() > 0 
                ? round(($submissions->count() / $assignments->count()) * 100) 
                : 0,
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
            ]
        );

        // Update video tracking fields
        $progress->watched_seconds = $request->watched_seconds;
        $progress->watched_percentage = round($request->watched_percentage, 2);
        $progress->last_position = $request->current_position ?? $progress->last_position;
        $progress->max_watched_time = max(
            $progress->max_watched_time ?? 0,
            $request->max_watched_time ?? 0
        );
        $progress->last_watched_at = now();

        // Update time_spent_minutes (convert seconds to minutes, rounded)
        $progress->time_spent_minutes = round($request->watched_seconds / 60);

        // RULE 1: If watched_percentage >= 80%, mark video_completed = true
        $isVideoCompleted = $request->watched_percentage >= 80;
        $wasVideoCompleted = $progress->video_completed ?? false;
        $progress->video_completed = $isVideoCompleted;

        // If video just became completed, unlock the game
        if ($isVideoCompleted && !$wasVideoCompleted) {
            $this->unlockLessonGame($student->student_id, $lessonId);
        }

        // Check if game is completed
        $isGameCompleted = $this->checkGameCompletion($student->student_id, $lessonId);

        // RULE 3: If game is passed, mark lesson completed
        if ($isGameCompleted && $progress->status !== 'completed') {
            $progress->status = 'completed';
            $progress->completed_at = now();
            
            // RULE 4: Unlock next lesson when current lesson is completed
            $this->unlockNextLesson($student->student_id, $lesson->level_id, $lessonId);
        } else if ($isVideoCompleted && !$isGameCompleted) {
            // Video completed but game not completed yet - keep status as in_progress
            if ($progress->status === 'not_started') {
                $progress->status = 'in_progress';
                if (!$progress->started_at) {
                    $progress->started_at = now();
                }
            }
        } else if ($progress->status === 'not_started') {
            $progress->status = 'in_progress';
            if (!$progress->started_at) {
                $progress->started_at = now();
            }
        }

        $progress->save();

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
     * Unlock the lesson game when video is completed
     * 
     * @param int $studentId
     * @param int $lessonId
     * @return void
     */
    private function unlockLessonGame($studentId, $lessonId)
    {
        // RULE 2: When video is completed, unlock the lesson game
        // Check if lesson has a game
        $game = Game::where('lesson_id', $lessonId)->first();
        
        if ($game) {
            // Create or update game progress record to allow access
            StudentGameProgress::firstOrCreate(
                [
                    'student_id' => $studentId,
                    'game_id' => $game->game_id,
                ],
                [
                    'status' => 'not_started',
                    'score' => 0,
                    'attempts' => 0,
                ]
            );
            
            \Log::info("Game unlocked for student {$studentId}, lesson {$lessonId}");
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

        return response()->json([
            'success' => true,
            'data' => [
                'watched_seconds' => $progress->watched_seconds ?? 0,
                'watched_percentage' => $progress->watched_percentage ?? 0,
                'last_position' => $progress->last_position ?? 0,
                'max_watched_time' => $progress->max_watched_time ?? 0,
                'status' => $progress->status,
            ]
        ]);
    }
}
