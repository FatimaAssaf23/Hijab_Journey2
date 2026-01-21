@extends('layouts.app')

@section('content')
@php
    $hasQuizGrades = isset($quizGrades) && is_array($quizGrades) && count($quizGrades) > 0;
    $hasGameScores = isset($lessonScores) && is_array($lessonScores) && count($lessonScores) > 0;
@endphp
<div class="container mx-auto py-8" style="background-color: #FFF4FA; min-height: 100vh;" x-data="{ activeTab: null }">
    <div class="max-w-7xl mx-auto">
        <!-- Header with decorative elements -->
        <div class="text-center mb-12 relative">
            <!-- Decorative stars/hearts -->
            <div class="absolute left-10 top-0 text-3xl opacity-30">✨</div>
            <div class="absolute right-10 top-0 text-3xl opacity-30">💖</div>
            <div class="absolute left-1/4 top-8 text-2xl opacity-20">⭐</div>
            <div class="absolute right-1/4 top-8 text-2xl opacity-20">🌸</div>
            
            <h1 class="text-5xl font-extrabold bg-gradient-to-r from-pink-600 to-teal-500 bg-clip-text text-transparent mb-4 flex items-center justify-center gap-3 relative z-10">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-teal-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z" />
                </svg>
                My Grades
            </h1>
            <!-- Back Arrow Button (positioned to the left, inline with subtitle) -->
            <button @click="activeTab = null" 
                    x-show="activeTab !== null"
                    x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="opacity-0 -translate-x-4"
                    x-transition:enter-end="opacity-100 translate-x-0"
                    x-transition:leave="transition ease-in duration-200"
                    x-transition:leave-start="opacity-100 translate-x-0"
                    x-transition:leave-end="opacity-0 -translate-x-4"
                    class="absolute left-[calc(50%-11cm)] top-[4.5rem] w-10 h-10 rounded-full bg-white shadow-lg border-2 border-teal-300 hover:bg-gradient-to-br hover:from-teal-50 hover:to-pink-50 hover:border-teal-400 hover:shadow-xl hover:scale-110 transition-all duration-300 flex items-center justify-center group z-10">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-teal-600 group-hover:text-teal-700 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                </svg>
            </button>
            <p class="text-xl text-gray-600 font-medium relative z-10">✨ Track your progress and celebrate your achievements! ✨</p>
            <p x-show="activeTab === null" x-cloak class="text-lg text-pink-600 font-bold mt-3">Choose What You'd Like to View</p>
            
            <!-- Chibi Hijab Girl Image -->
            <div class="absolute right-8 top-8 hidden md:flex items-center justify-center z-0" x-show="activeTab === null">
                <img src="{{ asset('storage/grade-page-design/hijab6.jpg') }}" alt="Chibi Hijab Girl" class="chibi-girl-image w-48 h-48 rounded-full object-cover border-4 border-white shadow-lg hover:shadow-2xl transition-all duration-300 hover:scale-110" loading="lazy">
            </div>
        </div>

        <!-- Section Tabs - Enhanced Structure (shown only when no tab is selected) -->
        <div x-show="activeTab === null" x-cloak class="flex flex-col items-center gap-8 mb-12">
            <!-- Main Action Buttons Container -->
            <div class="w-full max-w-4xl">
                <div class="flex justify-center gap-8 flex-wrap">
                    <!-- Quizzes Button -->
                    <button @click="activeTab = 'quizzes'" 
                            :class="activeTab === 'quizzes' 
                                ? 'bg-gradient-to-br from-pink-500 via-pink-600 to-teal-600 text-white shadow-2xl scale-105 ring-4 ring-teal-300 ring-opacity-50 transform rotate-0' 
                                : 'bg-white text-pink-600 border-3 border-teal-300 hover:bg-gradient-to-br hover:from-pink-50 hover:via-teal-50 hover:to-pink-50 hover:border-teal-400 hover:shadow-xl hover:scale-102'"
                            class="px-12 py-8 rounded-3xl font-extrabold text-xl transition-all duration-300 flex flex-col items-center gap-4 min-w-[220px] max-w-[280px] relative overflow-hidden group cursor-pointer">
                        <!-- Animated background gradient -->
                        <div class="absolute inset-0 bg-gradient-to-br from-pink-200/30 via-teal-100/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                        <!-- Sparkle decoration -->
                        <div class="absolute top-3 right-3 text-2xl opacity-20 group-hover:opacity-40 transition-opacity">✨</div>
                        <div class="absolute bottom-3 left-3 text-xl opacity-15 group-hover:opacity-30 transition-opacity">💖</div>
                        
                        <div class="relative z-10 flex flex-col items-center gap-3 w-full">
                            <!-- Large emoji icon -->
                            <div class="text-5xl mb-2 transform group-hover:scale-110 transition-transform duration-300">📝</div>
                            
                            <!-- Title with icon -->
                            <div class="flex items-center gap-3">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                <span class="text-2xl">Quizzes</span>
                            </div>
                            
                            <!-- Divider line -->
                            <div class="w-16 h-1 rounded-full" :class="activeTab === 'quizzes' ? 'bg-white/40' : 'bg-gradient-to-r from-pink-300 to-teal-300'"></div>
                            
                            <!-- Count badge -->
                            @php
                                $quizCount = isset($quizGrades) && is_array($quizGrades) ? count($quizGrades) : 0;
                            @endphp
                            @if($quizCount > 0)
                                <div :class="activeTab === 'quizzes' ? 'bg-white/25 backdrop-blur-sm' : 'bg-gradient-to-r from-pink-100 to-teal-100'" class="px-4 py-2 rounded-full text-sm font-bold border-2" :style="activeTab === 'quizzes' ? 'border-color: rgba(255,255,255,0.3)' : 'border-color: #7dd3fc'">
                                    <span class="text-lg">{{ $quizCount }}</span> <span class="text-xs">Available</span>
                                </div>
                            @else
                                <div class="text-xs opacity-60 font-medium">No quizzes yet</div>
                            @endif
                        </div>
                    </button>
                    
                    <!-- Games Button -->
                    <button @click="activeTab = 'games'" 
                            :class="activeTab === 'games' 
                                ? 'bg-gradient-to-br from-pink-500 via-pink-600 to-pink-700 text-white shadow-2xl scale-105 ring-4 ring-pink-300 ring-opacity-50 transform rotate-0' 
                                : 'bg-white text-pink-600 border-3 border-pink-300 hover:bg-gradient-to-br hover:from-pink-50 hover:via-pink-100 hover:to-pink-50 hover:border-pink-400 hover:shadow-xl hover:scale-102'"
                            class="px-12 py-8 rounded-3xl font-extrabold text-xl transition-all duration-300 flex flex-col items-center gap-4 min-w-[220px] max-w-[280px] relative overflow-hidden group cursor-pointer">
                        <!-- Animated background gradient -->
                        <div class="absolute inset-0 bg-gradient-to-br from-pink-200/30 via-pink-100/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                        <!-- Sparkle decoration -->
                        <div class="absolute top-3 right-3 text-2xl opacity-20 group-hover:opacity-40 transition-opacity">⭐</div>
                        <div class="absolute bottom-3 left-3 text-xl opacity-15 group-hover:opacity-30 transition-opacity">🎮</div>
                        
                        <div class="relative z-10 flex flex-col items-center gap-3 w-full">
                            <!-- Large emoji icon -->
                            <div class="text-5xl mb-2 transform group-hover:scale-110 transition-transform duration-300">🎮</div>
                            
                            <!-- Title with icon -->
                            <div class="flex items-center gap-3">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <span class="text-2xl">Games</span>
                            </div>
                            
                            <!-- Divider line -->
                            <div class="w-16 h-1 rounded-full" :class="activeTab === 'games' ? 'bg-white/40' : 'bg-pink-300'"></div>
                            
                            <!-- Count badge -->
                            @php
                                $gameCount = isset($lessonScores) && is_array($lessonScores) ? count($lessonScores) : 0;
                            @endphp
                            @if($gameCount > 0)
                                <div :class="activeTab === 'games' ? 'bg-white/25 backdrop-blur-sm' : 'bg-pink-100'" class="px-4 py-2 rounded-full text-sm font-bold border-2" :style="activeTab === 'games' ? 'border-color: rgba(255,255,255,0.3)' : 'border-color: #fbcfe8'">
                                    <span class="text-lg">{{ $gameCount }}</span> <span class="text-xs">Available</span>
                                </div>
                            @else
                                <div class="text-xs opacity-60 font-medium">No games yet</div>
                            @endif
                        </div>
                    </button>
                </div>
            </div>
        </div>

        <!-- Quizzes Section -->
        <div x-show="activeTab === 'quizzes'" x-cloak class="mt-8 md:mt-16 lg:mt-20 mb-12">
            @php
                $hasQuizGrades = isset($quizGrades) && is_array($quizGrades) && count($quizGrades) > 0;
                $quizCount = isset($quizGrades) && is_array($quizGrades) ? count($quizGrades) : 0;
            @endphp
            @if(!$hasQuizGrades)
                <div class="bg-white rounded-3xl shadow-xl p-12 text-center border-2 border-teal-200">
                    <div class="text-6xl mb-4">📝</div>
                    <h3 class="text-2xl font-bold text-gray-700 mb-2">No Quiz Grades Yet</h3>
                    <p class="text-gray-500 mb-6">Complete quizzes to see your grades here!</p>
                    <a href="{{ route('student.quizzes') }}" class="inline-block px-6 py-3 bg-gradient-to-r from-pink-400 via-teal-400 to-pink-600 text-white rounded-xl font-semibold shadow-lg hover:from-pink-500 hover:via-teal-500 hover:to-pink-700 transition-all">
                        Take Quizzes
                    </a>
                </div>
            @else
                <div class="{{ $quizCount === 1 ? 'flex justify-center' : 'grid gap-6 md:grid-cols-2 lg:grid-cols-3' }} gap-6">
                    @foreach($quizGrades as $quizData)
                        @php
                            $quiz = $quizData['quiz'];
                            $bestScore = $quizData['best_score'];
                            $latestScore = $quizData['latest_score'];
                            $attemptsCount = $quizData['attempts_count'];
                            // Always use 60% as the passing score for all quizzes (standardized)
                            $passingScore = 60;
                            $passed = $latestScore >= $passingScore;
                            
                            // Determine score color
                            $scoreColor = 'text-pink-600';
                            $bgGradient = 'from-pink-100 to-pink-200';
                            $borderColor = 'border-pink-300';
                            
                            if ($latestScore >= 90) {
                                $scoreColor = 'text-green-600';
                                $bgGradient = 'from-green-100 to-green-200';
                                $borderColor = 'border-green-300';
                            } elseif ($latestScore >= 70) {
                                $scoreColor = 'text-blue-600';
                                $bgGradient = 'from-blue-100 to-blue-200';
                                $borderColor = 'border-blue-300';
                            } elseif ($latestScore >= 50) {
                                $scoreColor = 'text-yellow-600';
                                $bgGradient = 'from-yellow-100 to-yellow-200';
                                $borderColor = 'border-yellow-300';
                            }
                        @endphp
                        
                        <div class="bg-white rounded-3xl shadow-2xl p-6 border-2 {{ $borderColor }} hover:shadow-3xl transition-all duration-300 hover:scale-105 relative overflow-hidden group {{ $quizCount === 1 ? 'w-full max-w-sm' : 'w-full' }}">
                            <!-- Decorative background -->
                            <div class="absolute top-0 right-0 w-40 h-40 bg-gradient-to-br {{ $bgGradient }} opacity-20 rounded-full -mr-20 -mt-20 group-hover:opacity-30 transition-opacity"></div>
                            <div class="absolute bottom-0 left-0 w-24 h-24 bg-gradient-to-tr from-pink-100 to-teal-100 opacity-15 rounded-full -ml-12 -mb-12"></div>
                            <div class="absolute top-4 right-4 text-2xl opacity-20">📝</div>
                            
                            <div class="relative z-10">
                                <!-- Quiz Title -->
                                <div class="mb-5 flex items-center gap-3 pb-3 border-b-2 border-teal-100">
                                    <div class="text-4xl bg-gradient-to-br from-pink-200 to-teal-200 rounded-full w-16 h-16 flex items-center justify-center shadow-lg">
                                        📋
                                    </div>
                                    <div class="flex-1">
                                        <h3 class="text-xl font-bold text-pink-700">{{ $quiz->title }}</h3>
                                        <p class="text-xs text-gray-500">{{ $quiz->level->level_name ?? 'N/A' }}</p>
                                    </div>
                                </div>
                                
                                <!-- Score Display -->
                                <div class="mb-5 p-5 rounded-2xl bg-gradient-to-br {{ $bgGradient }} border-2 {{ $borderColor }} shadow-lg relative overflow-hidden">
                                    <div class="absolute top-2 right-2 text-3xl opacity-10">{{ $latestScore >= 90 ? '🏆' : ($latestScore >= 70 ? '⭐' : '💫') }}</div>
                                    
                                    <div class="relative z-10 text-center">
                                        <div class="text-xs font-semibold text-gray-600 mb-2 uppercase tracking-wide">Latest Score</div>
                                        <div class="text-5xl font-extrabold {{ $scoreColor }} mb-2 drop-shadow-lg">{{ $latestScore }}%</div>
                                        @if($bestScore != $latestScore)
                                            <div class="text-xs text-gray-500 font-medium">Best: {{ $bestScore }}%</div>
                                        @endif
                                        
                                        @if($passed)
                                            <div class="mt-2 inline-block px-3 py-1 bg-green-200 text-green-800 rounded-full text-xs font-bold">Passed! 🌟</div>
                                        @else
                                            <div class="mt-2 inline-block px-3 py-1 bg-red-200 text-red-800 rounded-full text-xs font-bold">Needs Improvement 💪</div>
                                        @endif
                                    </div>
                                </div>
                                
                                <!-- Quiz Details -->
                                <div class="space-y-2 mb-4">
                                    <div class="flex items-center justify-between p-2 rounded-lg bg-gradient-to-r from-pink-50 to-teal-50">
                                        <span class="text-sm font-semibold text-gray-700">Questions:</span>
                                        <span class="text-sm font-bold text-teal-600">{{ $quiz->questions->count() }}</span>
                                    </div>
                                    <div class="flex items-center justify-between p-2 rounded-lg bg-gradient-to-r from-pink-50 to-teal-50">
                                        <span class="text-sm font-semibold text-gray-700">Time Limit:</span>
                                        <span class="text-sm font-bold text-teal-600">{{ $quiz->timer_minutes }} min</span>
                                    </div>
                                    <div class="flex items-center justify-between p-2 rounded-lg bg-gradient-to-r from-pink-50 to-teal-50">
                                        <span class="text-sm font-semibold text-gray-700">Attempts:</span>
                                        <span class="text-sm font-bold text-teal-600">{{ $attemptsCount }}</span>
                                    </div>
                                </div>
                                
                                <!-- Progress Bar -->
                                <div class="mt-5">
                                    <div class="flex items-center justify-between mb-2">
                                        <span class="text-xs font-semibold text-gray-600">Progress</span>
                                        <span class="text-xs font-bold {{ $scoreColor }}">{{ $latestScore }}%</span>
                                    </div>
                                    <div class="w-full bg-gray-200 rounded-full h-4 overflow-hidden shadow-inner">
                                        <div class="bg-gradient-to-r from-pink-400 via-pink-500 to-pink-600 h-4 rounded-full transition-all duration-700 flex items-center justify-end pr-2" 
                                             style="width: {{ $latestScore }}%">
                                            @if($latestScore >= 90)
                                                <span class="text-xs text-white font-bold">🏆</span>
                                            @elseif($latestScore >= 70)
                                                <span class="text-xs text-white font-bold">⭐</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- View Details Button -->
                                @if(isset($quizData['latest_attempt']))
                                    <a href="{{ route('student.quizzes.result', $quizData['latest_attempt']->attempt_id) }}" 
                                       class="mt-4 block text-center bg-gradient-to-r from-pink-500 via-pink-600 to-pink-700 text-white px-4 py-2 rounded-xl font-bold hover:from-pink-600 hover:via-pink-700 hover:to-pink-800 transition-all shadow-md">
                                        View Details
                                    </a>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        <!-- Games Section -->
        <div x-show="activeTab === 'games'" x-cloak class="mt-8 md:mt-16 lg:mt-20">
            @php
                $hasGameScores = isset($lessonScores) && is_array($lessonScores) && count($lessonScores) > 0;
                $gameCount = isset($lessonScores) && is_array($lessonScores) ? count($lessonScores) : 0;
            @endphp
            @if(!$hasGameScores)
                <div class="bg-white rounded-3xl shadow-xl p-12 text-center border-2 border-pink-200">
                    <div class="text-6xl mb-4">🎮</div>
                    <h3 class="text-2xl font-bold text-gray-700 mb-2">No Game Scores Yet</h3>
                    <p class="text-gray-500 mb-6">Complete games in lessons to see your grades here!</p>
                    <a href="{{ route('student.games') }}" class="inline-block px-6 py-3 bg-gradient-to-r from-pink-400 to-pink-600 text-white rounded-xl font-semibold shadow-lg hover:from-pink-500 hover:to-pink-700 transition-all">
                        Play Games
                    </a>
                </div>
            @else
                <div class="{{ $gameCount === 1 ? 'flex justify-center' : 'grid gap-6 md:grid-cols-2 lg:grid-cols-3' }} gap-6">
                    @foreach($lessonScores as $lessonData)
                        @php
                            $lesson = $lessonData['lesson'];
                            $averageScore = $lessonData['average_score'];
                            $scores = $lessonData['scores'];
                            $gamesCount = $lessonData['games_count'];
                            
                            // Determine score color
                            $scoreColor = 'text-gray-600';
                            $bgGradient = 'from-gray-100 to-gray-200';
                            $borderColor = 'border-gray-300';
                            
                            if ($averageScore >= 90) {
                                $scoreColor = 'text-green-600';
                                $bgGradient = 'from-green-100 to-green-200';
                                $borderColor = 'border-green-300';
                            } elseif ($averageScore >= 70) {
                                $scoreColor = 'text-blue-600';
                                $bgGradient = 'from-blue-100 to-blue-200';
                                $borderColor = 'border-blue-300';
                            } elseif ($averageScore >= 50) {
                                $scoreColor = 'text-yellow-600';
                                $bgGradient = 'from-yellow-100 to-yellow-200';
                                $borderColor = 'border-yellow-300';
                            } else {
                                $scoreColor = 'text-pink-600';
                                $bgGradient = 'from-pink-100 to-pink-200';
                                $borderColor = 'border-pink-300';
                            }
                        @endphp
                        
                        <div class="bg-white rounded-3xl shadow-2xl p-6 border-2 {{ $borderColor }} hover:shadow-3xl transition-all duration-300 hover:scale-105 relative overflow-hidden group {{ $gameCount === 1 ? 'w-full max-w-sm' : 'w-full' }}">
                            <!-- Decorative background -->
                            <div class="absolute top-0 right-0 w-40 h-40 bg-gradient-to-br {{ $bgGradient }} opacity-20 rounded-full -mr-20 -mt-20 group-hover:opacity-30 transition-opacity"></div>
                            <div class="absolute bottom-0 left-0 w-24 h-24 bg-gradient-to-tr from-pink-100 to-teal-100 opacity-15 rounded-full -ml-12 -mb-12"></div>
                            <div class="absolute top-4 right-4 text-2xl opacity-20">✨</div>
                            
                            <div class="relative z-10">
                                <!-- Lesson Title -->
                                <div class="mb-5 flex items-center gap-3 pb-3 border-b-2 border-teal-100">
                                    <div class="text-4xl bg-gradient-to-br from-pink-200 to-teal-200 rounded-full w-16 h-16 flex items-center justify-center shadow-lg">
                                        {{ $lesson->icon ?? '🎮' }}
                                    </div>
                                    <h3 class="text-xl font-bold text-pink-700 flex-1">{{ $lesson->title }}</h3>
                                </div>
                                
                                <!-- Total Score Display -->
                                <div class="mb-5 p-5 rounded-2xl bg-gradient-to-br {{ $bgGradient }} border-2 {{ $borderColor }} shadow-lg relative overflow-hidden">
                                    <div class="absolute top-2 right-2 text-3xl opacity-10">{{ $averageScore >= 90 ? '🏆' : ($averageScore >= 70 ? '⭐' : '💫') }}</div>
                                    
                                    <div class="relative z-10 text-center">
                                        <div class="text-xs font-semibold text-gray-600 mb-2 uppercase tracking-wide">Total Score</div>
                                        <div class="text-5xl font-extrabold {{ $scoreColor }} mb-2 drop-shadow-lg">{{ $averageScore }}%</div>
                                        <div class="text-xs text-gray-500 font-medium">Average of {{ $gamesCount }} game{{ $gamesCount > 1 ? 's' : '' }}</div>
                                        
                                        @if($averageScore >= 90)
                                            <div class="mt-2 inline-block px-3 py-1 bg-green-200 text-green-800 rounded-full text-xs font-bold">Excellent! 🌟</div>
                                        @elseif($averageScore >= 70)
                                            <div class="mt-2 inline-block px-3 py-1 bg-blue-200 text-blue-800 rounded-full text-xs font-bold">Great Job! 💙</div>
                                        @elseif($averageScore >= 50)
                                            <div class="mt-2 inline-block px-3 py-1 bg-yellow-200 text-yellow-800 rounded-full text-xs font-bold">Good Effort! 💛</div>
                                        @else
                                            <div class="mt-2 inline-block px-3 py-1 bg-pink-200 text-pink-800 rounded-full text-xs font-bold">Keep Trying! 💗</div>
                                        @endif
                                    </div>
                                </div>
                                
                                <!-- Individual Game Scores -->
                                <div class="space-y-3 mb-4">
                                    <div class="text-sm font-bold text-gray-700 mb-3 flex items-center gap-2">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-pink-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                                        </svg>
                                        Individual Game Scores:
                                    </div>
                                    @foreach($scores as $gameScore)
                                        @php
                                            $gameScoreColor = 'text-pink-600';
                                            $gameBgColor = 'bg-pink-50';
                                            if ($gameScore['score'] >= 90) {
                                                $gameScoreColor = 'text-green-600';
                                                $gameBgColor = 'bg-green-50';
                                            } elseif ($gameScore['score'] >= 70) {
                                                $gameScoreColor = 'text-blue-600';
                                                $gameBgColor = 'bg-blue-50';
                                            } elseif ($gameScore['score'] >= 50) {
                                                $gameScoreColor = 'text-yellow-600';
                                                $gameBgColor = 'bg-yellow-50';
                                            }
                                        @endphp
                                        <div class="flex items-center justify-between p-3 rounded-xl {{ $gameBgColor }} border-2 border-pink-200 hover:border-pink-300 transition-all shadow-sm hover:shadow-md">
                                            <div class="flex items-center gap-2">
                                                <span class="text-lg">🎮</span>
                                                <span class="text-sm font-semibold text-gray-700">{{ $gameScore['game_name'] }}</span>
                                            </div>
                                            <div class="flex items-center gap-2">
                                                <span class="text-lg font-bold {{ $gameScoreColor }}">{{ $gameScore['score'] }}%</span>
                                                @if($gameScore['score'] >= 90)
                                                    <span class="text-lg">🌟</span>
                                                @elseif($gameScore['score'] >= 70)
                                                    <span class="text-lg">⭐</span>
                                                @else
                                                    <span class="text-lg">💫</span>
                                                @endif
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                                
                                <!-- Progress Bar -->
                                <div class="mt-5">
                                    <div class="flex items-center justify-between mb-2">
                                        <span class="text-xs font-semibold text-gray-600">Progress</span>
                                        <span class="text-xs font-bold {{ $scoreColor }}">{{ $averageScore }}%</span>
                                    </div>
                                    <div class="w-full bg-gray-200 rounded-full h-4 overflow-hidden shadow-inner">
                                        <div class="bg-gradient-to-r from-pink-400 via-pink-500 to-pink-600 h-4 rounded-full transition-all duration-700 flex items-center justify-end pr-2" 
                                             style="width: {{ $averageScore }}%">
                                            @if($averageScore >= 90)
                                                <span class="text-xs text-white font-bold">🏆</span>
                                            @elseif($averageScore >= 70)
                                                <span class="text-xs text-white font-bold">⭐</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</div>

<style>
    [x-cloak] {
        display: none !important;
    }
    .hover\:shadow-3xl:hover {
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
    }
    .border-3 {
        border-width: 3px;
    }
    .hover\:scale-102:hover {
        transform: scale(1.02);
    }
    
    /* Chibi Girl Image Animations */
    .chibi-girl-image {
        animation: floatIn 1s ease-out, float 3s ease-in-out infinite;
        animation-delay: 0s, 1s;
    }
    
    @keyframes floatIn {
        0% {
            opacity: 0;
            transform: translateY(-20px) scale(0.9);
        }
        100% {
            opacity: 1;
            transform: translateY(0) scale(1);
        }
    }
    
    @keyframes float {
        0%, 100% {
            transform: translateY(0px);
        }
        50% {
            transform: translateY(-15px);
        }
    }
</style>
@endsection
