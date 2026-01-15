@extends('layouts.app')
@section('content')
<div class="max-w-5xl mx-auto py-10">
    <div class="bg-gradient-to-br from-pink-50 via-white to-pink-100 shadow-2xl rounded-3xl p-10 mb-10 border-2 border-pink-200">
        <h2 class="text-3xl font-extrabold mb-8 text-pink-600 flex items-center gap-3 drop-shadow">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-9 w-9 text-pink-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
            </svg>
            Edit Quiz: {{ $quiz->title }}
        </h2>

        @if($errors->any())
            <div class="bg-red-100 border border-red-300 text-red-700 px-4 py-3 rounded mb-6">
                <strong class="font-bold">Please fix the following errors:</strong>
                <ul class="list-disc pl-5 mt-2">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-100 border border-red-300 text-red-700 px-4 py-3 rounded mb-6">
                {{ session('error') }}
            </div>
        @endif

        <form method="POST" action="{{ route('quizzes.update', $quiz->quiz_id) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <!-- Quiz Basic Info -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                <div>
                    <label class="block font-bold text-pink-700 mb-2">Quiz Title</label>
                    <input type="text" name="title" value="{{ old('title', $quiz->title) }}" class="border-2 border-pink-200 rounded-xl px-4 py-3 w-full focus:ring-2 focus:ring-pink-200 bg-white" required>
                    @error('title')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="block font-bold text-pink-700 mb-2">Level</label>
                    <select name="level_id" class="border-2 border-pink-200 rounded-xl px-4 py-3 w-full focus:ring-2 focus:ring-pink-200 bg-white" required>
                        <option value="">Select Level</option>
                        @foreach($levels as $level)
                            <option value="{{ $level->level_id }}" {{ old('level_id', $quiz->level_id) == $level->level_id ? 'selected' : '' }}>{{ $level->level_name }}</option>
                        @endforeach
                    </select>
                    @error('level_id')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="block font-bold text-pink-700 mb-2">Timer (minutes)</label>
                    <input type="number" name="timer_minutes" value="{{ old('timer_minutes', $quiz->timer_minutes) }}" min="1" class="border-2 border-pink-200 rounded-xl px-4 py-3 w-full focus:ring-2 focus:ring-pink-200 bg-white" required>
                    @error('timer_minutes')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div class="md:col-span-2">
                    <label class="block font-bold text-pink-700 mb-2">Description (optional)</label>
                    <textarea name="description" class="border-2 border-pink-200 rounded-xl px-4 py-3 w-full focus:ring-2 focus:ring-pink-200 bg-white" rows="2">{{ old('description', $quiz->description) }}</textarea>
                </div>
            </div>

            <!-- Submit Button -->
            <div class="flex justify-end gap-4">
                <a href="{{ route('quizzes.show', $quiz->quiz_id) }}" class="px-6 py-3 rounded-xl font-bold border-2 border-pink-300 text-pink-700 hover:bg-pink-50 transition">
                    Cancel
                </a>
                <button type="submit" class="bg-gradient-to-r from-pink-500 to-pink-700 text-white px-10 py-3 rounded-2xl font-extrabold shadow-xl hover:from-pink-600 hover:to-pink-800 transition-all duration-150">
                    Update Quiz
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
