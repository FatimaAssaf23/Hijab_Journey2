@extends('layouts.app')

@section('content')
    <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg p-8 mt-8">
            <h2 class="font-semibold text-2xl text-gray-800 dark:text-gray-200 mb-6">My Classes</h2>
            @if($classes->count())
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
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
