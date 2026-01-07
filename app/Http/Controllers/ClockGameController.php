<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Game;

class ClockGameController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'clock_lesson_id' => 'required|integer',
            'clock_words' => 'required|array',
            'clock_words.*' => 'required|string',
        ]);

        $game = Game::where('lesson_id', $request->clock_lesson_id)
            ->where('game_type', 'clock')
            ->first();
        if ($game) {
            $game->game_data = json_encode(['words' => $request->clock_words]);
            $game->save();
        } else {
            Game::create([
                'lesson_id' => $request->clock_lesson_id,
                'game_type' => 'clock',
                'game_data' => json_encode(['words' => $request->clock_words]),
            ]);
        }

        return redirect()->route('teacher.games', ['lesson_id' => $request->clock_lesson_id])
            ->with('success', 'Clock game saved successfully!');
    }
}
