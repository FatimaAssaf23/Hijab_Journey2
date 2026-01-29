<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\StudentGameProgress;
use App\Models\Game;
use App\Models\ClockGame;
use App\Models\WordSearchGame;
use App\Models\MatchingPairsGame;
use App\Models\Lesson;
use App\Models\QuizAttempt;
use App\Models\Quiz;
use App\Models\ClassLessonVisibility;

class StudentGradeController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $student = $user->student;
        
        if (!$student) {
            return redirect()->route('student.dashboard')
                ->with('error', 'Student profile not found.');
        }
        
        // Build scores only from what the student actually played (StudentGameProgress)
        $progresses = StudentGameProgress::where('student_id', $student->student_id)
            ->where('status', 'completed')
            ->with('game')
            ->get();

        // Group progresses by lesson_id via related Game
        $progressesByLesson = $progresses->filter(function ($p) {
                return $p->game !== null && $p->game->lesson_id !== null;
            })
            ->groupBy(function ($p) {
                return $p->game->lesson_id;
            });

        $lessonScores = [];

        foreach ($progressesByLesson as $lessonId => $lessonProgresses) {
            $lesson = Lesson::find($lessonId);
            if (!$lesson) {
                continue;
            }

            $scores = [];
            $totalScore = 0;
            $scoredGamesCount = 0;

            foreach ($lessonProgresses as $progress) {
                $game = $progress->game;
                if (!$game || $progress->score === null) {
                    continue;
                }

                $scores[] = [
                    'game_type' => $game->game_type,
                    'score' => $progress->score,
                    'game_name' => $this->getGameName($game->game_type),
                ];

                $totalScore += $progress->score;
                $scoredGamesCount++;
            }

            if ($scoredGamesCount > 0) {
                $averageScore = round($totalScore / $scoredGamesCount);
                $lessonScores[] = [
                    'lesson' => $lesson,
                    'scores' => $scores,
                    'total_score' => $totalScore,
                    'average_score' => $averageScore,
                    'games_count' => $scoredGamesCount,
                ];
            }
        }
        
        // Get all quiz attempts for this student (ordered by most recent first)
        $quizAttempts = QuizAttempt::where('student_id', $student->student_id)
            ->whereNotNull('submitted_at')
            ->with(['quiz.level', 'quiz.questions'])
            ->orderBy('submitted_at', 'desc')
            ->get();
        
        // Organize quiz attempts by quiz
        $quizGrades = [];
        foreach ($quizAttempts as $attempt) {
            $quizId = $attempt->quiz_id;
            $score = round($attempt->score ?? 0, 2);
            
            if (!isset($quizGrades[$quizId])) {
                // First time seeing this quiz - initialize with this attempt (most recent)
                $quizGrades[$quizId] = [
                    'quiz' => $attempt->quiz,
                    'attempts' => [],
                    'best_score' => $score,
                    'latest_score' => $score, // This is the most recent since we're iterating desc
                    'attempts_count' => 1,
                    'latest_attempt' => $attempt
                ];
            } else {
                // Update best score if this attempt is better
                if ($score > $quizGrades[$quizId]['best_score']) {
                    $quizGrades[$quizId]['best_score'] = $score;
                }
                $quizGrades[$quizId]['attempts_count']++;
            }
            
            // Add attempt to list
            $quizGrades[$quizId]['attempts'][] = [
                'attempt' => $attempt,
                'score' => $score,
                'submitted_at' => $attempt->submitted_at
            ];
        }
        
        // Check quiz grades and unlock next level's first lesson if student passed (score >= 60)
        $this->checkAndUnlockNextLevels($student, $quizGrades);
        
        return view('student.grades', compact('lessonScores', 'quizGrades'));
    }
    
    /**
     * Check quiz grades and unlock next level's first lesson for passed quizzes
     * 
     * @param \App\Models\Student $student
     * @param array $quizGrades
     * @return void
     */
    private function checkAndUnlockNextLevels($student, $quizGrades)
    {
        $passingScore = 60;
        
        foreach ($quizGrades as $quizData) {
            $quiz = $quizData['quiz'];
            $latestScore = $quizData['latest_score'];
            
            // Only process if student passed the quiz
            if ($latestScore >= $passingScore && $quiz->level) {
                $this->unlockNextLevelFirstLesson($student, $quiz->level);
            }
        }
    }
    
    /**
     * Check if student can access next level's first lesson after passing quiz.
     * Only unlocks if the lesson exists AND teacher has already made it visible.
     * If lesson doesn't exist or isn't visible, waits for teacher action.
     * 
     * @param \App\Models\Student $student
     * @param \App\Models\Level $currentLevel
     * @return bool Returns true if lesson is accessible, false otherwise
     */
    private function unlockNextLevelFirstLesson($student, $currentLevel)
    {
        if (!$student || !$student->class_id) {
            \Log::warning("Cannot check next level: Student {$student->student_id} not found or has no class_id");
            return false;
        }

        // Get the next level
        $nextLevel = $currentLevel->nextLevel();
        if (!$nextLevel) {
            \Log::info("No next level found for level {$currentLevel->level_id} (Level {$currentLevel->level_name})");
            return false;
        }

        // Get the first lesson of the next level
        $firstLesson = $nextLevel->firstLesson();
        if (!$firstLesson) {
            \Log::info("No first lesson found in next level {$nextLevel->level_id} (Level {$nextLevel->level_name}). Waiting for teacher to upload lesson.");
            return false;
        }

        // Get student's class
        $class = $student->studentClass;
        if (!$class) {
            \Log::warning("Cannot check lesson: Student {$student->student_id} has no studentClass");
            return false;
        }
        
        if (!$class->teacher_id) {
            \Log::warning("Cannot check lesson: Class {$class->class_id} has no teacher_id");
            return false;
        }

        // CRITICAL: Check if teacher has already made this lesson visible
        // Only proceed if teacher has explicitly unlocked it
        $teacherVisibility = ClassLessonVisibility::where('class_id', $student->class_id)
            ->where('lesson_id', $firstLesson->lesson_id)
            ->where('teacher_id', $class->teacher_id)
            ->where('is_visible', true)
            ->first();
        
        if (!$teacherVisibility) {
            // Lesson exists but teacher hasn't made it visible yet - wait for teacher action
            \Log::info("Lesson {$firstLesson->lesson_id} '{$firstLesson->title}' exists but teacher hasn't made it visible yet. Student {$student->student_id} has met prerequisites (passed quiz), but waiting for teacher to unlock the lesson.");
            return false;
        }

        // Lesson exists AND teacher has made it visible
        // Student has met prerequisites (passed quiz), so they can now access it
        \Log::info("Student {$student->student_id} can now access next level first lesson: Lesson {$firstLesson->lesson_id} '{$firstLesson->title}' (Level {$nextLevel->level_name}). Teacher has made it visible and student has passed the quiz.");
        
        return true;
    }
    
    private function getGameName($gameType)
    {
        $names = [
            'clock' => 'Clock Game',
            'scrambled_clocks' => 'Scrambled Clocks',
            'word_clock_arrangement' => 'Word Clock Arrangement',
            'word_search' => 'Word Search Puzzle',
            'matching_pairs' => 'Matching Pairs Game',
            'mcq' => 'Multiple Choice',
            'scramble' => 'Scrambled Letters'
        ];
        
        return $names[$gameType] ?? ucfirst(str_replace('_', ' ', $gameType));
    }
}
