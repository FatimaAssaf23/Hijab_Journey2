
@extends('layouts.app')

@section('content')
    <div class="max-w-md mx-auto sm:px-2 lg:px-4">
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-xl p-3 mt-6">
            <h2 class="font-semibold text-lg text-gray-800 dark:text-gray-200 mb-3">Student Dashboard</h2>
            @php
                $student = Auth::user()->student;
                $class = $student?->studentClass;
            @endphp
            @if($class)
                <div class="space-y-2">
                    <div class="bg-gradient-to-r from-pink-50 to-purple-50 rounded-lg p-2 shadow flex flex-col gap-2 items-start">
                        <div class="mb-0.5 text-xs text-gray-500">Class Name</div>
                        <div class="text-base font-bold text-gray-800 mb-1">{{ $class->class_name }}</div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-1 mb-1 w-full">
                            <div class="bg-blue-50 rounded p-1">
                                <div class="text-[10px] text-gray-500 mb-0.5">👨‍🏫 Teacher</div>
                                <div class="font-semibold text-gray-800 text-xs">{{ $class->teacher ? $class->teacher->first_name . ' ' . $class->teacher->last_name : 'Unassigned' }}</div>
                            </div>
                            <div class="bg-purple-50 rounded p-1">
                                <div class="text-[10px] text-gray-500 mb-0.5">👥 Students</div>
                                <div class="text-base font-bold text-purple-600">{{ $class->students->count() }}</div>
                            </div>
                            <div class="bg-pink-50 rounded p-1">
                                <div class="text-[10px] text-gray-500 mb-0.5">📊 Capacity</div>
                                <div class="text-base font-bold text-pink-600">{{ $class->capacity }}</div>
                            </div>
                            <div class="bg-gray-50 rounded p-1">
                                <div class="text-[10px] text-gray-500 mb-0.5">Status</div>
                                <span class="inline-block px-2 py-0.5 rounded-full font-semibold text-[10px]
                                    @if($class->status === 'active') bg-green-100 text-green-800
                                    @elseif($class->status === 'full') bg-yellow-100 text-yellow-800
                                    @elseif($class->status === 'closed') bg-red-100 text-red-800
                                    @else bg-gray-200 text-gray-700 @endif">
                                    {{ ucfirst($class->status) }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            @else
                <div class="text-center text-gray-500 py-4 text-sm">
                    You are not enrolled in any class yet.
                </div>
            @endif
        </div>
    </div>
@endsection
