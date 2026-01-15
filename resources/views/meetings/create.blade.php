@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto py-10">
    <div class="bg-gradient-to-br from-pink-50 via-white to-pink-100 shadow-2xl rounded-3xl p-10 mb-10 border-2 border-pink-200">
        <h2 class="text-3xl font-extrabold text-pink-600 flex items-center gap-3 mb-6 drop-shadow">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-9 w-9 text-pink-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
            </svg>
            Create New Meeting
        </h2>

        @if (session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                {{ session('error') }}
            </div>
        @endif

        <form action="{{ route('meetings.store') }}" method="POST" class="space-y-6">
            @csrf

            <div>
                <label for="title" class="block font-bold text-pink-700 mb-2">
                    Meeting Title <span class="text-red-500">*</span>
                </label>
                <input type="text" 
                       name="title" 
                       id="title" 
                       value="{{ old('title') }}"
                       required
                       class="border-2 border-pink-200 rounded-xl px-4 py-3 w-full focus:ring-2 focus:ring-pink-200 bg-white">
                @error('title')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="class_id" class="block font-bold text-pink-700 mb-2">
                    Class <span class="text-red-500">*</span>
                </label>
                <select name="class_id" 
                        id="class_id" 
                        required
                        class="border-2 border-pink-200 rounded-xl px-4 py-3 w-full focus:ring-2 focus:ring-pink-200 bg-white">
                    <option value="">Select a class</option>
                    @foreach($classes as $class)
                        <option value="{{ $class->class_id }}" {{ old('class_id') == $class->class_id ? 'selected' : '' }}>
                            {{ $class->class_name }}
                        </option>
                    @endforeach
                </select>
                @error('class_id')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="date" class="block font-bold text-pink-700 mb-2">
                    Date <span class="text-red-500">*</span>
                </label>
                <input type="date" 
                       name="date" 
                       id="date" 
                       value="{{ old('date', date('Y-m-d')) }}"
                       required
                       min="{{ date('Y-m-d') }}"
                       class="border-2 border-pink-200 rounded-xl px-4 py-3 w-full focus:ring-2 focus:ring-pink-200 bg-white">
                @error('date')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="start_time" class="block font-bold text-pink-700 mb-2">
                        Start Time <span class="text-red-500">*</span>
                    </label>
                    <input type="time" 
                           name="start_time" 
                           id="start_time" 
                           value="{{ old('start_time') }}"
                           required
                           class="border-2 border-pink-200 rounded-xl px-4 py-3 w-full focus:ring-2 focus:ring-pink-200 bg-white">
                    @error('start_time')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="end_time" class="block font-bold text-pink-700 mb-2">
                        End Time <span class="text-red-500">*</span>
                    </label>
                    <input type="time" 
                           name="end_time" 
                           id="end_time" 
                           value="{{ old('end_time') }}"
                           required
                           class="border-2 border-pink-200 rounded-xl px-4 py-3 w-full focus:ring-2 focus:ring-pink-200 bg-white">
                    @error('end_time')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div>
                <label for="google_meet_link" class="block font-bold text-pink-700 mb-2">
                    Google Meet Link <span class="text-red-500">*</span>
                </label>
                <input type="url" 
                       name="google_meet_link" 
                       id="google_meet_link" 
                       value="{{ old('google_meet_link') }}"
                       placeholder="https://meet.google.com/xxx-xxxx-xxx"
                       required
                       class="border-2 border-pink-200 rounded-xl px-4 py-3 w-full focus:ring-2 focus:ring-pink-200 bg-white">
                <p class="text-sm text-gray-600 mt-1">
                    Create a meeting at <a href="https://meet.google.com" target="_blank" class="text-blue-500 hover:underline">meet.google.com</a> and paste the link here
                </p>
                @error('google_meet_link')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="description" class="block font-bold text-pink-700 mb-2">
                    Description
                </label>
                <textarea name="description" 
                          id="description" 
                          rows="4"
                          class="border-2 border-pink-200 rounded-xl px-4 py-3 w-full focus:ring-2 focus:ring-pink-200 bg-white">{{ old('description') }}</textarea>
                @error('description')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex items-center justify-between pt-4">
                <button type="submit" 
                        class="bg-gradient-to-r from-pink-500 to-pink-600 hover:from-pink-600 hover:to-pink-700 text-white font-bold py-3 px-6 rounded-xl shadow-lg transition-all duration-150">
                    Create Meeting
                </button>
                <a href="{{ route('meetings.index') }}" 
                   class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-3 px-6 rounded-xl">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
