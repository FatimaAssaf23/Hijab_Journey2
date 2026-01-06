<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LessonGroup;
use App\Models\GroupWordPair;
use App\Models\Lesson;

class LessonGroupController extends Controller
{
    // Show all groups for a lesson
    public function index($lesson_id)
    {
        $lesson = Lesson::findOrFail($lesson_id);
        $groups = $lesson->lessonGroups;
        return view('teacher.lesson-groups.index', compact('lesson', 'groups'));
    }

    // Create a new group for a lesson
    public function store(Request $request, $lesson_id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);
        $group = LessonGroup::create([
            'lesson_id' => $lesson_id,
            'name' => $request->name,
        ]);
        return redirect()->route('teacher.lesson-groups.index', $lesson_id);
    }

    // Add word/definition pairs to a group
    public function addPair(Request $request, $group_id)
    {
        $request->validate([
            'word' => 'required|string',
            'definition' => 'required|string',
        ]);
        GroupWordPair::create([
            'lesson_group_id' => $group_id,
            'word' => $request->word,
            'definition' => $request->definition,
        ]);
        $group = LessonGroup::findOrFail($group_id);
        return redirect()->route('teacher.lesson-groups.index', $group->lesson_id);
    }
}
