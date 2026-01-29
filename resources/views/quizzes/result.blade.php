@extends('layouts.app')
@section('content')
<div class="w-full max-w-full mx-auto py-10 px-4 sm:px-6 lg:px-8">
    <div class="bg-gradient-to-br from-pink-50 via-white to-pink-100 shadow-2xl rounded-3xl p-10 mb-10 border-2 border-pink-200">
        <div class="flex items-start justify-between mb-6">
            <a href="{{ route('student.quizzes') }}" class="flex items-center gap-2 bg-white hover:bg-pink-50 text-pink-600 px-4 py-2 rounded-xl font-bold shadow-md hover:shadow-lg transition-all duration-150 border-2 border-pink-200 hover:border-pink-300">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Go Back
            </a>
        </div>
        <div class="text-center mb-8">
            <h2 class="text-4xl font-extrabold text-pink-600 flex items-center justify-center gap-3 drop-shadow mb-4">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-pink-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                Quiz Results
            </h2>
            <h3 class="text-2xl font-bold text-gray-700 mb-4">{{ $attempt->quiz->title }}</h3>
            
            @php
                $score = round($attempt->score ?? 0, 2);
                $totalQuestions = $attempt->quiz->questions->count();
                $correctAnswers = $attempt->answers->where('is_correct', true)->count();
                // Always use 60% as the passing score for all quizzes (standardized)
                $passingScore = 60;
                $passed = $score >= $passingScore;
            @endphp
            
            @if(session('success'))
                <div class="bg-green-50 border-2 border-green-200 rounded-xl p-4 mb-4 text-center">
                    <p class="text-green-700 font-bold">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline-block mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        {{ session('success') }}
                    </p>
                </div>
            @endif
            
            @if(session('next_level_unlocked') && $passed)
                <div class="bg-blue-50 border-2 border-blue-200 rounded-xl p-4 mb-4 text-center">
                    <p class="text-blue-700 font-bold">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline-block mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                        </svg>
                        Congratulations! The first lesson of the next level has been unlocked for you.
                    </p>
                </div>
            @endif
            
            @if(session('info'))
                <div class="bg-blue-50 border-2 border-blue-200 rounded-xl p-4 mb-4 text-center">
                    <p class="text-blue-700 font-bold">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline-block mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        {{ session('info') }}
                    </p>
                    <p class="text-sm text-blue-600 mt-2">This is a read-only view of your previous submission. You cannot retake this quiz.</p>
                </div>
            @endif
            
            <div class="bg-white rounded-2xl shadow-lg p-6 mb-6 border-2 {{ $passed ? 'border-green-300' : 'border-red-300' }}">
                <div class="flex items-center justify-center gap-4 mb-4">
                    <div class="text-center">
                        <div class="text-5xl font-extrabold {{ $passed ? 'text-green-600' : 'text-red-600' }}">
                            {{ $score }}%
                        </div>
                        <div class="text-sm text-gray-600 mt-1">Score</div>
                    </div>
                    <div class="h-16 w-px bg-gray-300"></div>
                    <div class="text-center">
                        <div class="text-4xl font-bold text-pink-600">
                            {{ $correctAnswers }}/{{ $totalQuestions }}
                        </div>
                        <div class="text-sm text-gray-600 mt-1">Correct Answers</div>
                    </div>
                </div>
                
                @if($passed)
                    <div class="bg-green-50 border-2 border-green-200 rounded-xl p-4 text-center">
                        <p class="text-green-700 font-bold text-lg">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 inline-block mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Congratulations! You passed the quiz!
                        </p>
                        <p class="text-sm text-green-600 mt-1">Passing Score: {{ $passingScore }}%</p>
                    </div>
                @else
                    <div class="bg-red-50 border-2 border-red-200 rounded-xl p-4 text-center">
                        <p class="text-red-700 font-bold text-lg">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 inline-block mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            You need to score at least {{ $passingScore }}% to pass
                        </p>
                        <p class="text-sm text-red-600 mt-1">Keep practicing!</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Questions Review -->
    <div class="space-y-6">
        <h3 class="text-2xl font-bold text-pink-600 mb-4">Question Review</h3>
        
        @foreach($attempt->quiz->questions->sortBy('question_order') as $index => $question)
            @php
                $studentAnswer = $attempt->answers->where('question_id', $question->question_id)->first();
                $selectedOption = $studentAnswer ? $question->options->where('option_id', $studentAnswer->selected_option_id)->first() : null;
                $correctOption = $question->options->where('is_correct', true)->first();
                $isCorrect = $studentAnswer && $studentAnswer->is_correct;
            @endphp
            
            <div class="bg-white rounded-3xl shadow-xl border-2 {{ $isCorrect ? 'border-green-300' : 'border-red-300' }} p-6" style="background-color: {{ $question->background_color ?? '#FFFFFF' }}">
                <div class="flex items-start gap-3 mb-4">
                    <div class="flex-shrink-0">
                        <div class="bg-{{ $isCorrect ? 'green' : 'red' }}-500 text-white rounded-full w-10 h-10 flex items-center justify-center text-lg font-extrabold">
                            @if($isCorrect)
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                            @else
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            @endif
                        </div>
                    </div>
                    <div class="flex-1">
                        <h4 class="text-xl font-bold text-gray-800 mb-2">
                            Question {{ $index + 1 }}: {{ $question->question_text }}
                        </h4>
                        
                        @if($question->image_path)
                            <div class="mb-4 flex justify-center">
                                <img src="{{ asset('storage/' . $question->image_path) }}" alt="Question Image" class="max-w-md max-h-[300px] w-auto h-auto rounded-lg shadow-md object-contain">
                            </div>
                        @endif
                    </div>
                </div>

                <div class="space-y-3 mt-4">
                    @foreach($question->options->sortBy('option_order') as $option)
                        @php
                            $isSelected = $selectedOption && $selectedOption->option_id == $option->option_id;
                            $isCorrectAnswer = $option->is_correct;
                        @endphp
                        
                        <div class="p-4 rounded-xl border-2 
                            {{ $isCorrectAnswer ? 'border-green-500 bg-green-50' : ($isSelected ? 'border-red-500 bg-red-50' : 'border-gray-200 bg-gray-50') }}">
                            <div class="flex items-center gap-3">
                                @if($isCorrectAnswer)
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-green-600 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    <span class="text-green-700 font-bold text-sm">Correct Answer</span>
                                @elseif($isSelected)
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-red-600 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                    <span class="text-red-700 font-bold text-sm">Your Answer (Incorrect)</span>
                                @endif
                                <span class="flex-1 text-gray-700 font-medium {{ $isCorrectAnswer || $isSelected ? 'font-bold' : '' }}">
                                    {{ $option->option_text }}
                                </span>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endforeach
    </div>

    <div class="mt-8 flex justify-center">
        <a href="{{ route('student.quizzes') }}" class="bg-gradient-to-r from-pink-500 to-pink-700 text-white px-8 py-3 rounded-xl font-extrabold shadow-xl hover:from-pink-600 hover:to-pink-800 transition-all duration-150">
            Back to Quizzes
        </a>
    </div>
</div>
@endsection
