@extends('layouts.app')

@section('content')
<div class="container mx-auto py-8">
    <h2 class="text-2xl font-bold mb-6">Quiz Games</h2>
    @if (!empty($error))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">{{ $error }}</div>
    @else
        <div class="mb-6 flex gap-4">
            <button id="mcqBtn" class="quizTabBtn bg-blue-500 text-white px-4 py-2 rounded">Multiple Choice</button>
            <button id="scrambleBtn" class="quizTabBtn px-4 py-2 rounded" style="background-color:#FC8EAC !important;color:white !important;">Scrambled Letters</button>
        </div>
        <div id="quizProgress" class="mb-6 flex gap-2"></div>
        <div id="quizArea" data-route="{{ route('student.games.quiz') }}"></div>
    @endif
</div>
@vite(['resources/js/quiz.js'])
<style>
    .quiz-progress-btn { min-width: 2.5rem; min-height: 2.5rem; border-radius: 9999px; display: flex; align-items: center; justify-content: center; font-weight: bold; font-size: 1.1rem; }
    .quiz-progress-btn.active { border: 2px solid #2563eb; background: #dbeafe; }
    .quiz-progress-btn.correct { background: #bbf7d0; color: #15803d; border: 2px solid #22c55e; }
    .quiz-progress-btn.wrong { background: #fecaca; color: #b91c1c; border: 2px solid #ef4444; }
    #scrambleBtn { background-color: #FC8EAC !important; color: white !important; }
</style>
@endsection
