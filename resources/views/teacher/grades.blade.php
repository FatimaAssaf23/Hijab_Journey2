@extends('layouts.app')

@section('content')
<style>
    @keyframes float {
        0%, 100% { transform: translateY(0px); }
        50% { transform: translateY(-10px); }
    }
    @keyframes pulse {
        0%, 100% { opacity: 1; }
        50% { opacity: 0.7; }
    }
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }
    @keyframes slideIn {
        from { opacity: 0; transform: translateX(-20px); }
        to { opacity: 1; transform: translateX(0); }
    }
    @keyframes scaleIn {
        from { opacity: 0; transform: scale(0.95); }
        to { opacity: 1; transform: scale(1); }
    }
    @keyframes shimmer {
        0% { background-position: -1000px 0; }
        100% { background-position: 1000px 0; }
    }
    .fade-in {
        animation: fadeIn 0.6s ease-out;
    }
    .float-animation {
        animation: float 3s ease-in-out infinite;
    }
    .slide-in {
        animation: slideIn 0.5s ease-out;
    }
    .scale-in {
        animation: scaleIn 0.4s ease-out;
    }
    .grade-card {
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
        overflow: hidden;
    }
    .grade-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.4), transparent);
        transition: left 0.5s;
    }
    .grade-card:hover::before {
        left: 100%;
    }
    .grade-card:hover {
        transform: translateY(-8px) scale(1.02);
        box-shadow: 0 20px 40px rgba(252, 142, 172, 0.3), 0 0 0 1px rgba(110, 198, 197, 0.2);
    }
    .progress-ring {
        transform: rotate(-90deg);
    }
    .progress-ring-circle {
        transition: stroke-dashoffset 0.6s ease-in-out;
    }
    .section-header {
        background: linear-gradient(135deg, rgba(255, 255, 255, 0.95) 0%, rgba(255, 244, 250, 0.8) 100%);
        backdrop-filter: blur(10px);
    }
    .section-content {
        max-height: 5000px;
        overflow: hidden;
        transition: max-height 0.4s ease-in-out, opacity 0.3s ease-in-out, padding 0.4s ease-in-out;
        opacity: 1;
        padding-top: 0;
        padding-bottom: 0;
    }
    .section-content.collapsed {
        max-height: 0;
        opacity: 0;
        padding-top: 0;
        padding-bottom: 0;
    }
    .toggle-icon {
        transition: transform 0.3s ease-in-out;
    }
    .toggle-icon.rotated {
        transform: rotate(180deg);
    }
    .grade-badge {
        position: absolute;
        top: -8px;
        right: -8px;
        width: 32px;
        height: 32px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        font-size: 0.75rem;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        z-index: 10;
    }
    .sparkle {
        position: absolute;
        width: 4px;
        height: 4px;
        background: white;
        border-radius: 50%;
        animation: sparkle 2s infinite;
    }
    @keyframes sparkle {
        0%, 100% { opacity: 0; transform: scale(0); }
        50% { opacity: 1; transform: scale(1); }
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Handle section toggles
    document.querySelectorAll('[data-toggle-section]').forEach(button => {
        button.addEventListener('click', function() {
            const sectionId = this.getAttribute('data-toggle-section');
            const content = document.getElementById(sectionId);
            const icon = this.querySelector('.toggle-icon');
            
            if (content) {
                content.classList.toggle('collapsed');
                if (icon) {
                    icon.classList.toggle('rotated');
                }
            }
        });
    });

    // Animate progress rings
    function animateProgressRings() {
        document.querySelectorAll('.progress-ring-circle').forEach(circle => {
            const percentage = parseFloat(circle.getAttribute('data-percentage'));
            const radius = 45;
            const circumference = 2 * Math.PI * radius;
            const offset = circumference - (percentage / 100) * circumference;
            circle.style.strokeDasharray = `${circumference} ${circumference}`;
            circle.style.strokeDashoffset = offset;
        });
    }
    animateProgressRings();
});
</script>

<div class="min-h-screen" style="background: linear-gradient(135deg, #FFF4FA 0%, #F0F9FF 50%, #FFF4FA 100%);">
    <!-- Decorative Background Elements -->
    <div class="absolute inset-0 overflow-hidden pointer-events-none">
        <div class="absolute top-20 left-10 w-64 h-64 bg-pink-200/30 rounded-full blur-3xl float-animation"></div>
        <div class="absolute bottom-20 right-10 w-80 h-80 bg-cyan-200/30 rounded-full blur-3xl float-animation" style="animation-delay: 1.5s;"></div>
        <div class="absolute top-1/2 left-1/2 w-72 h-72 bg-pink-100/20 rounded-full blur-3xl float-animation" style="animation-delay: 3s;"></div>
        <div class="absolute top-40 right-40 w-48 h-48 bg-cyan-100/25 rounded-full blur-2xl float-animation" style="animation-delay: 2s;"></div>
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
        <div class="text-center mb-12 fade-in">
            <h1 class="text-5xl md:text-6xl font-extrabold mb-4" style="background: linear-gradient(135deg, #FC8EAC 0%, #6EC6C5 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;">
                <span class="flex items-center justify-center gap-3">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-pink-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z" />
                    </svg>
                    Student Grades
                </span>
            </h1>
            <p class="text-xl text-gray-600 font-medium">View and track grades for all your students ✨</p>
        </div>

        @if(empty($studentGrades))
            <div class="max-w-2xl mx-auto bg-white/90 backdrop-blur-lg rounded-3xl shadow-2xl p-12 text-center border-2 border-pink-200/50 fade-in">
                <div class="text-6xl mb-4">📚</div>
                <h3 class="text-2xl font-bold text-gray-700 mb-2">No Students Yet</h3>
                <p class="text-gray-500">You don't have any students in your classes yet.</p>
            </div>
        @else
            <!-- Overall Statistics -->
            <div class="w-full mb-12">
                <div class="bg-gradient-to-br from-white via-pink-50/50 to-cyan-50/50 backdrop-blur-lg rounded-3xl shadow-2xl p-8 border-2 border-pink-200/30 fade-in hover:shadow-3xl transition-all duration-300">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                        <div class="text-center p-6 rounded-2xl bg-gradient-to-br from-pink-50 to-pink-100/50 border-2 border-pink-200/50 hover:border-pink-300 transition-all duration-300 scale-in">
                            <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-gradient-to-br from-pink-400 to-pink-500 mb-4 shadow-lg">
                                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                                </svg>
                            </div>
                            <div class="text-5xl font-extrabold mb-2 bg-gradient-to-r from-pink-500 to-pink-600 bg-clip-text text-transparent">{{ count($studentGrades) }}</div>
                            <div class="text-sm font-bold text-gray-700 uppercase tracking-wide">Total Students</div>
                        </div>
                        <div class="text-center p-6 rounded-2xl bg-gradient-to-br from-cyan-50 to-cyan-100/50 border-2 border-cyan-200/50 hover:border-cyan-300 transition-all duration-300 scale-in" style="animation-delay: 0.1s;">
                            <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-gradient-to-br from-cyan-400 to-cyan-500 mb-4 shadow-lg">
                                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                </svg>
                            </div>
                            <div class="text-5xl font-extrabold mb-2 bg-gradient-to-r from-cyan-500 to-cyan-600 bg-clip-text text-transparent">{{ count($classes) }}</div>
                            <div class="text-sm font-bold text-gray-700 uppercase tracking-wide">Classes</div>
                        </div>
                        <div class="text-center p-6 rounded-2xl bg-gradient-to-br from-purple-50 to-purple-100/50 border-2 border-purple-200/50 hover:border-purple-300 transition-all duration-300 scale-in" style="animation-delay: 0.2s;">
                            <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-gradient-to-br from-purple-400 to-pink-400 mb-4 shadow-lg">
                                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                </svg>
                            </div>
                            <div class="text-5xl font-extrabold mb-2 bg-gradient-to-r from-purple-500 via-pink-500 to-cyan-500 bg-clip-text text-transparent">
                                {{ $overallAverageGrade ?? 0 }}<span class="text-3xl">%</span>
                            </div>
                            <div class="text-sm font-bold text-gray-700 uppercase tracking-wide">Class Average</div>
                            <div class="text-xs text-gray-500 mt-1">Across {{ $studentsWithGrades ?? 0 }} students</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Student Grades Cards -->
            <div class="w-full space-y-10">
                @foreach($studentGrades as $studentId => $data)
                    <div class="bg-gradient-to-br from-white via-pink-50/30 to-cyan-50/30 backdrop-blur-lg rounded-3xl shadow-2xl p-8 border-2 border-pink-200/40 hover:border-pink-300/60 transition-all duration-300 fade-in hover:shadow-3xl overflow-hidden relative">
                        <!-- Decorative gradient overlay -->
                        <div class="absolute top-0 right-0 w-64 h-64 bg-gradient-to-br from-pink-200/20 to-cyan-200/20 rounded-full blur-3xl -mr-32 -mt-32"></div>
                        <div class="absolute bottom-0 left-0 w-48 h-48 bg-gradient-to-br from-cyan-200/15 to-pink-200/15 rounded-full blur-2xl -ml-24 -mb-24"></div>
                        
                        <!-- Student Header -->
                        <div class="relative flex flex-col md:flex-row md:items-center md:justify-between mb-8 pb-6 border-b-2 border-gradient-to-r from-pink-200/50 to-cyan-200/50">
                            <div class="flex items-center gap-5 mb-4 md:mb-0">
                                <div class="relative">
                                    <div class="w-20 h-20 rounded-2xl flex items-center justify-center text-3xl font-bold text-white shadow-xl transform rotate-3 hover:rotate-0 transition-transform duration-300" style="background: linear-gradient(135deg, #FC8EAC 0%, #6EC6C5 100%);">
                                        {{ substr($data['student_name'], 0, 1) }}
                                    </div>
                                    <div class="absolute -top-1 -right-1 w-6 h-6 bg-cyan-400 rounded-full border-4 border-white shadow-lg"></div>
                                    <div class="sparkle" style="top: 5px; left: 5px; animation-delay: 0s;"></div>
                                    <div class="sparkle" style="bottom: 5px; right: 5px; animation-delay: 1s;"></div>
                                </div>
                                <div>
                                    <h3 class="text-3xl font-extrabold text-gray-800 mb-2 bg-gradient-to-r from-gray-800 to-gray-600 bg-clip-text text-transparent">{{ $data['student_name'] }}</h3>
                                    <p class="text-sm text-gray-600 flex items-center gap-2 font-semibold">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-pink-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                        </svg>
                                        <span class="px-3 py-1 rounded-full bg-gradient-to-r from-pink-100 to-cyan-100 text-pink-700 font-bold text-xs">{{ $data['class_name'] }}</span>
                                    </p>
                                </div>
                            </div>
                            <div class="text-right bg-gradient-to-br from-cyan-50 to-pink-50 rounded-2xl p-5 border-2 border-cyan-200/50 shadow-lg relative overflow-hidden">
                                <div class="absolute inset-0 bg-gradient-to-br from-pink-200/20 to-cyan-200/20 opacity-50"></div>
                                <div class="relative z-10">
                                    <div class="text-4xl font-extrabold mb-1 bg-gradient-to-r from-cyan-600 to-pink-600 bg-clip-text text-transparent">{{ number_format($data['average_percentage'], 1) }}%</div>
                                    <div class="text-xs font-bold text-gray-600 uppercase tracking-wider">Average Grade</div>
                                </div>
                            </div>
                        </div>

                        <!-- Grades List - Organized by Type -->
                        @php
                            // Separate grades by type
                            $assignments = [];
                            $quizzes = [];
                            $games = [];
                            
                            foreach ($data['grades'] as $grade) {
                                $type = $grade->type ?? 'Other';
                                if ($type === 'assignment') {
                                    $assignments[] = $grade;
                                } elseif ($type === 'quiz') {
                                    $quizzes[] = $grade;
                                } elseif ($type === 'game') {
                                    $games[] = $grade;
                                }
                            }
                        @endphp
                        
                        @if(count($data['grades']) > 0)
                            <div class="space-y-10">
                                <!-- Assignments Section -->
                                @if(count($assignments) > 0)
                                    <div class="relative">
                                        <div class="section-header flex items-center gap-4 mb-6 p-5 rounded-2xl border-2 border-pink-200/50 shadow-lg cursor-pointer hover:bg-pink-50/50 transition-colors duration-300" data-toggle-section="assignments-{{ $studentId }}">
                                            <div class="w-16 h-16 rounded-xl bg-gradient-to-br from-pink-400 to-pink-500 flex items-center justify-center shadow-lg transform hover:scale-110 transition-transform duration-300">
                                                <span class="text-3xl">📄</span>
                                            </div>
                                            <div class="flex-1">
                                                <h4 class="text-2xl font-extrabold text-gray-800 mb-1">Assignments</h4>
                                                <p class="text-xs text-gray-600 font-semibold">Teacher-graded submissions</p>
                                            </div>
                                            <span class="px-5 py-2 rounded-full bg-gradient-to-r from-pink-500 to-pink-600 text-white text-sm font-bold shadow-lg">{{ count($assignments) }}</span>
                                            <div class="ml-2 w-12 h-12 rounded-full bg-pink-100 hover:bg-pink-200 flex items-center justify-center transition-colors duration-300">
                                                <svg class="w-6 h-6 text-pink-600 toggle-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                                </svg>
                                            </div>
                                        </div>
                                        <div id="assignments-{{ $studentId }}" class="section-content">
                                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                                                @foreach($assignments as $index => $grade)
                                                    @php
                                                        $percentage = $grade->percentage ?? 0;
                                                        $itemName = $grade->item_name ?? 'N/A';
                                                        
                                                        // Color based on percentage
                                                        $primaryColor = '#FC8EAC'; // Pink
                                                        $secondaryColor = '#6EC6C5'; // Turquoise
                                                        $bgGradient = 'from-pink-50 to-pink-100';
                                                        $borderColor = 'border-pink-300';
                                                        $textColor = 'text-pink-700';
                                                        $ringColor = '#FC8EAC';
                                                        
                                                        if ($percentage >= 90) {
                                                            $primaryColor = '#6EC6C5';
                                                            $secondaryColor = '#4FD1C7';
                                                            $bgGradient = 'from-cyan-50 to-cyan-100';
                                                            $borderColor = 'border-cyan-300';
                                                            $textColor = 'text-cyan-700';
                                                            $ringColor = '#6EC6C5';
                                                        } elseif ($percentage >= 70) {
                                                            $primaryColor = '#A78BFA';
                                                            $secondaryColor = '#8B5CF6';
                                                            $bgGradient = 'from-purple-50 to-purple-100';
                                                            $borderColor = 'border-purple-300';
                                                            $textColor = 'text-purple-700';
                                                            $ringColor = '#A78BFA';
                                                        } elseif ($percentage >= 50) {
                                                            $primaryColor = '#FBBF24';
                                                            $secondaryColor = '#F59E0B';
                                                            $bgGradient = 'from-yellow-50 to-yellow-100';
                                                            $borderColor = 'border-yellow-300';
                                                            $textColor = 'text-yellow-700';
                                                            $ringColor = '#FBBF24';
                                                        }
                                                        
                                                        // Format the date
                                                        $gradedDate = null;
                                                        if ($grade->graded_at) {
                                                            if (is_string($grade->graded_at)) {
                                                                $gradedDate = \Carbon\Carbon::parse($grade->graded_at);
                                                            } else {
                                                                $gradedDate = $grade->graded_at;
                                                            }
                                                        }
                                                        
                                                        $gradeDisplay = number_format($grade->grade_value, 2) . ' / ' . ($grade->max_grade ?? 100);
                                                        $radius = 45;
                                                        $circumference = 2 * M_PI * $radius;
                                                        $offset = $circumference - ($percentage / 100) * $circumference;
                                                    @endphp
                                                    
                                                    <div class="grade-card bg-gradient-to-br {{ $bgGradient }} rounded-2xl p-6 border-2 {{ $borderColor }} shadow-lg relative overflow-hidden group" style="animation-delay: {{ $index * 0.1 }}s;">
                                                        <!-- Decorative corner accent -->
                                                        <div class="absolute top-0 right-0 w-24 h-24 bg-gradient-to-br from-white/30 to-transparent rounded-bl-full"></div>
                                                        
                                                        <!-- Progress Ring -->
                                                        <div class="flex items-center justify-center mb-4 relative">
                                                            <svg class="progress-ring w-28 h-28" viewBox="0 0 100 100">
                                                                <circle cx="50" cy="50" r="45" fill="none" stroke="rgba(255, 255, 255, 0.3)" stroke-width="8"/>
                                                                <circle cx="50" cy="50" r="45" fill="none" stroke="{{ $ringColor }}" stroke-width="8" 
                                                                        class="progress-ring-circle" 
                                                                        data-percentage="{{ $percentage }}"
                                                                        style="stroke-dasharray: {{ $circumference }} {{ $circumference }}; stroke-dashoffset: {{ $offset }};"/>
                                                            </svg>
                                                            <div class="absolute inset-0 flex items-center justify-center">
                                                                <div class="text-center">
                                                                    <div class="text-2xl font-extrabold {{ $textColor }}">{{ number_format($percentage, 0) }}%</div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        
                                                        <!-- Grade Info -->
                                                        <div class="text-center mb-4">
                                                            <h5 class="font-extrabold text-gray-800 text-lg mb-2 line-clamp-2">{{ $itemName }}</h5>
                                                            <div class="flex items-center justify-center gap-2 mb-3">
                                                                <span class="px-3 py-1 rounded-full bg-white/90 text-xs font-bold shadow-sm {{ $textColor }}">Assignment</span>
                                                            </div>
                                                            <div class="text-3xl font-extrabold {{ $textColor }} mb-1">{{ $gradeDisplay }}</div>
                                                        </div>
                                                        
                                                        <!-- Date -->
                                                        @if($gradedDate)
                                                            <div class="text-center text-xs text-gray-600 flex items-center justify-center gap-1 mb-3">
                                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                                                </svg>
                                                                {{ $gradedDate->format('M d, Y') }}
                                                            </div>
                                                        @endif
                                                        
                                                        <!-- Feedback -->
                                                        @if($grade->feedback)
                                                            <div class="mt-3 p-3 rounded-lg bg-white/60 border-l-4" style="border-color: {{ $ringColor }};">
                                                                <p class="text-xs text-gray-700 italic line-clamp-2">"{{ strlen($grade->feedback) > 80 ? substr($grade->feedback, 0, 80) . '...' : $grade->feedback }}"</p>
                                                            </div>
                                                        @endif
                                                        
                                                        <!-- Grade Badge -->
                                                        <div class="grade-badge" style="background: {{ $ringColor }}; color: white;">
                                                            @if($percentage >= 90) A+
                                                            @elseif($percentage >= 80) A
                                                            @elseif($percentage >= 70) B
                                                            @elseif($percentage >= 60) C
                                                            @elseif($percentage >= 50) D
                                                            @else F
                                                            @endif
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                @endif
                                
                                <!-- Quizzes Section -->
                                @if(count($quizzes) > 0)
                                    <div class="relative">
                                        <div class="section-header flex items-center gap-4 mb-6 p-5 rounded-2xl border-2 border-cyan-200/50 shadow-lg cursor-pointer hover:bg-cyan-50/50 transition-colors duration-300" data-toggle-section="quizzes-{{ $studentId }}">
                                            <div class="w-16 h-16 rounded-xl bg-gradient-to-br from-cyan-400 to-cyan-500 flex items-center justify-center shadow-lg transform hover:scale-110 transition-transform duration-300">
                                                <span class="text-3xl">📝</span>
                                            </div>
                                            <div class="flex-1">
                                                <h4 class="text-2xl font-extrabold text-gray-800 mb-1">Quizzes</h4>
                                                <p class="text-xs text-gray-600 font-semibold">Auto-graded assessments</p>
                                            </div>
                                            <span class="px-5 py-2 rounded-full bg-gradient-to-r from-cyan-500 to-cyan-600 text-white text-sm font-bold shadow-lg">{{ count($quizzes) }}</span>
                                            <div class="ml-2 w-12 h-12 rounded-full bg-cyan-100 hover:bg-cyan-200 flex items-center justify-center transition-colors duration-300">
                                                <svg class="w-6 h-6 text-cyan-600 toggle-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                                </svg>
                                            </div>
                                        </div>
                                        <div id="quizzes-{{ $studentId }}" class="section-content">
                                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                                                @foreach($quizzes as $index => $grade)
                                                    @php
                                                        $percentage = $grade->percentage ?? 0;
                                                        $itemName = $grade->item_name ?? 'N/A';
                                                        
                                                        // Color based on percentage
                                                        $primaryColor = '#FC8EAC';
                                                        $secondaryColor = '#6EC6C5';
                                                        $bgGradient = 'from-pink-50 to-pink-100';
                                                        $borderColor = 'border-pink-300';
                                                        $textColor = 'text-pink-700';
                                                        $ringColor = '#FC8EAC';
                                                        
                                                        if ($percentage >= 90) {
                                                            $primaryColor = '#6EC6C5';
                                                            $secondaryColor = '#4FD1C7';
                                                            $bgGradient = 'from-cyan-50 to-cyan-100';
                                                            $borderColor = 'border-cyan-300';
                                                            $textColor = 'text-cyan-700';
                                                            $ringColor = '#6EC6C5';
                                                        } elseif ($percentage >= 70) {
                                                            $primaryColor = '#A78BFA';
                                                            $secondaryColor = '#8B5CF6';
                                                            $bgGradient = 'from-purple-50 to-purple-100';
                                                            $borderColor = 'border-purple-300';
                                                            $textColor = 'text-purple-700';
                                                            $ringColor = '#A78BFA';
                                                        } elseif ($percentage >= 50) {
                                                            $primaryColor = '#FBBF24';
                                                            $secondaryColor = '#F59E0B';
                                                            $bgGradient = 'from-yellow-50 to-yellow-100';
                                                            $borderColor = 'border-yellow-300';
                                                            $textColor = 'text-yellow-700';
                                                            $ringColor = '#FBBF24';
                                                        }
                                                        
                                                        // Format the date
                                                        $gradedDate = null;
                                                        if ($grade->graded_at) {
                                                            if (is_string($grade->graded_at)) {
                                                                $gradedDate = \Carbon\Carbon::parse($grade->graded_at);
                                                            } else {
                                                                $gradedDate = $grade->graded_at;
                                                            }
                                                        }
                                                        
                                                        $gradeDisplay = number_format($grade->grade_value, 2) . ' / ' . ($grade->max_grade ?? 100);
                                                        $radius = 45;
                                                        $circumference = 2 * M_PI * $radius;
                                                        $offset = $circumference - ($percentage / 100) * $circumference;
                                                    @endphp
                                                    
                                                    <div class="grade-card bg-gradient-to-br {{ $bgGradient }} rounded-2xl p-6 border-2 {{ $borderColor }} shadow-lg relative overflow-hidden group" style="animation-delay: {{ $index * 0.1 }}s;">
                                                        <!-- Decorative corner accent -->
                                                        <div class="absolute top-0 right-0 w-24 h-24 bg-gradient-to-br from-white/30 to-transparent rounded-bl-full"></div>
                                                        
                                                        <!-- Progress Ring -->
                                                        <div class="flex items-center justify-center mb-4 relative">
                                                            <svg class="progress-ring w-28 h-28" viewBox="0 0 100 100">
                                                                <circle cx="50" cy="50" r="45" fill="none" stroke="rgba(255, 255, 255, 0.3)" stroke-width="8"/>
                                                                <circle cx="50" cy="50" r="45" fill="none" stroke="{{ $ringColor }}" stroke-width="8" 
                                                                        class="progress-ring-circle" 
                                                                        data-percentage="{{ $percentage }}"
                                                                        style="stroke-dasharray: {{ $circumference }} {{ $circumference }}; stroke-dashoffset: {{ $offset }};"/>
                                                            </svg>
                                                            <div class="absolute inset-0 flex items-center justify-center">
                                                                <div class="text-center">
                                                                    <div class="text-2xl font-extrabold {{ $textColor }}">{{ number_format($percentage, 0) }}%</div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        
                                                        <!-- Grade Info -->
                                                        <div class="text-center mb-4">
                                                            <h5 class="font-extrabold text-gray-800 text-lg mb-2 line-clamp-2">{{ $itemName }}</h5>
                                                            <div class="flex items-center justify-center gap-2 mb-3">
                                                                <span class="px-3 py-1 rounded-full bg-white/90 text-xs font-bold shadow-sm {{ $textColor }}">Quiz</span>
                                                            </div>
                                                            <div class="text-3xl font-extrabold {{ $textColor }} mb-1">{{ $gradeDisplay }}</div>
                                                        </div>
                                                        
                                                        <!-- Date -->
                                                        @if($gradedDate)
                                                            <div class="text-center text-xs text-gray-600 flex items-center justify-center gap-1 mb-3">
                                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                                                </svg>
                                                                {{ $gradedDate->format('M d, Y') }}
                                                            </div>
                                                        @endif
                                                        
                                                        <!-- Feedback -->
                                                        @if($grade->feedback)
                                                            <div class="mt-3 p-3 rounded-lg bg-white/60 border-l-4" style="border-color: {{ $ringColor }};">
                                                                <p class="text-xs text-gray-700 italic line-clamp-2">"{{ strlen($grade->feedback) > 80 ? substr($grade->feedback, 0, 80) . '...' : $grade->feedback }}"</p>
                                                            </div>
                                                        @endif
                                                        
                                                        <!-- Grade Badge -->
                                                        <div class="grade-badge" style="background: {{ $ringColor }}; color: white;">
                                                            @if($percentage >= 90) A+
                                                            @elseif($percentage >= 80) A
                                                            @elseif($percentage >= 70) B
                                                            @elseif($percentage >= 60) C
                                                            @elseif($percentage >= 50) D
                                                            @else F
                                                            @endif
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                @endif
                                
                                <!-- Games Section -->
                                @if(count($games) > 0)
                                    <div class="relative">
                                        <div class="section-header flex items-center gap-4 mb-6 p-5 rounded-2xl border-2 border-cyan-200/50 shadow-lg cursor-pointer hover:bg-cyan-50/50 transition-colors duration-300" data-toggle-section="games-{{ $studentId }}">
                                            <div class="w-16 h-16 rounded-xl bg-gradient-to-br from-cyan-400 to-cyan-500 flex items-center justify-center shadow-lg transform hover:scale-110 transition-transform duration-300">
                                                <span class="text-3xl">🎮</span>
                                            </div>
                                            <div class="flex-1">
                                                <h4 class="text-2xl font-extrabold text-gray-800 mb-1">Games</h4>
                                                <p class="text-xs text-gray-600 font-semibold">Interactive learning activities</p>
                                            </div>
                                            <span class="px-5 py-2 rounded-full bg-gradient-to-r from-cyan-500 to-cyan-600 text-white text-sm font-bold shadow-lg">{{ count($games) }}</span>
                                            <div class="ml-2 w-12 h-12 rounded-full bg-cyan-100 hover:bg-cyan-200 flex items-center justify-center transition-colors duration-300">
                                                <svg class="w-6 h-6 text-cyan-600 toggle-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                                </svg>
                                            </div>
                                        </div>
                                        <div id="games-{{ $studentId }}" class="section-content">
                                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                                                @foreach($games as $index => $grade)
                                                    @php
                                                        $percentage = $grade->percentage ?? 0;
                                                        $itemName = $grade->item_name ?? 'N/A';
                                                        
                                                        // Color based on percentage/score
                                                        $primaryColor = '#FC8EAC';
                                                        $secondaryColor = '#6EC6C5';
                                                        $bgGradient = 'from-pink-50 to-pink-100';
                                                        $borderColor = 'border-pink-300';
                                                        $textColor = 'text-pink-700';
                                                        $ringColor = '#FC8EAC';
                                                        
                                                        if ($percentage >= 90) {
                                                            $primaryColor = '#6EC6C5';
                                                            $secondaryColor = '#4FD1C7';
                                                            $bgGradient = 'from-cyan-50 to-cyan-100';
                                                            $borderColor = 'border-cyan-300';
                                                            $textColor = 'text-cyan-700';
                                                            $ringColor = '#6EC6C5';
                                                        } elseif ($percentage >= 70) {
                                                            $primaryColor = '#A78BFA';
                                                            $secondaryColor = '#8B5CF6';
                                                            $bgGradient = 'from-purple-50 to-purple-100';
                                                            $borderColor = 'border-purple-300';
                                                            $textColor = 'text-purple-700';
                                                            $ringColor = '#A78BFA';
                                                        } elseif ($percentage >= 50) {
                                                            $primaryColor = '#FBBF24';
                                                            $secondaryColor = '#F59E0B';
                                                            $bgGradient = 'from-yellow-50 to-yellow-100';
                                                            $borderColor = 'border-yellow-300';
                                                            $textColor = 'text-yellow-700';
                                                            $ringColor = '#FBBF24';
                                                        }
                                                        
                                                        // Format the date
                                                        $gradedDate = null;
                                                        if ($grade->graded_at) {
                                                            if (is_string($grade->graded_at)) {
                                                                $gradedDate = \Carbon\Carbon::parse($grade->graded_at);
                                                            } else {
                                                                $gradedDate = $grade->graded_at;
                                                            }
                                                        }
                                                        
                                                        $gradeDisplay = $grade->grade_value . ' points';
                                                        $radius = 45;
                                                        $circumference = 2 * M_PI * $radius;
                                                        $offset = $circumference - ($percentage / 100) * $circumference;
                                                    @endphp
                                                    
                                                    <div class="grade-card bg-gradient-to-br {{ $bgGradient }} rounded-2xl p-6 border-2 {{ $borderColor }} shadow-lg relative overflow-hidden group" style="animation-delay: {{ $index * 0.1 }}s;">
                                                        <!-- Decorative corner accent -->
                                                        <div class="absolute top-0 right-0 w-24 h-24 bg-gradient-to-br from-white/30 to-transparent rounded-bl-full"></div>
                                                        
                                                        <!-- Progress Ring -->
                                                        <div class="flex items-center justify-center mb-4 relative">
                                                            <svg class="progress-ring w-28 h-28" viewBox="0 0 100 100">
                                                                <circle cx="50" cy="50" r="45" fill="none" stroke="rgba(255, 255, 255, 0.3)" stroke-width="8"/>
                                                                <circle cx="50" cy="50" r="45" fill="none" stroke="{{ $ringColor }}" stroke-width="8" 
                                                                        class="progress-ring-circle" 
                                                                        data-percentage="{{ $percentage }}"
                                                                        style="stroke-dasharray: {{ $circumference }} {{ $circumference }}; stroke-dashoffset: {{ $offset }};"/>
                                                            </svg>
                                                            <div class="absolute inset-0 flex items-center justify-center">
                                                                <div class="text-center">
                                                                    <div class="text-2xl font-extrabold {{ $textColor }}">{{ number_format($percentage, 0) }}%</div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        
                                                        <!-- Grade Info -->
                                                        <div class="text-center mb-4">
                                                            <h5 class="font-extrabold text-gray-800 text-lg mb-2 line-clamp-2">{{ $itemName }}</h5>
                                                            <div class="flex items-center justify-center gap-2 mb-3">
                                                                <span class="px-3 py-1 rounded-full bg-white/90 text-xs font-bold shadow-sm {{ $textColor }}">Game</span>
                                                            </div>
                                                            <div class="text-3xl font-extrabold {{ $textColor }} mb-1">{{ $gradeDisplay }}</div>
                                                        </div>
                                                        
                                                        <!-- Date -->
                                                        @if($gradedDate)
                                                            <div class="text-center text-xs text-gray-600 flex items-center justify-center gap-1 mb-3">
                                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                                                </svg>
                                                                {{ $gradedDate->format('M d, Y') }}
                                                            </div>
                                                        @endif
                                                        
                                                        <!-- Grade Badge -->
                                                        <div class="grade-badge" style="background: {{ $ringColor }}; color: white;">
                                                            @if($percentage >= 90) ⭐
                                                            @elseif($percentage >= 70) 🎯
                                                            @elseif($percentage >= 50) ✓
                                                            @else ✗
                                                            @endif
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        @else
                            <div class="text-center py-12 text-gray-500">
                                <div class="text-6xl mb-4">📭</div>
                                <p class="text-xl font-semibold">No grades yet for this student.</p>
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>
@endsection
