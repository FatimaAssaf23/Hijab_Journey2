<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\GameWordPair;
use App\Models\WordSearchGame;
use App\Models\ClassLessonVisibility;

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

        // Get all classes for this teacher
        $classes = \App\Models\StudentClass::where('teacher_id', $user->user_id)->get();

        // Get selected lesson and game type from request
        $selectedLessonId = $request->input('lesson_id');
        $selectedGameType = $request->input('game_type', null); // Explicitly set to null if not provided

        $scramblePairs = collect();
        $clockGame = null;
        $scrambledClocksGame = null;
        $wordClockArrangementGame = null;
        $wordSearchGame = null;
        $matchingPairsGame = null;

        if ($selectedLessonId) {
            // Fetch word/definition pairs directly for this lesson (separated by game type)
            $scramblePairs = \App\Models\GroupWordPair::where('lesson_id', $selectedLessonId)
                ->where('game_type', 'scramble')
                ->get();
            // Fetch clock game for this lesson from clock_games table
            $clockGame = \App\Models\ClockGame::where('lesson_id', $selectedLessonId)->first();
            // Fetch scrambled clocks game for this lesson
            $scrambledClocksGame = \App\Models\Game::where('lesson_id', $selectedLessonId)
                ->where('game_type', 'scrambled_clocks')
                ->first();
            // Fetch word clock arrangement game for this lesson
            $wordClockArrangementGame = \App\Models\Game::where('lesson_id', $selectedLessonId)
                ->where('game_type', 'word_clock_arrangement')
                ->first();
            // Fetch word search game for this lesson
            $wordSearchGame = \App\Models\WordSearchGame::where('lesson_id', $selectedLessonId)->first();
            // Fetch matching pairs game for this lesson
            $matchingPairsGame = \App\Models\MatchingPairsGame::where('lesson_id', $selectedLessonId)->with('pairs')->first();
        }

        return view('games', compact('lessons', 'scramblePairs', 'selectedLessonId', 'selectedGameType', 'clockGame', 'scrambledClocksGame', 'wordClockArrangementGame', 'wordSearchGame', 'matchingPairsGame', 'classes'));
    }

    public function store(Request $request)
    {
        // Handle saving word/definition pairs directly for a lesson with game_type
        if ($request->has('lesson_id') && $request->has('words') && $request->has('definitions') && $request->has('game_type')) {
            $request->validate([
                'lesson_id' => 'required|integer',
                'class_id' => 'nullable|exists:student_classes,class_id',
                'game_type' => 'required|string',
            ]);

            $lessonId = $request->input('lesson_id');
            $gameType = $request->input('game_type'); // 'mcq' or 'scramble'
            $words = $request->input('words');
            $definitions = $request->input('definitions');
            // Remove empty pairs
            $pairs = array_filter(array_map(function($w, $d) {
                return (trim($w) !== '' && trim($d) !== '') ? ['word' => $w, 'definition' => $d] : null;
            }, $words, $definitions));
            // Save each pair directly to the lesson with game_type
            foreach ($pairs as $pair) {
                \App\Models\GroupWordPair::create([
                    'lesson_id' => $lessonId,
                    'game_type' => $gameType,
                    'word' => $pair['word'],
                    'definition' => $pair['definition'],
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
            
            // Redirect to show the lesson and its pairs
            return redirect()->route('teacher.games', ['lesson_id' => $lessonId])
                ->with('success', 'Word pairs saved successfully!');
        }

        return redirect()->route('teacher.games');
    }
}
