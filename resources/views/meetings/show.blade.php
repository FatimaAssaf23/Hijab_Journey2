@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto py-10">
    <div class="bg-gradient-to-br from-pink-50 via-white to-pink-100 shadow-2xl rounded-3xl p-10 border-2 border-pink-200">
        <h1 class="text-3xl font-extrabold text-pink-600 mb-6 drop-shadow">{{ $meeting->title }}</h1>

        <div class="space-y-4 mb-8">
            <div class="bg-white rounded-xl p-4 border border-pink-200">
                <strong class="text-pink-600">Class:</strong> 
                <span class="text-gray-700">{{ $meeting->studentClass->class_name ?? 'N/A' }}</span>
            </div>
            <div class="bg-white rounded-xl p-4 border border-pink-200">
                <strong class="text-pink-600">Teacher:</strong> 
                <span class="text-gray-700">
                    {{ $meeting->teacher->first_name }} {{ $meeting->teacher->last_name }}
                </span>
            </div>
            <div class="bg-white rounded-xl p-4 border border-pink-200">
                <strong class="text-pink-600">Date:</strong> 
                <span class="text-gray-700">
                    {{ $meeting->start_time ? $meeting->start_time->format('F d, Y') : 'Not set' }}
                </span>
            </div>
            <div class="bg-white rounded-xl p-4 border border-pink-200">
                <strong class="text-pink-600">Time:</strong> 
                <span class="text-gray-700">
                    @if($meeting->start_time && $meeting->end_time)
                        {{ $meeting->start_time->format('h:i A') }} - 
                        {{ $meeting->end_time->format('h:i A') }}
                    @else
                        Not set
                    @endif
                </span>
            </div>
            @if($meeting->description)
                <div class="bg-white rounded-xl p-4 border border-pink-200">
                    <strong class="text-pink-600">Description:</strong>
                    <p class="text-gray-700 mt-2">{{ $meeting->description }}</p>
                </div>
            @endif
            <div class="bg-white rounded-xl p-4 border border-pink-200">
                <strong class="text-pink-600">Status:</strong> 
                <span class="inline-block bg-blue-100 text-blue-800 text-xs font-semibold px-3 py-1 rounded-full">
                    {{ ucfirst($meeting->status) }}
                </span>
            </div>
        </div>

        @if($meeting->google_meet_link)
            <div class="text-center">
                <a href="{{ $meeting->google_meet_link }}" 
                   target="_blank"
                   class="inline-block bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 text-white font-bold py-4 px-8 rounded-xl shadow-lg text-lg transition-all duration-150">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 inline mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
                    </svg>
                    Join Google Meet
                </a>
            </div>
        @endif

        <div class="mt-8 text-center">
            <a href="{{ route('meetings.index') }}" 
               class="text-pink-600 hover:text-pink-700 font-semibold">
                ← Back to Meetings
            </a>
        </div>
    </div>
</div>
@endsection
