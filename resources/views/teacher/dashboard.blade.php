@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-pink-50 via-cyan-50/30 to-teal-50/20 relative overflow-hidden">
    <!-- Animated Background Elements - Light Pink & Turquoise -->
    <div class="absolute inset-0 overflow-hidden pointer-events-none">
        <div class="absolute -top-40 -left-40 w-[400px] h-[400px] bg-pink-200/40 rounded-full opacity-20 blur-3xl animate-pulse"></div>
        <div class="absolute top-1/2 -right-40 w-[400px] h-[400px] bg-cyan-200/40 rounded-full opacity-20 blur-3xl animate-pulse" style="animation-delay: 1.5s;"></div>
        <div class="absolute -bottom-40 left-1/3 w-[400px] h-[400px] bg-teal-200/40 rounded-full opacity-20 blur-3xl animate-pulse" style="animation-delay: 3s;"></div>
    </div>

    <!-- Floating decorative shapes - Lighter -->
    <div class="absolute top-20 right-20 w-32 h-32 bg-pink-200/30 rounded-full blur-3xl animate-bounce" style="animation-duration: 4s;"></div>
    <div class="absolute bottom-20 left-20 w-40 h-40 bg-cyan-200/30 rounded-full blur-3xl animate-bounce" style="animation-duration: 5s; animation-delay: 1s;"></div>
    <div class="absolute top-1/2 left-1/4 w-28 h-28 bg-teal-200/30 rounded-full blur-2xl animate-bounce" style="animation-duration: 6s; animation-delay: 2s;"></div>

    <div class="relative z-10 w-full max-w-full mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <!-- Hero Header Section -->
        <div class="mb-8 animate-fade-in">
            <div class="relative w-full max-w-6xl mx-auto bg-gradient-to-br from-pink-200/80 via-rose-100/70 to-cyan-200/80 rounded-2xl shadow-xl transform transition-all duration-500 hover:shadow-2xl border border-pink-200/50 backdrop-blur-sm" style="overflow: visible;">
                <!-- Inner container for background with rounded corners -->
                <div class="absolute inset-0 rounded-2xl overflow-hidden pointer-events-none">
                    <!-- Elegant Pattern Overlay -->
                    <div class="absolute inset-0 opacity-5">
                        <div class="absolute top-0 left-0 w-full h-full" style="background-image: radial-gradient(circle, rgba(255,255,255,0.4) 1px, transparent 1px); background-size: 25px 25px;"></div>
                    </div>
                    
                    <!-- Subtle gradient overlay -->
                    <div class="absolute inset-0 bg-gradient-to-br from-white/20 to-transparent"></div>
                </div>
                
                <div class="relative flex flex-col lg:flex-row items-center justify-between p-8 lg:p-12 lg:pt-20 lg:pb-20 lg:pr-24" style="overflow: visible;">
                    <div class="flex-1 text-center lg:text-left mb-5 lg:mb-0 z-10">
                        <div class="inline-flex items-center gap-2 bg-white/40 backdrop-blur-md px-3 py-1.5 rounded-full mb-3 border border-pink-300/30 shadow-md animate-slide-in-left">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 text-pink-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                            <span class="text-pink-400 font-semibold text-xs tracking-wide">TEACHER DASHBOARD</span>
                        </div>
                        <h1 class="text-2xl lg:text-3xl font-black text-gray-800 mb-2 tracking-tight leading-tight animate-slide-in-left" style="animation-delay: 0.1s;">
                            Welcome Back,<br>
                            <span class="bg-gradient-to-r from-pink-300 to-cyan-300 bg-clip-text text-transparent">{{ Auth::user()->first_name }}!</span> ✨
                        </h1>
                        <p class="text-sm lg:text-base text-gray-700 font-medium italic animate-slide-in-left" style="animation-delay: 0.2s;">
                            "Every letter is a seed growing in the mukalfa soul to bloom into a brighter future."
                        </p>
                    </div>
                    
                    <!-- Right: Decorative Icon -->
                    <div class="relative flex-shrink-0 mt-5 lg:mt-0 animate-slide-in-right" style="overflow: visible; margin-right: -40px; margin-top: 19px; margin-bottom: -10px;">
                        <div class="relative" style="overflow: visible;">
                            <!-- Subtle Glow Layers -->
                            <div class="absolute inset-0 bg-pink-300/30 rounded-full blur-2xl opacity-50 animate-pulse" style="top: -25px; left: -25px; right: -25px; bottom: -25px;"></div>
                            <div class="absolute inset-0 bg-cyan-300/30 rounded-full blur-xl opacity-40 animate-pulse" style="animation-delay: 1s; top: -20px; left: -20px; right: -20px; bottom: -20px;"></div>
                            
                            <!-- Elegant Decorative Elements -->
                            <div class="absolute -top-2 -right-2 w-8 h-8 bg-pink-300/80 rounded-full flex items-center justify-center animate-bounce shadow-md border-2 border-white backdrop-blur-sm">
                                <span class="text-base">⭐</span>
                            </div>
                            <div class="absolute -bottom-2 -left-2 w-8 h-8 bg-cyan-300/80 rounded-full flex items-center justify-center animate-bounce shadow-md border-2 border-white backdrop-blur-sm" style="animation-delay: 0.5s;">
                                <span class="text-base">💖</span>
                            </div>
                            <div class="absolute top-1/2 -right-5 w-7 h-7 bg-teal-300/80 rounded-full flex items-center justify-center animate-bounce shadow-md border-2 border-white backdrop-blur-sm" style="animation-delay: 1s;">
                                <span class="text-xs">✨</span>
                            </div>
                            
                            <!-- Image container with elegant styling -->
                            <div class="relative bg-white rounded-full p-1.5 shadow-xl transform hover:scale-105 transition-transform duration-500 border-2 border-pink-200/60" style="overflow: visible;">
                                <div class="absolute inset-0 bg-gradient-to-br from-pink-200/40 to-cyan-200/40 rounded-full opacity-30 blur-lg" style="top: -15px; left: -15px; right: -15px; bottom: -15px;"></div>
                                <div class="relative w-48 h-48 lg:w-60 lg:h-60 rounded-full bg-gradient-to-br from-pink-300 to-cyan-300 flex items-center justify-center border border-pink-200/40 shadow-lg z-10">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-24 h-24 lg:w-32 lg:h-32 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                    </svg>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Stats Cards Grid -->
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-3 lg:gap-4 mb-8">
            <!-- Total Classes Card -->
            <div class="bg-white/90 backdrop-blur-md rounded-xl shadow-xl p-4 lg:p-5 border border-pink-200/40 transform transition-all duration-300 hover:shadow-2xl hover:-translate-y-1 hover:scale-105 animate-fade-in-up" style="animation-delay: 0.1s;">
                <div class="flex items-center justify-between mb-3">
                    <div class="w-10 h-10 lg:w-12 lg:h-12 bg-gradient-to-br from-pink-300 to-rose-300 rounded-lg flex items-center justify-center shadow-md transform rotate-3 hover:rotate-6 transition-transform">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 lg:h-6 lg:w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                        </svg>
                    </div>
                    <div class="text-2xl lg:text-3xl opacity-20">🎓</div>
                </div>
                <div class="text-pink-400 text-[10px] lg:text-xs font-bold uppercase tracking-wider mb-1.5">Total Classes</div>
                <div class="text-2xl lg:text-3xl font-black text-gray-800 mb-1">{{ $totalClasses }}</div>
                <div class="text-[10px] lg:text-xs text-gray-600 font-medium">Active classes</div>
            </div>

            <!-- Total Students Card -->
            <div class="bg-white/90 backdrop-blur-md rounded-xl shadow-xl p-4 lg:p-5 border border-cyan-200/40 transform transition-all duration-300 hover:shadow-2xl hover:-translate-y-1 hover:scale-105 animate-fade-in-up" style="animation-delay: 0.2s;">
                <div class="flex items-center justify-between mb-3">
                    <div class="w-10 h-10 lg:w-12 lg:h-12 bg-gradient-to-br from-cyan-300 to-teal-300 rounded-lg flex items-center justify-center shadow-md transform -rotate-3 hover:-rotate-6 transition-transform">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 lg:h-6 lg:w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                    </div>
                    <div class="text-2xl lg:text-3xl opacity-20">👥</div>
                </div>
                <div class="text-cyan-400 text-[10px] lg:text-xs font-bold uppercase tracking-wider mb-1.5">Total Students</div>
                <div class="text-2xl lg:text-3xl font-black text-gray-800 mb-1">{{ $totalStudents }}</div>
                <div class="text-[10px] lg:text-xs text-gray-600 font-medium">Students across classes</div>
            </div>

            <!-- Total Assignments Card -->
            <div class="bg-white/90 backdrop-blur-md rounded-xl shadow-xl p-4 lg:p-5 border border-pink-200/40 transform transition-all duration-300 hover:shadow-2xl hover:-translate-y-1 hover:scale-105 animate-fade-in-up" style="animation-delay: 0.3s;">
                <div class="flex items-center justify-between mb-3">
                    <div class="w-10 h-10 lg:w-12 lg:h-12 bg-gradient-to-br from-pink-300 to-cyan-300 rounded-lg flex items-center justify-center shadow-md transform rotate-3 hover:rotate-6 transition-transform">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 lg:h-6 lg:w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                    </div>
                    <div class="text-2xl lg:text-3xl opacity-20">📝</div>
                </div>
                <div class="text-pink-400 text-[10px] lg:text-xs font-bold uppercase tracking-wider mb-1.5">Total Assignments</div>
                <div class="text-2xl lg:text-3xl font-black text-gray-800 mb-1">{{ $totalAssignments }}</div>
                <div class="text-[10px] lg:text-xs text-gray-600 font-medium">Assignments created</div>
            </div>

            <!-- Total Quizzes Card -->
            <div class="bg-white/90 backdrop-blur-md rounded-xl shadow-xl p-4 lg:p-5 border border-teal-200/40 transform transition-all duration-300 hover:shadow-2xl hover:-translate-y-1 hover:scale-105 animate-fade-in-up" style="animation-delay: 0.4s;">
                <div class="flex items-center justify-between mb-3">
                    <div class="w-10 h-10 lg:w-12 lg:h-12 bg-gradient-to-br from-teal-300 to-cyan-300 rounded-lg flex items-center justify-center shadow-md transform -rotate-3 hover:-rotate-6 transition-transform">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 lg:h-6 lg:w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div class="text-2xl lg:text-3xl opacity-20">❓</div>
                </div>
                <div class="text-teal-600 text-[10px] lg:text-xs font-bold uppercase tracking-wider mb-1.5">Total Quizzes</div>
                <div class="text-2xl lg:text-3xl font-black text-gray-800 mb-1">{{ $totalQuizzes }}</div>
                <div class="text-[10px] lg:text-xs text-gray-600 font-medium">Quizzes created</div>
            </div>

            <!-- Pending Assignment Grading Card -->
            <div class="bg-white/90 backdrop-blur-md rounded-xl shadow-xl p-4 lg:p-5 border border-red-200/40 transform transition-all duration-300 hover:shadow-2xl hover:-translate-y-1 hover:scale-105 animate-fade-in-up" style="animation-delay: 0.5s;">
                <div class="flex items-center justify-between mb-3">
                    <div class="w-10 h-10 lg:w-12 lg:h-12 bg-gradient-to-br from-red-400 to-orange-400 rounded-lg flex items-center justify-center shadow-md transform rotate-3 hover:rotate-6 transition-transform">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 lg:h-6 lg:w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                    </div>
                    <div class="text-2xl lg:text-3xl opacity-20">📝</div>
                </div>
                <div class="text-red-600 text-[10px] lg:text-xs font-bold uppercase tracking-wider mb-1.5">Pending Grading</div>
                <div class="text-2xl lg:text-3xl font-black text-gray-800 mb-1">{{ $pendingGrading ?? 0 }}</div>
                <div class="text-[10px] lg:text-xs text-gray-600 font-medium">Awaiting review</div>
            </div>

            <!-- Average Grade Card -->
            <div class="bg-gradient-to-br from-pink-200/90 via-rose-200/80 to-cyan-200/90 backdrop-blur-md rounded-xl shadow-xl p-4 lg:p-5 border border-pink-200/60 transform transition-all duration-300 hover:shadow-2xl hover:-translate-y-1 hover:scale-105 animate-fade-in-up" style="animation-delay: 0.6s;">
                <div class="flex items-center justify-between mb-3">
                    <div class="w-10 h-10 lg:w-12 lg:h-12 bg-white/50 backdrop-blur-md rounded-lg flex items-center justify-center shadow-md transform -rotate-3 hover:-rotate-6 transition-transform border border-white/60">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 lg:h-6 lg:w-6 text-pink-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                        </svg>
                    </div>
                    <div class="text-2xl lg:text-3xl opacity-30">📈</div>
                </div>
                <div class="text-pink-400 text-[10px] lg:text-xs font-bold uppercase tracking-wider mb-1.5">Average Grade</div>
                <div class="text-2xl lg:text-3xl font-black bg-gradient-to-r from-pink-400 to-cyan-400 bg-clip-text text-transparent mb-1">{{ number_format($averageGrade, 1) }}%</div>
                <div class="text-[10px] lg:text-xs text-gray-700 font-medium">Average across all</div>
            </div>
        </div>

        <!-- Charts Grid Section -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
            <!-- Level Lessons Chart (Existing - Resized) -->
            <div class="bg-white/90 backdrop-blur-md rounded-2xl shadow-xl p-6 border border-pink-200/40 transform transition-all duration-300 hover:shadow-2xl animate-fade-in-up" style="animation-delay: 0.7s;">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-10 h-10 bg-gradient-to-br from-pink-300 to-cyan-300 rounded-lg flex items-center justify-center shadow-md">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-black text-gray-800">Lessons by Level</h3>
                </div>
                <div class="w-full" style="position: relative; height: 300px;">
                    <canvas id="levelLessonsChart"></canvas>
                </div>
            </div>

            <!-- Student Distribution Chart (Doughnut) -->
            @if(count($classNames) > 0)
            <div class="bg-white/90 backdrop-blur-md rounded-2xl shadow-xl p-6 border border-cyan-200/40 transform transition-all duration-300 hover:shadow-2xl animate-fade-in-up" style="animation-delay: 0.8s;">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-10 h-10 bg-gradient-to-br from-cyan-300 to-teal-300 rounded-lg flex items-center justify-center shadow-md">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-black text-gray-800">Student Distribution</h3>
                </div>
                <div id="studentDistributionChart" style="height: 300px;"></div>
            </div>
            @else
            <div class="bg-white/90 backdrop-blur-md rounded-2xl shadow-xl p-6 border border-cyan-200/40 flex items-center justify-center" style="height: 300px;">
                <div class="text-center">
                    <div class="text-6xl mb-4">📊</div>
                    <p class="text-gray-600 font-medium">No classes yet</p>
                </div>
            </div>
            @endif
        </div>

        <!-- Bottom Charts Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
            <!-- Assignments & Quizzes by Class (Bar Chart) -->
            @if(count($classNames) > 0)
            <div class="bg-white/90 backdrop-blur-md rounded-2xl shadow-xl p-6 border border-pink-200/40 transform transition-all duration-300 hover:shadow-2xl animate-fade-in-up" style="animation-delay: 0.9s;">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-10 h-10 bg-gradient-to-br from-pink-300 to-cyan-300 rounded-lg flex items-center justify-center shadow-md">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-black text-gray-800">Assignments & Quizzes by Class</h3>
                </div>
                <div id="assignmentsQuizzesChart" style="height: 300px;"></div>
            </div>
            @endif

            <!-- Upcoming Scheduled Activities (Next 3 Months) -->
            @if($totalClasses > 0)
            <div class="bg-white/90 backdrop-blur-md rounded-2xl shadow-xl p-6 border border-cyan-200/40 transform transition-all duration-300 hover:shadow-2xl animate-fade-in-up" style="animation-delay: 1s;">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-10 h-10 bg-gradient-to-br from-purple-200 to-pink-200 rounded-lg flex items-center justify-center shadow-md">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-black text-gray-800">Upcoming Activities (Next 6 Months)</h3>
                </div>
                <div id="activityOverTimeChart" style="height: 300px;"></div>
            </div>
            @else
            <div class="bg-white/90 backdrop-blur-md rounded-2xl shadow-xl p-6 border border-cyan-200/40 flex items-center justify-center" style="height: 300px;">
                <div class="text-center">
                    <div class="text-6xl mb-4">📅</div>
                    <p class="text-gray-600 font-medium">No upcoming activities yet</p>
                </div>
            </div>
            @endif
        </div>

        <!-- Student Risk Predictions Section -->
        @if(isset($taughtClasses) && $taughtClasses->count() > 0)
        @foreach($taughtClasses as $classItem)
        <div class="{{ $loop->last ? 'mb-0' : 'mb-8' }} animate-fade-in-up" style="animation-delay: {{ $loop->index * 0.1 + 1.1 }}s;">
            <div class="bg-white/90 backdrop-blur-md rounded-xl shadow-xl border border-pink-200/40 overflow-hidden transform transition-all duration-300 hover:shadow-2xl">
                <!-- Header -->
                <div class="bg-gradient-to-r from-pink-200 via-rose-200 to-cyan-200 p-3">
                    <div class="flex items-center justify-between flex-wrap gap-2">
                        <div class="flex items-center gap-2">
                            <div class="w-8 h-8 bg-white/20 backdrop-blur-md rounded-lg flex items-center justify-center shadow-md">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-base lg:text-lg font-black text-white">Student Risk Predictions</h3>
                                <p class="text-xs text-white/90">{{ $classItem->class_name }} ({{ $classItem->students->count() }} students)</p>
                            </div>
                        </div>
                        <button onclick="refreshPredictions({{ $classItem->class_id }})" class="bg-white/20 hover:bg-white/30 backdrop-blur-md px-3 py-1.5 rounded-lg text-xs font-semibold text-white transition-all flex items-center gap-1.5">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                            </svg>
                            Refresh
                        </button>
                    </div>
                </div>

                <!-- Content -->
                <div class="px-4 pt-4 pb-2 lg:px-6 lg:pt-6 lg:pb-3">
                    @php
                        // Get predictions for this specific class
                        $classPredictions = [];
                        $classErrors = [];
                        
                        if (isset($predictionsByClass[$classItem->class_id])) {
                            $classData = $predictionsByClass[$classItem->class_id];
                            $classPredictions = $classData['predictions'] ?? [];
                            $classErrors = $classData['errors'] ?? [];
                        } else {
                            // Fallback: filter all predictions by class_id
                            if (isset($predictions) && is_array($predictions)) {
                                $classPredictions = array_filter($predictions, function($pred) use ($classItem) {
                                    return isset($pred['class_id']) && $pred['class_id'] == $classItem->class_id;
                                });
                            }
                        }
                        
                        // Get all students for this class (including those without predictions)
                        $allClassStudents = $classItem->students;
                        $studentsWithPredictions = collect($classPredictions)->pluck('student_id')->toArray();
                        $studentsWithoutPredictions = $allClassStudents->filter(function($student) use ($studentsWithPredictions) {
                            return !in_array($student->student_id, $studentsWithPredictions);
                        });
                    @endphp
                    
                    @if(isset($apiAvailable) && !$apiAvailable && $loop->first)
                    <!-- API Not Available -->
                    <div class="text-center py-12">
                        <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-red-100 mb-4">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                        </div>
                        <h4 class="text-lg font-bold text-gray-800 mb-2">ML API Not Running</h4>
                        <p class="text-gray-600 mb-4">The Machine Learning API server is not running. Predictions cannot be generated until the API is started.</p>
                        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 text-left max-w-md mx-auto">
                            <p class="text-sm font-semibold text-yellow-800 mb-2">To start the API:</p>
                            <ol class="text-sm text-yellow-700 list-decimal list-inside space-y-1">
                                <li>Open a terminal/command prompt</li>
                                <li>Navigate to: <code class="bg-yellow-100 px-1 rounded">ml_api</code> folder</li>
                                <li>Run: <code class="bg-yellow-100 px-1 rounded">python app.py</code></li>
                                <li>Keep the terminal open</li>
                                <li>Refresh this page</li>
                            </ol>
                        </div>
                        @if(config('app.debug'))
                        <div class="mt-4 p-3 bg-gray-50 rounded-lg text-left text-xs max-w-md mx-auto">
                            <p class="font-semibold mb-1">Debug Info:</p>
                            <p>API URL: {{ config('services.ml_api.url') }}</p>
                            <p>Error: {{ $predictionErrors['api_unavailable'] ?? 'Connection failed' }}</p>
                        </div>
                        @endif
                    </div>
                    @elseif(count($classPredictions) > 0 || $studentsWithoutPredictions->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead>
                                <tr class="border-b-2 border-gray-200">
                                    <th class="text-left py-3 px-4 text-xs font-bold text-gray-700 uppercase tracking-wider">Student Name</th>
                                    <th class="text-left py-3 px-4 text-xs font-bold text-gray-700 uppercase tracking-wider">Risk Level</th>
                                    <th class="text-left py-3 px-4 text-xs font-bold text-gray-700 uppercase tracking-wider">Confidence</th>
                                    <th class="text-left py-3 px-4 text-xs font-bold text-gray-700 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                {{-- Show students with predictions --}}
                                @foreach($classPredictions as $pred)
                                <tr class="hover:bg-gray-50/50 transition-colors {{ $pred['risk_level'] == 2 ? 'bg-red-50/30' : ($pred['risk_level'] == 1 ? 'bg-yellow-50/30' : 'bg-green-50/30') }}">
                                    <td class="py-4 px-4">
                                        <div class="flex items-center gap-2">
                                            <div class="w-8 h-8 rounded-full bg-gradient-to-br {{ $pred['risk_level'] == 2 ? 'from-red-300 to-red-400' : ($pred['risk_level'] == 1 ? 'from-yellow-300 to-yellow-400' : 'from-green-300 to-green-400') }} flex items-center justify-center text-white text-xs font-bold">
                                                {{ strtoupper(substr($pred['student_name'] ?? 'N', 0, 1)) }}
                                            </div>
                                            <span class="text-sm font-semibold text-gray-800">{{ $pred['student_name'] ?? 'Unknown Student' }}</span>
                                        </div>
                                    </td>
                                    <td class="py-4 px-4">
                                        @if($pred['risk_level'] == 0)
                                            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-bold bg-green-100 text-green-700 border border-green-200">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                                </svg>
                                                Will Pass
                                            </span>
                                        @elseif($pred['risk_level'] == 1)
                                            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-bold bg-yellow-100 text-yellow-700 border border-yellow-200">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                                </svg>
                                                May Struggle
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-bold bg-red-100 text-red-700 border border-red-200">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                                </svg>
                                                Needs Help
                                            </span>
                                        @endif
                                    </td>
                                    <td class="py-4 px-4">
                                        <div class="flex items-center gap-2">
                                            <div class="flex-1 bg-gray-200 rounded-full h-2 overflow-hidden">
                                                <div class="h-full rounded-full transition-all duration-500 {{ $pred['risk_level'] == 2 ? 'bg-red-500' : ($pred['risk_level'] == 1 ? 'bg-yellow-500' : 'bg-green-500') }}" style="width: {{ number_format($pred['confidence'] * 100, 1) }}%"></div>
                                            </div>
                                            <span class="text-sm font-semibold text-gray-700 min-w-[3rem] text-right">{{ number_format($pred['confidence'] * 100, 1) }}%</span>
                                        </div>
                                    </td>
                                    <td class="py-4 px-4">
                                        @if($pred['risk_level'] >= 1)
                                            <a href="{{ route('teacher.progress') }}?student_id={{ $pred['student_id'] }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-purple-500 hover:bg-purple-600 text-white text-xs font-semibold rounded-lg transition-colors shadow-sm hover:shadow-md">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                </svg>
                                                View Progress
                                            </a>
                                        @else
                                            <span class="text-xs text-gray-400 italic">No action needed</span>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                                
                                {{-- Show students without predictions (insufficient data) --}}
                                @foreach($studentsWithoutPredictions as $student)
                                @php
                                    $studentName = 'Unknown';
                                    if ($student->user) {
                                        $studentName = trim(($student->user->first_name ?? '') . ' ' . ($student->user->last_name ?? ''));
                                        if (empty($studentName)) {
                                            $studentName = $student->user->email ?? 'Unknown';
                                        }
                                    }
                                @endphp
                                <tr class="hover:bg-gray-50/50 transition-colors bg-gray-50/30">
                                    <td class="py-4 px-4">
                                        <div class="flex items-center gap-2">
                                            <div class="w-8 h-8 rounded-full bg-gradient-to-br from-gray-300 to-gray-400 flex items-center justify-center text-white text-xs font-bold">
                                                {{ strtoupper(substr($studentName, 0, 1)) }}
                                            </div>
                                            <span class="text-sm font-semibold text-gray-800">{{ $studentName }}</span>
                                        </div>
                                    </td>
                                    <td class="py-4 px-4">
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-bold bg-gray-100 text-gray-600 border border-gray-200">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                            Insufficient Data
                                        </span>
                                    </td>
                                    <td class="py-4 px-4">
                                        <span class="text-sm text-gray-400 italic">N/A</span>
                                    </td>
                                    <td class="py-4 px-4">
                                        <span class="text-xs text-gray-400 italic">Student needs to complete lessons</span>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @else
                    <div class="text-center py-12">
                        <div class="w-16 h-16 mx-auto mb-4 bg-gray-100 rounded-full flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                        </div>
                        <p class="text-gray-600 font-medium mb-2">No predictions available for {{ $classItem->class_name }}</p>
                        <p class="text-sm text-gray-500 mb-4">Students may not have enough data yet. Predictions are generated automatically when students complete lessons or quizzes.</p>
                        
                        <!-- Debug info (only in debug mode) -->
                        @if(config('app.debug'))
                        <div class="mt-4 p-3 bg-yellow-50 rounded-lg text-left text-xs max-w-md mx-auto">
                            <p class="font-semibold mb-2 text-yellow-800">Debug Information:</p>
                            <ul class="space-y-1 text-yellow-700">
                                <li>Class ID: {{ $classItem->class_id ?? 'N/A' }}</li>
                                <li>Class Name: {{ $classItem->class_name ?? 'N/A' }}</li>
                                <li>Students in class: {{ $classItem->students->count() ?? 0 }}</li>
                                <li>Predictions: {{ count($classPredictions) }}</li>
                                <li>Students without data: {{ $studentsWithoutPredictions->count() }}</li>
                                <li>ML API URL: {{ config('services.ml_api.url', 'Not configured') }}</li>
                                <li>API Available: {{ isset($apiAvailable) ? ($apiAvailable ? 'Yes' : 'No') : 'Unknown' }}</li>
                            </ul>
                            <p class="mt-2 text-yellow-600 text-xs">Check Laravel logs for detailed error messages.</p>
                        </div>
                        @endif
                    </div>
                    @endif
                </div>
            </div>
        </div>
        @endforeach
        @else
        <!-- No class assigned message -->
        <div class="mb-0 animate-fade-in-up">
            <div class="bg-white/90 backdrop-blur-md rounded-xl shadow-xl border border-purple-200/40 p-6 text-center">
                <div class="w-16 h-16 mx-auto mb-4 bg-gray-100 rounded-full flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                    </svg>
                </div>
                <p class="text-gray-600 font-medium">You are not assigned to any class yet.</p>
                <p class="text-sm text-gray-500 mt-2">Contact an administrator to be assigned to a class.</p>
            </div>
        </div>
        @endif
    </div>
</div>
</div>

@push('styles')
<style>
    @keyframes fade-in {
        from {
            opacity: 0;
        }
        to {
            opacity: 1;
        }
    }
    
    @keyframes fade-in-up {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    @keyframes slide-in-left {
        from {
            opacity: 0;
            transform: translateX(-20px);
        }
        to {
            opacity: 1;
            transform: translateX(0);
        }
    }
    
    @keyframes slide-in-right {
        from {
            opacity: 0;
            transform: translateX(20px);
        }
        to {
            opacity: 1;
            transform: translateX(0);
        }
    }
    
    .animate-fade-in {
        animation: fade-in 0.6s ease-out;
    }
    
    .animate-fade-in-up {
        animation: fade-in-up 0.6s ease-out forwards;
        opacity: 0;
    }
    
    .animate-slide-in-left {
        animation: slide-in-left 0.6s ease-out forwards;
    }
    
    .animate-slide-in-right {
        animation: slide-in-right 0.6s ease-out forwards;
    }
    
    /* Schedule scrollbar styling */
    .max-h-48::-webkit-scrollbar {
        width: 4px;
    }
    
    .max-h-48::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 10px;
    }
    
    .max-h-48::-webkit-scrollbar-thumb {
        background: linear-gradient(to bottom, #F472B6, #06B6D4);
        border-radius: 10px;
    }
    
    .max-h-48::-webkit-scrollbar-thumb:hover {
        background: linear-gradient(to bottom, #EC4899, #0891B2);
    }
    
    /* Custom scrollbar for tooltip */
    .custom-scrollbar::-webkit-scrollbar {
        width: 6px;
    }
    
    .custom-scrollbar::-webkit-scrollbar-track {
        background: rgba(255, 255, 255, 0.1);
        border-radius: 10px;
    }
    
    .custom-scrollbar::-webkit-scrollbar-thumb {
        background: rgba(255, 255, 255, 0.3);
        border-radius: 10px;
    }
    
    .custom-scrollbar::-webkit-scrollbar-thumb:hover {
        background: rgba(255, 255, 255, 0.5);
    }
    
</style>
@endpush

@push('scripts')
<!-- Chart.js is now loaded via Vite (resources/js/app.js) -->
<!-- ApexCharts for additional charts -->
<script src="https://unpkg.com/apexcharts@3.44.0/dist/apexcharts.min.js" crossorigin="anonymous"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Existing Level Lessons Chart (Resized)
    const levelCtx = document.getElementById('levelLessonsChart');
    if (levelCtx) {
        const levelNames = @json($levelNames);
        const lessonCounts = @json($lessonCounts);
        
        new Chart(levelCtx, {
            type: 'line',
            data: {
                labels: levelNames,
                datasets: [{
                    label: 'Number of Lessons',
                    data: lessonCounts,
                    borderColor: '#6EC6C5',
                    backgroundColor: 'rgba(110, 198, 197, 0.2)',
                    borderWidth: 2,
                    fill: true,
                    tension: 0.4,
                    pointRadius: 5,
                    pointBackgroundColor: '#6EC6C5',
                    pointBorderColor: '#ffffff',
                    pointBorderWidth: 2,
                    pointHoverRadius: 7,
                    pointHoverBackgroundColor: '#197D8C',
                    pointHoverBorderColor: '#ffffff',
                    pointHoverBorderWidth: 2
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                animation: {
                    duration: 1500,
                    easing: 'easeInOutQuart'
                },
                plugins: {
                    legend: {
                        display: true,
                        position: 'top',
                        labels: {
                            font: {
                                size: 12
                            }
                        }
                    },
                    tooltip: {
                        backgroundColor: 'rgba(0, 0, 0, 0.8)',
                        padding: 12,
                        titleFont: {
                            size: 14,
                            weight: 'bold'
                        },
                        bodyFont: {
                            size: 13
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Number of Lessons',
                            font: {
                                size: 14,
                                weight: 'bold'
                            }
                        },
                        ticks: {
                            stepSize: 1,
                            precision: 0
                        },
                        grid: {
                            color: 'rgba(0, 0, 0, 0.05)'
                        }
                    },
                    x: {
                        title: {
                            display: true,
                            text: 'Level',
                            font: {
                                size: 14,
                                weight: 'bold'
                            }
                        },
                        grid: {
                            display: false
                        },
                        ticks: {
                            maxRotation: 45,
                            minRotation: 45,
                            autoSkip: false
                        }
                    }
                }
            }
        });
    }

    // Student Distribution Chart (Doughnut)
    @if(count($classNames) > 0)
    const classNames = @json($classNames);
    const studentCounts = @json($studentCountsByClass);
    
    const studentDistributionOptions = {
        series: studentCounts,
        chart: {
            type: 'donut',
            height: 300,
            animations: {
                enabled: true,
                easing: 'easeinout',
                speed: 800
            }
        },
        labels: classNames,
        colors: ['#F472B6', '#6EC6C5', '#EC769A', '#79BDBC', '#FBCFDD', '#B5D7D5', '#FFB9C6', '#67E8F9'],
        dataLabels: {
            enabled: true,
            formatter: function (val) {
                return val.toFixed(1) + "%"
            },
            style: {
                fontSize: '12px',
                fontWeight: 'bold'
            }
        },
        legend: {
            position: 'bottom',
            fontSize: '12px',
            fontFamily: 'inherit'
        },
        tooltip: {
            y: {
                formatter: function (val) {
                    return val + " students"
                }
            }
        },
        plotOptions: {
            pie: {
                donut: {
                    size: '65%',
                    labels: {
                        show: true,
                        name: {
                            show: true,
                            fontSize: '14px',
                            fontWeight: 'bold'
                        },
                        value: {
                            show: true,
                            fontSize: '16px',
                            fontWeight: 'bold',
                            formatter: function (val) {
                                return val
                            }
                        },
                        total: {
                            show: true,
                            label: 'Total Students',
                            fontSize: '14px',
                            fontWeight: 'bold',
                            formatter: function (w) {
                                return w.globals.seriesTotals.reduce((a, b) => a + b, 0)
                            }
                        }
                    }
                }
            }
        }
    };
    
    const studentDistributionChart = new ApexCharts(document.querySelector("#studentDistributionChart"), studentDistributionOptions);
    studentDistributionChart.render();
    @endif

    // Assignments & Quizzes by Class (Bar Chart)
    @if(count($classNames) > 0)
    const assignmentsData = @json($assignmentsByClass);
    const quizzesData = @json($quizzesByClass);
    
    const assignmentsQuizzesOptions = {
        series: [
            {
                name: 'Assignments',
                data: assignmentsData
            },
            {
                name: 'Quizzes',
                data: quizzesData
            }
        ],
        chart: {
            type: 'bar',
            height: 300,
            stacked: false,
            animations: {
                enabled: true,
                easing: 'easeinout',
                speed: 800
            },
            toolbar: {
                show: false
            }
        },
        plotOptions: {
            bar: {
                horizontal: false,
                columnWidth: '55%',
                borderRadius: 8,
                dataLabels: {
                    position: 'top'
                }
            }
        },
        dataLabels: {
            enabled: true,
            formatter: function (val) {
                return val
            },
            offsetY: -20,
            style: {
                fontSize: '12px',
                colors: ["#374151"]
            }
        },
        stroke: {
            show: true,
            width: 2,
            colors: ['transparent']
        },
        xaxis: {
            categories: classNames,
            labels: {
                rotate: -45,
                rotateAlways: true,
                style: {
                    fontSize: '11px'
                }
            }
        },
        yaxis: {
            title: {
                text: 'Count'
            }
        },
        fill: {
            opacity: 1,
            type: 'gradient',
            gradient: {
                shade: 'light',
                type: 'vertical',
                shadeIntensity: 0.5,
                gradientToColors: ['#F472B6', '#6EC6C5'],
                inverseColors: false,
                opacityFrom: 1,
                opacityTo: 1,
                stops: [0, 100]
            }
        },
        colors: ['#EC769A', '#79BDBC'],
        legend: {
            position: 'top',
            horizontalAlign: 'right'
        },
        tooltip: {
            y: {
                formatter: function (val) {
                    return val
                }
            }
        }
    };
    
    const assignmentsQuizzesChart = new ApexCharts(document.querySelector("#assignmentsQuizzesChart"), assignmentsQuizzesOptions);
    assignmentsQuizzesChart.render();
    @endif

    // Upcoming Scheduled Activities (Next 3 Months)
    @if($totalClasses > 0)
    const activityLabels = @json($activityOverTimeLabels ?? []);
    const upcomingAssignmentsData = @json($upcomingAssignments ?? []);
    const upcomingQuizzesData = @json($upcomingQuizzes ?? []);
    
    const activityOverTimeOptions = {
        series: [{
            name: 'Assignments Due',
            data: upcomingAssignmentsData
        }, {
            name: 'Quizzes Available',
            data: upcomingQuizzesData
        }],
        chart: {
            type: 'line',
            height: 300,
            zoom: {
                enabled: false
            },
            animations: {
                enabled: true,
                easing: 'easeinout',
                speed: 800
            },
            toolbar: {
                show: false
            }
        },
        dataLabels: {
            enabled: true,
            style: {
                fontSize: '11px',
                fontWeight: 'bold'
            }
        },
        stroke: {
            curve: 'smooth',
            width: 3
        },
        colors: ['#EC4899', '#06B6D4'],
        xaxis: {
            categories: activityLabels,
            title: {
                text: 'Upcoming Months'
            },
            labels: {
                style: {
                    fontSize: '10px'
                },
                rotate: -45,
                rotateAlways: true
            }
        },
        yaxis: {
            title: {
                text: 'Number of Activities'
            },
            labels: {
                formatter: function (val) {
                    return Math.floor(val)
                }
            }
        },
        tooltip: {
            shared: true,
            intersect: false,
            y: {
                formatter: function (val) {
                    return val + " items"
                }
            }
        },
        legend: {
            position: 'top',
            horizontalAlign: 'right',
            floating: false,
            fontSize: '12px',
            offsetY: -5
        },
        markers: {
            size: 6,
            hover: {
                size: 8
            }
        },
        grid: {
            borderColor: '#e7e7e7',
            strokeDashArray: 3
        }
    };
    
    const activityOverTimeChart = new ApexCharts(document.querySelector("#activityOverTimeChart"), activityOverTimeOptions);
    activityOverTimeChart.render();
    @endif

    // Student Risk Predictions Section - Refresh predictions functionality
    function refreshPredictions(classId) {
        fetch(`/teacher/class/${classId}/refresh-predictions`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload(); // Reload to show updated predictions
            } else {
                alert('Failed to refresh predictions: ' + (data.message || 'Unknown error'));
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error refreshing predictions');
        });
    }
});
</script>
@endpush
