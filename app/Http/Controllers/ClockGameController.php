<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Game;
use App\Models\ClockGame;

class ClockGameController extends Controller
{
    public function store(Request $request)
    {
        try {
            $request->validate([
                'clock_lesson_id' => 'required|integer',
                'clock_words' => 'required|array',
                'clock_words.*' => 'required|string',
            ]);

            // First, create or get the Game record
            $game = Game::where('lesson_id', $request->clock_lesson_id)
                ->where('game_type', 'clock')
                ->first();
            
            if (!$game) {
                $game = Game::create([
                    'lesson_id' => $request->clock_lesson_id,
                    'game_type' => 'clock',
                ]);
                \Log::info('Game record created', ['game_id' => $game->game_id, 'lesson_id' => $request->clock_lesson_id]);
            } else {
                \Log::info('Game record found', ['game_id' => $game->game_id, 'lesson_id' => $request->clock_lesson_id]);
            }

            // Verify game was created successfully
            if (!$game || !$game->game_id) {
                throw new \Exception('Failed to create or retrieve Game record');
            }

            // Then, create or update the ClockGame record
            $clockGame = ClockGame::where('lesson_id', $request->clock_lesson_id)->first();
            
            if ($clockGame) {
                \Log::info('Updating existing ClockGame', ['clock_game_id' => $clockGame->clock_game_id]);
                $clockGame->game_id = $game->game_id;
                $clockGame->words = $request->clock_words;
                $clockGame->save();
            } else {
                \Log::info('Creating new ClockGame', ['game_id' => $game->game_id, 'lesson_id' => $request->clock_lesson_id]);
                $clockGame = new ClockGame();
                $clockGame->game_id = $game->game_id;
                $clockGame->lesson_id = $request->clock_lesson_id;
                $clockGame->words = $request->clock_words;
                $clockGame->save();
                
                if (!$clockGame->clock_game_id) {
                    throw new \Exception('Failed to create ClockGame record - no ID returned');
                }
            }
            
            \Log::info('Clock Game saved successfully', [
                'clock_game_id' => $clockGame->clock_game_id,
                'game_id' => $game->game_id,
                'lesson_id' => $request->clock_lesson_id,
                'words_count' => count($request->clock_words)
            ]);

            return redirect()->route('teacher.games', ['lesson_id' => $request->clock_lesson_id])
                ->with('success', 'Clock game saved successfully!');
        } catch (\Exception $e) {
            \Log::error('Clock Game Save Error: ' . $e->getMessage());
            \Log::error('Stack trace: ' . $e->getTraceAsString());
            
            return redirect()->route('teacher.games', ['lesson_id' => $request->clock_lesson_id ?? null])
                ->with('error', 'Failed to save clock game: ' . $e->getMessage());
        }
    }
}
