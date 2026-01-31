@extends('layouts.app')

@section('content')
<div class="h-screen bg-gradient-to-br from-pink-50 via-white to-cyan-50 flex flex-col overflow-hidden">
    <!-- Header Section -->
    <div class="flex-shrink-0 bg-gradient-to-br from-pink-200/80 via-rose-100/70 to-cyan-200/80 border-b border-pink-200/50 px-4 sm:px-6 lg:px-8 py-6">
        <div class="flex flex-col lg:flex-row items-center gap-4">
            <!-- Left: Back Button -->
            <div class="flex-shrink-0">
                <a href="{{ route('teacher.dashboard') }}" 
                   class="px-4 py-2 bg-white/90 backdrop-blur-md border-2 border-pink-300/50 rounded-xl hover:bg-white hover:shadow-lg transition-all font-semibold text-gray-700 flex items-center gap-2 text-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Back
                </a>
            </div>
            
            <div class="flex-1 text-center lg:text-left">
                <div class="inline-flex items-center gap-2 bg-white/40 backdrop-blur-md px-3 py-1.5 rounded-full mb-2 border border-pink-300/30 shadow-md">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-pink-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                    </svg>
                    <span class="text-pink-700 font-semibold text-xs tracking-wide">GROUP CHAT</span>
                </div>
                <h1 class="text-xl lg:text-2xl font-black text-gray-800 mb-1">
                    <span class="bg-gradient-to-r from-pink-500 to-cyan-500 bg-clip-text text-transparent">Select a Class</span> 💬
                </h1>
                <p class="text-xs lg:text-sm text-gray-700 font-medium">
                    Choose a class to view its group chat
                </p>
            </div>
        </div>
    </div>

    <!-- Classes Grid -->
    <div class="flex-1 overflow-y-auto px-4 sm:px-6 lg:px-8 py-6">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($teacherClasses as $index => $class)
                @php
                    $students = $class->students()->with('user')->get();
                    $studentCount = $students->count();
                    $capacity = $class->capacity ?? 10;
                    $status = $class->status ?? 'active';
                    
                    // Color variations for cards
                    $colors = [
                        ['bg' => 'from-pink-200/80', 'to' => 'to-rose-100/70', 'border' => 'border-pink-200/50', 'icon' => 'text-pink-600'],
                        ['bg' => 'from-cyan-200/80', 'to' => 'to-blue-100/70', 'border' => 'border-cyan-200/50', 'icon' => 'text-cyan-600'],
                        ['bg' => 'from-purple-200/80', 'to' => 'to-indigo-100/70', 'border' => 'border-purple-200/50', 'icon' => 'text-purple-600'],
                        ['bg' => 'from-teal-200/80', 'to' => 'to-emerald-100/70', 'border' => 'border-teal-200/50', 'icon' => 'text-teal-600'],
                        ['bg' => 'from-rose-200/80', 'to' => 'to-pink-100/70', 'border' => 'border-rose-200/50', 'icon' => 'text-rose-600'],
                        ['bg' => 'from-blue-200/80', 'to' => 'to-cyan-100/70', 'border' => 'border-blue-200/50', 'icon' => 'text-blue-600'],
                    ];
                    $colorIndex = $index % count($colors);
                    $cardColor = $colors[$colorIndex];
                    
                    // Status badge colors
                    $statusColors = [
                        'active' => ['bg' => 'bg-green-100', 'text' => 'text-green-700', 'border' => 'border-green-300'],
                        'full' => ['bg' => 'bg-pink-100', 'text' => 'text-pink-700', 'border' => 'border-pink-300'],
                        'closed' => ['bg' => 'bg-gray-100', 'text' => 'text-gray-700', 'border' => 'border-gray-300'],
                        'empty' => ['bg' => 'bg-yellow-100', 'text' => 'text-yellow-700', 'border' => 'border-yellow-300'],
                    ];
                    $statusColor = $statusColors[$status] ?? $statusColors['active'];
                @endphp
                
                <a href="{{ route('group-chat.index', $class->class_id) }}" 
                   class="group relative bg-gradient-to-br {{ $cardColor['bg'] }} {{ $cardColor['to'] }} rounded-2xl shadow-xl overflow-hidden transform transition-all duration-500 hover:shadow-2xl hover:scale-105 border {{ $cardColor['border'] }} backdrop-blur-sm animate-fade-in-up"
                   style="animation-delay: {{ ($index % 3) * 0.1 }}s;">
                    <!-- Pattern Overlay -->
                    <div class="absolute inset-0 opacity-5">
                        <div class="absolute top-0 left-0 w-full h-full" style="background-image: radial-gradient(circle, rgba(255,255,255,0.4) 1px, transparent 1px); background-size: 20px 20px;"></div>
                    </div>
                    
                    <!-- Decorative corner accent -->
                    <div class="absolute top-0 right-0 w-24 h-24 bg-white/20 rounded-bl-full transform translate-x-8 -translate-y-8"></div>
                    
                    <div class="relative p-6">
                        <!-- Header with class name and icon -->
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-xl font-black text-gray-800 group-hover:text-gray-900 transition-colors">
                                {{ $class->class_name }}
                            </h3>
                            <div class="w-12 h-12 bg-white/40 backdrop-blur-md rounded-xl flex items-center justify-center shadow-md border border-white/50 group-hover:scale-110 transition-transform">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 {{ $cardColor['icon'] }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                                </svg>
                            </div>
                        </div>
                        
                        <!-- Class Info -->
                        <div class="space-y-3 mb-4">
                            <!-- Students Count -->
                            <div class="flex items-center gap-2 text-sm font-semibold text-gray-700">
                                <div class="w-8 h-8 bg-white/60 backdrop-blur-sm rounded-lg flex items-center justify-center border border-white/50">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 {{ $cardColor['icon'] }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                    </svg>
                                </div>
                                <span>{{ $studentCount }} / {{ $capacity }} students</span>
                            </div>
                            
                            <!-- Status -->
                            <div class="flex items-center gap-2 text-sm font-semibold text-gray-700">
                                <div class="w-8 h-8 bg-white/60 backdrop-blur-sm rounded-lg flex items-center justify-center border border-white/50">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 {{ $cardColor['icon'] }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                                    </svg>
                                </div>
                                <span>Status:</span>
                                <span class="px-3 py-1 rounded-full text-xs font-bold {{ $statusColor['bg'] }} {{ $statusColor['text'] }} border {{ $statusColor['border'] }} capitalize">
                                    {{ $status }}
                                </span>
                            </div>
                            
                            @if($class->description)
                                <p class="text-xs text-gray-600 mt-2 line-clamp-2">{{ Str::limit($class->description, 80) }}</p>
                            @endif
                        </div>
                        
                        <!-- View Chat Button -->
                        <div class="pt-4 border-t border-white/30">
                            <div class="flex items-center justify-between">
                                <span class="text-sm font-bold text-gray-700 group-hover:text-gray-900 transition-colors">View Chat</span>
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 {{ $cardColor['icon'] }} transform group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                                </svg>
                            </div>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>
    </div>
</div>

@push('styles')
<style>
    /* Full screen layout */
    html, body {
        height: 100%;
        margin: 0;
        padding: 0;
        overflow: hidden;
    }
    
    @keyframes fade-in {
        from {
            opacity: 0;
        }
        to {
            opacity: 1;
        }
    }
    
    @keyframes fade-in-up {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    @keyframes slide-in-left {
        from {
            opacity: 0;
            transform: translateX(-20px);
        }
        to {
            opacity: 1;
            transform: translateX(0);
        }
    }
    
    @keyframes slide-in-right {
        from {
            opacity: 0;
            transform: translateX(20px);
        }
        to {
            opacity: 1;
            transform: translateX(0);
        }
    }
    
    .animate-fade-in {
        animation: fade-in 0.6s ease-out;
    }
    
    .animate-fade-in-up {
        animation: fade-in-up 0.6s ease-out forwards;
        opacity: 0;
    }
    
    .animate-slide-in-left {
        animation: slide-in-left 0.6s ease-out forwards;
    }
    
    .animate-slide-in-right {
        animation: slide-in-right 0.6s ease-out forwards;
    }
    
</style>
@endpush
@endsection
