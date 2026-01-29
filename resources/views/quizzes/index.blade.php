@extends('layouts.app')
@section('content')
<div class="w-full min-h-screen py-10 px-4 sm:px-6 lg:px-8">
    <!-- Go Back Button -->
    <div class="mb-6">
        <button onclick="goBackOrRedirect('{{ route('teacher.dashboard') }}')" 
                class="flex items-center gap-2 px-6 py-3 rounded-xl font-semibold text-white shadow-lg hover:shadow-xl transition-all duration-300 transform hover:scale-105" 
                style="background: linear-gradient(135deg, #FC8EAC, #6EC6C5);">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Go Back
        </button>
    </div>
    
    <div class="bg-gradient-to-br from-pink-50 via-white to-pink-100 shadow-2xl rounded-3xl p-10 mb-10 border-2 border-pink-200">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-3xl font-extrabold text-pink-600 flex items-center gap-3 drop-shadow">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-9 w-9 text-pink-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                </svg>
                My Quizzes
            </h2>
            <a href="{{ route('quizzes.create') }}" class="bg-gradient-to-r from-pink-500 to-pink-700 text-white px-6 py-3 rounded-2xl font-extrabold shadow-xl hover:from-pink-600 hover:to-pink-800 transition-all duration-150">
                + Create New Quiz
            </a>
        </div>
        @if(session('success'))
            <div class="bg-green-100 text-green-800 px-4 py-3 rounded-xl mb-4 font-bold text-center shadow">{{ session('success') }}</div>
        @endif
        
        <!-- Class Filter -->
        <div class="mb-6 bg-white/50 rounded-2xl p-6 border-2 border-pink-100 shadow-lg">
            <form method="GET" action="{{ route('quizzes.index') }}" class="flex items-end gap-4 flex-wrap">
                <div class="flex-1 min-w-[280px] max-w-md">
                    <label for="class_id" class="block font-extrabold text-pink-700 mb-3 flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-pink-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                        </svg>
                        Filter by Class
                    </label>
                    <div class="relative">
                        <select name="class_id" id="class_id" class="border-2 border-pink-300 rounded-xl px-4 py-3 pr-10 w-full focus:ring-2 focus:ring-pink-400 focus:border-pink-400 bg-white text-pink-700 font-semibold shadow-md hover:shadow-lg transition-all cursor-pointer appearance-none" onchange="this.form.submit()">
                            <option value="">All Classes</option>
                            @foreach($classes ?? [] as $class)
                                <option value="{{ $class->class_id }}" {{ request('class_id') == $class->class_id ? 'selected' : '' }}>{{ $class->class_name }}</option>
                            @endforeach
                        </select>
                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3">
                            <svg class="h-5 w-5 text-pink-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </div>
                    </div>
                </div>
                @if(request('class_id'))
                    <div class="flex items-center gap-2 px-4 py-3 bg-gradient-to-r from-pink-100 to-pink-200 rounded-xl border-2 border-pink-300 shadow-md">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-pink-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span class="text-pink-700 font-bold text-sm">{{ $classes->where('class_id', request('class_id'))->first()?->class_name ?? 'Selected' }}</span>
                    </div>
                @endif
            </form>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
        @forelse($quizzes as $quiz)
            <div class="bg-white rounded-3xl shadow-xl border border-pink-100 p-6 flex flex-col gap-4 hover:shadow-pink-200 transition-all duration-150">
                <div class="flex items-center gap-4 mb-2">
                    <div class="w-16 h-16 rounded-full flex items-center justify-center text-2xl font-bold text-white shadow-lg" style="background-color: {{ $quiz->background_color ?? '#EC769A' }}">
                        Q
                    </div>
                    <div class="flex-1">
                        <h3 class="font-black text-xl text-pink-700 tracking-tight">{{ $quiz->title }}</h3>
                        <p class="text-sm text-gray-600">{{ $quiz->level->level_name ?? 'N/A' }}</p>
                        @if($quiz->studentClass)
                            <p class="text-xs text-pink-500 font-semibold mt-1">{{ $quiz->studentClass->class_name }}</p>
                        @endif
                    </div>
                </div>
                <div class="flex flex-wrap gap-2 text-xs">
                    <span class="px-3 py-1 rounded-full bg-pink-50 text-pink-600 font-bold border border-pink-200">
                        {{ $quiz->questions->count() }} Questions
                    </span>
                    <span class="px-3 py-1 rounded-full bg-pink-50 text-pink-600 font-bold border border-pink-200">
                        {{ $quiz->timer_minutes }} min
                    </span>
                </div>
                <div class="mt-auto pt-4">
                    <a href="{{ route('quizzes.show', $quiz->quiz_id) }}" class="block text-center bg-gradient-to-r from-pink-400 to-pink-600 text-white px-4 py-2 rounded-xl font-bold hover:from-pink-500 hover:to-pink-700 transition">
                        View Quiz
                    </a>
                </div>
            </div>
        @empty
            <div class="col-span-full text-center py-12">
                <p class="text-gray-500 text-lg mb-4">No quizzes created yet.</p>
                <a href="{{ route('quizzes.create') }}" class="inline-block bg-gradient-to-r from-pink-500 to-pink-700 text-white px-6 py-3 rounded-2xl font-extrabold shadow-xl hover:from-pink-600 hover:to-pink-800 transition">
                    Create Your First Quiz
                </a>
            </div>
        @endforelse
    </div>
</div>
@endsection
