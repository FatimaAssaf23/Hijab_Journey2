@extends('layouts.app')

@section('content')
<div class="relative min-h-screen" style="background: linear-gradient(135deg, #FFF4FA 0%, #FDF2F8 30%, #F0F9FF 70%, #E0F7FA 100%);">
    <!-- Animated Background Elements -->
    <div class="fixed inset-0 overflow-hidden pointer-events-none z-0">
        <div class="absolute top-20 left-10 w-96 h-96 bg-pink-300/20 rounded-full blur-3xl animate-pulse" style="animation-delay: 0s;"></div>
        <div class="absolute top-60 right-20 w-[500px] h-[500px] bg-cyan-300/20 rounded-full blur-3xl animate-pulse" style="animation-delay: 2s;"></div>
        <div class="absolute bottom-20 left-1/4 w-80 h-80 bg-rose-300/20 rounded-full blur-3xl animate-pulse" style="animation-delay: 4s;"></div>
        <div class="absolute top-1/3 right-1/3 w-64 h-64 bg-teal-300/15 rounded-full blur-2xl animate-pulse" style="animation-delay: 1s;"></div>
    </div>
    
    <div class="relative z-10 max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 pt-8 pb-16">
        <!-- Header Section -->
        <div class="mb-6">
            <div class="relative bg-gradient-to-br from-pink-200/90 via-rose-100/80 to-cyan-200/90 rounded-2xl shadow-xl overflow-hidden border-2 border-pink-300/50 backdrop-blur-sm">
                <!-- Animated Pattern Overlay -->
                <div class="absolute inset-0 opacity-[0.08]">
                    <div class="absolute inset-0" style="background-image: 
                        repeating-linear-gradient(45deg, transparent, transparent 15px, rgba(236,72,153,0.1) 15px, rgba(236,72,153,0.1) 30px),
                        repeating-linear-gradient(-45deg, transparent, transparent 15px, rgba(6,182,212,0.1) 15px, rgba(6,182,212,0.1) 30px);"></div>
                </div>
                
                <!-- Decorative Corner Elements -->
                <div class="absolute top-0 right-0 w-24 h-24 bg-gradient-to-br from-pink-400/30 to-transparent rounded-bl-full"></div>
                <div class="absolute bottom-0 left-0 w-24 h-24 bg-gradient-to-tr from-cyan-400/30 to-transparent rounded-tr-full"></div>
                
                <div class="relative p-6">
                    <!-- Go Back Button -->
                    <div class="mb-4">
                        <a href="{{ route('meetings.index') }}" 
                           class="inline-flex items-center gap-2 text-pink-600 hover:text-pink-700 font-semibold transition-colors duration-200 group bg-white/80 backdrop-blur-sm px-3 py-1.5 rounded-lg border-2 border-pink-300/50 shadow-md hover:shadow-lg">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 group-hover:-translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                            </svg>
                            <span>Back to Meetings</span>
                        </a>
                    </div>
                    
                    <!-- Title Section -->
                    <div class="text-center lg:text-left">
                        <div class="inline-flex items-center gap-2 bg-white/80 backdrop-blur-md px-4 py-2 rounded-full mb-3 border-2 border-pink-300/50 shadow-lg">
                            <div class="relative">
                                <div class="w-2 h-2 bg-pink-500 rounded-full animate-ping absolute"></div>
                                <div class="w-2 h-2 bg-pink-500 rounded-full relative"></div>
                            </div>
                            <span class="text-pink-700 font-bold text-xs tracking-wider uppercase">Edit Meeting</span>
                        </div>
                        
                        <h1 class="text-3xl lg:text-4xl font-black mb-2 leading-tight">
                            <span class="bg-clip-text text-transparent bg-gradient-to-r from-pink-600 via-rose-500 to-cyan-600">
                                Update Meeting Details
                            </span>
                        </h1>
                        <p class="text-gray-700 font-semibold">Modify your meeting information and student list</p>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Error Message -->
        @if(session('error'))
            <div class="mb-6 bg-red-100 border-l-4 border-red-500 text-red-700 px-4 py-3 rounded-lg shadow-md backdrop-blur-sm">
                <div class="flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                    {{ session('error') }}
                </div>
            </div>
        @endif
        
        <!-- Form Card -->
        <div class="bg-white/90 backdrop-blur-md rounded-2xl shadow-2xl border-2 border-pink-200/50 overflow-hidden">
            <form action="{{ route('teacher.meetings.update', $meeting->meeting_id) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="p-6 lg:p-8 space-y-6">
                    <!-- Meeting Title -->
                    <div>
                        <label for="title" class="block text-pink-700 font-bold mb-2 text-sm uppercase tracking-wide">
                            <span class="flex items-center gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z" />
                                </svg>
                                Meeting Title
                            </span>
                        </label>
                        <input type="text" name="title" id="title" value="{{ old('title', $meeting->title) }}" 
                               class="w-full px-4 py-3 border-2 border-pink-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-pink-500 focus:border-pink-400 transition-all duration-200 bg-white/80 backdrop-blur-sm" 
                               required>
                        @error('title')
                            <p class="text-red-500 text-sm mt-2 flex items-center gap-1">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>
                    
                    <!-- Google Meet Link -->
                    <div>
                        <label for="google_meet_link" class="block text-pink-700 font-bold mb-2 text-sm uppercase tracking-wide">
                            <span class="flex items-center gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1" />
                                </svg>
                                Google Meet Link
                            </span>
                        </label>
                        <input type="url" name="google_meet_link" id="google_meet_link" value="{{ old('google_meet_link', $meeting->google_meet_link) }}" 
                               placeholder="https://meet.google.com/xxx-xxxx-xxx"
                               class="w-full px-4 py-3 border-2 border-pink-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-pink-500 focus:border-pink-400 transition-all duration-200 bg-white/80 backdrop-blur-sm" 
                               required>
                        @error('google_meet_link')
                            <p class="text-red-500 text-sm mt-2 flex items-center gap-1">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>
                    
                    <!-- Date & Time and Duration Row -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Scheduled Date & Time -->
                        <div>
                            <label for="scheduled_at" class="block text-pink-700 font-bold mb-2 text-sm uppercase tracking-wide">
                                <span class="flex items-center gap-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                    Scheduled Date & Time
                                </span>
                            </label>
                            <input type="datetime-local" name="scheduled_at" id="scheduled_at" 
                                   value="{{ old('scheduled_at', $meeting->scheduled_at ? $meeting->scheduled_at->format('Y-m-d\TH:i') : '') }}" 
                                   class="w-full px-4 py-3 border-2 border-pink-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-pink-500 focus:border-pink-400 transition-all duration-200 bg-white/80 backdrop-blur-sm" 
                                   required>
                            @error('scheduled_at')
                                <p class="text-red-500 text-sm mt-2 flex items-center gap-1">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>
                        
                        <!-- Duration -->
                        <div>
                            <label for="duration_minutes" class="block text-pink-700 font-bold mb-2 text-sm uppercase tracking-wide">
                                <span class="flex items-center gap-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    Duration (minutes)
                                </span>
                            </label>
                            <input type="number" name="duration_minutes" id="duration_minutes" value="{{ old('duration_minutes', $meeting->duration_minutes) }}" 
                                   min="10" max="480" step="10"
                                   class="w-full px-4 py-3 border-2 border-pink-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-pink-500 focus:border-pink-400 transition-all duration-200 bg-white/80 backdrop-blur-sm" 
                                   required>
                            @error('duration_minutes')
                                <p class="text-red-500 text-sm mt-2 flex items-center gap-1">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>
                    </div>
                    
                    <!-- Select Students -->
                    <div>
                        <label class="block text-pink-700 font-bold mb-2 text-sm uppercase tracking-wide">
                            <span class="flex items-center gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                </svg>
                                Select Students
                            </span>
                        </label>
                        <div class="border-2 border-pink-200 rounded-xl p-4 max-h-64 overflow-y-auto bg-white/50 backdrop-blur-sm">
                            <div class="space-y-2">
                                @foreach($students as $student)
                                <label class="flex items-center p-2 rounded-lg hover:bg-pink-50 transition-colors duration-200 cursor-pointer group">
                                    <input type="checkbox" name="student_ids[]" value="{{ $student->user_id }}" 
                                           class="mr-3 w-4 h-4 text-pink-600 border-pink-300 rounded focus:ring-pink-500 focus:ring-2" 
                                           {{ in_array($student->user_id, old('student_ids', $selectedStudentIds)) ? 'checked' : '' }}>
                                    <span class="text-gray-700 group-hover:text-pink-700 font-medium transition-colors">
                                        {{ $student->first_name }} {{ $student->last_name }} 
                                        <span class="text-gray-500 text-sm">({{ $student->email }})</span>
                                    </span>
                                </label>
                                @endforeach
                            </div>
                        </div>
                        @error('student_ids')
                            <p class="text-red-500 text-sm mt-2 flex items-center gap-1">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>
                    
                    <!-- Action Buttons -->
                    <div class="flex flex-col sm:flex-row gap-4 pt-4 border-t-2 border-pink-200">
                        <button type="submit" 
                                class="flex-1 bg-gradient-to-r from-pink-500 to-rose-500 hover:from-pink-600 hover:to-rose-600 text-white font-bold py-3 px-6 rounded-xl shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-200 flex items-center justify-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            Update Meeting
                        </button>
                        <a href="{{ route('meetings.index') }}" 
                           class="flex-1 bg-gradient-to-r from-gray-400 to-gray-500 hover:from-gray-500 hover:to-gray-600 text-white font-bold py-3 px-6 rounded-xl shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-200 flex items-center justify-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                            Cancel
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
