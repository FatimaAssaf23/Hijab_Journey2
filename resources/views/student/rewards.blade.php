@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-pink-50 via-cyan-50/30 to-teal-50/20 relative overflow-hidden">
    <!-- Enhanced Animated Background Elements -->
    <div class="absolute inset-0 overflow-hidden pointer-events-none">
        <div class="absolute -top-40 -left-40 w-[500px] h-[500px] bg-pink-200/40 rounded-full opacity-20 blur-3xl animate-pulse"></div>
        <div class="absolute top-1/2 -right-40 w-[500px] h-[500px] bg-cyan-200/40 rounded-full opacity-20 blur-3xl animate-pulse" style="animation-delay: 1.5s;"></div>
        <div class="absolute -bottom-40 left-1/3 w-[500px] h-[500px] bg-teal-200/40 rounded-full opacity-20 blur-3xl animate-pulse" style="animation-delay: 3s;"></div>
    </div>

    <!-- Floating Particles -->
    <div class="absolute inset-0 overflow-hidden pointer-events-none" id="particles">
        @for($i = 0; $i < 30; $i++)
        <div class="particle" style="
            left: {{ rand(0, 100) }}%;
            top: {{ rand(0, 100) }}%;
            animation-delay: {{ rand(0, 5) }}s;
            animation-duration: {{ rand(10, 20) }}s;
        "></div>
        @endfor
    </div>

    <!-- Floating decorative shapes with more movement -->
    <div class="absolute top-20 right-20 w-32 h-32 bg-pink-200/30 rounded-full blur-3xl animate-float" style="animation-duration: 6s;"></div>
    <div class="absolute bottom-20 left-20 w-40 h-40 bg-cyan-200/30 rounded-full blur-3xl animate-float" style="animation-duration: 8s; animation-delay: 1s;"></div>
    <div class="absolute top-1/2 left-1/4 w-28 h-28 bg-teal-200/30 rounded-full blur-2xl animate-float" style="animation-duration: 7s; animation-delay: 2s;"></div>

    <div class="relative z-10 w-full max-w-full mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Enhanced Header Section with Animation -->
        <div class="text-center mb-12 hero-entrance">
            <div class="inline-flex items-center gap-3 bg-white/70 backdrop-blur-md px-8 py-4 rounded-full mb-6 border-2 border-pink-300/60 shadow-2xl transform hover:scale-105 transition-all duration-300 relative overflow-hidden group">
                <!-- Animated shimmer effect -->
                <div class="absolute inset-0 -top-2 bg-gradient-to-r from-transparent via-white/30 to-transparent transform translate-x-[-100%] group-hover:translate-x-[100%] transition-transform duration-1000"></div>
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-pink-600 animate-spin-slow" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z" />
                </svg>
                <span class="text-pink-700 font-black text-2xl tracking-wide relative z-10">REWARDS & LEADERBOARD</span>
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-cyan-600 animate-spin-slow-reverse" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z" />
                </svg>
            </div>
            <h1 class="text-5xl lg:text-6xl font-black mb-4 bg-gradient-to-r from-pink-600 via-cyan-600 to-teal-600 bg-clip-text text-transparent animate-gradient-text">
                Celebrate Excellence! 🏆
            </h1>
            <p class="text-gray-700 text-xl font-semibold animate-fade-in">See who's shining the brightest today and this week!</p>
        </div>

        <!-- Current Student Status Banner with Enhanced Effects -->
        @if($isTopOfDay)
        <div class="mb-12 celebration-banner">
            <div class="bg-gradient-to-r from-yellow-400 via-yellow-300 to-yellow-400 rounded-3xl shadow-2xl p-8 border-4 border-yellow-500 transform hover:scale-[1.02] transition-all duration-500 relative overflow-hidden group">
                <!-- Animated background pattern -->
                <div class="absolute inset-0 opacity-20">
                    <div class="absolute top-0 left-0 w-full h-full" style="background-image: radial-gradient(circle, rgba(255,255,255,0.4) 1px, transparent 1px); background-size: 30px 30px; animation: pattern-move 20s linear infinite;"></div>
                </div>
                
                <!-- Confetti effect -->
                <div class="confetti-container">
                    @for($i = 0; $i < 20; $i++)
                    <div class="confetti" style="
                        left: {{ rand(0, 100) }}%;
                        animation-delay: {{ rand(0, 2) }}s;
                        background-color: hsl({{ rand(0, 360) }}, 70%, 60%);
                    "></div>
                    @endfor
                </div>
                
                <div class="relative z-10 text-center">
                    <div class="flex justify-center mb-4">
                        <div class="relative crown-container">
                            <div class="absolute inset-0 bg-yellow-300 rounded-full blur-2xl animate-ping-slow"></div>
                            <div class="absolute inset-0 bg-yellow-400 rounded-full blur-xl animate-pulse"></div>
                            <div class="relative text-8xl animate-bounce-rotate">👑</div>
                        </div>
                    </div>
                    <h2 class="text-4xl lg:text-5xl font-black text-yellow-900 mb-3 animate-text-glow">
                        🎉 CONGRATULATIONS! 🎉
                    </h2>
                    <p class="text-2xl lg:text-3xl font-bold text-yellow-800 mb-2">
                        You are the <span class="text-yellow-900 text-4xl animate-pulse-inline">TOP STUDENT OF THE DAY!</span>
                    </p>
                    <p class="text-xl text-yellow-700 font-semibold">
                        Keep up the amazing work! You're an inspiration to everyone! ✨
                    </p>
                    <div class="mt-6 flex justify-center gap-4">
                        <span class="text-5xl animate-bounce-star" style="animation-delay: 0.1s;">🌟</span>
                        <span class="text-5xl animate-bounce-star" style="animation-delay: 0.3s;">⭐</span>
                        <span class="text-5xl animate-bounce-star" style="animation-delay: 0.5s;">🌟</span>
                    </div>
                </div>
            </div>
        </div>
        @endif

        <!-- Top of the Day Section with 3D Card Effect -->
        <div class="mb-12 card-entrance" style="animation-delay: 0.2s;">
            <div class="card-3d bg-white/90 backdrop-blur-md rounded-3xl shadow-2xl p-8 border border-pink-200/60 transform transition-all duration-500 hover:shadow-3xl">
                <div class="flex items-center justify-between mb-8">
                    <div class="flex items-center gap-4">
                        <div class="icon-bounce w-20 h-20 bg-gradient-to-br from-yellow-400 to-yellow-500 rounded-2xl flex items-center justify-center shadow-xl transform rotate-3 hover:rotate-6 transition-transform relative">
                            <div class="absolute inset-0 bg-yellow-400 rounded-2xl blur-lg opacity-50 animate-pulse"></div>
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-yellow-900 relative z-10" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z" />
                            </svg>
                        </div>
                        <div>
                            <h2 class="text-4xl font-black text-gray-800">Top of the Day</h2>
                            <p class="text-gray-600 font-semibold text-lg">{{ \Carbon\Carbon::now()->format('l, F j, Y') }}</p>
                        </div>
                    </div>
                    <div class="text-7xl animate-trophy-spin">🏆</div>
                </div>

                @if($topOfDay)
                <div class="champion-card bg-gradient-to-br from-yellow-50 via-amber-50 to-yellow-100 rounded-3xl p-8 border-4 border-yellow-300 shadow-xl transform transition-all duration-500 hover:scale-[1.02] relative overflow-hidden group">
                    <!-- Animated celebration background -->
                    <div class="absolute inset-0 opacity-10 celebration-pattern">
                        <div class="absolute top-4 left-4 text-5xl animate-float-celebration" style="animation-delay: 0s;">🎊</div>
                        <div class="absolute top-8 right-8 text-6xl animate-float-celebration" style="animation-delay: 0.5s;">🎉</div>
                        <div class="absolute bottom-6 left-8 text-5xl animate-float-celebration" style="animation-delay: 1s;">✨</div>
                        <div class="absolute bottom-8 right-6 text-6xl animate-float-celebration" style="animation-delay: 1.5s;">🌟</div>
                    </div>

                    <div class="relative z-10 flex flex-col lg:flex-row items-center gap-8">
                        <!-- Profile Picture with Rotating Glow -->
                        <div class="relative profile-picture-container">
                            <div class="absolute inset-0 bg-yellow-400 rounded-full blur-2xl opacity-50 animate-pulse-ring"></div>
                            <div class="absolute inset-0 bg-yellow-300 rounded-full blur-xl opacity-30 animate-spin-slow"></div>
                            <div class="relative w-40 h-40 rounded-full overflow-hidden border-4 border-yellow-400 shadow-2xl transform hover:scale-110 hover:rotate-6 transition-all duration-500 group-hover:border-yellow-500">
                                <img src="{{ $topOfDay->user?->profile_photo_url ?? asset('storage/grade-page-design/hijab7.jpg') }}" 
                                     alt="{{ $topOfDay->user?->first_name ?? 'Student' }} {{ $topOfDay->user?->last_name ?? '' }}"
                                     class="w-full h-full object-cover transform group-hover:scale-110 transition-transform duration-500">
                            </div>
                            <div class="absolute -top-4 -right-4 text-6xl animate-crown-bounce">👑</div>
                            <!-- Rotating stars around profile -->
                            <div class="absolute -top-2 left-1/2 text-2xl animate-spin-slow transform -translate-x-1/2">⭐</div>
                            <div class="absolute -bottom-2 left-1/2 text-2xl animate-spin-slow-reverse transform -translate-x-1/2" style="animation-delay: 1s;">✨</div>
                        </div>

                        <!-- Student Info -->
                        <div class="flex-1 text-center lg:text-left">
                            <div class="inline-block bg-yellow-400 text-yellow-900 px-6 py-3 rounded-full text-sm font-black mb-4 uppercase tracking-wide shadow-lg transform hover:scale-110 transition-transform duration-300 animate-badge-pulse">
                                #1 Champion
                            </div>
                            <h3 class="text-4xl lg:text-5xl font-black text-gray-800 mb-4 animate-text-reveal">
                                {{ $topOfDay->user?->first_name ?? 'Unknown' }} {{ $topOfDay->user?->last_name ?? 'Student' }}
                            </h3>
                            <div class="flex items-center justify-center lg:justify-start gap-4 mb-6">
                                <div class="score-card bg-white/90 backdrop-blur-sm px-6 py-4 rounded-2xl shadow-xl transform hover:scale-105 transition-all duration-300 border-2 border-yellow-300 group-hover:border-yellow-400">
                                    <div class="text-xs font-bold text-gray-600 uppercase tracking-wider mb-2">Today's Performance</div>
                                    <div class="text-3xl font-black text-yellow-700 animate-number-glow">{{ number_format($topOfDay->performance_score ?? 0, 1) }} <span class="text-lg">pts</span></div>
                                    <div class="text-lg font-bold text-yellow-600 mt-1">({{ number_format($topOfDay->percentage ?? 0, 1) }}%)</div>
                                </div>
                            </div>
                            <p class="text-xl font-bold text-gray-700 leading-relaxed">
                                🎊 Congratulations on being today's top performer! Your dedication and hard work are truly inspiring! 🎊
                            </p>
                        </div>
                    </div>
                </div>
                @else
                <div class="text-center py-16 bg-gradient-to-br from-gray-50 to-gray-100 rounded-2xl animate-pulse">
                    <div class="text-7xl mb-6 animate-bounce">📊</div>
                    <p class="text-gray-600 text-xl font-semibold mb-2">No top student yet today!</p>
                    <p class="text-gray-500">Be the first to earn points and claim the crown! 👑</p>
                </div>
                @endif
            </div>
        </div>

        <!-- Top of the Week Section with Enhanced Cards -->
        <div class="mb-8 card-entrance" style="animation-delay: 0.4s;">
            <div class="bg-white/90 backdrop-blur-md rounded-3xl shadow-2xl p-8 border border-cyan-200/60 transform transition-all duration-500 hover:shadow-3xl">
                <div class="flex items-center justify-between mb-8">
                    <div class="flex items-center gap-4">
                        <div class="icon-bounce w-20 h-20 bg-gradient-to-br from-cyan-400 to-teal-400 rounded-2xl flex items-center justify-center shadow-xl transform -rotate-3 hover:rotate-0 transition-transform relative">
                            <div class="absolute inset-0 bg-cyan-400 rounded-2xl blur-lg opacity-50 animate-pulse"></div>
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-white relative z-10" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                            </svg>
                        </div>
                        <div>
                            <h2 class="text-4xl font-black text-gray-800">Top of the Week</h2>
                            <p class="text-gray-600 font-semibold text-lg">Week of {{ \Carbon\Carbon::now()->startOfWeek()->format('M j') }} - {{ \Carbon\Carbon::now()->endOfWeek()->format('M j, Y') }}</p>
                        </div>
                    </div>
                    <div class="text-7xl animate-star-pulse">⭐</div>
                </div>

                @if($topOfWeek->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    @foreach($topOfWeek as $index => $student)
                    @php
                        $positions = ['1st', '2nd', '3rd'];
                        $position = $positions[$index];
                        $colors = [
                            ['bg' => 'from-yellow-100 to-amber-100', 'border' => 'border-yellow-400', 'badge' => 'bg-yellow-500', 'icon' => '👑', 'medal' => '🥇'],
                            ['bg' => 'from-gray-100 to-slate-100', 'border' => 'border-gray-400', 'badge' => 'bg-gray-500', 'icon' => '🥈', 'medal' => '🥈'],
                            ['bg' => 'from-orange-100 to-amber-100', 'border' => 'border-orange-400', 'badge' => 'bg-orange-500', 'icon' => '🥉', 'medal' => '🥉'],
                        ];
                        $colorScheme = $colors[$index];
                        $isCurrentStudent = $currentStudent && $student->student_id === $currentStudent->student_id;
                    @endphp
                    <div class="relative group card-entrance" style="animation-delay: {{ 0.6 + ($index * 0.15) }}s;">
                        <div class="rank-card bg-gradient-to-br {{ $colorScheme['bg'] }} rounded-3xl p-8 border-4 {{ $colorScheme['border'] }} shadow-2xl transform transition-all duration-500 hover:scale-110 hover:rotate-1 hover:shadow-3xl relative overflow-hidden">
                            <!-- Animated background with floating elements -->
                            <div class="absolute inset-0 opacity-15 celebration-pattern">
                                <div class="absolute top-2 left-2 text-4xl animate-float-celebration">{{ $colorScheme['medal'] }}</div>
                                <div class="absolute bottom-2 right-2 text-5xl animate-float-celebration" style="animation-delay: 0.7s;">✨</div>
                            </div>

                            <!-- Shimmer effect on hover -->
                            <div class="absolute inset-0 bg-gradient-to-r from-transparent via-white/20 to-transparent transform translate-x-[-100%] group-hover:translate-x-[100%] transition-transform duration-1000 opacity-0 group-hover:opacity-100"></div>

                            <div class="relative z-10">
                                <!-- Position Badge with rotation -->
                                <div class="absolute -top-6 -right-6 w-20 h-20 {{ $colorScheme['badge'] }} rounded-full flex items-center justify-center shadow-2xl transform rotate-12 group-hover:rotate-0 group-hover:scale-110 transition-all duration-500 border-4 border-white animate-badge-float">
                                    <span class="text-white text-3xl font-black">{{ $position }}</span>
                                </div>

                                <!-- Profile Picture with enhanced effects -->
                                <div class="flex justify-center mb-6">
                                    <div class="relative">
                                        <div class="absolute inset-0 {{ $colorScheme['border'] }} rounded-full blur-2xl opacity-50 animate-pulse-ring"></div>
                                        <div class="relative w-28 h-28 rounded-full overflow-hidden border-4 {{ $colorScheme['border'] }} shadow-2xl transform group-hover:scale-125 group-hover:rotate-6 transition-all duration-500">
                                            <img src="{{ $student->user?->profile_photo_url ?? asset('storage/grade-page-design/hijab7.jpg') }}" 
                                                 alt="{{ $student->user?->first_name ?? 'Student' }} {{ $student->user?->last_name ?? '' }}"
                                                 class="w-full h-full object-cover transform group-hover:scale-110 transition-transform duration-500">
                                        </div>
                                        @if($index === 0)
                                        <div class="absolute -top-3 -right-3 text-4xl animate-crown-bounce">👑</div>
                                        @endif
                                        <!-- Rotating decorative elements -->
                                        <div class="absolute -top-1 left-1/2 text-xl animate-spin-slow transform -translate-x-1/2">⭐</div>
                                    </div>
                                </div>

                                <!-- Student Info -->
                                <div class="text-center">
                                    <h3 class="text-2xl font-black text-gray-800 mb-3 leading-tight">
                                        {{ $student->user?->first_name ?? 'Unknown' }} {{ $student->user?->last_name ?? 'Student' }}
                                    </h3>
                                    <div class="bg-white/90 backdrop-blur-sm px-5 py-4 rounded-2xl shadow-lg mb-4 transform group-hover:scale-105 transition-transform duration-300 border-2 border-transparent group-hover:border-white/50">
                                        <div class="text-xs font-bold text-gray-600 uppercase tracking-wider mb-2">Weekly Performance</div>
                                        <div class="text-3xl font-black {{ $index === 0 ? 'text-yellow-700' : ($index === 1 ? 'text-gray-700' : 'text-orange-700') }} animate-number-glow">
                                            {{ number_format($student->performance_score ?? 0, 1) }} <span class="text-sm">pts</span>
                                        </div>
                                        <div class="text-base font-bold {{ $index === 0 ? 'text-yellow-600' : ($index === 1 ? 'text-gray-600' : 'text-orange-600') }} mt-1">
                                            ({{ number_format($student->percentage ?? 0, 1) }}%)
                                        </div>
                                    </div>
                                    
                                    @if($isCurrentStudent)
                                    <div class="bg-gradient-to-r from-pink-400 via-cyan-400 to-pink-400 text-white px-5 py-2 rounded-full text-sm font-black uppercase tracking-wide shadow-xl animate-pulse-glow mb-3 transform hover:scale-105 transition-transform">
                                        That's You! 🎉
                                    </div>
                                    @endif

                                    <p class="text-sm font-bold text-gray-700 mt-4 leading-relaxed">
                                        @if($index === 0)
                                        🎊 Outstanding achievement! 🎊
                                        @elseif($index === 1)
                                        🌟 Excellent work! 🌟
                                        @else
                                        ✨ Great job! ✨
                                        @endif
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>

                <!-- Enhanced Congratulations Message -->
                <div class="mt-10 bg-gradient-to-r from-pink-200 via-cyan-200 to-teal-200 rounded-3xl p-8 border-2 border-pink-300/60 shadow-2xl relative overflow-hidden group">
                    <div class="absolute inset-0 bg-gradient-to-r from-transparent via-white/30 to-transparent transform translate-x-[-100%] group-hover:translate-x-[100%] transition-transform duration-2000"></div>
                    <div class="relative z-10 text-center">
                        <h3 class="text-3xl font-black text-gray-800 mb-4 flex items-center justify-center gap-4">
                            <span class="text-5xl animate-bounce-star">🎉</span>
                            <span>Congratulations to All Top Performers!</span>
                            <span class="text-5xl animate-bounce-star" style="animation-delay: 0.5s;">🎉</span>
                        </h3>
                        <p class="text-xl font-semibold text-gray-700 leading-relaxed">
                            Your dedication, hard work, and passion for learning shine brightly! Keep striving for excellence!
                        </p>
                        <div class="flex justify-center gap-3 mt-6">
                            <span class="text-4xl animate-bounce-star" style="animation-delay: 0.1s;">🌟</span>
                            <span class="text-4xl animate-bounce-star" style="animation-delay: 0.3s;">⭐</span>
                            <span class="text-4xl animate-bounce-star" style="animation-delay: 0.5s;">💫</span>
                            <span class="text-4xl animate-bounce-star" style="animation-delay: 0.7s;">✨</span>
                        </div>
                    </div>
                </div>
                @else
                <div class="text-center py-16 bg-gradient-to-br from-gray-50 to-gray-100 rounded-2xl animate-pulse">
                    <div class="text-7xl mb-6 animate-bounce">📊</div>
                    <p class="text-gray-600 text-xl font-semibold mb-2">No top students yet this week!</p>
                    <p class="text-gray-500">Start earning points and climb the leaderboard! 🚀</p>
                </div>
                @endif
            </div>
        </div>

        <!-- Current Student's Status Card with Enhanced Design -->
        @if($currentStudent && !$isTopOfDay)
        <div class="mt-8 card-entrance" style="animation-delay: 0.8s;">
            <div class="bg-gradient-to-br from-pink-100 via-cyan-100 to-teal-100 rounded-3xl p-8 border-2 border-pink-300/60 shadow-2xl transform hover:scale-[1.02] transition-all duration-500 relative overflow-hidden group">
                <div class="absolute inset-0 bg-gradient-to-r from-transparent via-white/20 to-transparent transform translate-x-[-100%] group-hover:translate-x-[100%] transition-transform duration-1500"></div>
                <div class="relative z-10 grid grid-cols-1 md:grid-cols-3 gap-6 items-center">
                    <div class="flex items-center gap-5">
                        <div class="w-16 h-16 bg-gradient-to-br from-pink-400 to-cyan-400 rounded-2xl flex items-center justify-center shadow-xl transform hover:rotate-12 transition-transform duration-300 relative">
                            <div class="absolute inset-0 bg-pink-400 rounded-2xl blur-lg opacity-50 animate-pulse"></div>
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-white relative z-10" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-2xl font-black text-gray-800 mb-1">Your Status</h3>
                            <p class="text-gray-600 font-semibold">
                                @if($currentStudentDailyRank)
                                    Daily Rank: #{{ $currentStudentDailyRank }}
                                    @if($currentStudentWeeklyRank)
                                        | Weekly Rank: #{{ $currentStudentWeeklyRank }}
                                    @endif
                                @else
                                    Keep earning points to climb the leaderboard!
                                @endif
                            </p>
                        </div>
                    </div>
                    <div class="bg-white/90 backdrop-blur-sm px-6 py-5 rounded-2xl shadow-xl text-center transform hover:scale-105 transition-transform duration-300 border-2 border-pink-300/50">
                        <div class="text-xs font-bold text-gray-600 uppercase tracking-wider mb-2">Today's Performance</div>
                        <div class="text-4xl font-black text-pink-700 animate-number-glow">{{ number_format($currentStudentDailyScore ?? 0, 1) }} <span class="text-sm">pts</span></div>
                        <div class="text-lg font-bold text-pink-600 mt-1">({{ number_format($currentStudentDailyPercentage ?? 0, 1) }}%)</div>
                    </div>
                    <div class="bg-white/90 backdrop-blur-sm px-6 py-5 rounded-2xl shadow-xl text-center transform hover:scale-105 transition-transform duration-300 border-2 border-cyan-300/50">
                        <div class="text-xs font-bold text-gray-600 uppercase tracking-wider mb-2">Weekly Performance</div>
                        <div class="text-4xl font-black text-cyan-700 animate-number-glow">{{ number_format($currentStudentWeeklyScore ?? 0, 1) }} <span class="text-sm">pts</span></div>
                        <div class="text-lg font-bold text-cyan-600 mt-1">({{ number_format($currentStudentWeeklyPercentage ?? 0, 1) }}%)</div>
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>

    <style>
        /* Enhanced Animations */
        @keyframes fade-in-up {
            from {
                opacity: 0;
                transform: translateY(40px) scale(0.95);
            }
            to {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }

        .card-entrance, .hero-entrance, .celebration-banner {
            animation: fade-in-up 0.8s ease-out forwards;
            opacity: 0;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0) translateX(0); }
            33% { transform: translateY(-20px) translateX(10px); }
            66% { transform: translateY(10px) translateX(-10px); }
        }

        .animate-float {
            animation: float 6s ease-in-out infinite;
        }

        @keyframes particle-float {
            0% { transform: translateY(100vh) translateX(0) rotate(0deg); opacity: 0; }
            10% { opacity: 1; }
            90% { opacity: 1; }
            100% { transform: translateY(-100px) translateX(100px) rotate(360deg); opacity: 0; }
        }

        .particle {
            position: absolute;
            width: 4px;
            height: 4px;
            background: linear-gradient(45deg, rgba(236, 72, 153, 0.6), rgba(6, 182, 212, 0.6));
            border-radius: 50%;
            animation: particle-float linear infinite;
            pointer-events: none;
        }

        @keyframes confetti-fall {
            0% { transform: translateY(-100vh) rotate(0deg); opacity: 1; }
            100% { transform: translateY(100vh) rotate(720deg); opacity: 0; }
        }

        .confetti {
            position: absolute;
            width: 10px;
            height: 10px;
            animation: confetti-fall 3s linear infinite;
            pointer-events: none;
        }

        .confetti-container {
            position: absolute;
            inset: 0;
            overflow: hidden;
            pointer-events: none;
        }

        @keyframes spin-slow {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }

        .animate-spin-slow {
            animation: spin-slow 8s linear infinite;
        }

        .animate-spin-slow-reverse {
            animation: spin-slow 8s linear infinite reverse;
        }

        @keyframes bounce-rotate {
            0%, 100% { transform: translateY(0) rotate(0deg); }
            50% { transform: translateY(-15px) rotate(10deg); }
        }

        .animate-bounce-rotate {
            animation: bounce-rotate 2s ease-in-out infinite;
        }

        @keyframes bounce-star {
            0%, 100% { transform: translateY(0) scale(1); }
            50% { transform: translateY(-10px) scale(1.1); }
        }

        .animate-bounce-star {
            animation: bounce-star 1.5s ease-in-out infinite;
        }

        @keyframes crown-bounce {
            0%, 100% { transform: translateY(0) rotate(-10deg); }
            50% { transform: translateY(-8px) rotate(10deg); }
        }

        .animate-crown-bounce {
            animation: crown-bounce 2s ease-in-out infinite;
        }

        @keyframes pulse-ring {
            0% { transform: scale(1); opacity: 0.8; }
            50% { transform: scale(1.2); opacity: 0.4; }
            100% { transform: scale(1); opacity: 0.8; }
        }

        .animate-pulse-ring {
            animation: pulse-ring 2s ease-in-out infinite;
        }

        @keyframes ping-slow {
            0% { transform: scale(1); opacity: 1; }
            75%, 100% { transform: scale(2); opacity: 0; }
        }

        .animate-ping-slow {
            animation: ping-slow 3s cubic-bezier(0, 0, 0.2, 1) infinite;
        }

        @keyframes float-celebration {
            0%, 100% { transform: translateY(0) rotate(0deg); }
            50% { transform: translateY(-15px) rotate(5deg); }
        }

        .animate-float-celebration {
            animation: float-celebration 3s ease-in-out infinite;
        }

        @keyframes text-glow {
            0%, 100% { text-shadow: 0 0 10px rgba(251, 191, 36, 0.5); }
            50% { text-shadow: 0 0 20px rgba(251, 191, 36, 0.8), 0 0 30px rgba(251, 191, 36, 0.6); }
        }

        .animate-text-glow {
            animation: text-glow 2s ease-in-out infinite;
        }

        @keyframes pulse-inline {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.05); }
        }

        .animate-pulse-inline {
            animation: pulse-inline 1.5s ease-in-out infinite;
            display: inline-block;
        }

        @keyframes number-glow {
            0%, 100% { text-shadow: 0 0 5px currentColor; }
            50% { text-shadow: 0 0 15px currentColor, 0 0 25px currentColor; }
        }

        .animate-number-glow {
            animation: number-glow 3s ease-in-out infinite;
        }

        @keyframes badge-pulse {
            0%, 100% { transform: scale(1); box-shadow: 0 0 0 0 rgba(251, 191, 36, 0.7); }
            50% { transform: scale(1.05); box-shadow: 0 0 0 10px rgba(251, 191, 36, 0); }
        }

        .animate-badge-pulse {
            animation: badge-pulse 2s ease-in-out infinite;
        }

        @keyframes badge-float {
            0%, 100% { transform: rotate(12deg) translateY(0); }
            50% { transform: rotate(12deg) translateY(-5px); }
        }

        .animate-badge-float {
            animation: badge-float 2s ease-in-out infinite;
        }

        @keyframes text-reveal {
            from { opacity: 0; transform: translateX(-20px); }
            to { opacity: 1; transform: translateX(0); }
        }

        .animate-text-reveal {
            animation: text-reveal 0.8s ease-out forwards;
        }

        @keyframes trophy-spin {
            0%, 100% { transform: rotate(0deg) scale(1); }
            50% { transform: rotate(15deg) scale(1.1); }
        }

        .animate-trophy-spin {
            animation: trophy-spin 3s ease-in-out infinite;
        }

        @keyframes star-pulse {
            0%, 100% { transform: scale(1) rotate(0deg); opacity: 1; }
            50% { transform: scale(1.2) rotate(180deg); opacity: 0.8; }
        }

        .animate-star-pulse {
            animation: star-pulse 2s ease-in-out infinite;
        }

        @keyframes gradient-text {
            0%, 100% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
        }

        .animate-gradient-text {
            background-size: 200% 200%;
            animation: gradient-text 3s ease infinite;
        }

        @keyframes pattern-move {
            0% { background-position: 0 0; }
            100% { background-position: 30px 30px; }
        }

        @keyframes pulse-glow {
            0%, 100% { box-shadow: 0 0 10px rgba(236, 72, 154, 0.5); }
            50% { box-shadow: 0 0 20px rgba(236, 72, 154, 0.8), 0 0 30px rgba(6, 182, 212, 0.6); }
        }

        .animate-pulse-glow {
            animation: pulse-glow 2s ease-in-out infinite;
        }

        /* 3D Card Effect */
        .card-3d {
            transform-style: preserve-3d;
            perspective: 1000px;
        }

        .card-3d:hover {
            transform: rotateY(2deg) rotateX(2deg);
        }

        /* Icon Bounce */
        .icon-bounce {
            animation: icon-bounce 3s ease-in-out infinite;
        }

        @keyframes icon-bounce {
            0%, 100% { transform: translateY(0) rotate(3deg); }
            50% { transform: translateY(-5px) rotate(-3deg); }
        }

        /* Profile Picture Container */
        .profile-picture-container {
            animation: profile-float 4s ease-in-out infinite;
        }

        @keyframes profile-float {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-10px); }
        }

        /* Rank Card Hover Effect */
        .rank-card {
            transition: all 0.5s cubic-bezier(0.34, 1.56, 0.64, 1);
        }

        .rank-card:hover {
            z-index: 10;
        }

        /* Celebration Pattern */
        .celebration-pattern {
            animation: celebration-float 4s ease-in-out infinite;
        }
    </style>
</div>
@endsection
