<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use App\Models\Game;
use App\Models\MatchingPairsGame;
use App\Models\MatchingPair;
use App\Models\ClassLessonVisibility;

class MatchingPairsGameController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'matching_pairs_lesson_id' => 'required|integer',
            'class_id' => 'nullable|exists:student_classes,class_id',
            'pairs' => 'required|array|min:1',
            'pairs.*.left_item_text' => 'nullable|string|max:500',
            'pairs.*.left_item_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'pairs.*.right_item_text' => 'nullable|string|max:500',
            'pairs.*.right_item_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'title' => 'nullable|string|max:255',
        ]);

        try {
            $lessonId = $request->matching_pairs_lesson_id;
            $pairs = $request->pairs ?? [];
            
            // Filter out pairs that have at least one field filled
            $validPairs = array_filter($pairs, function($pair) {
                return !empty($pair['left_item_text']) || 
                       !empty($pair['left_item_image']) ||
                       !empty($pair['right_item_text']) || 
                       !empty($pair['right_item_image']);
            });

            if (empty($validPairs)) {
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'Please add at least one matching pair.');
            }

            // First, create or get the Game record
            $game = Game::where('lesson_id', $lessonId)
                ->where('game_type', 'matching_pairs')
                ->first();
            
            if (!$game) {
                $game = Game::create([
                    'lesson_id' => $lessonId,
                    'game_type' => 'matching_pairs',
                ]);
            }

            // Create or update the MatchingPairsGame record
            $matchingPairsGame = MatchingPairsGame::where('lesson_id', $lessonId)->first();
            
            if ($matchingPairsGame) {
                // Get old pairs before deleting to delete images
                $oldPairs = MatchingPair::where('matching_pairs_game_id', $matchingPairsGame->matching_pairs_game_id)->get();
                
                // Delete old images
                foreach ($oldPairs as $oldPair) {
                    if ($oldPair->left_item_image && Storage::disk('public')->exists($oldPair->left_item_image)) {
                        Storage::disk('public')->delete($oldPair->left_item_image);
                    }
                    if ($oldPair->right_item_image && Storage::disk('public')->exists($oldPair->right_item_image)) {
                        Storage::disk('public')->delete($oldPair->right_item_image);
                    }
                }
                
                // Delete old pairs
                MatchingPair::where('matching_pairs_game_id', $matchingPairsGame->matching_pairs_game_id)->delete();
                
                $matchingPairsGame->update([
                    'game_id' => $game->game_id,
                    'title' => $request->title ?: null,
                ]);
            } else {
                $matchingPairsGame = MatchingPairsGame::create([
                    'game_id' => $game->game_id,
                    'lesson_id' => $lessonId,
                    'title' => $request->title ?: null,
                ]);
            }

            // Save pairs
            foreach ($validPairs as $index => $pairData) {
                $leftImagePath = null;
                $rightImagePath = null;

                // Handle left item image upload
                if (isset($pairData['left_item_image']) && is_object($pairData['left_item_image']) && $pairData['left_item_image']->isValid()) {
                    $file = $pairData['left_item_image'];
                    $filename = time() . '_' . $index . '_left_' . $file->getClientOriginalName();
                    $leftImagePath = $file->storeAs('matching-pairs-images', $filename, 'public');
                }

                // Handle right item image upload
                if (isset($pairData['right_item_image']) && is_object($pairData['right_item_image']) && $pairData['right_item_image']->isValid()) {
                    $file = $pairData['right_item_image'];
                    $filename = time() . '_' . $index . '_right_' . $file->getClientOriginalName();
                    $rightImagePath = $file->storeAs('matching-pairs-images', $filename, 'public');
                }

                MatchingPair::create([
                    'matching_pairs_game_id' => $matchingPairsGame->matching_pairs_game_id,
                    'left_item_text' => !empty($pairData['left_item_text']) ? $pairData['left_item_text'] : null,
                    'left_item_image' => $leftImagePath,
                    'right_item_text' => !empty($pairData['right_item_text']) ? $pairData['right_item_text'] : null,
                    'right_item_image' => $rightImagePath,
                    'order' => $index,
                ]);
            }

            // If class_id is provided, make the lesson visible for that class
            if ($request->class_id) {
                ClassLessonVisibility::firstOrCreate(
                    [
                        'lesson_id' => $lessonId,
                        'class_id' => $request->class_id,
                        'teacher_id' => Auth::id(),
                    ],
                    ['is_visible' => true]
                )->update(['is_visible' => true]);
            }

            \Log::info('Matching Pairs Game saved', [
                'game_id' => $game->game_id,
                'lesson_id' => $lessonId,
                'pairs_count' => count($validPairs)
            ]);

            return redirect()->route('teacher.games', ['lesson_id' => $lessonId])
                ->with('success', 'Matching Pairs game saved successfully!');
        } catch (\Exception $e) {
            \Log::error('Matching Pairs Game Save Error: ' . $e->getMessage());
            \Log::error('Stack trace: ' . $e->getTraceAsString());
            
            return redirect()->route('teacher.games', ['lesson_id' => $request->matching_pairs_lesson_id ?? null])
                ->withInput()
                ->with('error', 'Failed to save Matching Pairs game: ' . $e->getMessage());
        }
    }
}
