@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-pink-50 via-purple-50 to-indigo-50">
    <div class="w-full max-w-full mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Header -->
        <div class="bg-white rounded-2xl shadow-lg p-6 mb-6">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-800 mb-2">💬 Group Chat</h1>
                    <p class="text-gray-600">Select a class to view its group chat</p>
                </div>
                <a href="{{ route('teacher.dashboard') }}" class="px-4 py-2 bg-gray-200 hover:bg-gray-300 rounded-lg transition">
                    ← Back
                </a>
            </div>
        </div>

        <!-- Classes Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($teacherClasses as $class)
                <a href="{{ route('group-chat.index', $class->class_id) }}" 
                   class="bg-white rounded-2xl shadow-lg p-6 hover:shadow-xl transition-all transform hover:scale-105">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-xl font-bold text-gray-800">{{ $class->class_name }}</h3>
                        <span class="text-2xl">💬</span>
                    </div>
                    <div class="space-y-2 text-sm text-gray-600">
                        <p>👥 {{ $class->current_enrollment }} / {{ $class->capacity }} students</p>
                        <p>📊 Status: <span class="font-semibold capitalize">{{ $class->status }}</span></p>
                        @if($class->description)
                            <p class="text-xs text-gray-500 mt-2">{{ Str::limit($class->description, 50) }}</p>
                        @endif
                    </div>
                    <div class="mt-4 pt-4 border-t border-gray-200">
                        <span class="text-blue-600 font-semibold">View Chat →</span>
                    </div>
                </a>
            @endforeach
        </div>
    </div>
</div>
@endsection
