@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto py-10">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-extrabold text-pink-600 flex items-center gap-3 drop-shadow">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-9 w-9 text-pink-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
            </svg>
            Meetings
        </h1>
        @can('isTeacher')
            <a href="{{ route('meetings.create') }}" 
               class="bg-gradient-to-r from-pink-500 to-pink-600 hover:from-pink-600 hover:to-pink-700 text-white font-bold py-2 px-4 rounded-xl shadow-lg transition-all duration-150">
                Create New Meeting
            </a>
        @endcan
    </div>

    @if (session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    @if ($meetings->isEmpty())
        <div class="bg-gradient-to-br from-pink-50 via-white to-pink-100 shadow-lg rounded-3xl p-12 text-center border-2 border-pink-200">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 text-pink-300 mx-auto mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
            </svg>
            <p class="text-gray-600 text-lg">No meetings scheduled yet.</p>
        </div>
    @else
        <div class="grid gap-6">
            @foreach($meetings as $meeting)
                <div class="bg-gradient-to-br from-pink-50 via-white to-pink-100 shadow-lg rounded-2xl p-6 border-2 border-pink-200 hover:shadow-xl transition-shadow">
                    <div class="flex justify-between items-start">
                        <div class="flex-1">
                            <h2 class="text-2xl font-bold text-pink-700 mb-3">
                                <a href="{{ route('meetings.show', $meeting) }}" class="hover:text-pink-800 hover:underline">
                                    {{ $meeting->title }}
                                </a>
                            </h2>
                            <div class="space-y-2 text-gray-700">
                                <p>
                                    <strong class="text-pink-600">Class:</strong> 
                                    {{ $meeting->studentClass->class_name ?? 'N/A' }}
                                </p>
                                <p>
                                    <strong class="text-pink-600">Date:</strong> 
                                    {{ $meeting->start_time ? $meeting->start_time->format('F d, Y') : 'Not set' }}
                                </p>
                                <p>
                                    <strong class="text-pink-600">Time:</strong> 
                                    @if($meeting->start_time && $meeting->end_time)
                                        {{ $meeting->start_time->format('h:i A') }} - 
                                        {{ $meeting->end_time->format('h:i A') }}
                                    @else
                                        Not set
                                    @endif
                                </p>
                                @if($meeting->description)
                                    <p class="mt-2">
                                        <strong class="text-pink-600">Description:</strong>
                                        {{ $meeting->description }}
                                    </p>
                                @endif
                            </div>
                        </div>
                        <div class="text-right ml-4 flex flex-col items-end gap-2">
                            <span class="inline-block bg-blue-100 text-blue-800 text-xs font-semibold px-3 py-1 rounded-full">
                                {{ ucfirst($meeting->status) }}
                            </span>
                            <a href="{{ route('meetings.show', $meeting) }}" 
                               class="bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white font-bold py-2 px-4 rounded-xl shadow-lg transition-all duration-150 text-sm">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                                View Details
                            </a>
                            @if($meeting->google_meet_link)
                                <a href="{{ $meeting->google_meet_link }}" 
                                   target="_blank"
                                   class="block bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 text-white font-bold py-3 px-6 rounded-xl shadow-lg transition-all duration-150">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
                                    </svg>
                                    Join Google Meet
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection
