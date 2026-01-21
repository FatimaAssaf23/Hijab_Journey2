<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Game;
use App\Models\ClassLessonVisibility;

class WordClockArrangementController extends Controller
{
    public function store(Request $request)
    {
        // Filter out empty word entries before validation
        $wordClockWords = array_filter($request->word_clock_words ?? [], function($wordData) {
            return !empty($wordData['word']) && isset($wordData['hour']) && isset($wordData['minute']);
        });
        
        // Re-index the array
        $wordClockWords = array_values($wordClockWords);
        
        // Replace the request data with filtered data
        $request->merge(['word_clock_words' => $wordClockWords]);
        
        $request->validate([
            'word_clock_lesson_id' => 'required|integer',
            'class_id' => 'nullable|exists:student_classes,class_id',
            'word_clock_word' => 'required|string',
            'word_clock_sentence' => 'required|string',
            'word_clock_words' => 'required|array|min:1',
            'word_clock_words.*.word' => 'required|string',
            'word_clock_words.*.hour' => 'required|integer|min:0|max:11',
            'word_clock_words.*.minute' => 'required|integer|min:0|max:59',
        ]);

        // Validate that all clock times are unique (only for non-empty entries)
        $clockTimes = [];
        foreach ($wordClockWords as $wordData) {
            if (empty($wordData['word']) || !isset($wordData['hour']) || !isset($wordData['minute'])) {
                continue; // Skip empty entries
            }
            $timeKey = $wordData['hour'] . ':' . $wordData['minute'];
            if (isset($clockTimes[$timeKey])) {
                return redirect()->back()
                    ->withInput()
                    ->withErrors(['word_clock_words' => 'Each word must have a unique clock time.']);
            }
            $clockTimes[$timeKey] = true;
        }

        // Calculate correct order based on clock times (only non-empty entries)
        $wordsWithTimes = array_map(function($wordData) {
            return [
                'word' => $wordData['word'],
                'hour' => (int)$wordData['hour'],
                'minute' => (int)$wordData['minute'],
                'time' => (int)$wordData['hour'] * 60 + (int)$wordData['minute'],
            ];
        }, $wordClockWords);

        // Sort by time to get correct order
        usort($wordsWithTimes, function($a, $b) {
            return $a['time'] - $b['time'];
        });

        $correctOrder = array_column($wordsWithTimes, 'word');

        $gameData = [
            'word' => $request->word_clock_word,
            'full_sentence' => $request->word_clock_sentence,
            'words' => $wordClockWords,
            'correct_order' => $correctOrder,
            'clock_times' => array_map(function($w) {
                return [
                    'word' => $w['word'],
                    'hour' => $w['hour'],
                    'minute' => $w['minute'],
                ];
            }, $wordClockWords),
        ];

        try {
            $game = Game::where('lesson_id', $request->word_clock_lesson_id)
                ->where('game_type', 'word_clock_arrangement')
                ->first();

            if ($game) {
                $game->game_data = json_encode($gameData);
                $game->save();
                \Log::info('Word Clock Arrangement Game updated', [
                    'game_id' => $game->game_id,
                    'lesson_id' => $request->word_clock_lesson_id
                ]);
            } else {
                $game = Game::create([
                    'lesson_id' => $request->word_clock_lesson_id,
                    'game_type' => 'word_clock_arrangement',
                    'game_data' => json_encode($gameData),
                ]);
                \Log::info('Word Clock Arrangement Game created', [
                    'game_id' => $game->game_id,
                    'lesson_id' => $request->word_clock_lesson_id
                ]);
            }

            // If class_id is provided, make the lesson visible for that class
            if ($request->class_id) {
                ClassLessonVisibility::firstOrCreate(
                    [
                        'lesson_id' => $request->word_clock_lesson_id,
                        'class_id' => $request->class_id,
                        'teacher_id' => Auth::id(),
                    ],
                    ['is_visible' => true]
                )->update(['is_visible' => true]);
            }

            return redirect()->route('teacher.games', ['lesson_id' => $request->word_clock_lesson_id])
                ->with('success', 'Word Clock Arrangement game saved successfully!');
        } catch (\Exception $e) {
            \Log::error('Word Clock Arrangement Game Save Error: ' . $e->getMessage());
            \Log::error('Stack trace: ' . $e->getTraceAsString());
            
            return redirect()->route('teacher.games', ['lesson_id' => $request->word_clock_lesson_id ?? null])
                ->withInput()
                ->with('error', 'Failed to save Word Clock Arrangement game: ' . $e->getMessage());
        }
    }
}
