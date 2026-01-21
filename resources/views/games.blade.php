@extends('layouts.app')

@section('content')
@push('styles')
<style>
    @keyframes float {
        0%, 100% { transform: translateY(0px); }
        50% { transform: translateY(-10px); }
    }
    @keyframes shimmer {
        0% { background-position: -1000px 0; }
        100% { background-position: 1000px 0; }
    }
    @keyframes gradient-shift {
        0%, 100% { background-position: 0% 50%; }
        50% { background-position: 100% 50%; }
    }
    .float-animation {
        animation: float 3s ease-in-out infinite;
    }
    .shimmer-effect {
        background-size: 200% 100%;
        animation: shimmer 3s infinite;
    }
    .gradient-animated {
        background-size: 200% 200%;
        animation: gradient-shift 3s ease infinite;
    }
    .card-hover-effect {
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .card-hover-effect:hover {
        transform: translateY(-5px) scale(1.01);
    }
</style>
@endpush

<div class="min-h-screen bg-gradient-to-br from-pink-50 via-pink-100/40 via-cyan-50/30 to-teal-50/20 relative overflow-hidden">
    <!-- Enhanced Animated Background Elements -->
    <div class="absolute inset-0 overflow-hidden pointer-events-none">
        <div class="absolute -top-40 -left-40 w-[500px] h-[500px] bg-pink-200/40 rounded-full opacity-20 blur-3xl animate-pulse"></div>
        <div class="absolute top-1/2 -right-40 w-[500px] h-[500px] bg-cyan-200/40 rounded-full opacity-20 blur-3xl animate-pulse" style="animation-delay: 1.5s;"></div>
        <div class="absolute bottom-0 left-1/2 w-[400px] h-[400px] bg-teal-200/30 rounded-full opacity-15 blur-3xl animate-pulse" style="animation-delay: 2.5s;"></div>
        <div class="absolute top-1/4 right-1/4 w-[300px] h-[300px] bg-purple-200/20 rounded-full opacity-10 blur-2xl animate-pulse" style="animation-delay: 3.5s;"></div>
    </div>
    
    <!-- Floating decorative elements -->
    <div class="absolute top-20 right-20 w-32 h-32 bg-pink-200/20 rounded-full blur-2xl animate-bounce" style="animation-duration: 6s;"></div>
    <div class="absolute bottom-20 left-20 w-40 h-40 bg-cyan-200/20 rounded-full blur-2xl animate-bounce" style="animation-duration: 8s; animation-delay: 2s;"></div>
    
    <div class="container mx-auto py-8 relative z-10">
        <!-- Enhanced Header Section -->
        <div class="max-w-7xl mx-auto mb-8">
            <div class="relative bg-gradient-to-br from-white/95 via-pink-50/90 to-cyan-50/90 backdrop-blur-xl rounded-3xl shadow-2xl p-8 border-2 border-pink-200/50 overflow-hidden transform transition-all duration-500 hover:shadow-3xl">
                <!-- Decorative pattern overlay -->
                <div class="absolute inset-0 opacity-5">
                    <div class="absolute inset-0" style="background-image: radial-gradient(circle, rgba(252, 142, 172, 0.3) 1px, transparent 1px); background-size: 30px 30px;"></div>
                </div>
                
                <!-- Animated gradient border -->
                <div class="absolute inset-0 rounded-3xl bg-gradient-to-r from-pink-400 via-cyan-400 to-pink-400 opacity-20 blur-xl animate-pulse"></div>
                
                <div class="relative flex items-center justify-between mb-6">
                    <div class="flex items-center gap-5">
                        <!-- Enhanced Go Back Button -->
                        <button onclick="goBackOrRedirect('{{ route('teacher.dashboard') }}')" 
                                class="group relative flex items-center gap-2.5 px-6 py-3 rounded-2xl font-bold text-white shadow-lg hover:shadow-2xl transition-all duration-300 transform hover:scale-110 hover:-translate-x-1 overflow-hidden border-2 border-white/30 backdrop-blur-sm z-10"
                                style="background: linear-gradient(135deg, #FC8EAC 0%, #EC769A 50%, #6EC6C5 100%);">
                            <!-- Animated shimmer effect -->
                            <div class="absolute inset-0 bg-gradient-to-r from-transparent via-white/30 to-transparent transform -translate-x-full group-hover:translate-x-full transition-transform duration-1000"></div>
                            <!-- Button content -->
                            <div class="relative flex items-center gap-2.5">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 transition-transform duration-300 group-hover:-translate-x-2 group-hover:scale-125" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                                </svg>
                                <span class="text-sm font-bold tracking-wide">Go Back</span>
                            </div>
                        </button>
                        
                        <!-- Enhanced Game Icon with animation -->
                        <div class="relative">
                            <div class="absolute inset-0 bg-gradient-to-br from-pink-400 to-cyan-500 rounded-2xl blur-lg opacity-50 animate-pulse"></div>
                            <div class="relative w-16 h-16 bg-gradient-to-br from-pink-400 via-rose-400 to-cyan-500 rounded-2xl flex items-center justify-center shadow-2xl transform hover:scale-110 hover:rotate-6 transition-all duration-300 border-2 border-white/30">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-9 w-9 text-white drop-shadow-lg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                        </div>
                        
                        <!-- Enhanced Title Section -->
                        <div class="flex-1">
                            <div class="flex items-center gap-3 mb-3">
                                <h1 class="text-4xl font-black bg-gradient-to-r from-pink-600 via-rose-500 to-cyan-600 bg-clip-text text-transparent drop-shadow-sm">
                                    Game Creator
                                </h1>
                                <span class="text-2xl animate-bounce" style="animation-duration: 2s;">🎮</span>
                            </div>
                            <p class="text-gray-700 font-semibold text-lg mb-2 flex items-center gap-2">
                                <span class="w-2 h-2 bg-gradient-to-r from-pink-400 to-cyan-400 rounded-full animate-pulse"></span>
                                Create and manage interactive games for your lessons
                            </p>
                        </div>
                    </div>
                </div>
                
                <!-- Alert Messages -->
                @if(session('success'))
                    <div class="mb-4 p-4 bg-gradient-to-r from-green-50 to-emerald-50 border-l-4 border-green-500 rounded-lg shadow-md flex items-start gap-3">
                        <div class="flex-shrink-0">
                            <svg class="h-6 w-6 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div class="flex-1">
                            <p class="font-semibold text-green-800">{{ session('success') }}</p>
                        </div>
                    </div>
                @endif
                
                @if(session('error'))
                    <div class="mb-4 p-4 bg-gradient-to-r from-red-50 to-rose-50 border-l-4 border-red-500 rounded-lg shadow-md flex items-start gap-3">
                        <div class="flex-shrink-0">
                            <svg class="h-6 w-6 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div class="flex-1">
                            <p class="font-semibold text-red-800">{{ session('error') }}</p>
                        </div>
                    </div>
                @endif
                
                @if($errors->any())
                    <div class="mb-4 p-4 bg-gradient-to-r from-red-50 to-rose-50 border-l-4 border-red-500 rounded-lg shadow-md">
                        <div class="flex items-start gap-3 mb-2">
                            <svg class="h-6 w-6 text-red-500 flex-shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <p class="font-semibold text-red-800">Please fix the following errors:</p>
                        </div>
                        <ul class="list-disc list-inside space-y-1 ml-9">
                            @foreach($errors->all() as $error)
                                <li class="text-red-700">{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <!-- Enhanced Lesson & Class Selector -->
                <div class="mt-6 pt-6 border-t-2 border-pink-200/40">
                    <p class="text-gray-600 font-medium text-sm mb-5 ml-2">
                        Choose a lesson to create games for. You can optionally assign the game to a specific class after creation.
                    </p>
                <form method="GET" action="">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="relative group">
                            <label for="lesson_id" class="block font-bold text-gray-800 mb-3 text-lg flex items-center gap-3">
                                <div class="relative">
                                    <div class="absolute inset-0 bg-gradient-to-br from-pink-300 to-cyan-400 rounded-xl blur-md opacity-50 group-hover:opacity-75 transition-opacity"></div>
                                    <div class="relative w-10 h-10 bg-gradient-to-br from-pink-400 to-cyan-500 rounded-xl flex items-center justify-center shadow-lg transform group-hover:scale-110 transition-transform">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                        </svg>
                                    </div>
                                </div>
                                <span class="bg-gradient-to-r from-pink-600 to-rose-600 bg-clip-text text-transparent">Select Lesson:</span>
                            </label>
                            <div class="relative">
                                <select name="lesson_id" id="lesson_id" 
                                        class="w-full bg-gradient-to-br from-pink-50 to-rose-50 border-2 border-pink-300/60 rounded-xl px-5 py-3.5 pr-12 text-gray-800 font-semibold shadow-md hover:border-pink-400 hover:shadow-lg focus:border-pink-500 focus:ring-2 focus:ring-pink-200/50 focus:bg-pink-100 transition-all duration-300 appearance-none cursor-pointer"
                                        onchange="this.form.submit()">
                                    <option value="">-- Choose Lesson --</option>
                                    @foreach($lessons ?? [] as $lesson)
                                        <option value="{{ $lesson->lesson_id }}" {{ (isset($selectedLessonId) && $selectedLessonId == $lesson->lesson_id) ? 'selected' : '' }}>{{ $lesson->title }}</option>
                                    @endforeach
                                </select>
                                <div class="absolute inset-y-0 right-0 flex items-center pr-5 pointer-events-none">
                                    <svg class="h-6 w-6 text-pink-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                                    </svg>
                                </div>
                            </div>
                        </div>
                        @if(isset($selectedLessonId) && $selectedLessonId)
                        <div class="relative group">
                            <label for="class_id" class="block font-bold text-gray-800 mb-3 text-lg flex items-center gap-3">
                                <div class="relative">
                                    <div class="absolute inset-0 bg-gradient-to-br from-cyan-300 to-teal-400 rounded-xl blur-md opacity-50 group-hover:opacity-75 transition-opacity"></div>
                                    <div class="relative w-10 h-10 bg-gradient-to-br from-cyan-400 to-teal-500 rounded-xl flex items-center justify-center shadow-lg transform group-hover:scale-110 transition-transform">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                        </svg>
                                    </div>
                                </div>
                                <span class="bg-gradient-to-r from-cyan-600 to-teal-600 bg-clip-text text-transparent">Assign to Class (Optional):</span>
                            </label>
                            <div class="relative">
                                <select name="class_id" id="class_id" 
                                        class="w-full bg-gradient-to-br from-cyan-50 to-teal-50 border-2 border-cyan-300/60 rounded-xl px-5 py-3.5 pr-12 text-gray-800 font-semibold shadow-md hover:border-cyan-400 hover:shadow-lg focus:border-cyan-500 focus:ring-2 focus:ring-cyan-200/50 transition-all duration-300 appearance-none cursor-pointer">
                                    <option value="">-- Choose Class --</option>
                                    @foreach($classes ?? [] as $class)
                                        <option value="{{ $class->class_id }}">{{ $class->class_name }}</option>
                                    @endforeach
                                </select>
                                <div class="absolute inset-y-0 right-0 flex items-center pr-5 pointer-events-none">
                                    <svg class="h-6 w-6 text-cyan-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                                    </svg>
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>
                </form>
                </div>
            </div>
        </div>

        @if(isset($selectedLessonId) && $selectedLessonId)
        <!-- Enhanced Word Search Game Section -->
        <div class="max-w-7xl mx-auto mb-8">
            <div class="relative bg-gradient-to-br from-white/95 via-pink-50/90 to-rose-50/90 backdrop-blur-xl rounded-3xl shadow-2xl p-8 border-2 border-pink-200/50 transform transition-all duration-500 hover:shadow-3xl hover:scale-[1.01] overflow-hidden">
                <!-- Decorative elements -->
                <div class="absolute top-0 right-0 w-64 h-64 bg-gradient-to-br from-pink-200/20 to-rose-200/20 rounded-full blur-3xl -mr-32 -mt-32"></div>
                <div class="absolute bottom-0 left-0 w-48 h-48 bg-gradient-to-br from-cyan-200/15 to-teal-200/15 rounded-full blur-2xl -ml-24 -mb-24"></div>
                
                <div class="relative flex items-center gap-5 mb-6 pb-5 border-b-2 border-gradient-to-r from-pink-200/60 to-rose-200/60">
                    <div class="relative">
                        <div class="absolute inset-0 bg-gradient-to-br from-pink-400 to-rose-500 rounded-2xl blur-lg opacity-50 animate-pulse"></div>
                        <div class="relative w-16 h-16 bg-gradient-to-br from-pink-400 via-rose-400 to-pink-500 rounded-2xl flex items-center justify-center shadow-xl transform hover:scale-110 hover:rotate-3 transition-all duration-300 border-2 border-white/30">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-white drop-shadow-lg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </div>
                    </div>
                    <div class="flex-1">
                        <h3 class="text-2xl font-black bg-gradient-to-r from-pink-600 to-rose-600 bg-clip-text text-transparent mb-2">
                            Word Search Puzzle
                        </h3>
                        <p class="text-gray-700 font-semibold mb-2">Create engaging word search games for your students</p>
                        <p class="text-gray-600 text-sm leading-relaxed max-w-xl">
                            Build custom word search puzzles with Arabic or English words. Students search for hidden words in a grid, making vocabulary learning interactive and enjoyable.
                        </p>
                    </div>
                    <div class="text-3xl animate-bounce" style="animation-duration: 3s;">🔍</div>
                </div>
        @php
            $wordSearchData = null;
            if (isset($wordSearchGame) && $wordSearchGame) {
                $wordSearchData = [
                    'title' => $wordSearchGame->title ?? '',
                    'words' => is_array($wordSearchGame->words) ? $wordSearchGame->words : [],
                    'grid_size' => $wordSearchGame->grid_size ?? 10,
                    'grid_data' => $wordSearchGame->grid_data ?? null
                ];
            }
        @endphp
                @if($wordSearchData && !empty($wordSearchData['words']))
                    <div id="wordSearchSavedView" class="relative mb-6 p-8 bg-gradient-to-br from-green-50 via-emerald-50 to-teal-50 border-2 border-green-300/60 rounded-3xl shadow-2xl overflow-hidden card-hover-effect">
                        <!-- Decorative background elements -->
                        <div class="absolute top-0 right-0 w-48 h-48 bg-green-200/20 rounded-full blur-3xl -mr-24 -mt-24"></div>
                        <div class="absolute bottom-0 left-0 w-40 h-40 bg-emerald-200/20 rounded-full blur-2xl -ml-20 -mb-20"></div>
                        
                        <div class="relative flex items-center justify-between mb-6">
                            <div class="flex items-center gap-4">
                                <div class="relative">
                                    <div class="absolute inset-0 bg-green-500 rounded-2xl blur-lg opacity-50 animate-pulse"></div>
                                    <div class="relative w-14 h-14 bg-gradient-to-br from-green-500 via-emerald-500 to-teal-500 rounded-2xl flex items-center justify-center shadow-xl transform hover:scale-110 hover:rotate-6 transition-all duration-300 border-2 border-white/30">
                                        <svg class="h-8 w-8 text-white drop-shadow-lg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                        </svg>
                                    </div>
                                </div>
                                <div>
                                    <h4 class="text-xl font-black bg-gradient-to-r from-green-700 to-emerald-700 bg-clip-text text-transparent mb-2">
                                        Saved Word Search Game
                                    </h4>
                                    <p class="text-green-700 font-semibold text-sm mb-1">Your game is ready to use!</p>
                                    <p class="text-green-600 text-xs leading-relaxed max-w-xl">
                                        View the game to see how it looks, or edit it to make changes. Once satisfied, you can assign it to your classes.
                                    </p>
                                </div>
                            </div>
                            <div class="flex items-center gap-3">
                                <button type="button" id="viewWordSearchBtn" class="group relative px-6 py-3 rounded-2xl bg-gradient-to-r from-blue-500 via-cyan-500 to-blue-600 text-white font-bold shadow-lg hover:shadow-2xl transform hover:scale-110 transition-all duration-300 flex items-center gap-2.5 overflow-hidden border-2 border-white/20">
                                    <div class="absolute inset-0 bg-gradient-to-r from-transparent via-white/30 to-transparent transform -translate-x-full group-hover:translate-x-full transition-transform duration-700"></div>
                                    <svg class="h-5 w-5 relative z-10" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                    <span class="relative z-10">View</span>
                                </button>
                                <button type="button" id="editWordSearchBtn" class="group relative px-6 py-3 rounded-2xl bg-gradient-to-r from-yellow-500 via-orange-500 to-yellow-600 text-white font-bold shadow-lg hover:shadow-2xl transform hover:scale-110 transition-all duration-300 flex items-center gap-2.5 overflow-hidden border-2 border-white/20">
                                    <div class="absolute inset-0 bg-gradient-to-r from-transparent via-white/30 to-transparent transform -translate-x-full group-hover:translate-x-full transition-transform duration-700"></div>
                                    <svg class="h-5 w-5 relative z-10" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                    <span class="relative z-10">Edit</span>
                                </button>
                            </div>
                        </div>
                @if(!empty($wordSearchData['title']))
                    <div class="mb-4 p-3 bg-white/60 rounded-xl border border-green-200/50">
                        <div class="flex items-center gap-2 mb-1">
                            <svg class="h-4 w-4 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                            </svg>
                            <strong class="text-green-800 font-bold text-sm">Game Title:</strong>
                        </div>
                        <p class="text-green-900 font-bold text-base ml-6" dir="rtl">{{ $wordSearchData['title'] }}</p>
                    </div>
                @endif
                        <div class="mb-5">
                            <div class="flex items-center gap-2 mb-3">
                                <svg class="h-4 w-4 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                <span class="text-green-800 font-semibold text-sm">Words to Find:</span>
                            </div>
                            <div class="flex flex-wrap gap-2.5">
                            @foreach($wordSearchData['words'] as $word)
                                @php
                                    // Clean word - remove ALL non-Arabic characters
                                    $cleanWord = preg_replace('/[^\p{Arabic}]/u', '', trim((string)$word));
                                    if (empty($cleanWord)) {
                                        $cleanWord = preg_replace('/[^\x{0600}-\x{06FF}]/u', '', trim((string)$word));
                                    }
                                    if (empty($cleanWord)) {
                                        // Character by character fallback
                                        $cleanWord = '';
                                        $length = mb_strlen($word, 'UTF-8');
                                        for ($i = 0; $i < $length; $i++) {
                                            $char = mb_substr($word, $i, 1, 'UTF-8');
                                            $code = mb_ord($char, 'UTF-8');
                                            if ($code >= 1536 && $code <= 1791) {
                                                $cleanWord .= $char;
                                            }
                                        }
                                    }
                                    $cleanWord = trim($cleanWord) ?: $word;
                                @endphp
                                <span class="px-4 py-2.5 bg-gradient-to-br from-green-100 to-emerald-100 rounded-lg text-green-900 font-semibold text-sm shadow-sm border border-green-300 hover:scale-105 transition-transform duration-200" dir="rtl">{{ $cleanWord }}</span>
                            @endforeach
                            </div>
                        </div>
                        <div class="p-4 bg-white/60 rounded-xl border border-green-200/50">
                            <div class="flex items-center gap-2 mb-2">
                                <svg class="h-5 w-5 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
                                </svg>
                                <span class="font-bold text-green-800 text-sm">Game Configuration:</span>
                            </div>
                            <div class="ml-7 space-y-1.5">
                                <div class="flex items-center gap-2">
                                    <span class="text-gray-700 text-sm font-medium">Grid Size:</span>
                                    <span class="px-3 py-1 bg-green-200 rounded-lg text-green-900 font-bold text-sm border border-green-300">{{ $wordSearchData['grid_size'] }}x{{ $wordSearchData['grid_size'] }}</span>
                                    <span class="text-xs text-gray-600">({{ $wordSearchData['grid_size'] * $wordSearchData['grid_size'] }} cells)</span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <span class="text-gray-700 text-sm font-medium">Total Words:</span>
                                    <span class="px-3 py-1 bg-emerald-200 rounded-lg text-emerald-900 font-bold text-sm border border-emerald-300">{{ count($wordSearchData['words']) }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
                <div id="wordSearchSection" class="{{ (isset($wordSearchData) && !empty($wordSearchData['words'])) ? 'hidden' : '' }}">
                    <form id="wordSearchForm" method="POST" action="{{ route('teacher.games.word-search.store') }}">
                        @csrf
                        <input type="hidden" name="word_search_lesson_id" value="{{ $selectedLessonId }}">
                        <input type="hidden" name="class_id" id="word_search_class_id" value="">
                        
                        <div class="mb-6">
                            <label for="word_search_title" class="block font-bold text-gray-800 mb-2 text-lg" dir="rtl">عنوان اللعبة / Title:</label>
                            <input type="text" 
                                   name="word_search_title" 
                                   id="word_search_title" 
                                   class="w-full max-w-md bg-pink-50 border-2 border-pink-200/60 rounded-xl px-4 py-3 text-gray-800 font-medium shadow-md hover:border-pink-300 hover:bg-pink-100 focus:border-pink-400 focus:ring-2 focus:ring-pink-200 focus:bg-pink-50 transition-all duration-300" 
                                   value="{{ $wordSearchData['title'] ?? '' }}" 
                                   placeholder="أدخل العنوان" 
                                   dir="rtl">
                            <p class="text-sm text-gray-500 mt-2 flex items-center gap-1" dir="rtl">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                (اختياري / Optional)
                            </p>
                        </div>
                        
                        <div class="mb-6">
                            <label for="grid_size" class="block font-bold text-gray-800 mb-2 text-lg">Grid Size:</label>
                            <div class="relative w-48">
                                <select name="grid_size" id="grid_size" class="w-full bg-pink-50 border-2 border-pink-200/60 rounded-xl px-4 py-3 text-gray-800 font-semibold shadow-md hover:border-pink-300 hover:bg-pink-100 focus:border-pink-400 focus:ring-2 focus:ring-pink-200 focus:bg-pink-50 transition-all duration-300 appearance-none cursor-pointer">
                                    <option value="8" {{ (isset($wordSearchData) && $wordSearchData['grid_size'] == 8) ? 'selected' : '' }}>8x8</option>
                                    <option value="10" {{ (isset($wordSearchData) && $wordSearchData['grid_size'] == 10) ? 'selected' : 'selected' }}>10x10</option>
                                    <option value="12" {{ (isset($wordSearchData) && $wordSearchData['grid_size'] == 12) ? 'selected' : '' }}>12x12</option>
                                    <option value="15" {{ (isset($wordSearchData) && $wordSearchData['grid_size'] == 15) ? 'selected' : '' }}>15x15</option>
                                </select>
                                <div class="absolute inset-y-0 right-0 flex items-center pr-4 pointer-events-none">
                                    <svg class="h-5 w-5 text-pink-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                    </svg>
                                </div>
                            </div>
                            <p class="text-sm text-gray-600 mt-2 flex items-center gap-1">
                                <svg class="h-4 w-4 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                Choose the size of the word search grid
                            </p>
                        </div>
                        
                        <div class="mb-6">
                            <label class="block font-bold text-gray-800 mb-3 text-lg flex items-center gap-2">
                                <svg class="h-5 w-5 text-pink-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14" />
                                </svg>
                                Words to Find:
                            </label>
                            <p class="text-sm text-gray-600 mb-4 pl-7">Add words that students will search for in the puzzle.</p>
                    <div id="wordSearchWordsBoxes">
                        @if(isset($wordSearchData) && !empty($wordSearchData['words']))
                            @foreach($wordSearchData['words'] as $word)
                                @php
                                    // Clean word - remove ALL non-Arabic characters before displaying in form
                                    $cleanWord = preg_replace('/[^\p{Arabic}]/u', '', trim((string)$word));
                                    if (empty($cleanWord)) {
                                        $cleanWord = preg_replace('/[^\x{0600}-\x{06FF}]/u', '', trim((string)$word));
                                    }
                                    if (empty($cleanWord)) {
                                        // Character by character fallback
                                        $cleanWord = '';
                                        $length = mb_strlen($word, 'UTF-8');
                                        for ($i = 0; $i < $length; $i++) {
                                            $char = mb_substr($word, $i, 1, 'UTF-8');
                                            $code = mb_ord($char, 'UTF-8');
                                            if ($code >= 1536 && $code <= 1791) {
                                                $cleanWord .= $char;
                                            }
                                        }
                                    }
                                    $cleanWord = trim($cleanWord) ?: $word;
                                @endphp
                                <div class="word-search-word-box flex items-center gap-3 mb-3 p-3 bg-pink-50 rounded-xl border-2 border-pink-200 hover:border-pink-300 transition-colors">
                                    <input type="text" name="word_search_words[]" 
                                           class="flex-1 bg-pink-50 border-2 border-pink-200 rounded-lg px-4 py-2.5 text-gray-800 font-medium hover:bg-pink-100 focus:border-pink-400 focus:ring-2 focus:ring-pink-200 focus:bg-pink-50 transition-all" 
                                           value="{{ $cleanWord }}" 
                                           placeholder="Enter word" required>
                                    <button type="button" class="removeWordSearchWordBox px-4 py-2.5 bg-gradient-to-r from-red-400 to-rose-500 text-white rounded-lg font-bold shadow-md hover:from-red-500 hover:to-rose-600 transform hover:scale-105 transition-all duration-200">&times;</button>
                                </div>
                            @endforeach
                        @else
                                <!-- Initial word box when no saved data -->
                                <div class="word-search-word-box flex items-center gap-3 mb-3 p-3 bg-pink-50 rounded-xl border-2 border-pink-200 hover:border-pink-300 transition-colors">
                                    <input type="text" name="word_search_words[]" 
                                           class="flex-1 bg-pink-50 border-2 border-pink-200 rounded-lg px-4 py-2.5 text-gray-800 font-medium hover:bg-pink-100 focus:border-pink-400 focus:ring-2 focus:ring-pink-200 focus:bg-pink-50 transition-all" 
                                           placeholder="Enter word" required>
                                    <button type="button" class="removeWordSearchWordBox px-4 py-2.5 bg-gradient-to-r from-red-400 to-rose-500 text-white rounded-lg font-bold shadow-md hover:from-red-500 hover:to-rose-600 transform hover:scale-105 transition-all duration-200">&times;</button>
                                </div>
                        @endif
                    </div>
                            <button type="button" id="addWordSearchWordBox" class="mt-3 px-5 py-2.5 rounded-xl bg-gradient-to-r from-green-500 to-emerald-600 text-white font-semibold shadow-lg hover:from-green-600 hover:to-emerald-700 transform hover:scale-105 transition-all duration-200 flex items-center gap-2 w-fit">
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                </svg>
                                Add Another Word
                            </button>
                        </div>

                        <button type="submit" class="w-full md:w-auto px-8 py-3.5 rounded-xl bg-gradient-to-r from-pink-500 to-cyan-600 text-white font-bold text-lg shadow-xl hover:from-pink-600 hover:to-cyan-700 transform hover:scale-105 transition-all duration-200 flex items-center justify-center gap-2">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            Save Word Search Game
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Word Clock Arrangement Game Section -->
        <div class="max-w-7xl mx-auto mb-8">
            <div class="bg-gradient-to-br from-pink-50/60 via-white/80 to-cyan-50/90 backdrop-blur-md rounded-2xl shadow-xl p-8 border border-cyan-200/40 transform transition-all duration-300 hover:shadow-2xl">
                <div class="flex items-center gap-4 mb-6 pb-4 border-b-2 border-cyan-200/50">
                    <div class="w-12 h-12 bg-gradient-to-br from-cyan-400 to-teal-500 rounded-xl flex items-center justify-center shadow-lg">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <h3 class="text-2xl font-extrabold text-gray-800">Word Clock Arrangement Game</h3>
                </div>
        @php
            $wordClockArrangementData = null;
            if (isset($wordClockArrangementGame) && $wordClockArrangementGame && $wordClockArrangementGame->game_data) {
                $wordClockArrangementData = is_string($wordClockArrangementGame->game_data) ? json_decode($wordClockArrangementGame->game_data, true) : $wordClockArrangementGame->game_data;
            }
        @endphp
                @if($wordClockArrangementData && isset($wordClockArrangementData['words']) && !empty($wordClockArrangementData['words']))
                    <div id="wordClockArrangementSavedView" class="mb-6 p-6 bg-gradient-to-r from-green-50 to-emerald-50 border-2 border-green-300 rounded-xl shadow-lg">
                        <div class="flex items-center justify-between mb-4">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-green-500 rounded-lg flex items-center justify-center shadow-md">
                                    <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                    </svg>
                                </div>
                                <h4 class="text-xl font-bold text-green-800">Saved Word Clock Arrangement Game</h4>
                            </div>
                            <button type="button" id="editWordClockArrangementBtn" class="px-5 py-2.5 rounded-xl bg-gradient-to-r from-yellow-400 to-orange-500 text-white font-semibold shadow-md hover:from-yellow-500 hover:to-orange-600 transform hover:scale-105 transition-all duration-200 flex items-center gap-2">
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                                Edit
                            </button>
                        </div>
                        <div class="space-y-3 mb-4">
                            <div class="flex items-center gap-2">
                                <span class="font-bold text-gray-700">Word:</span> 
                                <span class="text-green-900 font-semibold text-lg">{{ $wordClockArrangementData['word'] ?? '' }}</span>
                            </div>
                            <div class="flex items-start gap-2">
                                <span class="font-bold text-gray-700">Sentence/Definition:</span> 
                                <span class="text-green-900 font-medium">{{ $wordClockArrangementData['full_sentence'] ?? '' }}</span>
                            </div>
                        </div>
                        <div class="flex flex-wrap gap-3">
                            @foreach($wordClockArrangementData['words'] as $idx => $wordData)
                                <span class="px-4 py-2 bg-green-200 rounded-lg text-green-900 font-semibold shadow-sm border border-green-300">
                                    {{ $wordData['word'] }} ({{ str_pad($wordData['hour'], 2, '0', STR_PAD_LEFT) }}:{{ str_pad($wordData['minute'], 2, '0', STR_PAD_LEFT) }})
                                </span>
                            @endforeach
                        </div>
                    </div>
                @endif
                <div id="wordClockArrangementSection" class="{{ (isset($wordClockArrangementData) && !empty($wordClockArrangementData['words'])) ? 'hidden' : '' }}">
                    <form id="wordClockArrangementForm" method="POST" action="{{ route('teacher.games.word-clock-arrangement.store') }}">
                        @csrf
                        <input type="hidden" name="word_clock_lesson_id" value="{{ $selectedLessonId }}">
                        <input type="hidden" name="class_id" id="word_clock_class_id" value="">
                        
                        <div class="mb-6">
                            <label for="word_clock_word" class="block font-bold text-gray-800 mb-2 text-lg">Word:</label>
                            <input type="text" name="word_clock_word" id="word_clock_word" 
                                   class="w-full bg-pink-50 border-2 border-cyan-200/60 rounded-xl px-4 py-3 text-gray-800 font-medium shadow-md hover:border-cyan-300 hover:bg-pink-100 focus:border-cyan-400 focus:ring-2 focus:ring-cyan-200 focus:bg-pink-50 transition-all duration-300" 
                                   value="{{ $wordClockArrangementData['word'] ?? '' }}" 
                                   placeholder="Enter a word (e.g., التقليد)" required>
                        </div>

                        <div class="mb-6">
                            <label for="word_clock_sentence" class="block font-bold text-gray-800 mb-2 text-lg">Sentence / Definition:</label>
                            <textarea name="word_clock_sentence" id="word_clock_sentence" 
                                      class="w-full bg-pink-50 border-2 border-cyan-200/60 rounded-xl px-4 py-3 text-gray-800 font-medium shadow-md hover:border-cyan-300 hover:bg-pink-100 focus:border-cyan-400 focus:ring-2 focus:ring-cyan-200 focus:bg-pink-50 transition-all duration-300" 
                                      rows="4" 
                                      placeholder="Enter the full sentence or definition" required>{{ $wordClockArrangementData['full_sentence'] ?? '' }}</textarea>
                            <p class="text-sm text-gray-600 mt-2 flex items-center gap-1">
                                <svg class="h-4 w-4 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                This sentence will be automatically split into words. Each word will need a clock time assigned.
                            </p>
                            <button type="button" id="splitSentenceBtn" class="mt-3 px-5 py-2.5 rounded-xl bg-gradient-to-r from-cyan-500 to-teal-600 text-white font-semibold shadow-lg hover:from-cyan-600 hover:to-teal-700 transform hover:scale-105 transition-all duration-200 flex items-center gap-2 w-fit">
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                </svg>
                                Split Sentence into Words
                            </button>
                        </div>

                        <div class="mb-6">
                            <label class="block font-bold text-gray-800 mb-3 text-lg flex items-center gap-2">
                                <svg class="h-5 w-5 text-cyan-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                Words with Clock Times:
                            </label>
                            <div id="wordClockArrangementWordsBoxes" class="space-y-4">
                                @if(isset($wordClockArrangementData) && !empty($wordClockArrangementData['words']))
                                    @foreach($wordClockArrangementData['words'] as $wordData)
                                        <div class="word-clock-arrangement-word-box flex flex-col md:flex-row items-start md:items-center gap-4 p-5 border-2 border-cyan-200 rounded-xl bg-gradient-to-r from-pink-50/60 to-cyan-50/60 hover:border-cyan-300 transition-colors">
                                            <div class="flex-1 w-full md:w-auto">
                                                <input type="text" name="word_clock_words[][word]" 
                                                       class="w-full bg-pink-50 border-2 border-pink-200 rounded-lg px-4 py-2.5 text-gray-800 font-medium hover:bg-pink-100 focus:border-cyan-400 focus:ring-2 focus:ring-cyan-200 focus:bg-pink-50 transition-all" 
                                                       value="{{ $wordData['word'] }}" 
                                                       placeholder="Word" required>
                                            </div>
                                            <div class="flex items-center gap-3 flex-wrap">
                                                <div class="flex items-center gap-2">
                                                    <label class="text-sm font-semibold text-gray-700">Hour:</label>
                                                    <input type="number" name="word_clock_words[][hour]" 
                                                           class="w-20 bg-pink-50 border-2 border-pink-200 rounded-lg px-3 py-2 text-gray-800 font-medium hour-input hover:bg-pink-100 focus:border-cyan-400 focus:ring-2 focus:ring-cyan-200 focus:bg-pink-50 transition-all" 
                                                           value="{{ $wordData['hour'] }}" 
                                                           min="0" max="11" placeholder="0-11" required>
                                                </div>
                                                <div class="flex items-center gap-2">
                                                    <label class="text-sm font-semibold text-gray-700">Minute:</label>
                                                    <input type="number" name="word_clock_words[][minute]" 
                                                           class="w-20 bg-pink-50 border-2 border-pink-200 rounded-lg px-3 py-2 text-gray-800 font-medium minute-input hover:bg-pink-100 focus:border-cyan-400 focus:ring-2 focus:ring-cyan-200 focus:bg-pink-50 transition-all" 
                                                           value="{{ $wordData['minute'] }}" 
                                                           min="0" max="59" placeholder="0-59" required>
                                                </div>
                                                <div class="clock-preview ml-2 p-2 bg-pink-50 rounded-lg border-2 border-pink-200 shadow-sm" style="width: 70px; height: 70px;">
                                            <svg width="60" height="60" class="clock-svg-preview">
                                                <circle cx="30" cy="30" r="27" fill="white" stroke="#333" stroke-width="2"/>
                                                <!-- Clock numbers (simplified) -->
                                                @for($i = 1; $i <= 12; $i++)
                                                    @php
                                                        $angle = ($i - 3) * 30 * M_PI / 180;
                                                        $x = 30 + 20 * cos($angle);
                                                        $y = 30 + 20 * sin($angle);
                                                    @endphp
                                                    <text x="{{ $x }}" y="{{ $y + 3 }}" text-anchor="middle" font-size="7" fill="#333">{{ $i }}</text>
                                                @endfor
                                                <!-- Hour hand -->
                                                @php
                                                    $hourAngle = (($wordData['hour'] % 12) * 30 + $wordData['minute'] * 0.5 - 90) * M_PI / 180;
                                                    $hourX = 30 + 15 * cos($hourAngle);
                                                    $hourY = 30 + 15 * sin($hourAngle);
                                                @endphp
                                                <line x1="30" y1="30" x2="{{ $hourX }}" y2="{{ $hourY }}" stroke="#333" stroke-width="2" stroke-linecap="round"/>
                                                <!-- Minute hand -->
                                                @php
                                                    $minuteAngle = ($wordData['minute'] * 6 - 90) * M_PI / 180;
                                                    $minuteX = 30 + 22 * cos($minuteAngle);
                                                    $minuteY = 30 + 22 * sin($minuteAngle);
                                                @endphp
                                                <line x1="30" y1="30" x2="{{ $minuteX }}" y2="{{ $minuteY }}" stroke="#333" stroke-width="1.5" stroke-linecap="round"/>
                                                <!-- Center dot -->
                                                <circle cx="30" cy="30" r="2" fill="#333"/>
                                            </svg>
                                        </div>
                                                <button type="button" class="removeWordClockArrangementWordBox px-4 py-2.5 bg-gradient-to-r from-red-400 to-rose-500 text-white rounded-lg font-bold shadow-md hover:from-red-500 hover:to-rose-600 transform hover:scale-105 transition-all duration-200">&times;</button>
                                            </div>
                                        </div>
                            @endforeach
                        @endif
                    </div>
                            <button type="button" id="addWordClockArrangementWordBox" class="mt-3 px-5 py-2.5 rounded-xl bg-gradient-to-r from-green-500 to-emerald-600 text-white font-semibold shadow-lg hover:from-green-600 hover:to-emerald-700 transform hover:scale-105 transition-all duration-200 flex items-center gap-2 w-fit">
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                </svg>
                                Add Another Word
                            </button>
                        </div>

                        <button type="submit" class="w-full md:w-auto px-8 py-3.5 rounded-xl bg-gradient-to-r from-cyan-500 to-teal-600 text-white font-bold text-lg shadow-xl hover:from-cyan-600 hover:to-teal-700 transform hover:scale-105 transition-all duration-200 flex items-center justify-center gap-2">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            Save Word Clock Arrangement Game
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Matching Pairs Game Section -->
        <div class="max-w-7xl mx-auto mb-8">
            <div class="bg-gradient-to-br from-pink-50/60 via-white/80 to-teal-50/90 backdrop-blur-md rounded-2xl shadow-xl p-8 border border-teal-200/40 transform transition-all duration-300 hover:shadow-2xl">
                <div class="flex items-center gap-4 mb-6 pb-4 border-b-2 border-teal-200/50">
                    <div class="w-12 h-12 bg-gradient-to-br from-pink-400 via-teal-400 to-cyan-500 rounded-xl flex items-center justify-center shadow-lg">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                        </svg>
                    </div>
                    <h3 class="text-2xl font-extrabold text-gray-800">Matching Pairs Game</h3>
                </div>
        @php
            $matchingPairsData = null;
            if (isset($matchingPairsGame) && $matchingPairsGame && $matchingPairsGame->pairs->count() > 0) {
                $matchingPairsData = [
                    'title' => $matchingPairsGame->title ?? '',
                    'pairs' => $matchingPairsGame->pairs->map(function($pair) {
                        return [
                            'left_item_text' => $pair->left_item_text,
                            'left_item_image' => $pair->left_item_image ? asset('storage/' . $pair->left_item_image) : null,
                            'right_item_text' => $pair->right_item_text,
                            'right_item_image' => $pair->right_item_image ? asset('storage/' . $pair->right_item_image) : null,
                        ];
                    })->toArray()
                ];
            }
        @endphp
                @if($matchingPairsData && !empty($matchingPairsData['pairs']))
                    <div id="matchingPairsSavedView" class="mb-6 p-6 bg-gradient-to-r from-green-50 to-emerald-50 border-2 border-green-300 rounded-xl shadow-lg">
                        <div class="flex justify-between items-center mb-4">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-green-500 rounded-lg flex items-center justify-center shadow-md">
                                    <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                    </svg>
                                </div>
                                <h4 class="text-xl font-bold text-green-800">Saved Matching Pairs Game</h4>
                            </div>
                            <button type="button" id="editMatchingPairsBtn" class="px-5 py-2.5 rounded-xl bg-gradient-to-r from-yellow-400 to-orange-500 text-white font-semibold shadow-md hover:from-yellow-500 hover:to-orange-600 transform hover:scale-105 transition-all duration-200 flex items-center gap-2">
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                                Edit
                            </button>
                        </div>
                @if(!empty($matchingPairsData['title']))
                    <div class="mb-3">
                        <strong class="text-green-800">Title:</strong> 
                        <span class="text-green-900 font-semibold text-lg" dir="rtl">{{ $matchingPairsData['title'] }}</span>
                    </div>
                @endif
                        <div class="grid gap-4">
                            @foreach($matchingPairsData['pairs'] as $index => $pair)
                                <div class="border-2 border-green-200 rounded-xl p-4 bg-white shadow-sm hover:shadow-md transition-shadow">
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 items-center">
                                        <div class="flex items-center gap-3">
                                            <span class="font-bold text-teal-600 text-lg">{{ $index + 1 }}.</span>
                                            @if($pair['left_item_image'])
                                                <img src="{{ $pair['left_item_image'] }}" alt="Left item" class="w-20 h-20 object-cover rounded-lg border-2 border-gray-200 shadow-sm">
                                            @endif
                                            @if($pair['left_item_text'])
                                                <span class="text-gray-800 font-semibold text-lg" dir="rtl">{{ $pair['left_item_text'] }}</span>
                                            @endif
                                        </div>
                                        <div class="flex items-center gap-3">
                                                <svg class="h-6 w-6 text-teal-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                                            </svg>
                                            @if($pair['right_item_image'])
                                                <img src="{{ $pair['right_item_image'] }}" alt="Right item" class="w-20 h-20 object-cover rounded-lg border-2 border-gray-200 shadow-sm">
                                            @endif
                                            @if($pair['right_item_text'])
                                                <span class="text-gray-800 font-semibold text-lg" dir="rtl">{{ $pair['right_item_text'] }}</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
                <div id="matchingPairsSection" class="{{ (isset($matchingPairsData) && !empty($matchingPairsData['pairs'])) ? 'hidden' : '' }}">
                    <form id="matchingPairsForm" method="POST" action="{{ route('teacher.games.matching-pairs.store') }}" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="matching_pairs_lesson_id" value="{{ $selectedLessonId }}">
                        <input type="hidden" name="class_id" id="matching_pairs_class_id" value="">
                        
                        <div class="mb-6">
                            <label for="matching_pairs_title" class="block font-bold text-gray-800 mb-2 text-lg" dir="rtl">عنوان اللعبة / Title (اختياري):</label>
                            <input type="text" 
                                   name="title" 
                                   id="matching_pairs_title" 
                                   class="w-full max-w-md bg-pink-50 border-2 border-teal-200/60 rounded-xl px-4 py-3 text-gray-800 font-medium shadow-md hover:border-teal-300 hover:bg-pink-100 focus:border-teal-400 focus:ring-2 focus:ring-teal-200 focus:bg-pink-50 transition-all duration-300" 
                                   value="{{ $matchingPairsData['title'] ?? '' }}" 
                                   placeholder="أدخل العنوان" 
                                   dir="rtl">
                        </div>
                        
                        <div class="mb-6">
                            <label class="block font-bold text-gray-800 mb-3 text-lg flex items-center gap-2">
                                <svg class="h-5 w-5 text-teal-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                </svg>
                                Matching Pairs:
                            </label>
                            <div id="matchingPairsBoxes" class="space-y-4">
                                @if(isset($matchingPairsData) && !empty($matchingPairsData['pairs']))
                                    @foreach($matchingPairsData['pairs'] as $index => $pair)
                                        <div class="matching-pair-box border-2 border-teal-200 rounded-xl p-5 bg-gradient-to-r from-pink-50/50 to-teal-50/50 hover:border-teal-300 transition-colors">
                                            <div class="flex justify-between items-center mb-4">
                                                <span class="font-bold text-teal-700 text-lg flex items-center gap-2">
                                                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                                    </svg>
                                                    Pair {{ $index + 1 }}
                                                </span>
                                                <button type="button" class="removeMatchingPairBox px-4 py-2 bg-gradient-to-r from-red-400 to-rose-500 text-white rounded-lg font-bold shadow-md hover:from-red-500 hover:to-rose-600 transform hover:scale-105 transition-all duration-200">&times;</button>
                                            </div>
                                            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                                                <div class="space-y-3">
                                                    <label class="block text-sm font-bold text-gray-700">Left Item (Text):</label>
                                                    <input type="text" 
                                                           name="pairs[{{ $index }}][left_item_text]" 
                                                           class="w-full bg-pink-50 border-2 border-pink-200 rounded-lg px-4 py-2.5 text-gray-800 font-medium hover:bg-pink-100 focus:border-teal-400 focus:ring-2 focus:ring-teal-200 focus:bg-pink-50 transition-all" 
                                                           value="{{ $pair['left_item_text'] ?? '' }}" 
                                                           placeholder="Text for left column" 
                                                           dir="rtl">
                                                    <label class="block text-sm font-bold text-gray-700 mt-3">Left Item (Image):</label>
                                                    <input type="file" 
                                                           name="pairs[{{ $index }}][left_item_image]" 
                                                           class="w-full border-2 border-gray-300 rounded-lg px-4 py-2.5 text-gray-800 font-medium focus:border-teal-400 focus:ring-2 focus:ring-teal-200 transition-all file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-teal-50 file:text-teal-700 hover:file:bg-teal-100" 
                                                           accept="image/*">
                                                    @if($pair['left_item_image'])
                                                        <img src="{{ $pair['left_item_image'] }}" alt="Current left image" class="mt-3 w-24 h-24 object-cover rounded-lg border-2 border-gray-200 shadow-sm">
                                                    @endif
                                                </div>
                                                <div class="space-y-3">
                                                    <label class="block text-sm font-bold text-gray-700">Right Item (Text):</label>
                                                    <input type="text" 
                                                           name="pairs[{{ $index }}][right_item_text]" 
                                                           class="w-full bg-pink-50 border-2 border-pink-200 rounded-lg px-4 py-2.5 text-gray-800 font-medium hover:bg-pink-100 focus:border-teal-400 focus:ring-2 focus:ring-teal-200 focus:bg-pink-50 transition-all" 
                                                           value="{{ $pair['right_item_text'] ?? '' }}" 
                                                           placeholder="Text for right column" 
                                                           dir="rtl">
                                                    <label class="block text-sm font-bold text-gray-700 mt-3">Right Item (Image):</label>
                                                    <input type="file" 
                                                           name="pairs[{{ $index }}][right_item_image]" 
                                                           class="w-full border-2 border-gray-300 rounded-lg px-4 py-2.5 text-gray-800 font-medium focus:border-teal-400 focus:ring-2 focus:ring-teal-200 transition-all file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-teal-50 file:text-teal-700 hover:file:bg-teal-100" 
                                                           accept="image/*">
                                                    @if($pair['right_item_image'])
                                                        <img src="{{ $pair['right_item_image'] }}" alt="Current right image" class="mt-3 w-24 h-24 object-cover rounded-lg border-2 border-gray-200 shadow-sm">
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                            @endforeach
                        @endif
                    </div>
                            <button type="button" id="addMatchingPairBox" class="mt-4 px-5 py-2.5 rounded-xl bg-gradient-to-r from-green-500 to-emerald-600 text-white font-semibold shadow-lg hover:from-green-600 hover:to-emerald-700 transform hover:scale-105 transition-all duration-200 flex items-center gap-2 w-fit">
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                </svg>
                                Add Pair
                            </button>
                        </div>

                        <button type="submit" class="w-full md:w-auto px-8 py-3.5 rounded-xl bg-gradient-to-r from-teal-500 to-cyan-600 text-white font-bold text-lg shadow-xl hover:from-teal-600 hover:to-cyan-700 transform hover:scale-105 transition-all duration-200 flex items-center justify-center gap-2">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            Save Matching Pairs Game
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Scrambled Letters Game - Word/Definition Pairs Section -->
        <div class="max-w-7xl mx-auto mb-8">
            <div class="bg-gradient-to-br from-pink-50/60 via-white/80 to-teal-50/90 backdrop-blur-md rounded-2xl shadow-xl p-8 border border-teal-200/40 transform transition-all duration-300 hover:shadow-2xl">
                <div class="flex items-center gap-4 mb-6 pb-4 border-b-2 border-teal-200/50">
                    <div class="w-12 h-12 bg-gradient-to-br from-pink-400 via-teal-400 to-cyan-500 rounded-xl flex items-center justify-center shadow-lg">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                        </svg>
                    </div>
                    <h3 class="text-2xl font-extrabold text-gray-800">Scrambled Letters Game - Word/Definition Pairs</h3>
                </div>
                
                <!-- Show saved pairs for Scrambled Letters -->
                @if(isset($scramblePairs) && $scramblePairs->count() > 0)
                    <div class="mb-6 p-5 bg-gradient-to-r from-pink-50 to-teal-50 to-cyan-50 border-2 border-teal-200 rounded-xl">
                        <h5 class="font-bold text-gray-800 mb-4 text-lg flex items-center gap-2">
                            <svg class="h-5 w-5 text-teal-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            Saved Pairs:
                        </h5>
                        <div class="grid gap-3 md:grid-cols-2">
                            @foreach ($scramblePairs as $pair)
                                <div class="pair-row border-2 border-teal-300 rounded-xl p-4 flex flex-col gap-2 relative bg-gradient-to-r from-pink-50 to-white shadow-sm hover:shadow-md transition-shadow" data-id="{{ $pair->id }}">
                                    <div class="font-bold text-teal-800 text-lg word-text" dir="rtl">{{ $pair->word }}</div>
                                    <div class="text-gray-700 def-text">{{ $pair->definition }}</div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @else
                    <div class="mb-6 p-4 bg-gray-50 border-2 border-gray-200 rounded-xl text-gray-600 flex items-center gap-2">
                        <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        No word pairs found for Scrambled Letters game.
                    </div>
                @endif
                
                <!-- Add new pairs form for Scrambled Letters -->
                <form method="POST" action="{{ route('teacher.games.store') }}">
                    @csrf
                    <input type="hidden" name="lesson_id" value="{{ $selectedLessonId }}">
                    <input type="hidden" name="game_type" value="scramble">
                    <input type="hidden" name="class_id" id="scramble_class_id" value="">
                    
                    <div class="mb-4">
                        <label class="block font-bold text-gray-800 mb-3 text-lg">Add New Word/Definition Pair:</label>
                            <div class="flex flex-col md:flex-row gap-3">
                            <input type="text" name="words[]" class="flex-1 bg-pink-50 border-2 border-pink-200 rounded-xl px-4 py-3 text-gray-800 font-medium shadow-md hover:bg-pink-100 focus:border-teal-400 focus:ring-2 focus:ring-teal-200 focus:bg-pink-50 transition-all" placeholder="Word" required>
                            <input type="text" name="definitions[]" class="flex-2 bg-pink-50 border-2 border-pink-200 rounded-xl px-4 py-3 text-gray-800 font-medium shadow-md hover:bg-pink-100 focus:border-teal-400 focus:ring-2 focus:ring-teal-200 focus:bg-pink-50 transition-all" placeholder="Definition" required>
                            <button type="button" class="addPairBtn px-6 py-3 bg-gradient-to-r from-teal-400 to-cyan-500 text-white font-bold rounded-xl shadow-lg hover:from-teal-500 hover:to-cyan-600 transform hover:scale-105 transition-all duration-200 flex items-center justify-center gap-2">
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                </svg>
                                Add
                            </button>
                        </div>
                    </div>
                    <div class="pairs-list space-y-3 mb-6"></div>
                    <button type="submit" class="w-full md:w-auto px-8 py-3.5 rounded-xl bg-gradient-to-r from-teal-500 to-cyan-600 text-white font-bold text-lg shadow-xl hover:from-teal-600 hover:to-cyan-700 transform hover:scale-105 transition-all duration-200 flex items-center justify-center gap-2">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        Save Scrambled Letters Pairs
                    </button>
                </form>
            </div>
        </div>
        @endif
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Get the class selector dropdown
    const classSelector = document.getElementById('class_id');
    
    // Function to update all class_id hidden fields
    function updateClassIdFields(classId) {
        document.getElementById('word_search_class_id').value = classId || '';
        document.getElementById('word_clock_class_id').value = classId || '';
        document.getElementById('matching_pairs_class_id').value = classId || '';
        document.getElementById('scramble_class_id').value = classId || '';
    }
    
    // Update class_id fields when class selector changes
    if (classSelector) {
        classSelector.addEventListener('change', function() {
            updateClassIdFields(this.value);
        });
        // Initialize on page load
        updateClassIdFields(classSelector.value);
    }
    
    // Update class_id fields before form submissions
    const wordSearchForm = document.getElementById('wordSearchForm');
    if (wordSearchForm) {
        wordSearchForm.addEventListener('submit', function() {
            updateClassIdFields(classSelector ? classSelector.value : '');
        });
    }
    
    const wordClockArrangementForm = document.getElementById('wordClockArrangementForm');
    if (wordClockArrangementForm) {
        wordClockArrangementForm.addEventListener('submit', function() {
            updateClassIdFields(classSelector ? classSelector.value : '');
        });
    }
    
    const matchingPairsForm = document.getElementById('matchingPairsForm');
    if (matchingPairsForm) {
        matchingPairsForm.addEventListener('submit', function() {
            updateClassIdFields(classSelector ? classSelector.value : '');
        });
    }
    
    const scrambleForm = document.querySelector('form[action*="teacher.games.store"]');
    if (scrambleForm) {
        scrambleForm.addEventListener('submit', function() {
            updateClassIdFields(classSelector ? classSelector.value : '');
        });
    }
    
    // Word Search Game functionality
    const editWordSearchBtn = document.getElementById('editWordSearchBtn');
    const viewWordSearchBtn = document.getElementById('viewWordSearchBtn');
    const wordSearchSavedView = document.getElementById('wordSearchSavedView');
    const wordSearchSection = document.getElementById('wordSearchSection');
    
    if (editWordSearchBtn) {
        editWordSearchBtn.addEventListener('click', function() {
            if (wordSearchSavedView) wordSearchSavedView.style.display = 'none';
            if (wordSearchSection) wordSearchSection.classList.remove('hidden');
        });
    }
    
    // Word Search View Modal functionality
    const wordSearchModal = document.getElementById('wordSearchViewModal');
    const wordSearchModalContent = document.getElementById('wordSearchModalContent');
    const closeWordSearchModal = document.getElementById('closeWordSearchModal');
    
    // Word Search Game Data (passed from PHP)
    @php
        $wordSearchGameForJS = null;
        if (isset($wordSearchGame) && $wordSearchGame) {
            $words = $wordSearchGame->words;
            if (!is_array($words) && is_string($words)) {
                $words = json_decode($words, true);
            }
            if (!is_array($words)) {
                $words = [];
            }
            
            $wordSearchGameForJS = [
                'title' => $wordSearchGame->title ?? '',
                'words' => $words,
                'grid_size' => $wordSearchGame->grid_size ?? 10,
                'grid_data' => $wordSearchGame->grid_data ?? null
            ];
        }
    @endphp
    const wordSearchGameData = @json($wordSearchGameForJS ?? null);
    
    console.log('Word Search View Button:', viewWordSearchBtn);
    console.log('Word Search Game Data:', wordSearchGameData);
    console.log('Word Search Modal:', wordSearchModal);
    
    if (viewWordSearchBtn) {
        viewWordSearchBtn.addEventListener('click', function(e) {
            e.preventDefault();
            console.log('View button clicked');
            
            if (!wordSearchGameData) {
                console.error('No word search game data available');
                alert('Word search game data not available');
                return;
            }
            
            if (!wordSearchModal) {
                console.error('Modal not found');
                alert('Modal element not found');
                return;
            }
            
            try {
                let gridData = wordSearchGameData.grid_data;
                console.log('Raw grid_data:', gridData);
                console.log('Grid data type:', typeof gridData);
                
                // Parse grid_data if it's a string
                if (typeof gridData === 'string') {
                    try {
                        gridData = JSON.parse(gridData);
                    } catch (e) {
                        console.error('Error parsing grid_data:', e);
                        gridData = null;
                    }
                }
                
                // Extract the actual grid array from grid_data structure
                let grid = null;
                console.log('Processing gridData:', gridData);
                
                if (gridData) {
                    // Try different possible structures
                    if (gridData.grid && Array.isArray(gridData.grid)) {
                        grid = gridData.grid;
                        console.log('Found grid in gridData.grid');
                    } else if (Array.isArray(gridData)) {
                        grid = gridData;
                        console.log('gridData is already an array');
                    } else if (gridData.grid && typeof gridData.grid === 'string') {
                        // If grid is a JSON string
                        try {
                            grid = JSON.parse(gridData.grid);
                            console.log('Parsed grid from string');
                        } catch (e) {
                            console.error('Error parsing grid string:', e);
                        }
                    }
                }
                
                console.log('Final grid:', grid);
                console.log('Grid is array:', Array.isArray(grid));
                if (grid) {
                    console.log('Grid length:', grid.length);
                }
                
                // Build modal content
                let modalHTML = `
                    <div class="bg-gradient-to-br from-pink-50 to-cyan-50 rounded-xl p-6">
                        ${wordSearchGameData.title ? `<h4 class="text-xl font-bold text-purple-600 mb-4" dir="rtl">${wordSearchGameData.title}</h4>` : ''}
                        <div class="flex flex-col lg:flex-row gap-8">
                            <div class="flex-1">
                                <h5 class="text-lg font-bold mb-3">Word Search Grid</h5>
                                <div class="inline-block border-2 border-gray-300 bg-white p-2 rounded-lg" style="direction: ltr;">
                `;
                
                // Render the grid
                if (grid && Array.isArray(grid) && grid.length > 0) {
                    grid.forEach((row) => {
                        modalHTML += '<div class="flex">';
                        if (Array.isArray(row)) {
                            row.forEach((cell) => {
                                // Handle both string and object cell formats
                                let cellValue = '';
                                if (typeof cell === 'string') {
                                    cellValue = cell.trim();
                                } else if (cell && typeof cell === 'object' && cell.letter) {
                                    cellValue = String(cell.letter).trim();
                                } else if (cell !== null && cell !== undefined) {
                                    cellValue = String(cell).trim();
                                }
                                // Display the cell value, or a dot if empty to show grid structure
                                const displayValue = cellValue || '·';
                                modalHTML += `<div class="w-10 h-10 border border-gray-200 flex items-center justify-center text-lg font-semibold bg-white text-gray-800" dir="rtl" style="min-width: 2.5rem; min-height: 2.5rem; line-height: 1; font-size: 1.1rem;">${displayValue}</div>`;
                            });
                        }
                        modalHTML += '</div>';
                    });
                } else {
                    // Generate placeholder grid if no grid data
                    const gridSize = wordSearchGameData.grid_size || 10;
                    for (let row = 0; row < gridSize; row++) {
                        modalHTML += '<div class="flex">';
                        for (let col = 0; col < gridSize; col++) {
                            modalHTML += `<div class="w-8 h-8 border border-gray-200 flex items-center justify-center text-sm font-semibold bg-gray-50 text-gray-400" style="min-width: 2rem; min-height: 2rem;">?</div>`;
                        }
                        modalHTML += '</div>';
                    }
                }
                
                modalHTML += `
                                </div>
                            </div>
                            <div class="lg:w-64">
                                <h5 class="text-lg font-bold mb-3" dir="rtl">Words to Find:</h5>
                                <div class="space-y-2" dir="rtl">
                `;
                
                // Render words list
                if (wordSearchGameData.words && wordSearchGameData.words.length > 0) {
                    wordSearchGameData.words.forEach(word => {
                        modalHTML += `
                            <div class="p-3 border-2 border-gray-300 rounded-lg bg-white">
                                <span class="font-semibold text-lg" dir="rtl">${word}</span>
                            </div>
                        `;
                    });
                } else {
                    modalHTML += `<p class="text-gray-500">No words available</p>`;
                }
                
                modalHTML += `
                                </div>
                            </div>
                        </div>
                    </div>
                `;
                
                // Set modal content and show modal
                console.log('Modal HTML length:', modalHTML.length);
                
                if (wordSearchModalContent) {
                    wordSearchModalContent.innerHTML = modalHTML;
                    console.log('Modal content set');
                } else {
                    console.error('wordSearchModalContent not found');
                }
                
                if (wordSearchModal) {
                    wordSearchModal.classList.remove('hidden');
                    wordSearchModal.style.display = 'flex';
                    document.body.style.overflow = 'hidden'; // Prevent background scrolling
                    console.log('Modal shown');
                } else {
                    console.error('wordSearchModal not found');
                }
            } catch (error) {
                console.error('Error displaying word search:', error);
                if (wordSearchModalContent) {
                    wordSearchModalContent.innerHTML = `
                        <div class="bg-red-50 border-2 border-red-300 rounded-xl p-6">
                            <p class="text-red-600 font-bold mb-2">Error displaying word search game</p>
                            <p class="text-sm text-red-500">${error.message}</p>
                        </div>
                    `;
                }
                if (wordSearchModal) {
                    wordSearchModal.classList.remove('hidden');
                }
            }
        });
    }
    
    // Close modal functionality
    function closeWordSearchModalFunc() {
        if (wordSearchModal) {
            wordSearchModal.classList.add('hidden');
            wordSearchModal.style.display = 'none';
            document.body.style.overflow = ''; // Restore scrolling
        }
    }
    
    if (closeWordSearchModal) {
        closeWordSearchModal.addEventListener('click', function(e) {
            e.preventDefault();
            closeWordSearchModalFunc();
        });
    }
    
    // Close modal when clicking outside
    if (wordSearchModal) {
        wordSearchModal.addEventListener('click', function(e) {
            if (e.target === wordSearchModal) {
                closeWordSearchModalFunc();
            }
        });
    }
    
    // Close modal on Escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && wordSearchModal && !wordSearchModal.classList.contains('hidden')) {
            closeWordSearchModalFunc();
        }
    });

    // Add/Remove Word Search Word Boxes
    const addWordSearchWordBox = document.getElementById('addWordSearchWordBox');
    const wordSearchWordsBoxes = document.getElementById('wordSearchWordsBoxes');
    
    if (addWordSearchWordBox && wordSearchWordsBoxes) {
        addWordSearchWordBox.addEventListener('click', function() {
            const box = document.createElement('div');
            box.className = 'word-search-word-box flex items-center gap-3 mb-3 p-3 bg-gray-50 rounded-xl border-2 border-gray-200 hover:border-pink-300 transition-colors';
            box.innerHTML = `
                <input type="text" name="word_search_words[]" class="flex-1 border-2 border-gray-300 rounded-lg px-4 py-2.5 text-gray-800 font-medium focus:border-pink-400 focus:ring-2 focus:ring-pink-200 transition-all" placeholder="Enter word" required>
                <button type="button" class="removeWordSearchWordBox px-4 py-2.5 bg-gradient-to-r from-red-400 to-rose-500 text-white rounded-lg font-bold shadow-md hover:from-red-500 hover:to-rose-600 transform hover:scale-105 transition-all duration-200">&times;</button>
            `;
            wordSearchWordsBoxes.appendChild(box);
        });

        // Remove word box logic
        wordSearchWordsBoxes.addEventListener('click', function(e) {
            if (e.target.classList.contains('removeWordSearchWordBox')) {
                const box = e.target.closest('.word-search-word-box');
                if (box && wordSearchWordsBoxes.querySelectorAll('.word-search-word-box').length > 1) {
                    box.remove();
                } else if (box) {
                    alert('You must have at least one word.');
                }
            }
        });
    }

    // Filter out empty word boxes before form submission for Word Search
    const wordSearchForm = document.getElementById('wordSearchForm');
    if (wordSearchForm) {
        wordSearchForm.addEventListener('submit', function(e) {
            const wordBoxes = document.querySelectorAll('.word-search-word-box');
            const words = [];
            
            wordBoxes.forEach(function(box) {
                const wordInput = box.querySelector('input[name*="word_search_words"]');
                if (wordInput && wordInput.value.trim()) {
                    words.push(wordInput.value.trim());
                }
            });
            
            if (words.length === 0) {
                e.preventDefault();
                alert('Please add at least one word.');
                return false;
            }
        });
    }

    // Word Clock Arrangement Game functionality
    const editWordClockArrangementBtn = document.getElementById('editWordClockArrangementBtn');
    const wordClockArrangementSavedView = document.getElementById('wordClockArrangementSavedView');
    const wordClockArrangementSection = document.getElementById('wordClockArrangementSection');
    
    if (editWordClockArrangementBtn) {
        editWordClockArrangementBtn.addEventListener('click', function() {
            if (wordClockArrangementSavedView) wordClockArrangementSavedView.style.display = 'none';
            if (wordClockArrangementSection) wordClockArrangementSection.classList.remove('hidden');
        });
    }

    // Filter out empty word boxes before form submission
    const wordClockArrangementForm = document.getElementById('wordClockArrangementForm');
    if (wordClockArrangementForm) {
        wordClockArrangementForm.addEventListener('submit', function(e) {
            const wordBoxes = document.querySelectorAll('.word-clock-arrangement-word-box');
            let hasEmptyBoxes = false;
            
            wordBoxes.forEach(function(box) {
                const wordInput = box.querySelector('input[name*="[word]"]');
                const hourInput = box.querySelector('input[name*="[hour]"]');
                const minuteInput = box.querySelector('input[name*="[minute]"]');
                
                // Check if all fields are empty
                if ((!wordInput || !wordInput.value.trim()) && 
                    (!hourInput || !hourInput.value) && 
                    (!minuteInput || !minuteInput.value)) {
                    // Remove completely empty boxes
                    box.remove();
                } else {
                    // Check if any field is missing
                    if (!wordInput || !wordInput.value.trim()) {
                        hasEmptyBoxes = true;
                    }
                    if (!hourInput || !hourInput.value) {
                        hasEmptyBoxes = true;
                    }
                    if (!minuteInput || !minuteInput.value) {
                        hasEmptyBoxes = true;
                    }
                }
            });
            
            if (hasEmptyBoxes) {
                e.preventDefault();
                alert('Please fill in all fields (word, hour, and minute) for each word, or remove empty word boxes.');
                return false;
            }
            
            // Remove the 'required' attribute from removed boxes and re-index
            const remainingBoxes = document.querySelectorAll('.word-clock-arrangement-word-box');
            remainingBoxes.forEach(function(box, index) {
                const wordInput = box.querySelector('input[name*="[word]"]');
                const hourInput = box.querySelector('input[name*="[hour]"]');
                const minuteInput = box.querySelector('input[name*="[minute]"]');
                
                if (wordInput) {
                    wordInput.name = `word_clock_words[${index}][word]`;
                }
                if (hourInput) {
                    hourInput.name = `word_clock_words[${index}][hour]`;
                }
                if (minuteInput) {
                    minuteInput.name = `word_clock_words[${index}][minute]`;
                }
            });
        });
    }

    // Split sentence into words
    const splitSentenceBtn = document.getElementById('splitSentenceBtn');
    const wordClockSentence = document.getElementById('word_clock_sentence');
    const wordClockArrangementWordsBoxes = document.getElementById('wordClockArrangementWordsBoxes');
    
    if (splitSentenceBtn && wordClockSentence) {
        splitSentenceBtn.addEventListener('click', function() {
            const sentence = wordClockSentence.value.trim();
            if (!sentence) {
                alert('Please enter a sentence first.');
                return;
            }
            
            // Split by spaces and filter empty strings
            const words = sentence.split(/\s+/).filter(w => w.trim() !== '');
            
            if (words.length === 0) {
                alert('No words found in the sentence.');
                return;
            }
            
            // Clear existing word boxes (optional - you might want to ask for confirmation)
            wordClockArrangementWordsBoxes.innerHTML = '';
            
            // Create word boxes for each word
            words.forEach((word, index) => {
                addWordClockArrangementWordBox(word);
            });
        });
    }

    // Function to create clock SVG
    function createClockSVG(hour, minute, size = 70) {
        const center = size / 2;
        const radius = size / 2 - 3;
        const hourHandLength = radius * 0.5;
        const minuteHandLength = radius * 0.75;
        
        // Calculate angles
        const hourAngle = ((hour % 12) * 30 + minute * 0.5 - 90) * Math.PI / 180;
        const minuteAngle = (minute * 6 - 90) * Math.PI / 180;
        
        // Calculate hand positions
        const hourX = center + hourHandLength * Math.cos(hourAngle);
        const hourY = center + hourHandLength * Math.sin(hourAngle);
        const minuteX = center + minuteHandLength * Math.cos(minuteAngle);
        const minuteY = center + minuteHandLength * Math.sin(minuteAngle);
        
        let svg = `<svg width="${size}" height="${size}" class="clock-svg-preview">
            <circle cx="${center}" cy="${center}" r="${radius - 2}" fill="white" stroke="#333" stroke-width="2"/>`;
        
        // Add numbers
        for (let i = 1; i <= 12; i++) {
            const angle = ((i - 3) * 30) * Math.PI / 180;
            const x = center + (radius - 8) * Math.cos(angle);
            const y = center + (radius - 8) * Math.sin(angle);
            const fontSize = size <= 60 ? 7 : 10;
            svg += `<text x="${x}" y="${y + (size <= 60 ? 3 : 5)}" text-anchor="middle" font-size="${fontSize}" fill="#333">${i}</text>`;
        }
        
        svg += `<line x1="${center}" y1="${center}" x2="${hourX}" y2="${hourY}" stroke="#333" stroke-width="${size <= 60 ? 2 : 3}" stroke-linecap="round"/>
            <line x1="${center}" y1="${center}" x2="${minuteX}" y2="${minuteY}" stroke="#333" stroke-width="${size <= 60 ? 1.5 : 2}" stroke-linecap="round"/>
            <circle cx="${center}" cy="${center}" r="${size <= 60 ? 2 : 3}" fill="#333"/>
        </svg>`;
        
        return svg;
    }

    // Function to update clock preview
    function updateClockPreview(container, hour, minute) {
        const clockPreview = container.querySelector('.clock-preview');
        if (clockPreview) {
            const size = clockPreview.offsetWidth || 70;
            clockPreview.innerHTML = createClockSVG(parseInt(hour) || 0, parseInt(minute) || 0, size);
        }
    }

    // Add word box
    function addWordClockArrangementWordBox(wordValue = '') {
        const box = document.createElement('div');
        box.className = 'word-clock-arrangement-word-box flex flex-col md:flex-row items-start md:items-center gap-4 p-5 border-2 border-cyan-200 rounded-xl bg-gradient-to-r from-pink-50/60 to-cyan-50/60 hover:border-cyan-300 transition-colors';
        box.innerHTML = `
            <div class="flex-1 w-full md:w-auto">
                <input type="text" name="word_clock_words[][word]" 
                       class="w-full bg-pink-50 border-2 border-pink-200 rounded-lg px-4 py-2.5 text-gray-800 font-medium hover:bg-pink-100 focus:border-cyan-400 focus:ring-2 focus:ring-cyan-200 focus:bg-pink-50 transition-all" 
                       value="${wordValue}" 
                       placeholder="Word" required>
            </div>
            <div class="flex items-center gap-3 flex-wrap">
                <div class="flex items-center gap-2">
                    <label class="text-sm font-semibold text-gray-700">Hour:</label>
                    <input type="number" name="word_clock_words[][hour]" 
                           class="w-20 bg-pink-50 border-2 border-pink-200 rounded-lg px-3 py-2 text-gray-800 font-medium hour-input hover:bg-pink-100 focus:border-cyan-400 focus:ring-2 focus:ring-cyan-200 focus:bg-pink-50 transition-all" 
                           value="0" 
                           min="0" max="11" placeholder="0-11" required>
                </div>
                <div class="flex items-center gap-2">
                    <label class="text-sm font-semibold text-gray-700">Minute:</label>
                    <input type="number" name="word_clock_words[][minute]" 
                           class="w-20 bg-pink-50 border-2 border-pink-200 rounded-lg px-3 py-2 text-gray-800 font-medium minute-input hover:bg-pink-100 focus:border-cyan-400 focus:ring-2 focus:ring-cyan-200 focus:bg-pink-50 transition-all" 
                           value="0" 
                           min="0" max="59" placeholder="0-59" required>
                </div>
                <div class="clock-preview ml-2 p-2 bg-pink-50 rounded-lg border-2 border-pink-200 shadow-sm" style="width: 70px; height: 70px;">
                    ${createClockSVG(0, 0, 70)}
                </div>
                <button type="button" class="removeWordClockArrangementWordBox px-4 py-2.5 bg-gradient-to-r from-red-400 to-rose-500 text-white rounded-lg font-bold shadow-md hover:from-red-500 hover:to-rose-600 transform hover:scale-105 transition-all duration-200">&times;</button>
            </div>
        `;
        wordClockArrangementWordsBoxes.appendChild(box);
        
        // Add event listeners for clock preview updates
        const hourInput = box.querySelector('.hour-input');
        const minuteInput = box.querySelector('.minute-input');
        
        hourInput.addEventListener('input', function() {
            updateClockPreview(box, hourInput.value, minuteInput.value);
        });
        
        minuteInput.addEventListener('input', function() {
            updateClockPreview(box, hourInput.value, minuteInput.value);
        });
    }

    // Add word box button
    const addWordClockArrangementWordBoxBtn = document.getElementById('addWordClockArrangementWordBox');
    if (addWordClockArrangementWordBoxBtn && wordClockArrangementWordsBoxes) {
        addWordClockArrangementWordBoxBtn.addEventListener('click', function() {
            addWordClockArrangementWordBox();
        });

        // Remove word box logic
        wordClockArrangementWordsBoxes.addEventListener('click', function(e) {
            if (e.target.classList.contains('removeWordClockArrangementWordBox')) {
                const box = e.target.closest('.word-clock-arrangement-word-box');
                if (box) box.remove();
            }
        });
    }

        // Update existing clock previews on page load
    document.querySelectorAll('.word-clock-arrangement-word-box').forEach(function(box) {
        const hourInput = box.querySelector('input[name*="[hour]"]');
        const minuteInput = box.querySelector('input[name*="[minute]"]');
        if (hourInput && minuteInput) {
            hourInput.addEventListener('input', function() {
                updateClockPreview(box, hourInput.value, minuteInput.value);
            });
            minuteInput.addEventListener('input', function() {
                updateClockPreview(box, hourInput.value, minuteInput.value);
            });
            // Initial update
            updateClockPreview(box, hourInput.value, minuteInput.value);
        }
    });

    // Matching Pairs Game functionality
    const editMatchingPairsBtn = document.getElementById('editMatchingPairsBtn');
    const matchingPairsSavedView = document.getElementById('matchingPairsSavedView');
    const matchingPairsSection = document.getElementById('matchingPairsSection');
    
    if (editMatchingPairsBtn) {
        editMatchingPairsBtn.addEventListener('click', function() {
            if (matchingPairsSavedView) matchingPairsSavedView.style.display = 'none';
            if (matchingPairsSection) matchingPairsSection.classList.remove('hidden');
        });
    }

    // Add/Remove Matching Pair Boxes
    const addMatchingPairBox = document.getElementById('addMatchingPairBox');
    const matchingPairsBoxes = document.getElementById('matchingPairsBoxes');
    
    if (addMatchingPairBox && matchingPairsBoxes) {
        addMatchingPairBox.addEventListener('click', function() {
            const pairIndex = matchingPairsBoxes.querySelectorAll('.matching-pair-box').length;
            const box = document.createElement('div');
            box.className = 'matching-pair-box border-2 border-teal-200 rounded-xl p-5 bg-gradient-to-r from-pink-50/50 to-teal-50/50 hover:border-teal-300 transition-colors';
            box.innerHTML = `
                <div class="flex justify-between items-center mb-4">
                    <span class="font-bold text-teal-700 text-lg flex items-center gap-2">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg>
                        Pair ${pairIndex + 1}
                    </span>
                    <button type="button" class="removeMatchingPairBox px-4 py-2 bg-gradient-to-r from-red-400 to-rose-500 text-white rounded-lg font-bold shadow-md hover:from-red-500 hover:to-rose-600 transform hover:scale-105 transition-all duration-200">&times;</button>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div class="space-y-3">
                        <label class="block text-sm font-bold text-gray-700">Left Item (Text):</label>
                        <input type="text" 
                               name="pairs[${pairIndex}][left_item_text]" 
                               class="w-full bg-pink-50 border-2 border-pink-200 rounded-lg px-4 py-2.5 text-gray-800 font-medium hover:bg-pink-100 focus:border-teal-400 focus:ring-2 focus:ring-teal-200 focus:bg-pink-50 transition-all" 
                               placeholder="Text for left column" 
                               dir="rtl">
                        <label class="block text-sm font-bold text-gray-700 mt-3">Left Item (Image):</label>
                        <input type="file" 
                               name="pairs[${pairIndex}][left_item_image]" 
                               class="w-full border-2 border-gray-300 rounded-lg px-4 py-2.5 text-gray-800 font-medium focus:border-teal-400 focus:ring-2 focus:ring-teal-200 transition-all file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-teal-50 file:text-teal-700 hover:file:bg-teal-100" 
                               accept="image/*">
                    </div>
                    <div class="space-y-3">
                        <label class="block text-sm font-bold text-gray-700">Right Item (Text):</label>
                        <input type="text" 
                               name="pairs[${pairIndex}][right_item_text]" 
                               class="w-full bg-pink-50 border-2 border-pink-200 rounded-lg px-4 py-2.5 text-gray-800 font-medium hover:bg-pink-100 focus:border-teal-400 focus:ring-2 focus:ring-teal-200 focus:bg-pink-50 transition-all" 
                               placeholder="Text for right column" 
                               dir="rtl">
                        <label class="block text-sm font-bold text-gray-700 mt-3">Right Item (Image):</label>
                        <input type="file" 
                               name="pairs[${pairIndex}][right_item_image]" 
                               class="w-full border-2 border-gray-300 rounded-lg px-4 py-2.5 text-gray-800 font-medium focus:border-teal-400 focus:ring-2 focus:ring-teal-200 transition-all file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-teal-50 file:text-teal-700 hover:file:bg-teal-100" 
                               accept="image/*">
                    </div>
                </div>
            `;
            matchingPairsBoxes.appendChild(box);
        });

        // Remove matching pair box logic
        matchingPairsBoxes.addEventListener('click', function(e) {
            if (e.target.classList.contains('removeMatchingPairBox')) {
                const box = e.target.closest('.matching-pair-box');
                if (box) box.remove();
                // Re-index remaining pairs
                const remainingBoxes = matchingPairsBoxes.querySelectorAll('.matching-pair-box');
                remainingBoxes.forEach(function(box, index) {
                    box.querySelector('span.font-semibold').textContent = `Pair ${index + 1}`;
                    const inputs = box.querySelectorAll('input[type="text"], input[type="file"]');
                    inputs.forEach(function(input) {
                        const name = input.name;
                        const newName = name.replace(/pairs\[\d+\]/, `pairs[${index}]`);
                        input.name = newName;
                    });
                });
            }
        });
    }

    // Existing logic for word/definition pairs
    document.querySelectorAll('.addPairBtn').forEach(function(btn) {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            const container = btn.closest('form');
            const pairsList = container.querySelector('.pairs-list');
            const wordInput = container.querySelector('input[name="words[]"]');
            const defInput = container.querySelector('input[name="definitions[]"]');
            if (wordInput.value && defInput.value) {
                const pairDiv = document.createElement('div');
                pairDiv.className = 'flex flex-col md:flex-row gap-3 p-4 bg-gradient-to-r from-pink-50 to-teal-50 border-2 border-teal-200 rounded-xl hover:border-teal-300 transition-colors';
                pairDiv.innerHTML = `
                    <input type='text' name='words[]' value='${wordInput.value}' class='flex-1 border-2 border-teal-300 rounded-lg px-4 py-2.5 text-teal-900 font-semibold bg-pink-50' required readonly> 
                    <input type='text' name='definitions[]' value='${defInput.value}' class='flex-2 border-2 border-teal-300 rounded-lg px-4 py-2.5 text-teal-900 font-semibold bg-pink-50' required readonly> 
                    <button type='button' class='removePairBtn px-4 py-2.5 bg-gradient-to-r from-red-400 to-rose-500 text-white rounded-lg font-bold shadow-md hover:from-red-500 hover:to-rose-600 transform hover:scale-105 transition-all duration-200'>-</button>
                `;
                pairsList.appendChild(pairDiv);
                wordInput.value = '';
                defInput.value = '';
                pairDiv.querySelector('.removePairBtn').onclick = function() { pairDiv.remove(); };
            }
        });
    });
});
</script>

        {{-- Removed global Saved Pairs section. Pairs are now only shown inside their group boxes. --}}

        <!-- Removed fallback word/definition pairs form. Only group boxes with their own forms are shown. -->
    </div>
</div>

<!-- Word Search View Modal -->
<div id="wordSearchViewModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden items-center justify-center p-4" style="display: none;">
    <div class="bg-white rounded-2xl shadow-2xl max-w-4xl w-full max-h-[90vh] overflow-y-auto">
        <div class="sticky top-0 bg-gradient-to-r from-pink-500 to-cyan-600 text-white p-6 rounded-t-2xl flex justify-between items-center">
            <h3 class="text-2xl font-bold">Word Search Game Preview</h3>
            <button type="button" id="closeWordSearchModal" class="text-white hover:text-gray-200 text-3xl font-bold w-10 h-10 flex items-center justify-center rounded-full hover:bg-white hover:bg-opacity-20 transition-all">
                ×
            </button>
        </div>
        <div class="p-8" id="wordSearchModalContent" dir="rtl">
            <!-- Content will be populated by JavaScript -->
        </div>
    </div>
</div>

@endsection
