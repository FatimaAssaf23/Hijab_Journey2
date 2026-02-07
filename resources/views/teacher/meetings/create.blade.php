@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold mb-6">Create New Meeting</h1>
    
    <div class="bg-white rounded-lg shadow-md p-6 max-w-2xl">
        <form action="{{ route('teacher.meetings.store') }}" method="POST">
            @csrf
            
            <div class="mb-4">
                <label for="title" class="block text-gray-700 font-bold mb-2">Meeting Title</label>
                <input type="text" name="title" id="title" value="{{ old('title') }}" 
                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" 
                       required>
                @error('title')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
            
            <div class="mb-4">
                <label for="google_meet_link" class="block text-gray-700 font-bold mb-2">Google Meet Link</label>
                <input type="url" name="google_meet_link" id="google_meet_link" value="{{ old('google_meet_link') }}" 
                       placeholder="https://meet.google.com/xxx-xxxx-xxx"
                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" 
                       required>
                @error('google_meet_link')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
            
            <div class="mb-4">
                <label for="scheduled_at" class="block text-gray-700 font-bold mb-2">Scheduled Date & Time</label>
                <input type="datetime-local" name="scheduled_at" id="scheduled_at" value="{{ old('scheduled_at') }}" 
                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" 
                       required>
                @error('scheduled_at')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
            
            <div class="mb-4">
                <label for="duration_minutes" class="block text-gray-700 font-bold mb-2">Duration (minutes)</label>
                <input type="number" name="duration_minutes" id="duration_minutes" value="{{ old('duration_minutes', 60) }}" 
                       min="10" max="480" step="10"
                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" 
                       required>
                @error('duration_minutes')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
            
            <div class="mb-4">
                <label class="block text-gray-700 font-bold mb-2">Select Students</label>
                <div class="border border-gray-300 rounded-md p-4 max-h-64 overflow-y-auto">
                    @foreach($students as $student)
                    <label class="flex items-center mb-2">
                        <input type="checkbox" name="student_ids[]" value="{{ $student->user_id }}" 
                               class="mr-2" {{ in_array($student->user_id, old('student_ids', [])) ? 'checked' : '' }}>
                        <span>{{ $student->first_name }} {{ $student->last_name }} ({{ $student->email }})</span>
                    </label>
                    @endforeach
                </div>
                @error('student_ids')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
            
            <div class="flex gap-4">
                <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded">
                    Create Meeting
                </button>
                <a href="{{ route('teacher.meetings.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
