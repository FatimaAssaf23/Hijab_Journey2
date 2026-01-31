@extends('layouts.app')
@section('content')
<div class="relative min-h-screen" style="background: linear-gradient(135deg, #FFF4FA 0%, #FDF2F8 30%, #F0F9FF 70%, #E0F7FA 100%);">
    <!-- Animated Background Elements -->
    <div class="fixed inset-0 overflow-hidden pointer-events-none z-0">
        <div class="absolute top-20 left-10 w-96 h-96 bg-pink-300/20 rounded-full blur-3xl animate-pulse" style="animation-delay: 0s;"></div>
        <div class="absolute top-60 right-20 w-[500px] h-[500px] bg-cyan-300/20 rounded-full blur-3xl animate-pulse" style="animation-delay: 2s;"></div>
        <div class="absolute bottom-20 left-1/4 w-80 h-80 bg-rose-300/20 rounded-full blur-3xl animate-pulse" style="animation-delay: 4s;"></div>
    </div>
    
    <div class="relative z-10 w-full max-w-full mx-auto px-4 sm:px-6 lg:px-8 pt-8 pb-16">
        <!-- Go Back Button -->
        <div class="mb-6">
            <a href="{{ url()->previous() !== url()->current() ? url()->previous() : (Auth::check() && Auth::user()->role === 'teacher' ? route('teacher.dashboard') : '/') }}" 
               class="inline-flex items-center gap-2 text-pink-600 hover:text-pink-700 font-semibold transition-colors duration-200 group bg-white/80 backdrop-blur-sm px-4 py-2 rounded-xl border-2 border-pink-300/50 shadow-md hover:shadow-lg">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 group-hover:-translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                <span>Go Back</span>
            </a>
        </div>
        
        <!-- Header Section -->
        <div class="relative bg-gradient-to-br from-pink-200/90 via-rose-100/80 to-cyan-200/90 rounded-3xl shadow-2xl overflow-hidden border-2 border-pink-300/50 backdrop-blur-sm mb-8">
            <!-- Decorative Pattern -->
            <div class="absolute inset-0 opacity-[0.08]">
                <div class="absolute inset-0" style="background-image: 
                    repeating-linear-gradient(45deg, transparent, transparent 15px, rgba(236,72,153,0.1) 15px, rgba(236,72,153,0.1) 30px),
                    repeating-linear-gradient(-45deg, transparent, transparent 15px, rgba(6,182,212,0.1) 15px, rgba(6,182,212,0.1) 30px);"></div>
            </div>
            
            <!-- Decorative Corners -->
            <div class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-br from-pink-400/30 to-transparent rounded-bl-full"></div>
            <div class="absolute bottom-0 left-0 w-32 h-32 bg-gradient-to-tr from-cyan-400/30 to-transparent rounded-tr-full"></div>
            
            <div class="relative p-6 lg:p-8">
                <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-6 mb-6">
                    <div class="flex items-center gap-4">
                        <div class="w-16 h-16 bg-gradient-to-br from-pink-400 to-rose-400 rounded-2xl flex items-center justify-center shadow-xl">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-9 w-9 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                            </svg>
                        </div>
                        <div>
                            <h2 class="text-3xl lg:text-4xl font-black text-gray-800 mb-1">
                                <span class="bg-clip-text text-transparent bg-gradient-to-r from-pink-600 via-rose-500 to-cyan-600">
                                    My Quizzes
                                </span>
                            </h2>
                            <p class="text-gray-600 font-medium">Manage and organize your quizzes</p>
                        </div>
                    </div>
                    <a href="{{ route('quizzes.create') }}" 
                       class="inline-flex items-center gap-2 bg-gradient-to-r from-pink-400 to-rose-400 hover:from-pink-500 hover:to-rose-500 text-white px-6 py-3 rounded-xl font-bold shadow-lg hover:shadow-xl transition-all duration-300 transform hover:scale-105 border-2 border-pink-300/50">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        Create New Quiz
                    </a>
                </div>
                
                @if(session('success'))
                    <div class="bg-gradient-to-r from-green-50 to-emerald-50 border-l-4 border-green-500 text-green-800 px-6 py-4 rounded-xl shadow-lg mb-6">
                        <div class="flex items-center gap-3">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <span class="font-semibold">{{ session('success') }}</span>
                        </div>
                    </div>
                @endif
                
                <!-- Class Filter -->
                <div class="bg-white/80 backdrop-blur-md rounded-2xl p-6 border-2 border-pink-200/50 shadow-lg">
                    <form method="GET" action="{{ route('quizzes.index') }}" class="flex items-end gap-4 flex-wrap">
                        <div class="flex-1 min-w-[280px] max-w-md">
                            <label for="class_id" class="block font-bold text-pink-700 mb-3 flex items-center gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-pink-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                                </svg>
                                Filter by Class
                            </label>
                            <div class="relative">
                                <select name="class_id" id="class_id" class="border-2 border-pink-300 rounded-xl px-4 py-3 pr-10 w-full focus:ring-2 focus:ring-pink-400 focus:border-pink-400 bg-white text-pink-700 font-semibold shadow-md hover:shadow-lg transition-all cursor-pointer appearance-none" onchange="this.form.submit()">
                                    <option value="">All Classes</option>
                                    @foreach($classes ?? [] as $class)
                                        <option value="{{ $class->class_id }}" {{ request('class_id') == $class->class_id ? 'selected' : '' }}>{{ $class->class_name }}</option>
                                    @endforeach
                                </select>
                                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3">
                                    <svg class="h-5 w-5 text-pink-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                    </svg>
                                </div>
                            </div>
                        </div>
                        @if(request('class_id'))
                            <div class="flex items-center gap-2 px-4 py-3 bg-gradient-to-r from-pink-100 to-rose-100 rounded-xl border-2 border-pink-300 shadow-md">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-pink-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <span class="text-pink-700 font-bold text-sm">{{ $classes->where('class_id', request('class_id'))->first()?->class_name ?? 'Selected' }}</span>
                            </div>
                        @endif
                    </form>
                </div>
            </div>
        </div>

        <!-- Quizzes Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            @forelse($quizzes as $quiz)
                <div class="group relative bg-white/90 backdrop-blur-md rounded-2xl shadow-xl overflow-hidden border-2 border-pink-200/50 transform transition-all duration-300 hover:shadow-2xl hover:-translate-y-2 hover:scale-105 animate-fade-in-up">
                    <!-- Gradient Top Border -->
                    <div class="absolute top-0 left-0 right-0 h-1.5 bg-gradient-to-r from-pink-400 via-rose-400 to-cyan-400"></div>
                    
                    <!-- Decorative Corner -->
                    <div class="absolute top-0 right-0 w-24 h-24 bg-gradient-to-br from-pink-200/20 to-transparent rounded-bl-full"></div>
                    
                    <div class="relative p-6 flex flex-col gap-4">
                        <div class="flex items-center gap-4 mb-2">
                            <div class="relative">
                                <div class="absolute inset-0 bg-gradient-to-br from-pink-400/30 to-rose-400/30 rounded-2xl blur-xl group-hover:blur-2xl transition-all"></div>
                                <div class="relative w-16 h-16 rounded-2xl flex items-center justify-center text-2xl font-black text-white shadow-xl transform group-hover:rotate-6 group-hover:scale-110 transition-all duration-500 border-2 border-white/50" style="background: linear-gradient(135deg, {{ $quiz->background_color ?? '#EC769A' }}, {{ $quiz->background_color ?? '#F472B6' }});">
                                    Q
                                </div>
                            </div>
                            <div class="flex-1 min-w-0">
                                <h3 class="font-black text-lg text-gray-800 tracking-tight mb-1 group-hover:text-pink-600 transition-colors line-clamp-2">{{ $quiz->title }}</h3>
                                <p class="text-sm text-gray-600 font-medium">{{ $quiz->level->level_name ?? 'N/A' }}</p>
                                @if($quiz->studentClass)
                                    <p class="text-xs text-pink-600 font-bold mt-1 flex items-center gap-1">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                        </svg>
                                        {{ $quiz->studentClass->class_name }}
                                    </p>
                                @endif
                            </div>
                        </div>
                        
                        <div class="flex flex-wrap gap-2">
                            <span class="px-3 py-1.5 rounded-lg bg-gradient-to-r from-pink-50 to-rose-50 text-pink-700 font-bold text-xs border-2 border-pink-200/50 shadow-sm">
                                <span class="flex items-center gap-1">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    {{ $quiz->questions->count() }} Questions
                                </span>
                            </span>
                            <span class="px-3 py-1.5 rounded-lg bg-gradient-to-r from-cyan-50 to-teal-50 text-cyan-700 font-bold text-xs border-2 border-cyan-200/50 shadow-sm">
                                <span class="flex items-center gap-1">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    {{ $quiz->timer_minutes }} min
                                </span>
                        </div>
                        
                        <div class="mt-auto pt-4 border-t border-pink-100">
                            <a href="{{ route('quizzes.show', $quiz->quiz_id) }}" 
                               class="block text-center bg-gradient-to-r from-pink-400 to-rose-400 hover:from-pink-500 hover:to-rose-500 text-white px-4 py-2.5 rounded-xl font-bold shadow-lg hover:shadow-xl transition-all duration-300 transform hover:scale-105 border-2 border-pink-300/50">
                                View Quiz
                            </a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full">
                    <div class="relative bg-gradient-to-br from-pink-100/90 via-rose-50/80 to-cyan-100/90 shadow-2xl rounded-3xl p-16 text-center border-2 border-pink-300/50 backdrop-blur-sm">
                        <div class="relative inline-block mb-8">
                            <div class="absolute inset-0 bg-gradient-to-br from-pink-400/30 to-cyan-400/30 rounded-full blur-3xl animate-pulse"></div>
                            <div class="relative bg-gradient-to-br from-pink-400 to-cyan-400 p-8 rounded-full shadow-2xl">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-24 w-24 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                </svg>
                            </div>
                        </div>
                        <h3 class="text-3xl font-black text-gray-800 mb-3">No quizzes created yet</h3>
                        <p class="text-gray-600 text-lg mb-8 font-medium">Start creating engaging quizzes for your students!</p>
                        <a href="{{ route('quizzes.create') }}" 
                           class="inline-flex items-center gap-2 bg-gradient-to-r from-pink-400 to-rose-400 hover:from-pink-500 hover:to-rose-500 text-white px-8 py-4 rounded-2xl font-bold shadow-xl hover:shadow-2xl transition-all duration-300 transform hover:scale-105 border-2 border-pink-300/50">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                            Create Your First Quiz
                        </a>
                    </div>
                </div>
            @endforelse
        </div>
    </div>
</div>

@push('styles')
<style>
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
        animation: fade-in-up 0.8s ease-out forwards;
        opacity: 0;
    }
    
    @media (prefers-reduced-motion: reduce) {
        .animate-fade-in-up {
            animation: none;
            opacity: 1;
        }
    }
</style>
@endpush
@endsection
