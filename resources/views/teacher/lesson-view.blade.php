@extends('layouts.app')

@section('content')
<div class="container py-8">
    <div class="max-w-2xl mx-auto bg-white rounded-2xl shadow-lg p-8 border border-[#EAD8C0]">
        <h1 class="text-3xl font-bold mb-4 text-[#197D8C] flex items-center gap-2">
            <span>{{ $lesson->icon ?? '📖' }}</span> {{ $lesson->title }}
        </h1>
        <div class="mb-2 text-[#6EC6C5] font-semibold">Duration: {{ $lesson->duration_minutes ?? '-' }} min</div>
        <div class="mb-4 text-gray-700">{{ $lesson->description }}</div>
        <div class="mb-6">
            <a href="{{ $lesson->content_url }}" target="_blank" class="inline-block px-5 py-2 rounded-lg bg-[#6EC6C5] text-white font-bold hover:bg-[#197D8C] transition">Open Lesson Content</a>
        </div>
        @if($lesson->skills)
            <div class="mb-2">
                <span class="font-semibold text-[#197D8C]">Skills:</span>
                <span class="ml-2 px-3 py-1 rounded-lg bg-[#e0e0e0] text-gray-700 text-xs font-semibold">{{ is_array($lesson->skills) ? implode(', ', $lesson->skills) : $lesson->skills }}</span>
            </div>
        @endif
    </div>
</div>
@endsection
