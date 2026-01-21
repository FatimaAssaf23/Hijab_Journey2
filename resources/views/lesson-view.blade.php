@extends('layouts.app')

@section('content')
<div class="py-12 px-2" style="background-color: #FFF4FA; min-height: 100vh;">
    <div class="max-w-2xl mx-auto">
        <div class="relative bg-white/70 backdrop-blur-xl rounded-3xl shadow-2xl p-10 border border-pink-100">
            <!-- Decorative SVG background -->
            <svg class="absolute right-0 top-0 w-32 h-32 opacity-10 pointer-events-none z-0" viewBox="0 0 200 200" fill="none" xmlns="http://www.w3.org/2000/svg">
                <circle cx="100" cy="100" r="100" fill="#f472b6"/>
            </svg>
            <div class="relative z-10">
                <div class="flex items-center gap-4 mb-4">
                    <span class="text-5xl drop-shadow">{{ $lesson->icon ?? '📘' }}</span>
                    <h1 class="text-3xl font-extrabold text-pink-600 tracking-tight">{{ $lesson->title }}</h1>
                </div>
                <!-- Mark as Completed Button (below title/icon) -->
                <form method="POST" action="{{ route('student.lesson.complete', $lesson->lesson_id) }}" class="mb-6 flex justify-end">
                    @csrf
                    <button type="submit" class="bg-gradient-to-r from-pink-400 via-pink-300 to-pink-200 hover:from-pink-500 hover:to-pink-300 text-white font-bold py-2 px-7 rounded-full shadow-lg transition-all text-base">Mark as Completed</button>
                </form>
                <div class="mb-5 text-lg text-gray-700 leading-relaxed">{{ $lesson->description }}</div>
                <div class="mb-2 text-sm text-gray-500 flex gap-4">
                    <span><span class="font-semibold text-pink-500">Skills:</span> {{ $lesson->skills }}</span>
                    <span><span class="font-semibold text-pink-500">Duration:</span> {{ $lesson->duration_minutes ?? '-' }} min</span>
                </div>
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
                        
                        @if($isPdf)
                            <iframe src="{{ $storageUrl }}" width="100%" height="600px" class="rounded-xl border border-pink-100 shadow"></iframe>
                            <div class="mt-2">
                                <a href="{{ $storageUrl }}" target="_blank" class="inline-block bg-pink-100 text-pink-700 px-4 py-2 rounded-lg shadow hover:bg-pink-200 transition text-base font-semibold mt-2">Open PDF in New Tab</a>
                            </div>
                        @elseif($isVideo)
                            <video controls width="100%" class="rounded-xl border border-pink-100 shadow">
                                <source src="{{ $storageUrl }}" type="video/{{ $fileExtension }}">
                                Your browser does not support the video tag.
                            </video>
                            @if($lesson->video_duration_seconds)
                                <p class="text-sm text-gray-500 mt-2">Duration: {{ gmdate('i:s', $lesson->video_duration_seconds) }}</p>
                            @endif
                        @else
                            <a href="{{ $storageUrl }}" class="inline-block bg-pink-100 text-pink-700 px-4 py-2 rounded-lg shadow hover:bg-pink-200 transition text-base font-semibold mt-2" target="_blank">Download Content</a>
                            <p class="text-sm text-gray-500 mt-2">File: {{ basename($lesson->content_url) }}</p>
                        @endif
                    </div>
                @else
                    <div class="text-gray-400 italic mt-8">No content uploaded for this lesson.</div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
