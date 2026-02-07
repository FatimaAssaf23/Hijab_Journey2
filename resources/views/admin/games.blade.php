@extends('layouts.admin')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-pink-50 via-rose-50 to-cyan-50">
    <!-- Header - Matches Navbar -->
    <div class="relative bg-gradient-to-r from-[#FC8EAC] via-[#EC769A] to-[#6EC6C5] shadow-xl overflow-hidden">
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
            <div class="flex items-center justify-between flex-wrap gap-4">
                <div class="flex items-center gap-4">
                    <a href="{{ route('admin.dashboard') }}" class="flex items-center justify-center w-12 h-12 bg-white/20 backdrop-blur-xl rounded-xl border border-white/30 shadow-lg hover:bg-white/30 transition-all transform hover:scale-105" title="Go Back">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                        </svg>
                    </a>
                    <div>
                        <h1 class="text-4xl font-black text-white mb-1 drop-shadow-lg tracking-tight">
                            🎮 Games Analytics Hub
                        </h1>
                        <p class="text-white/90 text-sm font-medium">Comprehensive insights into student game performance</p>
                    </div>
                </div>
                <div class="bg-white/20 backdrop-blur-xl rounded-xl px-6 py-3 border border-white/30 shadow-lg">
                    <div class="text-center">
                        <p class="text-white/90 text-xs font-semibold mb-1">Total Games</p>
                        <p class="text-white text-3xl font-black">{{ $totalGames }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if(session('success'))
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-6">
        <div class="bg-gradient-to-r from-cyan-400 to-teal-500 text-white rounded-2xl p-4 flex items-center gap-3 shadow-xl border-2 border-cyan-300">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <p class="font-bold">{{ session('success') }}</p>
        </div>
    </div>
    @endif

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Filter Section -->
        <div class="mb-8">
            <div class="bg-white/90 backdrop-blur-xl rounded-3xl p-6 shadow-2xl border-2 border-pink-200">
                <form method="GET" action="{{ route('admin.games') }}" class="flex flex-wrap items-end gap-4">
                    <div class="flex-1 min-w-[200px]">
                        <label class="block text-sm font-bold text-gray-700 mb-2">Filter by Class</label>
                        <select name="class_id" class="w-full bg-pink-50 border-2 border-pink-200 rounded-xl px-4 py-3 text-gray-800 font-semibold shadow-md hover:border-pink-300 focus:border-pink-400 focus:ring-2 focus:ring-pink-200 transition-all">
                            <option value="">All Classes</option>
                            @foreach($allClasses as $class)
                                <option value="{{ $class->class_id }}" {{ $selectedClassId == $class->class_id ? 'selected' : '' }}>
                                    {{ $class->class_name }} ({{ $class->teacher ? $class->teacher->first_name . ' ' . $class->teacher->last_name : 'Unassigned' }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit" class="px-6 py-3 bg-gradient-to-r from-pink-500 to-cyan-600 text-white font-bold rounded-xl shadow-lg hover:from-pink-600 hover:to-cyan-700 transform hover:scale-105 transition-all">
                        Filter
                    </button>
                    @if($selectedClassId)
                    <a href="{{ route('admin.games') }}" class="px-6 py-3 bg-gradient-to-r from-gray-400 to-gray-500 text-white font-bold rounded-xl shadow-lg hover:from-gray-500 hover:to-gray-600 transform hover:scale-105 transition-all">
                        Clear Filter
                    </a>
                    @endif
                </form>
            </div>
        </div>

        <!-- Overall Statistics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <div class="bg-gradient-to-br from-pink-300 to-rose-400 rounded-2xl p-6 shadow-xl text-gray-800 transform hover:scale-105 transition-all">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 bg-white/60 rounded-xl flex items-center justify-center">
                        <span class="text-2xl">🎯</span>
                    </div>
                    <span class="text-3xl font-black">{{ $totalGames }}</span>
                </div>
                <p class="text-gray-700 font-semibold text-sm">Total Games</p>
            </div>
            
            <div class="bg-gradient-to-br from-cyan-300 to-teal-400 rounded-2xl p-6 shadow-xl text-gray-800 transform hover:scale-105 transition-all">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 bg-white/60 rounded-xl flex items-center justify-center">
                        <span class="text-2xl">✅</span>
                    </div>
                    <span class="text-3xl font-black">{{ $completedProgresses }}</span>
                </div>
                <p class="text-gray-700 font-semibold text-sm">Completed</p>
            </div>
            
            <div class="bg-gradient-to-br from-rose-300 to-pink-400 rounded-2xl p-6 shadow-xl text-gray-800 transform hover:scale-105 transition-all">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 bg-white/60 rounded-xl flex items-center justify-center">
                        <span class="text-2xl">📊</span>
                    </div>
                    <span class="text-3xl font-black">{{ number_format($completionRate, 1) }}%</span>
                </div>
                <p class="text-gray-700 font-semibold text-sm">Completion Rate</p>
            </div>
            
            <div class="bg-gradient-to-br from-teal-300 to-cyan-400 rounded-2xl p-6 shadow-xl text-gray-800 transform hover:scale-105 transition-all">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 bg-white/60 rounded-xl flex items-center justify-center">
                        <span class="text-2xl">⭐</span>
                    </div>
                    <span class="text-3xl font-black">{{ number_format($averageScore, 1) }}</span>
                </div>
                <p class="text-gray-700 font-semibold text-sm">Average Score</p>
            </div>
        </div>

        <!-- Game Type Distribution -->
        <div class="mb-8">
            <div class="bg-white/90 backdrop-blur-xl rounded-3xl p-8 shadow-2xl border-2 border-cyan-200">
                <h3 class="text-2xl font-bold text-gray-800 mb-6 flex items-center gap-3">
                    <span class="text-3xl">📈</span>
                    Games by Type
                </h3>
                <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-7 gap-4">
                    @foreach($gameTypeCounts as $type => $count)
                        @if($count > 0)
                        <div class="bg-gradient-to-br from-pink-50 to-cyan-50 rounded-xl p-4 text-center border-2 border-pink-200 hover:border-cyan-400 transition-all transform hover:scale-105">
                            <p class="text-xs text-gray-600 mb-2 font-semibold">{{ ucfirst(str_replace('_', ' ', $type)) }}</p>
                            <p class="text-3xl font-black text-pink-600">{{ $count }}</p>
                        </div>
                        @endif
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Charts Section -->
        <div class="mb-8">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Progress Status Radial Bar Chart -->
                <div class="bg-white/90 backdrop-blur-xl rounded-3xl p-8 shadow-2xl border-2 border-pink-200">
                    <h3 class="text-2xl font-bold text-gray-800 mb-2 flex items-center gap-3">
                        <span class="text-3xl">🎯</span>
                        Progress Status Overview
                    </h3>
                    <div id="progressChart" style="min-height: 400px;"></div>
                </div>

                <!-- Class Performance Comparison -->
                @if(!empty($classStats))
                <div class="bg-white/90 backdrop-blur-xl rounded-3xl p-8 shadow-2xl border-2 border-cyan-200">
                    <h3 class="text-2xl font-bold text-gray-800 mb-8 flex items-center gap-3">
                        <span class="text-3xl">✨</span>
                        Class Performance Trends
                    </h3>
                    <div id="classComparisonChart" style="min-height: 400px;"></div>
                </div>
                @endif
            </div>
        </div>

        <!-- By Classes Section -->
        @if(!empty($classStats))
        <div class="mb-8">
            <div class="bg-white/90 backdrop-blur-xl rounded-3xl shadow-2xl border-2 border-pink-200">
                <div class="p-8">
                    <h3 class="text-2xl font-bold text-gray-800 mb-6 flex items-center gap-3">
                        <span class="text-3xl">🎓</span>
                        Performance by Class
                    </h3>
                    <div class="space-y-6">
                        @foreach($classStats as $index => $class)
                        <div class="bg-gradient-to-r {{ $index % 2 == 0 ? 'from-pink-50 via-rose-50 to-pink-50' : 'from-cyan-50 via-teal-50 to-cyan-50' }} rounded-2xl p-6 shadow-xl border-2 {{ $index % 2 == 0 ? 'border-pink-300' : 'border-cyan-300' }} hover:shadow-2xl transition-all">
                            <div class="flex items-center justify-between mb-4">
                                <div class="flex items-center gap-4">
                                    <div class="w-16 h-16 rounded-xl bg-gradient-to-br {{ $index % 2 == 0 ? 'from-pink-300 to-rose-400' : 'from-cyan-300 to-teal-400' }} flex items-center justify-center text-gray-800 font-black text-2xl shadow-lg">
                                        {{ $loop->iteration }}
                                    </div>
                                    <div>
                                        <h4 class="text-2xl font-black text-gray-800">{{ $class['class_name'] }}</h4>
                                        <p class="text-gray-600 font-semibold">Teacher: {{ $class['teacher'] }}</p>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
                                <div class="bg-white/80 rounded-xl p-4 text-center border border-pink-200 shadow-sm">
                                    <p class="text-xs text-gray-600 mb-1">Games</p>
                                    <p class="text-2xl font-black text-pink-600">{{ $class['total_games'] }}</p>
                                </div>
                                <div class="bg-white/80 rounded-xl p-4 text-center border border-green-200 shadow-sm">
                                    <p class="text-xs text-gray-600 mb-1">Completed</p>
                                    <p class="text-2xl font-black text-green-600">{{ $class['completed'] }}</p>
                                </div>
                                <div class="bg-white/80 rounded-xl p-4 text-center border border-purple-200 shadow-sm">
                                    <p class="text-xs text-gray-600 mb-1">Avg Score</p>
                                    <p class="text-2xl font-black text-purple-600">{{ $class['average_score'] }}</p>
                                </div>
                                <div class="bg-white/80 rounded-xl p-4 text-center border border-orange-200 shadow-sm">
                                    <p class="text-xs text-gray-600 mb-1">Completion</p>
                                    <p class="text-2xl font-black text-orange-600">{{ $class['completion_rate'] }}%</p>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
        @endif

        <!-- Games List Section -->
        <div class="mb-8">
            <div class="bg-white/90 backdrop-blur-xl rounded-3xl shadow-2xl border-2 border-pink-200">
                <div class="p-8">
                    <h3 class="text-2xl font-bold text-gray-800 mb-6 flex items-center gap-3">
                        <span class="text-3xl">🎮</span>
                        All Games
                    </h3>
                    
                    @if(empty($organizedGames) && empty($gamesData))
                    <div class="text-center py-20">
                        <div class="w-32 h-32 bg-gradient-to-br from-pink-200 to-cyan-200 rounded-full flex items-center justify-center mx-auto mb-6 shadow-xl">
                            <span class="text-6xl">🎮</span>
                        </div>
                        <h3 class="text-3xl font-bold text-gray-800 mb-2">No Games Found</h3>
                        <p class="text-gray-600 text-lg">No games have been created yet.</p>
                    </div>
                    @else
                    <div class="space-y-8">
                        @php
                            $globalGameIndex = 0;
                        @endphp
                        @foreach($organizedGames as $levelName => $lessons)
                        <!-- Level Section -->
                        <div class="border-2 border-pink-300 rounded-2xl overflow-hidden shadow-lg">
                            <div class="bg-gradient-to-r from-pink-400 via-rose-400 to-cyan-400 px-6 py-4">
                                <h4 class="text-2xl font-black text-white flex items-center gap-3">
                                    <span class="text-3xl">📚</span>
                                    Level: {{ $levelName }}
                                    <span class="text-lg font-normal text-white/90 ml-auto">
                                        {{ count($lessons) }} {{ count($lessons) == 1 ? 'Lesson' : 'Lessons' }}
                                    </span>
                                </h4>
                            </div>
                            
                            <div class="p-6 space-y-6">
                                @foreach($lessons as $lessonId => $lessonData)
                                <!-- Lesson Section -->
                                <div class="bg-gradient-to-br from-cyan-50 to-teal-50 rounded-xl p-6 border-2 border-cyan-200 shadow-md">
                                    <div class="flex items-center justify-between mb-4 pb-4 border-b-2 border-cyan-300">
                                        <h5 class="text-xl font-black text-gray-800 flex items-center gap-3">
                                            <span class="text-2xl">📖</span>
                                            {{ $lessonData['lesson_title'] }}
                                        </h5>
                                        <span class="bg-cyan-200 text-cyan-800 px-4 py-2 rounded-lg font-bold text-sm">
                                            {{ count($lessonData['games']) }} {{ count($lessonData['games']) == 1 ? 'Game' : 'Games' }}
                                        </span>
                                    </div>
                                    
                                    <div class="space-y-4">
                                        @foreach($lessonData['games'] as $game)
                                        @php
                                            $gameIndex = $globalGameIndex++;
                                            $isEven = $gameIndex % 2 == 0;
                                        @endphp
                                        <div x-data="{ expanded: false }" 
                                             class="bg-gradient-to-r {{ $isEven ? 'from-pink-50 via-rose-50 to-pink-50' : 'from-cyan-50 via-teal-50 to-cyan-50' }} rounded-xl p-5 shadow-lg border-2 {{ $isEven ? 'border-pink-300' : 'border-cyan-300' }} hover:shadow-xl transition-all">
                                            <div class="flex items-center justify-between mb-3">
                                                <div class="flex items-center gap-4 flex-1">
                                                    <div class="w-12 h-12 rounded-xl bg-gradient-to-br {{ $isEven ? 'from-pink-300 to-rose-400' : 'from-cyan-300 to-teal-400' }} flex items-center justify-center text-white font-black text-lg shadow-md">
                                                        {{ $loop->iteration }}
                                                    </div>
                                                    <div class="flex-1">
                                                        <h6 class="text-lg font-black text-gray-800 mb-1">{{ $game['game_name'] }}</h6>
                                                        <div class="flex items-center gap-4 text-sm">
                                                            <span class="text-gray-600 font-semibold">Type: <span class="text-gray-800">{{ ucfirst(str_replace('_', ' ', $game['game_type'])) }}</span></span>
                                                            <span class="text-gray-500">•</span>
                                                            <span class="text-gray-600">Created: <span class="text-gray-800">{{ $game['created_at'] ? \Carbon\Carbon::parse($game['created_at'])->format('M d, Y') : 'N/A' }}</span></span>
                                                        </div>
                                                    </div>
                                                    <div class="text-right">
                                                        <div class="bg-white/80 rounded-lg px-4 py-2">
                                                            <p class="text-xs text-gray-600">Avg Score</p>
                                                            <p class="text-lg font-black text-cyan-600">{{ $game['average_score'] }}</p>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="ml-4 flex gap-3">
                                                    <button @click="expanded = !expanded" 
                                                            class="px-5 py-2.5 bg-gradient-to-r {{ $isEven ? 'from-pink-300 to-rose-300' : 'from-cyan-300 to-teal-300' }} text-gray-800 rounded-xl font-bold shadow-md hover:shadow-lg transform hover:scale-105 transition-all text-sm">
                                                        <span x-text="expanded ? 'Hide' : 'Details'"></span>
                                                    </button>
                                                    <button onclick="window.showGamePreview({{ $gameIndex }})" 
                                                            class="px-5 py-2.5 bg-gradient-to-r from-purple-400 to-pink-500 text-white rounded-xl font-bold shadow-md hover:shadow-lg transform hover:scale-105 transition-all flex items-center gap-2 text-sm">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                        </svg>
                                                        View
                                                    </button>
                                                </div>
                                            </div>
                                            
                                            <div x-show="expanded" x-collapse class="mt-4 pt-4 border-t-2 border-gray-200">
                                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                                    <div>
                                                        <h5 class="font-bold text-gray-700 mb-3 flex items-center gap-2">
                                                            <span>📊</span> Statistics
                                                        </h5>
                                                        <div class="space-y-2">
                                                            <div class="flex justify-between items-center bg-white/60 rounded-lg px-4 py-2">
                                                                <span class="text-sm text-gray-600">Completed:</span>
                                                                <span class="font-bold text-green-600">{{ $game['completed'] }}</span>
                                                            </div>
                                                            <div class="flex justify-between items-center bg-white/60 rounded-lg px-4 py-2">
                                                                <span class="text-sm text-gray-600">Average Score:</span>
                                                                <span class="font-bold text-cyan-600">{{ $game['average_score'] }}</span>
                                                            </div>
                                                            <div class="flex justify-between items-center bg-white/60 rounded-lg px-4 py-2">
                                                                <span class="text-sm text-gray-600">Created:</span>
                                                                <span class="font-bold text-gray-800">{{ $game['created_at'] ? \Carbon\Carbon::parse($game['created_at'])->format('M d, Y') : 'N/A' }}</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div>
                                                        <h5 class="font-bold text-gray-700 mb-3 flex items-center gap-2">
                                                            <span>🎯</span> Game Content
                                                        </h5>
                                                        <div class="bg-white/60 rounded-lg p-4 max-h-64 overflow-y-auto">
                                                            @if($game['game_type'] == 'word_search')
                                                                @if(isset($game['game_data']['title']) && $game['game_data']['title'])
                                                                    <p class="font-semibold text-gray-800 mb-2">Title: <span class="font-normal" dir="rtl">{{ $game['game_data']['title'] }}</span></p>
                                                                @endif
                                                                <p class="font-semibold text-gray-800 mb-2">Words:</p>
                                                                <div class="flex flex-wrap gap-2">
                                                                    @if(isset($game['game_data']['words']) && is_array($game['game_data']['words']))
                                                                        @foreach($game['game_data']['words'] as $word)
                                                                            <span class="px-3 py-1 bg-pink-200 rounded-lg text-sm font-semibold text-gray-800" dir="rtl">{{ $word }}</span>
                                                                        @endforeach
                                                                    @endif
                                                                </div>
                                                                <p class="text-sm text-gray-600 mt-2">Grid Size: {{ $game['game_data']['grid_size'] ?? 10 }}x{{ $game['game_data']['grid_size'] ?? 10 }}</p>
                                                            @elseif($game['game_type'] == 'matching_pairs')
                                                                @if(isset($game['game_data']['title']) && $game['game_data']['title'])
                                                                    <p class="font-semibold text-gray-800 mb-2">Title: <span class="font-normal" dir="rtl">{{ $game['game_data']['title'] }}</span></p>
                                                                @endif
                                                                <p class="font-semibold text-gray-800 mb-2">Pairs:</p>
                                                                <div class="space-y-2">
                                                                    @if(isset($game['game_data']['pairs']) && is_array($game['game_data']['pairs']))
                                                                        @foreach($game['game_data']['pairs'] as $pairIndex => $pair)
                                                                            <div class="bg-cyan-50 rounded-lg p-2 text-sm">
                                                                                <span class="font-semibold text-gray-700">{{ $pairIndex + 1 }}.</span>
                                                                                <span class="text-gray-800" dir="rtl">{{ $pair['left_item_text'] ?? '' }}</span>
                                                                                <span class="mx-2">↔</span>
                                                                                <span class="text-gray-800" dir="rtl">{{ $pair['right_item_text'] ?? '' }}</span>
                                                                            </div>
                                                                        @endforeach
                                                                    @endif
                                                                </div>
                                                            @elseif($game['game_type'] == 'word_clock_arrangement')
                                                                @php
                                                                    // Parse game_data - handle both string and array formats
                                                                    $wordClockGameData = $game['game_data'] ?? null;
                                                                    if (is_string($wordClockGameData)) {
                                                                        $wordClockGameData = json_decode($wordClockGameData, true);
                                                                        if (json_last_error() !== JSON_ERROR_NONE) {
                                                                            $wordClockGameData = null;
                                                                        }
                                                                    }
                                                                    if (!is_array($wordClockGameData)) {
                                                                        $wordClockGameData = [];
                                                                    }
                                                                @endphp
                                                                @if(!empty($wordClockGameData))
                                                                    @if(isset($wordClockGameData['word']) && !empty($wordClockGameData['word']))
                                                                        <p class="font-semibold text-gray-800 mb-2">Word: <span class="font-normal" dir="rtl">{{ $wordClockGameData['word'] }}</span></p>
                                                                    @endif
                                                                    @if(isset($wordClockGameData['full_sentence']) && !empty($wordClockGameData['full_sentence']))
                                                                        <p class="font-semibold text-gray-800 mb-2">Sentence:</p>
                                                                        <p class="text-gray-700 text-sm mb-2" dir="rtl">{{ $wordClockGameData['full_sentence'] }}</p>
                                                                    @endif
                                                                    @if(isset($wordClockGameData['words']) && is_array($wordClockGameData['words']) && count($wordClockGameData['words']) > 0)
                                                                        <p class="font-semibold text-gray-800 mb-2">Words with Times:</p>
                                                                        <div class="space-y-1">
                                                                            @foreach($wordClockGameData['words'] as $wordData)
                                                                                @if(is_array($wordData) && !empty($wordData['word'] ?? ''))
                                                                                    <div class="bg-cyan-50 rounded-lg p-2 text-sm">
                                                                                        <span class="text-gray-800" dir="rtl">{{ $wordData['word'] ?? '' }}</span>
                                                                                        <span class="mx-2 text-gray-600">→</span>
                                                                                        <span class="text-gray-700">{{ str_pad($wordData['hour'] ?? 0, 2, '0', STR_PAD_LEFT) }}:{{ str_pad($wordData['minute'] ?? 0, 2, '0', STR_PAD_LEFT) }}</span>
                                                                                    </div>
                                                                                @endif
                                                                            @endforeach
                                                                        </div>
                                                                    @else
                                                                        <p class="text-gray-600 text-sm">No words with clock times available</p>
                                                                    @endif
                                                                @else
                                                                    <p class="text-gray-600 text-sm">Game data not available</p>
                                                                @endif
                                                            @elseif($game['game_type'] == 'clock')
                                                                @if(isset($game['game_data']['words']) && is_array($game['game_data']['words']) && count($game['game_data']['words']) > 0)
                                                                    <p class="font-semibold text-gray-800 mb-2">Words:</p>
                                                                    <div class="flex flex-wrap gap-2">
                                                                        @foreach($game['game_data']['words'] as $word)
                                                                            <span class="px-3 py-1 bg-cyan-200 rounded-lg text-sm font-semibold text-gray-800" dir="rtl">{{ $word }}</span>
                                                                        @endforeach
                                                                    </div>
                                                                @else
                                                                    <p class="text-gray-600 text-sm">Clock game data available</p>
                                                                @endif
                                                            @else
                                                                <p class="text-gray-600 text-sm">Game data available</p>
                                                                <pre class="text-xs bg-gray-100 p-2 rounded mt-2 overflow-auto">{{ json_encode($game['game_data'], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        @endforeach
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Progress Status Radial Bar Chart
    @php
        $totalProgresses = $completedProgresses + $inProgressProgresses + $notStartedProgresses;
        $completedPercent = $totalProgresses > 0 ? round(($completedProgresses / $totalProgresses) * 100, 1) : 0;
        $notStartedPercent = $totalProgresses > 0 ? round(($notStartedProgresses / $totalProgresses) * 100, 1) : 0;
    @endphp
    const progressChartOptions = {
        series: [{{ $completedPercent }}, {{ $notStartedPercent }}],
        chart: {
            type: 'radialBar',
            height: 400,
            fontFamily: 'inherit',
            sparkline: {
                enabled: false
            }
        },
        plotOptions: {
            radialBar: {
                track: {
                    background: '#e5e7eb',
                    strokeWidth: '97%',
                    margin: 5,
                },
                dataLabels: {
                    name: {
                        fontSize: '16px',
                        fontWeight: 700,
                        color: '#374151',
                        offsetY: -10
                    },
                    value: {
                        fontSize: '24px',
                        fontWeight: 800,
                        color: '#111827',
                        offsetY: 10,
                        formatter: function(val) {
                            return val + '%';
                        }
                    },
                    total: {
                        show: true,
                        label: 'Total Games',
                        fontSize: '18px',
                        fontWeight: 700,
                        color: '#6b7280',
                        formatter: function() {
                            return {{ $totalProgresses }};
                        }
                    }
                }
            }
        },
        labels: ['Completed', 'Not Started'],
        colors: ['#10b981', '#f59e0b'],
        legend: {
            show: true,
            position: 'bottom',
            fontSize: '14px',
            fontWeight: 600,
            markers: {
                width: 12,
                height: 12,
                radius: 6
            },
            itemMargin: {
                horizontal: 15,
                vertical: 5
            }
        },
        fill: {
            type: 'gradient',
            gradient: {
                shade: 'light',
                type: 'horizontal',
                shadeIntensity: 0.5,
                gradientToColors: ['#34d399', '#fbbf24'],
                inverseColors: false,
                opacityFrom: 1,
                opacityTo: 1,
                stops: [0, 50, 100]
            }
        },
        stroke: {
            lineCap: 'round'
        },
        tooltip: {
            y: {
                formatter: function(val, opts) {
                    const index = opts.seriesIndex;
                    const counts = [{{ $completedProgresses }}, {{ $notStartedProgresses }}];
                    return counts[index] + ' games (' + val.toFixed(1) + '%)';
                }
            }
        }
    };
    
    const progressChart = new ApexCharts(document.querySelector("#progressChart"), progressChartOptions);
    progressChart.render();

    @if(!empty($classStats))
    // Class Comparison Chart
    const classNames = @json(array_column($classStats, 'class_name'));
    const classCompletionRates = @json(array_column($classStats, 'completion_rate'));
    const classAverageScores = @json(array_column($classStats, 'average_score'));
    
    const classComparisonOptions = {
        series: [{
            name: 'Completion Rate',
            data: classCompletionRates
        }, {
            name: 'Average Score',
            data: classAverageScores
        }],
        chart: {
            type: 'area',
            height: 400,
            fontFamily: 'inherit',
            toolbar: {
                show: true,
                tools: {
                    download: true,
                    selection: true,
                    zoom: true,
                    zoomin: true,
                    zoomout: true,
                    pan: false,
                    reset: true
                }
            },
            zoom: {
                enabled: true,
                type: 'x',
                autoSelected: 'zoom'
            },
            animations: {
                enabled: true,
                easing: 'easeinout',
                speed: 800,
                animateGradually: {
                    enabled: true,
                    delay: 150
                },
                dynamicAnimation: {
                    enabled: true,
                    speed: 350
                }
            }
        },
        stroke: {
            curve: 'smooth',
            width: [3, 3],
            dashArray: [0, 0]
        },
        fill: {
            type: 'gradient',
            gradient: {
                shade: 'light',
                type: 'vertical',
                shadeIntensity: 0.5,
                gradientToColors: ['#f472b6', '#67e8f9'],
                inverseColors: false,
                opacityFrom: 0.8,
                opacityTo: 0.3,
                stops: [0, 50, 100]
            }
        },
        dataLabels: {
            enabled: true,
            style: {
                fontSize: '12px',
                fontWeight: 700,
                colors: ['#fff', '#fff']
            },
            background: {
                enabled: true,
                foreColor: '#fff',
                padding: 6,
                borderRadius: 4,
                borderWidth: 1,
                borderColor: '#fff',
                opacity: 0.9
            },
            formatter: function(val, opts) {
                if (opts.seriesIndex === 0) {
                    return val.toFixed(1) + '%';
                }
                return val.toFixed(1);
            }
        },
        xaxis: {
            categories: classNames,
            labels: {
                style: {
                    fontSize: '12px',
                    fontWeight: 600,
                    colors: '#6b7280'
                },
                rotate: -45,
                rotateAlways: false
            },
            axisBorder: {
                show: true,
                color: '#e5e7eb',
                height: 1,
                width: '100%',
                offsetX: 0,
                offsetY: 0
            }
        },
        yaxis: {
            min: 0,
            max: 100,
            labels: {
                style: {
                    fontSize: '12px',
                    fontWeight: 600,
                    colors: '#6b7280'
                },
                formatter: function(val) {
                    return val.toFixed(0);
                }
            },
            title: {
                text: 'Percentage / Score',
                style: {
                    fontSize: '14px',
                    fontWeight: 700,
                    color: '#374151'
                }
            }
        },
        colors: ['#ec4899', '#06b6d4'],
        markers: {
            size: [6, 6],
            strokeWidth: 2,
            strokeColors: ['#fff', '#fff'],
            hover: {
                size: 8
            }
        },
        grid: {
            borderColor: '#e5e7eb',
            strokeDashArray: 4,
            xaxis: {
                lines: {
                    show: false
                }
            },
            yaxis: {
                lines: {
                    show: true
                }
            },
            padding: {
                top: 0,
                right: 0,
                bottom: 0,
                left: 0
            }
        },
        legend: {
            position: 'top',
            horizontalAlign: 'center',
            fontSize: '14px',
            fontWeight: 700,
            markers: {
                width: 14,
                height: 14,
                radius: 7,
                offsetX: -5,
                offsetY: 0
            },
            itemMargin: {
                horizontal: 20,
                vertical: 5
            }
        },
        tooltip: {
            shared: true,
            intersect: false,
            theme: 'dark',
            style: {
                fontSize: '13px'
            },
            y: {
                formatter: function(val, opts) {
                    if (opts.seriesIndex === 0) {
                        return val.toFixed(1) + '% Completion Rate';
                    }
                    return val.toFixed(1) + ' Average Score';
                }
            },
            marker: {
                show: true
            }
        }
    };
    
    const classComparisonChart = new ApexCharts(document.querySelector("#classComparisonChart"), classComparisonOptions);
    classComparisonChart.render();
    @endif
});

// Game preview functionality - defined globally
const gamesData = @json($flatGamesData ?? $gamesData);

window.showGamePreview = function(gameIndex) {
    const game = gamesData[gameIndex];
    if (!game) {
        alert('Game not found');
        return;
    }
    
    console.log('Showing preview for game:', game);
    console.log('Game type:', game.game_type);
    console.log('Game data:', game.game_data);
    console.log('Game data type:', typeof game.game_data);
    
    if (game.game_type === 'word_search' && game.game_data) {
        console.log('Word search grid_data:', game.game_data.grid_data);
        console.log('Grid data type:', typeof game.game_data.grid_data);
    }
    
    const modal = document.getElementById('gamePreviewModal');
    const modalContent = document.getElementById('gamePreviewContent');
    
    if (!modal || !modalContent) {
        alert('Modal not found');
        return;
    }
    
    let previewHTML = `
        <div class="bg-white rounded-2xl p-8 max-h-[90vh] overflow-y-auto">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-3xl font-black text-gray-800">${game.game_name} Preview</h3>
                <button onclick="window.closeGamePreview()" class="text-gray-500 hover:text-gray-700 text-3xl font-bold w-10 h-10 flex items-center justify-center rounded-full hover:bg-gray-100">×</button>
            </div>
            <div class="mb-4">
                <p class="text-lg text-gray-600"><strong>Lesson:</strong> ${game.lesson_title}</p>
                <p class="text-sm text-gray-500">Level: ${game.level_name}</p>
            </div>
    `;
    
    // Render based on game type
    if (game.game_type === 'word_search') {
        console.log('Rendering word search game');
        console.log('Full game object:', JSON.stringify(game, null, 2));
        try {
            // Ensure game_data is an object - handle null case
            let gameData = {};
            if (game.game_data && typeof game.game_data === 'object') {
                gameData = game.game_data;
            } else if (game.game_data && typeof game.game_data === 'string') {
                try {
                    gameData = JSON.parse(game.game_data);
                } catch (e) {
                    console.error('Error parsing game_data string:', e);
                    gameData = {};
                }
            }
            console.log('Initial gameData:', gameData);
            console.log('gameData type:', typeof gameData);
            
            const words = gameData.words || [];
            const gridSize = gameData.grid_size || 10;
            let gridData = gameData.grid_data || null;
            const title = gameData.title || '';
            
            console.log('Words:', words);
            console.log('Grid size:', gridSize);
            console.log('Grid data:', gridData);
            console.log('Title:', title);
            
            // Parse grid_data if it's a string
            if (typeof gridData === 'string') {
                try {
                    gridData = JSON.parse(gridData);
                    console.log('Parsed gridData:', gridData);
                } catch (e) {
                    console.error('Error parsing grid_data:', e);
                    gridData = null;
                }
            }
            
            // Extract the actual grid array from grid_data structure
            let grid = null;
            if (gridData) {
                if (gridData.grid && Array.isArray(gridData.grid)) {
                    grid = gridData.grid;
                    console.log('Found grid in gridData.grid');
                } else if (Array.isArray(gridData)) {
                    grid = gridData;
                    console.log('gridData is already an array');
                } else {
                    console.log('gridData structure:', typeof gridData, gridData);
                }
            } else {
                console.log('No gridData available');
            }
            
            console.log('Final grid:', grid);
            
            previewHTML += `
                <div class="bg-gradient-to-br from-pink-50 to-cyan-50 rounded-xl p-6" dir="rtl">
                    ${title ? `<h4 class="text-xl font-bold text-purple-600 mb-4" dir="rtl">${title}</h4>` : ''}
                    <div class="flex flex-col lg:flex-row gap-8">
                        <div class="flex-1">
                            <h5 class="text-lg font-bold mb-3">Word Search Grid</h5>
                            <div class="inline-block border-2 border-gray-300 bg-white p-2 rounded-lg" style="direction: ltr;">
            `;
            
            if (grid && Array.isArray(grid) && grid.length > 0) {
                console.log('Rendering grid with', grid.length, 'rows');
                grid.forEach((row, rowIndex) => {
                    previewHTML += '<div class="flex">';
                    if (Array.isArray(row)) {
                        row.forEach((cell, colIndex) => {
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
                            previewHTML += `<div class="w-10 h-10 border border-gray-200 flex items-center justify-center text-lg font-semibold bg-white text-gray-800" dir="rtl" style="min-width: 2.5rem; min-height: 2.5rem; line-height: 1; font-size: 1.1rem;">${displayValue}</div>`;
                        });
                    } else {
                        // If row is not an array, create empty cells
                        for (let col = 0; col < gridSize; col++) {
                            previewHTML += `<div class="w-8 h-8 border border-gray-200 flex items-center justify-center text-sm font-semibold bg-gray-50 text-gray-400" style="min-width: 2rem; min-height: 2rem;">?</div>`;
                        }
                    }
                    previewHTML += '</div>';
                });
            } else {
                console.log('No grid data available, showing placeholder grid of size', gridSize);
                // Generate placeholder grid if no grid data
                for (let row = 0; row < gridSize; row++) {
                    previewHTML += '<div class="flex">';
                    for (let col = 0; col < gridSize; col++) {
                        previewHTML += `<div class="w-8 h-8 border border-gray-200 flex items-center justify-center text-sm font-semibold bg-gray-50 text-gray-400" style="min-width: 2rem; min-height: 2rem;">?</div>`;
                    }
                    previewHTML += '</div>';
                }
            }
            
            console.log('Grid HTML generated, length:', previewHTML.length);
            
            previewHTML += `
                            </div>
                        </div>
                        <div class="lg:w-64">
                            <h5 class="text-lg font-bold mb-3" dir="rtl">Words to Find:</h5>
                            <div class="space-y-2" dir="rtl">
            `;
            
            if (words && words.length > 0) {
                words.forEach(word => {
                    previewHTML += `
                        <div class="p-3 border-2 border-gray-300 rounded-lg bg-white">
                            <span class="font-semibold text-lg" dir="rtl">${word}</span>
                        </div>
                    `;
                });
            } else {
                previewHTML += `<p class="text-gray-500">No words available</p>`;
            }
            
            previewHTML += `
                            </div>
                        </div>
                    </div>
                </div>
            `;
        } catch (error) {
            console.error('Error rendering word search preview:', error);
            previewHTML += `
                <div class="bg-red-50 border-2 border-red-300 rounded-xl p-6">
                    <p class="text-red-600 font-bold mb-2">Error displaying word search game</p>
                    <p class="text-sm text-red-500">${error.message}</p>
                    <details class="mt-4">
                        <summary class="cursor-pointer text-sm font-semibold text-red-700">Show game data</summary>
                        <pre class="text-xs bg-white p-4 rounded mt-2 overflow-auto">${JSON.stringify(game.game_data, null, 2)}</pre>
                    </details>
                </div>
            `;
        }
    } else if (game.game_type === 'matching_pairs') {
        const pairs = game.game_data.pairs || [];
        const title = game.game_data.title || '';
        
        previewHTML += `
            <div class="bg-gradient-to-br from-pink-50 to-purple-50 rounded-xl p-6">
                ${title ? `<h4 class="text-xl font-bold text-purple-600 mb-4" dir="rtl">${title}</h4>` : ''}
                <div class="grid grid-cols-2 gap-8">
                    <div>
                        <h5 class="text-xl font-bold mb-4 text-center text-pink-600">Left Column</h5>
                        <div class="space-y-4">
        `;
        
        pairs.forEach((pair) => {
            previewHTML += `
                <div class="bg-gradient-to-br from-pink-50 to-purple-50 border-2 border-pink-300 rounded-xl p-4">
                    <div class="flex items-center gap-3">
                        ${pair.left_item_image ? `<img src="/storage/${pair.left_item_image}" alt="Left" class="w-16 h-16 object-cover rounded-lg border-2 border-pink-400">` : ''}
                        ${pair.left_item_text ? `<span class="text-lg font-semibold text-gray-800 flex-1" dir="rtl">${pair.left_item_text}</span>` : ''}
                    </div>
                </div>
            `;
        });
        
        previewHTML += `
                        </div>
                    </div>
                    <div>
                        <h5 class="text-xl font-bold mb-4 text-center text-purple-600">Right Column</h5>
                        <div class="space-y-4">
        `;
        
        pairs.forEach((pair) => {
            previewHTML += `
                <div class="bg-gradient-to-br from-purple-50 to-pink-50 border-2 border-purple-300 rounded-xl p-4">
                    <div class="flex items-center gap-3">
                        ${pair.right_item_image ? `<img src="/storage/${pair.right_item_image}" alt="Right" class="w-16 h-16 object-cover rounded-lg border-2 border-purple-400">` : ''}
                        ${pair.right_item_text ? `<span class="text-lg font-semibold text-gray-800 flex-1" dir="rtl">${pair.right_item_text}</span>` : ''}
                    </div>
                </div>
            `;
        });
        
        previewHTML += `
                        </div>
                    </div>
                </div>
            </div>
        `;
    } else if (game.game_type === 'word_clock_arrangement') {
        // Parse game_data if it's a string
        let gameData = game.game_data;
        if (typeof gameData === 'string') {
            try {
                gameData = JSON.parse(gameData);
            } catch (e) {
                console.error('Error parsing game_data:', e);
                gameData = {};
            }
        }
        
        const words = gameData.words || [];
        const word = gameData.word || '';
        const sentence = gameData.full_sentence || '';
        
        if (words.length === 0) {
            previewHTML += `
                <div class="bg-gradient-to-br from-cyan-50 to-teal-50 rounded-xl p-6">
                    <p class="text-gray-600 mb-4">No word clock data available.</p>
                    <pre class="text-xs bg-white p-4 rounded overflow-auto">${JSON.stringify(gameData, null, 2)}</pre>
                </div>
            `;
        } else {
            previewHTML += `
                <div class="bg-gradient-to-br from-cyan-50 to-teal-50 rounded-xl p-6" dir="rtl">
                    ${word ? `<h4 class="text-2xl font-bold text-pink-600 mb-2">${word}</h4>` : ''}
                    ${sentence ? `<p class="text-lg text-gray-700 mb-6">${sentence}</p>` : ''}
                    <div class="flex flex-wrap justify-center gap-6 items-start">
            `;
            
            words.forEach((wordData, index) => {
                // Handle both object format {word, hour, minute} and array format
                const wordText = wordData.word || (typeof wordData === 'string' ? wordData : '');
                const hour = wordData.hour !== undefined ? parseInt(wordData.hour) : ((index * 2) % 12);
                const minute = wordData.minute !== undefined ? parseInt(wordData.minute) : ((index * 5) % 60);
                
                const hourAngle = ((hour % 12) * 30 + minute * 0.5 - 90) * Math.PI / 180;
                const minuteAngle = (minute * 6 - 90) * Math.PI / 180;
                const hourX = 50 + 25 * Math.cos(hourAngle);
                const hourY = 50 + 25 * Math.sin(hourAngle);
                const minuteX = 50 + 35 * Math.cos(minuteAngle);
                const minuteY = 50 + 35 * Math.sin(minuteAngle);
                
                previewHTML += `
                    <div class="flex flex-col items-center">
                        <svg width="100" height="100" class="mb-2">
                            <circle cx="50" cy="50" r="45" fill="white" stroke="#333" stroke-width="2"/>
                `;
                
                for (let i = 1; i <= 12; i++) {
                    const angle = ((i - 3) * 30) * Math.PI / 180;
                    const x = 50 + 35 * Math.cos(angle);
                    const y = 50 + 35 * Math.sin(angle);
                    previewHTML += `<text x="${x}" y="${y + 5}" text-anchor="middle" font-size="10" fill="#333">${i}</text>`;
                }
                
                previewHTML += `
                            <line x1="50" y1="50" x2="${hourX}" y2="${hourY}" stroke="#333" stroke-width="3" stroke-linecap="round"/>
                            <line x1="50" y1="50" x2="${minuteX}" y2="${minuteY}" stroke="#333" stroke-width="2" stroke-linecap="round"/>
                            <circle cx="50" cy="50" r="3" fill="#333"/>
                        </svg>
                        <div class="text-2xl mb-1">↓</div>
                        <div class="text-lg font-semibold text-gray-800 px-3 py-2 bg-pink-50 rounded border border-pink-200" dir="rtl">${wordText}</div>
                        <div class="text-xs text-gray-500 mt-1">${String(hour).padStart(2, '0')}:${String(minute).padStart(2, '0')}</div>
                    </div>
                `;
            });
            
            previewHTML += `
                    </div>
                </div>
            `;
        }
    } else if (game.game_type === 'clock') {
        // Parse game_data if it's a string
        let gameData = game.game_data;
        if (typeof gameData === 'string') {
            try {
                gameData = JSON.parse(gameData);
            } catch (e) {
                console.error('Error parsing game_data:', e);
                gameData = {};
            }
        }
        
        const words = gameData.words || [];
        
        if (words.length === 0) {
            previewHTML += `
                <div class="bg-gradient-to-br from-cyan-50 to-teal-50 rounded-xl p-6">
                    <p class="text-gray-600 mb-4">No clock game data available.</p>
                </div>
            `;
        } else {
            previewHTML += `
                <div class="bg-gradient-to-br from-cyan-50 to-teal-50 rounded-xl p-6">
                    <div class="flex flex-wrap justify-center gap-6 items-start">
            `;
            
            words.forEach((word, index) => {
                const wordText = typeof word === 'string' ? word : (word.word || '');
                const hour = (index * 2) % 12;
                const minute = (index * 5) % 60;
                const hourAngle = ((hour % 12) * 30 + minute * 0.5 - 90) * Math.PI / 180;
                const minuteAngle = (minute * 6 - 90) * Math.PI / 180;
                const hourX = 50 + 25 * Math.cos(hourAngle);
                const hourY = 50 + 25 * Math.sin(hourAngle);
                const minuteX = 50 + 35 * Math.cos(minuteAngle);
                const minuteY = 50 + 35 * Math.sin(minuteAngle);
                
                previewHTML += `
                    <div class="flex flex-col items-center">
                        <svg width="100" height="100" class="mb-2">
                            <circle cx="50" cy="50" r="45" fill="white" stroke="#333" stroke-width="2"/>
                `;
                
                for (let i = 1; i <= 12; i++) {
                    const angle = ((i - 3) * 30) * Math.PI / 180;
                    const x = 50 + 35 * Math.cos(angle);
                    const y = 50 + 35 * Math.sin(angle);
                    previewHTML += `<text x="${x}" y="${y + 5}" text-anchor="middle" font-size="10" fill="#333">${i}</text>`;
                }
                
                previewHTML += `
                            <line x1="50" y1="50" x2="${hourX}" y2="${hourY}" stroke="#333" stroke-width="3" stroke-linecap="round"/>
                            <line x1="50" y1="50" x2="${minuteX}" y2="${minuteY}" stroke="#333" stroke-width="2" stroke-linecap="round"/>
                            <circle cx="50" cy="50" r="3" fill="#333"/>
                        </svg>
                        <div class="text-lg font-semibold text-gray-800 px-3 py-2 bg-pink-50 rounded border border-pink-200" dir="rtl">${wordText}</div>
                    </div>
                `;
            });
            
            previewHTML += `
                    </div>
                </div>
            `;
        }
    } else {
        // For any other game type, show the raw data
        let gameDataDisplay = game.game_data;
        if (typeof gameDataDisplay === 'string') {
            try {
                gameDataDisplay = JSON.parse(gameDataDisplay);
            } catch (e) {
                // Keep as string if parsing fails
            }
        }
        
        previewHTML += `
            <div class="bg-gray-50 rounded-xl p-6">
                <p class="text-gray-600 mb-4">Game preview for this type is being prepared.</p>
                <p class="text-sm text-gray-500 mb-2">Game Type: <strong>${game.game_type}</strong></p>
                <pre class="text-xs bg-white p-4 rounded overflow-auto">${JSON.stringify(gameDataDisplay, null, 2)}</pre>
            </div>
        `;
    }
    
    previewHTML += `
        </div>
    `;
    
    try {
        modalContent.innerHTML = previewHTML;
        modal.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
        console.log('Modal displayed successfully');
    } catch (error) {
        console.error('Error displaying modal:', error);
        alert('Error displaying game preview: ' + error.message);
    }
};

window.closeGamePreview = function() {
    const modal = document.getElementById('gamePreviewModal');
    if (modal) {
        modal.classList.add('hidden');
        document.body.style.overflow = 'auto';
    }
};

// Close modal on outside click
document.addEventListener('DOMContentLoaded', function() {
    const modalElement = document.getElementById('gamePreviewModal');
    if (modalElement) {
        modalElement.addEventListener('click', function(e) {
            if (e.target === this) {
                closeGamePreview();
            }
        });
    }
});
</script>

<!-- Game Preview Modal -->
<div id="gamePreviewModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
    <div class="w-full max-w-6xl">
        <div id="gamePreviewContent"></div>
    </div>
</div>

@endsection
