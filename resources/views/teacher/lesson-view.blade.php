@extends('layouts.app')

@section('content')
<div class="fixed inset-0 bg-white flex flex-col" style="z-index: 9999;">
    <!-- Fixed Header -->
    <div class="bg-gradient-to-r from-[#FC8EAC] via-[#EC769A] to-[#6EC6C5] shadow-lg px-4 sm:px-6 lg:px-8 py-4 flex items-center justify-between">
        <div class="flex items-center gap-4">
            <a href="{{ route('teacher.lessons.manage') }}" 
               class="flex items-center gap-2 px-4 py-2 rounded-lg font-semibold text-white hover:bg-white/20 transition-all duration-200">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                <span class="hidden sm:inline">Back</span>
            </a>
            <div class="flex items-center gap-3">
                <span class="text-3xl">{{ $lesson->icon ?? '📖' }}</span>
                <div>
                    <h1 class="text-xl sm:text-2xl font-bold text-white">{{ $lesson->title }}</h1>
                    <div class="text-sm text-white/80">
                        Duration: {{ $lesson->duration_minutes ?? '-' }} min
                        @if($lesson->skills)
                            <span class="mx-2">•</span>
                            Skills: {{ is_array($lesson->skills) ? implode(', ', $lesson->skills) : $lesson->skills }}
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Full Screen Content Area -->
    <div class="flex-1 overflow-hidden">
        @if($lesson->content_url)
            @php
                $fileExtension = strtolower(pathinfo($lesson->content_url, PATHINFO_EXTENSION));
                $isVideo = in_array($fileExtension, ['mp4', 'mov', 'avi', 'mkv', 'wmv', 'flv', 'webm']);
                $isPdf = $fileExtension === 'pdf';
                $contentUrl = ltrim($lesson->content_url, '/');
                $storageUrl = asset('storage/' . $contentUrl);
            @endphp
            
            @if($isVideo)
                <div class="w-full h-full bg-black flex items-center justify-center">
                    <video controls class="w-full h-full" style="max-width: 100%; max-height: 100%;">
                        <source src="{{ $storageUrl }}" type="video/{{ $fileExtension }}">
                        Your browser does not support the video tag.
                    </video>
                </div>
            @elseif($isPdf)
                <div class="w-full h-full">
                    <iframe src="{{ $storageUrl }}" class="w-full h-full border-0"></iframe>
                </div>
            @else
                <div class="w-full h-full flex items-center justify-center bg-gray-50">
                    <div class="text-center">
                        <div class="text-6xl mb-4">📄</div>
                        <h2 class="text-2xl font-bold text-gray-700 mb-4">{{ basename($lesson->content_url) }}</h2>
                        <a href="{{ $storageUrl }}" target="_blank" 
                           class="inline-block px-6 py-3 rounded-lg bg-[#6EC6C5] text-white font-bold hover:bg-[#197D8C] transition shadow-lg">
                            Download Lesson Content
                        </a>
                    </div>
                </div>
            @endif
        @else
            <div class="w-full h-full flex items-center justify-center bg-gray-50">
                <div class="text-center">
                    <div class="text-6xl mb-4">📚</div>
                    <h2 class="text-2xl font-bold text-gray-700 mb-2">No Content Available</h2>
                    <p class="text-gray-500">{{ $lesson->description ?? 'No content has been uploaded for this lesson yet.' }}</p>
                </div>
            </div>
        @endif
    </div>

    <!-- Optional Footer with Description -->
    @if($lesson->description && $lesson->content_url)
        <div class="bg-white border-t border-gray-200 px-4 sm:px-6 lg:px-8 py-3 max-h-24 overflow-y-auto">
            <p class="text-sm text-gray-600">{{ $lesson->description }}</p>
        </div>
    @endif
</div>
@endsection
