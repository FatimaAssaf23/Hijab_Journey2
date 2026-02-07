@extends('layouts.admin')

@section('content')
<div class="min-h-screen">
    <!-- Header -->
    <div class="bg-gradient-to-r from-[#FC8EAC] via-[#EC769A] to-[#6EC6C5] shadow-xl">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <a href="{{ route('admin.dashboard') }}" class="flex items-center justify-center w-12 h-12 bg-white/20 backdrop-blur-xl rounded-xl border border-white/30 shadow-lg hover:bg-white/30 transition-all transform hover:scale-105" title="Go Back">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                        </svg>
                    </a>
                    <div>
                        <h1 class="text-4xl font-extrabold text-white mb-2">📄 Assignments Management</h1>
                        <p class="text-pink-100">View all assignments uploaded by teachers</p>
                    </div>
                </div>
                <div class="bg-white/20 rounded-xl px-6 py-3 backdrop-blur">
                    <p class="text-white font-bold text-xl">{{ $assignments->count() }} Assignment{{ $assignments->count() !== 1 ? 's' : '' }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Success/Error Messages -->
    @if(session('success'))
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-6">
        <div class="bg-green-50 border border-green-200 rounded-xl p-4 flex items-center gap-3 shadow-sm">
            <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <p class="text-green-800 font-semibold">{{ session('success') }}</p>
        </div>
    </div>
    @endif

    <!-- Statistics & Progress Diagrams -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Circular Progress Diagram -->
        <div class="bg-white border-2 border-gray-200 rounded-2xl p-8 shadow-xl">
            <h3 class="text-2xl font-bold text-gray-800 mb-8 text-center flex items-center justify-center gap-3">
                <span class="text-4xl">📊</span>
                Overall Assignment Statistics
            </h3>
            
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <!-- Circular Progress - Assignments -->
                <div class="flex flex-col items-center">
                    <div class="relative w-40 h-40 mb-4">
                        <svg class="transform -rotate-90 w-40 h-40">
                            <circle cx="80" cy="80" r="70" stroke="currentColor" stroke-width="12" fill="none" class="text-gray-200"/>
                            @php
                                $assignmentProgress = $totalAssignments > 0 ? min(($totalAssignments / 100) * 100, 100) : 0;
                                $circumference = 2 * pi() * 70;
                                $offset = $circumference - ($assignmentProgress / 100) * $circumference;
                            @endphp
                            <circle cx="80" cy="80" r="70" stroke="currentColor" stroke-width="12" fill="none" 
                                    stroke-dasharray="{{ $circumference }}"
                                    stroke-dashoffset="{{ $offset }}"
                                    class="text-pink-500 transition-all duration-1000"
                                    stroke-linecap="round"/>
                        </svg>
                        <div class="absolute inset-0 flex items-center justify-center">
                            <div class="text-center">
                                <p class="text-3xl font-bold text-pink-600">{{ $totalAssignments }}</p>
                                <p class="text-xs text-gray-500">Total</p>
                            </div>
                        </div>
                    </div>
                    <h4 class="text-lg font-semibold text-gray-800">Assignments</h4>
                </div>

                <!-- Circular Progress - Submissions Rate -->
                <div class="flex flex-col items-center">
                    <div class="relative w-40 h-40 mb-4">
                        <svg class="transform -rotate-90 w-40 h-40">
                            <circle cx="80" cy="80" r="70" stroke="currentColor" stroke-width="12" fill="none" class="text-gray-200"/>
                            @php
                                $submissionProgress = $totalStudents > 0 ? min(($totalSubmissions / $totalStudents) * 100, 100) : 0;
                                $circumference = 2 * pi() * 70;
                                $offset = $circumference - ($submissionProgress / 100) * $circumference;
                            @endphp
                            <circle cx="80" cy="80" r="70" stroke="currentColor" stroke-width="12" fill="none" 
                                    stroke-dasharray="{{ $circumference }}"
                                    stroke-dashoffset="{{ $offset }}"
                                    class="text-cyan-500 transition-all duration-1000"
                                    stroke-linecap="round"/>
                        </svg>
                        <div class="absolute inset-0 flex items-center justify-center">
                            <div class="text-center">
                                <p class="text-3xl font-bold text-cyan-600">{{ round($submissionProgress) }}%</p>
                                <p class="text-xs text-gray-500">Submitted</p>
                            </div>
                        </div>
                    </div>
                    <h4 class="text-lg font-semibold text-gray-800">Submissions Rate</h4>
                </div>

                <!-- Circular Progress - Average Grade -->
                <div class="flex flex-col items-center">
                    <div class="relative w-40 h-40 mb-4">
                        <svg class="transform -rotate-90 w-40 h-40">
                            <circle cx="80" cy="80" r="70" stroke="currentColor" stroke-width="12" fill="none" class="text-gray-200"/>
                            @php
                                $averageGradeProgress = min($averageGrade, 100);
                                $circumference = 2 * pi() * 70;
                                $offset = $circumference - ($averageGradeProgress / 100) * $circumference;
                            @endphp
                            <circle cx="80" cy="80" r="70" stroke="currentColor" stroke-width="12" fill="none" 
                                    stroke-dasharray="{{ $circumference }}"
                                    stroke-dashoffset="{{ $offset }}"
                                    class="text-pink-500 transition-all duration-1000"
                                    stroke-linecap="round"/>
                        </svg>
                        <div class="absolute inset-0 flex items-center justify-center">
                            <div class="text-center">
                                <p class="text-3xl font-bold text-pink-600">{{ number_format($averageGrade, 1) }}%</p>
                                <p class="text-xs text-gray-500">Average</p>
                            </div>
                        </div>
                    </div>
                    <h4 class="text-lg font-semibold text-gray-800">Average Grade</h4>
                </div>

                <!-- Circular Progress - Grading Rate -->
                <div class="flex flex-col items-center">
                    <div class="relative w-40 h-40 mb-4">
                        <svg class="transform -rotate-90 w-40 h-40">
                            <circle cx="80" cy="80" r="70" stroke="currentColor" stroke-width="12" fill="none" class="text-gray-200"/>
                            @php
                                $gradingProgress = $totalSubmissions > 0 ? min(($totalGraded / $totalSubmissions) * 100, 100) : 0;
                                $circumference = 2 * pi() * 70;
                                $offset = $circumference - ($gradingProgress / 100) * $circumference;
                            @endphp
                            <circle cx="80" cy="80" r="70" stroke="currentColor" stroke-width="12" fill="none" 
                                    stroke-dasharray="{{ $circumference }}"
                                    stroke-dashoffset="{{ $offset }}"
                                    class="text-cyan-500 transition-all duration-1000"
                                    stroke-linecap="round"/>
                        </svg>
                        <div class="absolute inset-0 flex items-center justify-center">
                            <div class="text-center">
                                <p class="text-3xl font-bold text-cyan-600">{{ round($gradingProgress) }}%</p>
                                <p class="text-xs text-gray-500">Graded</p>
                            </div>
                        </div>
                    </div>
                    <h4 class="text-lg font-semibold text-gray-800">Grading Rate</h4>
                </div>
            </div>
        </div>

        <!-- Class-by-Class Statistics Section -->
        @if(!empty($classStats))
        <div class="mt-12 mb-8">
            <div class="bg-gradient-to-r from-pink-100 via-purple-50 to-cyan-100 border-2 border-pink-300 rounded-2xl p-8 shadow-xl">
                <h3 class="text-3xl font-bold text-gray-800 mb-8 flex items-center justify-center gap-3">
                    <span class="text-4xl">🎓</span>
                    Class-by-Class Analysis
                </h3>
                
                <div class="grid grid-cols-1 gap-6">
                    @foreach($classStats as $index => $class)
                    <div x-data="{ expanded: {{ $index === 0 ? 'true' : 'false' }} }" 
                         class="bg-white border-2 {{ $index % 2 == 0 ? 'border-pink-300' : 'border-cyan-300' }} rounded-2xl shadow-lg hover:shadow-xl transition-all overflow-hidden">
                        <!-- Class Header - Clickable -->
                        <button @click="expanded = !expanded" 
                                class="w-full p-6 flex items-center justify-between hover:bg-gradient-to-r {{ $index % 2 == 0 ? 'hover:from-pink-50 hover:to-purple-50' : 'hover:from-cyan-50 hover:to-teal-50' }} transition-all">
                            <div class="flex items-center gap-4 flex-1">
                                <div class="w-16 h-16 rounded-xl {{ $index % 2 == 0 ? 'bg-gradient-to-br from-pink-400 to-pink-600' : 'bg-gradient-to-br from-cyan-400 to-cyan-600' }} flex items-center justify-center shadow-lg">
                                    <span class="text-2xl font-bold text-white">{{ $loop->iteration }}</span>
                                </div>
                                <div class="text-left flex-1">
                                    <h4 class="text-2xl font-bold text-gray-800 mb-1">{{ $class['class_name'] }}</h4>
                                    <p class="text-sm text-gray-600 flex items-center gap-2">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                        </svg>
                                        Teacher: {{ $class['teacher'] }}
                                    </p>
                                </div>
                            </div>
                            <div class="flex items-center gap-6">
                                <!-- Quick Stats -->
                                <div class="flex gap-4">
                                    <div class="text-center">
                                        <p class="text-xs text-gray-500 mb-1">Assignments</p>
                                        <p class="text-xl font-bold {{ $index % 2 == 0 ? 'text-pink-600' : 'text-cyan-600' }}">{{ $class['total_assignments'] }}</p>
                                    </div>
                                    <div class="text-center">
                                        <p class="text-xs text-gray-500 mb-1">Submissions</p>
                                        <p class="text-xl font-bold {{ $index % 2 == 0 ? 'text-pink-600' : 'text-cyan-600' }}">{{ $class['total_submissions'] }}</p>
                                    </div>
                                    <div class="text-center">
                                        <p class="text-xs text-gray-500 mb-1">Avg Grade</p>
                                        <p class="text-xl font-bold {{ $index % 2 == 0 ? 'text-pink-600' : 'text-cyan-600' }}">{{ number_format($class['average_grade'], 1) }}%</p>
                                    </div>
                                </div>
                                <!-- Expand Icon -->
                                <svg class="w-6 h-6 text-gray-400 transform transition-transform duration-300" 
                                     :class="{'rotate-180': expanded}" 
                                     fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                </svg>
                            </div>
                        </button>

                        <!-- Expanded Content -->
                        <div x-show="expanded" 
                             x-transition:enter="transition ease-out duration-300"
                             x-transition:enter-start="opacity-0 transform -translate-y-4"
                             x-transition:enter-end="opacity-100 transform translate-y-0"
                             class="border-t-2 {{ $index % 2 == 0 ? 'border-pink-200' : 'border-cyan-200' }} bg-gradient-to-br {{ $index % 2 == 0 ? 'from-pink-50 to-purple-50' : 'from-cyan-50 to-teal-50' }}">
                            <div class="p-6">
                                <!-- Progress Bars Grid -->
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                                    <!-- Submission Progress -->
                                    <div class="bg-white rounded-xl p-6 border-2 border-pink-200 shadow-md">
                                        <div class="flex items-center justify-between mb-4">
                                            <h5 class="font-bold text-gray-800 flex items-center gap-2">
                                                <span class="text-pink-500">📝</span> Submission Rate
                                            </h5>
                                            <span class="text-2xl font-bold text-pink-600">{{ $class['submission_rate'] }}%</span>
                                        </div>
                                        <div class="w-full bg-gray-200 rounded-full h-4 overflow-hidden">
                                            <div class="h-full bg-gradient-to-r from-pink-400 to-pink-600 rounded-full transition-all duration-1000" 
                                                 style="width: {{ min($class['submission_rate'], 100) }}%"></div>
                                        </div>
                                        <div class="flex justify-between mt-2 text-xs text-gray-600">
                                            <span>{{ $class['total_submissions'] }} submitted</span>
                                            <span>{{ $class['total_students'] }} students</span>
                                        </div>
                                    </div>

                                    <!-- Grading Progress -->
                                    <div class="bg-white rounded-xl p-6 border-2 border-cyan-200 shadow-md">
                                        <div class="flex items-center justify-between mb-4">
                                            <h5 class="font-bold text-gray-800 flex items-center gap-2">
                                                <span class="text-cyan-500">✅</span> Grading Rate
                                            </h5>
                                            <span class="text-2xl font-bold text-cyan-600">{{ $class['grading_rate'] }}%</span>
                                        </div>
                                        <div class="w-full bg-gray-200 rounded-full h-4 overflow-hidden">
                                            <div class="h-full bg-gradient-to-r from-cyan-400 to-cyan-600 rounded-full transition-all duration-1000" 
                                                 style="width: {{ $class['total_submissions'] > 0 ? min(($class['total_graded'] / $class['total_submissions']) * 100, 100) : 0 }}%"></div>
                                        </div>
                                        <div class="flex justify-between mt-2 text-xs text-gray-600">
                                            <span>{{ $class['total_graded'] }} graded</span>
                                            <span>{{ $class['total_pending'] }} pending</span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Stats Grid -->
                                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                                    <div class="bg-white rounded-lg p-4 border border-pink-200 text-center shadow-sm">
                                        <p class="text-xs text-gray-500 mb-1">Total Assignments</p>
                                        <p class="text-2xl font-bold text-pink-600">{{ $class['total_assignments'] }}</p>
                                    </div>
                                    <div class="bg-white rounded-lg p-4 border border-cyan-200 text-center shadow-sm">
                                        <p class="text-xs text-gray-500 mb-1">Total Submissions</p>
                                        <p class="text-2xl font-bold text-cyan-600">{{ $class['total_submissions'] }}</p>
                                    </div>
                                    <div class="bg-white rounded-lg p-4 border border-pink-200 text-center shadow-sm">
                                        <p class="text-xs text-gray-500 mb-1">Graded</p>
                                        <p class="text-2xl font-bold text-pink-600">{{ $class['total_graded'] }}</p>
                                    </div>
                                    <div class="bg-white rounded-lg p-4 border border-orange-200 text-center shadow-sm">
                                        <p class="text-xs text-gray-500 mb-1">Pending</p>
                                        <p class="text-2xl font-bold text-orange-600">{{ $class['total_pending'] }}</p>
                                    </div>
                                </div>

                                <!-- Class Assignments Cards -->
                                @php
                                    $classAssignments = $assignments->where('class_id', $class['class_id']);
                                @endphp
                                @if($classAssignments->count() > 0)
                                <div x-data="{ showAssignments: true }" class="mt-8 pt-6 border-t-2 {{ $index % 2 == 0 ? 'border-pink-200' : 'border-cyan-200' }}">
                                    <div class="flex items-center justify-between mb-6">
                                        <h5 class="font-bold text-gray-800 flex items-center gap-2 text-xl">
                                            <span>📄</span> Class Assignments ({{ $classAssignments->count() }})
                                        </h5>
                                        <button @click="showAssignments = !showAssignments" 
                                                class="flex items-center gap-2 px-4 py-2 {{ $index % 2 == 0 ? 'bg-pink-500 hover:bg-pink-600' : 'bg-cyan-500 hover:bg-cyan-600' }} text-white rounded-xl font-semibold transition-all shadow-lg hover:shadow-xl transform hover:scale-105">
                                            <svg class="w-5 h-5 transition-transform duration-300" 
                                                 :class="{ 'rotate-180': !showAssignments }" 
                                                 fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                            </svg>
                                            <span x-text="showAssignments ? 'Hide' : 'Show'"></span>
                                        </button>
                                    </div>
                                    <div x-show="showAssignments" 
                                         x-transition:enter="transition ease-out duration-300"
                                         x-transition:enter-start="opacity-0 transform -translate-y-4"
                                         x-transition:enter-end="opacity-100 transform translate-y-0"
                                         class="space-y-6">
                                        @foreach($classAssignments as $classAssignment)
                                        <div x-data="{ 
                                            expanded: false,
                                            showCommentForm: false,
                                            showSubmissions: false 
                                        }" 
                                        class="bg-white rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300 border-2 border-pink-100 overflow-hidden">
                                            
                                            <!-- Assignment Header Card -->
                                            <div class="bg-gradient-to-r from-pink-50 via-purple-50 to-teal-50 p-6 border-b-2 border-pink-200">
                                                <div class="flex items-start justify-between gap-4">
                                                    <div class="flex-1">
                                                        <div class="flex items-center gap-3 mb-3">
                                                            <div class="w-12 h-12 bg-gradient-to-br from-pink-400 to-purple-500 rounded-xl flex items-center justify-center text-white font-bold text-xl shadow-lg">
                                                                {{ strtoupper(substr($classAssignment->title, 0, 2)) }}
                                                            </div>
                                                            <div>
                                                                <h3 class="text-2xl font-extrabold text-gray-800 mb-1">{{ $classAssignment->title }}</h3>
                                                                <div class="flex items-center gap-4 text-sm text-gray-600">
                                                                    <span class="flex items-center gap-1">
                                                                        <svg class="w-4 h-4 text-pink-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                                                        </svg>
                                                                        {{ $classAssignment->teacher ? $classAssignment->teacher->first_name . ' ' . $classAssignment->teacher->last_name : 'Unknown Teacher' }}
                                                                    </span>
                                                                    <span class="flex items-center gap-1">
                                                                        <svg class="w-4 h-4 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                                                                        </svg>
                                                                        {{ $classAssignment->studentClass ? $classAssignment->studentClass->class_name : 'Unknown Class' }}
                                                                    </span>
                                                                    <span class="flex items-center gap-1">
                                                                        <svg class="w-4 h-4 text-teal-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                                                        </svg>
                                                                        {{ $classAssignment->level ? $classAssignment->level->level_name : 'N/A' }}
                                                                    </span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        
                                                        @if($classAssignment->description)
                                                        <p class="text-gray-700 mb-4 bg-white/70 rounded-lg p-3 border border-pink-200">{{ $classAssignment->description }}</p>
                                                        @endif
                                                        
                                                        <!-- Stats Bar -->
                                                        <div class="grid grid-cols-4 gap-3 mt-4">
                                                            <div class="bg-white rounded-lg p-3 border border-pink-200 text-center">
                                                                <p class="text-xs text-gray-600 mb-1">Total Students</p>
                                                                <p class="text-xl font-bold text-purple-600">{{ $classAssignment->total_students }}</p>
                                                            </div>
                                                            <div class="bg-white rounded-lg p-3 border border-green-200 text-center">
                                                                <p class="text-xs text-gray-600 mb-1">Submitted</p>
                                                                <p class="text-xl font-bold text-green-600">{{ $classAssignment->submitted_count }}</p>
                                                            </div>
                                                            <div class="bg-white rounded-lg p-3 border border-blue-200 text-center">
                                                                <p class="text-xs text-gray-600 mb-1">Graded</p>
                                                                <p class="text-xl font-bold text-blue-600">{{ $classAssignment->graded_count }}</p>
                                                            </div>
                                                            <div class="bg-white rounded-lg p-3 border border-orange-200 text-center">
                                                                <p class="text-xs text-gray-600 mb-1">Pending</p>
                                                                <p class="text-xl font-bold text-orange-600">{{ $classAssignment->pending_grading }}</p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    
                                                    <!-- Due Date Badge -->
                                                    <div class="text-right">
                                                        <div class="bg-white rounded-xl p-4 border-2 border-pink-300 shadow-lg">
                                                            <p class="text-xs text-gray-600 mb-1">Due Date</p>
                                                            <p class="text-lg font-bold text-pink-600">{{ \Carbon\Carbon::parse($classAssignment->due_date)->format('M d, Y') }}</p>
                                                            <p class="text-sm text-gray-500">{{ \Carbon\Carbon::parse($classAssignment->due_date)->format('h:i A') }}</p>
                                                            @if(\Carbon\Carbon::parse($classAssignment->due_date)->isPast())
                                                                <span class="inline-block mt-2 px-2 py-1 bg-red-100 text-red-700 rounded-full text-xs font-semibold">Overdue</span>
                                                            @elseif(\Carbon\Carbon::parse($classAssignment->due_date)->isToday())
                                                                <span class="inline-block mt-2 px-2 py-1 bg-orange-100 text-orange-700 rounded-full text-xs font-semibold">Due Today</span>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Action Buttons -->
                                            <div class="p-6 bg-gray-50 border-b border-gray-200">
                                                <div class="flex flex-wrap gap-3">
                                                    <button @click="expanded = !expanded" 
                                                            class="flex items-center gap-2 px-4 py-2 bg-pink-500 hover:bg-pink-600 text-white rounded-xl font-semibold transition-all shadow-lg hover:shadow-xl transform hover:scale-105">
                                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                                        </svg>
                                                        <span x-text="expanded ? 'Hide Details' : 'View Details'"></span>
                                                    </button>
                                                    
                                                    <a href="{{ ($classAssignment->file_path ? asset('storage/' . $classAssignment->file_path) : ($classAssignment->pdf_url ?? '#')) }}" 
                                                       target="_blank"
                                                       class="flex items-center gap-2 px-4 py-2 bg-cyan-500 hover:bg-cyan-600 text-white rounded-xl font-semibold transition-all shadow-lg hover:shadow-xl transform hover:scale-105">
                                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                                        </svg>
                                                        View Assignment File
                                                    </a>
                                                    
                                                    <button @click="showSubmissions = !showSubmissions" 
                                                            class="flex items-center gap-2 px-4 py-2 bg-pink-500 hover:bg-pink-600 text-white rounded-xl font-semibold transition-all shadow-lg hover:shadow-xl transform hover:scale-105">
                                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                                        </svg>
                                                        <span x-text="showSubmissions ? 'Hide' : 'View'"></span> Submissions ({{ $classAssignment->submissions->count() }})
                                                    </button>
                                                    
                                                    <button @click="showCommentForm = !showCommentForm" 
                                                            class="flex items-center gap-2 px-4 py-2 bg-cyan-500 hover:bg-cyan-600 text-white rounded-xl font-semibold transition-all shadow-lg hover:shadow-xl transform hover:scale-105">
                                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                                        </svg>
                                                        <span x-text="showCommentForm ? 'Cancel' : 'Add Comment'"></span>
                                                    </button>
                                                </div>
                                            </div>

                                            <!-- Expanded Details Section -->
                                            <div x-show="expanded" 
                                                 x-transition:enter="transition ease-out duration-300"
                                                 x-transition:enter-start="opacity-0 transform -translate-y-4"
                                                 x-transition:enter-end="opacity-100 transform translate-y-0"
                                                 class="p-6 bg-gray-50 border-t border-gray-200">
                                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                                    <div class="bg-white rounded-xl p-5 border border-pink-200">
                                                        <h4 class="font-bold text-gray-800 mb-3 flex items-center gap-2">
                                                            <span class="text-pink-500">📋</span> Assignment Information
                                                        </h4>
                                                        <div class="space-y-2 text-sm">
                                                            <div class="flex justify-between">
                                                                <span class="text-gray-600">Posted Date:</span>
                                                                <span class="font-semibold">{{ $classAssignment->created_at ? $classAssignment->created_at->format('M d, Y h:i A') : 'N/A' }}</span>
                                                            </div>
                                                            <div class="flex justify-between">
                                                                <span class="text-gray-600">Submission Progress:</span>
                                                                <span class="font-semibold text-green-600">
                                                                    {{ $classAssignment->total_students > 0 ? round(($classAssignment->submitted_count / $classAssignment->total_students) * 100) : 0 }}%
                                                                </span>
                                                            </div>
                                                            <div class="flex justify-between">
                                                                <span class="text-gray-600">Grading Progress:</span>
                                                                <span class="font-semibold text-blue-600">
                                                                    {{ $classAssignment->submitted_count > 0 ? round(($classAssignment->graded_count / $classAssignment->submitted_count) * 100) : 0 }}%
                                                                </span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="bg-white rounded-xl p-5 border border-purple-200">
                                                        <h4 class="font-bold text-gray-800 mb-3 flex items-center gap-2">
                                                            <span class="text-purple-500">👤</span> Admin Review
                                                        </h4>
                                                        @if($classAssignment->admin_comment)
                                                        <div class="bg-purple-50 rounded-lg p-4 border border-purple-200">
                                                            <p class="text-sm text-gray-700 mb-2">{{ $classAssignment->admin_comment }}</p>
                                                            @if($classAssignment->checkedByAdmin)
                                                            <p class="text-xs text-gray-500">
                                                                Reviewed by: {{ $classAssignment->checkedByAdmin->first_name }} {{ $classAssignment->checkedByAdmin->last_name }}
                                                                on {{ $classAssignment->updated_at->format('M d, Y') }}
                                                            </p>
                                                            @endif
                                                        </div>
                                                        @else
                                                        <p class="text-sm text-gray-500 italic">No admin comment yet</p>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Comment Form -->
                                            <div x-show="showCommentForm" 
                                                 x-transition:enter="transition ease-out duration-300"
                                                 x-transition:enter-start="opacity-0 transform -translate-y-4"
                                                 x-transition:enter-end="opacity-100 transform translate-y-0"
                                                 class="p-6 bg-cyan-50 border-t-2 border-cyan-400 shadow-inner">
                                                <form method="POST" action="{{ route('admin.assignments.comment', $classAssignment->assignment_id) }}" class="space-y-4">
                                                    @csrf
                                                    <label class="block font-bold text-gray-800 mb-2">Add Admin Comment</label>
                                                    <textarea name="admin_comment" 
                                                              rows="4" 
                                                              class="w-full rounded-xl border-2 border-cyan-400 focus:border-cyan-500 focus:ring-4 focus:ring-cyan-300 p-4 resize-none bg-white shadow-sm"
                                                              placeholder="Enter your comment or feedback about this assignment...">{{ old('admin_comment', $classAssignment->admin_comment) }}</textarea>
                                                    <div class="flex gap-3">
                                                        <button type="submit" 
                                                                class="px-6 py-2 bg-pink-500 hover:bg-pink-600 text-white rounded-xl font-semibold transition-all shadow-lg hover:shadow-xl transform hover:scale-105">
                                                            Save Comment
                                                        </button>
                                                        <button type="button" 
                                                                @click="showCommentForm = false"
                                                                class="px-6 py-2 bg-cyan-200 hover:bg-cyan-300 text-gray-700 rounded-xl font-semibold transition-all shadow-md hover:shadow-lg">
                                                            Cancel
                                                        </button>
                                                    </div>
                                                </form>
                                            </div>

                                            <!-- Submissions Section -->
                                            <div x-show="showSubmissions" 
                                                 x-transition:enter="transition ease-out duration-300"
                                                 x-transition:enter-start="opacity-0 transform -translate-y-4"
                                                 x-transition:enter-end="opacity-100 transform translate-y-0"
                                                 class="p-6 bg-green-50 border-t border-green-200">
                                                <h4 class="text-xl font-bold text-gray-800 mb-4 flex items-center gap-2">
                                                    <span class="text-green-600">📝</span> Student Submissions ({{ $classAssignment->submissions->count() }})
                                                </h4>
                                                
                                                @if($classAssignment->submissions->isEmpty())
                                                <div class="bg-white rounded-xl p-8 text-center border border-green-200">
                                                    <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                                        <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                                        </svg>
                                                    </div>
                                                    <p class="text-gray-600 font-semibold">No submissions yet</p>
                                                    <p class="text-sm text-gray-500 mt-1">Students haven't submitted their work for this assignment</p>
                                                </div>
                                                @else
                                                <div class="space-y-4">
                                                    @foreach($classAssignment->submissions as $submission)
                                                    <div class="bg-white rounded-xl p-5 border-2 {{ $submission->grade ? 'border-green-300' : 'border-yellow-300' }} shadow-md hover:shadow-lg transition-all">
                                                        <div class="flex items-start justify-between gap-4">
                                                            <div class="flex-1">
                                                                <div class="flex items-center gap-3 mb-3">
                                                                    <div class="w-10 h-10 bg-gradient-to-br from-pink-400 to-purple-500 rounded-full flex items-center justify-center text-white font-bold">
                                                                        {{ $submission->student && $submission->student->user ? strtoupper(substr($submission->student->user->first_name, 0, 1)) : '?' }}
                                                                    </div>
                                                                    <div>
                                                                        <h5 class="font-bold text-gray-800 text-lg">
                                                                            {{ $submission->student && $submission->student->user ? $submission->student->user->first_name . ' ' . $submission->student->user->last_name : 'Unknown Student' }}
                                                                        </h5>
                                                                        <p class="text-sm text-gray-600">
                                                                            Submitted: {{ $submission->submitted_at ? $submission->submitted_at->format('M d, Y h:i A') : 'N/A' }}
                                                                            @if($submission->is_late)
                                                                                <span class="ml-2 px-2 py-1 bg-red-100 text-red-700 rounded-full text-xs font-semibold">Late</span>
                                                                            @endif
                                                                        </p>
                                                                    </div>
                                                                </div>
                                                                
                                                                <div class="mb-3">
                                                                    <a href="{{ $submission->submission_file_url ? asset('storage/' . $submission->submission_file_url) : '#' }}" 
                                                                       target="_blank"
                                                                       class="inline-flex items-center gap-2 px-4 py-2 bg-cyan-500 hover:bg-cyan-600 text-white rounded-lg font-semibold transition-all shadow-md hover:shadow-lg transform hover:scale-105">
                                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                                                        </svg>
                                                                        View Submission
                                                                    </a>
                                                                </div>
                                                                
                                                                @if($submission->grade)
                                                                <div class="bg-gradient-to-r from-green-50 to-emerald-50 rounded-lg p-4 border-2 border-green-300">
                                                                    <div class="flex items-center justify-between mb-2">
                                                                        <span class="font-bold text-green-800">Grade:</span>
                                                                        <span class="text-2xl font-extrabold text-green-700">
                                                                            {{ $submission->grade->grade_value }} / {{ $submission->grade->max_grade ?? 100 }}
                                                                            <span class="text-lg">({{ number_format($submission->grade->percentage ?? 0, 1) }}%)</span>
                                                                        </span>
                                                                    </div>
                                                                    @if($submission->grade->feedback)
                                                                    <div class="mt-3 pt-3 border-t border-green-200">
                                                                        <p class="text-sm font-semibold text-green-900 mb-1">Teacher Feedback:</p>
                                                                        <p class="text-sm text-green-800">{{ $submission->grade->feedback }}</p>
                                                                    </div>
                                                                    @endif
                                                                </div>
                                                                @else
                                                                <div class="bg-yellow-50 rounded-lg p-4 border-2 border-yellow-300">
                                                                    <p class="text-yellow-800 font-semibold">⏳ Awaiting Grade</p>
                                                                    <p class="text-sm text-yellow-700 mt-1">This submission has not been graded yet</p>
                                                                </div>
                                                                @endif
                                                            </div>
                                                            
                                                            @if($submission->grade)
                                                            <div class="flex items-center justify-center">
                                                                <div class="w-20 h-20 bg-gradient-to-br from-green-400 to-emerald-500 rounded-full flex items-center justify-center text-white font-bold text-2xl shadow-lg">
                                                                    {{ number_format($submission->grade->percentage ?? 0, 0) }}%
                                                                </div>
                                                            </div>
                                                            @endif
                                                        </div>
                                                    </div>
                                                    @endforeach
                                                </div>
                                                @endif
                                            </div>
                                        </div>
                                        @endforeach
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
        @endif
    </div>

    @if($assignments->isEmpty() && empty($classStats))
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="bg-white rounded-2xl shadow-xl p-12 text-center border-2 border-pink-200">
            <div class="w-24 h-24 bg-gradient-to-br from-pink-100 to-purple-100 rounded-full flex items-center justify-center mx-auto mb-6">
                <span class="text-5xl">📄</span>
            </div>
            <h3 class="text-2xl font-bold text-gray-800 mb-2">No Assignments Yet</h3>
            <p class="text-gray-600">Teachers haven't uploaded any assignments yet. They will appear here once uploaded.</p>
        </div>
    </div>
    @endif
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
@endpush