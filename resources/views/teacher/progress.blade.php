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

<div class="min-h-screen" style="background: linear-gradient(135deg, #FFF4FA 0%, #F0F9FF 50%, #FFF4FA 100%);" x-data="{ showStudentDetails: true }">
    <!-- Decorative Background Elements -->
    <div class="absolute inset-0 overflow-hidden pointer-events-none">
        <div class="absolute top-20 left-10 w-64 h-64 bg-pink-200/30 rounded-full blur-3xl float-animation"></div>
        <div class="absolute bottom-20 right-10 w-80 h-80 bg-turquoise-200/30 rounded-full blur-3xl float-animation" style="animation-delay: 1.5s;"></div>
        <div class="absolute top-1/2 left-1/2 w-72 h-72 bg-pink-100/20 rounded-full blur-3xl float-animation" style="animation-delay: 3s;"></div>
    </div>

    <div class="relative z-10 container mx-auto px-4 py-8">
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
            <h1 class="text-5xl md:text-6xl font-extrabold mb-4" style="background: linear-gradient(135deg, #FC8EAC 0%, #6EC6C5 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;">
                <span class="flex items-center justify-center gap-3">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-turquoise-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                    </svg>
                    Student Progress
                </span>
            </h1>
            <p class="text-xl text-gray-600 font-medium">Monitor and track progress for all your students ✨</p>
        </div>

        <!-- Class Filter Section -->
        @if(isset($allClasses) && $allClasses->count() > 0)
            <div class="max-w-7xl mx-auto mb-8 fade-in">
                <div class="bg-white/90 backdrop-blur-lg rounded-2xl shadow-xl p-6 border-2 border-pink-200/50">
                    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                        <div class="flex items-center gap-3">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" style="color: #FC8EAC;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                            </svg>
                            <h2 class="text-xl font-bold text-gray-800">Filter by Class:</h2>
                        </div>
                        <div class="flex flex-wrap gap-3">
                            <!-- All Classes Button -->
                            <a href="{{ route('teacher.progress') }}" 
                               class="px-6 py-3 rounded-xl font-semibold transition-all duration-300 shadow-md hover:shadow-xl {{ !isset($selectedClassId) || $selectedClassId == null ? 'text-white' : 'text-gray-700 bg-white border-2 border-pink-200 hover:border-pink-300' }}"
                               style="{{ !isset($selectedClassId) || $selectedClassId == null ? 'background: linear-gradient(135deg, #FC8EAC, #6EC6C5);' : '' }}">
                                <span class="flex items-center gap-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                    </svg>
                                    All Classes
                                </span>
                            </a>
                            <!-- Individual Class Buttons -->
                            @foreach($allClasses as $class)
                                <a href="{{ route('teacher.progress', ['class_id' => $class->class_id]) }}" 
                                   class="px-6 py-3 rounded-xl font-semibold transition-all duration-300 shadow-md hover:shadow-xl {{ isset($selectedClassId) && (int)$selectedClassId == $class->class_id ? 'text-white' : 'text-gray-700 bg-white border-2 border-pink-200 hover:border-pink-300' }}"
                                   style="{{ isset($selectedClassId) && (int)$selectedClassId == $class->class_id ? 'background: linear-gradient(135deg, #FC8EAC, #6EC6C5);' : '' }}">
                                    {{ $class->class_name }}
                                </a>
                            @endforeach
                        </div>
                    </div>
                    @if(isset($selectedClassId) && $selectedClassId)
                        <div class="mt-4 p-4 rounded-xl" style="background: linear-gradient(135deg, #FFF4FA, #F0F9FF);">
                            <p class="text-sm text-gray-600 flex items-center gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-pink-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                Showing progress for: <span class="font-bold" style="color: #FC8EAC;">{{ $allClasses->firstWhere('class_id', (int)$selectedClassId)->class_name ?? 'Selected Class' }}</span>
                            </p>
                        </div>
                    @endif
                </div>
            </div>
        @endif

        @if(empty($studentProgress))
            <div class="max-w-2xl mx-auto bg-white/90 backdrop-blur-lg rounded-3xl shadow-2xl p-12 text-center border-2 border-pink-200/50 fade-in">
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
            <div class="max-w-7xl mx-auto mb-8">
                <div class="bg-white/90 backdrop-blur-lg rounded-2xl shadow-xl p-6 border-2 border-pink-200/50 fade-in">
                    <h2 class="text-2xl font-bold mb-6" style="color: #FC8EAC;">Class Overview</h2>
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                        <!-- Total Students -->
                        <div class="text-center p-5 rounded-xl min-h-[120px] flex flex-col justify-center" style="background: linear-gradient(135deg, #FFF4FA, #F0F9FF);">
                            <div class="text-4xl font-extrabold mb-2" style="color: #FC8EAC;">{{ $overallStats['total_students'] }}</div>
                            <div class="text-sm font-semibold text-gray-600">Total Students</div>
                        </div>
                        
                        <!-- Classes -->
                        <div class="text-center p-5 rounded-xl min-h-[120px] flex flex-col justify-center" style="background: linear-gradient(135deg, #F0F9FF, #FFF4FA);">
                            <div class="text-4xl font-extrabold mb-2" style="color: #6EC6C5;">
                                @if(isset($selectedClassId) && $selectedClassId)
                                    1
                                @else
                                    {{ isset($allClasses) ? $allClasses->count() : count($classes) }}
                                @endif
                            </div>
                            <div class="text-sm font-semibold text-gray-600">Class{{ (isset($selectedClassId) && $selectedClassId) || (isset($allClasses) && $allClasses->count() == 1) ? '' : 'es' }}</div>
                        </div>
                        
                        <!-- Average Lesson Progress -->
                        <div class="text-center p-5 rounded-xl min-h-[120px] flex flex-col justify-center" style="background: linear-gradient(135deg, #FFF4FA, #F0F9FF);">
                            <div class="text-4xl font-extrabold mb-2" style="color: #FC8EAC;">{{ number_format($overallStats['average_lessons_completed'], 1) }}%</div>
                            <div class="text-sm font-semibold text-gray-600">Avg. Lesson Progress</div>
                        </div>
                        
                        <!-- Average Assignments Submitted -->
                        <div class="text-center p-5 rounded-xl min-h-[120px] flex flex-col justify-center" style="background: linear-gradient(135deg, #F0F9FF, #FFF4FA);">
                            <div class="text-4xl font-extrabold mb-2" style="color: #6EC6C5;">{{ number_format($overallStats['average_assignments_submitted'], 1) }}</div>
                            <div class="text-sm font-semibold text-gray-600">Avg. Assignments</div>
                        </div>
                        
                        <!-- Average Games Score -->
                        <div class="text-center p-5 rounded-xl min-h-[120px] flex flex-col justify-center" style="background: linear-gradient(135deg, #FFF4FA, #F0F9FF);">
                            @if(isset($overallStats['average_games_score']) && $overallStats['average_games_score'] > 0)
                                <div class="text-4xl font-extrabold mb-2" style="color: #FC8EAC;">{{ number_format($overallStats['average_games_score'], 1) }}</div>
                            @else
                                <div class="text-4xl font-extrabold mb-2 text-gray-400">N/A</div>
                            @endif
                            <div class="text-sm font-semibold text-gray-600">Avg. Games Score</div>
                        </div>
                        
                        <!-- Average Quiz Score -->
                        <div class="text-center p-5 rounded-xl min-h-[120px] flex flex-col justify-center" style="background: linear-gradient(135deg, #F0F9FF, #FFF4FA);">
                            @if(isset($overallStats['average_quizzes_score']) && $overallStats['average_quizzes_score'] > 0)
                                <div class="text-4xl font-extrabold mb-2" style="color: #6EC6C5;">{{ number_format($overallStats['average_quizzes_score'], 1) }}%</div>
                            @else
                                <div class="text-4xl font-extrabold mb-2 text-gray-400">N/A</div>
                            @endif
                            <div class="text-sm font-semibold text-gray-600">Avg. Quiz Score</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Show/Hide Info Button -->
            <div class="max-w-7xl mx-auto mb-6 flex justify-center fade-in">
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
                 class="max-w-7xl mx-auto space-y-6">
                @foreach($studentProgress as $studentId => $data)
                    <div class="bg-white/90 backdrop-blur-lg rounded-3xl shadow-2xl p-6 border-2 border-pink-200/50 hover:border-pink-300 transition-all duration-300 fade-in hover:shadow-3xl">
                        <!-- Student Header -->
                        <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6 pb-4 border-b-2 border-pink-100">
                            <div class="flex items-center gap-4 mb-4 md:mb-0">
                                <div class="w-16 h-16 rounded-full flex items-center justify-center text-2xl font-bold text-white shadow-lg" style="background: linear-gradient(135deg, #FC8EAC, #6EC6C5);">
                                    {{ substr($data['student_name'], 0, 1) }}
                                </div>
                                <div>
                                    <h3 class="text-2xl font-bold text-gray-800">{{ $data['student_name'] }}</h3>
                                    <p class="text-sm text-gray-600 flex items-center gap-2 mt-1">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-pink-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                        </svg>
                                        {{ $data['class_name'] }}
                                    </p>
                                </div>
                            </div>
                            <div class="text-right">
                                <div class="text-3xl font-extrabold mb-1" style="color: #6EC6C5;">{{ $data['lesson_progress']['percentage'] }}%</div>
                                <div class="text-xs font-semibold text-gray-600">Lesson Progress</div>
                            </div>
                        </div>

                        <!-- Progress Statistics -->
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <!-- Lessons Progress -->
                            <div class="p-5 rounded-xl border-2 border-pink-200/50" style="background: linear-gradient(135deg, #FFF4FA, #F0F9FF);">
                                <div class="flex items-center gap-3 mb-4">
                                    <div class="w-12 h-12 rounded-full flex items-center justify-center text-xl" style="background: linear-gradient(135deg, #FC8EAC, #EC769A);">
                                        📚
                                    </div>
                                    <h4 class="text-lg font-bold text-gray-800">Lessons</h4>
                                </div>
                                <div class="space-y-2">
                                    <div class="flex justify-between text-sm">
                                        <span class="text-gray-600">Completed:</span>
                                        <span class="font-bold" style="color: #FC8EAC;">{{ $data['lesson_progress']['completed_lessons'] }} / {{ $data['lesson_progress']['total_lessons'] }}</span>
                                    </div>
                                    <div class="flex justify-between text-sm">
                                        <span class="text-gray-600">In Progress:</span>
                                        <span class="font-bold" style="color: #6EC6C5;">{{ $data['lesson_progress']['in_progress_lessons'] }}</span>
                                    </div>
                                    <div class="mt-3 h-3 bg-gray-200 rounded-full overflow-hidden">
                                        <div class="h-full rounded-full transition-all duration-500" 
                                             style="width: {{ $data['lesson_progress']['percentage'] }}%; background: linear-gradient(90deg, #FC8EAC, #6EC6C5);">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Games Progress -->
                            <div class="p-5 rounded-xl border-2 border-pink-200/50" style="background: linear-gradient(135deg, #F0F9FF, #FFF4FA);">
                                <div class="flex items-center gap-3 mb-4">
                                    <div class="w-12 h-12 rounded-full flex items-center justify-center text-xl" style="background: linear-gradient(135deg, #6EC6C5, #197D8C);">
                                        🎮
                                    </div>
                                    <h4 class="text-lg font-bold text-gray-800">Games</h4>
                                </div>
                                <div class="space-y-2">
                                    <div class="flex justify-between text-sm">
                                        <span class="text-gray-600">Completed:</span>
                                        <span class="font-bold" style="color: #FC8EAC;">{{ $data['games_stats']['completed'] }}</span>
                                    </div>
                                    <div class="flex justify-between text-sm">
                                        <span class="text-gray-600">In Progress:</span>
                                        <span class="font-bold" style="color: #6EC6C5;">{{ $data['games_stats']['in_progress'] }}</span>
                                    </div>
                                    @if($data['games_stats']['average_score'] > 0)
                                    <div class="flex justify-between text-sm">
                                        <span class="text-gray-600">Avg Score:</span>
                                        <span class="font-bold" style="color: #6EC6C5;">{{ number_format($data['games_stats']['average_score'], 1) }}</span>
                                    </div>
                                    @endif
                                    <div class="mt-3 h-3 bg-gray-200 rounded-full overflow-hidden">
                                        @php
                                            $gamesPercentage = $data['games_stats']['total'] > 0 
                                                ? ($data['games_stats']['completed'] / $data['games_stats']['total'] * 100) 
                                                : 0;
                                        @endphp
                                        <div class="h-full rounded-full transition-all duration-500" 
                                             style="width: {{ $gamesPercentage }}%; background: linear-gradient(90deg, #6EC6C5, #197D8C);">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Quizzes & Assignments -->
                            <div class="p-5 rounded-xl border-2 border-pink-200/50" style="background: linear-gradient(135deg, #FFF4FA, #F0F9FF);">
                                <div class="flex items-center gap-3 mb-4">
                                    <div class="w-12 h-12 rounded-full flex items-center justify-center text-xl" style="background: linear-gradient(135deg, #EC769A, #FC8EAC);">
                                        📝
                                    </div>
                                    <h4 class="text-lg font-bold text-gray-800">Quizzes</h4>
                                </div>
                                <div class="space-y-2 mb-4">
                                    <div class="flex justify-between text-sm">
                                        <span class="text-gray-600">Completed:</span>
                                        <span class="font-bold" style="color: #FC8EAC;">{{ $data['quizzes_stats']['completed'] }}</span>
                                    </div>
                                    <div class="flex justify-between text-sm">
                                        <span class="text-gray-600">Avg Score:</span>
                                        @if(isset($data['quizzes_stats']['average_score']) && $data['quizzes_stats']['average_score'] > 0)
                                            <span class="font-bold" style="color: #6EC6C5;">{{ number_format($data['quizzes_stats']['average_score'], 1) }}%</span>
                                        @else
                                            <span class="font-semibold text-gray-400">N/A</span>
                                        @endif
                                    </div>
                                </div>
                                
                                <div class="border-t-2 border-pink-100 pt-4 mt-4">
                                    <div class="flex items-center gap-3 mb-4">
                                        <div class="w-12 h-12 rounded-full flex items-center justify-center text-xl" style="background: linear-gradient(135deg, #FC8EAC, #EC769A);">
                                            📄
                                        </div>
                                        <h4 class="text-lg font-bold text-gray-800">Assignments</h4>
                                    </div>
                                    <div class="space-y-2">
                                        <div class="flex justify-between text-sm">
                                            <span class="text-gray-600">Submitted:</span>
                                            <span class="font-bold" style="color: #FC8EAC;">{{ $data['assignments_stats']['submitted'] }} / {{ $data['assignments_stats']['total'] }}</span>
                                        </div>
                                        <div class="flex justify-between text-sm">
                                            <span class="text-gray-600">Pending:</span>
                                            <span class="font-bold" style="color: #6EC6C5;">{{ $data['assignments_stats']['pending'] }}</span>
                                        </div>
                                        <div class="flex justify-between text-sm">
                                            <span class="text-gray-600">Avg Score:</span>
                                            @if(isset($data['assignments_stats']['average_score']) && $data['assignments_stats']['average_score'] > 0)
                                                <span class="font-bold" style="color: #6EC6C5;">{{ number_format($data['assignments_stats']['average_score'], 1) }}%</span>
                                            @else
                                                <span class="font-semibold text-gray-400">N/A</span>
                                            @endif
                                        </div>
                                        <div class="mt-3 h-3 bg-gray-200 rounded-full overflow-hidden">
                                            <div class="h-full rounded-full transition-all duration-500" 
                                                 style="width: {{ $data['assignments_stats']['completed_percentage'] }}%; background: linear-gradient(90deg, #FC8EAC, #EC769A);">
                                            </div>
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
@endsection
