@extends('layouts.app')

@section('content')
    <div class="w-full min-h-screen px-4 sm:px-6 lg:px-8 py-8" style="background-image: url('/storage/Teacher_Dashboard/background3.jpg'); background-size: cover; background-position: center; background-repeat: no-repeat;">
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
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg p-8">
            <h2 class="font-semibold text-2xl text-gray-800 dark:text-gray-200 mb-6">My Classes</h2>
            @if($classes->count())
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                    @foreach($classes as $class)
                        <div class="bg-pink-50 border border-pink-200 rounded-lg p-6 shadow" x-data="{ show: false }">
                            <h3 class="text-xl font-bold text-pink-700 mb-2">{{ $class->class_name }}</h3>
                            <p class="text-gray-700 mb-1">Capacity: {{ $class->capacity }}</p>
                            <p class="text-gray-700 mb-1">Enrolled: {{ $class->current_enrollment }}</p>
                            <p class="text-gray-700 mb-1">Status: {{ ucfirst($class->status) }}</p>
                            @if($class->description)
                                <p class="text-gray-600 mt-2">{{ $class->description }}</p>
                            @endif
                            <hr class="my-4">
                            <button @click="show = !show" class="bg-pink-600 text-white px-4 py-2 rounded shadow hover:bg-pink-700 transition mb-2">
                                <span x-show="!show">View List</span>
                                <span x-show="show">Hide List</span>
                            </button>
                            <div x-show="show" x-transition>
                                <h4 class="font-semibold text-pink-600 mb-2">Students Enrolled:</h4>
                                @if($class->students->count())
                                    <ul class="list-disc ml-6">
                                        @foreach($class->students as $student)
                                            <li class="mb-1">
                                                {{ $student->user->first_name ?? 'N/A' }} {{ $student->user->last_name ?? '' }}
                                                <span class="text-xs text-gray-500">({{ $student->user->email ?? 'No email' }})</span>
                                            </li>
                                        @endforeach
                                    </ul>
                                @else
                                    <p class="text-gray-500">No students enrolled.</p>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center text-gray-500 py-8">
                    You are not assigned to any class.
                </div>
            @endif
        </div>
    </div>
@endsection
