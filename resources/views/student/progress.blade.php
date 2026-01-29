@extends('layouts.app')

@section('content')
<style>
    @keyframes float {
        0%, 100% { transform: translateY(0px); }
        50% { transform: translateY(-20px); }
    }
    @keyframes pulse-glow {
        0%, 100% { opacity: 0.6; }
        50% { opacity: 1; }
    }
    @keyframes shimmer {
        0% { background-position: -1000px 0; }
        100% { background-position: 1000px 0; }
    }
    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }
    @keyframes slideInRight {
        from { 
            opacity: 0;
            transform: translateX(50px);
        }
        to { 
            opacity: 1;
            transform: translateX(0);
        }
    }
    @keyframes slideInLeft {
        from { 
            opacity: 0;
            transform: translateX(-50px);
        }
        to { 
            opacity: 1;
            transform: translateX(0);
        }
    }
    @keyframes rotate360 {
        from { transform: rotate(0deg); }
        to { transform: rotate(360deg); }
    }
    @keyframes heartbeat {
        0%, 100% { transform: scale(1); }
        25% { transform: scale(1.1); }
        50% { transform: scale(1); }
        75% { transform: scale(1.05); }
    }
    @keyframes shake {
        0%, 100% { transform: translateX(0); }
        25% { transform: translateX(-5px); }
        75% { transform: translateX(5px); }
    }
    @keyframes zoomIn {
        from { 
            opacity: 0;
            transform: scale(0.5);
        }
        to { 
            opacity: 1;
            transform: scale(1);
        }
    }
    @keyframes gradientShift {
        0% { background-position: 0% 50%; }
        50% { background-position: 100% 50%; }
        100% { background-position: 0% 50%; }
    }
    @keyframes floatRotate {
        0%, 100% { transform: translateY(0px) rotate(0deg); }
        50% { transform: translateY(-15px) rotate(5deg); }
    }
    @keyframes slideInUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    @keyframes bounceIn {
        0% { transform: scale(0.3); opacity: 0; }
        50% { transform: scale(1.05); }
        70% { transform: scale(0.9); }
        100% { transform: scale(1); opacity: 1; }
    }
    @keyframes countUp {
        from { opacity: 0; transform: scale(0.5); }
        to { opacity: 1; transform: scale(1); }
    }
    @keyframes rotate {
        from { transform: rotate(0deg); }
        to { transform: rotate(360deg); }
    }
    .stat-card {
        animation: slideInUp 0.6s ease-out;
        animation-fill-mode: both;
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .stat-card:nth-child(1) { animation-delay: 0.1s; }
    .stat-card:nth-child(2) { animation-delay: 0.2s; }
    .stat-card:nth-child(3) { animation-delay: 0.3s; }
    .stat-card:hover {
        transform: translateY(-12px) scale(1.08) rotate(1deg);
        box-shadow: 0 25px 50px rgba(236, 118, 154, 0.4);
    }
    .stat-icon {
        transition: transform 0.3s ease;
    }
    .stat-card:hover .stat-icon {
        transform: scale(1.2) rotate(10deg);
    }
    .stat-icon {
        animation: bounceIn 0.8s ease-out;
    }
    .counter-number {
        animation: countUp 1s ease-out;
    }
    .floating-heart {
        animation: float 6s ease-in-out infinite;
    }
    .pulse-glow {
        animation: pulse-glow 2s ease-in-out infinite;
    }
    .animate-fadeIn {
        animation: fadeIn 0.8s ease-out;
    }
    .animate-slideInRight {
        animation: slideInRight 0.6s ease-out;
    }
    .animate-slideInLeft {
        animation: slideInLeft 0.6s ease-out;
    }
    .animate-zoomIn {
        animation: zoomIn 0.5s ease-out;
    }
    .animate-heartbeat {
        animation: heartbeat 2s ease-in-out infinite;
    }
    .animate-floatRotate {
        animation: floatRotate 4s ease-in-out infinite;
    }
    .gradient-animate {
        background-size: 200% 200%;
        animation: gradientShift 3s ease infinite;
    }
    .animate-shimmer {
        animation: shimmer 2s linear infinite;
    }
    .animate-sparkle {
        animation: sparkle 2s ease-in-out infinite;
    }
    .pattern-bg {
        background-image: 
            radial-gradient(circle at 20% 50%, rgba(252, 142, 172, 0.1) 0%, transparent 50%),
            radial-gradient(circle at 80% 80%, rgba(110, 198, 197, 0.1) 0%, transparent 50%),
            radial-gradient(circle at 40% 20%, rgba(236, 118, 154, 0.08) 0%, transparent 50%);
        background-size: 100% 100%;
    }
    .lesson-card {
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
        overflow: hidden;
    }
    .lesson-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255,255,255,0.5), transparent);
        transition: left 0.6s ease;
    }
    .lesson-card:hover::before {
        left: 100%;
    }
    .lesson-card:hover {
        transform: translateY(-8px) scale(1.03) rotate(1deg);
        box-shadow: 0 25px 50px rgba(236, 118, 154, 0.4);
    }
    .lesson-journey-card {
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .progress-ring {
        transform: rotate(-90deg);
    }
    @keyframes wave {
        0%, 100% { transform: translateX(0) translateY(0); }
        50% { transform: translateX(-25px) translateY(-10px); }
    }
    @keyframes sparkle {
        0%, 100% { opacity: 0; transform: scale(0); }
        50% { opacity: 1; transform: scale(1); }
    }
    @keyframes scaleIn {
        from { opacity: 0; transform: scale(0.8); }
        to { opacity: 1; transform: scale(1); }
    }
    @keyframes currentPulse {
        0%, 100% { 
            transform: scale(1);
            box-shadow: 0 0 0 0 rgba(252, 142, 172, 0.7);
        }
        50% { 
            transform: scale(1.05);
            box-shadow: 0 0 0 10px rgba(252, 142, 172, 0);
        }
    }
    @keyframes currentGlow {
        0%, 100% { 
            box-shadow: 0 0 20px rgba(252, 142, 172, 0.5), 0 0 40px rgba(110, 198, 197, 0.3);
        }
        50% { 
            box-shadow: 0 0 30px rgba(252, 142, 172, 0.8), 0 0 60px rgba(110, 198, 197, 0.5);
        }
    }
    @keyframes bounce {
        0%, 100% { transform: translateY(0); }
        50% { transform: translateY(-8px); }
    }
    @keyframes wiggle {
        0%, 100% { transform: rotate(0deg); }
        25% { transform: rotate(-5deg); }
        75% { transform: rotate(5deg); }
    }
    .current-level-badge {
        animation: currentGlow 2s ease-in-out infinite;
        border: 3px solid #FC8EAC !important;
    }
    .current-lesson-badge {
        animation: currentPulse 2s ease-in-out infinite;
        position: relative;
    }
    .current-lesson-badge::before {
        content: '📍';
        position: absolute;
        top: -8px;
        right: -8px;
        font-size: 1.2rem;
        animation: bounce 1.5s ease-in-out infinite;
        z-index: 20;
    }
    .current-tag {
        animation: wiggle 1s ease-in-out infinite;
        background: linear-gradient(135deg, #FC8EAC, #EC769A, #6EC6C5) !important;
    }
    .level-milestone {
        position: relative;
        animation: slideInUp 0.6s ease-out;
        animation-fill-mode: both;
    }
    .lesson-journey-card {
        animation: scaleIn 0.4s ease-out;
        animation-fill-mode: both;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .lesson-journey-card:hover {
        transform: translateX(6px) scale(1.02);
        border-color: #FC8EAC !important;
        box-shadow: 0 10px 25px rgba(252, 142, 172, 0.3);
    }
    .level-milestone {
        transition: transform 0.3s ease;
    }
    .level-milestone:hover {
        transform: scale(1.02);
    }
    /* Custom Scrollbar */
    .overflow-y-auto::-webkit-scrollbar {
        width: 6px;
    }
    .overflow-y-auto::-webkit-scrollbar-track {
        background: rgba(252, 142, 172, 0.1);
        border-radius: 10px;
    }
    .overflow-y-auto::-webkit-scrollbar-thumb {
        background: linear-gradient(180deg, #FC8EAC, #6EC6C5);
        border-radius: 10px;
    }
    .overflow-y-auto::-webkit-scrollbar-thumb:hover {
        background: linear-gradient(180deg, #EC769A, #197D8C);
    }
    /* Horizontal Scrollbar */
    .overflow-x-auto::-webkit-scrollbar {
        height: 8px;
    }
    .overflow-x-auto::-webkit-scrollbar-track {
        background: rgba(252, 142, 172, 0.1);
        border-radius: 10px;
    }
    .overflow-x-auto::-webkit-scrollbar-thumb {
        background: linear-gradient(90deg, #FC8EAC, #6EC6C5);
        border-radius: 10px;
    }
    .line-clamp-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    .pattern-dots {
        background-image: radial-gradient(circle, rgba(252, 142, 172, 0.2) 1px, transparent 1px);
        background-size: 30px 30px;
    }
    .pattern-waves {
        background-image: 
            repeating-linear-gradient(45deg, transparent, transparent 10px, rgba(110, 198, 197, 0.03) 10px, rgba(110, 198, 197, 0.03) 20px);
    }
    .gradient-text {
        background: linear-gradient(135deg, #FC8EAC 0%, #EC769A 50%, #6EC6C5 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }
    .completed-badge {
        background: linear-gradient(135deg, #10b981, #34d399);
        box-shadow: 0 4px 15px rgba(16, 185, 129, 0.4);
    }
    .in-progress-badge {
        background: linear-gradient(135deg, #F59E0B, #FBBF24);
        box-shadow: 0 4px 15px rgba(245, 158, 11, 0.4);
    }
    .not-started-badge {
        background: linear-gradient(135deg, #6B7280, #9CA3AF);
        box-shadow: 0 4px 15px rgba(107, 114, 128, 0.3);
    }
</style>

<div class="min-h-screen relative overflow-hidden" style="background: linear-gradient(135deg, #FFF4FA 0%, #F0F9FF 50%, #FFF4FA 100%);">
    <!-- Decorative Pattern Background -->
    <div class="absolute inset-0 pattern-bg pattern-dots pattern-waves"></div>
    
    <!-- Floating Decorative Elements -->
    <div class="absolute top-20 left-10 floating-heart text-6xl opacity-20 animate-floatRotate" style="color: #FC8EAC;">💖</div>
    <div class="absolute top-40 right-20 floating-heart text-5xl opacity-20 animate-heartbeat" style="animation-delay: 1s; color: #6EC6C5;">✨</div>
    <div class="absolute bottom-40 left-20 floating-heart text-4xl opacity-20 animate-floatRotate" style="animation-delay: 2s; color: #EC769A;">🌸</div>
    <div class="absolute bottom-20 right-40 floating-heart text-5xl opacity-20 animate-heartbeat" style="animation-delay: 0.5s; color: #6EC6C5;">🌟</div>
    <div class="absolute top-60 right-40 text-3xl opacity-15 floating-heart animate-zoomIn" style="animation-delay: 1.5s; color: #FC8EAC;">💫</div>
    <div class="absolute bottom-60 left-40 text-4xl opacity-15 floating-heart animate-floatRotate" style="animation-delay: 0.8s; color: #6EC6C5;">⭐</div>
    
    <div class="relative z-10 w-full max-w-full mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <!-- Header Section -->
        <div class="text-center mb-12 animate-fadeIn">
            <div class="inline-block mb-6 animate-zoomIn">
                <h1 class="text-6xl md:text-7xl font-extrabold mb-4 gradient-text gradient-animate">
                    My Progress Journey
                </h1>
                <div class="h-1 w-32 mx-auto rounded-full animate-slideInRight" style="background: linear-gradient(90deg, #FC8EAC, #6EC6C5); animation-delay: 0.3s;"></div>
            </div>
            <p class="text-xl text-gray-600 font-medium animate-fadeIn" style="animation-delay: 0.5s;">Track your learning adventure step by step! ✨</p>
        </div>
        
        <!-- Overall Progress Tracking Line -->
        <div class="w-full max-w-full mx-auto mb-16 px-4">
            <div class="bg-white/95 backdrop-blur-xl rounded-3xl shadow-2xl p-6 border-2 border-pink-200/50 animate-fadeIn"
                 style="background: linear-gradient(135deg, rgba(255,255,255,0.98) 0%, rgba(255,244,250,0.95) 100%); animation-delay: 0.6s;">
                <div class="flex flex-col md:flex-row items-center justify-between gap-6">
                    <!-- Progress Info -->
                    <div class="flex-1 w-full">
                        <div class="flex items-center justify-between mb-3">
                            <h2 class="text-2xl md:text-3xl font-extrabold gradient-text">
                                Overall Progress
                            </h2>
                            <div class="text-right">
                                <div class="text-3xl md:text-4xl font-black gradient-text animate-heartbeat">
                                    {{ $overallProgress ?? 0 }}%
                                </div>
                                <div class="text-sm text-gray-600 font-semibold">
                                    {{ $completedLessons ?? 0 }} / {{ $totalLessons ?? 0 }} Lessons
                                </div>
                            </div>
                        </div>
                        
                        <!-- Progress Bar -->
                        <div class="relative h-6 bg-gray-200 rounded-full overflow-visible shadow-inner">
                            <div class="h-full rounded-full transition-all duration-1000 ease-out gradient-animate"
                                 style="width: {{ $overallProgress ?? 0 }}%; background: linear-gradient(90deg, #FC8EAC 0%, #EC769A 50%, #6EC6C5 100%); background-size: 200% 100%;">
                                <div class="absolute inset-0 bg-gradient-to-r from-transparent via-white/30 to-transparent animate-shimmer"></div>
                            </div>
                            <!-- Progress Milestones -->
                            <div class="absolute inset-0 flex items-center">
                                @for($i = 0; $i <= 100; $i += 25)
                                    <div class="flex-1 flex justify-center">
                                        @if($overallProgress >= $i)
                                            <div class="w-3 h-3 rounded-full bg-white shadow-lg animate-zoomIn" style="animation-delay: {{ $i * 0.02 }}s;"></div>
                                        @else
                                            <div class="w-2 h-2 rounded-full bg-gray-300"></div>
                                        @endif
                                    </div>
                                @endfor
                            </div>
                            <!-- Girl Character at Progress End -->
                            <div class="absolute top-1/2 transform -translate-y-1/2 -translate-x-1/2 transition-all duration-1000 ease-out z-20"
                                 style="left: {{ $overallProgress ?? 0 }}%;">
                                <div class="relative animate-floatRotate">
                                    <img src="{{ asset('images/student-girl.png') }}" 
                                         alt="Student Progress" 
                                         class="w-24 h-24 md:w-28 md:h-28 object-contain drop-shadow-2xl"
                                         style="filter: drop-shadow(0 6px 12px rgba(252, 142, 172, 0.4)); animation: bounce 2s ease-in-out infinite;"
                                         onerror="this.style.display='none';">
                                    <!-- Animated sparkles around the girl -->
                                    <div class="absolute -top-2 -left-2 text-lg animate-sparkle" style="animation-delay: 0s;">✨</div>
                                    <div class="absolute -top-2 -right-2 text-lg animate-sparkle" style="animation-delay: 0.5s;">⭐</div>
                                    <div class="absolute -bottom-2 left-1/2 transform -translate-x-1/2 text-base animate-sparkle" style="animation-delay: 1s;">💫</div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Progress Steps -->
                        <div class="flex justify-between mt-2 text-xs font-semibold text-gray-600">
                            <span>0%</span>
                            <span>25%</span>
                            <span>50%</span>
                            <span>75%</span>
                            <span>100%</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Statistics Cards -->
        <div class="w-full max-w-full mx-auto mb-16">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <!-- Games Stat Card -->
                <div class="stat-card bg-white/90 backdrop-blur-lg rounded-2xl shadow-xl p-6 border-2 border-pink-200/50 relative overflow-hidden"
                     style="background: linear-gradient(135deg, rgba(255,255,255,0.98) 0%, rgba(255,244,250,0.95) 100%);">
                    <div class="absolute top-0 right-0 w-32 h-32 rounded-bl-full opacity-10"
                         style="background: linear-gradient(135deg, #FC8EAC, #6EC6C5);"></div>
                    <div class="relative z-10">
                        <div class="flex items-center justify-between mb-4">
                            <div class="w-16 h-16 rounded-full flex items-center justify-center text-3xl stat-icon"
                                 style="background: linear-gradient(135deg, #FC8EAC, #EC769A);">
                                🎮
                            </div>
                            <div class="text-right">
                                <div class="text-3xl font-extrabold counter-number gradient-text">
                                    {{ $gamesStats['completed'] ?? 0 }}
                                </div>
                                <div class="text-sm text-gray-600 font-semibold">Completed</div>
                            </div>
                        </div>
                        <h3 class="text-xl font-bold text-gray-800 mb-2">Games</h3>
                        <div class="space-y-2">
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Total Played:</span>
                                <span class="font-bold text-gray-800">{{ $gamesStats['total'] ?? 0 }}</span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">In Progress:</span>
                                <span class="font-bold" style="color: #F59E0B;">{{ $gamesStats['in_progress'] ?? 0 }}</span>
                            </div>
                            @if(isset($gamesStats['average_score']) && $gamesStats['average_score'] > 0)
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Avg Score:</span>
                                <span class="font-bold gradient-text">{{ number_format($gamesStats['average_score'], 0) }}</span>
                            </div>
                            @endif
                        </div>
                        <div class="mt-4 h-2 bg-gray-200 rounded-full overflow-hidden">
                            <div class="h-full rounded-full transition-all duration-500 counter-number"
                                 style="width: {{ $gamesStats['total'] > 0 ? ($gamesStats['completed'] / $gamesStats['total'] * 100) : 0 }}%; background: linear-gradient(90deg, #FC8EAC, #EC769A);">
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Quizzes Stat Card -->
                <div class="stat-card bg-white/90 backdrop-blur-lg rounded-2xl shadow-xl p-6 border-2 border-pink-200/50 relative overflow-hidden"
                     style="background: linear-gradient(135deg, rgba(255,255,255,0.98) 0%, rgba(240,249,255,0.95) 100%);">
                    <div class="absolute top-0 right-0 w-32 h-32 rounded-bl-full opacity-10"
                         style="background: linear-gradient(135deg, #6EC6C5, #197D8C);"></div>
                    <div class="relative z-10">
                        <div class="flex items-center justify-between mb-4">
                            <div class="w-16 h-16 rounded-full flex items-center justify-center text-3xl stat-icon"
                                 style="background: linear-gradient(135deg, #6EC6C5, #197D8C);">
                                📝
                            </div>
                            <div class="text-right">
                                <div class="text-3xl font-extrabold counter-number gradient-text">
                                    {{ $quizzesStats['completed'] ?? 0 }}
                                </div>
                                <div class="text-sm text-gray-600 font-semibold">Completed</div>
                            </div>
                        </div>
                        <h3 class="text-xl font-bold text-gray-800 mb-2">Quizzes</h3>
                        <div class="space-y-2">
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Total Attempts:</span>
                                <span class="font-bold text-gray-800">{{ $quizzesStats['total_attempts'] ?? 0 }}</span>
                            </div>
                            @if(isset($quizzesStats['average_score']) && $quizzesStats['average_score'] > 0)
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Avg Score:</span>
                                <span class="font-bold gradient-text">{{ number_format($quizzesStats['average_score'], 1) }}%</span>
                            </div>
                            @endif
                            @if(isset($quizzesStats['highest_score']) && $quizzesStats['highest_score'] > 0)
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Highest Score:</span>
                                <span class="font-bold" style="color: #10b981;">{{ number_format($quizzesStats['highest_score'], 1) }}%</span>
                            </div>
                            @endif
                        </div>
                        <div class="mt-4 h-2 bg-gray-200 rounded-full overflow-hidden">
                            <div class="h-full rounded-full transition-all duration-500 counter-number"
                                 style="width: {{ $quizzesStats['total_attempts'] > 0 ? ($quizzesStats['completed'] / $quizzesStats['total_attempts'] * 100) : 0 }}%; background: linear-gradient(90deg, #6EC6C5, #197D8C);">
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Assignments Stat Card -->
                <div class="stat-card bg-white/90 backdrop-blur-lg rounded-2xl shadow-xl p-6 border-2 border-pink-200/50 relative overflow-hidden"
                     style="background: linear-gradient(135deg, rgba(255,255,255,0.98) 0%, rgba(255,244,250,0.95) 100%);">
                    <div class="absolute top-0 right-0 w-32 h-32 rounded-bl-full opacity-10"
                         style="background: linear-gradient(135deg, #EC769A, #FC8EAC);"></div>
                    <div class="relative z-10">
                        <div class="flex items-center justify-between mb-4">
                            <div class="w-16 h-16 rounded-full flex items-center justify-center text-3xl stat-icon"
                                 style="background: linear-gradient(135deg, #EC769A, #FC8EAC);">
                                📄
                            </div>
                            <div class="text-right">
                                <div class="text-3xl font-extrabold counter-number gradient-text">
                                    {{ $assignmentsStats['submitted'] ?? 0 }}
                                </div>
                                <div class="text-sm text-gray-600 font-semibold">Submitted</div>
                            </div>
                        </div>
                        <h3 class="text-xl font-bold text-gray-800 mb-2">Assignments</h3>
                        <div class="space-y-2">
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Total:</span>
                                <span class="font-bold text-gray-800">{{ $assignmentsStats['total'] ?? 0 }}</span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Pending:</span>
                                <span class="font-bold" style="color: #F59E0B;">{{ $assignmentsStats['pending'] ?? 0 }}</span>
                            </div>
                            @if(isset($assignmentsStats['average_grade']) && $assignmentsStats['average_grade'] > 0)
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Avg Grade:</span>
                                <span class="font-bold gradient-text">{{ number_format($assignmentsStats['average_grade'], 1) }}%</span>
                            </div>
                            @endif
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Completion:</span>
                                <span class="font-bold gradient-text">{{ $assignmentsStats['completed_percentage'] ?? 0 }}%</span>
                            </div>
                        </div>
                        <div class="mt-4 h-2 bg-gray-200 rounded-full overflow-hidden">
                            <div class="h-full rounded-full transition-all duration-500 counter-number"
                                 style="width: {{ $assignmentsStats['completed_percentage'] ?? 0 }}%; background: linear-gradient(90deg, #EC769A, #FC8EAC);">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Creative Horizontal Inline Display -->
        <div class="max-w-full mx-auto overflow-x-auto pb-8">
            <div class="inline-flex items-start gap-8 px-4 min-w-max">
                @if(isset($levels) && $levels->count() > 0)
                    @php
                        // Determine current lesson (first in_progress, or first not_started after all completed)
                        $currentLessonId = null;
                        $currentLevelIndex = null;
                        foreach($levels as $levelIdx => $levelItem) {
                            foreach($levelItem->lessons as $lessonItem) {
                                if(isset($lessonItem->progress_status) && $lessonItem->progress_status === 'in_progress') {
                                    $currentLessonId = $lessonItem->lesson_id;
                                    $currentLevelIndex = $levelIdx;
                                    break 2;
                                }
                            }
                        }
                        // If no in_progress, find first not_started
                        if(!$currentLessonId) {
                            foreach($levels as $levelIdx => $levelItem) {
                                foreach($levelItem->lessons as $lessonItem) {
                                    if(isset($lessonItem->progress_status) && $lessonItem->progress_status === 'not_started' && !isset($lessonItem->is_completed)) {
                                        $currentLessonId = $lessonItem->lesson_id;
                                        $currentLevelIndex = $levelIdx;
                                        break 2;
                                    }
                                }
                            }
                        }
                    @endphp
                    @foreach($levels as $index => $level)
                        @php
                            $totalLessons = $level->lessons->count();
                            $completedLessons = $level->lessons->filter(function($lesson) {
                                return isset($lesson->is_completed) && $lesson->is_completed;
                            })->count();
                            $progressPercentage = $totalLessons > 0 ? ($completedLessons / $totalLessons) * 100 : 0;
                            $isCurrentLevel = $currentLevelIndex === $index;
                        @endphp
                        
                        <div class="flex-shrink-0 w-80 md:w-96 level-milestone" style="animation-delay: {{ $index * 0.1 }}s;">
                            <!-- Level Card Container -->
                            <div class="bg-white/95 backdrop-blur-xl rounded-3xl shadow-2xl border-2 overflow-hidden relative flex flex-col h-[600px] {{ $isCurrentLevel ? 'current-level-badge border-pink-400' : 'border-pink-200/50' }}"
                                 style="background: linear-gradient(135deg, rgba(255,255,255,0.98) 0%, rgba(255,244,250,0.95) 100%);">
                                
                                <!-- Current Level Tag -->
                                @if($isCurrentLevel)
                                    <div class="absolute top-4 right-4 z-30 current-tag px-3 py-1.5 rounded-full text-xs font-bold text-white shadow-lg flex items-center gap-1">
                                        <span class="text-sm">🎯</span>
                                        <span>You Are Here</span>
                                    </div>
                                @endif
                                
                                <!-- Decorative Top Banner -->
                                <div class="relative h-32 overflow-hidden"
                                     style="background: linear-gradient(135deg, #FC8EAC 0%, #EC769A 50%, #6EC6C5 100%);">
                                    <div class="absolute inset-0 opacity-20">
                                        <div class="absolute top-4 left-4 text-4xl">✨</div>
                                        <div class="absolute top-8 right-6 text-3xl">⭐</div>
                                        <div class="absolute bottom-4 left-1/2 text-3xl transform -translate-x-1/2">💫</div>
                                    </div>
                                    
                                    <!-- Level Badge -->
                                    <div class="relative h-full flex flex-col items-center justify-center text-white z-10">
                                        <div class="text-2xl font-bold mb-1 opacity-90 animate-fadeIn">LEVEL</div>
                                        <div class="text-6xl font-black leading-none animate-heartbeat">{{ $level->level_number ?? $level->level_id }}</div>
                                    </div>
                                    
                                    <!-- Progress Ring Indicator -->
                                    <div class="absolute bottom-0 left-0 right-0 h-2 bg-white/30">
                                        <div class="h-full transition-all duration-500" 
                                             style="width: {{ $progressPercentage }}%; background: rgba(255,255,255,0.9);"></div>
                                    </div>
                                </div>
                                
                                <!-- Level Content -->
                                <div class="p-6 flex flex-col flex-1 min-h-0">
                                    <!-- Level Name -->
                                    <h2 class="text-xl md:text-2xl font-extrabold mb-3 gradient-text text-center line-clamp-2 animate-fadeIn">
                                        {{ $level->level_name }}
                                    </h2>
                                    
                                    <!-- Progress Stats -->
                                    <div class="flex items-center justify-between mb-4 p-3 rounded-xl bg-gradient-to-r from-pink-50 to-turquoise-50 flex-shrink-0 hover:scale-105 transition-transform duration-300">
                                        <div class="text-center flex-1 animate-zoomIn">
                                            <div class="text-xl md:text-2xl font-bold gradient-text animate-heartbeat">{{ round($progressPercentage) }}%</div>
                                            <div class="text-xs text-gray-600 font-semibold">Complete</div>
                                        </div>
                                        <div class="w-px h-8 bg-gray-300 animate-fadeIn"></div>
                                        <div class="text-center flex-1 animate-zoomIn">
                                            <div class="text-xl md:text-2xl font-bold text-gray-800 animate-heartbeat">{{ $completedLessons }}/{{ $totalLessons }}</div>
                                            <div class="text-xs text-gray-600 font-semibold">Lessons</div>
                                        </div>
                                    </div>
                                    
                                    <!-- Lessons List -->
                                    <div class="space-y-3 flex-1 overflow-y-auto pr-2 min-h-0">
                                        @foreach($level->lessons as $lessonIndex => $lesson)
                                            @php
                                                $isCurrentLesson = isset($currentLessonId) && $lesson->lesson_id == $currentLessonId;
                                            @endphp
                                            <div class="lesson-journey-card rounded-xl p-4 border hover:shadow-lg transition-all duration-300 relative overflow-hidden {{ $isCurrentLesson ? 'current-lesson-badge bg-gradient-to-r from-pink-100 to-turquoise-100 border-pink-400 border-2' : 'bg-gradient-to-r from-white to-pink-50/50 border-pink-100' }}"
                                                 style="animation-delay: {{ ($index * 0.1) + ($lessonIndex * 0.05) }}s;">
                                                
                                                <!-- Current Lesson Ribbon -->
                                                @if($isCurrentLesson)
                                                    <div class="absolute top-0 left-0 bg-gradient-to-r from-pink-500 to-turquoise-500 text-white text-xs font-bold px-3 py-1 rounded-br-xl rounded-tl-xl shadow-md z-20">
                                                        CURRENT
                                                    </div>
                                                @endif
                                                
                                                <!-- Decorative Corner -->
                                                <div class="absolute top-0 right-0 w-16 h-16 rounded-bl-full {{ $isCurrentLesson ? 'opacity-20' : 'opacity-10' }}"
                                                     style="background: linear-gradient(135deg, #FC8EAC, #6EC6C5);"></div>
                                                
                                                <div class="relative z-10 flex items-start gap-3 {{ $isCurrentLesson ? 'mt-2' : '' }}">
                                                    <!-- Lesson Number -->
                                                    <div class="flex-shrink-0 w-10 h-10 rounded-full flex items-center justify-center text-sm font-bold text-white shadow-md {{ $isCurrentLesson ? 'ring-2 ring-pink-400 ring-offset-2' : '' }}"
                                                         style="background: linear-gradient(135deg, #FC8EAC, #EC769A);">
                                                        {{ $lessonIndex + 1 }}
                                                    </div>
                                                    
                                                    <!-- Lesson Info -->
                                                    <div class="flex-1 min-w-0">
                                                        <h3 class="text-sm font-bold text-gray-800 leading-tight mb-2 line-clamp-2">
                                                            {{ $lesson->title }}
                                                        </h3>
                                                        
                                                        <!-- Status Badge -->
                                                        @if(isset($lesson->is_completed) && $lesson->is_completed)
                                                            <div class="inline-flex items-center gap-1 px-2 py-1 rounded-full text-xs font-bold text-white completed-badge">
                                                                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                                                </svg>
                                                                Done
                                                            </div>
                                                        @elseif(isset($lesson->progress_status) && $lesson->progress_status === 'in_progress')
                                                            <div class="inline-flex items-center gap-1 px-2 py-1 rounded-full text-xs font-bold text-white in-progress-badge">
                                                                <svg class="w-3 h-3 animate-spin" fill="none" viewBox="0 0 24 24">
                                                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                                                </svg>
                                                                Active
                                                            </div>
                                                        @else
                                                            <div class="inline-flex items-center px-2 py-1 rounded-full text-xs font-bold text-white not-started-badge">
                                                                New
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                                
                                <!-- Connector Arrow (except for last level) -->
                                @if(!$loop->last)
                                    <div class="absolute top-1/2 -right-4 transform -translate-y-1/2 z-20">
                                        <div class="w-8 h-8 rounded-full flex items-center justify-center text-white shadow-lg border-2 border-white"
                                             style="background: linear-gradient(135deg, #FC8EAC, #6EC6C5);">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M9 5l7 7-7 7"/>
                                            </svg>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-20">
                    <div class="inline-block p-8 bg-white/80 backdrop-blur-xl rounded-3xl shadow-2xl border-2 border-pink-200/50">
                        <div class="text-6xl mb-4">📚</div>
                        <p class="text-2xl font-bold text-gray-700 mb-2">No Levels Available Yet</p>
                        <p class="text-gray-600">Your learning journey will begin soon! ✨</p>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
