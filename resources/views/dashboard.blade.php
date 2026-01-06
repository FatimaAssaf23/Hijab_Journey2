@extends('layouts.app')

@section('content')
<div class="max-w-5xl mx-auto px-2 lg:px-0 min-h-screen bg-gradient-to-br from-pink-100 via-purple-100 to-white relative">
    <!-- Decorative background shapes -->
    <div class="absolute left-0 top-0 w-72 h-72 bg-pink-200 rounded-full opacity-30 blur-2xl z-0"></div>
    <div class="absolute right-0 bottom-0 w-72 h-72 bg-purple-200 rounded-full opacity-30 blur-2xl z-0"></div>
    <div class="relative z-10 bg-white/80 shadow-2xl rounded-3xl p-10 mt-16 border border-pink-100 overflow-hidden group transition-all duration-300 hover:scale-[1.01]">
        <!-- Hijabi Girl Image in Pink Circle -->
        <div class="absolute right-16 top-16 flex flex-col items-center z-20">
            <div class="w-36 h-36 rounded-full bg-pink-200 flex items-center justify-center shadow-lg border-4 border-white mt-[-60px]">
                <img src="{{ asset('images/dashboard/hijabi1.jpg') }}" alt="Hijabi Girl 1" class="w-28 h-28 rounded-full object-contain p-2 shadow-xl border-2 border-pink-300 bg-white" loading="lazy">
            </div>
            <span class="mt-2 text-pink-500 font-bold text-lg drop-shadow">Welcome!</span>
        </div>
        <div class="relative z-10">
            <!-- Hijabi Girl Image now inside pink circle above -->
            <h2 class="font-black text-4xl text-pink-600 mb-4 flex items-center gap-3 tracking-tight drop-shadow-lg">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-pink-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5zm0 7v-6m0 6H5a2 2 0 01-2-2V7a2 2 0 012-2h14a2 2 0 012 2v12a2 2 0 01-2 2h-7z" /></svg>
                Student Dashboard
            </h2>
            <div class="mb-8 text-lg text-purple-500 italic font-medium">A girl is like a pearl; she needs a hijab to protect her.</div>
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
                <div class="mb-6 p-4 rounded-xl bg-pink-50 border border-pink-200 shadow flex flex-col gap-2">
                    <div class="font-bold text-pink-600 text-lg">Class Info</div>
                    <div><span class="font-semibold">Class Name:</span> {{ $class->class_name }}</div>
                    @if(!empty($class->class_message))
                        <div class="text-purple-700"><span class="font-semibold">Message:</span> {{ $class->class_message }}</div>
                    @endif
                    <div><span class="font-semibold">Teacher:</span> {{ $class->teacher?->first_name }} {{ $class->teacher?->last_name }}</div>
                </div>
                <div class="flex flex-col lg:flex-row gap-8 items-stretch">
                    <!-- Class Info Card -->
                    <div class="rounded-2xl p-8 shadow-2xl flex-1 flex flex-col gap-6 border border-pink-100 ring-2 ring-pink-50 min-w-[280px] max-w-lg bg-gradient-to-br from-pink-50 via-white to-purple-50">
                        <div class="flex items-center gap-4 mb-2">
                            <span class="inline-block bg-gradient-to-r from-pink-400 to-purple-400 text-white px-8 py-3 rounded-full text-2xl font-black shadow-xl tracking-tight border-2 border-pink-200">{{ $class->class_name }}</span>
                        </div>
                        <div class="grid grid-cols-2 gap-6 w-full">
                            <div class="bg-blue-100/70 rounded-xl p-5 flex flex-col items-start shadow-md">
                                <div class="text-xs text-gray-500 mb-1 flex items-center gap-1">👨‍🏫 <span>Teacher</span></div>
                                <div class="font-semibold text-gray-800 text-base">{{ $class->teacher ? $class->teacher->first_name . ' ' . $class->teacher->last_name : 'Unassigned' }}</div>
                            </div>
                            <div class="bg-purple-100/70 rounded-xl p-5 flex flex-col items-start shadow-md">
                                <div class="text-xs text-gray-500 mb-1 flex items-center gap-1">👥 <span>Students</span></div>
                                <div class="text-2xl font-bold text-purple-600">{{ $class->students->count() }}</div>
                            </div>
                            <div class="bg-pink-100/70 rounded-xl p-5 flex flex-col items-start shadow-md">
                                <div class="text-xs text-gray-500 mb-1 flex items-center gap-1">📊 <span>Capacity</span></div>
                                <div class="text-2xl font-bold text-pink-600">{{ $class->capacity }}</div>
                            </div>
                            <div class="bg-gray-100/70 rounded-xl p-5 flex flex-col items-start shadow-md">
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
                            <div class="bg-gradient-to-r from-green-200 via-green-100 to-white rounded-xl p-5 flex flex-col items-start shadow-xl border-2 border-green-200 col-span-2">
                                <div class="flex items-center gap-2 mb-1">
                                    <svg xmlns='http://www.w3.org/2000/svg' class='h-5 w-5 text-green-500' fill='none' viewBox='0 0 24 24' stroke='currentColor'><path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M5 13l4 4L19 7' /></svg>
                                    <span class="text-xs text-green-700 font-semibold uppercase tracking-wide">Lessons Completed</span>
                                </div>
                                <span class="text-3xl font-extrabold text-green-700 drop-shadow">{{ $lessonsCompleted }}</span>
                            </div>
                        </div>
                        <!-- Progress bar placeholder for future features -->
                        <div class="w-full mt-4">
                            <div class="text-xs text-pink-400 mb-2 font-bold">Progress</div>
                            <div class="w-full bg-pink-100 rounded-full h-3">
                                <div class="bg-gradient-to-r from-pink-400 to-purple-400 h-3 rounded-full transition-all duration-700" style="width: 40%"></div>
                            </div>
                        </div>
                    </div>
                    <!-- To-Do List: Upcoming Assignments (Inline Cards) -->
                    <div class="bg-gradient-to-br from-pink-50 via-white to-purple-50 rounded-2xl p-8 shadow-2xl border border-pink-100 flex-1 mt-0 w-full min-w-[280px] max-w-2xl flex flex-col">
                        <div class="flex items-center gap-3 mb-6">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-pink-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m2 0a2 2 0 100-4H7a2 2 0 100 4zm0 0v4a2 2 0 11-4 0v-4" /></svg>
                            <span class="font-black text-pink-600 text-2xl tracking-tight drop-shadow">Upcoming Assignments</span>
                        </div>
                        @if($upcomingAssignments->count())
                            <div class="flex gap-8 overflow-x-auto pb-2 scrollbar-thin scrollbar-thumb-pink-200 scrollbar-track-pink-50">
                                @foreach($upcomingAssignments as $assignment)
                                    <div class="min-w-[240px] max-w-xs bg-gradient-to-br from-pink-100 via-white to-purple-100 rounded-2xl shadow-xl p-6 flex flex-col justify-between border-2 border-pink-200 hover:scale-105 hover:shadow-2xl transition-all duration-300">
                                        <div>
                                            <div class="font-bold text-pink-600 text-xl mb-2 truncate drop-shadow">{{ $assignment->title }}</div>
                                            <div class="text-xs text-purple-500 flex items-center gap-2 mb-3"><svg xmlns='http://www.w3.org/2000/svg' class='h-5 w-5 text-pink-300' fill='none' viewBox='0 0 24 24' stroke='currentColor'><path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z' /></svg> Due: {{ \Carbon\Carbon::parse($assignment->due_date)->format('M d, Y') }}</div>
                                        </div>
                                        <a href="{{ asset('storage/' . $assignment->file_path) }}" class="mt-2 inline-block bg-gradient-to-r from-pink-400 to-purple-400 text-white px-6 py-2 rounded-xl shadow-lg hover:scale-110 hover:shadow-2xl transition text-base font-bold text-center" target="_blank">View</a>
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
