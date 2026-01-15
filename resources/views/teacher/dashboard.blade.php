@extends('layouts.app')

@section('content')
    <div class="w-full px-0">
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-xl w-full max-w-7xl mx-auto mt-4" style="min-height:4.5rem; padding: 2.5rem 2rem;">
            <div class="flex flex-col items-start justify-center h-full mb-6">
                <h2 class="font-semibold text-2xl text-gray-800 dark:text-gray-200 mb-2">Teacher Dashboard</h2>
                <p class="text-base text-gray-600">Welcome to your dashboard. Here you can manage your lessons, quizzes, grades, and more.</p>
            </div>
            
            <!-- Level Lessons Chart -->
            @include('teacher.partials.level-lessons-chart')
        </div>
    </div>
@endsection
