@extends('layouts.app')

@section('content')
<style>
    /* Animated Background Elements */
    @keyframes float {
        0%, 100% { transform: translateY(0px) translateX(0px) rotate(0deg); }
        33% { transform: translateY(-30px) translateX(20px) rotate(5deg); }
        66% { transform: translateY(20px) translateX(-20px) rotate(-5deg); }
    }
    
    @keyframes pulse-glow {
        0%, 100% { 
            opacity: 0.4;
            transform: scale(1);
        }
        50% { 
            opacity: 0.8;
            transform: scale(1.1);
        }
    }
    
    @keyframes shimmer {
        0% { background-position: -1000px 0; }
        100% { background-position: 1000px 0; }
    }
    
    @keyframes gradient-shift {
        0%, 100% { background-position: 0% 50%; }
        50% { background-position: 100% 50%; }
    }
    
    @keyframes slideInUp {
        from {
            opacity: 0;
            transform: translateY(50px) scale(0.9);
        }
        to {
            opacity: 1;
            transform: translateY(0) scale(1);
        }
    }
    
    @keyframes bounceIn {
        0% { 
            transform: scale(0.3) rotate(-180deg);
            opacity: 0;
        }
        50% { 
            transform: scale(1.1) rotate(10deg);
        }
        70% { 
            transform: scale(0.9) rotate(-5deg);
        }
        100% { 
            transform: scale(1) rotate(0deg);
            opacity: 1;
        }
    }
    
    @keyframes heartbeat {
        0%, 100% { transform: scale(1); }
        25% { transform: scale(1.15); }
        50% { transform: scale(1); }
        75% { transform: scale(1.1); }
    }
    
    @keyframes rotate360 {
        from { transform: rotate(0deg); }
        to { transform: rotate(360deg); }
    }
    
    @keyframes sparkle {
        0%, 100% { 
            opacity: 0;
            transform: scale(0) rotate(0deg);
        }
        50% { 
            opacity: 1;
            transform: scale(1.5) rotate(180deg);
        }
    }
    
    @keyframes wave {
        0%, 100% { transform: translateY(0px); }
        50% { transform: translateY(-20px); }
    }
    
    @keyframes float-gentle {
        0%, 100% { transform: translateY(0px) rotate(0deg); }
        50% { transform: translateY(-15px) rotate(3deg); }
    }
    
    @keyframes scale-in {
        from {
            opacity: 0;
            transform: scale(0.8);
        }
        to {
            opacity: 1;
            transform: scale(1);
        }
    }
    
    @keyframes glow-pulse {
        0%, 100% { 
            box-shadow: 0 0 20px rgba(252, 142, 172, 0.4);
        }
        50% { 
            box-shadow: 0 0 40px rgba(252, 142, 172, 0.8), 0 0 60px rgba(110, 198, 197, 0.6);
        }
    }
    
    /* Background Animation */
    .animated-bg {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        z-index: 0;
        overflow: hidden;
        pointer-events: none;
    }
    
    .floating-shape {
        position: absolute;
        border-radius: 50%;
        opacity: 0.1;
        animation: float 20s ease-in-out infinite;
    }
    
    .floating-shape:nth-child(1) {
        width: 300px;
        height: 300px;
        background: linear-gradient(135deg, #FC8EAC, #EC769A);
        top: 10%;
        left: 5%;
        animation-delay: 0s;
    }
    
    .floating-shape:nth-child(2) {
        width: 250px;
        height: 250px;
        background: linear-gradient(135deg, #6EC6C5, #5AB8B7);
        top: 60%;
        right: 10%;
        animation-delay: 2s;
    }
    
    .floating-shape:nth-child(3) {
        width: 200px;
        height: 200px;
        background: linear-gradient(135deg, #EC769A, #FC8EAC);
        bottom: 20%;
        left: 20%;
        animation-delay: 4s;
    }
    
    .floating-shape:nth-child(4) {
        width: 180px;
        height: 180px;
        background: linear-gradient(135deg, #6EC6C5, #5AB8B7);
        top: 30%;
        right: 30%;
        animation-delay: 6s;
    }
    
    /* Sparkle Particles */
    .sparkle {
        position: absolute;
        width: 8px;
        height: 8px;
        background: white;
        border-radius: 50%;
        animation: sparkle 3s ease-in-out infinite;
        pointer-events: none;
    }
    
    /* Card Animations */
    .event-card {
        animation: slideInUp 0.6s ease-out;
        animation-fill-mode: both;
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
        overflow: hidden;
    }
    
    .event-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255,255,255,0.4), transparent);
        transition: left 0.6s ease;
    }
    
    .event-card:hover::before {
        left: 100%;
    }
    
    .event-card:hover {
        transform: translateY(-12px) scale(1.03) rotate(1deg);
        box-shadow: 0 30px 60px rgba(252, 142, 172, 0.4);
    }
    
    .event-card:nth-child(1) { animation-delay: 0.1s; }
    .event-card:nth-child(2) { animation-delay: 0.2s; }
    .event-card:nth-child(3) { animation-delay: 0.3s; }
    .event-card:nth-child(4) { animation-delay: 0.4s; }
    .event-card:nth-child(5) { animation-delay: 0.5s; }
    .event-card:nth-child(6) { animation-delay: 0.6s; }
    
    /* Timeline Animations */
    .timeline-item {
        animation: slideInUp 0.5s ease-out;
        animation-fill-mode: both;
        position: relative;
    }
    
    .timeline-item::before {
        content: '';
        position: absolute;
        left: 24px;
        top: 60px;
        width: 3px;
        height: calc(100% + 20px);
        background: linear-gradient(180deg, #FC8EAC, #6EC6C5);
        opacity: 0.3;
        animation: pulse-glow 2s ease-in-out infinite;
    }
    
    .timeline-item:last-child::before {
        display: none;
    }
    
    .timeline-item:nth-child(1) { animation-delay: 0.1s; }
    .timeline-item:nth-child(2) { animation-delay: 0.2s; }
    .timeline-item:nth-child(3) { animation-delay: 0.3s; }
    .timeline-item:nth-child(4) { animation-delay: 0.4s; }
    .timeline-item:nth-child(5) { animation-delay: 0.5s; }
    
    .timeline-dot {
        animation: heartbeat 2s ease-in-out infinite;
        position: relative;
        z-index: 10;
    }
    
    .timeline-dot::after {
        content: '';
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        width: 100%;
        height: 100%;
        border-radius: 50%;
        background: inherit;
        opacity: 0.5;
        animation: pulse-glow 2s ease-in-out infinite;
    }
    
    /* Header Animations */
    .header-content {
        animation: bounceIn 1s ease-out;
    }
    
    .gradient-text {
        background: linear-gradient(135deg, #FC8EAC, #EC769A, #6EC6C5, #5AB8B7);
        background-size: 300% 300%;
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        animation: gradient-shift 5s ease infinite;
    }
    
    /* Glassmorphism Effect */
    .glass-card {
        background: rgba(255, 255, 255, 0.85);
        backdrop-filter: blur(20px);
        -webkit-backdrop-filter: blur(20px);
        border: 1px solid rgba(255, 255, 255, 0.3);
    }
    
    /* Shimmer Effect */
    .shimmer {
        background: linear-gradient(
            90deg,
            transparent,
            rgba(255, 255, 255, 0.6),
            transparent
        );
        background-size: 200% 100%;
        animation: shimmer 2s infinite;
    }
    
    /* Icon Animations */
    .icon-bounce {
        animation: float-gentle 3s ease-in-out infinite;
    }
    
    .icon-rotate {
        animation: rotate360 20s linear infinite;
    }
    
    /* Stats Card */
    .stat-card {
        animation: scale-in 0.5s ease-out;
        animation-fill-mode: both;
        transition: all 0.3s ease;
    }
    
    .stat-card:hover {
        transform: scale(1.1) rotate(2deg);
    }
    
    .stat-card:nth-child(1) { animation-delay: 0.1s; }
    .stat-card:nth-child(2) { animation-delay: 0.2s; }
    .stat-card:nth-child(3) { animation-delay: 0.3s; }
    
    /* Empty State Animation */
    .empty-state-icon {
        animation: bounceIn 1s ease-out, float-gentle 3s ease-in-out infinite 1s;
    }
    
    /* Button Hover Effects */
    .btn-glow {
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }
    
    .btn-glow::before {
        content: '';
        position: absolute;
        top: 50%;
        left: 50%;
        width: 0;
        height: 0;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.5);
        transform: translate(-50%, -50%);
        transition: width 0.6s, height 0.6s;
    }
    
    .btn-glow:hover::before {
        width: 300px;
        height: 300px;
    }
    
    .btn-glow:hover {
        animation: glow-pulse 1.5s ease-in-out infinite;
    }
    
    /* Fade in animation for events */
    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(-10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    .animate-fade-in {
        animation: fadeIn 0.3s ease-in-out;
    }
    
    /* Smooth transition for hidden events */
    .hidden-events-0,
    .hidden-events-1,
    .hidden-events-2 {
        transition: opacity 0.3s ease-in-out, max-height 0.3s ease-in-out;
    }
</style>

<div class="min-h-screen relative overflow-hidden bg-gradient-to-br from-pink-50 via-white to-cyan-50">
    <!-- Animated Background -->
    <div class="animated-bg">
        <div class="floating-shape"></div>
        <div class="floating-shape"></div>
        <div class="floating-shape"></div>
        <div class="floating-shape"></div>
    </div>
    
    <!-- Sparkle Particles -->
    <div id="sparkles-container"></div>
    
    <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Animated Header -->
        <div class="header-content mb-10">
            <div class="relative bg-gradient-to-r from-[#FC8EAC] via-[#EC769A] to-[#6EC6C5] shadow-2xl rounded-3xl p-8 overflow-hidden transform transition-all duration-500 hover:scale-[1.02]">
                <!-- Shimmer Overlay -->
                <div class="absolute inset-0 shimmer"></div>
                
                <!-- Decorative Elements -->
                <div class="absolute top-4 right-4 w-32 h-32 bg-white/20 rounded-full blur-2xl icon-rotate"></div>
                <div class="absolute bottom-4 left-4 w-24 h-24 bg-cyan-300/30 rounded-full blur-xl icon-bounce"></div>
                
                <div class="relative flex flex-col lg:flex-row items-center justify-between gap-6">
                    <div class="flex-1 text-center lg:text-left">
                        <div class="inline-flex items-center gap-3 bg-white/30 backdrop-blur-md px-6 py-3 rounded-full mb-4 border-2 border-white/50 shadow-lg transform hover:scale-105 transition">
                            <div class="w-3 h-3 bg-pink-400 rounded-full animate-pulse"></div>
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white icon-bounce" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            <span class="text-white font-black text-sm tracking-wider">MY SCHEDULE</span>
                        </div>
                        <h1 class="text-5xl lg:text-6xl font-black text-white mb-3 tracking-tight">
                            <span class="inline-block icon-bounce">📅</span> Today's Journey
                        </h1>
                        <p class="text-pink-100 text-xl font-bold flex items-center gap-2">
                            <span class="w-2 h-2 bg-pink-300 rounded-full animate-pulse"></span>
                            {{ $today->format('l, F d, Y') }}
                        </p>
                    </div>
                    <a href="{{ route('student.dashboard') }}" class="btn-glow bg-white/20 hover:bg-white/30 backdrop-blur-md text-white px-8 py-4 rounded-2xl font-bold transition transform hover:scale-110 border-2 border-white/30 shadow-xl relative z-10">
                        <span class="relative z-10 flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                            </svg>
                            Back to Dashboard
                        </span>
                    </a>
                </div>
            </div>
        </div>

        @if($events->count() > 0)
            <!-- Quick Info Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-10">
                <!-- Next Event Countdown -->
                @if($nextEvent)
                <div class="stat-card glass-card rounded-2xl p-6 shadow-xl border-2 border-pink-200/50">
                    <div class="flex items-center justify-between mb-3">
                        <p class="text-gray-600 font-semibold text-sm">Next Event</p>
                        <div class="w-3 h-3 bg-green-500 rounded-full animate-pulse"></div>
                    </div>
                    <p class="text-2xl font-black gradient-text mb-2 truncate">{{ $nextEvent['title'] }}</p>
                    <p class="text-lg font-black text-pink-600 mb-1">{{ $nextEvent['event_time'] }}</p>
                    <p class="text-xs text-gray-600 font-bold">In {{ $nextEvent['time_until'] }}</p>
                </div>
                @endif
                
                <!-- Total Events Today -->
                <div class="stat-card glass-card rounded-2xl p-6 shadow-xl border-2 border-cyan-200/50">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-600 font-semibold mb-2 text-sm">Total Today</p>
                            <p class="text-4xl font-black gradient-text">{{ $events->count() }}</p>
                        </div>
                        <div class="w-12 h-12 bg-gradient-to-br from-cyan-400 to-teal-400 rounded-xl flex items-center justify-center icon-bounce">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        </div>
                    </div>
                </div>
                
                <!-- Upcoming Count -->
                <div class="stat-card glass-card rounded-2xl p-6 shadow-xl border-2 border-rose-200/50">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-600 font-semibold mb-2 text-sm">Upcoming</p>
                            <p class="text-4xl font-black gradient-text">{{ $events->filter(function($event) { return ($event['is_upcoming'] ?? false) === true; })->count() }}</p>
                        </div>
                        <div class="w-12 h-12 bg-gradient-to-br from-rose-400 to-pink-400 rounded-xl flex items-center justify-center icon-bounce" style="animation-delay: 0.5s;">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                    </div>
                </div>
                
                <!-- Completed Count -->
                <div class="stat-card glass-card rounded-2xl p-6 shadow-xl border-2 border-teal-200/50">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-600 font-semibold mb-2 text-sm">Completed</p>
                            <p class="text-4xl font-black gradient-text">{{ $events->filter(function($event) { return ($event['is_past'] ?? false) === true; })->count() }}</p>
                        </div>
                        <div class="w-12 h-12 bg-gradient-to-br from-teal-400 to-cyan-400 rounded-xl flex items-center justify-center icon-bounce" style="animation-delay: 1s;">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Events Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-10">
                @foreach($events as $index => $event)
                    <div class="event-card glass-card rounded-3xl p-6 border-2 border-white/50 shadow-2xl relative" 
                         style="border-left: 6px solid {{ $event['color'] ?? '#FC8EAC' }};">
                        <!-- Status Badge with 5-Minute Countdown -->
                        @if($event['is_upcoming'] ?? false && isset($event['event_datetime_iso']))
                            <div class="absolute top-4 right-4 bg-gradient-to-r from-green-500 to-emerald-500 text-white px-3 py-1 rounded-full text-xs font-black shadow-lg z-10">
                                <span class="countdown-5min" 
                                      data-target-time="{{ $event['event_datetime_iso'] }}"
                                      data-update-interval="{{ $event['seconds_until_next_update'] ?? 300 }}">
                                    ⏳ In {{ $event['time_until'] }}
                                </span>
                            </div>
                        @endif
                        
                        <!-- Event Header -->
                        <div class="flex items-start justify-between mb-4 pr-20">
                            <div class="flex-1">
                                <h3 class="text-2xl font-black text-gray-800 mb-3 leading-tight">{{ $event['title'] }}</h3>
                                @if($event['event_type'])
                                    <span class="inline-block px-4 py-1.5 text-xs font-black rounded-full bg-gradient-to-r from-pink-100 to-cyan-100 text-gray-700 border border-pink-200 transform hover:scale-110 transition">
                                        {{ ucfirst($event['event_type']) }}
                                    </span>
                                @endif
                            </div>
                        </div>

                        <!-- Event Details -->
                        <div class="space-y-4">
                            @if($event['event_time'] || $event['event_date_display'])
                                <div class="flex items-center gap-3 p-3 bg-gradient-to-r from-pink-50 to-cyan-50 rounded-xl border border-pink-100">
                                    <div class="w-10 h-10 bg-gradient-to-br from-pink-400 to-rose-400 rounded-xl flex items-center justify-center icon-bounce">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                    </div>
                                    <div class="flex-1">
                                        @if($event['event_date_display'])
                                            <span class="font-black text-gray-800 text-base block mb-1">{{ $event['event_date_display'] }}</span>
                                        @endif
                                        @if($event['event_time'])
                                            <span class="font-black text-gray-800 text-lg block">{{ $event['event_time'] }}</span>
                                        @endif
                                        @if($event['is_upcoming'] && isset($event['event_datetime_iso']))
                                            <span class="text-xs text-green-600 font-bold countdown-5min-inline" 
                                                  data-target-time="{{ $event['event_datetime_iso'] }}">
                                                Starts in {{ $event['time_until'] }}
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            @endif

                            @if($event['teacher_name'])
                                <div class="flex items-center gap-3 p-3 bg-gradient-to-r from-cyan-50 to-teal-50 rounded-xl border border-cyan-100">
                                    <div class="w-10 h-10 bg-gradient-to-br from-cyan-400 to-teal-400 rounded-xl flex items-center justify-center icon-bounce" style="animation-delay: 0.3s;">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                        </svg>
                                    </div>
                                    <span class="font-black text-gray-800">{{ $event['teacher_name'] }}</span>
                                </div>
                            @endif

                            @if($event['description'])
                                <div class="pt-3 border-t-2 border-gray-200">
                                    <p class="text-sm text-gray-700 leading-relaxed font-medium">{{ $event['description'] }}</p>
                                </div>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Next 3 Days Preview -->
            @if(count($next3Days) > 0)
            <div class="glass-card rounded-3xl shadow-2xl p-8 border-2 border-white/50 mb-10">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-12 h-12 bg-gradient-to-br from-pink-400 to-cyan-400 rounded-2xl flex items-center justify-center icon-bounce">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <h2 class="text-3xl font-black gradient-text">Upcoming Days Preview</h2>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    @foreach($next3Days as $index => $day)
                        <div class="bg-gradient-to-br from-pink-50/80 via-white to-cyan-50/80 rounded-2xl p-5 border-2 border-pink-200/50 hover:border-pink-300 transition-all">
                            <div class="flex items-center justify-between mb-3">
                                <div>
                                    <p class="text-xs font-black text-gray-600 uppercase">{{ $day['day_name'] }}</p>
                                    <p class="text-lg font-black text-gray-800">{{ $day['date_formatted'] }}</p>
                                </div>
                                <div class="w-10 h-10 bg-gradient-to-br from-pink-400 to-cyan-400 rounded-xl flex items-center justify-center">
                                    <span class="text-white font-black text-sm">{{ $day['count'] }}</span>
                                </div>
                            </div>
                            <div class="space-y-2">
                                <!-- Visible events (first 2) -->
                                <div class="visible-events-{{ $index }}">
                                    @foreach($day['events']->take(2) as $event)
                                        <div class="flex items-center gap-2 text-sm">
                                            <div class="w-2 h-2 rounded-full" style="background-color: {{ $event['color'] ?? '#FC8EAC' }};"></div>
                                            <span class="font-bold text-gray-700 truncate">{{ $event['title'] }}</span>
                                            @if($event['event_time'])
                                                <span class="text-xs text-gray-500 font-semibold ml-auto">{{ $event['event_time'] }}</span>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                                
                                <!-- Hidden events (rest) -->
                                @if($day['count'] > 2)
                                    <div class="hidden-events-{{ $index }} space-y-2" style="display: none; transition: all 0.3s ease-in-out;">
                                        @foreach($day['events']->skip(2) as $event)
                                            <div class="flex items-center gap-2 text-sm animate-fade-in">
                                                <div class="w-2 h-2 rounded-full" style="background-color: {{ $event['color'] ?? '#FC8EAC' }};"></div>
                                                <span class="font-bold text-gray-700 truncate">{{ $event['title'] }}</span>
                                                @if($event['event_time'])
                                                    <span class="text-xs text-gray-500 font-semibold ml-auto">{{ $event['event_time'] }}</span>
                                                @endif
                                            </div>
                                        @endforeach
                                    </div>
                                    
                                    <!-- Toggle Button -->
                                    <button onclick="toggleEvents({{ $index }})" 
                                            class="toggle-btn-{{ $index }} w-full mt-3 px-4 py-2 bg-gradient-to-r from-pink-400 to-cyan-400 hover:from-pink-500 hover:to-cyan-500 text-white text-xs font-bold rounded-lg transition-all transform hover:scale-105 shadow-md flex items-center justify-center gap-2">
                                        <svg class="w-4 h-4 see-all-icon-{{ $index }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                        </svg>
                                        <svg class="w-4 h-4 see-less-icon-{{ $index }}" style="display: none;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path>
                                        </svg>
                                        <span class="see-all-text-{{ $index }}">See All ({{ $day['count'] - 2 }} more)</span>
                                        <span class="see-less-text-{{ $index }}" style="display: none;">Show Less</span>
                                    </button>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            @endif
        @else
            <!-- Empty State with Animation -->
            <div class="glass-card rounded-3xl shadow-2xl p-16 text-center border-2 border-white/50">
                <div class="max-w-md mx-auto">
                    <div class="empty-state-icon w-32 h-32 mx-auto mb-8 bg-gradient-to-br from-pink-200 to-cyan-200 rounded-full flex items-center justify-center shadow-2xl">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 text-pink-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <h3 class="text-3xl font-black gradient-text mb-4">No Schedule Events Today</h3>
                    <p class="text-gray-600 mb-8 text-lg font-medium">
                        You don't have any scheduled events for today. Check back later or contact your teacher for upcoming schedules.
                    </p>
                    <a href="{{ route('student.dashboard') }}" class="btn-glow inline-block bg-gradient-to-r from-pink-400 via-rose-400 to-cyan-400 hover:from-pink-500 hover:via-rose-500 hover:to-cyan-500 text-white font-black py-4 px-8 rounded-2xl transition transform hover:scale-110 shadow-2xl relative overflow-hidden">
                        <span class="relative z-10 flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                            </svg>
                            Go to Dashboard
                        </span>
                    </a>
                </div>
            </div>
        @endif
    </div>
</div>

<script>
    // Create sparkle particles
    function createSparkles() {
        const container = document.getElementById('sparkles-container');
        const sparkleCount = 20;
        
        for (let i = 0; i < sparkleCount; i++) {
            const sparkle = document.createElement('div');
            sparkle.className = 'sparkle';
            sparkle.style.left = Math.random() * 100 + '%';
            sparkle.style.top = Math.random() * 100 + '%';
            sparkle.style.animationDelay = Math.random() * 3 + 's';
            sparkle.style.animationDuration = (Math.random() * 2 + 2) + 's';
            container.appendChild(sparkle);
        }
    }
    
    // Format time remaining (rounded to 5 minutes)
    function formatTimeRemaining(minutes) {
        if (minutes <= 0) {
            return 'Starting now';
        }
        
        // Round down to nearest 5 minutes
        const roundedMinutes = Math.floor(minutes / 5) * 5;
        
        if (roundedMinutes >= 60) {
            const hours = Math.floor(roundedMinutes / 60);
            const mins = roundedMinutes % 60;
            if (mins > 0) {
                return hours + ' hour' + (hours > 1 ? 's' : '') + ' ' + mins + ' minute' + (mins > 1 ? 's' : '');
            } else {
                return hours + ' hour' + (hours > 1 ? 's' : '');
            }
        } else {
            return roundedMinutes + ' minute' + (roundedMinutes > 1 ? 's' : '');
        }
    }
    
    // Update countdown timers (5-minute intervals)
    function updateCountdowns() {
        const countdownElements = document.querySelectorAll('.countdown-5min, .countdown-5min-inline');
        
        countdownElements.forEach(function(element) {
            const targetTime = element.getAttribute('data-target-time');
            if (!targetTime) return;
            
            const target = new Date(targetTime);
            const now = new Date();
            const diffMs = target - now;
            
            if (diffMs <= 0) {
                element.textContent = '⏳ Starting now';
                return;
            }
            
            const diffMinutes = Math.floor(diffMs / 60000);
            const formatted = formatTimeRemaining(diffMinutes);
            
            if (element.classList.contains('countdown-5min-inline')) {
                element.textContent = 'Starts in ' + formatted;
            } else {
                element.textContent = '⏳ In ' + formatted;
            }
        });
    }
    
    // Toggle events visibility for upcoming days preview
    function toggleEvents(index) {
        const hiddenEvents = document.querySelector('.hidden-events-' + index);
        const seeAllText = document.querySelector('.see-all-text-' + index);
        const seeLessText = document.querySelector('.see-less-text-' + index);
        const seeAllIcon = document.querySelector('.see-all-icon-' + index);
        const seeLessIcon = document.querySelector('.see-less-icon-' + index);
        
        if (hiddenEvents.style.display === 'none' || hiddenEvents.style.display === '') {
            // Show all events with smooth animation
            hiddenEvents.style.display = 'block';
            hiddenEvents.style.opacity = '0';
            setTimeout(() => {
                hiddenEvents.style.opacity = '1';
            }, 10);
            seeAllText.style.display = 'none';
            seeLessText.style.display = 'inline';
            if (seeAllIcon) seeAllIcon.style.display = 'none';
            if (seeLessIcon) seeLessIcon.style.display = 'block';
        } else {
            // Hide extra events with smooth animation
            hiddenEvents.style.opacity = '0';
            setTimeout(() => {
                hiddenEvents.style.display = 'none';
            }, 300);
            seeAllText.style.display = 'inline';
            seeLessText.style.display = 'none';
            if (seeAllIcon) seeAllIcon.style.display = 'block';
            if (seeLessIcon) seeLessIcon.style.display = 'none';
        }
    }
    
    // Initialize on page load
    document.addEventListener('DOMContentLoaded', function() {
        createSparkles();
        
        // Add stagger animation to cards
        const cards = document.querySelectorAll('.event-card');
        cards.forEach((card, index) => {
            card.style.animationDelay = (index * 0.1) + 's';
        });
        
        // Update countdowns immediately
        updateCountdowns();
        
        // Update countdowns every 5 minutes (300000 ms)
        setInterval(updateCountdowns, 300000);
        
        // Also update every minute to catch the 5-minute boundary more accurately
        setInterval(function() {
            const now = new Date();
            const seconds = now.getSeconds();
            // Update when we cross a 5-minute boundary (at :00, :05, :10, etc.)
            if (seconds === 0) {
                const minutes = now.getMinutes();
                if (minutes % 5 === 0) {
                    updateCountdowns();
                }
            }
        }, 1000);
    });
</script>
@endsection
