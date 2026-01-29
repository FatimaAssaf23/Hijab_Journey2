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
        // Handle empty string as null
        $lessonId = $lessonId ?: null;
        $selectedLessonId = $lessonId; // Initialize for use in compact()
        $scrambledClocksGame = null;
        $clockGame = null;
        $wordClockArrangementGame = null;
        $wordSearchGame = null;
        $matchingPairsGame = null;
        $lesson = null;

        // Get current student and their class
        $student = null;
        $studentClassId = null;
        if (auth()->check() && auth()->user()->student) {
            $student = auth()->user()->student;
            $studentClassId = $student->class_id;
        }

        // Get all lessons that have games for this student's class (for dropdown)
        // Get lesson IDs from clock_games table filtered by class
        $clockGameLessonIds = $studentClassId 
            ? ClockGame::where('class_id', $studentClassId)->pluck('lesson_id')->unique()
            : collect();
        // Get lesson IDs from word_search_games table filtered by class
        $wordSearchGameLessonIds = $studentClassId 
            ? WordSearchGame::where('class_id', $studentClassId)->pluck('lesson_id')->unique()
            : collect();
        // Get lesson IDs from matching_pairs_games table filtered by class
        $matchingPairsGameLessonIds = $studentClassId 
            ? MatchingPairsGame::where('class_id', $studentClassId)->pluck('lesson_id')->unique()
            : collect();
        // Get lesson IDs from games table for other game types filtered by class
        $otherGameLessonIds = $studentClassId 
            ? \App\Models\Game::where('class_id', $studentClassId)
                ->whereIn('game_type', ['scrambled_clocks', 'word_clock_arrangement', 'word_search', 'matching_pairs'])
                ->pluck('lesson_id')
                ->unique()
            : collect();
        // Get lesson IDs from group_word_pairs table (word/definition pairs) - Scramble filtered by class
        $scramblePairLessonIds = $studentClassId 
            ? GroupWordPair::whereNotNull('lesson_id')
                ->where('class_id', $studentClassId)
                ->where('game_type', 'scramble')
                ->pluck('lesson_id')
                ->unique()
            : collect();
        // Get lesson IDs from group_word_pairs table (word/definition pairs) - MCQ filtered by class
        $mcqPairLessonIds = $studentClassId 
            ? GroupWordPair::whereNotNull('lesson_id')
                ->where('class_id', $studentClassId)
                ->where('game_type', 'mcq')
                ->pluck('lesson_id')
                ->unique()
            : collect();
        // Merge all lesson IDs
        $gameLessonIds = $clockGameLessonIds->merge($wordSearchGameLessonIds)->merge($matchingPairsGameLessonIds)->merge($otherGameLessonIds)->merge($scramblePairLessonIds)->merge($mcqPairLessonIds)->unique();
        $lessonsWithGames = \App\Models\Lesson::whereIn('lesson_id', $gameLessonIds)->get();

        // Collect all games for the lesson and class in sequential order
        $gamesInOrder = [];
        
        // Helper: ensure a corresponding row exists in `games` table for progress tracking.
        // This project uses specialized tables (clock_games, word_search_games, matching_pairs_games)
        // but also relies on `games.game_id` for StudentGameProgress + score saving.
        $ensureGameModel = function (int $lessonId, int $classId, string $gameType) {
            return \App\Models\Game::firstOrCreate(
                [
                    'lesson_id' => $lessonId,
                    'class_id' => $classId,
                    'game_type' => $gameType,
                ],
                [
                    'game_data' => null,
                ]
            );
        };
        
        \Log::info('StudentGameController - Starting game collection', [
            'lessonId' => $lessonId,
            'studentClassId' => $studentClassId,
            'student' => $student ? $student->student_id : null
        ]);
        
        if ($lessonId && $studentClassId) {
            $lesson = \App\Models\Lesson::find($lessonId);
            \Log::info('StudentGameController - Lesson found', ['lesson' => $lesson ? $lesson->title : 'NOT FOUND']);
            
            // Auto-initialize games for this student/lesson if they haven't been initialized yet
            // This ensures games are always accessible even if initialization didn't run during registration
            if ($student) {
                try {
                    $progressController = new \App\Http\Controllers\StudentProgressController();
                    $progressController->unlockLessonGame($student->student_id, $lessonId);
                } catch (\Exception $e) {
                    \Log::warning('Failed to auto-initialize games in StudentGameController: ' . $e->getMessage(), [
                        'student_id' => $student->student_id,
                        'lesson_id' => $lessonId
                    ]);
                }
            }
            
            // Get all games for this lesson and class, ordered by creation time
            // First try with class_id, then fallback to without class_id (for backward compatibility)
            
            // 1. Clock Game
            $clockGame = ClockGame::where('lesson_id', $lessonId)
                ->where('class_id', $studentClassId)
                ->first();
            // Fallback: check without class_id if not found
            if (!$clockGame) {
                $clockGame = ClockGame::where('lesson_id', $lessonId)
                    ->whereNull('class_id')
                    ->first();
            }
            \Log::info('StudentGameController - Clock Game check', [
                'found' => $clockGame ? 'YES' : 'NO',
                'clock_game_id' => $clockGame ? $clockGame->clock_game_id : null,
                'has_class_id' => $clockGame ? ($clockGame->class_id ? 'YES' : 'NO') : 'N/A'
            ]);
            if ($clockGame) {
                // Update class_id if it was null (for backward compatibility)
                if (!$clockGame->class_id) {
                    $clockGame->class_id = $studentClassId;
                    $clockGame->save();
                }
                // Ensure a `games` row exists; otherwise this lesson will look like it has no games.
                $clockGameModel = $ensureGameModel((int)$lessonId, (int)$studentClassId, 'clock');
                $gamesInOrder[] = ['type' => 'clock', 'game' => $clockGame, 'gameModel' => $clockGameModel];
                \Log::info('StudentGameController - Clock Game added to gamesInOrder', [
                    'game_id' => $clockGameModel->game_id ?? null
                ]);
            }
            
            // 2. Scrambled Clocks Game
            $scrambledClocksGame = \App\Models\Game::where('lesson_id', $lessonId)
                ->where('class_id', $studentClassId)
                ->where('game_type', 'scrambled_clocks')
                ->first();
            if (!$scrambledClocksGame) {
                $scrambledClocksGame = \App\Models\Game::where('lesson_id', $lessonId)
                    ->whereNull('class_id')
                    ->where('game_type', 'scrambled_clocks')
                    ->first();
                if ($scrambledClocksGame && !$scrambledClocksGame->class_id) {
                    $scrambledClocksGame->class_id = $studentClassId;
                    $scrambledClocksGame->save();
                }
            }
            if ($scrambledClocksGame) {
                $gamesInOrder[] = ['type' => 'scrambled_clocks', 'game' => $scrambledClocksGame, 'gameModel' => $scrambledClocksGame];
            }
            
            // 3. Word Clock Arrangement Game
            $wordClockArrangementGame = \App\Models\Game::where('lesson_id', $lessonId)
                ->where('class_id', $studentClassId)
                ->where('game_type', 'word_clock_arrangement')
                ->first();
            if (!$wordClockArrangementGame) {
                $wordClockArrangementGame = \App\Models\Game::where('lesson_id', $lessonId)
                    ->whereNull('class_id')
                    ->where('game_type', 'word_clock_arrangement')
                    ->first();
                if ($wordClockArrangementGame && !$wordClockArrangementGame->class_id) {
                    $wordClockArrangementGame->class_id = $studentClassId;
                    $wordClockArrangementGame->save();
                }
            }
            if ($wordClockArrangementGame) {
                $gamesInOrder[] = ['type' => 'word_clock_arrangement', 'game' => $wordClockArrangementGame, 'gameModel' => $wordClockArrangementGame];
            }
            
            // 4. Word Search Game
            $wordSearchGame = WordSearchGame::where('lesson_id', $lessonId)
                ->where('class_id', $studentClassId)
                ->first();
            // Fallback: check without class_id if not found
            if (!$wordSearchGame) {
                $wordSearchGame = WordSearchGame::where('lesson_id', $lessonId)
                    ->whereNull('class_id')
                    ->first();
            }
            \Log::info('StudentGameController - Word Search Game check', [
                'found' => $wordSearchGame ? 'YES' : 'NO',
                'word_search_game_id' => $wordSearchGame ? $wordSearchGame->word_search_game_id : null,
                'has_class_id' => $wordSearchGame ? ($wordSearchGame->class_id ? 'YES' : 'NO') : 'N/A'
            ]);
            if ($wordSearchGame) {
                // Update class_id if it was null (for backward compatibility)
                if (!$wordSearchGame->class_id) {
                    $wordSearchGame->class_id = $studentClassId;
                    $wordSearchGame->save();
                }
                // Ensure a `games` row exists for word search (progress + score saving).
                $wordSearchGameModel = $ensureGameModel((int)$lessonId, (int)$studentClassId, 'word_search');
                \Log::info('StudentGameController - Word Search Game Model check', [
                    'found' => $wordSearchGameModel ? 'YES' : 'NO',
                    'game_id' => $wordSearchGameModel ? $wordSearchGameModel->game_id : null
                ]);
                $gamesInOrder[] = ['type' => 'word_search', 'game' => $wordSearchGame, 'gameModel' => $wordSearchGameModel];
                \Log::info('StudentGameController - Word Search Game added to gamesInOrder', [
                    'gamesInOrder_count' => count($gamesInOrder),
                    'game_id' => $wordSearchGameModel->game_id ?? null
                ]);
            }
            
            // 5. Matching Pairs Game
            $matchingPairsGame = MatchingPairsGame::where('lesson_id', $lessonId)
                ->where('class_id', $studentClassId)
                ->with('pairs')
                ->first();
            // Fallback: check without class_id if not found
            if (!$matchingPairsGame) {
                $matchingPairsGame = MatchingPairsGame::where('lesson_id', $lessonId)
                    ->whereNull('class_id')
                    ->with('pairs')
                    ->first();
                if ($matchingPairsGame && !$matchingPairsGame->class_id) {
                    $matchingPairsGame->class_id = $studentClassId;
                    $matchingPairsGame->save();
                }
            }
            if ($matchingPairsGame) {
                $matchingPairsGameModel = $ensureGameModel((int)$lessonId, (int)$studentClassId, 'matching_pairs');
                $gamesInOrder[] = ['type' => 'matching_pairs', 'game' => $matchingPairsGame, 'gameModel' => $matchingPairsGameModel];
            }
            
            // 6. Scramble Game
            $scramblePairs = GroupWordPair::where('lesson_id', $lessonId)
                ->where('class_id', $studentClassId)
                ->where('game_type', 'scramble')
                ->get();
            // Fallback: check without class_id if not found
            if ($scramblePairs->isEmpty()) {
                $scramblePairs = GroupWordPair::where('lesson_id', $lessonId)
                    ->whereNull('class_id')
                    ->where('game_type', 'scramble')
                    ->get();
                // Update class_id for all pairs found
                if ($scramblePairs->isNotEmpty()) {
                    GroupWordPair::where('lesson_id', $lessonId)
                        ->whereNull('class_id')
                        ->where('game_type', 'scramble')
                        ->update(['class_id' => $studentClassId]);
                    $scramblePairs = GroupWordPair::where('lesson_id', $lessonId)
                        ->where('class_id', $studentClassId)
                        ->where('game_type', 'scramble')
                        ->get();
                }
            }
            if ($scramblePairs->isNotEmpty()) {
                // Ensure `games` row exists so the game shows up and can be tracked.
                $scrambleGame = $ensureGameModel((int)$lessonId, (int)$studentClassId, 'scramble');
                $gamesInOrder[] = ['type' => 'scramble', 'pairs' => $scramblePairs, 'gameModel' => $scrambleGame];
            }
            
            // 7. MCQ Game
            $mcqPairs = GroupWordPair::where('lesson_id', $lessonId)
                ->where('class_id', $studentClassId)
                ->where('game_type', 'mcq')
                ->get();
            // Fallback: check without class_id if not found
            if ($mcqPairs->isEmpty()) {
                $mcqPairs = GroupWordPair::where('lesson_id', $lessonId)
                    ->whereNull('class_id')
                    ->where('game_type', 'mcq')
                    ->get();
                // Update class_id for all pairs found
                if ($mcqPairs->isNotEmpty()) {
                    GroupWordPair::where('lesson_id', $lessonId)
                        ->whereNull('class_id')
                        ->where('game_type', 'mcq')
                        ->update(['class_id' => $studentClassId]);
                    $mcqPairs = GroupWordPair::where('lesson_id', $lessonId)
                        ->where('class_id', $studentClassId)
                        ->where('game_type', 'mcq')
                        ->get();
                }
            }
            if ($mcqPairs->isNotEmpty()) {
                // Ensure `games` row exists so the game shows up and can be tracked.
                $mcqGame = $ensureGameModel((int)$lessonId, (int)$studentClassId, 'mcq');
                $gamesInOrder[] = ['type' => 'mcq', 'pairs' => $mcqPairs, 'gameModel' => $mcqGame];
            }
            
            // 8. Check for any Game records without specific game type tables
            $otherGames = \App\Models\Game::where('lesson_id', $lessonId)
                ->where(function($query) use ($studentClassId) {
                    $query->where('class_id', $studentClassId)
                          ->orWhereNull('class_id');
                })
                ->whereNotIn('game_type', ['scrambled_clocks', 'word_clock_arrangement', 'clock', 'word_search', 'matching_pairs', 'scramble', 'mcq'])
                ->get();
            
            foreach ($otherGames as $otherGame) {
                if (!$otherGame->class_id) {
                    $otherGame->class_id = $studentClassId;
                    $otherGame->save();
                }
                $gamesInOrder[] = ['type' => $otherGame->game_type, 'game' => $otherGame, 'gameModel' => $otherGame];
            }
        } else {
            $lesson = $lessonId ? \App\Models\Lesson::find($lessonId) : null;
            $scramblePairs = collect();
            $mcqPairs = collect();
            $scrambledClocksGame = null;
            $clockGame = null;
            $wordClockArrangementGame = null;
            $wordSearchGame = null;
            $matchingPairsGame = null;
        }
        
        // Build game ID mapping for progress tracking
        $completedGameIds = [];
        $gameTypeToGameIdMap = [];
        
        if ($student && !empty($gamesInOrder)) {
            // Build game ID mapping from gamesInOrder
            foreach ($gamesInOrder as $gameData) {
                if (isset($gameData['gameModel']) && $gameData['gameModel']) {
                    $gameType = $gameData['type'];
                    $gameId = $gameData['gameModel']->game_id;
                    
                    // Map different game type names
                    $mapKey = $gameType;
                    if ($gameType === 'scrambled_clocks') $mapKey = 'scrambledclocks';
                    elseif ($gameType === 'word_clock_arrangement') $mapKey = 'wordclock';
                    elseif ($gameType === 'word_search') $mapKey = 'wordsearch';
                    elseif ($gameType === 'matching_pairs') $mapKey = 'matchingpairs';
                    
                    $gameTypeToGameIdMap[$mapKey] = $gameId;
                }
            }
            
            // Fetch all completed progress records
            if (!empty($gameTypeToGameIdMap)) {
                $completedGameIds = \App\Models\StudentGameProgress::where('student_id', $student->student_id)
                    ->whereIn('game_id', array_values($gameTypeToGameIdMap))
                    ->where('status', 'completed')
                    ->pluck('game_id')
                    ->toArray();
            }
        }

        // Initialize gamesInOrder if not set
        if (!isset($gamesInOrder)) {
            $gamesInOrder = [];
        }
        
        \Log::info('StudentGameController - Final gamesInOrder', [
            'count' => count($gamesInOrder),
            'games' => array_map(function($g) {
                return ['type' => $g['type'] ?? 'unknown'];
            }, $gamesInOrder)
        ]);
        
        // Check if there are any games
        if (empty($gamesInOrder)) {
            \Log::warning('StudentGameController - No games found for lesson and class', [
                'lessonId' => $lessonId,
                'studentClassId' => $studentClassId
            ]);
            return view('student.games', [
                'error' => 'No games available for this lesson and class. Ask your teacher to add games.', 
                'lesson' => $lesson ?? null,
                'lessonsWithGames' => $lessonsWithGames,
                'selectedLessonId' => $lessonId,
                'student' => $student,
                'gamesInOrder' => [],
                'completedGameIds' => $completedGameIds ?? [],
                'gameTypeToGameIdMap' => $gameTypeToGameIdMap ?? []
            ]);
        }
        
        \Log::info('StudentGameController - Returning view with data', [
            'gamesInOrder_count' => count($gamesInOrder),
            'has_lesson' => isset($lesson),
            'selectedLessonId' => $lessonId,
            'has_student' => isset($student)
        ]);
        
        return view('student.games', [
            'gamesInOrder' => $gamesInOrder,
            'lesson' => $lesson ?? null,
            'lessonsWithGames' => $lessonsWithGames,
            'selectedLessonId' => $lessonId,
            'student' => $student,
            'completedGameIds' => $completedGameIds ?? [],
            'gameTypeToGameIdMap' => $gameTypeToGameIdMap ?? []
        ]);
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

        // Check if student has already completed this game
        $existingProgress = \App\Models\StudentGameProgress::where('game_id', $gameId)
            ->where('student_id', $student->student_id)
            ->first();
        
        // If already completed, prevent replay
        if ($existingProgress && $existingProgress->status === 'completed') {
            return response()->json([
                'error' => 'You have already played this game. You cannot play the same game more than once.',
                'already_completed' => true,
                'score' => $existingProgress->score
            ], 403);
        }
        
        // Create or update progress (first time only)
        $progress = \App\Models\StudentGameProgress::updateOrCreate(
            [
                'game_id' => $gameId,
                'student_id' => $student->student_id,
            ],
            [
                'status' => 'completed',
                'score' => $request->score,
                'completed_at' => now(),
                'attempts' => 1, // Always 1 - no multiple attempts allowed
            ]
        );

        // Check if lesson should be marked as completed
        $lessonId = $game->lesson_id;
        if ($lessonId) {
            $this->checkAndCompleteLesson($student->student_id, $lessonId, $game);
        }

        return response()->json([
            'success' => true,
            'score' => $progress->score,
            'message' => 'Score saved successfully!'
        ]);
    }

    /**
     * Check if lesson should be marked as completed when game is passed
     * 
     * @param int $studentId
     * @param int $lessonId
     * @param \App\Models\Game $game
     * @return void
     */
    private function checkAndCompleteLesson($studentId, $lessonId, $game)
    {
        // Check game progress for this specific game
        $gameProgress = \App\Models\StudentGameProgress::where('student_id', $studentId)
            ->where('game_id', $game->game_id)
            ->where('status', 'completed')
            ->first();

        // Game is considered "passed" when score >= 60
        if ($gameProgress && ($gameProgress->score ?? 0) >= 60) {
            // Mark lesson progress as completed (if it exists)
            $progress = \App\Models\StudentLessonProgress::where('student_id', $studentId)
                ->where('lesson_id', $lessonId)
                ->first();

            $wasLessonCompleted = $progress && $progress->status === 'completed';
            
            if ($progress && $progress->status !== 'completed') {
                $progress->status = 'completed';
                $progress->completed_at = now();
                $progress->last_activity_at = now();
                
                // If watched_percentage >= 80, also set video_completed for consistency
                if (($progress->watched_percentage ?? 0) >= 80) {
                    $progress->video_completed = true;
                }
                
                $progress->save();
            } else if (!$progress) {
                // Create progress record if it doesn't exist
                $progress = \App\Models\StudentLessonProgress::create([
                    'student_id' => $studentId,
                    'lesson_id' => $lessonId,
                    'status' => 'completed',
                    'completed_at' => now(),
                    'last_activity_at' => now(),
                ]);
            }

            // RULE: When lesson is completed (via game), unlock all games for this lesson
            // This ensures all game types are accessible even if lesson was completed by playing one game
            if (!$wasLessonCompleted) {
                $progressController = new \App\Http\Controllers\StudentProgressController();
                $progressController->unlockLessonGame($studentId, $lessonId);
            }

            // RULE: Passing the game (score >= 60) unlocks the next lesson
            $lesson = \App\Models\Lesson::find($lessonId);
            if ($lesson) {
                $progressController = new \App\Http\Controllers\StudentProgressController();
                $progressController->unlockNextLesson($studentId, $lesson->level_id, $lessonId);
            }
        }
    }
}
