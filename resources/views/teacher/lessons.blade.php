@extends('layouts.app')

@section('content')
<div class="bg-gradient-to-r from-[#FC8EAC] via-[#EC769A] to-[#6EC6C5] shadow-xl mb-8">
    <div class="w-full px-4 sm:px-6 lg:px-8 py-8 flex items-center justify-between">
        <div class="flex items-center gap-4">
            <!-- Go Back Button -->
            <button onclick="goBackOrRedirect('{{ route('teacher.dashboard') }}')" 
                    class="flex items-center gap-2 px-6 py-3 rounded-xl font-semibold text-white shadow-lg hover:shadow-xl transition-all duration-300 transform hover:scale-105 border-2 border-white/30" 
                    style="background: rgba(255, 255, 255, 0.2); backdrop-filter: blur(10px);">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Go Back
            </button>
            <span class="text-5xl align-middle">📚</span>
            <div>
                <h1 class="text-4xl font-extrabold text-white mb-2">Lessons Management</h1>
            </div>
        </div>
        <div>
            @if(!$hasSchedule)
                <button onclick="generateSchedule()" 
                        class="flex items-center gap-2 px-6 py-3 rounded-xl font-semibold text-white shadow-lg hover:shadow-xl transition-all duration-300 transform hover:scale-105 bg-white/20 backdrop-blur-md border-2 border-white/30">
                    <span>📅</span> Generate Auto-Schedule
                </button>
            @else
                <a href="{{ route('teacher.schedule.show') }}" 
                   class="flex items-center gap-2 px-6 py-3 rounded-xl font-semibold text-white shadow-lg hover:shadow-xl transition-all duration-300 transform hover:scale-105 bg-white/20 backdrop-blur-md border-2 border-white/30">
                    <span>📅</span> View Schedule
                </a>
            @endif
        </div>
    </div>
</div>

@if(!$hasSchedule)
<script>
function generateSchedule() {
    if (!confirm('This will generate an automatic schedule for all your lessons. Continue?')) {
        return;
    }
    
    // Get the first class ID (or let user select)
    fetch('{{ route("teacher.schedule.generate") }}', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({})
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Schedule generated successfully!');
            location.reload();
        } else {
            alert('Error: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error generating schedule. Please try again.');
    });
}
</script>
@endif
<div class="w-full min-h-screen px-4 sm:px-6 lg:px-8 py-5 flex flex-col items-center">
    @foreach($levels as $level)
        <div class="mb-8 w-full" x-data="{ showLessons: true }">
            <div class="rounded-2xl px-6 py-4 mb-4 flex items-start justify-between" style="background: linear-gradient(90deg, #F8C5C8 0%, #FC8EAC 50%, #EC769A 100%); color: #222;">
                <div>
                    <h2 class="text-2xl font-extrabold mb-1">Level {{ $level->level_number ?? $level->level_id }}</h2>
                    <div class="text-base opacity-80 mb-1">{{ $level->level_name }}</div>
                    <div class="text-sm opacity-70">{{ $level->description ?? 'Curriculum' }} &bull; {{ $level->lessons->count() }} lessons</div>
                </div>
                <button @click="showLessons = !showLessons" type="button" class="ml-4 px-4 py-2 rounded-xl font-semibold text-white bg-[#EC769A] hover:bg-[#FC8EAC] transition shadow" x-text="showLessons ? 'Hide Lessons' : 'Show Lessons'"></button>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6" x-show="showLessons" x-transition>
                @forelse($level->lessons as $lesson)
                    <div class="rounded-2xl shadow-lg p-6 flex flex-col gap-3 w-full"
                        style="background: linear-gradient(135deg, #b2f7ef 0%, #f6d6d6 100%); border: 1.5px solid #EAD8C0;">
                        <div class="flex items-center gap-3 mb-2">
                            <span class="text-4xl">{{ $lesson->icon ?? '📘' }}</span>
                            <div class="flex-1">
                                <div class="font-bold text-lg text-[#197D8C]">{{ $lesson->title }}</div>
                                <div class="text-xs text-[#6EC6C5]">Duration: {{ ($lesson->duration_minutes === null || $lesson->duration_minutes == 0) ? '0' : $lesson->duration_minutes }} min</div>
                            </div>
                        </div>
                        <div class="text-gray-700 mb-2">{{ $lesson->description }}</div>
                        <div class="mb-2">
                            <div class="font-semibold text-sm text-[#197D8C] mb-1">Class Visibility:</div>
                            <ul class="space-y-1">
                                @foreach($classes as $class)
                                    @php
                                        $visibility = $lesson->classLessonVisibilities->firstWhere('class_id', $class->class_id);
                                    @endphp
                                    <li class="flex items-center gap-2">
                                        <span class="text-gray-800">{{ $class->class_name }}</span>
                                        <form method="POST" action="{{ $visibility && $visibility->is_visible ? route('teacher.lessons.lock', $lesson->lesson_id) : route('teacher.lessons.unlock', $lesson->lesson_id) }}" class="inline">
                                            @csrf
                                            <input type="hidden" name="class_id" value="{{ $class->class_id }}">
                                            <button type="submit" class="px-2 py-1 rounded text-xs font-bold focus:outline-none transition-colors duration-200
                                                {{ $visibility && $visibility->is_visible
                                                    ? 'bg-[#EC769A] text-white hover:bg-[#FC8EAC]'
                                                    : 'bg-[#6EC6C5] text-white hover:bg-[#197D8C]' }}">
                                                {{ $visibility && $visibility->is_visible ? 'Hide' : 'Show' }}
                                            </button>
                                        </form>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                        <div class="flex gap-3 mt-auto">
                            <a href="{{ route('teacher.lessons.view', $lesson->lesson_id) }}" target="_blank" title="View lesson content" class="flex items-center gap-1 px-4 py-2 rounded-lg bg-[#197D8C] text-white font-bold hover:bg-[#6EC6C5] transition">
                                <span class="text-lg">📖</span> View
                            </a>
                        </div>
                    </div>
                @empty
                    <div class="text-gray-400 italic">No lessons in this level.</div>
                @endforelse
            </div>
        </div>
    @endforeach
</div>
@endsection
