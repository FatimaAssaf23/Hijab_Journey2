<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\GameWordPair;

class StudentGameController extends Controller
{
    public function index(Request $request)
    {
        // Get pairs from session (or database in real app)
        $pairs = GameWordPair::all()->toArray();
        if (empty($pairs)) {
            return view('student.games', ['error' => 'No quiz data available. Ask your teacher to add words.']);
        }
        return view('student.games', compact('pairs'));
    }

    public function quiz(Request $request)
    {
        $pairs = GameWordPair::all()->toArray();
        if (empty($pairs)) {
            return response()->json(['error' => 'No quiz data available.'], 422);
        }
        $type = $request->input('type', 'mcq');
        if ($type === 'scramble') {
            $quiz = $this->generateScrambleQuiz($pairs);
        } else {
            $quiz = $this->generateMcqQuiz($pairs);
        }
        return response()->json(['quiz' => $quiz]);
    }

    private function generateMcqQuiz($pairs)
    {
        $questions = [];
        $words = array_column($pairs, 'word');
        foreach ($pairs as $pair) {
            $correct = $pair['word'];
            $definition = $pair['definition'];
            $options = [$correct];
            $otherWords = array_diff($words, [$correct]);
            shuffle($otherWords);
            $options = array_merge($options, array_slice($otherWords, 0, 3));
            shuffle($options);
            $questions[] = [
                'definition' => $definition,
                'options' => $options,
                'answer' => $correct,
            ];
        }
        shuffle($questions);
        return $questions;
    }

    private function generateScrambleQuiz($pairs)
    {
        $questions = [];
        foreach ($pairs as $pair) {
            $questions[] = [
                'definition' => $pair['definition'],
                'answer' => $pair['word'],
            ];
        }
        shuffle($questions);
        return $questions;
    }
}
