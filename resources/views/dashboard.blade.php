@extends('layouts.app')

@section('content')
<div class="max-w-5xl mx-auto px-2 lg:px-0" style="background-color: #FFF4FA; min-height: 100vh;">
    <div class="relative" style="background-color: #FFF4FA;" class="bg-gradient-to-br from-pink-100 via-purple-100 to-white shadow-2xl rounded-3xl p-10 mt-12 border border-pink-100 overflow-hidden group transition-all duration-300 hover:scale-[1.01]">
        <!-- Glassmorphism effect -->
        <div class="absolute inset-0 bg-white/60 backdrop-blur-xl rounded-3xl z-0"></div>
        <!-- Decorative SVG background -->
        <svg class="absolute right-0 top-0 w-56 h-56 opacity-10 pointer-events-none z-0" viewBox="0 0 200 200" fill="none" xmlns="http://www.w3.org/2000/svg">
            <circle cx="100" cy="100" r="100" fill="#f472b6"/>
        </svg>
        <div class="relative z-10">
            <h2 class="font-black text-3xl text-pink-600 mb-6 flex items-center gap-3 tracking-tight">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-pink-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5zm0 7v-6m0 6H5a2 2 0 01-2-2V7a2 2 0 012-2h14a2 2 0 012 2v12a2 2 0 01-2 2h-7z" /></svg>
                Student Dashboard
            </h2>
            <div class="mb-6 text-base text-gray-500 italic">“Education is the passport to the future, for tomorrow belongs to those who prepare for it today.”</div>
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
            @if($class)
                <div class="flex flex-col lg:flex-row gap-8 items-stretch">
                    <!-- Class Info Card -->
                    <div style="background-color: #FFF4FA;" class="rounded-2xl p-6 shadow-xl flex-1 flex flex-col gap-5 border border-pink-100 ring-1 ring-pink-50 min-w-[280px] max-w-lg">
                        <div class="flex items-center gap-4 mb-2">
                            <span class="inline-block bg-gradient-to-r from-pink-400 to-purple-400 text-white px-6 py-2 rounded-full text-2xl font-black shadow-lg tracking-tight">{{ $class->class_name }}</span>
                        </div>
                        <div class="grid grid-cols-2 gap-5 w-full">
                            <div class="bg-blue-50/70 rounded-xl p-4 flex flex-col items-start shadow-sm">
                                <div class="text-xs text-gray-500 mb-1 flex items-center gap-1">👨‍🏫 <span>Teacher</span></div>
                                <div class="font-semibold text-gray-800 text-base">{{ $class->teacher ? $class->teacher->first_name . ' ' . $class->teacher->last_name : 'Unassigned' }}</div>
                            </div>
                            <div class="bg-purple-50/70 rounded-xl p-4 flex flex-col items-start shadow-sm">
                                <div class="text-xs text-gray-500 mb-1 flex items-center gap-1">👥 <span>Students</span></div>
                                <div class="text-2xl font-bold text-purple-600">{{ $class->students->count() }}</div>
                            </div>
                            <div class="bg-pink-50/70 rounded-xl p-4 flex flex-col items-start shadow-sm">
                                <div class="text-xs text-gray-500 mb-1 flex items-center gap-1">📊 <span>Capacity</span></div>
                                <div class="text-2xl font-bold text-pink-600">{{ $class->capacity }}</div>
                            </div>
                            <div class="bg-gray-50/70 rounded-xl p-4 flex flex-col items-start shadow-sm">
                                <div class="text-xs text-gray-500 mb-1 flex items-center gap-1">🔖 <span>Status</span></div>
                                <span class="inline-block px-4 py-1 rounded-full font-bold text-xs tracking-wide
                                    @if($class->status === 'active') bg-green-100 text-green-800
                                    @elseif($class->status === 'full') bg-yellow-100 text-yellow-800
                                    @elseif($class->status === 'closed') bg-red-100 text-red-800
                                    @else bg-gray-200 text-gray-700 @endif">
                                    {{ ucfirst($class->status) }}
                                </span>
                            </div>
                            <!-- Lessons Completed -->
                            <div class="bg-gradient-to-r from-green-200 via-green-100 to-white rounded-xl p-4 flex flex-col items-start shadow-lg border border-green-200 col-span-2">
                                <div class="flex items-center gap-2 mb-1">
                                    <svg xmlns='http://www.w3.org/2000/svg' class='h-5 w-5 text-green-500' fill='none' viewBox='0 0 24 24' stroke='currentColor'><path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M5 13l4 4L19 7' /></svg>
                                    <span class="text-xs text-green-700 font-semibold uppercase tracking-wide">Lessons Completed</span>
                                </div>
                                <span class="text-3xl font-extrabold text-green-700 drop-shadow">{{ $lessonsCompleted }}</span>
                            </div>
                        </div>
                        <!-- Progress bar placeholder for future features -->
                        <div class="w-full mt-2">
                            <div class="text-xs text-gray-400 mb-1">Progress</div>
                            <div class="w-full bg-pink-100 rounded-full h-2.5">
                                <div class="bg-gradient-to-r from-pink-400 to-purple-400 h-2.5 rounded-full transition-all duration-700" style="width: 40%"></div>
                            </div>
                        </div>
                    </div>
                    <!-- To-Do List: Upcoming Assignments (Inline Cards) -->
                    <div class="bg-white/70 rounded-2xl p-6 shadow-lg border border-pink-100 flex-1 mt-0 w-full min-w-[280px] max-w-2xl flex flex-col">
                        <div class="flex items-center gap-3 mb-4">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-pink-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m2 0a2 2 0 100-4H7a2 2 0 100 4zm0 0v4a2 2 0 11-4 0v-4" /></svg>
                            <span class="font-black text-pink-600 text-xl tracking-tight">Upcoming Assignments</span>
                        </div>
                        @if($upcomingAssignments->count())
                            <div class="flex gap-5 overflow-x-auto pb-2 scrollbar-thin scrollbar-thumb-pink-200 scrollbar-track-pink-50">
                                @foreach($upcomingAssignments as $assignment)
                                    <div class="min-w-[220px] max-w-xs bg-gradient-to-br from-pink-50 via-purple-50 to-white rounded-xl shadow-md p-5 flex flex-col justify-between border border-pink-100 hover:shadow-xl transition-all duration-300">
                                        <div>
                                            <div class="font-bold text-gray-800 text-lg mb-1 truncate">{{ $assignment->title }}</div>
                                            <div class="text-xs text-gray-500 flex items-center gap-1 mb-2"><svg xmlns='http://www.w3.org/2000/svg' class='h-4 w-4 text-pink-300' fill='none' viewBox='0 0 24 24' stroke='currentColor'><path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z' /></svg> Due: {{ \Carbon\Carbon::parse($assignment->due_date)->format('M d, Y') }}</div>
                                        </div>
                                        <a href="{{ asset('storage/' . $assignment->file_path) }}" class="mt-2 inline-block bg-gradient-to-r from-pink-400 to-purple-400 text-white px-4 py-1.5 rounded-lg shadow hover:scale-105 hover:shadow-xl transition text-sm font-bold text-center" target="_blank">View</a>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-gray-400 text-base py-4">No upcoming assignments.</div>
                        @endif
                    </div>
                </div>
            @else
                <div class="text-center text-gray-400 py-16 text-lg italic relative">
                    You are not enrolled in any class yet.
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
