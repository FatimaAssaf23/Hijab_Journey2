<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Level;

class LevelController extends Controller
{
    public function updateName(Request $request)
    {
        $request->validate([
            'level_id' => 'required|exists:levels,level_id',
            'level_name' => 'required|string|max:255',
        ]);
        $level = Level::findOrFail($request->level_id);
        $level->level_name = $request->level_name;
        $level->save();
        return redirect()->back()->with('success', 'Level name updated successfully!');
	}
}

