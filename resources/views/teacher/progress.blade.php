@extends('layouts.app')

@section('content')
<style>
    @keyframes float {
        0%, 100% { transform: translateY(0px); }
        50% { transform: translateY(-10px); }
    }
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }
    @keyframes slideIn {
        from { opacity: 0; transform: translateX(-20px); }
        to { opacity: 1; transform: translateX(0); }
    }
    .fade-in {
        animation: fadeIn 0.6s ease-out;
    }
    .slide-in {
        animation: slideIn 0.6s ease-out;
    }
    .float-animation {
        animation: float 3s ease-in-out infinite;
    }
    .progress-ring {
        transform: rotate(-90deg);
    }
    [x-cloak] {
        display: none !important;
    }
</style>

<div class="min-h-screen" style="background: linear-gradient(135deg, #FFF4FA 0%, #F0F9FF 50%, #FFF4FA 100%);" x-data="{ showStudentDetails: false }">
    <!-- Decorative Background Elements -->
    <div class="absolute inset-0 overflow-hidden pointer-events-none">
        <div class="absolute top-20 left-10 w-64 h-64 bg-pink-400/50 rounded-full blur-3xl float-animation"></div>
        <div class="absolute bottom-20 right-10 w-80 h-80 bg-cyan-400/50 rounded-full blur-3xl float-animation" style="animation-delay: 1.5s;"></div>
        <div class="absolute top-1/2 left-1/2 w-72 h-72 bg-pink-300/40 rounded-full blur-3xl float-animation" style="animation-delay: 3s;"></div>
    </div>

    <div class="relative z-10 w-full min-h-screen px-4 sm:px-6 lg:px-8 py-8">
        <!-- Go Back Button -->
        <div class="mb-6 fade-in">
            <button onclick="goBackOrRedirect('{{ route('teacher.dashboard') }}')" 
                    class="flex items-center gap-2 px-6 py-3 rounded-xl font-semibold text-white shadow-lg hover:shadow-xl transition-all duration-300 transform hover:scale-105" 
                    style="background: linear-gradient(135deg, #FC8EAC, #6EC6C5);">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Go Back
            </button>
        </div>
        <!-- Header -->
        <div class="text-center mb-10 fade-in">
            <h1 class="text-5xl md:text-6xl font-extrabold mb-4" style="background: linear-gradient(135deg, #D81B60 0%, #00695C 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;">
                <span class="flex items-center justify-center gap-3">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12" style="color: #00695C;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                    </svg>
                    Student Progress
                </span>
            </h1>
            <p class="text-xl text-gray-600 font-medium">Monitor and track progress for all your students ✨</p>
        </div>

        <!-- Search Section -->
        <div class="w-full mb-8 fade-in">
            <div class="bg-white/90 backdrop-blur-lg rounded-2xl shadow-xl p-6 border-2 border-pink-400/70">
                <form method="GET" action="{{ route('teacher.progress') }}" class="space-y-4">
                    @if(isset($selectedClassId) && $selectedClassId)
                        <input type="hidden" name="class_id" value="{{ $selectedClassId }}">
                    @endif
                    <div class="relative">
                        <input 
                            type="text" 
                            name="search" 
                            value="{{ $searchQuery ?? '' }}"
                            placeholder="🔍 Search for student by name or class..." 
                            class="w-full px-6 py-4 pl-14 pr-12 rounded-2xl border-2 border-pink-400/70 bg-white/90 backdrop-blur-lg shadow-lg focus:outline-none focus:ring-4 focus:ring-pink-500/70 focus:border-pink-600 transition-all duration-300 text-lg font-medium text-gray-700 placeholder-gray-400"
                            autocomplete="off"
                        >
                        <div class="absolute left-4 top-1/2 transform -translate-y-1/2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-pink-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </div>
                        @if(!empty($searchQuery))
                            <a href="{{ route('teacher.progress', isset($selectedClassId) && $selectedClassId ? ['class_id' => $selectedClassId] : []) }}" 
                               class="absolute right-4 top-1/2 transform -translate-y-1/2 text-pink-600 hover:text-pink-700 transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </a>
                        @endif
                    </div>
                    @if(!empty($searchQuery))
                        <div class="mt-4 p-4 rounded-xl" style="background: linear-gradient(135deg, #FFD6E5, #80DEEA);">
                            <p class="text-sm text-gray-600 flex items-center gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-pink-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                                Searching for: <span class="font-bold" style="color: #D81B60;">"{{ $searchQuery }}"</span>
                            </p>
                        </div>
                    @endif
                </form>
            </div>
        </div>

        @if(empty($studentProgress))
            <div class="max-w-2xl mx-auto bg-white/90 backdrop-blur-lg rounded-3xl shadow-2xl p-12 text-center border-2 border-pink-400/70 fade-in">
                <div class="text-6xl mb-4">📚</div>
                <h3 class="text-2xl font-bold text-gray-700 mb-2">
                    @if(isset($selectedClassId) && $selectedClassId)
                        No Students in This Class
                    @else
                        No Students Yet
                    @endif
                </h3>
                <p class="text-gray-500">
                    @if(isset($selectedClassId) && $selectedClassId)
                        This class doesn't have any students yet. Try selecting a different class or view all classes.
                    @else
                        You don't have any students in your classes yet.
                    @endif
                </p>
            </div>
        @else
            <!-- Overall Statistics -->
            <div class="w-full mb-8">
                <div class="bg-white/90 backdrop-blur-lg rounded-2xl shadow-xl p-6 border-2 border-pink-400/70 fade-in">
                    <h2 class="text-2xl font-bold mb-6" style="color: #D81B60;">Class Overview</h2>
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
                        <!-- Total Students -->
                        <div class="text-center p-6 rounded-xl min-h-[140px] flex flex-col justify-center transition-all duration-300 hover:shadow-lg" style="background: linear-gradient(135deg, #FFD6E5, #80DEEA);">
                            <div class="text-4xl font-extrabold mb-2" style="color: #D81B60;">{{ $overallStats['total_students'] }}</div>
                            <div class="text-sm font-semibold text-gray-600">Total Students</div>
                        </div>
                        
                        <!-- Classes -->
                        <div class="text-center p-6 rounded-xl min-h-[140px] flex flex-col justify-center transition-all duration-300 hover:shadow-lg" style="background: linear-gradient(135deg, #80DEEA, #FFD6E5);">
                            <div class="text-4xl font-extrabold mb-2" style="color: #00695C;">
                                @if(isset($selectedClassId) && $selectedClassId)
                                    1
                                @else
                                    {{ isset($allClasses) ? $allClasses->count() : count($classes) }}
                                @endif
                            </div>
                            <div class="text-sm font-semibold text-gray-600">Class{{ (isset($selectedClassId) && $selectedClassId) || (isset($allClasses) && $allClasses->count() == 1) ? '' : 'es' }}</div>
                        </div>
                        
                        <!-- Average Lesson Progress -->
                        <div class="text-center p-6 rounded-xl min-h-[140px] flex flex-col justify-center transition-all duration-300 hover:shadow-lg" style="background: linear-gradient(135deg, #FFD6E5, #80DEEA);">
                            <div class="text-4xl font-extrabold mb-2" style="color: #D81B60;">{{ number_format($overallStats['average_lessons_completed'], 1) }}%</div>
                            <div class="text-sm font-semibold text-gray-600">Avg. Lesson Progress</div>
                        </div>
                        
                        <!-- Average Assignments Submitted -->
                        <div class="text-center p-6 rounded-xl min-h-[140px] flex flex-col justify-center transition-all duration-300 hover:shadow-lg" style="background: linear-gradient(135deg, #80DEEA, #FFD6E5);">
                            <div class="text-4xl font-extrabold mb-2" style="color: #00695C;">{{ number_format($overallStats['average_assignments_submitted'], 1) }}</div>
                            <div class="text-sm font-semibold text-gray-600">Avg. Assignments</div>
                        </div>
                        
                        <!-- Average Games Score -->
                        <div class="text-center p-6 rounded-xl min-h-[140px] flex flex-col justify-center transition-all duration-300 hover:shadow-lg" style="background: linear-gradient(135deg, #FFD6E5, #80DEEA);">
                            @if(isset($overallStats['average_games_score']) && $overallStats['average_games_score'] > 0)
                                <div class="text-4xl font-extrabold mb-2" style="color: #D81B60;">{{ number_format($overallStats['average_games_score'], 1) }}</div>
                            @else
                                <div class="text-4xl font-extrabold mb-2 text-gray-400">N/A</div>
                            @endif
                            <div class="text-sm font-semibold text-gray-600">Avg. Games Score</div>
                        </div>
                        
                        <!-- Average Quiz Score -->
                        <div class="text-center p-6 rounded-xl min-h-[140px] flex flex-col justify-center transition-all duration-300 hover:shadow-lg" style="background: linear-gradient(135deg, #80DEEA, #FFD6E5);">
                            @if(isset($overallStats['average_quizzes_score']) && $overallStats['average_quizzes_score'] > 0)
                                <div class="text-4xl font-extrabold mb-2" style="color: #00695C;">{{ number_format($overallStats['average_quizzes_score'], 1) }}%</div>
                            @else
                                <div class="text-4xl font-extrabold mb-2 text-gray-400">N/A</div>
                            @endif
                            <div class="text-sm font-semibold text-gray-600">Avg. Quiz Score</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Show/Hide Info Button -->
            <div class="w-full mb-6 flex justify-center fade-in">
                <button @click="showStudentDetails = !showStudentDetails" 
                        class="px-8 py-4 rounded-2xl font-bold text-lg transition-all duration-300 shadow-lg hover:shadow-xl transform hover:scale-105 flex items-center gap-3"
                        style="background: linear-gradient(135deg, #FC8EAC, #6EC6C5); color: white;">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 transition-transform duration-300" :class="{ 'rotate-180': !showStudentDetails }" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                    </svg>
                    <span x-text="showStudentDetails ? 'Hide Student Details' : 'Show Student Details'"></span>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </button>
            </div>

            <!-- Student Progress Cards -->
            <div x-show="showStudentDetails" 
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 transform translate-y-4"
                 x-transition:enter-end="opacity-100 transform translate-y-0"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100 transform translate-y-0"
                 x-transition:leave-end="opacity-0 transform translate-y-4"
                 class="w-full space-y-6">
                @foreach($studentProgress as $studentId => $data)
                    <div class="bg-white/90 backdrop-blur-lg rounded-3xl shadow-2xl p-6 border-2 border-pink-400/70 hover:border-pink-500 transition-all duration-300 fade-in hover:shadow-3xl">
                        <!-- Student Header -->
                        <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6 pb-4 border-b-2 border-pink-300">
                            <div class="flex items-center gap-4 mb-4 md:mb-0">
                                <div class="w-16 h-16 rounded-full flex items-center justify-center text-2xl font-bold text-white shadow-lg" style="background: linear-gradient(135deg, #D81B60, #00695C);">
                                    {{ substr($data['student_name'], 0, 1) }}
                                </div>
                                <div>
                                    <h3 class="text-2xl font-bold text-gray-800">{{ $data['student_name'] }}</h3>
                                    <p class="text-sm text-gray-600 flex items-center gap-2 mt-1">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-pink-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                        </svg>
                                        {{ $data['class_name'] }}
                                    </p>
                                </div>
                            </div>
                            <div class="text-right">
                                <div class="text-3xl font-extrabold mb-1" style="color: #00695C;">{{ $data['lesson_progress']['percentage'] }}%</div>
                                <div class="text-xs font-semibold text-gray-600">Lesson Progress</div>
                            </div>
                        </div>

                        <!-- Progress Statistics -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                            <!-- Lessons Progress -->
                            <div class="p-5 rounded-xl border-2 border-pink-400/70" style="background: linear-gradient(135deg, #FFD6E5, #80DEEA);">
                                <div class="flex items-center gap-3 mb-4">
                                    <div class="w-12 h-12 rounded-full flex items-center justify-center text-xl" style="background: linear-gradient(135deg, #D81B60, #EC769A);">
                                        📚
                                    </div>
                                    <h4 class="text-lg font-bold text-gray-800">Lessons</h4>
                                </div>
                                <div class="space-y-2">
                                    <div class="flex justify-between text-sm">
                                        <span class="text-gray-600">Completed:</span>
                                        <span class="font-bold" style="color: #D81B60;">{{ $data['lesson_progress']['completed_lessons'] }} / {{ $data['lesson_progress']['total_lessons'] }}</span>
                                    </div>
                                    <div class="flex justify-between text-sm">
                                        <span class="text-gray-600">In Progress:</span>
                                        <span class="font-bold" style="color: #00695C;">{{ $data['lesson_progress']['in_progress_lessons'] }}</span>
                                    </div>
                                    <div class="flex justify-between text-sm">
                                        <span class="text-gray-600">Pending:</span>
                                        <span class="font-bold" style="color: #00695C;">{{ $data['lesson_progress']['pending_lessons'] }}</span>
                                    </div>
                                    <div class="mt-3 h-3 bg-gray-200 rounded-full overflow-hidden">
                                        <div class="h-full rounded-full transition-all duration-500" 
                                             style="width: {{ $data['lesson_progress']['percentage'] }}%; background: linear-gradient(90deg, #D81B60, #00695C);">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Games Progress -->
                            <div class="p-5 rounded-xl border-2 border-pink-400/70" style="background: linear-gradient(135deg, #80DEEA, #FFD6E5);">
                                <div class="flex items-center gap-3 mb-4">
                                    <div class="w-12 h-12 rounded-full flex items-center justify-center text-xl" style="background: linear-gradient(135deg, #00695C, #4DD0E1);">
                                        🎮
                                    </div>
                                    <h4 class="text-lg font-bold text-gray-800">Games</h4>
                                </div>
                                <div class="space-y-2">
                                    <div class="flex justify-between text-sm">
                                        <span class="text-gray-600">Completed:</span>
                                        <span class="font-bold" style="color: #D81B60;">{{ $data['games_stats']['completed'] }} / {{ $data['games_stats']['total'] }}</span>
                                    </div>
                                    <div class="flex justify-between text-sm">
                                        <span class="text-gray-600">Pending:</span>
                                        <span class="font-bold" style="color: #00695C;">{{ $data['games_stats']['pending'] }}</span>
                                    </div>
                                    @if($data['games_stats']['average_score'] > 0)
                                    <div class="flex justify-between text-sm">
                                        <span class="text-gray-600">Avg Score:</span>
                                        <span class="font-bold" style="color: #00695C;">{{ number_format($data['games_stats']['average_score'], 1) }}</span>
                                    </div>
                                    @endif
                                    <div class="mt-3 h-3 bg-gray-200 rounded-full overflow-hidden">
                                        @php
                                            $gamesPercentage = $data['games_stats']['total'] > 0 
                                                ? ($data['games_stats']['completed'] / $data['games_stats']['total'] * 100) 
                                                : 0;
                                        @endphp
                                        <div class="h-full rounded-full transition-all duration-500" 
                                             style="width: {{ $gamesPercentage }}%; background: linear-gradient(90deg, #00695C, #4DD0E1);">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Quizzes Progress -->
                            <div class="p-5 rounded-xl border-2 border-pink-400/70" style="background: linear-gradient(135deg, #FFD6E5, #80DEEA);">
                                <div class="flex items-center gap-3 mb-4">
                                    <div class="w-12 h-12 rounded-full flex items-center justify-center text-xl" style="background: linear-gradient(135deg, #EC769A, #D81B60);">
                                        📝
                                    </div>
                                    <h4 class="text-lg font-bold text-gray-800">Quizzes</h4>
                                </div>
                                <div class="space-y-2">
                                    <div class="flex justify-between text-sm">
                                        <span class="text-gray-600">Completed:</span>
                                        <span class="font-bold" style="color: #D81B60;">{{ $data['quizzes_stats']['completed'] }} / {{ $data['quizzes_stats']['total_attempts'] }}</span>
                                    </div>
                                    <div class="flex justify-between text-sm">
                                        <span class="text-gray-600">Pending:</span>
                                        <span class="font-bold" style="color: #00695C;">{{ $data['quizzes_stats']['pending'] }}</span>
                                    </div>
                                    <div class="flex justify-between text-sm">
                                        <span class="text-gray-600">Avg Score:</span>
                                        @if(isset($data['quizzes_stats']['average_score']) && $data['quizzes_stats']['average_score'] > 0)
                                            <span class="font-bold" style="color: #00695C;">{{ number_format($data['quizzes_stats']['average_score'], 1) }}%</span>
                                        @else
                                            <span class="font-semibold text-gray-400">N/A</span>
                                        @endif
                                    </div>
                                    <div class="mt-3 h-3 bg-gray-200 rounded-full overflow-hidden">
                                        @php
                                            $quizzesPercentage = isset($data['quizzes_stats']['total_attempts']) && $data['quizzes_stats']['total_attempts'] > 0 
                                                ? ($data['quizzes_stats']['completed'] / $data['quizzes_stats']['total_attempts'] * 100) 
                                                : (isset($data['quizzes_stats']['completed']) && $data['quizzes_stats']['completed'] > 0 ? 100 : 0);
                                        @endphp
                                        <div class="h-full rounded-full transition-all duration-500" 
                                             style="width: {{ $quizzesPercentage }}%; background: linear-gradient(90deg, #EC769A, #D81B60);">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Assignments Progress -->
                            <div class="p-5 rounded-xl border-2 border-pink-400/70" style="background: linear-gradient(135deg, #80DEEA, #FFD6E5);">
                                <div class="flex items-center gap-3 mb-4">
                                    <div class="w-12 h-12 rounded-full flex items-center justify-center text-xl" style="background: linear-gradient(135deg, #D81B60, #EC769A);">
                                        📄
                                    </div>
                                    <h4 class="text-lg font-bold text-gray-800">Assignments</h4>
                                </div>
                                <div class="space-y-2">
                                    <div class="flex justify-between text-sm">
                                        <span class="text-gray-600">Submitted:</span>
                                        <span class="font-bold" style="color: #D81B60;">{{ $data['assignments_stats']['submitted'] }} / {{ $data['assignments_stats']['total'] }}</span>
                                    </div>
                                    <div class="flex justify-between text-sm">
                                        <span class="text-gray-600">Pending:</span>
                                        <span class="font-bold" style="color: #00695C;">{{ $data['assignments_stats']['pending'] }}</span>
                                    </div>
                                    <div class="flex justify-between text-sm">
                                        <span class="text-gray-600">Avg Score:</span>
                                        @if(isset($data['assignments_stats']['average_score']) && $data['assignments_stats']['average_score'] > 0)
                                            <span class="font-bold" style="color: #00695C;">{{ number_format($data['assignments_stats']['average_score'], 1) }}%</span>
                                        @else
                                            <span class="font-semibold text-gray-400">N/A</span>
                                        @endif
                                    </div>
                                    <div class="mt-3 h-3 bg-gray-200 rounded-full overflow-hidden">
                                        <div class="h-full rounded-full transition-all duration-500" 
                                             style="width: {{ $data['assignments_stats']['completed_percentage'] }}%; background: linear-gradient(90deg, #D81B60, #EC769A);">
                                        </div>
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

<script>
    // Auto-submit search form with debounce
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.querySelector('input[name="search"]');
        const searchForm = searchInput?.closest('form');
        let searchTimeout;

        if (searchInput && searchForm) {
            // Submit on Enter key
            searchInput.addEventListener('keypress', function(e) {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    clearTimeout(searchTimeout);
                    searchForm.submit();
                }
            });

            // Auto-submit after user stops typing (debounce - 1500ms delay)
            searchInput.addEventListener('input', function() {
                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(function() {
                    searchForm.submit();
                }, 1500); // Wait 1.5 seconds after user stops typing
            });
        }
    });
</script>
@endsection
