@extends('layouts.app')
@section('content')
<div class="w-full min-h-screen py-6 px-4 sm:px-6 lg:px-8" x-data="{ showForm: false }">
    <!-- Page Header Section -->
    <div class="mb-8">
        <div class="bg-white rounded-3xl shadow-xl p-8 mb-6 border-2 border-pink-100">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-6">
                <div class="flex items-start gap-4 flex-1">
                    <button onclick="goBackOrRedirect('{{ route('teacher.dashboard') }}')" 
                            class="flex items-center gap-2 px-4 py-2 rounded-xl font-semibold text-white shadow-lg hover:shadow-xl transition-all duration-300 transform hover:scale-105 flex-shrink-0" 
                            style="background: linear-gradient(135deg, #FC8EAC, #6EC6C5);">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                        Go Back
                    </button>
                    <div class="flex-1">
                        <h1 class="text-4xl font-extrabold text-gray-800 flex items-center gap-3 mb-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-pink-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            Assignments Management
                        </h1>
                        <p class="text-gray-600 font-medium text-lg">Create, manage, and track student assignments</p>
                    </div>
                </div>
            </div>

            <!-- Statistics Summary Bar -->
            <div class="mt-6 pt-6 border-t-2 border-pink-100">
                <div class="flex flex-wrap items-center gap-4">
                    <div class="flex items-center gap-2 bg-pink-50 px-4 py-2 rounded-lg border border-pink-200">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-pink-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        <span class="font-semibold text-sm text-gray-700">Total:</span>
                        <span class="font-extrabold text-lg text-pink-600">{{ $assignments->count() }}</span>
                        <span class="text-gray-600 text-sm">assignments</span>
                    </div>
                    <div class="flex items-center gap-2 bg-green-50 px-4 py-2 rounded-lg border border-green-200">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span class="font-semibold text-sm text-gray-700">Submissions:</span>
                        <span class="font-extrabold text-lg text-green-600">{{ $assignments->sum(fn($a) => $a->submitted_students->count()) }}</span>
                    </div>
                    <div class="flex items-center gap-2 bg-orange-50 px-4 py-2 rounded-lg border border-orange-200">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-orange-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span class="font-semibold text-sm text-gray-700">Pending:</span>
                        <span class="font-extrabold text-lg text-orange-600">{{ $assignments->sum(fn($a) => $a->submissions->where('grade', null)->count()) }}</span>
                        <span class="text-gray-600 text-sm">reviews</span>
                    </div>
                    <div class="flex items-center gap-2 bg-purple-50 px-4 py-2 rounded-lg border border-purple-200">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                        </svg>
                        <span class="font-semibold text-sm text-gray-700">Classes:</span>
                        <span class="font-extrabold text-lg text-purple-600">{{ $classes->count() }}</span>
                        <span class="text-gray-600 text-sm">active</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Success Message -->
        @if(session('success'))
            <div class="mb-6 bg-green-50 border-l-4 border-green-500 rounded-lg p-4 shadow-lg">
                <div class="flex items-center gap-3">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <p class="text-green-800 font-bold">{{ session('success') }}</p>
                </div>
            </div>
        @endif
    </div>

    <!-- Create New Assignment Section -->
    <div class="bg-gradient-to-br from-pink-50 via-white to-pink-100 shadow-2xl rounded-3xl p-8 mb-8 border-2 border-pink-200">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-extrabold text-pink-700 flex items-center gap-3 drop-shadow">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-pink-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Create New Assignment
            </h2>
            <button type="button" @click="showForm = !showForm" 
                    class="px-6 py-3 rounded-xl bg-gradient-to-r from-pink-500 to-pink-600 text-white font-extrabold border-2 border-pink-400 shadow-lg hover:from-pink-600 hover:to-pink-700 transition-all duration-150 transform hover:scale-105 flex items-center gap-2">
                <span x-show="!showForm">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Show Form
                </span>
                <span x-show="showForm">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4" />
                    </svg>
                    Hide Form
                </span>
            </button>
        </div>
        <template x-if="showForm">
            <form method="POST" action="{{ route('assignments.store') }}" enctype="multipart/form-data" class="space-y-6">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block font-bold text-pink-700 mb-2 flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z" />
                            </svg>
                            Title
                        </label>
                        <input type="text" name="title" class="border-2 border-pink-200 rounded-xl px-4 py-3 w-full focus:ring-2 focus:ring-pink-400 focus:border-pink-400 bg-white transition-all" placeholder="Enter assignment title" required>
                    </div>
                    <div>
                        <label class="block font-bold text-pink-700 mb-2 flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4" />
                            </svg>
                            Level
                        </label>
                        <select name="level_id" class="border-2 border-pink-200 rounded-xl px-4 py-3 w-full focus:ring-2 focus:ring-pink-400 focus:border-pink-400 bg-white transition-all" required>
                            <option value="">Select Level</option>
                            @foreach($levels as $level)
                                <option value="{{ $level->level_id }}">{{ $level->level_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block font-bold text-pink-700 mb-2 flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                            </svg>
                            Class
                        </label>
                        <select name="class_id" class="border-2 border-pink-200 rounded-xl px-4 py-3 w-full focus:ring-2 focus:ring-pink-400 focus:border-pink-400 bg-white transition-all" required>
                            <option value="">Select Class</option>
                            @foreach($classes as $class)
                                <option value="{{ $class->class_id }}">{{ $class->class_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block font-bold text-pink-700 mb-2 flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 4h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            Due Date & Time
                        </label>
                        <input type="datetime-local" name="due_date" class="border-2 border-pink-200 rounded-xl px-4 py-3 w-full focus:ring-2 focus:ring-pink-400 focus:border-pink-400 bg-white transition-all" required>
                    </div>
                </div>
                <div>
                    <label class="block font-bold text-pink-700 mb-2 flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7" />
                        </svg>
                        Description
                    </label>
                    <textarea name="description" rows="3" class="border-2 border-pink-200 rounded-xl px-4 py-3 w-full focus:ring-2 focus:ring-pink-400 focus:border-pink-400 bg-white transition-all" placeholder="Enter assignment description"></textarea>
                </div>
                <div>
                    <label class="block font-bold text-pink-700 mb-2 flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                        </svg>
                        Assignment File
                    </label>
                    <input type="file" name="file" class="border-2 border-pink-200 rounded-xl px-4 py-3 w-full focus:ring-2 focus:ring-pink-400 focus:border-pink-400 bg-white transition-all file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-pink-50 file:text-pink-700 hover:file:bg-pink-100" required>
                </div>
                <div class="flex justify-end pt-4 border-t-2 border-pink-200">
                    <button type="submit" class="bg-gradient-to-r from-pink-500 to-pink-700 text-white px-10 py-3 rounded-2xl font-extrabold shadow-xl hover:from-pink-600 hover:to-pink-800 transition-all duration-150 transform hover:scale-105 flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                        </svg>
                        Upload Assignment
                    </button>
                </div>
            </form>
        </template>
    </div>

    <!-- Assignments List Section -->
    <div class="bg-gradient-to-br from-white via-pink-50 to-pink-100 shadow-xl rounded-3xl p-8 border-2 border-pink-100">
        <!-- Section Header with Filter -->
        <div class="mb-8">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6">
                <h3 class="text-2xl font-extrabold text-pink-700 flex items-center gap-3 drop-shadow">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-pink-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    All Assignments
                </h3>
                
                <!-- Class Filter -->
                <div class="bg-white rounded-xl p-4 border-2 border-pink-200 shadow-lg">
                    <form method="GET" action="{{ route('assignments.index') }}" class="flex items-end gap-3 flex-wrap">
                        <div class="min-w-[240px] max-w-xs">
                            <label for="class_id" class="block font-extrabold text-pink-700 mb-2 text-sm flex items-center gap-2">
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
                        <div class="mt-3 flex items-center gap-2 px-3 py-2 bg-gradient-to-r from-pink-100 to-pink-200 rounded-lg border border-pink-300">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-pink-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <span class="text-pink-700 font-bold text-sm">{{ $classes->where('class_id', request('class_id'))->first()?->class_name ?? 'Selected' }}</span>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Assignments Grid -->
        <ul class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            @forelse($assignments as $assignment)
                <li class="bg-white rounded-2xl shadow-lg border-2 border-pink-100 overflow-hidden hover:shadow-2xl hover:border-pink-300 transition-all duration-300 transform hover:-translate-y-1">
                    <!-- Assignment Header -->
                    <div class="bg-gradient-to-r from-pink-500 to-pink-600 p-6 text-white">
                        <div class="flex items-start justify-between gap-4">
                            <div class="flex items-start gap-4 flex-1">
                                <div class="bg-white/20 rounded-xl p-3 flex-shrink-0">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <h4 class="font-extrabold text-xl mb-2 truncate">{{ $assignment->title }}</h4>
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
                            <div class="flex items-center gap-2 p-3 bg-pink-50 rounded-xl border border-pink-200">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-pink-500 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4" />
                                </svg>
                                <div class="min-w-0">
                                    <p class="text-xs text-gray-500 font-semibold">Level</p>
                                    <p class="text-sm font-bold text-pink-700 truncate">{{ $levels->where('level_id', $assignment->level_id)->first()?->level_name ?? 'N/A' }}</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-2 p-3 bg-pink-50 rounded-xl border border-pink-200">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-pink-500 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                </svg>
                                <div class="min-w-0">
                                    <p class="text-xs text-gray-500 font-semibold">Class</p>
                                    <p class="text-sm font-bold text-pink-700 truncate">{{ $classes->where('class_id', $assignment->class_id)->first()?->class_name ?? 'N/A' }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Due Date -->
                        <div class="flex items-center gap-2 p-3 bg-orange-50 rounded-xl border border-orange-200">
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
                        <div class="bg-gray-50 rounded-xl p-4 border border-gray-200">
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-sm font-bold text-gray-700">Submission Progress</span>
                                <span class="text-sm font-extrabold text-pink-600">{{ round($submissionRate) }}%</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-3 mb-3">
                                <div class="bg-gradient-to-r from-green-400 to-green-600 h-3 rounded-full transition-all duration-500" style="width: {{ $submissionRate }}%"></div>
                            </div>
                            <div class="flex items-center justify-between text-xs">
                                <span class="flex items-center gap-1 text-green-700 font-semibold">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                    </svg>
                                    Submitted: {{ $assignment->submitted_students->count() }}
                                </span>
                                <span class="flex items-center gap-1 text-red-700 font-semibold">
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
                                            <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-lg bg-green-50 text-green-800 font-semibold border border-green-200 text-sm">
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
                                            <span class="inline-flex items-center gap-2 px-3 py-1.5 rounded-lg bg-red-50 text-red-800 font-semibold border border-red-200 text-sm">
                                                {{ $student->user->first_name ?? '' }} {{ $student->user->last_name ?? '' }}
                                            </span>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        </div>

                        <!-- Actions -->
                        <div class="pt-4 border-t-2 border-gray-100">
                            <a href="{{ asset('storage/' . $assignment->file_path) }}" 
                               target="_blank"
                               class="w-full inline-flex items-center justify-center gap-2 bg-gradient-to-r from-pink-500 to-pink-600 text-white px-6 py-3 rounded-xl font-extrabold shadow-lg hover:from-pink-600 hover:to-pink-700 transition-all duration-150 transform hover:scale-105">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                                View Assignment File
                            </a>
                        </div>
                    </div>
                </li>
            @empty
                <li class="col-span-full">
                    <div class="bg-white rounded-2xl shadow-lg border-2 border-pink-100 p-12 text-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 text-gray-400 mx-auto mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        <h3 class="text-xl font-bold text-gray-600 mb-2">No Assignments Yet</h3>
                        <p class="text-gray-500 mb-4">Get started by creating your first assignment using the form above.</p>
                        <button @click="showForm = true" class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-pink-500 to-pink-600 text-white rounded-xl font-bold shadow-lg hover:from-pink-600 hover:to-pink-700 transition-all duration-150">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                            Create First Assignment
                        </button>
                    </div>
                </li>
            @endforelse
        </ul>
    </div>
</div>
@endsection

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/choices.js/public/assets/styles/choices.min.css">
@endpush
@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/choices.js/public/assets/scripts/choices.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const levelSelect = document.querySelector('select[name="level_id"]');
    if(levelSelect) {
        new Choices(levelSelect, {
            searchEnabled: false,
            shouldSort: false,
            position: 'bottom', // always open downward
        });
    }
});
</script>
@endpush
