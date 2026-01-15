@extends('layouts.app')
@section('content')
<div class="max-w-7xl mx-auto py-10">
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
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($quizzes as $quiz)
            <div class="bg-white rounded-3xl shadow-xl border border-pink-100 p-6 flex flex-col gap-4 hover:shadow-pink-200 transition-all duration-150">
                <div class="flex items-center gap-4 mb-2">
                    <div class="w-16 h-16 rounded-full flex items-center justify-center text-2xl font-bold text-white shadow-lg" style="background-color: {{ $quiz->background_color ?? '#EC769A' }}">
                        Q
                    </div>
                    <div class="flex-1">
                        <h3 class="font-black text-xl text-pink-700 tracking-tight">{{ $quiz->title }}</h3>
                        <p class="text-sm text-gray-600">{{ $quiz->level->level_name ?? 'N/A' }}</p>
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
