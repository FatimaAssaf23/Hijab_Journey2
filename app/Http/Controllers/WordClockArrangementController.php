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
        // Log the raw request data for debugging
        \Log::info('Word Clock Arrangement - Raw request data', [
            'word_clock_words_raw' => $request->word_clock_words,
            'word_clock_words_type' => gettype($request->word_clock_words),
            'all_input' => $request->all()
        ]);
        
        // FIX: Handle both indexed and non-indexed array formats
        $rawWordClockWords = $request->word_clock_words ?? [];
        
        // Convert non-indexed arrays to indexed format
        // Laravel may receive data in different formats depending on how the form submits
        if (!empty($rawWordClockWords)) {
            // Check if data is already in the correct format (indexed array of arrays)
            $isProperlyIndexed = true;
            $firstKey = array_key_first($rawWordClockWords);
            
            if ($firstKey !== null) {
                // Check if first element is an array with expected keys
                if (is_array($rawWordClockWords[$firstKey])) {
                    // Check if it has the expected structure
                    if (!isset($rawWordClockWords[$firstKey]['word']) && 
                        !isset($rawWordClockWords[$firstKey]['hour']) && 
                        !isset($rawWordClockWords[$firstKey]['minute'])) {
                        $isProperlyIndexed = false;
                    }
                } else {
                    $isProperlyIndexed = false;
                }
            }
            
            // If not properly indexed, try to reorganize
            if (!$isProperlyIndexed) {
                // Check if we have a transposed structure (keys are 'word', 'hour', 'minute' with arrays of values)
                if (isset($rawWordClockWords['word']) || isset($rawWordClockWords['hour']) || isset($rawWordClockWords['minute'])) {
                    // Reorganize from transposed format to indexed format
                    $reorganized = [];
                    $maxIndex = 0;
                    
                    // Find the maximum index
                    foreach (['word', 'hour', 'minute'] as $field) {
                        if (isset($rawWordClockWords[$field]) && is_array($rawWordClockWords[$field])) {
                            $keys = array_keys($rawWordClockWords[$field]);
                            if (!empty($keys)) {
                                $maxIndex = max($maxIndex, max($keys));
                            }
                        }
                    }
                    
                    // Reorganize the data
                    for ($i = 0; $i <= $maxIndex; $i++) {
                        $reorganized[$i] = [
                            'word' => $rawWordClockWords['word'][$i] ?? '',
                            'hour' => $rawWordClockWords['hour'][$i] ?? '',
                            'minute' => $rawWordClockWords['minute'][$i] ?? '',
                        ];
                    }
                    
                    if (!empty($reorganized)) {
                        $rawWordClockWords = $reorganized;
                    }
                } else {
                    // Try to extract data from any other structure
                    // Sometimes Laravel parses bracket notation differently
                    $reorganized = [];
                    foreach ($rawWordClockWords as $key => $value) {
                        if (is_array($value) && (isset($value['word']) || isset($value['hour']) || isset($value['minute']))) {
                            $reorganized[] = [
                                'word' => $value['word'] ?? '',
                                'hour' => $value['hour'] ?? '',
                                'minute' => $value['minute'] ?? '',
                            ];
                        }
                    }
                    if (!empty($reorganized)) {
                        $rawWordClockWords = $reorganized;
                    }
                }
            }
        }
        
        \Log::info('Word Clock Arrangement - After reorganization', [
            'reorganized_data' => $rawWordClockWords,
            'reorganized_count' => is_array($rawWordClockWords) ? count($rawWordClockWords) : 0
        ]);
        
        // Filter out empty word entries before validation
        $wordClockWords = array_filter($rawWordClockWords, function($wordData) {
            if (!is_array($wordData)) {
                return false;
            }
            $hasWord = !empty($wordData['word'] ?? '');
            $hasHour = isset($wordData['hour']) && $wordData['hour'] !== '' && $wordData['hour'] !== null;
            $hasMinute = isset($wordData['minute']) && $wordData['minute'] !== '' && $wordData['minute'] !== null;
            
            return $hasWord && $hasHour && $hasMinute;
        });
        
        // Re-index the array
        $wordClockWords = array_values($wordClockWords);
        
        \Log::info('Word Clock Arrangement - After filtering', [
            'filtered_count' => count($wordClockWords),
            'filtered_data' => $wordClockWords
        ]);
        
        // Replace the request data with filtered and properly indexed data
        $request->merge(['word_clock_words' => $wordClockWords]);
        
        // Check if this is an AJAX request
        $isAjax = $request->expectsJson() || $request->ajax();
        
        try {
            $validated = $request->validate([
                'word_clock_lesson_id' => 'required|integer',
                'class_id' => 'nullable|exists:student_classes,class_id',
                'word_clock_word' => 'required|string',
                'word_clock_sentence' => 'required|string',
                'word_clock_words' => 'required|array|min:1',
                'word_clock_words.*.word' => 'required|string',
                'word_clock_words.*.hour' => 'required|integer|min:0|max:11',
                'word_clock_words.*.minute' => 'required|integer|min:0|max:59',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            \Log::error('Word Clock Arrangement Validation Error', [
                'errors' => $e->errors(),
                'input' => $request->all()
            ]);
            
            if ($isAjax) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $e->errors()
                ], 422);
            }
            
            $redirectUrl = route('teacher.games');
            if ($request->word_clock_lesson_id) {
                $redirectUrl .= '?lesson_id=' . $request->word_clock_lesson_id;
            }
            
            return redirect($redirectUrl)
                ->withInput()
                ->withErrors($e->errors())
                ->with('error', 'Please check your input and try again.');
        }

        // Validate that all clock times are unique (only for non-empty entries)
        $clockTimes = [];
        foreach ($wordClockWords as $wordData) {
            if (empty($wordData['word']) || !isset($wordData['hour']) || !isset($wordData['minute'])) {
                continue; // Skip empty entries
            }
            $timeKey = $wordData['hour'] . ':' . $wordData['minute'];
            if (isset($clockTimes[$timeKey])) {
                if ($isAjax) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Each word must have a unique clock time.',
                        'errors' => ['word_clock_words' => ['Each word must have a unique clock time.']]
                    ], 422);
                }
                
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
            \Log::info('Word Clock Arrangement Game - Starting save process', [
                'lesson_id' => $request->word_clock_lesson_id,
                'word_count' => count($wordClockWords),
                'game_data' => $gameData
            ]);
            
            $game = Game::where('lesson_id', $request->word_clock_lesson_id)
                ->where('game_type', 'word_clock_arrangement')
                ->first();

            if ($game) {
                $game->game_data = json_encode($gameData);
                $saved = $game->save();
                \Log::info('Word Clock Arrangement Game updated', [
                    'game_id' => $game->game_id,
                    'lesson_id' => $request->word_clock_lesson_id,
                    'saved' => $saved,
                    'game_data_length' => strlen($game->game_data)
                ]);
                
                // Verify the data was saved
                $game->refresh();
                $savedData = json_decode($game->game_data, true);
                \Log::info('Word Clock Arrangement Game - Verified saved data', [
                    'has_data' => !empty($savedData),
                    'word_count' => isset($savedData['words']) ? count($savedData['words']) : 0
                ]);
            } else {
                $game = Game::create([
                    'lesson_id' => $request->word_clock_lesson_id,
                    'game_type' => 'word_clock_arrangement',
                    'game_data' => json_encode($gameData),
                ]);
                \Log::info('Word Clock Arrangement Game created', [
                    'game_id' => $game->game_id,
                    'lesson_id' => $request->word_clock_lesson_id,
                    'game_data_length' => strlen($game->game_data)
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

            // Build redirect URL with query parameter
            $redirectUrl = route('teacher.games');
            if ($request->word_clock_lesson_id) {
                $redirectUrl .= '?lesson_id=' . $request->word_clock_lesson_id;
            }
            
            if ($isAjax) {
                return response()->json([
                    'success' => true,
                    'message' => 'Word Clock Arrangement game saved successfully!',
                    'redirect_url' => $redirectUrl
                ]);
            }

            return redirect($redirectUrl)
                ->with('success', 'Word Clock Arrangement game saved successfully!');
        } catch (\Exception $e) {
            \Log::error('Word Clock Arrangement Game Save Error: ' . $e->getMessage());
            \Log::error('Stack trace: ' . $e->getTraceAsString());
            
            if ($isAjax) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to save Word Clock Arrangement game: ' . $e->getMessage()
                ], 500);
            }
            
            $redirectUrl = route('teacher.games');
            if ($request->word_clock_lesson_id) {
                $redirectUrl .= '?lesson_id=' . $request->word_clock_lesson_id;
            }
            
            return redirect($redirectUrl)
                ->withInput()
                ->with('error', 'Failed to save Word Clock Arrangement game: ' . $e->getMessage());
        }
    }
}
