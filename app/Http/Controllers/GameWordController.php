<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\GameWordPair;

class GameWordController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $pairs = GameWordPair::all()->toArray();
        return view('games', compact('pairs'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'words' => 'required|array',
            'definitions' => 'required|array',
        ]);
        // Remove all previous pairs (for demo: one global set)
        GameWordPair::truncate();
        foreach ($validated['words'] as $i => $word) {
            $def = $validated['definitions'][$i] ?? '';
            if ($word && $def) {
                GameWordPair::create(['word' => $word, 'definition' => $def]);
            }
        }
        return redirect()->route('teacher.games');
    }

    public function destroy($id)
    {
        $pair = GameWordPair::findOrFail($id);
        $pair->delete();
        return redirect()->route('teacher.games');
    }
}
