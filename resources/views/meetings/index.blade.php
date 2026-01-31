@extends('layouts.app')

@section('content')
<div class="relative min-h-screen" style="background: linear-gradient(135deg, #FFF4FA 0%, #FDF2F8 30%, #F0F9FF 70%, #E0F7FA 100%);">
    <!-- Animated Background Elements -->
    <div class="fixed inset-0 overflow-hidden pointer-events-none z-0">
        <div class="absolute top-20 left-10 w-96 h-96 bg-pink-300/20 rounded-full blur-3xl animate-pulse" style="animation-delay: 0s;"></div>
        <div class="absolute top-60 right-20 w-[500px] h-[500px] bg-cyan-300/20 rounded-full blur-3xl animate-pulse" style="animation-delay: 2s;"></div>
        <div class="absolute bottom-20 left-1/4 w-80 h-80 bg-rose-300/20 rounded-full blur-3xl animate-pulse" style="animation-delay: 4s;"></div>
        <div class="absolute top-1/3 right-1/3 w-64 h-64 bg-teal-300/15 rounded-full blur-2xl animate-pulse" style="animation-delay: 1s;"></div>
    </div>
    
    <!-- Floating decorative elements -->
    <div class="absolute top-32 right-32 w-40 h-40 bg-pink-300/10 rounded-full blur-2xl animate-bounce" style="animation-duration: 8s;"></div>
    <div class="absolute bottom-32 left-32 w-48 h-48 bg-cyan-300/10 rounded-full blur-2xl animate-bounce" style="animation-duration: 10s; animation-delay: 2s;"></div>
    
    <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-8 pb-16">
        <!-- Hero Header Section - Creative Design -->
        <div class="mb-6">
            <div class="relative">
                <!-- Main Header with Creative Layout -->
                <div class="relative bg-gradient-to-br from-pink-200/90 via-rose-100/80 to-cyan-200/90 rounded-2xl shadow-xl overflow-hidden border-2 border-pink-300/50 backdrop-blur-sm">
                    <!-- Animated Pattern Overlay -->
                    <div class="absolute inset-0 opacity-[0.08]">
                        <div class="absolute inset-0" style="background-image: 
                            repeating-linear-gradient(45deg, transparent, transparent 15px, rgba(236,72,153,0.1) 15px, rgba(236,72,153,0.1) 30px),
                            repeating-linear-gradient(-45deg, transparent, transparent 15px, rgba(6,182,212,0.1) 15px, rgba(6,182,212,0.1) 30px);"></div>
                    </div>
                    
                    <!-- Decorative Corner Elements -->
                    <div class="absolute top-0 right-0 w-24 h-24 bg-gradient-to-br from-pink-400/30 to-transparent rounded-bl-full"></div>
                    <div class="absolute bottom-0 left-0 w-24 h-24 bg-gradient-to-tr from-cyan-400/30 to-transparent rounded-tr-full"></div>
                    
                    <div class="relative p-5 lg:p-6">
                        <!-- Go Back Button -->
                        <div class="mb-4">
                            <a href="{{ url()->previous() !== url()->current() ? url()->previous() : (Auth::check() && Auth::user()->role === 'teacher' ? route('teacher.dashboard') : (Auth::check() && Auth::user()->role === 'student' ? route('student.dashboard') : '/')) }}" 
                               class="inline-flex items-center gap-2 text-pink-600 hover:text-pink-700 font-semibold transition-colors duration-200 group bg-white/80 backdrop-blur-sm px-3 py-1.5 rounded-lg border-2 border-pink-300/50 shadow-md hover:shadow-lg text-sm">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 group-hover:-translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                                </svg>
                                <span>Go Back</span>
                            </a>
                        </div>

                        <div class="flex flex-col lg:flex-row items-center justify-between gap-6">
                            <!-- Left Content -->
                            <div class="flex-1 text-center lg:text-left">
                                <!-- Animated Badge -->
                                <div class="inline-flex items-center gap-2 bg-white/80 backdrop-blur-md px-4 py-2 rounded-full mb-3 border-2 border-pink-300/50 shadow-lg transform hover:scale-105 transition-transform">
                                    <div class="relative">
                                        <div class="w-2 h-2 bg-pink-500 rounded-full animate-ping absolute"></div>
                                        <div class="w-2 h-2 bg-pink-500 rounded-full relative"></div>
                                    </div>
                                    <span class="text-pink-700 font-bold text-xs tracking-wider uppercase">Virtual Classrooms</span>
                                </div>
                                
                                <!-- Main Title with Gradient -->
                                <h1 class="text-3xl lg:text-4xl font-black mb-3 leading-tight">
                                    <span class="bg-clip-text text-transparent bg-gradient-to-r from-pink-600 via-rose-500 to-cyan-600 animate-gradient">
                                        Schedule & Connect
                                    </span>
                                </h1>
                                
                                <!-- Subtitle -->
                                <p class="text-base lg:text-lg text-gray-700 mb-4 font-semibold">
                                    Engage with your students through interactive virtual meetings
                                </p>
                                
                                <!-- Quick Stats Pills (for teachers) -->
                                @if(Auth::check() && Auth::user()->role === 'teacher' && isset($stats) && $stats['total'] > 0)
                                    <div class="flex flex-wrap gap-2 justify-center lg:justify-start">
                                        <div class="flex items-center gap-2 bg-white/70 backdrop-blur-sm px-3 py-1.5 rounded-full border-2 border-pink-300/50 shadow-md transform hover:scale-105 transition-transform">
                                            <div class="w-6 h-6 bg-gradient-to-br from-pink-400 to-rose-400 rounded-lg flex items-center justify-center">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                </svg>
                                            </div>
                                            <span class="font-bold text-gray-800 text-sm">{{ $stats['total'] }} Total</span>
                                        </div>
                                        @if($stats['upcoming'] > 0)
                                            <div class="flex items-center gap-2 bg-white/70 backdrop-blur-sm px-3 py-1.5 rounded-full border-2 border-cyan-300/50 shadow-md transform hover:scale-105 transition-transform">
                                                <div class="w-6 h-6 bg-gradient-to-br from-cyan-400 to-teal-400 rounded-lg flex items-center justify-center">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                    </svg>
                                                </div>
                                                <span class="font-bold text-gray-800 text-sm">{{ $stats['upcoming'] }} Upcoming</span>
                                            </div>
                                        @endif
                                        @if($stats['today'] > 0)
                                            <div class="flex items-center gap-2 bg-white/70 backdrop-blur-sm px-3 py-1.5 rounded-full border-2 border-rose-300/50 shadow-md transform hover:scale-105 transition-transform">
                                                <div class="w-6 h-6 bg-gradient-to-br from-rose-400 to-pink-400 rounded-lg flex items-center justify-center">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                                                    </svg>
                                                </div>
                                                <span class="font-bold text-gray-800 text-sm">{{ $stats['today'] }} Today</span>
                                            </div>
                                        @endif
                                    </div>
                                @endif
                            </div>
                            
                            <!-- Right: Video Icon & Create Button -->
                            <div class="flex-shrink-0 flex flex-col items-center gap-4">
                                <!-- Animated Video Icon with Glassmorphism -->
                                <div class="relative">
                                    <div class="absolute inset-0 bg-gradient-to-br from-pink-400/30 to-cyan-400/30 rounded-full blur-xl animate-pulse"></div>
                                    <!-- Outer card with gradient border -->
                                    <div class="relative p-1 rounded-2xl bg-gradient-to-br from-pink-500 via-rose-500 to-cyan-500 shadow-xl transform hover:scale-105 hover:rotate-3 transition-all duration-500">
                                        <!-- Inner glassmorphic card -->
                                        <div class="bg-gradient-to-br from-pink-100/40 via-rose-100/40 to-cyan-100/40 backdrop-blur-xl rounded-2xl p-5 border border-white/30">
                                            <div class="bg-white/30 backdrop-blur-md rounded-xl p-4 border-2 border-white/40 shadow-inner">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="w-12 h-12 lg:w-16 lg:h-16 text-white drop-shadow-lg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
                                                </svg>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Create Button - Simple Design -->
                                @can('isTeacher')
                                    <a href="{{ route('meetings.create') }}" 
                                       class="inline-flex items-center justify-center gap-2 bg-pink-400 hover:bg-pink-500 text-white font-bold py-2.5 px-6 rounded-lg shadow-lg transition-colors duration-200 text-sm">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                        </svg>
                                        <span>Create New Meeting</span>
                                    </a>
                                @endcan
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Success/Error Messages -->
        @if (session('success'))
            <div class="mb-6 bg-gradient-to-r from-green-50 to-emerald-50 border-l-4 border-green-500 text-green-800 px-6 py-4 rounded-xl shadow-lg animate-fade-in">
                <div class="flex items-center gap-3">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span class="font-semibold">{{ session('success') }}</span>
                </div>
            </div>
        @endif

        @if (session('error'))
            <div class="mb-6 bg-gradient-to-r from-red-50 to-rose-50 border-l-4 border-red-500 text-red-800 px-6 py-4 rounded-xl shadow-lg animate-fade-in">
                <div class="flex items-center gap-3">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                    <span class="font-semibold">{{ session('error') }}</span>
                </div>
            </div>
        @endif

        @if(Auth::check() && Auth::user()->role === 'teacher' && isset($stats))
            <!-- Statistics - Horizontal Bar Style Design -->
            <div class="mb-10 animate-fade-in-up">
                <!-- Horizontal Statistics Bar -->
                <div class="relative bg-gradient-to-r from-pink-200 via-rose-200 to-cyan-200 rounded-2xl shadow-xl overflow-hidden border-2 border-pink-300/50">
                    <!-- Animated Background Pattern -->
                    <div class="absolute inset-0 opacity-30">
                        <div class="absolute inset-0" style="background-image: repeating-linear-gradient(45deg, transparent, transparent 10px, rgba(255,255,255,0.3) 10px, rgba(255,255,255,0.3) 20px);"></div>
                    </div>
                    
                    <div class="relative px-6 py-8">
                        <!-- Statistics Row -->
                        <div class="flex flex-wrap items-center justify-between gap-6 lg:gap-8">
                            <!-- Total Meetings -->
                            <div class="flex items-center gap-4 flex-1 min-w-[200px]">
                                <div class="flex-shrink-0 w-16 h-16 bg-gradient-to-br from-pink-400 to-rose-400 rounded-xl flex items-center justify-center border-2 border-pink-500/30 shadow-md">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                </div>
                                <div>
                                    <div class="text-pink-700 text-xs font-semibold uppercase tracking-wider mb-1">Total Meetings</div>
                                    <div class="text-4xl font-black text-gray-800 mb-1">{{ $stats['total'] ?? 0 }}</div>
                                    <div class="text-gray-600 text-xs">All meetings</div>
                                </div>
                            </div>

                            <!-- Divider -->
                            <div class="hidden lg:block w-px h-16 bg-pink-300/50"></div>

                            <!-- Upcoming Meetings -->
                            <div class="flex items-center gap-4 flex-1 min-w-[200px]">
                                <div class="flex-shrink-0 w-16 h-16 bg-gradient-to-br from-cyan-400 to-teal-400 rounded-xl flex items-center justify-center border-2 border-cyan-500/30 shadow-md">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                                <div>
                                    <div class="text-cyan-700 text-xs font-semibold uppercase tracking-wider mb-1">Upcoming</div>
                                    <div class="text-4xl font-black text-gray-800 mb-1">{{ $stats['upcoming'] ?? 0 }}</div>
                                    <div class="text-gray-600 text-xs">Scheduled</div>
                                </div>
                            </div>

                            <!-- Divider -->
                            <div class="hidden lg:block w-px h-16 bg-cyan-300/50"></div>

                            <!-- Today's Meetings -->
                            <div class="flex items-center gap-4 flex-1 min-w-[200px]">
                                <div class="flex-shrink-0 w-16 h-16 bg-gradient-to-br from-rose-400 to-pink-400 rounded-xl flex items-center justify-center border-2 border-rose-500/30 shadow-md">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                                    </svg>
                                </div>
                                <div>
                                    <div class="text-rose-700 text-xs font-semibold uppercase tracking-wider mb-1">Today</div>
                                    <div class="text-4xl font-black text-gray-800 mb-1">{{ $stats['today'] ?? 0 }}</div>
                                    <div class="text-gray-600 text-xs">This day</div>
                                </div>
                            </div>

                            <!-- Divider -->
                            <div class="hidden lg:block w-px h-16 bg-rose-300/50"></div>

                            <!-- Completed Meetings -->
                            <div class="flex items-center gap-4 flex-1 min-w-[200px]">
                                <div class="flex-shrink-0 w-16 h-16 bg-gradient-to-br from-teal-400 to-cyan-400 rounded-xl flex items-center justify-center border-2 border-teal-500/30 shadow-md">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                                <div>
                                    <div class="text-teal-700 text-xs font-semibold uppercase tracking-wider mb-1">Completed</div>
                                    <div class="text-4xl font-black text-gray-800 mb-1">{{ $stats['completed'] ?? 0 }}</div>
                                    <div class="text-gray-600 text-xs">Finished</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        @if ($meetings->isEmpty())
            <!-- Empty State - Creative Design -->
            <div class="relative bg-gradient-to-br from-pink-100/90 via-rose-50/80 to-cyan-100/90 shadow-2xl rounded-3xl p-16 text-center border-2 border-pink-300/50 backdrop-blur-sm">
                <div class="relative inline-block mb-8">
                    <div class="absolute inset-0 bg-gradient-to-br from-pink-400/30 to-cyan-400/30 rounded-full blur-3xl animate-pulse"></div>
                    <div class="relative bg-gradient-to-br from-pink-400 to-cyan-400 p-8 rounded-full shadow-2xl">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-32 w-32 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                    </div>
                </div>
                <h3 class="text-3xl font-black text-gray-800 mb-3">No meetings scheduled yet</h3>
                <p class="text-gray-600 text-xl mb-8 font-medium">Start creating engaging virtual classrooms for your students!</p>
                @can('isTeacher')
                    <a href="{{ route('meetings.create') }}" 
                       class="inline-flex items-center gap-3 bg-gradient-to-r from-pink-500 via-rose-500 to-cyan-500 hover:from-pink-600 hover:via-rose-600 hover:to-cyan-600 text-white font-bold py-4 px-8 rounded-2xl shadow-xl transition-all duration-300 transform hover:scale-105 hover:shadow-2xl border-2 border-white/30">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        Create Your First Meeting
                    </a>
                @endcan
            </div>
        @else
            <!-- Meetings Grid - Creative Card Design -->
            <div class="grid gap-6 lg:gap-8">
                @foreach($meetings as $index => $meeting)
                    <div class="group relative bg-white/90 backdrop-blur-md rounded-3xl shadow-xl overflow-hidden border-2 border-pink-200/50 transform transition-all duration-500 hover:shadow-2xl hover:-translate-y-2 animate-fade-in-up" style="animation-delay: {{ ($index % 5) * 0.1 }}s;">
                        <!-- Gradient Background Overlay -->
                        <div class="absolute top-0 left-0 right-0 h-2 bg-gradient-to-r from-pink-400 via-rose-400 to-cyan-400"></div>
                        
                        <!-- Decorative Corner -->
                        <div class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-br from-pink-200/20 to-transparent rounded-bl-full"></div>
                        
                        <!-- Pattern Overlay -->
                        <div class="absolute inset-0 opacity-[0.03] pointer-events-none">
                            <div class="absolute inset-0" style="background-image: radial-gradient(circle, rgba(236,72,153,0.3) 1px, transparent 1px); background-size: 25px 25px;"></div>
                        </div>
                        
                        <div class="relative p-8 lg:p-10">
                            <div class="flex flex-col lg:flex-row justify-between items-start gap-8">
                                <!-- Left: Meeting Info -->
                                <div class="flex-1 w-full">
                                    <div class="flex items-start gap-6 mb-6">
                                        <!-- Icon Container -->
                                        <div class="flex-shrink-0">
                                            <div class="relative">
                                                <div class="absolute inset-0 bg-gradient-to-br from-pink-400/30 to-cyan-400/30 rounded-2xl blur-xl group-hover:blur-2xl transition-all"></div>
                                                <div class="relative w-20 h-20 bg-gradient-to-br from-pink-400 via-rose-400 to-cyan-400 rounded-2xl flex items-center justify-center shadow-xl transform group-hover:rotate-6 group-hover:scale-110 transition-all duration-500 border-2 border-white/50">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-white drop-shadow-lg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
                                                    </svg>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <!-- Content -->
                                        <div class="flex-1">
                                            <h2 class="text-3xl lg:text-4xl font-black text-gray-800 mb-4 group-hover:text-pink-600 transition-colors">
                                                <a href="{{ route('meetings.show', $meeting) }}" class="hover:underline">
                                                    {{ $meeting->title }}
                                                </a>
                                            </h2>
                                            
                                            <div class="space-y-4">
                                                <!-- Class Info -->
                                                <div class="flex items-center gap-3 text-gray-700 bg-pink-50/50 px-4 py-3 rounded-xl border border-pink-200/50">
                                                    <div class="w-10 h-10 bg-gradient-to-br from-pink-400 to-rose-400 rounded-lg flex items-center justify-center flex-shrink-0">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                                        </svg>
                                                    </div>
                                                    <div>
                                                        <span class="font-bold text-pink-600 text-sm">Class:</span>
                                                        <span class="ml-2 font-semibold">{{ $meeting->studentClass->class_name ?? 'N/A' }}</span>
                                                    </div>
                                                </div>
                                                
                                                <!-- Date & Time Row -->
                                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                                    <div class="flex items-center gap-3 text-gray-700 bg-cyan-50/50 px-4 py-3 rounded-xl border border-cyan-200/50">
                                                        <div class="w-10 h-10 bg-gradient-to-br from-cyan-400 to-teal-400 rounded-lg flex items-center justify-center flex-shrink-0">
                                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                            </svg>
                                                        </div>
                                                        <div>
                                                            <span class="font-bold text-cyan-600 text-sm">Date:</span>
                                                            <span class="ml-2 font-semibold">{{ $meeting->start_time ? $meeting->start_time->format('F d, Y') : 'Not set' }}</span>
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="flex items-center gap-3 text-gray-700 bg-rose-50/50 px-4 py-3 rounded-xl border border-rose-200/50">
                                                        <div class="w-10 h-10 bg-gradient-to-br from-rose-400 to-pink-400 rounded-lg flex items-center justify-center flex-shrink-0">
                                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                            </svg>
                                                        </div>
                                                        <div>
                                                            <span class="font-bold text-rose-600 text-sm">Time:</span>
                                                            <span class="ml-2 font-semibold">
                                                                @if($meeting->start_time && $meeting->end_time)
                                                                    {{ $meeting->start_time->format('h:i A') }} - {{ $meeting->end_time->format('h:i A') }}
                                                                @else
                                                                    Not set
                                                                @endif
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                                @if($meeting->description)
                                                    <div class="mt-4 p-4 bg-gradient-to-r from-pink-50/80 to-cyan-50/80 rounded-xl border-2 border-pink-200/50">
                                                        <p class="text-sm text-gray-700 font-medium line-clamp-2">{{ $meeting->description }}</p>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Right: Actions & Status -->
                                <div class="flex flex-col items-end gap-4 lg:min-w-[220px] w-full lg:w-auto">
                                    <!-- Status Badge -->
                                    <div class="w-full lg:w-auto">
                                        <span class="inline-flex items-center gap-2 px-5 py-3 rounded-full text-sm font-bold shadow-lg
                                            @if($meeting->status === 'scheduled' && $meeting->start_time && $meeting->start_time->isFuture())
                                                bg-gradient-to-r from-blue-100 to-cyan-100 text-blue-800 border-2 border-blue-300/50
                                            @elseif($meeting->end_time && $meeting->end_time->isPast())
                                                bg-gradient-to-r from-green-100 to-emerald-100 text-green-800 border-2 border-green-300/50
                                            @elseif($meeting->start_time && $meeting->start_time->isToday())
                                                bg-gradient-to-r from-purple-100 to-pink-100 text-purple-800 border-2 border-purple-300/50
                                            @else
                                                bg-gradient-to-r from-gray-100 to-gray-200 text-gray-800 border-2 border-gray-300/50
                                            @endif">
                                            @if($meeting->status === 'scheduled' && $meeting->start_time && $meeting->start_time->isFuture())
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                </svg>
                                                Upcoming
                                            @elseif($meeting->end_time && $meeting->end_time->isPast())
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                </svg>
                                                Completed
                                            @elseif($meeting->start_time && $meeting->start_time->isToday())
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                                                </svg>
                                                Today
                                            @else
                                                {{ ucfirst($meeting->status) }}
                                            @endif
                                        </span>
                                    </div>
                                    
                                    <!-- Action Buttons -->
                                    <div class="flex flex-col gap-3 w-full lg:w-auto">
                                        <a href="{{ route('meetings.show', $meeting) }}" 
                                           class="group/btn inline-flex items-center justify-center gap-2 bg-gradient-to-r from-pink-400 to-rose-400 hover:from-pink-500 hover:to-rose-500 text-white font-bold py-3.5 px-6 rounded-xl shadow-lg transition-all duration-300 transform hover:scale-105 hover:shadow-xl border-2 border-pink-300/50">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 group-hover/btn:rotate-12 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                            </svg>
                                            View Details
                                        </a>
                                        
                                        @if($meeting->google_meet_link)
                                            <a href="{{ $meeting->google_meet_link }}" 
                                               target="_blank"
                                               class="group/btn inline-flex items-center justify-center gap-2 bg-gradient-to-r from-cyan-400 to-teal-400 hover:from-cyan-500 hover:to-teal-500 text-white font-bold py-3.5 px-6 rounded-xl shadow-lg transition-all duration-300 transform hover:scale-105 hover:shadow-xl border-2 border-cyan-300/50">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 group-hover/btn:scale-110 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
                                                </svg>
                                                Join Google Meet
                                            </a>
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
            transform: translateY(30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    @keyframes gradient {
        0%, 100% {
            background-position: 0% 50%;
        }
        50% {
            background-position: 100% 50%;
        }
    }
    
    .animate-fade-in {
        animation: fade-in 0.8s ease-out;
    }
    
    .animate-fade-in-up {
        animation: fade-in-up 0.8s ease-out forwards;
        opacity: 0;
    }
    
    .animate-gradient {
        background-size: 200% 200%;
        animation: gradient 3s ease infinite;
    }
    
    @media (prefers-reduced-motion: reduce) {
        .animate-fade-in,
        .animate-fade-in-up,
        .animate-gradient {
            animation: none;
            opacity: 1;
        }
    }
</style>
@endpush
@endsection
