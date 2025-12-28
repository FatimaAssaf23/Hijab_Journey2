@extends('layouts.app')

@section('content')
<div class="bg-gradient-to-r from-[#FC8EAC] via-[#EC769A] to-[#6EC6C5] shadow-xl mb-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 flex items-center">
        <span class="text-5xl mr-4 align-middle">📚</span>
        <div>
            <h1 class="text-4xl font-extrabold text-white mb-2">Lessons Management</h1>
            <p class="text-white/90 text-lg">Manage lessons by grade level • Click "Add Lesson" under each level to add</p>
        </div>
    </div>
</div>
<div class="container py-5 flex flex-col items-center">
    @foreach($levels as $level)
        <div class="mb-8 w-full max-w-4xl mx-auto" x-data="{ showLessons: true }">
            <div class="rounded-2xl px-6 py-4 mb-4 flex items-start justify-between" style="background: linear-gradient(90deg, #F8C5C8 0%, #FC8EAC 50%, #EC769A 100%); color: #222;">
                <div>
                    <h2 class="text-2xl font-extrabold mb-1">Level {{ $level->level_number ?? $level->level_id }}</h2>
                    <div class="text-base opacity-80 mb-1">{{ $level->level_name }}</div>
                    <div class="text-sm opacity-70">{{ $level->description ?? 'Curriculum' }} &bull; {{ $level->lessons->count() }} lessons</div>
                </div>
                <button @click="showLessons = !showLessons" type="button" class="ml-4 px-4 py-2 rounded-xl font-semibold text-white bg-[#EC769A] hover:bg-[#FC8EAC] transition shadow" x-text="showLessons ? 'Hide Lessons' : 'Show Lessons'"></button>
            </div>
            <div class="flex flex-wrap gap-6 justify-start" x-show="showLessons" x-transition>
                @forelse($level->lessons as $lesson)
                    <div class="rounded-2xl shadow-lg p-6 flex flex-col gap-3 min-w-[260px] max-w-xs w-full"
                        style="background: linear-gradient(135deg, #b2f7ef 0%, #f6d6d6 100%); border: 1.5px solid #EAD8C0;">
                        <div class="flex items-center gap-3 mb-2">
                            <span class="text-4xl">{{ $lesson->icon ?? '📘' }}</span>
                            <div class="flex-1">
                                <div class="font-bold text-lg text-[#197D8C]">{{ $lesson->title }}</div>
                                <div class="text-xs text-[#6EC6C5]">Duration: {{ $lesson->duration_minutes ?? '-' }} min</div>
                            </div>
                        </div>
                        <div class="text-gray-700 mb-2">{{ $lesson->description }}</div>
                        <div class="flex items-center gap-2 mb-2">
                            <span class="px-3 py-1 rounded-full text-xs font-semibold"
                                style="background: {{ $lesson->is_visible ? '#6EC6C5' : '#EAD8C0' }}; color: {{ $lesson->is_visible ? '#fff' : '#197D8C' }};">
                                {{ $lesson->is_visible ? 'Visible to Students' : 'Hidden from Students' }}
                            </span>
                            @if($lesson->skills)
                                <span class="ml-2 px-3 py-1 rounded-lg bg-[#e0e0e0] text-gray-700 text-xs font-semibold">{{ is_array($lesson->skills) ? count($lesson->skills) : $lesson->skills }} skills</span>
                            @endif
                        </div>
                        <div class="flex gap-3 mt-auto">
                            <a href="{{ route('teacher.lessons.view', $lesson->lesson_id) }}" target="_blank" title="View lesson content" class="flex items-center gap-1 px-4 py-2 rounded-lg bg-[#197D8C] text-white font-bold hover:bg-[#6EC6C5] transition">
                                <span class="text-lg">📖</span> View
                            </a>
                            @if($lesson->is_visible)
                                <form method="POST" action="{{ route('teacher.lessons.lock', $lesson->lesson_id) }}">
                                    @csrf
                                    <button type="submit" title="Hide from students" class="flex items-center gap-1 px-4 py-2 rounded-lg bg-[#E9B7B9] text-[#197D8C] font-bold hover:bg-[#F6D6D6] transition">
                                        <span class="text-lg">🙈</span> Hide
                                    </button>
                                </form>
                            @else
                                <form method="POST" action="{{ route('teacher.lessons.unlock', $lesson->lesson_id) }}">
                                    @csrf
                                    <button type="submit" title="Show to students" class="flex items-center gap-1 px-4 py-2 rounded-lg bg-[#6EC6C5] text-white font-bold hover:bg-[#197D8C] hover:text-white transition">
                                        <span class="text-lg">👁️</span> Show
                                    </button>
                                </form>
                            @endif
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
