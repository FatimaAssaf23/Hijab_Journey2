@extends('layouts.app')

@section('content')
<style>
    @keyframes float {
        0%, 100% { transform: translateY(0px) rotate(0deg); }
        50% { transform: translateY(-15px) rotate(5deg); }
    }
    @keyframes slideInFromLeft {
        from { opacity: 0; transform: translateX(-50px) rotate(-5deg); }
        to { opacity: 1; transform: translateX(0) rotate(0deg); }
    }
    @keyframes slideInFromRight {
        from { opacity: 0; transform: translateX(50px) rotate(5deg); }
        to { opacity: 1; transform: translateX(0) rotate(0deg); }
    }
    @keyframes rotate {
        from { transform: rotate(0deg); }
        to { transform: rotate(360deg); }
    }
    @keyframes pulse-glow {
        0%, 100% { box-shadow: 0 0 20px rgba(252, 142, 172, 0.3); }
        50% { box-shadow: 0 0 40px rgba(110, 198, 197, 0.5); }
    }
    @keyframes wave {
        0%, 100% { transform: translateY(0); }
        50% { transform: translateY(-10px); }
    }
    .fade-in {
        animation: slideInFromLeft 0.8s ease-out;
    }
    .float-animation {
        animation: float 4s ease-in-out infinite;
    }
    .rotate-slow {
        animation: rotate 20s linear infinite;
    }
    .pulse-glow {
        animation: pulse-glow 3s ease-in-out infinite;
    }
    .wave-animation {
        animation: wave 2s ease-in-out infinite;
    }
    .class-card {
        transition: all 0.5s cubic-bezier(0.34, 1.56, 0.64, 1);
        position: relative;
        overflow: visible;
        transform-style: preserve-3d;
    }
    .class-card:hover {
        transform: translateY(-12px) rotateY(5deg) scale(1.03);
        z-index: 10;
    }
    .class-card::after {
        content: '';
        position: absolute;
        top: -2px;
        left: -2px;
        right: -2px;
        bottom: -2px;
        background: linear-gradient(45deg, #FC8EAC, #6EC6C5, #FC8EAC);
        border-radius: inherit;
        opacity: 0;
        z-index: -1;
        transition: opacity 0.3s;
        filter: blur(8px);
    }
    .class-card:hover::after {
        opacity: 0.6;
    }
    .geometric-shape {
        position: absolute;
        pointer-events: none;
        opacity: 0.1;
    }
    .hexagon {
        clip-path: polygon(30% 0%, 70% 0%, 100% 50%, 70% 100%, 30% 100%, 0% 50%);
    }
    .diamond {
        clip-path: polygon(50% 0%, 100% 50%, 50% 100%, 0% 50%);
    }
    .star {
        clip-path: polygon(50% 0%, 61% 35%, 98% 35%, 68% 57%, 79% 91%, 50% 70%, 21% 91%, 32% 57%, 2% 35%, 39% 35%);
    }
    [x-cloak] {
        display: none !important;
    }
    .masonry-grid {
        column-count: 1;
        column-gap: 1.5rem;
    }
    @media (min-width: 768px) {
        .masonry-grid { column-count: 2; }
    }
    @media (min-width: 1024px) {
        .masonry-grid { column-count: 3; }
    }
    @media (min-width: 1280px) {
        .masonry-grid { column-count: 4; }
    }
    .masonry-item {
        break-inside: avoid;
        margin-bottom: 1.5rem;
    }
    .stat-card {
        position: relative;
        overflow: hidden;
    }
    .stat-card::before {
        content: '';
        position: absolute;
        width: 200%;
        height: 200%;
        background: conic-gradient(from 0deg, transparent, rgba(252, 142, 172, 0.3), transparent 30%);
        animation: rotate 3s linear infinite;
        top: -50%;
        left: -50%;
    }
    .stat-card-content {
        position: relative;
        z-index: 1;
    }
    @keyframes gradient-shift {
        0%, 100% { background-position: 0% 50%; }
        50% { background-position: 100% 50%; }
    }
</style>

<div class="min-h-screen relative overflow-hidden" style="background: linear-gradient(135deg, #FFF0F5 0%, #E0F7FA 25%, #F3E5F5 50%, #E0F7FA 75%, #FFF0F5 100%); background-size: 400% 400%; animation: gradient-shift 15s ease infinite;">
    <!-- Animated Geometric Background Elements -->
    <div class="absolute inset-0 overflow-hidden pointer-events-none">
        <!-- Floating geometric shapes -->
        <div class="geometric-shape hexagon w-32 h-32 bg-pink-400 top-20 left-10 float-animation rotate-slow"></div>
        <div class="geometric-shape diamond w-24 h-24 bg-cyan-400 top-40 right-20 float-animation" style="animation-delay: 1s;"></div>
        <div class="geometric-shape star w-20 h-20 bg-pink-300 bottom-32 left-1/4 float-animation" style="animation-delay: 2s;"></div>
        <div class="geometric-shape hexagon w-28 h-28 bg-cyan-300 bottom-20 right-1/3 float-animation rotate-slow" style="animation-delay: 0.5s; animation-duration: 25s;"></div>
        <div class="geometric-shape diamond w-16 h-16 bg-pink-300 top-1/2 right-10 float-animation" style="animation-delay: 1.5s;"></div>
        
        <!-- Gradient orbs -->
        <div class="absolute top-10 left-1/4 w-96 h-96 bg-gradient-to-br from-pink-300/20 to-transparent rounded-full blur-3xl float-animation"></div>
        <div class="absolute bottom-10 right-1/4 w-80 h-80 bg-gradient-to-br from-cyan-300/20 to-transparent rounded-full blur-3xl float-animation" style="animation-delay: 2s;"></div>
        <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-[500px] h-[500px] bg-gradient-to-br from-purple-200/15 via-pink-200/15 to-cyan-200/15 rounded-full blur-3xl pulse-glow"></div>
    </div>

    <div class="relative z-10 w-full min-h-screen px-4 sm:px-6 lg:px-8 py-8">
        <!-- Creative Header Section -->
        <div class="relative mb-12 fade-in">
            <!-- Creative Header with decorative elements -->
            <div class="relative text-center">
                <!-- Decorative corner elements -->
                <div class="absolute top-0 left-0 w-20 h-20 border-t-4 border-l-4 border-pink-400/50 rounded-tl-3xl"></div>
                <div class="absolute top-0 right-0 w-20 h-20 border-t-4 border-r-4 border-cyan-400/50 rounded-tr-3xl"></div>
                <div class="absolute bottom-0 left-0 w-20 h-20 border-b-4 border-l-4 border-pink-400/50 rounded-bl-3xl"></div>
                <div class="absolute bottom-0 right-0 w-20 h-20 border-b-4 border-r-4 border-cyan-400/50 rounded-br-3xl"></div>
                
                <div class="relative bg-white/40 backdrop-blur-xl rounded-3xl p-8 md:p-12 border-4 border-double border-pink-300/50 shadow-2xl">
                    <!-- Go Back Button inside the header section -->
                    <div class="absolute top-4 left-4 md:top-6 md:left-6 z-20">
                        <button onclick="goBackOrRedirect('{{ route('teacher.dashboard') }}')" 
                                class="group relative flex items-center gap-2 px-4 py-2 md:px-6 md:py-3 rounded-xl font-bold text-white shadow-xl hover:shadow-2xl transition-all duration-500 transform hover:scale-110 hover:-rotate-1 overflow-hidden"
                                style="background: linear-gradient(135deg, #FC8EAC, #6EC6C5);">
                            <div class="absolute inset-0 bg-gradient-to-r from-cyan-400 to-pink-400 opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 md:h-6 md:w-6 relative z-10 transform group-hover:-translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                            </svg>
                            <span class="relative z-10 text-sm md:text-base">Go Back</span>
                            <div class="absolute inset-0 bg-white/20 transform -skew-x-12 -translate-x-full group-hover:translate-x-full transition-transform duration-700"></div>
                        </button>
                    </div>
                    <div class="inline-flex items-center justify-center mb-6">
                        <div class="relative">
                            <div class="absolute inset-0 bg-gradient-to-r from-pink-400 to-cyan-400 rounded-full blur-xl opacity-50 animate-pulse"></div>
                            <div class="relative w-20 h-20 md:w-24 md:h-24 rounded-full flex items-center justify-center text-4xl md:text-5xl font-black text-white shadow-2xl transform rotate-12 hover:rotate-0 transition-transform duration-500"
                                 style="background: linear-gradient(135deg, #FC8EAC, #6EC6C5);">
                                📚
                            </div>
                        </div>
                    </div>
                    <h1 class="text-5xl md:text-7xl font-black mb-4 relative">
                        <span class="relative inline-block">
                            <span class="absolute inset-0 bg-gradient-to-r from-pink-500 via-purple-500 to-cyan-500 blur-2xl opacity-50"></span>
                            <span class="relative" style="background: linear-gradient(135deg, #FC8EAC 0%, #9B59B6 50%, #6EC6C5 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text; text-shadow: 0 0 30px rgba(252, 142, 172, 0.3);">
                                My Classes
                            </span>
                        </span>
                    </h1>
                    <div class="flex items-center justify-center gap-2 mb-4">
                        <div class="h-1 w-16 bg-gradient-to-r from-transparent via-pink-400 to-cyan-400"></div>
                        <div class="w-3 h-3 rounded-full bg-gradient-to-r from-pink-400 to-cyan-400 animate-pulse"></div>
                        <div class="h-1 w-16 bg-gradient-to-r from-cyan-400 via-pink-400 to-transparent"></div>
                    </div>
                    <p class="text-xl md:text-2xl text-gray-700 font-semibold">Discover & Manage Your Teaching Journey ✨</p>
                </div>
            </div>
        </div>

        @if($classes->count())
            @php
                // Calculate total students from filtered students (excluding teachers)
                $totalStudents = $classes->sum(function($class) {
                    return $class->students->count();
                });
            @endphp
            <!-- Creative Statistics Cards -->
            <div class="max-w-7xl mx-auto mb-12">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <!-- Stat Card 1 - Hexagon style -->
                    <div class="stat-card relative group">
                        <div class="stat-card-content bg-white/70 backdrop-blur-xl rounded-3xl p-8 border-4 border-pink-300/50 shadow-2xl transform hover:scale-105 transition-all duration-500 hover:border-pink-400 hover:shadow-pink-500/30">
                            <div class="absolute -top-4 -right-4 w-16 h-16 bg-gradient-to-br from-pink-400 to-pink-600 rounded-full flex items-center justify-center shadow-xl transform rotate-12 group-hover:rotate-45 transition-transform duration-500">
                                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                </svg>
                            </div>
                            <div class="text-6xl font-black mb-3" style="background: linear-gradient(135deg, #FC8EAC, #E91E63); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">{{ $classes->count() }}</div>
                            <div class="text-lg font-bold text-gray-700 uppercase tracking-wider">Total Classes</div>
                            <div class="mt-4 h-1 bg-gradient-to-r from-pink-400 to-transparent rounded-full"></div>
                        </div>
                    </div>

                    <!-- Stat Card 2 - Diamond style -->
                    <div class="stat-card relative group">
                        <div class="stat-card-content bg-white/70 backdrop-blur-xl rounded-3xl p-8 border-4 border-cyan-300/50 shadow-2xl transform hover:scale-105 transition-all duration-500 hover:border-cyan-400 hover:shadow-cyan-500/30">
                            <div class="absolute -top-4 -left-4 w-16 h-16 bg-gradient-to-br from-cyan-400 to-cyan-600 rounded-full flex items-center justify-center shadow-xl transform -rotate-12 group-hover:-rotate-45 transition-transform duration-500">
                                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                                </svg>
                            </div>
                            <div class="text-6xl font-black mb-3" style="background: linear-gradient(135deg, #6EC6C5, #00ACC1); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">{{ $totalStudents }}</div>
                            <div class="text-lg font-bold text-gray-700 uppercase tracking-wider">Total Students</div>
                            <div class="mt-4 h-1 bg-gradient-to-r from-cyan-400 to-transparent rounded-full"></div>
                        </div>
                    </div>

                    <!-- Stat Card 3 - Star style -->
                    <div class="stat-card relative group">
                        <div class="stat-card-content bg-white/70 backdrop-blur-xl rounded-3xl p-8 border-4 border-purple-300/50 shadow-2xl transform hover:scale-105 transition-all duration-500 hover:border-purple-400 hover:shadow-purple-500/30">
                            <div class="absolute -bottom-4 -right-4 w-16 h-16 bg-gradient-to-br from-purple-400 via-pink-400 to-cyan-400 rounded-full flex items-center justify-center shadow-xl transform rotate-12 group-hover:rotate-180 transition-transform duration-700">
                                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <div class="text-6xl font-black mb-3" style="background: linear-gradient(135deg, #9B59B6, #FC8EAC, #6EC6C5); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">{{ $classes->where('status', 'active')->count() }}</div>
                            <div class="text-lg font-bold text-gray-700 uppercase tracking-wider">Active Classes</div>
                            <div class="mt-4 h-1 bg-gradient-to-r from-purple-400 via-pink-400 to-cyan-400 rounded-full"></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Creative Classes Grid - Asymmetric Layout -->
            <div class="max-w-7xl mx-auto">
                <div class="masonry-grid">
                    @foreach($classes as $index => $class)
                        @php
                            // Get actual filtered student count (excluding teachers)
                            $actualStudentCount = $class->students->count();
                            
                            // Calculate enrollment percentage based on filtered students, but cap at 100% for display
                            $rawPercentage = $class->capacity > 0 ? ($actualStudentCount / $class->capacity) * 100 : 0;
                            $enrollmentPercentage = min($rawPercentage, 100); // Cap at 100%
                            $isOverCapacity = $actualStudentCount > $class->capacity;
                            $isLarge = $index % 5 === 0;
                            $cardColors = [
                                ['from' => '#FC8EAC', 'to' => '#F8BBD0', 'border' => '#FC8EAC'],
                                ['from' => '#6EC6C5', 'to' => '#80DEEA', 'border' => '#6EC6C5'],
                                ['from' => '#BA68C8', 'to' => '#CE93D8', 'border' => '#BA68C8'],
                                ['from' => '#81C784', 'to' => '#A5D6A7', 'border' => '#81C784'],
                            ];
                            $colorScheme = $cardColors[$index % count($cardColors)];
                        @endphp
                        <div class="masonry-item">
                            <div class="class-card relative group" 
                                 x-data="{ show: false }" 
                                 style="animation-delay: {{ $index * 0.15 }}s;">
                                
                                <!-- Main Card -->
                                <div class="relative bg-white/80 backdrop-blur-2xl rounded-3xl p-6 md:p-8 shadow-2xl border-4 transition-all duration-500 overflow-hidden"
                                     style="border-color: {{ $colorScheme['border'] }}50;">
                                    
                                    <!-- Animated background pattern -->
                                    <div class="absolute inset-0 opacity-5">
                                        <div class="absolute inset-0" style="background-image: repeating-linear-gradient(45deg, transparent, transparent 10px, {{ $colorScheme['from'] }} 10px, {{ $colorScheme['from'] }} 20px);"></div>
                                    </div>
                                    
                                    <!-- Glowing corner accent -->
                                    <div class="absolute -top-2 -right-2 w-24 h-24 rounded-full blur-2xl opacity-30 group-hover:opacity-50 transition-opacity" style="background: linear-gradient(135deg, {{ $colorScheme['from'] }}, {{ $colorScheme['to'] }});"></div>
                                    <div class="absolute -bottom-2 -left-2 w-20 h-20 rounded-full blur-xl opacity-20 group-hover:opacity-40 transition-opacity" style="background: linear-gradient(135deg, {{ $colorScheme['to'] }}, {{ $colorScheme['from'] }});"></div>
                                    
                                    <!-- Decorative corner triangles -->
                                    <div class="absolute top-0 right-0 w-0 h-0 border-l-[40px] border-l-transparent" style="border-top: 40px solid {{ $colorScheme['border'] }}20;"></div>
                                    <div class="absolute bottom-0 left-0 w-0 h-0 border-r-[40px] border-r-transparent" style="border-bottom: 40px solid {{ $colorScheme['border'] }}20;"></div>
                                    
                                    <div class="relative z-10">
                                        <!-- Class Header with unique design -->
                                        <div class="flex items-start justify-between mb-6">
                                            <div class="flex-1">
                                                <div class="inline-flex items-center gap-3 mb-3">
                                                    <div class="relative">
                                                        <div class="absolute inset-0 bg-gradient-to-r from-{{ $colorScheme['from'] }} to-{{ $colorScheme['to'] }} rounded-2xl blur-md opacity-50"></div>
                                                        <div class="relative w-16 h-16 rounded-2xl flex items-center justify-center text-3xl font-black text-white shadow-xl transform rotate-6 group-hover:rotate-12 transition-transform duration-300"
                                                             style="background: linear-gradient(135deg, {{ $colorScheme['from'] }}, {{ $colorScheme['to'] }});">
                                                            {{ substr($class->class_name, 0, 1) }}
                                                        </div>
                                                    </div>
                                                    <div>
                                                        <h3 class="text-2xl md:text-3xl font-black text-gray-800 leading-tight" style="text-shadow: 2px 2px 4px rgba(0,0,0,0.1);">
                                                            {{ $class->class_name }}
                                                        </h3>
                                                        <div class="flex items-center gap-2 mt-1">
                                                            <div class="w-2 h-2 rounded-full animate-pulse" style="background: {{ $colorScheme['from'] }};"></div>
                                                            <span class="text-xs font-bold uppercase tracking-wider px-3 py-1 rounded-full {{ $class->status === 'active' ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-600' }}">
                                                                {{ ucfirst($class->status) }}
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Creative Info Display -->
                                        <div class="space-y-4 mb-6">
                                            <!-- Enrollment with visual indicator -->
                                            <div class="relative p-4 rounded-2xl bg-gradient-to-br from-white to-gray-50 border-2 shadow-lg"
                                                 style="border-color: {{ $colorScheme['border'] }}30;">
                                                <div class="flex items-center justify-between">
                                                    <div class="flex items-center gap-3">
                                                        <div class="w-12 h-12 rounded-xl flex items-center justify-center shadow-md transform -rotate-6 group-hover:rotate-6 transition-transform"
                                                             style="background: linear-gradient(135deg, {{ $colorScheme['from'] }}, {{ $colorScheme['to'] }});">
                                                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                                            </svg>
                                                        </div>
                                                        <div>
                                                            <div class="text-xs font-bold text-gray-500 uppercase tracking-wider">Enrolled</div>
                                                            <div class="text-2xl font-black" style="color: {{ $colorScheme['from'] }};">
                                                                {{ $actualStudentCount }}<span class="text-sm text-gray-500">/{{ $class->capacity }}</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Animated Progress Circle -->
                                            <div class="relative">
                                                <div class="flex items-center justify-between mb-3">
                                                    <span class="text-sm font-bold text-gray-700 uppercase tracking-wide">Progress</span>
                                                    <span class="text-lg font-black px-3 py-1 rounded-full text-white shadow-lg" style="background: linear-gradient(135deg, {{ $colorScheme['from'] }}, {{ $colorScheme['to'] }});">
                                                        {{ number_format($enrollmentPercentage, 0) }}%
                                                    </span>
                                                </div>
                                                <div class="relative h-4 bg-gray-200 rounded-full overflow-hidden shadow-inner">
                                                    <div class="absolute inset-0 bg-gradient-to-r from-gray-100 to-gray-200"></div>
                                                    <div class="relative h-full rounded-full transition-all duration-1000 ease-out shadow-lg" 
                                                         style="background: linear-gradient(90deg, {{ $colorScheme['from'] }}, {{ $colorScheme['to'] }}); width: {{ $enrollmentPercentage }}%;">
                                                        <div class="absolute inset-0 bg-white/30 animate-pulse"></div>
                                                    </div>
                                                </div>
                                            </div>

                                            @if($class->description)
                                                <div class="p-4 rounded-2xl border-2 border-dashed" style="border-color: {{ $colorScheme['border'] }}30; background: linear-gradient(135deg, rgba(255,255,255,0.5), rgba(255,255,255,0.2));">
                                                    <p class="text-sm text-gray-700 italic line-clamp-2">{{ $class->description }}</p>
                                                </div>
                                            @endif
                                        </div>

                                        <!-- Creative Toggle Button -->
                                        <button @click="show = !show" 
                                                class="group/btn w-full relative px-6 py-4 rounded-2xl font-bold text-white shadow-2xl overflow-hidden transition-all duration-500 transform hover:scale-105"
                                                style="background: linear-gradient(135deg, {{ $colorScheme['from'] }}, {{ $colorScheme['to'] }});">
                                            <div class="absolute inset-0 bg-white/20 transform -translate-x-full group-hover/btn:translate-x-0 transition-transform duration-700"></div>
                                            <div class="relative flex items-center justify-center gap-3">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 transform transition-transform duration-300" :class="show ? 'rotate-45' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                                </svg>
                                                <span x-text="show ? 'Hide Students' : 'View Students'"></span>
                                            </div>
                                        </button>

                                        <!-- Students List with creative design -->
                                        <div x-show="show" 
                                             x-transition:enter="transition ease-out duration-500"
                                             x-transition:enter-start="opacity-0 max-h-0"
                                             x-transition:enter-end="opacity-100 max-h-[500px]"
                                             x-transition:leave="transition ease-in duration-300"
                                             x-transition:leave-start="opacity-100 max-h-[500px]"
                                             x-transition:leave-end="opacity-0 max-h-0"
                                             x-cloak
                                             class="mt-6 pt-6 border-t-4 border-double overflow-hidden"
                                             style="border-color: {{ $colorScheme['border'] }}40;">
                                            <h4 class="font-black text-lg mb-4 flex items-center gap-3" style="color: {{ $colorScheme['from'] }};">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                                </svg>
                                                Students ({{ $class->students->count() }})
                                            </h4>
                                            @if($class->students->count())
                                                <div class="max-h-64 overflow-y-auto space-y-3 pr-2 custom-scrollbar">
                                                    @foreach($class->students as $studentIndex => $student)
                                                        <div class="p-4 rounded-xl bg-gradient-to-r from-white to-gray-50 border-2 shadow-md hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1"
                                                             style="border-color: {{ $colorScheme['border'] }}20; animation-delay: {{ $studentIndex * 0.05 }}s;">
                                                            <div class="flex items-center gap-4">
                                                                <div class="relative">
                                                                    <div class="w-12 h-12 rounded-xl flex items-center justify-center text-lg font-black text-white shadow-lg transform rotate-3 hover:rotate-0 transition-transform"
                                                                         style="background: linear-gradient(135deg, {{ $colorScheme['from'] }}, {{ $colorScheme['to'] }});">
                                                                        {{ substr($student->user->first_name ?? 'N', 0, 1) }}
                                                                    </div>
                                                                    <div class="absolute -top-1 -right-1 w-4 h-4 rounded-full border-2 border-white shadow-md" style="background: {{ $colorScheme['from'] }};"></div>
                                                                </div>
                                                                <div class="flex-1 min-w-0">
                                                                    <p class="text-base font-bold text-gray-800 truncate">
                                                                        {{ $student->user->first_name ?? 'N/A' }} {{ $student->user->last_name ?? '' }}
                                                                    </p>
                                                                    <p class="text-xs text-gray-500 truncate mt-1">{{ $student->user->email ?? 'No email' }}</p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            @else
                                                <div class="text-center py-8 rounded-2xl border-2 border-dashed" style="border-color: {{ $colorScheme['border'] }}30;">
                                                    <div class="text-5xl mb-3">👥</div>
                                                    <p class="text-sm font-bold text-gray-500">No students enrolled yet</p>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @else
            <!-- Creative Empty State -->
            <div class="max-w-3xl mx-auto relative fade-in">
                <div class="relative bg-white/70 backdrop-blur-2xl rounded-3xl shadow-2xl p-12 md:p-16 text-center border-4 border-dashed" style="border-color: rgba(252, 142, 172, 0.3);">
                    <!-- Decorative elements -->
                    <div class="absolute top-4 left-4 w-16 h-16 bg-pink-200/20 rounded-full blur-xl"></div>
                    <div class="absolute bottom-4 right-4 w-20 h-20 bg-cyan-200/20 rounded-full blur-xl"></div>
                    <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-32 h-32 bg-gradient-to-br from-pink-200/10 to-cyan-200/10 rounded-full blur-2xl"></div>
                    
                    <div class="relative z-10">
                        <div class="inline-block mb-6 transform hover:scale-110 transition-transform duration-300">
                            <div class="text-8xl mb-4 wave-animation">📚</div>
                        </div>
                        <h3 class="text-4xl md:text-5xl font-black mb-4" style="background: linear-gradient(135deg, #FC8EAC, #6EC6C5); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">
                            No Classes Assigned
                        </h3>
                        <div class="w-24 h-1 bg-gradient-to-r from-transparent via-pink-400 to-cyan-400 mx-auto mb-6 rounded-full"></div>
                        <p class="text-lg text-gray-600 font-semibold mb-6">You are not assigned to any class yet.</p>
                        <p class="text-base text-gray-500">Please contact the administrator to get started.</p>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>

<style>
    .custom-scrollbar::-webkit-scrollbar {
        width: 8px;
    }
    .custom-scrollbar::-webkit-scrollbar-track {
        background: rgba(252, 142, 172, 0.1);
        border-radius: 10px;
    }
    .custom-scrollbar::-webkit-scrollbar-thumb {
        background: linear-gradient(135deg, #FC8EAC, #6EC6C5);
        border-radius: 10px;
        border: 2px solid transparent;
        background-clip: padding-box;
    }
    .custom-scrollbar::-webkit-scrollbar-thumb:hover {
        background: linear-gradient(135deg, #FC8EAC, #6EC6C5);
        opacity: 0.9;
    }
</style>
@endsection
