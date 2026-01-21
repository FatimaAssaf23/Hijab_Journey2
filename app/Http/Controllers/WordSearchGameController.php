<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Game;
use App\Models\WordSearchGame;
use App\Models\ClassLessonVisibility;

class WordSearchGameController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'word_search_lesson_id' => 'required|integer',
            'class_id' => 'nullable|exists:student_classes,class_id',
            'word_search_words' => 'required|array|min:1',
            'word_search_words.*' => 'required|string',
            'word_search_title' => 'nullable|string|max:255',
            'grid_size' => 'nullable|integer|min:8|max:20',
        ]);

        try {
            // Filter out empty words and clean them
            $words = array_filter($request->word_search_words ?? [], function($word) {
                return !empty(trim($word));
            });
            // Clean words - remove ALL non-Arabic characters (numbers, hyphens, spaces, etc.)
            // Keep ONLY Arabic letters
            $words = array_values(array_map(function($word) {
                $word = trim($word);
                // Remove everything that is NOT an Arabic character
                $cleaned = preg_replace('/[^\p{Arabic}]/u', '', $word);
                // If cleaning removed everything (shouldn't happen for valid Arabic words), keep original
                return !empty($cleaned) ? $cleaned : trim($word);
            }, $words));

            if (empty($words)) {
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'Please add at least one word.');
            }

            $gridSize = $request->grid_size ?? 10;
            $title = trim($request->word_search_title ?? '');

            // Generate the word search grid
            $gridData = $this->generateWordSearchGrid($words, $gridSize);

            // First, create or get the Game record
            $game = Game::where('lesson_id', $request->word_search_lesson_id)
                ->where('game_type', 'word_search')
                ->first();
            
            if (!$game) {
                $game = Game::create([
                    'lesson_id' => $request->word_search_lesson_id,
                    'game_type' => 'word_search',
                ]);
            }

            // Then, create or update the WordSearchGame record
            $wordSearchGame = WordSearchGame::where('lesson_id', $request->word_search_lesson_id)->first();
            
            if ($wordSearchGame) {
                $wordSearchGame->update([
                    'game_id' => $game->game_id,
                    'title' => $title ?: null,
                    'words' => $words,
                    'grid_size' => $gridSize,
                    'grid_data' => $gridData,
                ]);
            } else {
                WordSearchGame::create([
                    'game_id' => $game->game_id,
                    'lesson_id' => $request->word_search_lesson_id,
                    'title' => $title ?: null,
                    'words' => $words,
                    'grid_size' => $gridSize,
                    'grid_data' => $gridData,
                ]);
            }

            // If class_id is provided, make the lesson visible for that class
            if ($request->class_id) {
                ClassLessonVisibility::firstOrCreate(
                    [
                        'lesson_id' => $request->word_search_lesson_id,
                        'class_id' => $request->class_id,
                        'teacher_id' => Auth::id(),
                    ],
                    ['is_visible' => true]
                )->update(['is_visible' => true]);
            }

            \Log::info('Word Search Game saved', [
                'game_id' => $game->game_id,
                'lesson_id' => $request->word_search_lesson_id,
                'words_count' => count($words)
            ]);

            return redirect()->route('teacher.games', ['lesson_id' => $request->word_search_lesson_id])
                ->with('success', 'Word Search game saved successfully!');
        } catch (\Exception $e) {
            \Log::error('Word Search Game Save Error: ' . $e->getMessage());
            \Log::error('Stack trace: ' . $e->getTraceAsString());
            
            return redirect()->route('teacher.games', ['lesson_id' => $request->word_search_lesson_id ?? null])
                ->withInput()
                ->with('error', 'Failed to save Word Search game: ' . $e->getMessage());
        }
    }

    /**
     * Reverse a multibyte string (for Arabic RTL)
     */
    private function mb_strrev($string, $encoding = 'UTF-8')
    {
        $length = mb_strlen($string, $encoding);
        $reversed = '';
        for ($i = $length - 1; $i >= 0; $i--) {
            $reversed .= mb_substr($string, $i, 1, $encoding);
        }
        return $reversed;
    }

    private function generateWordSearchGrid($words, $size)
    {
        // Initialize empty grid
        $grid = array_fill(0, $size, array_fill(0, $size, ''));
        
        // Arabic letters for filling empty spaces
        $arabicLetters = ['ا', 'ب', 'ت', 'ث', 'ج', 'ح', 'خ', 'د', 'ذ', 'ر', 'ز', 'س', 'ش', 'ص', 'ض', 'ط', 'ظ', 'ع', 'غ', 'ف', 'ق', 'ك', 'ل', 'م', 'ن', 'ه', 'و', 'ي'];
        
        $wordPositions = [];
        
        // Try to place each word in the grid
        foreach ($words as $wordIndex => $word) {
            $originalWord = trim($word);
            // For Arabic RTL: Reverse the word first, then place left-to-right
            // Example: "العدالة" becomes "ةالعدالا", then place normally left-to-right
            // Grid display: col 0 = left, col N = right
            // Placement: col 0 (left) = ة, col 6 (right) = ا
            // Visual result: ا (right) ... ة (left) = correct RTL reading
            $reversedWord = $this->mb_strrev($originalWord);
            $word = $reversedWord; // Use reversed word for placement
            $wordLength = mb_strlen($word, 'UTF-8');
            
            if ($wordLength > $size) {
                continue; // Skip words that are too long
            }
            
            $placed = false;
            $attempts = 0;
            $maxAttempts = 100;
            
            while (!$placed && $attempts < $maxAttempts) {
                $attempts++;
                
                // After reversing word, place left-to-right using normal grid directions
                // But use directions that will visually appear RTL when displayed
                // All 8 directions are valid, but prefer horizontal and diagonal
                $directions = [
                    ['row' => 0, 'col' => 1],   // Horizontal left-to-right (reversed word placed normally)
                    ['row' => 0, 'col' => 1],   // Horizontal (double weight)
                    ['row' => 1, 'col' => 0],   // Vertical top-to-bottom
                    ['row' => -1, 'col' => 0],  // Vertical bottom-to-top
                    ['row' => 1, 'col' => 1],   // Diagonal top-left to bottom-right
                    ['row' => -1, 'col' => -1], // Diagonal bottom-right to top-left
                    ['row' => 1, 'col' => -1],  // Diagonal top-right to bottom-left
                    ['row' => -1, 'col' => 1],  // Diagonal bottom-left to top-right
                ];
                
                $direction = $directions[array_rand($directions)];
                
                // Place reversed word left-to-right (normal grid logic)
                // Calculate starting position based on direction
                if ($direction['row'] == 0 && $direction['col'] == 1) {
                    // Horizontal left-to-right: start from left
                    $row = rand(0, $size - 1);
                    $col = rand(0, $size - $wordLength);
                } elseif ($direction['row'] != 0 && $direction['col'] != 0) {
                    // Diagonal: need space in both row and column directions
                    $row = rand(
                        $direction['row'] > 0 ? 0 : ($wordLength - 1),
                        $direction['row'] > 0 ? ($size - $wordLength) : ($size - 1)
                    );
                    $col = rand(
                        $direction['col'] > 0 ? 0 : ($wordLength - 1),
                        $direction['col'] > 0 ? ($size - $wordLength) : ($size - 1)
                    );
                } elseif ($direction['row'] != 0) {
                    // Vertical: need space in row direction
                    $row = rand(
                        $direction['row'] > 0 ? 0 : ($wordLength - 1),
                        $direction['row'] > 0 ? ($size - $wordLength) : ($size - 1)
                    );
                    $col = rand(0, $size - 1);
                } else {
                    // Horizontal (shouldn't reach here, but just in case)
                    $row = rand(0, $size - 1);
                    $col = rand(0, $size - $wordLength);
                }
                
                // Check if word fits in this direction
                $canPlace = true;
                $positions = [];
                
                // Place reversed word left-to-right (normal grid indexing)
                // Word was already reversed: "العدالة" → "ةالعدالا"
                // Now place it normally left-to-right: col 0 = ة, col 1 = ل, etc.
                // This will visually display right-to-left in Arabic
                for ($i = 0; $i < $wordLength; $i++) {
                    $checkRow = $row + ($direction['row'] * $i);
                    $checkCol = $col + ($direction['col'] * $i);
                    
                    // Check bounds
                    if ($checkRow < 0 || $checkRow >= $size || $checkCol < 0 || $checkCol >= $size) {
                        $canPlace = false;
                        break;
                    }
                    
                    // Word is already reversed, place left-to-right normally
                    // i=0: first letter of reversed word at starting position (left in grid)
                    // i=length-1: last letter of reversed word at ending position (right in grid)
                    // Visual result: original first letter (ا) appears on right, last letter (ة) on left
                    $letter = mb_substr($word, $i, 1, 'UTF-8');
                    
                    // Verify cell is empty or has matching letter (allow crossing)
                    if (!empty($grid[$checkRow][$checkCol]) && $grid[$checkRow][$checkCol] !== $letter) {
                        $canPlace = false;
                        break;
                    }
                    
                    // Store position with correct letter
                    $positions[] = ['row' => $checkRow, 'col' => $checkCol, 'letter' => $letter];
                }
                
                if ($canPlace) {
                    // Place the reversed word in the grid
                    foreach ($positions as $pos) {
                        $grid[$pos['row']][$pos['col']] = $pos['letter'];
                    }
                    
                    // Reverse the positions array to match visual order
                    // When word is reversed and placed left-to-right, positions[0] has last letter visually
                    // We need positions to match visual order: first letter (right) to last letter (left)
                    $reversedPositions = array_reverse($positions);
                    
                    // Store original word (not reversed) for matching
                    // Positions array now matches visual order: first letter (ا) at right, last letter (ة) at left
                    $wordPositions[] = [
                        'word' => $originalWord, // Store original word for matching (not reversed)
                        'wordIndex' => $wordIndex,
                        'positions' => $reversedPositions, // Reversed to match visual RTL order
                        'direction' => $direction
                    ];
                    
                    $placed = true;
                }
            }
        }
        
        // Fill empty cells with random Arabic letters
        for ($row = 0; $row < $size; $row++) {
            for ($col = 0; $col < $size; $col++) {
                if (empty($grid[$row][$col])) {
                    $grid[$row][$col] = $arabicLetters[array_rand($arabicLetters)];
                }
            }
        }
        
        return [
            'grid' => $grid,
            'word_positions' => $wordPositions,
            'size' => $size
        ];
    }
}
