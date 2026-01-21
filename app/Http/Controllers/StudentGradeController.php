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
        
        // Get all lessons that have games
        // Get lesson IDs from all game sources
        $clockGameLessonIds = ClockGame::pluck('lesson_id')->unique();
        $wordSearchGameLessonIds = WordSearchGame::pluck('lesson_id')->unique();
        $matchingPairsGameLessonIds = MatchingPairsGame::pluck('lesson_id')->unique();
        $otherGameLessonIds = Game::whereIn('game_type', ['scrambled_clocks', 'word_clock_arrangement'])
            ->pluck('lesson_id')
            ->unique();
        // Get lesson IDs from games table that have mcq or scramble (they're created when scores are saved)
        $quizGameLessonIds = Game::whereIn('game_type', ['mcq', 'scramble'])
            ->pluck('lesson_id')
            ->unique();
        
        $allGameLessonIds = $clockGameLessonIds->merge($wordSearchGameLessonIds)
            ->merge($matchingPairsGameLessonIds)
            ->merge($otherGameLessonIds)
            ->merge($quizGameLessonIds)
            ->unique();
        
        $lessons = Lesson::whereIn('lesson_id', $allGameLessonIds)->get();
        
        // Calculate scores for each lesson
        $lessonScores = [];
        
        foreach ($lessons as $lesson) {
            $scores = [];
            $totalScore = 0;
            $scoredGamesCount = 0;
            
            // Get scores from games table (clock, scrambled_clocks, word_clock_arrangement, mcq, scramble)
            $games = Game::where('lesson_id', $lesson->lesson_id)
                ->whereIn('game_type', ['clock', 'scrambled_clocks', 'word_clock_arrangement', 'mcq', 'scramble'])
                ->get();
            
            foreach ($games as $game) {
                $progress = StudentGameProgress::where('game_id', $game->game_id)
                    ->where('student_id', $student->student_id)
                    ->first();
                
                if ($progress && $progress->score !== null) {
                    $scores[] = [
                        'game_type' => $game->game_type,
                        'score' => $progress->score,
                        'game_name' => $this->getGameName($game->game_type)
                    ];
                    $totalScore += $progress->score;
                    $scoredGamesCount++;
                }
            }
            
            // Get score from word search games
            $wordSearchGame = WordSearchGame::where('lesson_id', $lesson->lesson_id)->first();
            if ($wordSearchGame && $wordSearchGame->game_id) {
                $progress = StudentGameProgress::where('game_id', $wordSearchGame->game_id)
                    ->where('student_id', $student->student_id)
                    ->first();
                
                if ($progress && $progress->score !== null) {
                    $scores[] = [
                        'game_type' => 'word_search',
                        'score' => $progress->score,
                        'game_name' => 'Word Search Puzzle'
                    ];
                    $totalScore += $progress->score;
                    $scoredGamesCount++;
                }
            }
            
            // Get score from matching pairs games
            $matchingPairsGame = MatchingPairsGame::where('lesson_id', $lesson->lesson_id)->first();
            if ($matchingPairsGame && $matchingPairsGame->game_id) {
                $progress = StudentGameProgress::where('game_id', $matchingPairsGame->game_id)
                    ->where('student_id', $student->student_id)
                    ->first();
                
                if ($progress && $progress->score !== null) {
                    $scores[] = [
                        'game_type' => 'matching_pairs',
                        'score' => $progress->score,
                        'game_name' => 'Matching Pairs Game'
                    ];
                    $totalScore += $progress->score;
                    $scoredGamesCount++;
                }
            }
            
            // Note: MCQ and Scrambled Letters scores are calculated on-the-fly from quiz results
            // They don't have game_id in StudentGameProgress, so we'll need to calculate them differently
            // For now, we'll show scores from games that have StudentGameProgress entries
            
            // Calculate average score
            $averageScore = $scoredGamesCount > 0 ? round($totalScore / $scoredGamesCount) : null;
            
            if ($scoredGamesCount > 0) {
                $lessonScores[] = [
                    'lesson' => $lesson,
                    'scores' => $scores,
                    'total_score' => $totalScore,
                    'average_score' => $averageScore,
                    'games_count' => $scoredGamesCount
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
        
        return view('student.grades', compact('lessonScores', 'quizGrades'));
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
