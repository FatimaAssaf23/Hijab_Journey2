<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\GameWordPair;

class GameWordController extends Controller
{
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'word' => 'required|string',
            'definition' => 'required|string',
        ]);
        $pair = GameWordPair::findOrFail($id);
        $pair->word = $validated['word'];
        $pair->definition = $validated['definition'];
        $pair->save();
        return redirect()->route('teacher.games');
    }
    public function index(Request $request)
    {
        $user = Auth::user();
        // Fetch all lessons for this teacher or uploaded by admin
        $lessons = \App\Models\Lesson::where(function ($query) use ($user) {
            $query->where('teacher_id', $user->user_id)
                  ->orWhereNotNull('uploaded_by_admin_id');
        })->get();

        // Get selected lesson and group from request
        $selectedLessonId = $request->input('lesson_id');
        $selectedGroupId = $request->input('group_id');

        $groups = collect();
        $pairs = collect();
        $clockGame = null;

        // Removed group creation logic from index. Now handled in store.

        if ($selectedLessonId) {
            $groups = \App\Models\LessonGroup::where('lesson_id', $selectedLessonId)->get();
            if ($selectedGroupId) {
                $pairs = \App\Models\GroupWordPair::where('lesson_group_id', $selectedGroupId)->get();
            }
            // Fetch clock game for this lesson
            $clockGame = \App\Models\Game::where('lesson_id', $selectedLessonId)
                ->where('game_type', 'clock')
                ->first();
        }

        return view('games', compact('lessons', 'groups', 'pairs', 'selectedLessonId', 'selectedGroupId', 'clockGame'));
    }

    public function store(Request $request)
    {
        // Handle group creation
        if ($request->input('create_groups') && $request->input('lesson_id')) {
            $selectedLessonId = $request->input('lesson_id');
            $groupName = $request->input('group_name');
            if ($groupName) {
                $group = \App\Models\LessonGroup::firstOrCreate([
                    'lesson_id' => $selectedLessonId,
                    'name' => $groupName
                ]);
                return redirect()->route('teacher.games', ['lesson_id' => $selectedLessonId, 'group_id' => $group->id]);
            }
        }

        // Handle saving word/definition pairs for a group
        if ($request->has('group_id') && $request->has('words') && $request->has('definitions')) {
            $groupId = $request->input('group_id');
            $words = $request->input('words');
            $definitions = $request->input('definitions');
            // Remove empty pairs
            $pairs = array_filter(array_map(function($w, $d) {
                return (trim($w) !== '' && trim($d) !== '') ? ['word' => $w, 'definition' => $d] : null;
            }, $words, $definitions));
            // Save each pair
            foreach ($pairs as $pair) {
                \App\Models\GroupWordPair::create([
                    'lesson_group_id' => $groupId,
                    'word' => $pair['word'],
                    'definition' => $pair['definition'],
                ]);
            }
            // Redirect to show the group and its pairs
            $group = \App\Models\LessonGroup::find($groupId);
            $lessonId = $group ? $group->lesson_id : null;
            return redirect()->route('teacher.games', ['lesson_id' => $lessonId, 'group_id' => $groupId]);
        }

        return redirect()->route('teacher.games');
    }
}
