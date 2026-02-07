@extends('layouts.admin')

@section('content')
<div class="min-h-screen" style="background: linear-gradient(135deg, #FFF4FA 0%, #FDF2F8 30%, #F0F9FF 70%, #E0F7FA 100%);">
    <!-- Header -->
    <div class="bg-gradient-to-r from-pink-200/90 via-rose-100/80 to-cyan-200/90 shadow-2xl border-b-4 border-pink-300/50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="flex flex-col md:flex-row justify-between items-center gap-6">
                <div class="flex items-center gap-6 text-center md:text-left">
                    <!-- Back Button -->
                    <a href="{{ route('admin.teachers.index') }}" 
                       class="flex items-center justify-center w-14 h-14 bg-white/30 backdrop-blur-xl rounded-2xl border-2 border-white/50 shadow-lg hover:bg-white/40 transition-all transform hover:scale-105">
                        <svg class="w-6 h-6 text-gray-800" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                        </svg>
                    </a>
                    
                    <!-- Teacher Icon -->
                    <div class="hidden md:flex items-center justify-center w-20 h-20 rounded-3xl bg-gradient-to-br from-pink-500 via-rose-400 to-cyan-500 shadow-2xl transform hover:scale-105 transition-all duration-300 border-4 border-white/50">
                        <div class="text-5xl filter drop-shadow-2xl">👩‍🏫</div>
                    </div>
                    
                    <div>
                        <h1 class="text-4xl font-extrabold text-gray-800 mb-2 drop-shadow-lg flex items-center gap-4 justify-center md:justify-start">
                            <span class="md:hidden flex items-center justify-center w-16 h-16 rounded-2xl bg-gradient-to-br from-pink-500 via-rose-400 to-cyan-500 shadow-xl border-4 border-white/50">
                                <span class="text-4xl">👩‍🏫</span>
                            </span>
                            <span class="bg-clip-text text-transparent bg-gradient-to-r from-pink-600 via-rose-500 to-cyan-600">
                                {{ $user->first_name }} {{ $user->last_name }}
                            </span>
                        </h1>
                        <p class="text-gray-700 text-base font-medium">Teacher Profile & Statistics</p>
                    </div>
                </div>
                
                @if($teacherRequest)
                <div class="flex items-center gap-3">
                    <span class="inline-flex items-center px-4 py-2 rounded-xl text-sm font-semibold 
                        @if($teacherRequest->status === 'approved') bg-green-100 text-green-700 border-2 border-green-300
                        @elseif($teacherRequest->status === 'rejected') bg-red-100 text-red-700 border-2 border-red-300
                        @else bg-yellow-100 text-yellow-700 border-2 border-yellow-300
                        @endif shadow-lg">
                        Status: {{ ucfirst($teacherRequest->status) }}
                    </span>
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Content -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Left Column - Profile & Contact -->
            <div class="lg:col-span-1 space-y-6">
                <!-- Profile Card -->
                <div class="bg-white rounded-2xl shadow-xl overflow-hidden border-2 border-gray-100">
                    <div class="bg-gradient-to-br from-pink-200 via-rose-200 to-cyan-200 p-6 text-center relative overflow-hidden">
                        <div class="absolute inset-0 bg-white/10 backdrop-blur-sm"></div>
                        <div class="relative z-10">
                            @if($user->profile_image_url)
                            <img src="{{ $user->profile_image_url }}" 
                                 alt="{{ $user->first_name }}" 
                                 class="w-24 h-24 mx-auto rounded-full object-cover mb-3 border-4 border-white/50 shadow-2xl">
                            @elseif($user->profile_photo_path)
                            <img src="{{ asset('storage/' . $user->profile_photo_path) }}" 
                                 alt="{{ $user->first_name }}" 
                                 class="w-24 h-24 mx-auto rounded-full object-cover mb-3 border-4 border-white/50 shadow-2xl">
                            @else
                            <div class="w-24 h-24 mx-auto rounded-full bg-white/50 backdrop-blur-md flex items-center justify-center text-gray-700 text-4xl font-bold mb-3 border-4 border-white/70 shadow-2xl">
                                {{ strtoupper(substr($user->first_name ?? 'T', 0, 1)) }}
                            </div>
                            @endif
                            <h2 class="text-2xl font-bold text-gray-800 mb-1 drop-shadow-lg">{{ $user->first_name }} {{ $user->last_name }}</h2>
                            <p class="text-gray-700 text-sm font-medium">👩‍🏫 Teacher</p>
                        </div>
                    </div>
                    
                    <div class="p-5 space-y-5">
                        <!-- Contact Information -->
                        <div>
                            <h3 class="text-xs font-bold text-gray-600 uppercase tracking-wider mb-3 flex items-center gap-2">
                                <span class="w-1 h-4 bg-gradient-to-b from-pink-500 to-cyan-500 rounded-full"></span>
                                Contact
                            </h3>
                            <div class="space-y-3">
                                <div class="flex items-center gap-3 p-3 bg-gradient-to-r from-pink-50 to-rose-50 rounded-xl border border-pink-100">
                                    <div class="w-10 h-10 bg-gradient-to-br from-pink-400 to-rose-400 rounded-lg flex items-center justify-center flex-shrink-0 shadow-md">
                                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                        </svg>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <div class="text-xs text-gray-500 font-semibold mb-0.5">Email</div>
                                        <div class="text-sm font-semibold text-gray-900 break-words">{{ $user->email }}</div>
                                    </div>
                                </div>
                                
                                @if($user->phone_number)
                                <div class="flex items-center gap-3 p-3 bg-gradient-to-r from-cyan-50 to-teal-50 rounded-xl border border-cyan-100">
                                    <div class="w-10 h-10 bg-gradient-to-br from-cyan-400 to-teal-400 rounded-lg flex items-center justify-center flex-shrink-0 shadow-md">
                                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                                        </svg>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <div class="text-xs text-gray-500 font-semibold mb-0.5">Phone</div>
                                        <div class="text-sm font-semibold text-gray-900">{{ $user->phone_number }}</div>
                                    </div>
                                </div>
                                @endif

                                @if($user->country)
                                <div class="flex items-center gap-3 p-3 bg-gradient-to-r from-blue-50 to-indigo-50 rounded-xl border border-blue-100">
                                    <div class="w-10 h-10 bg-gradient-to-br from-blue-400 to-indigo-400 rounded-lg flex items-center justify-center flex-shrink-0 shadow-md">
                                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <div class="text-xs text-gray-500 font-semibold mb-0.5">Country</div>
                                        <div class="text-sm font-semibold text-gray-900">{{ $user->country }}</div>
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>

                        <!-- Bio Section -->
                        @if($user->bio)
                        <div class="border-t-2 border-gray-100 pt-5">
                            <h3 class="text-xs font-bold text-gray-600 uppercase tracking-wider mb-3 flex items-center gap-2">
                                <span class="w-1 h-4 bg-gradient-to-b from-pink-500 to-cyan-500 rounded-full"></span>
                                About
                            </h3>
                            <div class="p-3 bg-gradient-to-br from-gray-50 to-gray-100 rounded-xl border border-gray-200">
                                <p class="text-sm text-gray-700 leading-relaxed">{{ $user->bio }}</p>
                            </div>
                        </div>
                        @endif

                        <!-- Account Details -->
                        <div class="border-t-2 border-gray-100 pt-5">
                            <h3 class="text-xs font-bold text-gray-600 uppercase tracking-wider mb-3 flex items-center gap-2">
                                <span class="w-1 h-4 bg-gradient-to-b from-pink-500 to-cyan-500 rounded-full"></span>
                                Account
                            </h3>
                            <div class="flex items-center justify-between p-3 bg-gradient-to-r from-purple-50 to-pink-50 rounded-xl border border-purple-100">
                                <span class="text-sm font-medium text-gray-600">Date Joined</span>
                                <span class="text-sm font-bold text-gray-900">
                                    {{ $user->date_joined ? $user->date_joined->format('M d, Y') : 'N/A' }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column - Classes & Activities -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Classes Taught -->
                @if($classes->count() > 0)
                <div class="bg-white rounded-2xl shadow-xl border-2 border-gray-100 overflow-hidden">
                    <div class="bg-gradient-to-r from-teal-300/90 via-cyan-200/80 to-blue-300/90 px-6 py-4 border-b-4 border-teal-300/50">
                        <div class="flex items-center justify-between">
                            <h2 class="text-2xl font-bold text-gray-800 flex items-center gap-3">
                                <span class="text-3xl">🎓</span>
                                Classes Taught
                            </h2>
                            <span class="px-4 py-1 bg-white/50 rounded-full text-sm font-bold text-gray-800">{{ $classes->count() }} Total</span>
                        </div>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            @foreach($classes as $class)
                            <div class="bg-gradient-to-br from-gray-50 to-white rounded-xl p-5 border-2 border-gray-200 hover:border-teal-300 hover:shadow-lg transition-all">
                                <div class="flex items-start justify-between mb-3">
                                    <h3 class="text-lg font-bold text-gray-900 flex-1">{{ $class->class_name }}</h3>
                                    <span class="inline-flex items-center px-3 py-1 rounded-lg text-xs font-bold bg-gradient-to-r from-blue-100 to-cyan-100 text-blue-800 border border-blue-300">
                                        <span class="mr-1">👥</span>
                                        {{ $class->students()->count() }}
                                    </span>
                                </div>
                                @if($class->description)
                                <p class="text-sm text-gray-600 mb-3 line-clamp-2">{{ $class->description }}</p>
                                @endif
                                <div class="flex items-center justify-between pt-3 border-t border-gray-200">
                                    <div class="text-xs text-gray-600">
                                        <span class="font-semibold">Capacity:</span>
                                        <span class="font-bold text-gray-900">{{ $class->current_enrollment }}/{{ $class->capacity }}</span>
                                    </div>
                                    <span class="inline-flex items-center px-2 py-1 rounded text-xs font-bold 
                                        @if(($class->status ?? 'active') === 'active') bg-green-100 text-green-700 border border-green-300
                                        @else bg-gray-100 text-gray-700 border border-gray-300
                                        @endif">
                                        {{ ucfirst($class->status ?? 'active') }}
                                    </span>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                @else
                <div class="bg-white rounded-2xl shadow-xl border-2 border-gray-100 overflow-hidden">
                    <div class="bg-gradient-to-r from-teal-300/90 via-cyan-200/80 to-blue-300/90 px-6 py-4 border-b-4 border-teal-300/50">
                        <h2 class="text-2xl font-bold text-gray-800 flex items-center gap-3">
                            <span class="text-3xl">🎓</span>
                            Classes Taught
                        </h2>
                    </div>
                    <div class="p-12 text-center">
                        <div class="text-6xl mb-4">📚</div>
                        <p class="text-gray-600 text-lg font-medium">No classes assigned yet</p>
                    </div>
                </div>
                @endif

                <!-- Application Information -->
                @if($teacherRequest)
                <div class="bg-white rounded-2xl shadow-xl border-2 border-gray-100 overflow-hidden">
                    <div class="bg-gradient-to-r from-indigo-300/90 via-purple-200/80 to-pink-300/90 px-6 py-4 border-b-4 border-indigo-300/50">
                        <h2 class="text-2xl font-bold text-gray-800 flex items-center gap-3">
                            <span class="text-3xl">ℹ️</span>
                            Application Details
                        </h2>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            @if($teacherRequest->specialization)
                            <div class="flex items-center justify-between p-4 bg-gradient-to-br from-indigo-50 to-purple-50 rounded-xl border-2 border-indigo-200">
                                <span class="text-xs text-indigo-600 font-bold uppercase">Specialization</span>
                                <span class="text-sm font-bold text-gray-900">{{ $teacherRequest->specialization }}</span>
                            </div>
                            @endif

                            @if($teacherRequest->experience_years)
                            <div class="flex items-center justify-between p-4 bg-gradient-to-br from-purple-50 to-pink-50 rounded-xl border-2 border-purple-200">
                                <span class="text-xs text-purple-600 font-bold uppercase">Experience</span>
                                <span class="text-sm font-bold text-gray-900">{{ $teacherRequest->experience_years }} years</span>
                            </div>
                            @endif

                            @if($teacherRequest->language)
                            <div class="flex items-center justify-between p-4 bg-gradient-to-br from-pink-50 to-rose-50 rounded-xl border-2 border-pink-200">
                                <span class="text-xs text-pink-600 font-bold uppercase">Language</span>
                                <span class="text-sm font-bold text-gray-900">{{ $teacherRequest->language }}</span>
                            </div>
                            @endif

                            @if($teacherRequest->request_date)
                            <div class="flex items-center justify-between p-4 bg-gradient-to-br from-cyan-50 to-teal-50 rounded-xl border-2 border-cyan-200">
                                <span class="text-xs text-cyan-600 font-bold uppercase">Applied On</span>
                                <span class="text-sm font-bold text-gray-900">{{ $teacherRequest->request_date->format('M d, Y') }}</span>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
                @endif

                <!-- Upcoming Meetings -->
                @if($meetings->count() > 0)
                <div class="bg-white rounded-2xl shadow-xl border-2 border-gray-100 overflow-hidden">
                    <div class="bg-gradient-to-r from-green-300/90 via-emerald-200/80 to-teal-300/90 px-6 py-4 border-b-4 border-green-300/50">
                        <div class="flex items-center justify-between">
                            <h2 class="text-2xl font-bold text-gray-800 flex items-center gap-3">
                                <span class="text-3xl">📅</span>
                                Upcoming Meetings
                            </h2>
                            <span class="px-4 py-1 bg-white/50 rounded-full text-sm font-bold text-gray-800">{{ $meetings->count() }} Scheduled</span>
                        </div>
                    </div>
                    <div class="p-6">
                        <div class="space-y-3">
                            @foreach($meetings->take(10) as $meeting)
                            <div class="flex items-center justify-between p-4 bg-gradient-to-r from-green-50 via-emerald-50 to-green-50 rounded-xl border-2 border-green-200 hover:border-green-300 hover:shadow-md transition-all">
                                <div class="flex-1">
                                    <div class="text-base font-bold text-gray-900 mb-2">{{ $meeting->title ?? 'Meeting' }}</div>
                                    <div class="flex flex-wrap items-center gap-3 text-sm text-gray-600">
                                        <span class="flex items-center gap-1">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                            </svg>
                                            {{ $meeting->scheduled_at ? $meeting->scheduled_at->format('M d, Y H:i') : 'TBD' }}
                                        </span>
                                        @if($meeting->studentClass)
                                        <span class="flex items-center gap-1">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                                            </svg>
                                            {{ $meeting->studentClass->class_name }}
                                        </span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
