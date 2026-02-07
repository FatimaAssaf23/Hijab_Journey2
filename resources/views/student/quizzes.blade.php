@extends('layouts.app')
@section('content')
<div class="w-full max-w-full mx-auto py-10 px-4 sm:px-6 lg:px-8">
    <!-- Go Back Button -->
    <div class="mb-6">
        <a href="{{ url()->previous() !== url()->current() ? url()->previous() : (Auth::check() && Auth::user()->role === 'student' ? route('student.dashboard') : '/') }}" 
           class="inline-flex items-center gap-2 text-pink-600 hover:text-pink-700 font-semibold transition-colors duration-200 group bg-white/80 backdrop-blur-sm px-4 py-2 rounded-xl border-2 border-pink-300/50 shadow-md hover:shadow-lg">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 group-hover:-translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            <span>Go Back</span>
        </a>
    </div>

    <div class="bg-gradient-to-br from-pink-50 via-white to-pink-100 shadow-2xl rounded-3xl p-10 mb-10 border-2 border-pink-200">
        <h2 class="text-3xl font-extrabold text-pink-600 flex items-center gap-3 drop-shadow">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-9 w-9 text-pink-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
            </svg>
            My Quizzes
        </h2>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($quizzes as $quiz)
            @php
                $attempt = $attempts[$quiz->quiz_id] ?? null;
                $isCompleted = $attempt !== null;
                $score = $attempt ? round($attempt->score ?? 0, 2) : null;
                $isUnlocked = $unlockedQuizzes[$quiz->quiz_id] ?? false;
            @endphp
            <div class="bg-white rounded-3xl shadow-xl border-2 {{ $isCompleted ? 'border-green-300' : ($isUnlocked ? 'border-pink-100' : 'border-gray-300') }} p-6 flex flex-col gap-4 hover:shadow-pink-200 transition-all duration-150 relative {{ !$isUnlocked && !$isCompleted ? 'opacity-75' : '' }}">
                @if($isCompleted)
                    <div class="absolute top-4 right-4">
                        <span class="px-3 py-1 rounded-full bg-green-100 text-green-700 font-bold text-xs border-2 border-green-300 flex items-center gap-1">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Completed
                        </span>
                    </div>
                @elseif(!$isUnlocked)
                    <div class="absolute top-4 right-4">
                        <span class="px-3 py-1 rounded-full bg-gray-100 text-gray-700 font-bold text-xs border-2 border-gray-300 flex items-center gap-1">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                            </svg>
                            Locked
                        </span>
                    </div>
                @endif
                <div class="flex items-center gap-4 mb-2">
                    <div class="w-16 h-16 rounded-full flex items-center justify-center text-2xl font-bold text-white shadow-lg" style="background-color: {{ $quiz->background_color ?? '#EC769A' }}">
                        Q
                    </div>
                    <div class="flex-1">
                        <h3 class="font-black text-xl text-pink-700 tracking-tight">{{ $quiz->title }}</h3>
                        <p class="text-sm text-gray-600">{{ $quiz->level->level_name ?? 'N/A' }}</p>
                    </div>
                </div>
                <div class="flex flex-wrap gap-2 text-xs">
                    <span class="px-3 py-1 rounded-full bg-pink-50 text-pink-600 font-bold border border-pink-200">
                        {{ $quiz->questions->count() }} Questions
                    </span>
                    <span class="px-3 py-1 rounded-full bg-pink-50 text-pink-600 font-bold border border-pink-200">
                        {{ $quiz->timer_minutes }} min
                    </span>
                    @if($isCompleted && $score !== null)
                        @php
                            // Always use 60% as the passing score for all quizzes (standardized)
                            $passingScorePercent = 60;
                        @endphp
                        <span class="px-3 py-1 rounded-full {{ $score >= $passingScorePercent ? 'bg-green-50 text-green-700 border-green-200' : 'bg-red-50 text-red-700 border-red-200' }} font-bold border">
                            Score: {{ $score }}%
                        </span>
                    @endif
                </div>
                <div class="mt-auto pt-4">
                    @if($isCompleted)
                        <a href="{{ route('student.quizzes.result', $attempt->attempt_id) }}" class="block text-center bg-gradient-to-r from-green-400 to-green-600 text-white px-4 py-2 rounded-xl font-bold hover:from-green-500 hover:to-green-700 transition">
                            View Results
                        </a>
                        <p class="text-xs text-center text-gray-500 mt-2 font-semibold">You have already taken this quiz. You cannot retake it.</p>
                    @elseif($isUnlocked)
                        <a href="{{ route('student.quizzes.show', $quiz->quiz_id) }}" class="block text-center bg-gradient-to-r from-pink-400 to-pink-600 text-white px-4 py-2 rounded-xl font-bold hover:from-pink-500 hover:to-pink-700 transition">
                            Take Quiz
                        </a>
                    @else
                        <button disabled class="block w-full text-center bg-gray-300 text-gray-600 px-4 py-2 rounded-xl font-bold cursor-not-allowed">
                            Locked
                        </button>
                        <p class="text-xs text-center text-gray-500 mt-2 font-semibold">Complete all lessons in this level to unlock this quiz.</p>
                    @endif
                </div>
            </div>
        @empty
            <div class="col-span-full text-center py-12">
                <p class="text-gray-500 text-lg mb-4">No quizzes available yet.</p>
                <p class="text-sm text-gray-400">Check back later for new quizzes from your teacher.</p>
            </div>
        @endforelse
    </div>
</div>
@endsection
