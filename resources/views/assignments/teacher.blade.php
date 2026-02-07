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

                <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-6 mb-6">
                    <div class="flex items-center gap-4">
                        <div class="w-16 h-16 bg-gradient-to-br from-pink-400 to-rose-400 rounded-2xl flex items-center justify-center shadow-xl">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-9 w-9 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                        </div>
                        <div>
                            <h1 class="text-3xl lg:text-4xl font-black text-gray-800 mb-1">
                                <span class="bg-clip-text text-transparent bg-gradient-to-r from-pink-600 via-rose-500 to-cyan-600">
                                    Assignments Management
                                </span>
                            </h1>
                            <p class="text-gray-600 font-medium">Create, manage, and track student assignments</p>
                        </div>
                    </div>
                </div>

                <!-- Statistics Bar -->
                <div class="bg-white/80 backdrop-blur-md rounded-2xl p-6 border-2 border-pink-200/50 shadow-lg">
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        <div class="flex items-center gap-3 bg-gradient-to-br from-pink-50 to-rose-50 rounded-xl p-4 border-l-4 border-pink-500">
                            <div class="w-12 h-12 bg-gradient-to-br from-pink-400 to-rose-400 rounded-xl flex items-center justify-center shadow-md">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                            </div>
                            <div>
                                <p class="text-xs text-gray-600 font-semibold mb-1">Total</p>
                                <p class="text-2xl font-black text-pink-700">{{ $assignments->count() }}</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-3 bg-gradient-to-br from-green-50 to-emerald-50 rounded-xl p-4 border-l-4 border-green-500">
                            <div class="w-12 h-12 bg-gradient-to-br from-green-400 to-emerald-400 rounded-xl flex items-center justify-center shadow-md">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <div>
                                <p class="text-xs text-gray-600 font-semibold mb-1">Submissions</p>
                                <p class="text-2xl font-black text-green-700">{{ $assignments->sum(fn($a) => $a->submitted_students->count()) }}</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-3 bg-gradient-to-br from-orange-50 to-amber-50 rounded-xl p-4 border-l-4 border-orange-500">
                            <div class="w-12 h-12 bg-gradient-to-br from-orange-400 to-amber-400 rounded-xl flex items-center justify-center shadow-md">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <div>
                                <p class="text-xs text-gray-600 font-semibold mb-1">Pending</p>
                                <p class="text-2xl font-black text-orange-700">{{ $assignments->sum(fn($a) => $a->submissions->where('grade', null)->count()) }}</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-3 bg-gradient-to-br from-cyan-50 to-teal-50 rounded-xl p-4 border-l-4 border-cyan-500">
                            <div class="w-12 h-12 bg-gradient-to-br from-cyan-400 to-teal-400 rounded-xl flex items-center justify-center shadow-md">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                </svg>
                            </div>
                            <div>
                                <p class="text-xs text-gray-600 font-semibold mb-1">Classes</p>
                                <p class="text-2xl font-black text-cyan-700">{{ $classes->count() }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                @if(session('success'))
                    <div class="mt-6 bg-gradient-to-r from-green-50 to-emerald-50 border-l-4 border-green-500 text-green-800 px-6 py-4 rounded-xl shadow-lg">
                        <div class="flex items-center gap-3">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <span class="font-semibold">{{ session('success') }}</span>
                        </div>
                    </div>
                @endif

                <!-- Class Filter and Add Button -->
                <div class="mt-6 bg-white/80 backdrop-blur-md rounded-2xl p-6 border-2 border-pink-200/50 shadow-lg">
                    <div class="flex flex-col lg:flex-row items-start lg:items-center justify-between gap-4">
                        <!-- Class Filter -->
                        <div class="flex-1 w-full lg:w-auto">
                            <form method="GET" action="{{ route('assignments.index') }}" class="flex items-end gap-3 flex-wrap">
                                <div class="min-w-[240px] max-w-xs flex-1">
                                    <label for="class_id" class="block font-bold text-pink-700 mb-2 text-sm flex items-center gap-2">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-pink-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                                        </svg>
                                        Filter by Class
                                    </label>
                                    <div class="relative">
                                        <select name="class_id" id="class_id" class="border-2 border-pink-300 rounded-lg px-3 py-2 pr-8 w-full focus:ring-2 focus:ring-pink-400 focus:border-pink-400 bg-white text-pink-700 font-semibold text-sm shadow-md hover:shadow-lg transition-all cursor-pointer appearance-none" onchange="this.form.submit()">
                                            <option value="">All Classes</option>
                                            @foreach($classes ?? [] as $class)
                                                <option value="{{ $class->class_id }}" {{ request('class_id') == $class->class_id ? 'selected' : '' }}>{{ $class->class_name }}</option>
                                            @endforeach
                                        </select>
                                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-2">
                                            <svg class="h-4 w-4 text-pink-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                            </svg>
                                        </div>
                                    </div>
                                </div>
                                @if(request('class_id'))
                                    <a href="{{ route('assignments.index') }}" class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded-lg font-semibold transition-all flex items-center gap-2">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                        Clear
                                    </a>
                                @endif
                            </form>
                            @if(request('class_id'))
                                <div class="mt-3 flex items-center gap-2 px-3 py-2 bg-gradient-to-r from-pink-100 to-rose-100 rounded-lg border border-pink-300">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-pink-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    <span class="text-pink-700 font-bold text-sm">{{ $classes->where('class_id', request('class_id'))->first()?->class_name ?? 'Selected' }}</span>
                                </div>
                            @endif
                        </div>

                        <!-- Add Assignment Button -->
                        <div class="w-full lg:w-auto">
                            <a href="{{ route('assignments.create') }}" 
                               class="inline-flex items-center gap-3 bg-gradient-to-r from-pink-400 to-rose-400 hover:from-pink-500 hover:to-rose-500 text-white px-8 py-4 rounded-xl font-bold shadow-lg hover:shadow-xl transition-all duration-300 transform hover:scale-105 border-2 border-pink-300/50 w-full lg:w-auto justify-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                </svg>
                                <span class="text-lg">Add Assignment</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Assignments List Section -->
        <div class="bg-gradient-to-br from-pink-50/90 via-white/90 to-pink-100/90 backdrop-blur-sm shadow-xl rounded-3xl p-8 lg:p-10 border-2 border-pink-200/50">
            <!-- Section Header -->
            <div class="mb-8">
                <h3 class="text-2xl font-black text-gray-800 flex items-center gap-3">
                    <div class="w-12 h-12 bg-gradient-to-br from-pink-400 to-rose-400 rounded-xl flex items-center justify-center shadow-lg">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                    </div>
                    <span class="bg-clip-text text-transparent bg-gradient-to-r from-pink-600 to-rose-600">All Assignments</span>
                </h3>
            </div>

            <!-- Assignments Grid -->
            <ul class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                @forelse($assignments as $assignment)
                    <li class="group relative bg-white/90 backdrop-blur-md rounded-2xl shadow-xl overflow-hidden border-2 border-pink-200/50 transform transition-all duration-300 hover:shadow-2xl hover:-translate-y-2 hover:scale-105 animate-fade-in-up">
                        <!-- Gradient Top Border -->
                        <div class="absolute top-0 left-0 right-0 h-1.5 bg-gradient-to-r from-pink-400 via-rose-400 to-cyan-400"></div>
                        
                        <!-- Decorative Corner -->
                        <div class="absolute top-0 right-0 w-24 h-24 bg-gradient-to-br from-pink-200/20 to-transparent rounded-bl-full"></div>
                        
                        <!-- Assignment Header -->
                        <div class="bg-gradient-to-r from-pink-400 to-rose-400 p-6 text-white relative">
                            <div class="flex items-start justify-between gap-4">
                                <div class="flex items-start gap-4 flex-1">
                                    <div class="bg-white/20 rounded-xl p-3 flex-shrink-0 backdrop-blur-sm">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                        </svg>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <h4 class="font-black text-xl mb-2 truncate">{{ $assignment->title }}</h4>
                                        @if($assignment->description)
                                            <p class="text-pink-50 text-sm line-clamp-2">{{ $assignment->description }}</p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Assignment Details -->
                        <div class="p-6 space-y-4">
                            <!-- Meta Information -->
                            <div class="grid grid-cols-2 gap-3">
                                <div class="flex items-center gap-2 p-3 bg-gradient-to-br from-pink-50 to-rose-50 rounded-xl border-2 border-pink-200/50">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-pink-500 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4" />
                                    </svg>
                                    <div class="min-w-0">
                                        <p class="text-xs text-gray-500 font-semibold">Level</p>
                                        <p class="text-sm font-bold text-pink-700 truncate">{{ $levels->where('level_id', $assignment->level_id)->first()?->level_name ?? 'N/A' }}</p>
                                    </div>
                                </div>
                                <div class="flex items-center gap-2 p-3 bg-gradient-to-br from-cyan-50 to-teal-50 rounded-xl border-2 border-cyan-200/50">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-cyan-500 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                    </svg>
                                    <div class="min-w-0">
                                        <p class="text-xs text-gray-500 font-semibold">Class</p>
                                        <p class="text-sm font-bold text-cyan-700 truncate">{{ $classes->where('class_id', $assignment->class_id)->first()?->class_name ?? 'N/A' }}</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Due Date -->
                            <div class="flex items-center gap-2 p-3 bg-gradient-to-br from-orange-50 to-amber-50 rounded-xl border-2 border-orange-200/50">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-orange-500 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 4h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                <div>
                                    <p class="text-xs text-gray-500 font-semibold">Due Date</p>
                                    <p class="text-sm font-bold text-orange-700">{{ \Carbon\Carbon::parse($assignment->due_date)->format('M d, Y H:i') }}</p>
                                </div>
                            </div>

                            <!-- Submission Status Summary -->
                            @php
                                $totalStudents = $assignment->submitted_students->count() + $assignment->unsubmitted_students->count();
                                $submissionRate = $totalStudents > 0 ? ($assignment->submitted_students->count() / $totalStudents) * 100 : 0;
                            @endphp
                            <div class="bg-gradient-to-br from-gray-50 to-gray-100 rounded-xl p-4 border-2 border-gray-200/50">
                                <div class="flex items-center justify-between mb-2">
                                    <span class="text-sm font-bold text-gray-700">Submission Progress</span>
                                    <span class="text-sm font-black text-pink-600">{{ round($submissionRate) }}%</span>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-3 mb-3">
                                    <div class="bg-gradient-to-r from-green-400 to-emerald-500 h-3 rounded-full transition-all duration-500" style="width: {{ $submissionRate }}%"></div>
                                </div>
                                <div class="flex items-center justify-between text-xs">
                                    <span class="flex items-center gap-1 text-green-700 font-bold">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                        </svg>
                                        Submitted: {{ $assignment->submitted_students->count() }}
                                    </span>
                                    <span class="flex items-center gap-1 text-red-700 font-bold">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                        Pending: {{ $assignment->unsubmitted_students->count() }}
                                    </span>
                                </div>
                            </div>

                            <!-- Students List -->
                            <div class="space-y-4">
                                <!-- Submitted Students -->
                                @if($assignment->submitted_students->count() > 0)
                                    <div>
                                        <div class="mb-3 flex items-center gap-2">
                                            <svg class="h-5 w-5 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                            <span class="font-bold text-green-700 text-sm">Submitted ({{ $assignment->submitted_students->count() }})</span>
                                        </div>
                                        <div class="flex flex-wrap gap-2 max-h-32 overflow-y-auto">
                                            @foreach($assignment->submitted_students as $student)
                                                <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-lg bg-gradient-to-r from-green-50 to-emerald-50 text-green-800 font-semibold border-2 border-green-200/50 text-sm">
                                                    <span>{{ $student->user->first_name ?? '' }} {{ $student->user->last_name ?? '' }}</span>
                                                    @if(isset($assignment->submissions[$student->student_id]))
                                                        <a href="{{ route('assignments.submission.view', $assignment->submissions[$student->student_id]->submission_id) }}" 
                                                           target="_blank" 
                                                           class="ml-1 px-2 py-0.5 rounded bg-green-200 text-green-800 text-xs font-bold hover:bg-green-300 transition flex items-center gap-1">
                                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                            </svg>
                                                            View
                                                        </a>
                                                    @endif
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif

                                <!-- Unsubmitted Students -->
                                @if($assignment->unsubmitted_students->count() > 0)
                                    <div>
                                        <div class="mb-3 flex items-center gap-2">
                                            <svg class="h-5 w-5 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                            <span class="font-bold text-red-700 text-sm">Pending ({{ $assignment->unsubmitted_students->count() }})</span>
                                        </div>
                                        <div class="flex flex-wrap gap-2 max-h-32 overflow-y-auto">
                                            @foreach($assignment->unsubmitted_students as $student)
                                                <span class="inline-flex items-center gap-2 px-3 py-1.5 rounded-lg bg-gradient-to-r from-red-50 to-rose-50 text-red-800 font-semibold border-2 border-red-200/50 text-sm">
                                                    {{ $student->user->first_name ?? '' }} {{ $student->user->last_name ?? '' }}
                                                </span>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif
                            </div>

                            <!-- Actions -->
                            <div class="pt-4 border-t-2 border-gray-100 space-y-3">
                                <a href="{{ asset('storage/' . $assignment->file_path) }}" 
                                   target="_blank"
                                   class="w-full inline-flex items-center justify-center gap-2 bg-gradient-to-r from-pink-400 to-rose-400 hover:from-pink-500 hover:to-rose-500 text-white px-6 py-3 rounded-xl font-bold shadow-lg hover:shadow-xl transition-all duration-300 transform hover:scale-105 border-2 border-pink-300/50">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                    View Assignment File
                                </a>
                                
                                <!-- Edit and Delete Buttons -->
                                <div class="flex gap-2">
                                    <a href="{{ route('assignments.edit', $assignment->assignment_id) }}" 
                                       class="flex-1 inline-flex items-center justify-center gap-2 bg-gradient-to-r from-blue-400 to-cyan-400 hover:from-blue-500 hover:to-cyan-500 text-white px-4 py-2.5 rounded-xl font-bold shadow-lg hover:shadow-xl transition-all duration-300 transform hover:scale-105 border-2 border-blue-300/50">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                        Edit
                                    </a>
                                    <form action="{{ route('assignments.destroy', $assignment->assignment_id) }}" 
                                          method="POST" 
                                          class="flex-1"
                                          onsubmit="return confirm('Are you sure you want to delete this assignment? This action cannot be undone and will also delete all student submissions.');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                class="w-full inline-flex items-center justify-center gap-2 bg-gradient-to-r from-red-400 to-rose-400 hover:from-red-500 hover:to-rose-500 text-white px-4 py-2.5 rounded-xl font-bold shadow-lg hover:shadow-xl transition-all duration-300 transform hover:scale-105 border-2 border-red-300/50">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                            Delete
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </li>
                @empty
                    <li class="col-span-full">
                        <div class="relative bg-gradient-to-br from-pink-100/90 via-rose-50/80 to-cyan-100/90 shadow-2xl rounded-3xl p-16 text-center border-2 border-pink-300/50 backdrop-blur-sm">
                            <div class="relative inline-block mb-8">
                                <div class="absolute inset-0 bg-gradient-to-br from-pink-400/30 to-cyan-400/30 rounded-full blur-3xl animate-pulse"></div>
                                <div class="relative bg-gradient-to-br from-pink-400 to-cyan-400 p-8 rounded-full shadow-2xl">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-24 w-24 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                </div>
                            </div>
                            <h3 class="text-3xl font-black text-gray-800 mb-3">No Assignments Yet</h3>
                            <p class="text-gray-600 text-lg mb-8 font-medium">Get started by creating your first assignment!</p>
                            <a href="{{ route('assignments.create') }}" class="inline-flex items-center gap-2 bg-gradient-to-r from-pink-400 to-rose-400 hover:from-pink-500 hover:to-rose-500 text-white px-8 py-4 rounded-2xl font-bold shadow-xl hover:shadow-2xl transition-all duration-300 transform hover:scale-105 border-2 border-pink-300/50">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                </svg>
                                Create First Assignment
                            </a>
                        </div>
                    </li>
                @endforelse
            </ul>
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
