@extends('layouts.app')

@section('content')
@php
    $student = Auth::user()->student;
    $class = $student?->studentClass;
    $upcomingAssignments = [];
    $lessonsCompleted = 0;
    if ($student) {
        $lessonsCompleted = $student->lessonProgresses()->where('status', 'completed')->count();
    }
    if ($class) {
        $upcomingAssignments = \App\Models\Assignment::where('class_id', $class->class_id)
            ->whereDate('due_date', '>=', now())
            ->orderBy('due_date')
            ->take(5)
            ->get();
    }
@endphp

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
        <!-- Hero Section with Hijab7 Girl - Classy Light Pink & Turquoise -->
        <div class="relative mb-8 flex justify-center">
            <div class="relative w-full max-w-6xl bg-gradient-to-br from-pink-200/80 via-rose-100/70 to-cyan-200/80 rounded-2xl shadow-xl overflow-hidden transform transition-all duration-500 hover:shadow-2xl border border-pink-200/50 backdrop-blur-sm">
                <!-- Elegant Pattern Overlay -->
                <div class="absolute inset-0 opacity-5">
                    <div class="absolute top-0 left-0 w-full h-full" style="background-image: radial-gradient(circle, rgba(255,255,255,0.4) 1px, transparent 1px); background-size: 25px 25px;"></div>
                </div>
                
                <!-- Subtle gradient overlay -->
                <div class="absolute inset-0 bg-gradient-to-br from-white/20 to-transparent"></div>
                
                <div class="relative flex flex-col lg:flex-row items-center justify-between p-8 lg:p-12">
                    <!-- Left Content -->
                    <div class="flex-1 text-center lg:text-left mb-5 lg:mb-0 z-10">
                        <div class="inline-flex items-center gap-2 bg-white/40 backdrop-blur-md px-3 py-1.5 rounded-full mb-3 border border-pink-300/30 shadow-md">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 text-pink-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5zm0 7v-6m0 6H5a2 2 0 01-2-2V7a2 2 0 012-2h14a2 2 0 012 2v12a2 2 0 01-2 2h-7z" />
                            </svg>
                            <span class="text-pink-700 font-semibold text-xs tracking-wide">STUDENT DASHBOARD</span>
                        </div>
                        <h1 class="text-2xl lg:text-3xl font-black text-gray-800 mb-2 tracking-tight leading-tight">
                            Welcome Back,<br>
                            <span class="bg-gradient-to-r from-pink-500 to-cyan-500 bg-clip-text text-transparent">{{ Auth::user()->first_name }}!</span> ✨
                        </h1>
                        <p class="text-sm lg:text-base text-gray-700 font-medium italic mb-5">
                            "A girl is like a pearl; she needs a hijab to protect her."
                        </p>
                        @if($class)
                        <div class="flex flex-wrap gap-2.5 justify-center lg:justify-start">
                            <div class="bg-white/60 backdrop-blur-md px-4 py-2.5 rounded-xl border border-pink-300/40 shadow-md transform hover:scale-105 transition-transform">
                                <div class="text-pink-600 text-xs font-semibold uppercase tracking-wider mb-0.5">Class</div>
                                <div class="text-gray-800 text-lg font-black">{{ $class->class_name }}</div>
                            </div>
                            <div class="bg-white/60 backdrop-blur-md px-4 py-2.5 rounded-xl border border-cyan-300/40 shadow-md transform hover:scale-105 transition-transform">
                                <div class="text-cyan-600 text-xs font-semibold uppercase tracking-wider mb-0.5">Lessons Completed</div>
                                <div class="text-gray-800 text-lg font-black">{{ $lessonsCompleted }}</div>
                            </div>
                        </div>
                        @endif
                    </div>
                    
                    <!-- Right: Hijab7 Girl Image with Elegant Design -->
                    <div class="relative flex-shrink-0 mt-5 lg:mt-0">
                        <div class="relative">
                            <!-- Subtle Glow Layers -->
                            <div class="absolute inset-0 bg-pink-300/30 rounded-full blur-2xl opacity-50 animate-pulse"></div>
                            <div class="absolute inset-0 bg-cyan-300/30 rounded-full blur-xl opacity-40 animate-pulse" style="animation-delay: 1s;"></div>
                            
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
                            <div class="relative bg-white rounded-full p-1.5 shadow-xl transform hover:scale-105 transition-transform duration-500 border-2 border-pink-200/60">
                                <div class="absolute inset-0 bg-gradient-to-br from-pink-200/40 to-cyan-200/40 rounded-full opacity-30 blur-lg"></div>
                                <img src="{{ asset('storage/grade-page-design/hijab7.jpg') }}" 
                                     alt="Hijabi Student" 
                                     class="relative w-48 h-48 lg:w-60 lg:h-60 rounded-full object-cover border border-pink-200/40 shadow-lg z-10"
                                     style="object-position: center 20%;"
                                     loading="lazy">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Congratulations Banner for Top 3 Students - Creative Design -->
        @if(isset($isInTop3Day) && ($isInTop3Day || $isInTop3Week))
        <div class="mb-8 relative">
            <!-- Confetti Container -->
            <div id="confetti-container" class="absolute inset-0 pointer-events-none overflow-hidden z-20"></div>
            
            @if($isInTop3Day)
            <!-- Top of the Day - Creative Medal Design -->
            <div class="relative overflow-visible">
                <!-- Ribbon Banner at Top -->
                <div class="absolute -top-5 left-1/2 transform -translate-x-1/2 z-30">
                    <div class="relative">
                        <!-- Ribbon with elegant design -->
                        <div class="bg-gradient-to-r from-yellow-400 via-amber-400 to-yellow-400 px-10 py-3 rounded-full shadow-2xl border-4 border-yellow-200 relative overflow-hidden">
                            <!-- Shine effect -->
                            <div class="absolute inset-0 bg-gradient-to-r from-transparent via-white/40 to-transparent animate-shimmer"></div>
                            
                            <!-- Decorative elements -->
                            <div class="absolute -left-2 top-1/2 transform -translate-y-1/2 w-6 h-6 bg-yellow-400 rounded-full border-2 border-yellow-200"></div>
                            <div class="absolute -right-2 top-1/2 transform -translate-y-1/2 w-6 h-6 bg-yellow-400 rounded-full border-2 border-yellow-200"></div>
                            
                            <span class="relative z-10 text-white font-black text-base md:text-lg lg:text-xl drop-shadow-lg tracking-wide flex items-center gap-2">
                                <span class="text-2xl animate-bounce" style="animation-delay: 0s;">🏅</span>
                                <span>CHAMPION OF THE DAY</span>
                                <span class="text-2xl animate-bounce" style="animation-delay: 0.2s;">🏅</span>
                            </span>
                        </div>
                        
                        <!-- Ribbon tails -->
                        <div class="absolute -left-4 top-0 w-8 h-6 bg-yellow-400 transform skew-y-12 border-l-4 border-yellow-200"></div>
                        <div class="absolute -right-4 top-0 w-8 h-6 bg-yellow-400 transform -skew-y-12 border-r-4 border-yellow-200"></div>
                    </div>
                </div>
                
                <!-- Main Card with 3D Effect -->
                <div class="relative overflow-hidden rounded-3xl shadow-2xl transform transition-all duration-500 hover:shadow-3xl hover:scale-[1.02] hover:-translate-y-1" style="perspective: 1000px;">
                    <!-- Animated gradient background -->
                    <div class="absolute inset-0 bg-gradient-to-br from-yellow-400 via-amber-400 via-yellow-500 to-orange-400 animate-gradient-shift"></div>
                    <div class="absolute inset-0 bg-gradient-to-tr from-transparent via-white/10 to-transparent"></div>
                    
                    <!-- Shimmer effect -->
                    <div class="absolute inset-0 -translate-x-full animate-shimmer bg-gradient-to-r from-transparent via-white/30 to-transparent"></div>
                    
                    <!-- Animated particles -->
                    <div class="absolute inset-0 pointer-events-none overflow-hidden">
                        @for($i = 0; $i < 20; $i++)
                        <div class="absolute w-2 h-2 rounded-full bg-yellow-300 animate-particle-fall opacity-70" 
                             style="left: {{ rand(0, 100) }}%; animation-delay: {{ $i * 0.1 }}s; animation-duration: {{ 2 + rand(0, 3) }}s;"></div>
                        @endfor
                    </div>
                    
                    <!-- Glowing border effect -->
                    <div class="absolute inset-0 rounded-3xl border-4 border-yellow-300/80 shadow-[0_0_30px_rgba(251,191,36,0.6)] animate-pulse-glow"></div>
                    
                    <!-- Content -->
                    <div class="relative z-10 p-4 md:p-5 pt-8">
                        <div class="flex flex-col lg:flex-row items-center justify-center gap-4">
                            <!-- Medal Badge (Left) -->
                            <div class="relative flex-shrink-0">
                                <div class="relative">
                                    <!-- Outer glow rings -->
                                    <div class="absolute inset-0 bg-yellow-400 rounded-full blur-2xl opacity-40 animate-pulse"></div>
                                    <div class="absolute inset-0 bg-amber-400 rounded-full blur-xl opacity-30 animate-pulse" style="animation-delay: 0.5s;"></div>
                                    
                                    <!-- Medal circle -->
                                    <div class="relative w-24 h-24 md:w-28 md:h-28 bg-gradient-to-br from-yellow-300 via-amber-300 to-yellow-400 rounded-full flex items-center justify-center border-4 border-yellow-200 shadow-2xl transform hover:rotate-12 transition-transform animate-medal-spin">
                                        <div class="text-5xl md:text-6xl animate-trophy-bounce">@if($top3DayPosition == 1)🥇@elseif($top3DayPosition == 2)🥈@else🥉@endif</div>
                                    </div>
                                    
                                    <!-- Rank number badge -->
                                    <div class="absolute -bottom-2 -right-2 w-10 h-10 bg-white rounded-full flex items-center justify-center border-4 border-yellow-400 shadow-xl animate-bounce-in">
                                        <span class="text-yellow-600 font-black text-xl">#{{ $top3DayPosition }}</span>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Center Content -->
                            <div class="flex-1 text-center lg:text-left">
                                <div class="inline-block mb-2">
                                    <span class="bg-white/95 backdrop-blur-sm px-3 py-1 rounded-full text-xs font-black text-yellow-900 uppercase tracking-wider border-2 border-yellow-300 shadow-lg animate-bounce-in">
                                        ⭐ TOP PERFORMER ⭐
                                    </span>
                                </div>
                                <h2 class="text-3xl md:text-4xl font-black text-white mb-2 drop-shadow-lg leading-tight animate-text-reveal">
                                    <span class="inline-block animate-bounce-in" style="animation-delay: 0.1s;">🎉</span>
                                    <span class="inline-block mx-2 bg-gradient-to-r from-white to-yellow-100 bg-clip-text text-transparent">CONGRATULATIONS!</span>
                                    <span class="inline-block animate-bounce-in" style="animation-delay: 0.2s;">🎉</span>
                                </h2>
                                <p class="text-xl md:text-2xl font-black text-yellow-900 drop-shadow-md mb-1">
                                    You're Ranked <span class="text-3xl md:text-4xl text-white">#{{ $top3DayPosition }}</span>
                                </p>
                                <p class="text-lg md:text-xl font-bold text-yellow-800 drop-shadow-sm">
                                    Top Student of the Day! 🌟
                                </p>
                                <p class="text-sm md:text-base text-yellow-900 font-semibold mt-2 drop-shadow-sm">
                                    Keep up the amazing work! ✨
                                </p>
                            </div>
                            
                            <!-- Sparkle Effects (Right) -->
                            <div class="flex flex-col gap-2">
                                <div class="relative">
                                    <span class="text-5xl md:text-6xl animate-star-spin opacity-90 block" style="animation-delay: 0.1s;">✨</span>
                                    <div class="absolute inset-0 text-5xl md:text-6xl animate-ping opacity-30">✨</div>
                                </div>
                                <div class="relative">
                                    <span class="text-5xl md:text-6xl animate-star-spin opacity-90 block" style="animation-delay: 0.3s;">🌟</span>
                                    <div class="absolute inset-0 text-5xl md:text-6xl animate-ping opacity-30">🌟</div>
                                </div>
                                <div class="relative">
                                    <span class="text-5xl md:text-6xl animate-star-spin opacity-90 block" style="animation-delay: 0.5s;">⭐</span>
                                    <div class="absolute inset-0 text-5xl md:text-6xl animate-ping opacity-30">⭐</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            @elseif($isInTop3Week)
            <!-- Top of the Week - Creative Medal Design -->
            <div class="relative overflow-visible">
                <!-- Ribbon Banner at Top -->
                <div class="absolute -top-5 left-1/2 transform -translate-x-1/2 z-30">
                    <div class="relative">
                        <!-- Ribbon with elegant design -->
                        <div class="bg-gradient-to-r from-purple-500 via-pink-500 to-purple-500 px-10 py-3 rounded-full shadow-2xl border-4 border-purple-200 relative overflow-hidden">
                            <!-- Shine effect -->
                            <div class="absolute inset-0 bg-gradient-to-r from-transparent via-white/40 to-transparent animate-shimmer"></div>
                            
                            <!-- Decorative elements -->
                            <div class="absolute -left-2 top-1/2 transform -translate-y-1/2 w-6 h-6 bg-purple-500 rounded-full border-2 border-purple-200"></div>
                            <div class="absolute -right-2 top-1/2 transform -translate-y-1/2 w-6 h-6 bg-purple-500 rounded-full border-2 border-purple-200"></div>
                            
                            <span class="relative z-10 text-white font-black text-base md:text-lg lg:text-xl drop-shadow-lg tracking-wide flex items-center gap-2">
                                <span class="text-2xl animate-bounce" style="animation-delay: 0s;">🏅</span>
                                <span>CHAMPION OF THE WEEK</span>
                                <span class="text-2xl animate-bounce" style="animation-delay: 0.2s;">🏅</span>
                            </span>
                        </div>
                        
                        <!-- Ribbon tails -->
                        <div class="absolute -left-4 top-0 w-8 h-6 bg-purple-500 transform skew-y-12 border-l-4 border-purple-200"></div>
                        <div class="absolute -right-4 top-0 w-8 h-6 bg-purple-500 transform -skew-y-12 border-r-4 border-purple-200"></div>
                    </div>
                </div>
                
                <!-- Main Card with 3D Effect -->
                <div class="relative overflow-hidden rounded-3xl shadow-2xl transform transition-all duration-500 hover:shadow-3xl hover:scale-[1.02] hover:-translate-y-1" style="perspective: 1000px;">
                    <!-- Animated gradient background -->
                    <div class="absolute inset-0 bg-gradient-to-br from-purple-500 via-pink-500 via-purple-600 to-indigo-500 animate-gradient-shift"></div>
                    <div class="absolute inset-0 bg-gradient-to-tr from-transparent via-white/10 to-transparent"></div>
                    
                    <!-- Shimmer effect -->
                    <div class="absolute inset-0 -translate-x-full animate-shimmer bg-gradient-to-r from-transparent via-white/30 to-transparent"></div>
                    
                    <!-- Animated particles -->
                    <div class="absolute inset-0 pointer-events-none overflow-hidden">
                        @for($i = 0; $i < 20; $i++)
                        <div class="absolute w-2 h-2 rounded-full bg-purple-300 animate-particle-fall opacity-70" 
                             style="left: {{ rand(0, 100) }}%; animation-delay: {{ $i * 0.1 }}s; animation-duration: {{ 2 + rand(0, 3) }}s;"></div>
                        @endfor
                    </div>
                    
                    <!-- Glowing border effect -->
                    <div class="absolute inset-0 rounded-3xl border-4 border-purple-300/80 shadow-[0_0_30px_rgba(196,181,253,0.6)] animate-pulse-glow"></div>
                    
                    <!-- Content -->
                    <div class="relative z-10 p-4 md:p-5 pt-8">
                        <div class="flex flex-col lg:flex-row items-center justify-center gap-4">
                            <!-- Medal Badge (Left) -->
                            <div class="relative flex-shrink-0">
                                <div class="relative">
                                    <!-- Outer glow rings -->
                                    <div class="absolute inset-0 bg-purple-400 rounded-full blur-2xl opacity-40 animate-pulse"></div>
                                    <div class="absolute inset-0 bg-pink-400 rounded-full blur-xl opacity-30 animate-pulse" style="animation-delay: 0.5s;"></div>
                                    
                                    <!-- Medal circle -->
                                    <div class="relative w-24 h-24 md:w-28 md:h-28 bg-gradient-to-br from-purple-400 via-pink-400 to-purple-500 rounded-full flex items-center justify-center border-4 border-purple-200 shadow-2xl transform hover:rotate-12 transition-transform animate-medal-spin">
                                        <div class="text-5xl md:text-6xl animate-trophy-bounce">@if($top3WeekPosition == 1)🥇@elseif($top3WeekPosition == 2)🥈@else🥉@endif</div>
                                    </div>
                                    
                                    <!-- Rank number badge -->
                                    <div class="absolute -bottom-2 -right-2 w-10 h-10 bg-white rounded-full flex items-center justify-center border-4 border-purple-400 shadow-xl animate-bounce-in">
                                        <span class="text-purple-600 font-black text-xl">#{{ $top3WeekPosition }}</span>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Center Content -->
                            <div class="flex-1 text-center lg:text-left">
                                <div class="inline-block mb-2">
                                    <span class="bg-white/95 backdrop-blur-sm px-3 py-1 rounded-full text-xs font-black text-purple-900 uppercase tracking-wider border-2 border-purple-300 shadow-lg animate-bounce-in">
                                        ⭐ TOP PERFORMER ⭐
                                    </span>
                                </div>
                                <h2 class="text-3xl md:text-4xl font-black text-white mb-2 drop-shadow-lg leading-tight animate-text-reveal">
                                    <span class="inline-block animate-bounce-in" style="animation-delay: 0.1s;">🎉</span>
                                    <span class="inline-block mx-2 bg-gradient-to-r from-white to-purple-100 bg-clip-text text-transparent">CONGRATULATIONS!</span>
                                    <span class="inline-block animate-bounce-in" style="animation-delay: 0.2s;">🎉</span>
                                </h2>
                                <p class="text-xl md:text-2xl font-black text-purple-900 drop-shadow-md mb-1">
                                    You're Ranked <span class="text-3xl md:text-4xl text-white">#{{ $top3WeekPosition }}</span>
                                </p>
                                <p class="text-lg md:text-xl font-bold text-purple-800 drop-shadow-sm">
                                    Top Student of the Week! 🌟
                                </p>
                                <p class="text-sm md:text-base text-purple-900 font-semibold mt-2 drop-shadow-sm">
                                    Outstanding performance this week! ✨
                                </p>
                            </div>
                            
                            <!-- Sparkle Effects (Right) -->
                            <div class="flex flex-col gap-2">
                                <div class="relative">
                                    <span class="text-5xl md:text-6xl animate-star-spin opacity-90 block" style="animation-delay: 0.1s;">✨</span>
                                    <div class="absolute inset-0 text-5xl md:text-6xl animate-ping opacity-30">✨</div>
                                </div>
                                <div class="relative">
                                    <span class="text-5xl md:text-6xl animate-star-spin opacity-90 block" style="animation-delay: 0.3s;">🌟</span>
                                    <div class="absolute inset-0 text-5xl md:text-6xl animate-ping opacity-30">🌟</div>
                                </div>
                                <div class="relative">
                                    <span class="text-5xl md:text-6xl animate-star-spin opacity-90 block" style="animation-delay: 0.5s;">⭐</span>
                                    <div class="absolute inset-0 text-5xl md:text-6xl animate-ping opacity-30">⭐</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endif
        </div>
        
        <!-- Confetti Script -->
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const container = document.getElementById('confetti-container');
                if (!container) return;
                
                const colors = ['#fbbf24', '#f59e0b', '#f97316', '#fb923c'];
                const confettiCount = 50;
                
                for (let i = 0; i < confettiCount; i++) {
                    const confetti = document.createElement('div');
                    confetti.style.position = 'absolute';
                    confetti.style.width = Math.random() * 10 + 5 + 'px';
                    confetti.style.height = confetti.style.width;
                    confetti.style.backgroundColor = colors[Math.floor(Math.random() * colors.length)];
                    confetti.style.left = Math.random() * 100 + '%';
                    confetti.style.top = '-10px';
                    confetti.style.borderRadius = Math.random() > 0.5 ? '50%' : '0';
                    confetti.style.opacity = Math.random() * 0.5 + 0.5;
                    confetti.style.transform = 'rotate(' + Math.random() * 360 + 'deg)';
                    confetti.style.animation = 'particle-fall ' + (Math.random() * 3 + 2) + 's linear forwards';
                    confetti.style.animationDelay = Math.random() * 2 + 's';
                    container.appendChild(confetti);
                }
            });
        </script>
        @endif

        @if($class)
        <!-- Main Content Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-5 mb-6">
            <!-- Class Info Card - Large (8 columns) -->
            <div class="lg:col-span-8 bg-white/90 backdrop-blur-md rounded-2xl shadow-xl p-6 border border-pink-200/40 transform transition-all duration-300 hover:shadow-2xl hover:-translate-y-1">
                <div class="flex items-center justify-between mb-6">
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 bg-gradient-to-br from-pink-300 to-cyan-300 rounded-xl flex items-center justify-center shadow-md transform rotate-3 hover:rotate-6 transition-transform">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                            </svg>
                        </div>
                        <h2 class="text-2xl font-black text-gray-800">Class Information</h2>
                    </div>
                    <span class="px-5 py-2 bg-gradient-to-r from-pink-300 to-cyan-300 text-gray-800 rounded-full text-sm font-black shadow-md border border-pink-200/50 transform hover:scale-110 transition-transform">
                        {{ $class->class_name }}
                    </span>
                </div>

                @if(!empty($class->class_message))
                <div class="mb-6 p-5 bg-gradient-to-r from-pink-50/80 via-cyan-50/60 to-teal-50/60 rounded-2xl border-l-4 border-pink-300 shadow-md">
                    <div class="flex items-start gap-3">
                        <div class="w-10 h-10 bg-gradient-to-br from-pink-300 to-cyan-300 rounded-xl flex items-center justify-center flex-shrink-0 shadow-md">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
                            </svg>
                        </div>
                        <div class="flex-1">
                            <div class="text-xs font-bold text-pink-600 uppercase tracking-wide mb-1.5">Class Message</div>
                            <div class="text-gray-800 text-base font-medium leading-relaxed">{{ $class->class_message }}</div>
                        </div>
                    </div>
                </div>
                @endif

                <!-- Stats Grid -->
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-5">
                    <div class="bg-gradient-to-br from-cyan-50 to-cyan-100/80 rounded-xl p-5 border border-cyan-200 transform transition-all duration-300 hover:scale-105 hover:shadow-lg hover:border-cyan-300">
                        <div class="flex items-center gap-2.5 mb-2.5">
                            <div class="w-9 h-9 bg-gradient-to-br from-cyan-300 to-teal-300 rounded-lg flex items-center justify-center shadow-md">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                            </div>
                            <span class="text-xs font-bold text-cyan-700 uppercase tracking-wider">Teacher</span>
                        </div>
                        <div class="text-base font-black text-cyan-900 truncate">{{ $class->teacher ? $class->teacher->first_name . ' ' . $class->teacher->last_name : 'Unassigned' }}</div>
                    </div>

                    <div class="bg-gradient-to-br from-teal-50 to-teal-100/80 rounded-xl p-5 border border-teal-200 transform transition-all duration-300 hover:scale-105 hover:shadow-lg hover:border-teal-300">
                        <div class="flex items-center gap-2.5 mb-2.5">
                            <div class="w-9 h-9 bg-gradient-to-br from-teal-300 to-cyan-300 rounded-lg flex items-center justify-center shadow-md">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                </svg>
                            </div>
                            <span class="text-xs font-bold text-teal-700 uppercase tracking-wider">Students</span>
                        </div>
                        <div class="text-3xl font-black text-teal-900">{{ $class->students->count() }}</div>
                    </div>

                    <div class="bg-gradient-to-br from-pink-50 to-pink-100/80 rounded-xl p-5 border border-pink-200 transform transition-all duration-300 hover:scale-105 hover:shadow-lg hover:border-pink-300">
                        <div class="flex items-center gap-2.5 mb-2.5">
                            <div class="w-9 h-9 bg-gradient-to-br from-pink-300 to-rose-300 rounded-lg flex items-center justify-center shadow-md">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                                </svg>
                            </div>
                            <span class="text-xs font-bold text-pink-700 uppercase tracking-wider">Capacity</span>
                        </div>
                        <div class="text-3xl font-black text-pink-900">{{ $class->capacity }}</div>
                    </div>

                    <div class="bg-gradient-to-br from-gray-50 to-gray-100/80 rounded-xl p-5 border border-gray-200 transform transition-all duration-300 hover:scale-105 hover:shadow-lg hover:border-gray-300">
                        <div class="flex items-center gap-2.5 mb-2.5">
                            <div class="w-9 h-9 bg-gradient-to-br from-gray-400 to-gray-500 rounded-lg flex items-center justify-center shadow-md">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <span class="text-xs font-bold text-gray-700 uppercase tracking-wider">Status</span>
                        </div>
                        <span class="inline-block px-3 py-1.5 rounded-full font-black text-xs tracking-wide border
                            @if($class->status === 'active') bg-green-100 text-green-800 border-green-300
                            @elseif($class->status === 'full') bg-yellow-100 text-yellow-800 border-yellow-300
                            @elseif($class->status === 'closed') bg-red-100 text-red-800 border-red-300
                            @else bg-gray-200 text-gray-700 border-gray-300 @endif">
                            {{ ucfirst($class->status) }}
                        </span>
                    </div>
                </div>

                <!-- Lessons Completed Highlight - Light Pink & Turquoise -->
                <div class="bg-gradient-to-r from-pink-200/90 via-rose-200/80 to-cyan-200/90 rounded-2xl p-6 shadow-xl border border-pink-200/60 transform transition-all duration-300 hover:scale-[1.01] hover:shadow-2xl">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-5">
                            <div class="w-16 h-16 bg-white/50 backdrop-blur-md rounded-2xl flex items-center justify-center shadow-lg border border-white/60">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-pink-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z" />
                                </svg>
                            </div>
                            <div>
                                <div class="text-gray-700 text-xs font-black uppercase tracking-widest mb-1.5">Lessons Completed</div>
                                <div class="text-5xl font-black bg-gradient-to-r from-pink-600 to-cyan-600 bg-clip-text text-transparent drop-shadow-sm">{{ $lessonsCompleted }}</div>
                            </div>
                        </div>
                        <div class="text-6xl opacity-30 animate-bounce">🎓</div>
                    </div>
                </div>
            </div>

            <!-- Upcoming Assignments Card (4 columns) -->
            <div class="lg:col-span-4 bg-white/90 backdrop-blur-md rounded-2xl shadow-xl p-6 border border-cyan-200/40 transform transition-all duration-300 hover:shadow-2xl hover:-translate-y-1">
                <div class="flex items-center gap-3 mb-5">
                    <div class="w-12 h-12 bg-gradient-to-br from-cyan-300 to-teal-300 rounded-xl flex items-center justify-center shadow-md transform -rotate-3 hover:rotate-0 transition-transform">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                        </svg>
                    </div>
                    <h2 class="text-xl font-black text-gray-800">Upcoming Assignments</h2>
                </div>
                
                @if($upcomingAssignments->count())
                    <div class="space-y-3 max-h-[600px] overflow-y-auto pr-2 custom-scrollbar">
                        @foreach($upcomingAssignments as $assignment)
                            <div class="group bg-gradient-to-br from-pink-50/80 via-white to-cyan-50/80 rounded-xl p-5 border border-pink-200/60 shadow-md transform transition-all duration-300 hover:scale-105 hover:shadow-lg hover:border-cyan-300">
                                <div class="flex items-start justify-between mb-3">
                                    <div class="flex-1">
                                        <h3 class="font-black text-pink-700 text-base mb-2 group-hover:text-cyan-700 transition-colors leading-tight">{{ $assignment->title }}</h3>
                                        <div class="flex items-center gap-2 text-cyan-600 text-xs font-bold">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                            </svg>
                                            <span>Due: {{ \Carbon\Carbon::parse($assignment->due_date)->format('M d, Y') }}</span>
                                        </div>
                                    </div>
                                    <div class="ml-2 text-2xl transform group-hover:scale-110 transition-transform">📝</div>
                                </div>
                                <a href="{{ asset('storage/' . $assignment->file_path) }}" 
                                   target="_blank"
                                   class="inline-flex items-center gap-2 w-full justify-center bg-gradient-to-r from-pink-300 to-cyan-300 text-gray-800 px-4 py-2.5 rounded-lg font-black shadow-md hover:shadow-lg transform hover:scale-105 transition-all duration-300 group-hover:from-pink-400 group-hover:to-cyan-400 text-xs uppercase tracking-wide">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                    View Assignment
                                </a>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-12">
                        <div class="text-6xl mb-4 animate-bounce">📚</div>
                        <div class="text-gray-500 text-base font-bold mb-2">No upcoming assignments</div>
                        <div class="text-gray-400 text-sm font-medium">You're all caught up! 🎉</div>
                    </div>
                @endif
            </div>
        </div>
        @else
        <!-- No Class Message -->
        <div class="bg-white/90 backdrop-blur-md rounded-2xl shadow-xl p-16 border border-pink-200/40 text-center">
            <div class="text-7xl mb-6 animate-bounce">🎓</div>
            <h3 class="text-3xl font-black text-gray-800 mb-4">Not Enrolled Yet</h3>
            <p class="text-gray-600 text-lg font-medium">You are not enrolled in any class yet. Please contact your administrator.</p>
        </div>
        @endif
    </div>

    <style>
        .custom-scrollbar::-webkit-scrollbar {
            width: 8px;
        }
        .custom-scrollbar::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 10px;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: linear-gradient(to bottom, #f9a8d4, #67e8f9);
            border-radius: 10px;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: linear-gradient(to bottom, #f472b6, #22d3ee);
        }
        @keyframes float {
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            50% { transform: translateY(-20px) rotate(5deg); }
        }
        @keyframes shimmer {
            0% { background-position: -1000px 0; }
            100% { background-position: 1000px 0; }
        }
        
        /* Enhanced Congratulations Banner Animations */
        @keyframes gradient-shift {
            0%, 100% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
        }
        .animate-gradient-shift {
            background-size: 200% 200%;
            animation: gradient-shift 3s ease infinite;
        }
        
        @keyframes shimmer {
            0% { transform: translateX(-100%); }
            100% { transform: translateX(100%); }
        }
        .animate-shimmer {
            animation: shimmer 3s infinite;
        }
        
        @keyframes float-celebration {
            0%, 100% { 
                transform: translateY(0px) translateX(0px) rotate(0deg) scale(1);
                opacity: 0.6;
            }
            25% { 
                transform: translateY(-15px) translateX(10px) rotate(5deg) scale(1.1);
                opacity: 0.8;
            }
            50% { 
                transform: translateY(-25px) translateX(-5px) rotate(-5deg) scale(0.9);
                opacity: 0.7;
            }
            75% { 
                transform: translateY(-10px) translateX(-10px) rotate(3deg) scale(1.05);
                opacity: 0.75;
            }
        }
        .animate-float-celebration {
            animation: float-celebration 4s ease-in-out infinite;
        }
        
        @keyframes pulse-glow {
            0%, 100% { 
                box-shadow: 0 0 30px rgba(251, 191, 36, 0.6);
                opacity: 1;
            }
            50% { 
                box-shadow: 0 0 50px rgba(251, 191, 36, 0.9), 0 0 70px rgba(251, 191, 36, 0.5);
                opacity: 0.8;
            }
        }
        .animate-pulse-glow {
            animation: pulse-glow 2s ease-in-out infinite;
        }
        
        @keyframes trophy-bounce {
            0%, 100% { 
                transform: translateY(0px) rotate(0deg) scale(1);
            }
            25% { 
                transform: translateY(-10px) rotate(-5deg) scale(1.05);
            }
            50% { 
                transform: translateY(-15px) rotate(0deg) scale(1.1);
            }
            75% { 
                transform: translateY(-10px) rotate(5deg) scale(1.05);
            }
        }
        .animate-trophy-bounce {
            animation: trophy-bounce 2s ease-in-out infinite;
        }
        
        @keyframes bounce-in {
            0% { 
                transform: scale(0) rotate(-180deg);
                opacity: 0;
            }
            50% { 
                transform: scale(1.2) rotate(10deg);
                opacity: 1;
            }
            100% { 
                transform: scale(1) rotate(0deg);
                opacity: 1;
            }
        }
        .animate-bounce-in {
            animation: bounce-in 0.6s ease-out;
        }
        
        @keyframes star-spin {
            0%, 100% { 
                transform: rotate(0deg) scale(1);
                opacity: 0.9;
            }
            25% { 
                transform: rotate(90deg) scale(1.1);
                opacity: 1;
            }
            50% { 
                transform: rotate(180deg) scale(1.2);
                opacity: 0.95;
            }
            75% { 
                transform: rotate(270deg) scale(1.1);
                opacity: 1;
            }
        }
        .animate-star-spin {
            animation: star-spin 3s ease-in-out infinite;
        }
        
        @keyframes fade-in-up {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        .animate-fade-in-up {
            animation: fade-in-up 0.8s ease-out;
        }
        
        /* Creative Design Animations */
        @keyframes particle-fall {
            0% {
                transform: translateY(0) translateX(0) rotate(0deg);
                opacity: 1;
            }
            100% {
                transform: translateY(100vh) translateX(calc(var(--random-x, 0) * 50px)) rotate(720deg);
                opacity: 0;
            }
        }
        .animate-particle-fall {
            animation: particle-fall linear forwards;
        }
        
        @keyframes medal-spin {
            0%, 100% { 
                transform: rotate(0deg) scale(1);
            }
            25% { 
                transform: rotate(-5deg) scale(1.05);
            }
            50% { 
                transform: rotate(0deg) scale(1.1);
            }
            75% { 
                transform: rotate(5deg) scale(1.05);
            }
        }
        .animate-medal-spin {
            animation: medal-spin 2s ease-in-out infinite;
        }
        
        @keyframes text-reveal {
            0% {
                opacity: 0;
                transform: translateY(20px) scale(0.9);
            }
            100% {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }
        .animate-text-reveal {
            animation: text-reveal 0.8s ease-out;
        }
        
        @keyframes confetti-fall {
            0% {
                transform: translateY(-10px) translateX(0) rotate(0deg);
                opacity: 1;
            }
            100% {
                transform: translateY(100vh) translateX(calc(var(--random-x, 0) * 100px)) rotate(720deg);
                opacity: 0;
            }
        }
    </style>
</div>
@endsection
