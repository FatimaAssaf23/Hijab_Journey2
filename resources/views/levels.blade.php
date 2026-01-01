@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto py-8">
    <h1 class="text-3xl font-bold mb-6">Levels</h1>
    @foreach($levels as $level)
        <div class="mb-8 p-6 rounded-xl shadow bg-pink-50 border border-pink-200">
            <h2 class="text-xl font-bold text-pink-700 mb-2">{{ $level->level_name }}</h2>
            <p class="text-gray-700 mb-2">{{ $level->description }}</p>
            <div class="flex flex-wrap gap-4">
                @foreach($level->lessons as $lesson)
                    @if($lesson->is_visible)
                        <div class="bg-white border border-gray-200 rounded-lg p-4 shadow w-64">
                            <div class="flex items-center gap-2 mb-2">
                                <span class="text-2xl">{{ $lesson->icon ?? '📘' }}</span>
                                <span class="font-semibold">{{ $lesson->title }}</span>
                            </div>
                            <div class="text-gray-600 text-sm mb-1">{{ $lesson->description }}</div>
                            <div class="text-xs text-gray-500">Skills: {{ $lesson->skills }}</div>
                            <div class="text-xs text-gray-500">Duration: {{ $lesson->duration_minutes ?? '-' }} min</div>
                            <div class="flex items-center gap-2 mt-2">
                                <a href="/lessons/{{ $lesson->lesson_id }}/view" class="inline-block px-4 py-2 rounded-lg bg-[#6EC6C5] text-white font-bold hover:bg-[#197D8C] transition shadow">
                                    <span class="text-lg">📖</span> View
                                </a>
                            </div>
                        </div>
                    @endif
                @endforeach
            </div>
        </div>
    @endforeach
</div>
@endsection
