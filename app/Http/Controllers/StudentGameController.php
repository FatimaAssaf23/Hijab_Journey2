<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Models\GameWordPair;
use App\Models\GroupWordPair;
use App\Models\ClockGame;
use App\Models\WordSearchGame;
use App\Models\MatchingPairsGame;

class StudentGameController extends Controller
{
    public function index(Request $request)
    {
        $lessonId = $request->input('lesson_id');
        $selectedLessonId = $lessonId; // Initialize for use in compact()
        $scrambledClocksGame = null;
        $clockGame = null;
        $wordClockArrangementGame = null;
        $wordSearchGame = null;
        $matchingPairsGame = null;
        $lesson = null;

        // Get all lessons that have games (for dropdown)
        // Get lesson IDs from clock_games table
        $clockGameLessonIds = ClockGame::pluck('lesson_id')->unique();
        // Get lesson IDs from word_search_games table
        $wordSearchGameLessonIds = WordSearchGame::pluck('lesson_id')->unique();
        // Get lesson IDs from matching_pairs_games table
        $matchingPairsGameLessonIds = MatchingPairsGame::pluck('lesson_id')->unique();
        // Get lesson IDs from games table for other game types
        $otherGameLessonIds = \App\Models\Game::whereIn('game_type', ['scrambled_clocks', 'word_clock_arrangement', 'word_search', 'matching_pairs'])
            ->pluck('lesson_id')
            ->unique();
        // Get lesson IDs from group_word_pairs table (word/definition pairs) - either MCQ or Scramble
        $wordPairLessonIds = GroupWordPair::whereNotNull('lesson_id')
            ->whereIn('game_type', ['mcq', 'scramble'])
            ->pluck('lesson_id')
            ->unique();
        // Merge all lesson IDs
        $gameLessonIds = $clockGameLessonIds->merge($wordSearchGameLessonIds)->merge($matchingPairsGameLessonIds)->merge($otherGameLessonIds)->merge($wordPairLessonIds)->unique();
        $lessonsWithGames = \App\Models\Lesson::whereIn('lesson_id', $gameLessonIds)->get();

        if ($lessonId) {
            $lesson = \App\Models\Lesson::find($lessonId);
            $scrambledClocksGame = \App\Models\Game::where('lesson_id', $lessonId)
                ->where('game_type', 'scrambled_clocks')
                ->first();
            // Get clock game from clock_games table
            $clockGame = ClockGame::where('lesson_id', $lessonId)->first();
            $wordClockArrangementGame = \App\Models\Game::where('lesson_id', $lessonId)
                ->where('game_type', 'word_clock_arrangement')
                ->first();
            // Get word search game from word_search_games table
            $wordSearchGame = WordSearchGame::where('lesson_id', $lessonId)->first();
            // Get matching pairs game from matching_pairs_games table
            $matchingPairsGame = MatchingPairsGame::where('lesson_id', $lessonId)->with('pairs')->first();
        }
        
        // Get current student
        $student = null;
        if (auth()->check() && auth()->user()->student) {
            $student = auth()->user()->student;
        }

        // Get pairs for the selected lesson separated by game type
        $mcqPairs = collect();
        $scramblePairs = collect();
        if ($lessonId) {
            $mcqPairs = GroupWordPair::where('lesson_id', $lessonId)
                ->where('game_type', 'mcq')
                ->get();
            $scramblePairs = GroupWordPair::where('lesson_id', $lessonId)
                ->where('game_type', 'scramble')
                ->get();
        }
        
        if ($mcqPairs->isEmpty() && $scramblePairs->isEmpty() && !$scrambledClocksGame && !$clockGame && !$wordClockArrangementGame && !$wordSearchGame && !$matchingPairsGame) {
            return view('student.games', [
                'error' => 'No quiz data available. Ask your teacher to add words.', 
                'scrambledClocksGame' => $scrambledClocksGame, 
                'clockGame' => $clockGame,
                'wordClockArrangementGame' => $wordClockArrangementGame,
                'wordSearchGame' => $wordSearchGame,
                'matchingPairsGame' => $matchingPairsGame,
                'lesson' => $lesson,
                'lessonsWithGames' => $lessonsWithGames,
                'selectedLessonId' => $lessonId,
                'student' => $student,
                'mcqPairs' => $mcqPairs,
                'scramblePairs' => $scramblePairs
            ]);
        }
        return view('student.games', compact('mcqPairs', 'scramblePairs', 'scrambledClocksGame', 'clockGame', 'wordClockArrangementGame', 'wordSearchGame', 'matchingPairsGame', 'lesson', 'lessonsWithGames', 'selectedLessonId', 'student'));
    }

    public function quiz(Request $request)
    {
        $lessonId = $request->input('lesson_id');
        $type = $request->input('type', 'mcq');
        
        // Get pairs for the selected lesson based on game type
        if ($lessonId) {
            if ($type === 'scramble') {
                $pairs = GroupWordPair::where('lesson_id', $lessonId)
                    ->where('game_type', 'scramble')
                    ->get()
                    ->toArray();
            } else {
                $pairs = GroupWordPair::where('lesson_id', $lessonId)
                    ->where('game_type', 'mcq')
                    ->get()
                    ->toArray();
            }
        } else {
            if ($type === 'scramble') {
                $pairs = GroupWordPair::whereNotNull('lesson_id')
                    ->where('game_type', 'scramble')
                    ->get()
                    ->toArray();
            } else {
                $pairs = GroupWordPair::whereNotNull('lesson_id')
                    ->where('game_type', 'mcq')
                    ->get()
                    ->toArray();
            }
        }
        
        if (empty($pairs)) {
            return response()->json(['error' => 'No quiz data available.'], 422);
        }
        
        if ($type === 'scramble') {
            $quiz = $this->generateScrambleQuiz($pairs);
        } else {
            $quiz = $this->generateMcqQuiz($pairs);
        }
        return response()->json(['quiz' => $quiz]);
    }

    private function generateMcqQuiz($pairs)
    {
        $questions = [];
        $words = array_column($pairs, 'word');
        foreach ($pairs as $pair) {
            $correct = $pair['word'];
            $definition = $pair['definition'];
            $options = [$correct];
            $otherWords = array_diff($words, [$correct]);
            shuffle($otherWords);
            $options = array_merge($options, array_slice($otherWords, 0, 3));
            shuffle($options);
            $questions[] = [
                'definition' => $definition,
                'options' => $options,
                'answer' => $correct,
            ];
        }
        shuffle($questions);
        return $questions;
    }

    private function generateScrambleQuiz($pairs)
    {
        $questions = [];
        foreach ($pairs as $pair) {
            $questions[] = [
                'definition' => $pair['definition'],
                'answer' => $pair['word'],
            ];
        }
        shuffle($questions);
        return $questions;
    }

    public function saveScore(Request $request)
    {
        $request->validate([
            'score' => 'required|integer|min:0|max:100',
        ]);

        $user = auth()->user();
        if (!$user || !$user->student) {
            return response()->json(['error' => 'Student not found'], 403);
        }

        $student = $user->student;
        $game = null;
        $gameId = null;
        
        // Handle different game types
        if ($request->has('game_id')) {
            // Traditional games (word search, scrambled clocks, etc.) that already have game_id
            $game = \App\Models\Game::find($request->game_id);
            if (!$game) {
                return response()->json(['error' => 'Game not found'], 404);
            }
            $gameId = $request->game_id;
        } elseif ($request->has('lesson_id') && $request->has('game_type')) {
            // MCQ or Scrambled Letters - need to create/find Game entry
            $lessonId = $request->lesson_id;
            $gameType = $request->game_type; // 'mcq' or 'scramble'
            
            // Validate game_type
            if (!in_array($gameType, ['mcq', 'scramble'])) {
                return response()->json(['error' => 'Invalid game type'], 400);
            }
            
            // Create or find Game entry for this lesson and game type
            $game = \App\Models\Game::firstOrCreate(
                [
                    'lesson_id' => $lessonId,
                    'game_type' => $gameType,
                ],
                [
                    'game_data' => json_encode([]),
                ]
            );
            $gameId = $game->game_id;
        } else {
            return response()->json(['error' => 'Either game_id or (lesson_id and game_type) required'], 400);
        }

        // Get existing progress to increment attempts
        $existingProgress = \App\Models\StudentGameProgress::where('game_id', $gameId)
            ->where('student_id', $student->student_id)
            ->first();
        
        $attempts = $existingProgress ? $existingProgress->attempts + 1 : 1;
        
        $progress = \App\Models\StudentGameProgress::updateOrCreate(
            [
                'game_id' => $gameId,
                'student_id' => $student->student_id,
            ],
            [
                'status' => 'completed',
                'score' => $request->score,
                'completed_at' => now(),
                'attempts' => $attempts,
            ]
        );

        return response()->json([
            'success' => true,
            'score' => $progress->score,
            'message' => 'Score saved successfully!'
        ]);
    }
}
