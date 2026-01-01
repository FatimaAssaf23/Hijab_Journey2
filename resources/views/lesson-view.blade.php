@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto py-8">
    <div class="bg-white rounded-xl shadow p-8">
        <div class="flex items-center gap-3 mb-4">
            <span class="text-4xl">{{ $lesson->icon ?? '📘' }}</span>
            <h1 class="text-2xl font-bold">{{ $lesson->title }}</h1>
        </div>
        <div class="mb-4 text-gray-700">{{ $lesson->description }}</div>
        <div class="mb-2 text-sm text-gray-500">Skills: {{ $lesson->skills }}</div>
        <div class="mb-4 text-sm text-gray-500">Duration: {{ $lesson->duration_minutes ?? '-' }} min</div>
        @if($lesson->content_url)
            <div class="mt-6">
                @if(Str::endsWith($lesson->content_url, ['.pdf']))
                    <iframe src="{{ asset($lesson->content_url) }}" width="100%" height="600px"></iframe>
                @elseif(Str::endsWith($lesson->content_url, ['.mp4', '.mov', '.avi']))
                    <video controls width="100%">
                        <source src="{{ asset($lesson->content_url) }}">
                        Your browser does not support the video tag.
                    </video>
                @else
                    <a href="{{ asset($lesson->content_url) }}" class="text-pink-600 underline" target="_blank">Download Content</a>
                @endif
            </div>
        @else
            <div class="text-gray-400 italic">No content uploaded for this lesson.</div>
        @endif
    </div>
</div>
@endsection
