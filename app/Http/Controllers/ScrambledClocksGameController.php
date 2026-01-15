<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Game;

class ScrambledClocksGameController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'scrambled_clocks_lesson_id' => 'required|integer',
            'scrambled_clocks_words' => 'required|array',
            'scrambled_clocks_words.*.word' => 'required|string',
            'scrambled_clocks_words.*.hour' => 'required|integer|min:0|max:11',
            'scrambled_clocks_words.*.minute' => 'required|integer|min:0|max:59',
            'scrambled_clocks_sentence' => 'required|string',
        ]);

        $game = Game::where('lesson_id', $request->scrambled_clocks_lesson_id)
            ->where('game_type', 'scrambled_clocks')
            ->first();
        
        $gameData = [
            'words' => $request->scrambled_clocks_words,
            'sentence' => $request->scrambled_clocks_sentence,
        ];

        if ($game) {
            $game->game_data = json_encode($gameData);
            $game->save();
        } else {
            Game::create([
                'lesson_id' => $request->scrambled_clocks_lesson_id,
                'game_type' => 'scrambled_clocks',
                'game_data' => json_encode($gameData),
            ]);
        }

        return redirect()->route('teacher.games', ['lesson_id' => $request->scrambled_clocks_lesson_id])
            ->with('success', 'Scrambled Clocks game saved successfully!');
    }
}
