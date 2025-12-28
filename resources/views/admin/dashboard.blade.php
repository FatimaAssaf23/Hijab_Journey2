@extends('layouts.admin')

@section('content')
<div class="min-h-screen">
    <!-- Header -->
    <div class="bg-gradient-to-r from-[#FC8EAC] via-[#EC769A] to-[#6EC6C5] shadow-xl">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-4xl font-extrabold text-white mb-2">Admin Dashboard</h1>
                    <p class="text-pink-100">Manage lessons, classes, and teacher assignments</p>
                </div>
                
                <!-- Notification Bell -->
                @if($unreadRequestsCount > 0)
                <div class="relative" x-data="{ open: false }">
                    <button @click="open = !open" class="relative bg-white/20 hover:bg-white/30 p-3 rounded-full transition-all">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                        </svg>
                        <span class="absolute -top-1 -right-1 bg-red-500 text-white text-xs font-bold w-5 h-5 flex items-center justify-center rounded-full animate-pulse">
                            {{ $unreadRequestsCount }}
                        </span>
                    </button>
                    
                    <!-- Dropdown -->
                    <div x-show="open" @click.away="open = false" x-transition 
                         class="absolute right-0 mt-2 w-80 bg-white rounded-xl shadow-2xl border border-gray-100 z-50 overflow-hidden">
                        <div class="bg-gradient-to-r from-pink-500 to-teal-500 px-4 py-3">
                            <h3 class="text-white font-semibold">New Teacher Requests</h3>
                        </div>
                        <div class="max-h-64 overflow-y-auto">
                            @foreach($unreadRequests as $request)
                            <a href="{{ route('admin.requests') }}" class="block px-4 py-3 hover:bg-gray-50 border-b border-gray-100 transition-colors">
                                <div class="flex items-start gap-3">
                                    <div class="w-10 h-10 bg-gradient-to-br from-pink-400 to-teal-400 rounded-full flex items-center justify-center text-white font-bold text-sm flex-shrink-0">
                                        {{ substr($request->full_name ?? 'U', 0, 1) }}
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="font-semibold text-gray-800 truncate">{{ $request->full_name ?? 'Unknown' }}</p>
                                        <p class="text-sm text-gray-500 truncate">{{ $request->specialization ?? 'Teacher Application' }}</p>
                                        <p class="text-xs text-gray-400 mt-1">{{ $request->created_at->diffForHumans() }}</p>
                                    </div>
                                    <span class="bg-yellow-100 text-yellow-700 text-xs px-2 py-1 rounded-full font-medium">New</span>
                                </div>
                            </a>
                            @endforeach
                        </div>
                        <a href="{{ route('admin.requests') }}" class="block px-4 py-3 text-center text-pink-600 hover:bg-pink-50 font-medium transition-colors">
                            View All Requests →
                        </a>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- New Teacher Request Alert Banner -->
    @if($unreadRequestsCount > 0)
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-6">
        <div class="bg-gradient-to-r from-yellow-50 to-orange-50 border border-yellow-200 rounded-xl p-4 flex items-center gap-4 shadow-sm">
            <div class="bg-yellow-100 p-3 rounded-full">
                <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
            </div>
            <div class="flex-1">
                <h4 class="font-semibold text-yellow-800">You have {{ $unreadRequestsCount }} new teacher application{{ $unreadRequestsCount > 1 ? 's' : '' }}!</h4>
                <p class="text-sm text-yellow-700">Review and respond to pending teacher requests.</p>
            </div>
            <a href="{{ route('admin.requests') }}" class="bg-yellow-500 hover:bg-yellow-600 text-white font-semibold px-4 py-2 rounded-lg transition-all">
                Review Now
            </a>
        </div>
    </div>
    @endif

    <!-- Stats Cards -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <!-- Lessons Card -->
            <div class="bg-[#79BDBC] border border-[#79BDBC] rounded-xl p-6 hover:border-[#B5D7D5] transition-all">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-white text-sm font-medium drop-shadow-sm">📚 Lessons</p>
                        <p class="text-3xl font-bold text-white mt-2">{{ $lessonsCount }}</p>
                    </div>
                    <a href="{{ route('admin.lessons') }}" class="bg-white hover:bg-[#B5D7D5] text-[#197D8C] p-3 rounded-lg transition-all">
                        →
                    </a>
                </div>
            </div>

            <!-- Classes Card -->
            <div class="bg-[#FFB9C6] border border-[#FFB9C6] rounded-xl p-6 hover:border-[#FBCFDD] transition-all">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-[#C2185B] text-sm font-medium drop-shadow-sm">🏫 Classes</p>
                        <p class="text-3xl font-bold text-[#C2185B] mt-2">{{ $classesCount }}</p>
                    </div>
                    <a href="{{ route('admin.classes') }}" class="bg-[#EC769A] hover:bg-[#FC8EAC] text-white p-3 rounded-lg transition-all">
                        →
                    </a>
                </div>
            </div>

            <!-- Teacher Requests Card -->
            <div class="bg-[#B5D7D5] border border-[#79BDBC] rounded-xl p-6 hover:border-[#79BDBC] transition-all">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-[#197D8C] text-sm font-medium drop-shadow-sm">✓ Requests</p>
                        <p class="text-3xl font-bold text-[#197D8C] mt-2">{{ $teacherRequestsCount }}</p>
                    </div>
                    <a href="{{ route('admin.requests') }}" class="bg-[#79BDBC] hover:bg-[#B5D7D5] text-white p-3 rounded-lg transition-all">
                        →
                    </a>
                </div>
            </div>

            <!-- Emergency Cases Card -->
            <div class="bg-[#FBCFDD] border border-[#FFB9C6] rounded-xl p-6 hover:border-[#FFB9C6] transition-all">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-[#C2185B] text-sm font-medium drop-shadow-sm">⚠️ Emergency</p>
                        <p class="text-3xl font-bold text-[#C2185B] mt-2">{{ $emergencyCasesCount }}</p>
                    </div>
                    <a href="{{ route('admin.emergency') }}" class="bg-[#FFB9C6] hover:bg-[#FBCFDD] text-white p-3 rounded-lg transition-all">
                        →
                    </a>
                </div>
            </div>
        </div>

        <!-- Quick Actions section removed as requested -->
    </div>
</div>
@endsection
