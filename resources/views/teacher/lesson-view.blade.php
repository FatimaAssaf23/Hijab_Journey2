@extends('layouts.app')

@section('content')
<div class="container py-8">
    <!-- Go Back Button -->
    <div class="max-w-2xl mx-auto mb-4">
        <button onclick="goBackOrRedirect('{{ route('teacher.lessons.manage') }}')" 
                class="flex items-center gap-2 px-6 py-3 rounded-xl font-semibold text-white shadow-lg hover:shadow-xl transition-all duration-300 transform hover:scale-105" 
                style="background: linear-gradient(135deg, #FC8EAC, #6EC6C5);">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Go Back
        </button>
    </div>
    <div class="max-w-2xl mx-auto bg-white rounded-2xl shadow-lg p-8 border border-[#EAD8C0]">
        <h1 class="text-3xl font-bold mb-4 text-[#197D8C] flex items-center gap-2">
            <span>{{ $lesson->icon ?? '📖' }}</span> {{ $lesson->title }}
        </h1>
        <div class="mb-2 text-[#6EC6C5] font-semibold">Duration: {{ $lesson->duration_minutes ?? '-' }} min</div>
        <div class="mb-4 text-gray-700">{{ $lesson->description }}</div>
        
        @if($lesson->content_url)
            <div class="mt-8">
                @php
                    $fileExtension = strtolower(pathinfo($lesson->content_url, PATHINFO_EXTENSION));
                    $isVideo = in_array($fileExtension, ['mp4', 'mov', 'avi', 'mkv', 'wmv', 'flv', 'webm']);
                    $isPdf = $fileExtension === 'pdf';
                    // Remove leading slash if present to avoid double slashes in URL
                    $contentUrl = ltrim($lesson->content_url, '/');
                    $storageUrl = asset('storage/' . $contentUrl);
                @endphp
                
                @if($isVideo)
                    <div class="mb-4">
                        <video controls width="100%" class="rounded-xl border border-[#EAD8C0] shadow">
                            <source src="{{ $storageUrl }}" type="video/{{ $fileExtension }}">
                            Your browser does not support the video tag.
                        </video>
                        @if($lesson->video_duration_seconds)
                            <p class="text-sm text-gray-500 mt-2">Duration: {{ gmdate('i:s', $lesson->video_duration_seconds) }}</p>
                        @endif
                    </div>
                @elseif($isPdf)
                    <div class="mb-4">
                        <iframe src="{{ $storageUrl }}" width="100%" height="600px" class="rounded-xl border border-[#EAD8C0] shadow"></iframe>
                        <div class="mt-2">
                            <a href="{{ $storageUrl }}" target="_blank" class="inline-block px-5 py-2 rounded-lg bg-[#6EC6C5] text-white font-bold hover:bg-[#197D8C] transition">Open PDF in New Tab</a>
                        </div>
                    </div>
                @else
                    <div class="mb-4">
                        <a href="{{ $storageUrl }}" target="_blank" class="inline-block px-5 py-2 rounded-lg bg-[#6EC6C5] text-white font-bold hover:bg-[#197D8C] transition">Download Lesson Content</a>
                        <p class="text-sm text-gray-500 mt-2">File: {{ basename($lesson->content_url) }}</p>
                    </div>
                @endif
            </div>
        @else
            <div class="mt-8 text-gray-400 italic">No content uploaded for this lesson.</div>
        @endif
        
        @if($lesson->skills)
            <div class="mb-2 mt-6">
                <span class="font-semibold text-[#197D8C]">Skills:</span>
                <span class="ml-2 px-3 py-1 rounded-lg bg-[#e0e0e0] text-gray-700 text-xs font-semibold">{{ is_array($lesson->skills) ? implode(', ', $lesson->skills) : $lesson->skills }}</span>
            </div>
        @endif
    </div>
</div>
@endsection
