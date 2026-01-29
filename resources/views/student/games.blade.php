@extends('layouts.app')

@section('content')
@push('styles')
<style>
    @keyframes float {
        0%, 100% { transform: translateY(0px) rotate(0deg); }
        50% { transform: translateY(-20px) rotate(5deg); }
    }
    @keyframes shimmer {
        0% { background-position: -1000px 0; }
        100% { background-position: 1000px 0; }
    }
    @keyframes gradient-shift {
        0%, 100% { background-position: 0% 50%; }
        50% { background-position: 100% 50%; }
    }
    @keyframes slideInUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    @keyframes scaleIn {
        from {
            opacity: 0;
            transform: scale(0.9);
        }
        to {
            opacity: 1;
            transform: scale(1);
        }
    }
    @keyframes pulse-glow {
        0%, 100% { box-shadow: 0 0 20px rgba(236, 72, 153, 0.3); }
        50% { box-shadow: 0 0 40px rgba(236, 72, 153, 0.6); }
    }
    @keyframes rotate-gradient {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }
    
    .float-animation {
        animation: float 6s ease-in-out infinite;
    }
    .shimmer-effect {
        background-size: 200% 100%;
        animation: shimmer 3s infinite;
    }
    .gradient-animated {
        background-size: 200% 200%;
        animation: gradient-shift 5s ease infinite;
    }
    .slide-in-up {
        animation: slideInUp 0.6s ease-out forwards;
    }
    .scale-in {
        animation: scaleIn 0.5s ease-out forwards;
    }
    .pulse-glow {
        animation: pulse-glow 2s ease-in-out infinite;
    }
    
    .game-card {
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
        overflow: hidden;
    }
    .game-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
        transition: left 0.5s;
    }
    .game-card:hover::before {
        left: 100%;
    }
    .game-card:hover {
        transform: translateY(-10px) scale(1.02);
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
    }
    
    .lesson-header-card {
        background: linear-gradient(135deg, rgba(255, 255, 255, 0.95) 0%, rgba(255, 182, 193, 0.1) 100%);
        backdrop-filter: blur(20px);
        border: 1px solid rgba(255, 182, 193, 0.3);
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.1);
    }
    
    .lesson-title-display {
        color: #4b5563;
        font-weight: 700;
        position: relative;
    }
    
    .game-container {
        opacity: 0;
        transform: translateY(20px);
        transition: all 0.6s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .game-container.show {
        opacity: 1 !important;
        transform: translateY(0) !important;
        display: block !important;
    }
    /* Ensure game containers can be displayed */
    .game-container[style*="display: none"] {
        /* This will be overridden by JavaScript */
    }
    
    .particle {
        position: absolute;
        border-radius: 50%;
        pointer-events: none;
        opacity: 0.6;
    }
</style>
@endpush

<div class="min-h-screen bg-gradient-to-br from-pink-50 via-cyan-50/30 to-teal-50/20 relative overflow-hidden">
    <!-- Enhanced Animated Background Elements -->
    <div class="absolute inset-0 overflow-hidden pointer-events-none">
        <div class="absolute -top-40 -left-40 w-[500px] h-[500px] bg-gradient-to-br from-pink-300/40 to-rose-400/40 rounded-full blur-3xl float-animation"></div>
        <div class="absolute top-1/2 -right-40 w-[500px] h-[500px] bg-gradient-to-br from-cyan-300/40 to-teal-400/40 rounded-full blur-3xl float-animation" style="animation-delay: 2s;"></div>
        <div class="absolute bottom-0 left-1/4 w-[400px] h-[400px] bg-gradient-to-br from-purple-300/30 to-pink-400/30 rounded-full blur-3xl float-animation" style="animation-delay: 4s;"></div>
    </div>
    
    <!-- Floating Particles -->
    <div class="absolute inset-0 overflow-hidden pointer-events-none">
        @for($i = 0; $i < 20; $i++)
            <div class="particle" style="
                width: {{ rand(4, 8) }}px;
                height: {{ rand(4, 8) }}px;
                left: {{ rand(0, 100) }}%;
                top: {{ rand(0, 100) }}%;
                background: {{ ['#f9a8d4', '#67e8f9', '#a78bfa', '#f472b6'][rand(0, 3)] }};
                animation: float {{ rand(3, 8) }}s ease-in-out infinite;
                animation-delay: {{ rand(0, 3) }}s;
            "></div>
        @endfor
    </div>
    
<div class="container mx-auto pt-6 pb-12 relative z-10">
    @if (!empty($error))
        <div class="max-w-5xl mx-auto mb-6 bg-red-100 border border-red-400 text-red-700 px-6 py-4 rounded-xl shadow">
            {{ $error }}
        </div>
    @endif

    <!-- Enhanced Lesson Header Card -->
    @if(isset($selectedLessonId) && $selectedLessonId && isset($lessonsWithGames) && $lessonsWithGames->count() > 0)
        <div class="max-w-7xl mx-auto mb-10 slide-in-up">
            <div class="lesson-header-card rounded-3xl p-8 transform transition-all duration-500 hover:scale-[1.01]">
                <!-- Go Back Button with Animation -->
                <div class="mb-6">
                    @if(isset($lesson) && $lesson)
                    <a href="{{ route('student.lesson.view', $lesson->lesson_id) }}?t={{ time() }}" class="group inline-flex items-center gap-3 bg-gradient-to-r from-pink-500 to-rose-500 hover:from-pink-600 hover:to-rose-600 text-white px-6 py-3 rounded-2xl font-bold shadow-lg hover:shadow-xl transition-all duration-300 transform hover:scale-105" onclick="this.href='{{ route('student.lesson.view', $lesson->lesson_id) }}?t=' + Date.now(); return true;">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 transform group-hover:-translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                        Back to Lesson
                    </a>
                    @else
                    <a href="{{ route('student.dashboard') }}" class="group inline-flex items-center gap-3 bg-gradient-to-r from-pink-500 to-rose-500 hover:from-pink-600 hover:to-rose-600 text-white px-6 py-3 rounded-2xl font-bold shadow-lg hover:shadow-xl transition-all duration-300 transform hover:scale-105">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 transform group-hover:-translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                        Go Back
                    </a>
                    @endif
                </div>
                
                <!-- Lesson Display with Creative Design -->
                <div class="flex flex-col items-center text-center">
                    <div class="mb-6">
                        <div class="relative mx-auto w-40 h-40 transform hover:scale-110 transition-all duration-300">
                            <!-- Decorative background layers -->
                            <div class="absolute inset-0 bg-gradient-to-br from-pink-400 via-rose-400 to-cyan-400 rounded-full blur-xl opacity-60 transform rotate-6"></div>
                            <div class="absolute inset-0 bg-gradient-to-br from-pink-300 via-rose-300 to-cyan-300 rounded-full blur-md opacity-40 transform -rotate-6"></div>
                            
                            <!-- Main image container - circular -->
                            <div class="relative w-full h-full rounded-full overflow-hidden shadow-2xl border-4 border-white transform rotate-3 hover:rotate-6 transition-transform duration-300">
                                <img src="{{ asset('storage/levels_page_design/hijab11.jpg') }}" 
                                     alt="Lesson Icon" 
                                     class="w-full h-full object-cover">
                                
                                <!-- Overlay gradient for depth -->
                                <div class="absolute inset-0 bg-gradient-to-t from-black/20 via-transparent to-transparent"></div>
                            </div>
                            
                            <!-- Decorative corner accents -->
                            <div class="absolute -top-2 -right-2 w-6 h-6 bg-pink-400 rounded-full blur-sm opacity-70"></div>
                            <div class="absolute -bottom-2 -left-2 w-6 h-6 bg-cyan-400 rounded-full blur-sm opacity-70"></div>
                        </div>
                    </div>
                    <label class="block text-sm font-bold text-gray-600 mb-3 uppercase tracking-wider">Current Lesson</label>
                    <div class="relative w-full max-w-2xl">
                        <div class="absolute inset-0 bg-gradient-to-r from-pink-400 via-rose-400 to-cyan-400 rounded-2xl blur-lg opacity-50"></div>
                        <input type="text" 
                               id="lesson_display" 
                               name="lesson_display"
                               value="{{ isset($lesson) && $lesson ? $lesson->title : '' }}"
                               readonly
                               class="relative w-full bg-white/90 backdrop-blur-sm border-3 border-transparent bg-clip-padding rounded-2xl px-6 py-4 text-2xl font-bold text-center lesson-title-display shadow-xl cursor-default text-gray-600">
                    </div>
                </div>
            </div>
        </div>
    @endif

    @php
        // Initialize availableGames array (will be populated if games exist)
        $availableGames = [];
        
        // Debug: Log what we received from controller
        \Log::info('Student Games View - Initial state', [
            'selectedLessonId' => $selectedLessonId ?? 'NOT SET',
            'gamesInOrder_count' => isset($gamesInOrder) ? count($gamesInOrder) : 'NOT SET',
            'has_lesson' => isset($lesson),
            'has_student' => isset($student)
        ]);
    @endphp

    @if(isset($selectedLessonId) && $selectedLessonId)
    <!-- All games appear here one after another when a lesson is selected -->
    
    @php
        // Initialize variables
        $clockGame = null;
        $wordSearchGame = null;
        $scrambledClocksGame = null;
        $wordClockArrangementGame = null;
        $matchingPairsGame = null;
        $scramblePairs = collect();
        $mcqPairs = collect();
        $hasMcqPairs = false;
        $hasScramblePairs = false;
        
        $gameIndex = 0;
        $clockGameIndex = null;
        $wordSearchGameIndex = null;
        $scrambledClocksGameIndex = null;
        $wordClockGameIndex = null;
        $mcqGameIndex = null;
        $scrambleGameIndex = null;
        $matchingPairsGameIndex = null;
        
        // Initialize game tracking variables if not set
        $gameTypeToGameIdMap = $gameTypeToGameIdMap ?? [];
        $completedGameIds = $completedGameIds ?? [];
        
        // Helper function to check if a game is completed
        $checkGameCompleted = function($gameType) use ($gameTypeToGameIdMap, $completedGameIds) {
            $mapKey = $gameType;
            if ($gameType === 'scrambled_clocks') $mapKey = 'scrambledclocks';
            elseif ($gameType === 'word_clock_arrangement') $mapKey = 'wordclock';
            elseif ($gameType === 'word_search') $mapKey = 'wordsearch';
            elseif ($gameType === 'matching_pairs') $mapKey = 'matchingpairs';
            
            if (!isset($gameTypeToGameIdMap[$mapKey])) {
                return false;
            }
            return in_array($gameTypeToGameIdMap[$mapKey], $completedGameIds);
        };
        
        // Populate game variables from gamesInOrder if it exists and is not empty
        if (isset($gamesInOrder) && !empty($gamesInOrder)) {
            foreach ($gamesInOrder as $gameData) {
            $gameType = $gameData['type'];
            
            if ($gameType === 'clock' && isset($gameData['game'])) {
                $clockGame = $gameData['game'];
                $clockGameIndex = $gameIndex;
                $gameIndex++;
            } elseif ($gameType === 'word_search' && isset($gameData['game'])) {
                $wordSearchGame = $gameData['game'];
                $wordSearchGameIndex = $gameIndex;
                \Log::info('Student Games View - Word Search game found', [
                    'wordSearchGameIndex' => $wordSearchGameIndex,
                    'gameIndex' => $gameIndex
                ]);
                $gameIndex++;
            } elseif ($gameType === 'scrambled_clocks' && isset($gameData['game'])) {
                $scrambledClocksGame = $gameData['game'];
                $scrambledClocksGameIndex = $gameIndex;
                $gameIndex++;
            } elseif ($gameType === 'word_clock_arrangement' && isset($gameData['game'])) {
                $wordClockArrangementGame = $gameData['game'];
                $wordClockGameIndex = $gameIndex;
                $gameIndex++;
            } elseif ($gameType === 'matching_pairs' && isset($gameData['game'])) {
                $matchingPairsGame = $gameData['game'];
                $matchingPairsGameIndex = $gameIndex;
                $gameIndex++;
            } elseif ($gameType === 'scramble' && isset($gameData['pairs'])) {
                $scramblePairs = $gameData['pairs'];
                $scrambleGameIndex = $gameIndex;
                $gameIndex++;
            } elseif ($gameType === 'mcq' && isset($gameData['pairs'])) {
                $mcqPairs = $gameData['pairs'];
                $mcqGameIndex = $gameIndex;
                $gameIndex++;
            }
            }
        }
        
        // Set boolean flags for easier checking in the view
        $hasMcqPairs = $mcqPairs->isNotEmpty();
        $hasScramblePairs = $scramblePairs->isNotEmpty();
        
        // Build availableGames array for JavaScript (in the order they appear)
        if (isset($gamesInOrder) && !empty($gamesInOrder)) {
            \Log::info('Student Games View - Building availableGames from gamesInOrder', [
                'gamesInOrder_count' => count($gamesInOrder)
            ]);
            foreach ($gamesInOrder as $index => $gameData) {
                $gameType = $gameData['type'] ?? 'unknown';
                $isCompleted = $checkGameCompleted($gameType);
                
                // Map game type names for JavaScript
                $jsGameType = $gameType;
                if ($gameType === 'scrambled_clocks') $jsGameType = 'scrambledclocks';
                elseif ($gameType === 'word_clock_arrangement') $jsGameType = 'wordclock';
                elseif ($gameType === 'word_search') $jsGameType = 'wordsearch';
                elseif ($gameType === 'matching_pairs') $jsGameType = 'matchingpairs';
                
                $availableGames[] = [
                    'type' => $jsGameType,
                    'index' => $index,
                    'completed' => $isCompleted
                ];
                
                \Log::info('Student Games View - Added game to availableGames', [
                    'index' => $index,
                    'type' => $gameType,
                    'jsType' => $jsGameType,
                    'completed' => $isCompleted
                ]);
            }
        } else {
            \Log::warning('Student Games View - gamesInOrder is empty or not set', [
                'isset' => isset($gamesInOrder),
                'empty' => isset($gamesInOrder) ? empty($gamesInOrder) : 'N/A'
            ]);
        }
        
        \Log::info('Student Games View - Final availableGames', [
            'count' => count($availableGames),
            'games' => $availableGames
        ]);
    @endphp
    
    <!-- 1. Clock Game -->
    @if(isset($clockGame) && $clockGame && !empty($clockGame->words))
        @php
            $clockWords = is_array($clockGame->words) ? $clockGame->words : [];
        @endphp
        @if(!empty($clockWords))
            <div class="game-container max-w-7xl mx-auto mb-10" data-game-type="clock" data-game-index="{{ $clockGameIndex }}" style="display: none;">
                <div class="relative">
                    <!-- Animated Background Glow -->
                    <div class="absolute inset-0 bg-gradient-to-br from-pink-400/20 via-rose-400/20 to-cyan-400/20 rounded-3xl blur-2xl transform scale-110"></div>
                    
                    <!-- Main Game Card -->
                    <div class="relative bg-gradient-to-br from-white via-pink-50/50 to-rose-50/30 backdrop-blur-xl rounded-3xl shadow-2xl p-8 md:p-12 border-2 border-pink-200/50 overflow-hidden">
                        <!-- Decorative Top Border -->
                        <div class="absolute top-0 left-0 right-0 h-2 bg-gradient-to-r from-pink-400 via-rose-400 to-cyan-400"></div>
                        
                        <!-- Game Header -->
                        <div class="mb-8 text-center">
                            <div class="inline-flex items-center justify-center w-16 h-16 bg-gradient-to-br from-pink-500 to-rose-500 rounded-2xl shadow-lg mb-4 transform hover:rotate-12 transition-transform duration-300">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            @if(isset($lesson))
                                <h2 class="text-3xl md:text-4xl font-extrabold mb-3 bg-gradient-to-r from-pink-600 via-rose-600 to-cyan-600 bg-clip-text text-transparent" dir="rtl">{{ $lesson->title }}</h2>
                            @endif
                            <h3 class="text-2xl md:text-3xl font-bold text-gray-800 mb-2">Clock Game</h3>
                            <p class="text-gray-600">Match the time on the clock with the Arabic word</p>
                        </div>

                        <!-- Clock Game Words Display with Enhanced Styling -->
                        <div class="flex flex-wrap justify-center gap-8 items-start">
                            @foreach($clockWords as $index => $word)
                                <div class="clock-game-word-item flex flex-col items-center transform hover:scale-110 transition-all duration-300" style="animation-delay: {{ $index * 0.1 }}s;">
                                    <!-- Clock Container with Glow -->
                                    <div class="relative mb-4">
                                        <div class="absolute inset-0 bg-gradient-to-br from-pink-400 to-rose-400 rounded-full blur-md opacity-50 transform scale-110"></div>
                                        <svg width="120" height="120" class="clock-svg relative z-10 transform hover:rotate-12 transition-transform duration-500">
                                            <defs>
                                                <linearGradient id="clockGradient{{ $index }}" x1="0%" y1="0%" x2="100%" y2="100%">
                                                    <stop offset="0%" style="stop-color:#f9a8d4;stop-opacity:1" />
                                                    <stop offset="100%" style="stop-color:#67e8f9;stop-opacity:1" />
                                                </linearGradient>
                                            </defs>
                                            <circle cx="60" cy="60" r="55" fill="url(#clockGradient{{ $index }})" stroke="white" stroke-width="3" class="drop-shadow-lg"/>
                                            <circle cx="60" cy="60" r="50" fill="white" stroke="#e5e7eb" stroke-width="2"/>
                                            <!-- Clock numbers -->
                                            @for($i = 1; $i <= 12; $i++)
                                                @php
                                                    $angle = ($i - 3) * 30 * M_PI / 180;
                                                    $x = 60 + 40 * cos($angle);
                                                    $y = 60 + 40 * sin($angle);
                                                @endphp
                                                <text x="{{ $x }}" y="{{ $y + 6 }}" text-anchor="middle" font-size="12" fill="#4b5563" font-weight="bold">{{ $i }}</text>
                                            @endfor
                                            <!-- Random time for each clock -->
                                            @php
                                                $hour = ($index * 2) % 12;
                                                $minute = ($index * 5) % 60;
                                                $hourAngle = (($hour % 12) * 30 + $minute * 0.5 - 90) * M_PI / 180;
                                                $hourX = 60 + 30 * cos($hourAngle);
                                                $hourY = 60 + 30 * sin($hourAngle);
                                                $minuteAngle = ($minute * 6 - 90) * M_PI / 180;
                                                $minuteX = 60 + 42 * cos($minuteAngle);
                                                $minuteY = 60 + 42 * sin($minuteAngle);
                                            @endphp
                                            <line x1="60" y1="60" x2="{{ $hourX }}" y2="{{ $hourY }}" stroke="#1f2937" stroke-width="4" stroke-linecap="round" class="drop-shadow"/>
                                            <line x1="60" y1="60" x2="{{ $minuteX }}" y2="{{ $minuteY }}" stroke="#1f2937" stroke-width="3" stroke-linecap="round" class="drop-shadow"/>
                                            <circle cx="60" cy="60" r="4" fill="#1f2937"/>
                                        </svg>
                                    </div>
                                    <!-- Animated Arrow -->
                                    <div class="text-3xl mb-2 text-pink-500 transform animate-bounce">↓</div>
                                    <!-- Word Card with Gradient -->
                                    <div class="relative group">
                                        <div class="absolute inset-0 bg-gradient-to-r from-pink-400 to-rose-400 rounded-xl blur opacity-50 group-hover:opacity-75 transition-opacity"></div>
                                        <div class="relative word-text text-lg font-bold text-gray-800 px-5 py-3 bg-gradient-to-br from-white to-pink-50 rounded-xl border-2 border-pink-200 shadow-lg transform group-hover:shadow-xl transition-all duration-300" dir="rtl">
                                            {{ $word }}
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        @endif
    @endif

    <!-- 2. Word Search Puzzle -->
    @if(isset($wordSearchGame) && $wordSearchGame && !empty($wordSearchGame->grid_data))
        @php
            $gridData = is_array($wordSearchGame->grid_data) ? $wordSearchGame->grid_data : json_decode($wordSearchGame->grid_data, true);
            $grid = $gridData['grid'] ?? [];
            $wordPositions = $gridData['word_positions'] ?? [];
            $gridSize = $gridData['size'] ?? 10;
            $wordsRaw = is_array($wordSearchGame->words) ? $wordSearchGame->words : [];
            
            // Clean words - remove ALL non-Arabic characters (numbers, hyphens, spaces, etc.)
            // Keep ONLY Arabic letters
            $words = [];
            foreach ($wordsRaw as $originalWord) {
                $word = trim((string)$originalWord);
                // Method 1: Remove everything that is NOT an Arabic character using Unicode property
                $cleaned = preg_replace('/[^\p{Arabic}]/u', '', $word);
                
                // Method 2: If Method 1 didn't work, use explicit Unicode range
                if (empty($cleaned) || $cleaned === $word) {
                    $cleaned = preg_replace('/[^\x{0600}-\x{06FF}]/u', '', $word);
                }
                
                // Method 3: Character by character if still not working
                if (empty($cleaned) || $cleaned === $word) {
                    $cleaned = '';
                    $length = mb_strlen($word, 'UTF-8');
                    for ($i = 0; $i < $length; $i++) {
                        $char = mb_substr($word, $i, 1, 'UTF-8');
                        $code = mb_ord($char, 'UTF-8');
                        // Arabic Unicode range: 0x0600-0x06FF
                        if ($code >= 1536 && $code <= 1791) {
                            $cleaned .= $char;
                        }
                    }
                }
                
                // Ensure we have a cleaned word
                $cleaned = trim($cleaned);
                if (!empty($cleaned)) {
                    $words[] = $cleaned;
                } else {
                    // Final fallback: try one more time with simpler regex
                    $cleaned = preg_replace('/[0-9\s\-\.,;:!?()\[\]{}|\\\/\*\+<>=_~`@#$%^&]/u', '', $word);
                    $words[] = trim($cleaned) ?: $word;
                }
            }
            
            // Also clean wordPositions array to use cleaned words for matching
            foreach ($wordPositions as &$wp) {
                if (isset($wp['word'])) {
                    $cleaned = preg_replace('/[^\p{Arabic}]/u', '', $wp['word']);
                    $wp['word'] = !empty($cleaned) ? $cleaned : $wp['word'];
                }
            }
            unset($wp); // Unset reference
        @endphp
        @if(!empty($grid) && !empty($words))
            <div class="game-container max-w-7xl mx-auto mb-10" data-game-type="wordsearch" data-game-index="{{ $wordSearchGameIndex ?? 0 }}" style="display: none;" dir="rtl">
                <div class="relative">
                    <!-- Animated Background Glow -->
                    <div class="absolute inset-0 bg-gradient-to-br from-purple-400/20 via-pink-400/20 to-rose-400/20 rounded-3xl blur-2xl transform scale-110"></div>
                    
                    <!-- Main Game Card -->
                    <div class="relative bg-gradient-to-br from-white via-purple-50/50 to-pink-50/30 backdrop-blur-xl rounded-3xl shadow-2xl p-8 md:p-12 border-2 border-purple-200/50 overflow-hidden">
                        <!-- Decorative Top Border -->
                        <div class="absolute top-0 left-0 right-0 h-2 bg-gradient-to-r from-purple-400 via-pink-400 to-rose-400"></div>
                        
                        <!-- Game Header -->
                        <div class="mb-8 text-center">
                            <div class="inline-flex items-center justify-center w-16 h-16 bg-gradient-to-br from-purple-500 to-pink-500 rounded-2xl shadow-lg mb-4 transform hover:rotate-12 transition-transform duration-300">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7" />
                                </svg>
                            </div>
                            @if(isset($lesson))
                                <h2 class="text-3xl md:text-4xl font-extrabold mb-3 bg-gradient-to-r from-purple-600 via-pink-600 to-rose-600 bg-clip-text text-transparent" dir="rtl">{{ $lesson->title }}</h2>
                            @endif
                            <h3 class="text-2xl md:text-3xl font-bold text-gray-800 mb-2">Word Search Puzzle</h3>
                            @if(!empty($wordSearchGame->title))
                                <p class="text-xl font-semibold text-purple-600 mb-4" dir="rtl" style="direction: rtl; text-align: right;">
                                    <strong>عنوان:</strong> {{ $wordSearchGame->title }}
                                </p>
                            @endif
                            <p class="text-gray-600">Find all the hidden words in the grid</p>
                        </div>

                <div class="flex flex-col lg:flex-row gap-8">
                    <!-- Word Search Grid -->
                    <div class="flex-1">
                        <div id="wordSearchGrid" class="inline-block border-2 border-gray-300 bg-white p-2 rounded-lg" style="direction: ltr;">
                            @for($row = 0; $row < $gridSize; $row++)
                                <div class="flex">
                                    @for($col = 0; $col < $gridSize; $col++)
                                        <div class="word-search-cell w-10 h-10 border border-gray-200 flex items-center justify-center cursor-pointer text-lg font-semibold select-none transition-all hover:bg-blue-50" 
                                             data-row="{{ $row }}" 
                                             data-col="{{ $col }}"
                                             data-letter="{{ $grid[$row][$col] ?? '' }}"
                                             dir="rtl">
                                            {{ $grid[$row][$col] ?? '' }}
                                        </div>
                                    @endfor
                                </div>
                            @endfor
                        </div>
                    </div>

                    <!-- Words List -->
                    <div class="lg:w-64">
                        <h4 class="text-xl font-bold mb-4" dir="rtl">الكلمات المطلوبة:</h4>
                        <div id="wordsList" class="space-y-2" dir="rtl">
                            @foreach($words as $index => $word)
                                <div class="word-item p-3 border-2 border-gray-300 rounded-lg bg-white transition-all flex items-center gap-2" 
                                     data-word="{{ $word }}" 
                                     data-word-index="{{ $index }}"
                                     dir="rtl"
                                     style="direction: rtl;">
                                    <div class="word-color-box w-6 h-6 rounded border-2 border-gray-400 flex-shrink-0" style="background-color: transparent;"></div>
                                    <span class="font-semibold text-lg flex-1" dir="rtl" style="text-align: right; direction: rtl;">{{ $word }}</span>
                                    <span class="found-indicator hidden text-pink-600 font-bold" style="font-size: 1.5rem;">✓</span>
                                </div>
                            @endforeach
                        </div>
                        <!-- Completion Message Overlay (centered on screen) -->
                        <div id="completionMessageOverlay" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden" style="animation: fadeIn 0.3s ease;">
                            <div id="completionMessage" class="bg-gradient-to-br from-pink-100 to-purple-100 border-4 border-pink-300 rounded-3xl p-8 text-center shadow-2xl" dir="rtl" style="max-width: 500px; transform: scale(0); opacity: 0;">
                                <div class="text-6xl mb-4" style="animation: bounce 1s ease infinite 0.7s;">🎉</div>
                                <p class="text-pink-800 font-bold text-2xl mb-4">أحسنت! لقد وجدت جميع الكلمات!</p>
                                <div id="finalScore" class="text-4xl font-bold text-pink-600 mb-4" style="animation: pulse 1.5s ease infinite 1s; text-shadow: 2px 2px 4px rgba(0,0,0,0.2);"></div>
                                <button onclick="closeCompletionOverlay()" class="mt-4 px-6 py-2 bg-pink-500 text-white rounded-lg hover:bg-pink-600 transition-colors font-semibold shadow-lg">موافق</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
                </div> <!-- /Main Game Card -->
            </div> <!-- /relative wrapper -->
        </div> <!-- /wordsearch game-container -->
        @endif
    @endif

    <!-- 3. Scrambled Clocks Game -->
    @if(isset($scrambledClocksGame) && $scrambledClocksGame && $scrambledClocksGame->game_data)
        @php
            $gameData = is_string($scrambledClocksGame->game_data) ? json_decode($scrambledClocksGame->game_data, true) : $scrambledClocksGame->game_data;
            $words = $gameData['words'] ?? [];
            $correctSentence = $gameData['sentence'] ?? '';
        @endphp
        @if(!empty($words))
            <div class="game-container max-w-6xl mx-auto bg-white/70 backdrop-blur-xl rounded-3xl shadow-2xl p-10 border border-pink-100 mb-8" data-game-type="scrambledclocks" data-game-index="{{ $scrambledClocksGameIndex }}" style="display: none;">
                <div class="mb-6">
                    @if(isset($lesson))
                        <h2 class="text-3xl font-bold text-pink-600 mb-2" style="text-decoration: underline;" dir="rtl">{{ $lesson->title }}</h2>
                    @endif
                    <p class="text-lg text-gray-700 mb-6" dir="rtl">
                        أيها الفتيات المؤمنات، يجب عليكن إعادة ترتيب معنى "{{ $lesson->title ?? 'الدرس' }}" بحسب توقيت الساعات من الأدنى إلى الأعلى.
                    </p>
                </div>

                <!-- Clocks and Words Section -->
                <div id="clocksContainer" class="mb-8 flex flex-wrap justify-center gap-8 items-start">
                    @php
                        // Shuffle the words array to display in random order
                        $shuffledWords = $words;
                        shuffle($shuffledWords);
                    @endphp
                    @foreach($shuffledWords as $index => $wordData)
                        <div class="clock-word-item flex flex-col items-center cursor-move" 
                             data-original-index="{{ array_search($wordData, $words) }}" 
                             data-hour="{{ $wordData['hour'] }}" 
                             data-minute="{{ $wordData['minute'] }}" 
                             data-word="{{ $wordData['word'] }}"
                             draggable="true">
                            <svg width="100" height="100" class="clock-svg mb-2">
                                <circle cx="50" cy="50" r="45" fill="white" stroke="#333" stroke-width="2"/>
                                <!-- Clock numbers -->
                                @for($i = 1; $i <= 12; $i++)
                                    @php
                                        $angle = ($i - 3) * 30 * M_PI / 180;
                                        $x = 50 + 35 * cos($angle);
                                        $y = 50 + 35 * sin($angle);
                                    @endphp
                                    <text x="{{ $x }}" y="{{ $y + 5 }}" text-anchor="middle" font-size="10" fill="#333">{{ $i }}</text>
                                @endfor
                                <!-- Hour hand -->
                                @php
                                    $hourAngle = (($wordHour % 12) * 30 + $wordMinute * 0.5 - 90) * M_PI / 180;
                                    $hourX = 50 + 25 * cos($hourAngle);
                                    $hourY = 50 + 25 * sin($hourAngle);
                                @endphp
                                <line x1="50" y1="50" x2="{{ $hourX }}" y2="{{ $hourY }}" stroke="#333" stroke-width="3" stroke-linecap="round"/>
                                <!-- Minute hand -->
                                @php
                                    $minuteAngle = ($wordMinute * 6 - 90) * M_PI / 180;
                                    $minuteX = 50 + 35 * cos($minuteAngle);
                                    $minuteY = 50 + 35 * sin($minuteAngle);
                                @endphp
                                <line x1="50" y1="50" x2="{{ $minuteX }}" y2="{{ $minuteY }}" stroke="#333" stroke-width="2" stroke-linecap="round"/>
                                <!-- Center dot -->
                                <circle cx="50" cy="50" r="3" fill="#333"/>
                            </svg>
                            <!-- Arrow pointing down -->
                            <div class="text-2xl mb-1">↓</div>
                            <!-- Word -->
                            <div class="word-text text-lg font-semibold text-gray-800 px-3 py-2 bg-pink-50 rounded border border-pink-200" dir="rtl">{{ $wordText }}</div>
                        </div>
                    @endforeach
                </div>

                <!-- Sentence Display Area -->
                <div class="mb-6 p-4 bg-gray-50 rounded-lg border border-gray-200">
                    <label class="block font-semibold mb-2 text-gray-700" dir="rtl">معنى {{ $lesson->title ?? 'الدرس' }}:</label>
                    <div id="arrangedSentence" class="text-xl font-semibold text-gray-800 min-h-[50px] p-3 bg-white rounded border-2 border-dashed border-gray-300 flex flex-wrap gap-2 items-center" dir="rtl">
                        <span class="text-gray-400 italic">قم بترتيب الساعات من الأصغر إلى الأكبر...</span>
                    </div>
                </div>

                <!-- Check Answer Button -->
                <div class="flex justify-center">
                    <button id="checkAnswerBtn" class="px-8 py-3 rounded-lg bg-green-500 text-white font-bold text-lg hover:bg-green-600 transition-colors">
                        تحقق من الإجابة
                    </button>
                </div>

                <!-- Result Message -->
                <div id="resultMessage" class="mt-6 hidden p-4 rounded-lg text-center text-lg font-semibold"></div>
            </div>
        @endif
    @endif

    <!-- 4. Word Clock Arrangement Game -->
    @if(isset($wordClockArrangementGame) && $wordClockArrangementGame && !empty($wordClockArrangementGame->game_data))
        @php
            // Parse game_data - handle both string and array formats
            $wordClockGameData = $wordClockArrangementGame->game_data;
            if (is_string($wordClockGameData)) {
                $wordClockGameData = json_decode($wordClockGameData, true);
                if (json_last_error() !== JSON_ERROR_NONE) {
                    $wordClockGameData = [];
                }
            }
            
            // Ensure it's an array
            if (!is_array($wordClockGameData)) {
                $wordClockGameData = [];
            }
            
            $wordClockWords = $wordClockGameData['words'] ?? [];
            $wordClockWord = $wordClockGameData['word'] ?? '';
            $wordClockSentence = $wordClockGameData['full_sentence'] ?? '';
            $wordClockCorrectOrder = $wordClockGameData['correct_order'] ?? [];
            
            // Ensure words is an array
            if (!is_array($wordClockWords)) {
                $wordClockWords = [];
            }
        @endphp
        @if(!empty($wordClockWords) && is_array($wordClockWords) && count($wordClockWords) > 0)
            <div class="game-container max-w-6xl mx-auto bg-white/70 backdrop-blur-xl rounded-3xl shadow-2xl p-10 border border-pink-100 mb-8" data-game-type="wordclock" data-game-index="{{ $wordClockGameIndex }}" style="display: none;" dir="rtl">
                <div class="mb-6">
                    @if(isset($lesson))
                        <h2 class="text-3xl font-bold text-pink-600 mb-2" style="text-decoration: underline;">{{ $lesson->lesson_id }}- {{ $wordClockWord }}</h2>
                    @endif
                    <p class="text-lg text-gray-700 mb-6" dir="rtl">
                        عليكن أيتها الفتيات المؤمنات إعادة ترتيب معنى "{{ $wordClockWord }}" بحسب توقيت الساعات من الأدنى إلى الأعلى.
                    </p>
                </div>

                <!-- Clocks and Words Section -->
                <div id="wordClockArrangementContainer" class="mb-8 flex flex-wrap justify-center gap-8 items-start">
                    @php
                        // Shuffle the words array to display in random order
                        $shuffledWordClockWords = $wordClockWords;
                        shuffle($shuffledWordClockWords);
                    @endphp
                    @foreach($shuffledWordClockWords as $index => $wordData)
                        @php
                            // Ensure wordData is an array and has required fields
                            if (!is_array($wordData)) {
                                continue;
                            }
                            $wordText = $wordData['word'] ?? '';
                            $wordHour = isset($wordData['hour']) ? (int)$wordData['hour'] : 0;
                            $wordMinute = isset($wordData['minute']) ? (int)$wordData['minute'] : 0;
                            
                            // Skip if word is empty
                            if (empty($wordText)) {
                                continue;
                            }
                            
                            // Find original index
                            $originalIndex = 0;
                            foreach ($wordClockWords as $idx => $w) {
                                if (is_array($w) && isset($w['word']) && $w['word'] === $wordText) {
                                    $originalIndex = $idx;
                                    break;
                                }
                            }
                        @endphp
                        @if(!empty($wordText))
                        <div class="word-clock-arrangement-item flex flex-col items-center cursor-move" 
                             data-original-index="{{ $originalIndex }}" 
                             data-hour="{{ $wordHour }}" 
                             data-minute="{{ $wordMinute }}" 
                             data-word="{{ $wordText }}"
                             draggable="true">
                            <svg width="100" height="100" class="clock-svg mb-2">
                                <circle cx="50" cy="50" r="45" fill="white" stroke="#333" stroke-width="2"/>
                                <!-- Clock numbers -->
                                @for($i = 1; $i <= 12; $i++)
                                    @php
                                        $angle = ($i - 3) * 30 * M_PI / 180;
                                        $x = 50 + 35 * cos($angle);
                                        $y = 50 + 35 * sin($angle);
                                    @endphp
                                    <text x="{{ $x }}" y="{{ $y + 5 }}" text-anchor="middle" font-size="10" fill="#333">{{ $i }}</text>
                                @endfor
                                <!-- Hour hand -->
                                @php
                                    $hourAngle = (($wordHour % 12) * 30 + $wordMinute * 0.5 - 90) * M_PI / 180;
                                    $hourX = 50 + 25 * cos($hourAngle);
                                    $hourY = 50 + 25 * sin($hourAngle);
                                @endphp
                                <line x1="50" y1="50" x2="{{ $hourX }}" y2="{{ $hourY }}" stroke="#333" stroke-width="3" stroke-linecap="round"/>
                                <!-- Minute hand -->
                                @php
                                    $minuteAngle = ($wordMinute * 6 - 90) * M_PI / 180;
                                    $minuteX = 50 + 35 * cos($minuteAngle);
                                    $minuteY = 50 + 35 * sin($minuteAngle);
                                @endphp
                                <line x1="50" y1="50" x2="{{ $minuteX }}" y2="{{ $minuteY }}" stroke="#333" stroke-width="2" stroke-linecap="round"/>
                                <!-- Center dot -->
                                <circle cx="50" cy="50" r="3" fill="#333"/>
                            </svg>
                            <!-- Arrow pointing down -->
                            <div class="text-2xl mb-1">↓</div>
                            <!-- Word -->
                            <div class="word-text text-lg font-semibold text-gray-800 px-3 py-2 bg-pink-50 rounded border border-pink-200" dir="rtl">{{ $wordText }}</div>
                        </div>
                        @endif
                    @endforeach
                    
                    @php
                        $validWordsCount = 0;
                        foreach ($shuffledWordClockWords as $w) {
                            if (is_array($w) && !empty($w['word'] ?? '')) {
                                $validWordsCount++;
                            }
                        }
                    @endphp
                    @if($validWordsCount === 0)
                        <div class="w-full text-center p-8 bg-yellow-50 rounded-lg border border-yellow-300">
                            <p class="text-yellow-700 font-semibold">No valid word data found. Please check the game configuration.</p>
                        </div>
                    @endif
                </div>

                <!-- Sentence Display Area -->
                <div class="mb-6 p-4 bg-gray-50 rounded-lg border border-gray-200">
                    <label class="block font-semibold mb-2 text-gray-700" dir="rtl">معنى {{ $wordClockWord }}:</label>
                    <div id="wordClockArrangedSentence" class="text-xl font-semibold text-gray-800 min-h-[50px] p-3 bg-white rounded border-2 border-dashed border-gray-300 flex flex-wrap gap-2 items-center" dir="rtl">
                        <span class="text-gray-400 italic">قم بترتيب الساعات من الأصغر إلى الأكبر...</span>
                    </div>
                </div>

                <!-- Check Answer Button -->
                <div class="flex justify-center">
                    <button id="wordClockCheckAnswerBtn" class="px-8 py-3 rounded-lg bg-green-500 text-white font-bold text-lg hover:bg-green-600 transition-colors">
                        تحقق من الإجابة
                    </button>
                </div>

                <!-- Result Message -->
                <div id="wordClockResultMessage" class="mt-6 hidden p-4 rounded-lg text-center text-lg font-semibold"></div>
            </div>
        @else
            <!-- Debug: Show if word clock game exists but has no words -->
            @if(isset($wordClockArrangementGame) && $wordClockArrangementGame)
                <div class="game-container max-w-6xl mx-auto bg-yellow-50/70 backdrop-blur-xl rounded-3xl shadow-2xl p-10 border border-yellow-300 mb-8" data-game-type="wordclock" data-game-index="{{ $wordClockGameIndex }}" style="display: none;" dir="rtl">
                    <div class="text-center">
                        <h3 class="text-2xl font-bold text-yellow-800 mb-3">Word Clock Arrangement Game</h3>
                        <p class="text-lg text-yellow-700 mb-4">No game content available. The game data may be missing or incomplete.</p>
                        <p class="text-sm text-yellow-600">Game Data Status: {{ !empty($wordClockArrangementGame->game_data) ? 'Present' : 'Missing' }}</p>
                        @if(!empty($wordClockArrangementGame->game_data))
                            <p class="text-sm text-yellow-600 mt-2">Words Count: {{ is_array($wordClockWords) ? count($wordClockWords) : 'Not an array' }}</p>
                        @endif
                    </div>
                </div>
            @endif
        @endif
    @endif

    <!-- Multiple Choice Game - Show only when lesson is selected and has MCQ pairs -->
    @if(isset($selectedLessonId) && $selectedLessonId && $hasMcqPairs)
        <div class="game-container max-w-6xl mx-auto mb-8" data-game-type="mcq" data-game-index="{{ $mcqGameIndex }}" style="display: none;">
            <h2 class="text-2xl font-bold mb-6">Multiple Choice Game</h2>
            @if (!empty($error))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">{{ $error }}</div>
            @else
                <div id="mcqQuizProgress" class="mb-6 flex gap-2"></div>
                <div id="mcqQuizArea" data-route="{{ route('student.games.quiz') }}" data-lesson-id="{{ $selectedLessonId ?? '' }}" data-game-type="mcq" data-save-score-route="{{ route('student.games.saveScore') }}"></div>
            @endif
        </div>
    @endif

    <!-- Scrambled Letters Game - Show only when lesson is selected and has Scrambled Letters pairs -->
    @if(isset($selectedLessonId) && $selectedLessonId && $hasScramblePairs)
        <div class="game-container max-w-6xl mx-auto mb-8" data-game-type="scramble" data-game-index="{{ $scrambleGameIndex }}" style="display: none;">
            <h2 class="text-2xl font-bold mb-6">Scrambled Letters Game</h2>
            @if (!empty($error))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">{{ $error }}</div>
            @else
                <div id="scrambleQuizProgress" class="mb-6 flex gap-2"></div>
                <div id="scrambleQuizArea" data-route="{{ route('student.games.quiz') }}" data-lesson-id="{{ $selectedLessonId ?? '' }}" data-game-type="scramble" data-save-score-route="{{ route('student.games.saveScore') }}"></div>
            @endif
        </div>
    @endif

    <!-- Matching Pairs Game -->
    @if(isset($matchingPairsGame) && $matchingPairsGame && $matchingPairsGame->pairs->count() > 0)
        <div class="game-container max-w-6xl mx-auto bg-white/70 backdrop-blur-xl rounded-3xl shadow-2xl p-10 border border-pink-100 mb-8" data-game-type="matchingpairs" data-game-index="{{ $matchingPairsGameIndex }}" style="display: none;">
            <div class="mb-6">
                @if(isset($lesson))
                    <h2 class="text-3xl font-bold text-pink-600 mb-2" style="text-decoration: underline;" dir="rtl">{{ $lesson->title }}</h2>
                @endif
                <h3 class="text-2xl font-bold mb-4">Matching Pairs Game</h3>
                @if(!empty($matchingPairsGame->title))
                    <p class="text-xl font-semibold text-purple-600 mb-4" dir="rtl">
                        <strong>عنوان:</strong> {{ $matchingPairsGame->title }}
                    </p>
                @endif
            </div>

            <div id="matchingPairsGameArea" class="matching-pairs-game">
                <div class="mb-4 flex justify-between items-center">
                    <div class="text-lg font-semibold text-gray-700">
                        <span id="matchingPairsScore">Pairs Matched: 0</span> / <span id="matchingPairsTotal">{{ $matchingPairsGame->pairs->count() }}</span>
                    </div>
                    <div class="flex gap-3">
                        <button id="submitMatchingPairsBtn" class="px-4 py-2 bg-green-500 text-white rounded-lg hover:bg-green-600 transition-colors font-semibold">Submit Answers</button>
                        <button id="resetMatchingPairsBtn" class="px-4 py-2 bg-purple-500 text-white rounded-lg hover:bg-purple-600 transition-colors font-semibold">Reset</button>
                    </div>
                </div>

                <div class="relative mb-6" id="matchingPairsContainer">
                    <div class="grid grid-cols-2 gap-8 md:gap-12 lg:gap-16 xl:gap-20">
                        <!-- Left Column -->
                        <div class="left-column flex flex-col">
                            <h4 class="text-xl font-bold mb-6 text-center text-pink-600">Left Column</h4>
                            <div id="leftItems" class="flex flex-col gap-5 flex-1">
                                @php
                                    $leftItems = $matchingPairsGame->pairs->shuffle();
                                @endphp
                                @foreach($leftItems as $index => $pair)
                                    <div class="matching-item left-item bg-gradient-to-br from-pink-50 to-purple-50 border-2 border-pink-300 rounded-xl p-4 cursor-pointer transition-all hover:shadow-lg hover:scale-105 shadow-md" 
                                         data-pair-id="{{ $pair->matching_pair_id }}"
                                         data-index="{{ $index }}">
                                        <div class="flex items-center gap-3">
                                            @if($pair->left_item_image)
                                                <img src="{{ asset('storage/' . $pair->left_item_image) }}" alt="Left item" class="w-20 h-20 object-cover rounded-lg border-2 border-pink-400 flex-shrink-0">
                                            @endif
                                            @if($pair->left_item_text)
                                                <span class="text-lg font-semibold text-gray-800 flex-1 text-center" dir="rtl">{{ $pair->left_item_text }}</span>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <!-- Right Column -->
                        <div class="right-column flex flex-col">
                            <h4 class="text-xl font-bold mb-6 text-center text-purple-600">Right Column</h4>
                            <div id="rightItems" class="flex flex-col gap-5 flex-1">
                                @php
                                    $rightItems = $matchingPairsGame->pairs->shuffle();
                                @endphp
                                @foreach($rightItems as $index => $pair)
                                    <div class="matching-item right-item bg-gradient-to-br from-purple-50 to-pink-50 border-2 border-purple-300 rounded-xl p-4 cursor-pointer transition-all hover:shadow-lg hover:scale-105 shadow-md" 
                                         data-pair-id="{{ $pair->matching_pair_id }}"
                                         data-index="{{ $index }}">
                                        <div class="flex items-center gap-3">
                                            @if($pair->right_item_image)
                                                <img src="{{ asset('storage/' . $pair->right_item_image) }}" alt="Right item" class="w-20 h-20 object-cover rounded-lg border-2 border-purple-400 flex-shrink-0">
                                            @endif
                                            @if($pair->right_item_text)
                                                <span class="text-lg font-semibold text-gray-800 flex-1 text-center" dir="rtl">{{ $pair->right_item_text }}</span>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <!-- Connection Canvas -->
                    <svg id="connectionCanvas" class="absolute top-0 left-0 w-full h-full pointer-events-none" style="z-index: 1; overflow: visible;"></svg>
                </div>

                <!-- Completion Message -->
                <div id="matchingPairsCompletionMessage" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
                    <div class="bg-gradient-to-br from-pink-100 to-purple-100 border-4 border-pink-300 rounded-3xl p-8 text-center shadow-2xl" dir="rtl" style="max-width: 500px;">
                        <div class="text-6xl mb-4">🎉</div>
                        <p class="text-pink-800 font-bold text-2xl mb-4">أحسنت! لقد أكملت جميع المطابقات!</p>
                        <div id="matchingPairsFinalScore" class="text-4xl font-bold text-pink-600 mb-4"></div>
                        <button onclick="closeMatchingPairsCompletion()" class="mt-4 px-6 py-2 bg-pink-500 text-white rounded-lg hover:bg-pink-600 transition-colors font-semibold shadow-lg">موافق</button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    @if(isset($matchingPairsGame) && $matchingPairsGame && $matchingPairsGame->pairs->count() > 0)
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Matching Pairs Game JavaScript
        const matchingPairsGameArea = document.getElementById('matchingPairsGameArea');
        if (matchingPairsGameArea) {
            let selectedLeftItem = null;
            let selectedRightItem = null;
            let lockedPairs = new Map(); // Store locked pairs: leftPairId -> rightPairId
            let lockedLeftItems = new Set(); // Track which left items are locked
            let lockedRightItems = new Set(); // Track which right items are locked
            let totalPairs = {{ $matchingPairsGame->pairs->count() }};
            let isSubmitted = false;
            const connectionCanvas = document.getElementById('connectionCanvas');
            const gridContainer = connectionCanvas ? connectionCanvas.parentElement : null;
            
            // Function to get connection point position for an element relative to the grid container
            function getConnectionPoint(element, isLeftItem) {
                if (!gridContainer) return { x: 0, y: 0 };
                const gridRect = gridContainer.getBoundingClientRect();
                const elementRect = element.getBoundingClientRect();
                const relativeTop = elementRect.top - gridRect.top;
                const relativeLeft = elementRect.left - gridRect.left;
                const centerY = relativeTop + elementRect.height / 2;
                
                // Get computed border width to account for it
                const computedStyle = window.getComputedStyle(element);
                const borderWidth = parseFloat(computedStyle.borderWidth) || 2; // Default to 2px if not found
                
                if (isLeftItem) {
                    // For left items, get the right edge center at the OUTER edge of the border
                    // elementRect.width includes border, so we're already at the outer edge
                    return {
                        x: relativeLeft + elementRect.width,
                        y: centerY
                    };
                } else {
                    // For right items, get the left edge center at the OUTER edge of the border
                    // relativeLeft is already at the outer edge
                    return {
                        x: relativeLeft,
                        y: centerY
                    };
                }
            }
            
            // Function to update SVG canvas size
            function updateCanvasSize() {
                if (!connectionCanvas || !gridContainer) return;
                const rect = gridContainer.getBoundingClientRect();
                if (rect.width === 0 || rect.height === 0) return; // Don't update if container is hidden
                connectionCanvas.setAttribute('width', rect.width);
                connectionCanvas.setAttribute('height', rect.height);
                connectionCanvas.setAttribute('viewBox', `0 0 ${rect.width} ${rect.height}`);
            }
            
            // Function to draw a curved line between two items
            function drawLine(leftItem, rightItem, pairId) {
                if (!connectionCanvas || !gridContainer) return;
                
                // Ensure canvas is sized correctly
                updateCanvasSize();
                
                // Wait a bit to ensure the container is visible and positioned
                setTimeout(() => {
                    if (!connectionCanvas || !gridContainer) return;
                    
                    const rect = gridContainer.getBoundingClientRect();
                    if (rect.width === 0 || rect.height === 0) {
                        console.warn('Grid container has zero dimensions, cannot draw line');
                        return;
                    }
                    
                    // Remove any existing paths for this pair to avoid duplicates
                    const existingPaths = connectionCanvas.querySelectorAll(`path[data-pair-id="${pairId}"]`);
                    existingPaths.forEach(path => path.remove());
                    
                    const leftPos = getConnectionPoint(leftItem, true); // Left item: right edge
                    const rightPos = getConnectionPoint(rightItem, false); // Right item: left edge
                    
                    // Calculate exact edge positions to prevent line from entering boxes
                    const strokeWidth = 2.5;
                    const offset = 4; // Offset to keep line clearly outside the border
                    
                    // Left item: start line at right edge + offset (outside the border)
                    const startX = leftPos.x + offset;
                    // Right item: end line at left edge - offset (outside the border)
                    const endX = rightPos.x - offset;
                    const startY = leftPos.y;
                    const endY = rightPos.y;
                    
                    // Calculate control points for a smooth S-curve
                    // This creates a curved path that avoids overlapping with other lines
                    const horizontalOffset = Math.min(Math.abs(endX - startX) * 0.3, 80); // Curvature amount
                    
                    // Create control points for a smooth bezier curve
                    // Use different curvature based on vertical distance to avoid overlaps
                    const verticalDiff = Math.abs(endY - startY);
                    const curveIntensity = Math.min(verticalDiff * 0.15, 40);
                    
                    // Control point 1: slightly to the right of start, with vertical offset
                    const cp1x = startX + horizontalOffset;
                    const cp1y = startY + (endY > startY ? curveIntensity : -curveIntensity);
                    
                    // Control point 2: slightly to the left of end, with vertical offset
                    const cp2x = endX - horizontalOffset;
                    const cp2y = endY + (startY > endY ? curveIntensity : -curveIntensity);
                    
                    // Create SVG path element with cubic bezier curve
                    const path = document.createElementNS('http://www.w3.org/2000/svg', 'path');
                    const pathData = `M ${startX} ${startY} C ${cp1x} ${cp1y}, ${cp2x} ${cp2y}, ${endX} ${endY}`;
                    path.setAttribute('d', pathData);
                    path.setAttribute('stroke', '#6b7280'); // Medium grey color
                    path.setAttribute('stroke-width', strokeWidth.toString());
                    path.setAttribute('fill', 'none');
                    path.setAttribute('stroke-linecap', 'round');
                    path.setAttribute('stroke-linejoin', 'round');
                    path.setAttribute('data-pair-id', pairId);
                    path.style.filter = 'drop-shadow(0 1px 2px rgba(0,0,0,0.1))';
                    
                    connectionCanvas.appendChild(path);
                }, 50);
            }
            
            // Initialize canvas size (will only work if game is visible)
            setTimeout(() => updateCanvasSize(), 100);
            window.addEventListener('resize', updateCanvasSize);
            
            // Make updateCanvasSize available globally for the game navigation script
            window.updateMatchingPairsCanvas = updateCanvasSize;
            
            // Update canvas when game becomes visible
            // Use a simpler, more reliable approach that doesn't interfere with game display
            const gameContainer = matchingPairsGameArea.closest('.game-container');
            if (gameContainer) {
                let canvasUpdateScheduled = false;
                
                // Function to safely update canvas
                const safeUpdateCanvas = () => {
                    if (canvasUpdateScheduled) return;
                    canvasUpdateScheduled = true;
                    
                    const display = window.getComputedStyle(gameContainer).display;
                    const hasHeight = gameContainer.offsetHeight > 0;
                    const hasShowClass = gameContainer.classList.contains('show');
                    
                    if (display !== 'none' && hasHeight && hasShowClass) {
                        setTimeout(() => {
                            if (gameContainer.offsetHeight > 0 && 
                                window.getComputedStyle(gameContainer).display !== 'none' &&
                                gameContainer.classList.contains('show')) {
                                updateCanvasSize();
                            }
                            canvasUpdateScheduled = false;
                        }, 200);
                    } else {
                        canvasUpdateScheduled = false;
                    }
                };
                
                // Only observe when game is actually shown, not on every change
                const observer = new MutationObserver((mutations) => {
                    // Only update if game is currently being shown (not hidden)
                    if (window.currentlyShowingGameType === 'matchingpairs' || 
                        (gameContainer.classList.contains('show') && 
                         window.getComputedStyle(gameContainer).display !== 'none')) {
                        safeUpdateCanvas();
                    }
                });
                
                // Only observe style and class changes
                observer.observe(gameContainer, { 
                    attributes: true, 
                    attributeFilter: ['style', 'class'],
                    childList: false,
                    subtree: false
                });
            }
            
            // Left items click handler
            document.querySelectorAll('.left-item').forEach(item => {
                item.addEventListener('click', function() {
                    if (isSubmitted) return; // Don't allow selection after submission
                    const pairId = parseInt(this.dataset.pairId);
                    if (lockedLeftItems.has(pairId)) return; // Don't allow selection of locked items
                    
                    // Remove previous selection (only if not locked)
                    document.querySelectorAll('.left-item').forEach(i => {
                        const itemPairId = parseInt(i.dataset.pairId);
                        if (!lockedLeftItems.has(itemPairId)) {
                            i.classList.remove('border-pink-600', 'shadow-xl', 'ring-4', 'ring-pink-400', 'selected');
                        }
                    });
                    
                    // Select this item
                    this.classList.add('border-pink-600', 'shadow-xl', 'ring-4', 'ring-pink-400', 'selected');
                    selectedLeftItem = this;
                    
                    // Lock in pair if right item is selected
                    if (selectedRightItem) {
                        lockPair();
                    }
                });
            });
            
            // Right items click handler
            document.querySelectorAll('.right-item').forEach(item => {
                item.addEventListener('click', function() {
                    if (isSubmitted) return; // Don't allow selection after submission
                    const pairId = parseInt(this.dataset.pairId);
                    if (lockedRightItems.has(pairId)) return; // Don't allow selection of locked items
                    
                    // Remove previous selection (only if not locked)
                    document.querySelectorAll('.right-item').forEach(i => {
                        const itemPairId = parseInt(i.dataset.pairId);
                        if (!lockedRightItems.has(itemPairId)) {
                            i.classList.remove('border-purple-600', 'shadow-xl', 'ring-4', 'ring-purple-400', 'selected');
                        }
                    });
                    
                    // Select this item
                    this.classList.add('border-purple-600', 'shadow-xl', 'ring-4', 'ring-purple-400', 'selected');
                    selectedRightItem = this;
                    
                    // Lock in pair if left item is selected
                    if (selectedLeftItem) {
                        lockPair();
                    }
                });
            });
            
            // Function to lock in a pair (without validation)
            function lockPair() {
                if (!selectedLeftItem || !selectedRightItem) return;
                
                const leftPairId = parseInt(selectedLeftItem.dataset.pairId);
                const rightPairId = parseInt(selectedRightItem.dataset.pairId);
                
                // Store the locked pair
                lockedPairs.set(leftPairId, rightPairId);
                lockedLeftItems.add(leftPairId);
                lockedRightItems.add(rightPairId);
                
                // Draw line between locked items
                drawLine(selectedLeftItem, selectedRightItem, leftPairId);
                
                // Remove selection highlighting but keep items visible
                selectedLeftItem.classList.remove('border-pink-600', 'shadow-xl', 'ring-4', 'ring-pink-400', 'selected');
                selectedRightItem.classList.remove('border-purple-600', 'shadow-xl', 'ring-4', 'ring-purple-400', 'selected');
                
                // Clear selection
                selectedLeftItem = null;
                selectedRightItem = null;
                
                // Update pairs matched count with visual animation
                const scoreElement = document.getElementById('matchingPairsScore');
                if (scoreElement) {
                    scoreElement.textContent = `Pairs Matched: ${lockedPairs.size}`;
                    // Visual animation only
                    scoreElement.classList.add('animate-score');
                    setTimeout(() => scoreElement.classList.remove('animate-score'), 500);
                }
            }
            
            function checkMatch() {
                if (!selectedLeftItem || !selectedRightItem) return;
                
                const leftPairId = parseInt(selectedLeftItem.dataset.pairId);
                const rightPairId = parseInt(selectedRightItem.dataset.pairId);
                
                if (leftPairId === rightPairId) {
                    // Correct match!
                    matchedPairs.add(leftPairId);
                    currentScore++;
                    
                    // Update score display
                    const scoreElement = document.getElementById('matchingPairsScore');
                    if (scoreElement) scoreElement.textContent = `Score: ${currentScore}`;
                    
                    // Mark items as matched
                    selectedLeftItem.classList.add('opacity-75', 'cursor-not-allowed', 'bg-green-100', 'border-green-500', 'matched');
                    selectedLeftItem.classList.remove('border-pink-600', 'cursor-pointer', 'ring-4', 'ring-pink-400', 'shadow-xl', 'selected');
                    selectedRightItem.classList.add('opacity-75', 'cursor-not-allowed', 'bg-green-100', 'border-green-500', 'matched');
                    selectedRightItem.classList.remove('border-purple-600', 'cursor-pointer', 'ring-4', 'ring-purple-400', 'shadow-xl', 'selected');
                    
                    // Draw line between matched items
                    // Use the items before clearing selection
                    const leftItemForLine = selectedLeftItem;
                    const rightItemForLine = selectedRightItem;
                    drawLine(leftItemForLine, rightItemForLine, leftPairId);
                    // Add visual animation class to path (visual only)
                    setTimeout(() => {
                        const paths = connectionCanvas.querySelectorAll(`path[data-pair-id="${leftPairId}"]`);
                        paths.forEach(path => path.classList.add('correct-line'));
                    }, 100);
                    
                    // Add checkmark animation
                    const checkmark1 = document.createElement('span');
                    checkmark1.className = 'absolute top-2 right-2 text-green-600 text-3xl font-bold';
                    checkmark1.textContent = '✓';
                    checkmark1.style.animation = 'scaleIn 0.3s ease';
                    selectedLeftItem.style.position = 'relative';
                    selectedLeftItem.appendChild(checkmark1);
                    
                    const checkmark2 = document.createElement('span');
                    checkmark2.className = 'absolute top-2 right-2 text-green-600 text-3xl font-bold';
                    checkmark2.textContent = '✓';
                    checkmark2.style.animation = 'scaleIn 0.3s ease';
                    selectedRightItem.style.position = 'relative';
                    selectedRightItem.appendChild(checkmark2);
                    
                    // Clear selection
                    selectedLeftItem = null;
                    selectedRightItem = null;
                    
                    // Check if all pairs are matched
                    if (matchedPairs.size === totalPairs) {
                        setTimeout(() => {
                            showMatchingPairsCompletion();
                        }, 500);
                    }
                } else {
                    // Incorrect match - shake animation
                    selectedLeftItem.style.animation = 'shake 0.5s';
                    selectedRightItem.style.animation = 'shake 0.5s';
                    
                    setTimeout(() => {
                        selectedLeftItem.style.animation = '';
                        selectedRightItem.style.animation = '';
                        selectedLeftItem.classList.remove('border-pink-600', 'shadow-xl', 'ring-4', 'ring-pink-400', 'selected');
                        selectedRightItem.classList.remove('border-purple-600', 'shadow-xl', 'ring-4', 'ring-purple-400', 'selected');
                        selectedLeftItem = null;
                        selectedRightItem = null;
                    }, 500);
                }
            }
            
            // Submit button
            const submitBtn = document.getElementById('submitMatchingPairsBtn');
            if (submitBtn) {
                submitBtn.addEventListener('click', function() {
                    if (isSubmitted) return; // Prevent multiple submissions
                    
                    // Check if all pairs are locked
                    if (lockedPairs.size < totalPairs) {
                        alert(`Please match all ${totalPairs} pairs before submitting!`);
                        return;
                    }
                    
                    // Mark as submitted
                    isSubmitted = true;
                    submitBtn.disabled = true;
                    submitBtn.classList.add('opacity-50', 'cursor-not-allowed');
                    
                    // Validate all pairs and calculate score
                    let correctCount = 0;
                    
                    lockedPairs.forEach((rightPairId, leftPairId) => {
                        const leftItem = document.querySelector(`.left-item[data-pair-id="${leftPairId}"]`);
                        const rightItem = document.querySelector(`.right-item[data-pair-id="${rightPairId}"]`);
                        
                        if (leftItem && rightItem) {
                            if (leftPairId === rightPairId) {
                                // Correct match!
                                correctCount++;
                                leftItem.classList.add('bg-green-100', 'border-green-500', 'matched');
                                rightItem.classList.add('bg-green-100', 'border-green-500', 'matched');
                                
                                // Update path color to green with visual animation
                                const paths = connectionCanvas.querySelectorAll(`path[data-pair-id="${leftPairId}"]`);
                                paths.forEach(path => {
                                    path.setAttribute('stroke', '#22c55e');
                                    path.setAttribute('stroke-width', '3');
                                    path.classList.add('correct-line');
                                });
                            } else {
                                // Incorrect match
                                leftItem.classList.add('bg-red-100', 'border-red-500');
                                rightItem.classList.add('bg-red-100', 'border-red-500');
                                
                                // Update path color to red
                                const paths = connectionCanvas.querySelectorAll(`path[data-pair-id="${leftPairId}"]`);
                                paths.forEach(path => {
                                    path.setAttribute('stroke', '#ef4444');
                                    path.setAttribute('stroke-width', '2.5');
                                });
                            }
                            
                            // Make items non-clickable
                            leftItem.classList.add('cursor-not-allowed', 'opacity-75');
                            rightItem.classList.add('cursor-not-allowed', 'opacity-75');
                        }
                    });
                    
                    // Calculate score percentage
                    const scorePercent = Math.round((correctCount / totalPairs) * 100);
                    
                    // Update score display with visual animation
                    const scoreElement = document.getElementById('matchingPairsScore');
                    if (scoreElement) {
                        scoreElement.textContent = `Score: ${correctCount} / ${totalPairs} (${scorePercent}%)`;
                        // Visual animation only
                        scoreElement.classList.add('animate-score');
                        setTimeout(() => scoreElement.classList.remove('animate-score'), 600);
                    }
                    
                    // Show completion message with slight delay for visual effect
                    setTimeout(() => {
                        showMatchingPairsCompletion(correctCount, scorePercent);
                    }, 500);
                });
            }
            
            // Reset button
            const resetBtn = document.getElementById('resetMatchingPairsBtn');
            if (resetBtn) {
                resetBtn.addEventListener('click', function() {
                    location.reload();
                });
            }
            
            // Updated showMatchingPairsCompletion function
            function showMatchingPairsCompletion(correctCount, scorePercent) {
                const finalScoreElement = document.getElementById('matchingPairsFinalScore');
                if (finalScoreElement) finalScoreElement.textContent = `Score: ${scorePercent}% (${correctCount} / ${totalPairs} correct)`;
                
                const overlay = document.getElementById('matchingPairsCompletionMessage');
                if (overlay) {
                    overlay.classList.remove('hidden');
                    overlay.style.display = 'flex';
                }
                
                // Save score
                @if($matchingPairsGame->game)
                const gameId = {{ $matchingPairsGame->game->game_id }};
                if (gameId) {
                    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
                    const saveScoreRoute = @json(route('student.games.saveScore'));
                    
                    fetch(saveScoreRoute, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken || '',
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({
                            game_id: gameId,
                            score: scorePercent
                        })
                    })
                    .then(response => {
                        if (!response.ok) {
                            return response.json().then(err => Promise.reject(err));
                        }
                        return response.json();
                    })
                    .then(data => {
                        console.log('Matching Pairs score saved:', data);
                        if (typeof window.gameScores !== 'undefined') {
                            window.gameScores.matchingpairs = scorePercent;
                        }
                    })
                    .catch(error => {
                        console.error('Error saving matching pairs score:', error);
                        if (error.already_completed) {
                            alert('You have already played this game. You cannot play the same game more than once.');
                            // Mark as completed in the availableGames array
                            const gameIndex = availableGames.findIndex(g => g.type === 'matchingpairs');
                            if (gameIndex !== -1) {
                                availableGames[gameIndex].completed = true;
                                if (typeof moveToNextGame === 'function') {
                                    moveToNextGame();
                                } else {
                                    showGame(currentGameIndex);
                                }
                            }
                        }
                    });
                }
                @endif
            }
            
            // Close completion message function
            window.closeMatchingPairsCompletion = function() {
                const overlay = document.getElementById('matchingPairsCompletionMessage');
                if (overlay) {
                    overlay.classList.add('hidden');
                    overlay.style.display = 'none';
                }
                
                // Move to next game
                if (typeof moveToNextGame === 'function') {
                    setTimeout(() => moveToNextGame(), 500);
                }
            };
        }
    });
    </script>
    @endif

    <!-- Debug Panel (remove in production) -->
    @if(config('app.debug'))
    <div id="debugPanel" style="position: fixed; bottom: 10px; right: 10px; background: rgba(0,0,0,0.8); color: white; padding: 15px; border-radius: 8px; z-index: 9999; max-width: 400px; font-size: 12px; max-height: 300px; overflow-y: auto;">
        <div style="font-weight: bold; margin-bottom: 10px; border-bottom: 1px solid white; padding-bottom: 5px;">🔍 DEBUG INFO</div>
        <div><strong>selectedLessonId:</strong> {{ $selectedLessonId ?? 'NOT SET' }}</div>
        <div><strong>gamesInOrder count:</strong> {{ isset($gamesInOrder) ? count($gamesInOrder) : 'NOT SET' }}</div>
        <div><strong>availableGames count:</strong> {{ count($availableGames ?? []) }}</div>
        <div><strong>has lesson:</strong> {{ isset($lesson) ? 'YES' : 'NO' }}</div>
        <div><strong>has student:</strong> {{ isset($student) ? 'YES' : 'NO' }}</div>
        @if(isset($student))
        <div><strong>student class_id:</strong> {{ $student->class_id ?? 'NOT SET' }}</div>
        @endif
        <div style="margin-top: 10px; border-top: 1px solid white; padding-top: 5px;">
            <strong>availableGames:</strong>
            <pre style="font-size: 10px; margin-top: 5px;">{{ json_encode($availableGames ?? [], JSON_PRETTY_PRINT) }}</pre>
        </div>
        <button onclick="document.getElementById('debugPanel').style.display='none'" style="margin-top: 10px; padding: 5px 10px; background: red; color: white; border: none; border-radius: 4px; cursor: pointer;">Close</button>
    </div>
    @endif

    <!-- Game Navigation Script -->
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Make availableGames globally accessible
        window.availableGames = @json($availableGames ?? []);
        const availableGames = window.availableGames;
        
        // DEBUG: Log availableGames
        console.log('=== GAMES DEBUG START ===');
        console.log('availableGames from PHP:', window.availableGames);
        console.log('availableGames count:', availableGames.length);
        console.log('availableGames content:', JSON.stringify(availableGames, null, 2));
        console.log('selectedLessonId:', @json($selectedLessonId ?? null));
        console.log('gamesInOrder count:', @json(isset($gamesInOrder) ? count($gamesInOrder) : 0));
        
        // DEBUG: Check for game containers in DOM
        const allGameContainers = document.querySelectorAll('.game-container');
        console.log('Game containers found in DOM:', allGameContainers.length);
        allGameContainers.forEach((container, idx) => {
            console.log(`Game container ${idx}:`, {
                'gameType': container.getAttribute('data-game-type'),
                'gameIndex': container.getAttribute('data-game-index'),
                'display': container.style.display,
                'visible': container.offsetParent !== null
            });
        });
        
        let currentGameIndex = 0;
        
        // Routes for navigation
        @php
            $lessonId = isset($lesson) && $lesson ? $lesson->lesson_id : null;
        @endphp
        const lessonId = @json($lessonId);
        window.lessonViewRoute = lessonId ? @json(route('student.lesson.view', $lessonId)) : null;
        const lessonViewRoute = window.lessonViewRoute;
        const dashboardRoute = @json(route('student.dashboard'));
        
        // Track scores for each game type
        window.gameScores = {
            clock: null,           // Clock game has no score (just display)
            wordsearch: null,      // Word Search score (0-100)
            scrambledclocks: null, // Scrambled Clocks has no score (just correct/incorrect)
            wordclock: null,       // Word Clock Arrangement score (0-100)
            mcq: null,             // Multiple Choice score (0-100)
            scramble: null,        // Scrambled Letters score (0-100)
            matchingpairs: null    // Matching Pairs score (0-100)
        };
        
        // Keep game switching SIMPLE + reliable: only one `.game-container` visible at a time.
        function hideAllCompletedMessages() {
            document.querySelectorAll('.game-completed-message').forEach(msg => {
                msg.style.display = 'none';
            });
        }

        // Track which game is currently being shown to prevent race conditions
        // Make it globally accessible so MutationObserver can access it
        window.currentlyShowingGameType = null;
        let isShowingGame = false; // Flag to prevent concurrent showGame calls
        
        function hideAllGameContainers(skipType = null) {
            document.querySelectorAll('.game-container').forEach(container => {
                const gameType = container.getAttribute('data-game-type');
                
                // Don't hide the game that's currently being shown (prevent race condition)
                if (skipType && gameType === skipType) {
                    console.log('Skipping hide for game type:', gameType);
                    return;
                }
                
                // Don't hide if we're currently showing a game
                if (isShowingGame && gameType === window.currentlyShowingGameType) {
                    console.log('Skipping hide - game is being shown:', gameType);
                    return;
                }
                
                container.classList.remove('show');
                container.style.display = 'none';
                container.style.opacity = '0';
                container.style.visibility = 'hidden';
            });
        }

        function findGameContainer(game) {
            const gameType = game?.type;
            const gameIndex = game?.index !== undefined ? String(game.index) : null;
            if (!gameType) return null;

            const candidates = Array.from(document.querySelectorAll(`[data-game-type="${gameType}"]`));
            if (candidates.length === 0) return null;
            if (candidates.length === 1) return candidates[0];

            // Prefer exact index match when multiple containers exist (e.g. wordclock fallback + real one)
            if (gameIndex !== null) {
                const byIndex = candidates.find(c => c.getAttribute('data-game-index') === gameIndex);
                if (byIndex) return byIndex;
            }

            // Prefer non-fallback container if present
            const nonFallback = candidates.find(c => !c.className.includes('bg-yellow-50'));
            return nonFallback || candidates[0];
        }

        function showGame(index) {
            // Prevent concurrent calls
            if (isShowingGame) {
                console.warn('showGame() already in progress, ignoring call for index:', index);
                return;
            }
            
            try {
                isShowingGame = true;
                console.log('=== showGame() called ===', index);

                const game = availableGames[index];
                if (!game) {
                    console.error('availableGames[index] is undefined for index:', index);
                    isShowingGame = false;
                    return;
                }

                // Set the currently showing game type BEFORE hiding others
                window.currentlyShowingGameType = game.type;
                console.log('Setting currentlyShowingGameType to:', game.type);

                hideAllCompletedMessages();
                // Pass the game type to skip hiding it
                hideAllGameContainers(game.type);
                
                // Small delay to ensure hideAllGameContainers completes
                setTimeout(() => {
                    // Clear the flag after a longer delay to allow the game to fully render
                    setTimeout(() => {
                        if (window.currentlyShowingGameType === game.type) {
                            // Only clear if it's still the same game (wasn't changed)
                            console.log('Clearing currentlyShowingGameType flag for:', game.type);
                            window.currentlyShowingGameType = null;
                        }
                        isShowingGame = false;
                    }, 2000); // Increased delay to 2 seconds to prevent auto-advance
                }, 50);

                const targetContainer = findGameContainer(game);
                if (!targetContainer) {
                    console.error('Game container NOT FOUND for type:', game.type);
                    isShowingGame = false;
                    window.currentlyShowingGameType = null;
                    return;
                }

                console.log('Showing game:', { type: game.type, index: game.index, completed: game.completed });
                console.log('Target container:', {
                    type: targetContainer.getAttribute('data-game-type'),
                    index: targetContainer.getAttribute('data-game-index'),
                    className: targetContainer.className
                });

                // If completed, show message + allow moving forward
                if (game.completed) {
                    let completedMsg = targetContainer.parentElement?.querySelector('.game-completed-message');
                    if (!completedMsg) {
                        completedMsg = document.createElement('div');
                        completedMsg.className = 'game-completed-message max-w-6xl mx-auto bg-gradient-to-br from-yellow-50 to-orange-50 backdrop-blur-xl rounded-3xl shadow-2xl p-10 border-2 border-yellow-300 mb-8';
                        completedMsg.innerHTML = `
                            <div class="text-center">
                                <div class="mb-4">
                                    <svg class="mx-auto h-16 w-16 text-yellow-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                                <h3 class="text-2xl font-bold text-yellow-800 mb-3">You Have Already Played This Game</h3>
                                <p class="text-lg text-yellow-700 mb-6">You cannot play the same game more than once. Please try another game.</p>
                                <button onclick="moveToNextGame()" class="px-6 py-3 bg-gradient-to-r from-yellow-400 to-orange-500 text-white font-bold rounded-xl shadow-lg hover:from-yellow-500 hover:to-orange-600 transform hover:scale-105 transition-all duration-200">
                                    Continue to Next Game
                                </button>
                            </div>
                        `;
                        targetContainer.parentNode.insertBefore(completedMsg, targetContainer.nextSibling);
                    }
                    completedMsg.style.display = 'block';
                    completedMsg.scrollIntoView({ behavior: 'smooth', block: 'start' });
                    return;
                }

                // Show only this game (FORCE inline visibility so it works even if .show CSS isn't applied)
                // For matching pairs, ensure it stays visible
                if (game.type === 'matchingpairs') {
                    // Force all styles to ensure visibility
                    targetContainer.style.setProperty('display', 'block', 'important');
                    targetContainer.style.setProperty('visibility', 'visible', 'important');
                    targetContainer.style.setProperty('opacity', '1', 'important');
                    targetContainer.style.setProperty('transform', 'none', 'important');
                    targetContainer.style.setProperty('position', 'relative', 'important');
                } else {
                    targetContainer.style.display = 'block';
                    targetContainer.style.visibility = 'visible';
                    targetContainer.style.opacity = '1';
                    targetContainer.style.transform = 'none';
                }
                targetContainer.classList.add('show');

                // Sanity log
                const computed = window.getComputedStyle(targetContainer);
                console.log('After show styles:', {
                    display: computed.display,
                    visibility: computed.visibility,
                    opacity: computed.opacity,
                    height: targetContainer.offsetHeight,
                    width: targetContainer.offsetWidth,
                    scrollHeight: targetContainer.scrollHeight,
                    scrollWidth: targetContainer.scrollWidth,
                    paddingTop: computed.paddingTop,
                    paddingBottom: computed.paddingBottom,
                    borderTop: computed.borderTopWidth,
                    borderBottom: computed.borderBottomWidth,
                    fontSize: computed.fontSize,
                    lineHeight: computed.lineHeight,
                    position: computed.position,
                    transform: computed.transform,
                    offsetParent: targetContainer.offsetParent ? targetContainer.offsetParent.tagName : null,
                    innerTextLen: (targetContainer.innerText || '').length,
                    children: targetContainer.children.length
                });
                
                // If we're still 0x0, it's almost always because a parent is display:none (or font-size/line-height 0).
                if (targetContainer.offsetHeight === 0 && targetContainer.offsetWidth === 0) {
                    const chain = [];
                    let p = targetContainer;
                    let i = 0;
                    while (p && p !== document.documentElement && i < 12) {
                        const cs = window.getComputedStyle(p);
                        chain.push({
                            node: p.tagName + (p.id ? `#${p.id}` : '') + (p.className ? `.${String(p.className).split(' ')[0]}` : ''),
                            display: cs.display,
                            visibility: cs.visibility,
                            opacity: cs.opacity,
                            position: cs.position,
                            transform: cs.transform,
                            fontSize: cs.fontSize,
                            lineHeight: cs.lineHeight,
                            offsetH: p.offsetHeight,
                            offsetW: p.offsetWidth
                        });
                        p = p.parentElement;
                        i++;
                    }
                    console.warn('0x0 parent chain (closest first):', chain);
                }

                // If still zero-size, force a visible box so we can confirm it's rendering at all
                if (targetContainer.offsetHeight === 0 && targetContainer.offsetWidth === 0) {
                    console.warn('⚠️ Target container is still 0x0 after showing. Forcing fallback box styles...');
                    targetContainer.style.setProperty('min-height', '200px', 'important');
                    targetContainer.style.setProperty('padding', '20px', 'important');
                    targetContainer.style.setProperty('border', '2px solid rgba(255,0,0,0.4)', 'important');
                    targetContainer.style.setProperty('background', 'rgba(255,255,0,0.08)', 'important');
                    const rect = targetContainer.getBoundingClientRect();
                    console.log('Fallback rect:', { width: rect.width, height: rect.height, top: rect.top, left: rect.left });
                    console.log('Fallback innerText preview:', (targetContainer.innerText || '').slice(0, 120));
                }

                targetContainer.scrollIntoView({ behavior: 'smooth', block: 'start' });

                // Matching pairs needs a canvas resize when revealed
                // Use a longer delay and check visibility to ensure container is fully rendered
                if (game.type === 'matchingpairs' && typeof window.updateMatchingPairsCanvas === 'function') {
                    // Wait for container to be fully visible before resizing canvas
                    setTimeout(() => {
                        if (targetContainer.offsetHeight > 0 && window.getComputedStyle(targetContainer).display !== 'none') {
                            window.updateMatchingPairsCanvas();
                        } else {
                            // Retry if not visible yet
                            setTimeout(() => {
                                if (targetContainer.offsetHeight > 0 && window.getComputedStyle(targetContainer).display !== 'none') {
                                    window.updateMatchingPairsCanvas();
                                }
                            }, 100);
                        }
                    }, 200);
                }
            } catch (e) {
                console.error('❌ showGame() crashed:', e);
                isShowingGame = false;
                window.currentlyShowingGameType = null;
            }
        }
        
        // Calculate total score based on available games
        function calculateTotalScore() {
            let totalScore = 0;
            let scoredGamesCount = 0;
            
            // Count games that have scores
            availableGames.forEach(game => {
                const score = window.gameScores[game.type];
                if (score !== null && score !== undefined) {
                    totalScore += score;
                    scoredGamesCount++;
                }
            });
            
            // Calculate average if there are scored games
            if (scoredGamesCount > 0) {
                return Math.round(totalScore / scoredGamesCount);
            }
            return 0;
        }
        
        // Initialize: show first non-completed game (only ONE visible at a time)
        console.log('=== INITIALIZING GAMES ===');
        console.log('availableGames.length:', availableGames.length);
        console.log('availableGames:', JSON.stringify(availableGames, null, 2));
        
        // Wait a bit before initializing to ensure DOM is fully ready, especially for matching pairs
        setTimeout(() => {
            hideAllCompletedMessages();
            hideAllGameContainers();
            
            if (availableGames.length > 0) {
                console.log('Games found, looking for first non-completed game...');
                let firstNonCompletedIndex = 0;
                while (firstNonCompletedIndex < availableGames.length && availableGames[firstNonCompletedIndex].completed) {
                    console.log(`Game ${firstNonCompletedIndex} (${availableGames[firstNonCompletedIndex].type}) is completed, skipping...`);
                    firstNonCompletedIndex++;
                }
                if (firstNonCompletedIndex < availableGames.length) {
                    console.log(`Showing game at index ${firstNonCompletedIndex}:`, availableGames[firstNonCompletedIndex]);
                    currentGameIndex = firstNonCompletedIndex;
                    showGame(currentGameIndex);
                } else {
                    console.log('All games are already completed');
                    // All games already completed - show message
                    const messageDiv = document.createElement('div');
                    messageDiv.className = 'max-w-6xl mx-auto bg-gradient-to-br from-green-50 to-emerald-50 backdrop-blur-xl rounded-3xl shadow-2xl p-10 border-2 border-green-300 mb-8 text-center';
                    messageDiv.innerHTML = `
                        <h3 class="text-2xl font-bold text-green-800 mb-3">All Games Completed!</h3>
                        <p class="text-lg text-green-700">You have already played all available games for this lesson.</p>
                    `;
                    const container = document.querySelector('.container') || document.body;
                    container.appendChild(messageDiv);
                }
            }
        }, 150); // Delay to ensure DOM is ready, especially for matching pairs canvas
        
        // Prevent rapid auto-advance
        let lastMoveToNextGameTime = 0;
        const MIN_TIME_BETWEEN_ADVANCES = 1500; // Minimum 1.5 seconds between advances
        
        // Function to move to next game (go in ORDER; do not skip "completed" here)
        window.moveToNextGame = function() {
            const now = Date.now();
            const timeSinceLastAdvance = now - lastMoveToNextGameTime;
            
            // Prevent rapid auto-advance (especially for matching pairs)
            if (timeSinceLastAdvance < MIN_TIME_BETWEEN_ADVANCES) {
                console.warn(`moveToNextGame() called too soon (${timeSinceLastAdvance}ms ago). Ignoring to prevent rapid advance.`);
                return;
            }
            
            // Don't advance if we're currently showing a game (prevent race condition)
            if (isShowingGame) {
                console.warn('moveToNextGame() called while showing game. Ignoring.');
                return;
            }
            
            lastMoveToNextGameTime = now;
            console.log('=== moveToNextGame() called ===');
            console.log('Current game index:', currentGameIndex);
            console.log('Currently showing game type:', window.currentlyShowingGameType);
            
            // Go to the next game in the list (even if it's completed; showGame() will handle "already played")
            let nextIndex = currentGameIndex + 1;
            
            if (nextIndex < availableGames.length) {
                const nextGame = availableGames[nextIndex];
                console.log(`Moving to next game at index ${nextIndex}:`, nextGame);
                
                // Special handling for matching pairs - ensure it stays visible
                if (nextGame.type === 'matchingpairs') {
                    console.log('Next game is matching pairs - ensuring proper display');
                }
                
                currentGameIndex = nextIndex;
                showGame(currentGameIndex);
            } else {
                // All games completed - show completion popup with options
                const totalScore = calculateTotalScore();
                const scoredGamesCount = availableGames.filter(g => window.gameScores[g.type] !== null && window.gameScores[g.type] !== undefined).length;
                const totalGamesCount = availableGames.length;
                
                // Create confetti effect
                if (typeof createConfetti === 'function') {
                    createConfetti();
                }
                
                const completionMessage = document.createElement('div');
                completionMessage.className = 'fixed inset-0 z-50 flex items-center justify-center';
                completionMessage.style.background = 'linear-gradient(135deg, rgba(236, 72, 153, 0.3) 0%, rgba(168, 85, 247, 0.3) 50%, rgba(59, 130, 246, 0.2) 100%)';
                completionMessage.style.backdropFilter = 'blur(8px)';
                completionMessage.style.animation = 'fadeInOverlay 0.5s ease';
                completionMessage.innerHTML = `
                    <div class="relative max-w-xl w-full mx-4">
                        <!-- Animated Background Circles -->
                        <div class="absolute -top-16 -left-16 w-32 h-32 rounded-full opacity-30 blur-3xl animate-pulse" style="background-color: #FC8EAC; animation-duration: 3s;"></div>
                        <div class="absolute -bottom-16 -right-16 w-32 h-32 rounded-full opacity-30 blur-3xl animate-pulse" style="background-color: #F8C5C8; animation-duration: 4s; animation-delay: 1s;"></div>
                        
                        <!-- Main Card - Using #F8C5C8 (lightest pink) -->
                        <div class="relative rounded-2xl p-6 md:p-8 text-center shadow-2xl border-3 backdrop-blur-xl overflow-hidden" style="background-color: #F8C5C8; border-color: rgba(252, 142, 172, 0.5); animation: slideUpBounce 0.8s cubic-bezier(0.34, 1.56, 0.64, 1);">
                            <!-- Decorative Sparkles -->
                            <div class="absolute top-3 right-3 text-2xl animate-bounce" style="animation-duration: 2s;">✨</div>
                            <div class="absolute top-5 left-5 text-xl animate-bounce" style="animation-duration: 2.5s; animation-delay: 0.3s;">⭐</div>
                            
                            <!-- Celebration Emoji -->
                            <div class="relative mb-4">
                                <div class="text-5xl mb-1 inline-block" style="animation: celebration 1.5s ease-in-out infinite; filter: drop-shadow(0 4px 8px rgba(252, 142, 172, 0.4));">🎉</div>
                            </div>
                            
                            <!-- Title -->
                            <h2 class="text-2xl md:text-3xl font-black mb-3 bg-gradient-to-r from-pink-600 via-rose-600 to-pink-500 bg-clip-text text-transparent" dir="rtl" style="text-shadow: 0 2px 4px rgba(0,0,0,0.1);">
                                تهانينا! / Congratulations!
                            </h2>
                            
                            <!-- Subtitle -->
                            <p class="text-base md:text-lg text-gray-700 mb-5 font-semibold" dir="rtl">
                                لقد أكملت جميع الألعاب بنجاح! / You have completed all ${totalGamesCount} game${totalGamesCount > 1 ? 's' : ''} successfully!
                            </p>
                            
                            ${scoredGamesCount > 0 ? `
                                <!-- Score Card - Using #FC8EAC (medium pink) -->
                                <div class="relative mb-5 rounded-xl p-4 shadow-lg" style="background-color: #FC8EAC; border: 3px solid rgba(248, 197, 200, 0.6);">
                                    <div class="relative">
                                        <div class="text-lg md:text-xl font-bold text-white mb-2 flex items-center justify-center gap-2" dir="rtl" style="text-shadow: 0 1px 2px rgba(0,0,0,0.2);">
                                            <span class="text-2xl">🏆</span>
                                            <span>النتيجة الإجمالية / Total Score</span>
                                        </div>
                                        <div class="text-4xl md:text-5xl font-black mb-2 text-white" style="text-shadow: 0 2px 4px rgba(0,0,0,0.3);">
                                            ${totalScore}%
                                        </div>
                                        <div class="text-xs md:text-sm text-white/90 font-medium" dir="rtl" style="text-shadow: 0 1px 2px rgba(0,0,0,0.2);">
                                            متوسط ${scoredGamesCount} لعبة / Average of ${scoredGamesCount} scored game${scoredGamesCount > 1 ? 's' : ''}
                                        </div>
                                    </div>
                                </div>
                            ` : ''}
                            
                            <!-- Action Buttons -->
                            <div class="flex flex-col sm:flex-row gap-3 justify-center items-center mt-5">
                                ${lessonViewRoute ? `
                                    <!-- Button 1 - Using #F8C5C8 (lightest pink) -->
                                    <a href="${lessonViewRoute}?t=${Date.now()}" class="group relative px-6 py-3 rounded-xl font-bold text-base md:text-lg shadow-xl transform hover:scale-105 transition-all duration-300 flex items-center gap-2 overflow-hidden border-2" style="background-color: #F8C5C8; border-color: #FC8EAC;">
                                        <div class="absolute inset-0 opacity-0 group-hover:opacity-100 transition-opacity duration-300" style="background-color: #FC8EAC;"></div>
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 relative z-10 transform group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor" style="color: #FC8EAC;">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                                        </svg>
                                        <span class="relative z-10" dir="rtl" style="color: #FC8EAC; font-weight: bold;">العودة إلى محتوى الدرس / Return to Lesson</span>
                                        <div class="absolute inset-0 bg-white/20 transform scale-x-0 group-hover:scale-x-100 transition-transform duration-300 origin-left"></div>
                                    </a>
                                ` : ''}
                                <!-- Button 2 - Using #FC8EAC (medium pink) -->
                                <a href="${dashboardRoute}" class="group relative px-6 py-3 text-white rounded-xl font-bold text-base md:text-lg shadow-xl transform hover:scale-105 transition-all duration-300 flex items-center gap-2 overflow-hidden border-2" style="background-color: #FC8EAC; border-color: #F8C5C8;">
                                    <div class="absolute inset-0 opacity-0 group-hover:opacity-100 transition-opacity duration-300" style="background-color: #F8C5C8;"></div>
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 relative z-10 transform group-hover:scale-110 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor" style="color: white;">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                                    </svg>
                                    <span class="relative z-10" dir="rtl" style="color: white; font-weight: bold;">لوحة التحكم / Go to Dashboard</span>
                                    <div class="absolute inset-0 bg-white/20 transform scale-x-0 group-hover:scale-x-100 transition-transform duration-300 origin-left"></div>
                                </a>
                            </div>
                            
                            <!-- Bottom Decoration -->
                            <div class="mt-5 pt-4" style="border-top: 2px solid rgba(252, 142, 172, 0.3);">
                                <p class="text-xs text-gray-600 italic">Keep up the great work! 🌟</p>
                            </div>
                        </div>
                    </div>
                `;
                document.body.appendChild(completionMessage);
                
                // Add sparkle animation on buttons after a delay
                setTimeout(() => {
                    const buttons = completionMessage.querySelectorAll('a');
                    buttons.forEach((btn, index) => {
                        setTimeout(() => {
                            btn.style.animation = 'pulse 2s ease-in-out infinite';
                        }, index * 200);
                    });
                }, 500);
            }
        };
        
        // For Clock Game - add a "Continue" button that saves score (only if there are more games)
        const clockGameContainer = document.querySelector('[data-game-type="clock"]');
        if (clockGameContainer) {
            const clockContent = clockGameContainer.querySelector('.flex.flex-wrap');
            if (clockContent && !clockGameContainer.querySelector('.continue-btn-container')) {
                @if(isset($clockGame) && $clockGame)
                const clockGameId = @json($clockGame->game_id ?? null);
                @else
                const clockGameId = null;
                @endif
                const clockLessonId = @json($selectedLessonId ?? null);
                const saveScoreRoute = @json(route('student.games.saveScore'));
                
                // Only show button if there are more games after this one
                const clockGameIndex = availableGames.findIndex(g => g.type === 'clock');
                const hasMoreGames = clockGameIndex !== -1 && clockGameIndex < availableGames.length - 1;
                
                if (hasMoreGames) {
                    const continueBtn = document.createElement('div');
                    continueBtn.className = 'continue-btn-container flex justify-center mt-6';
                    
                    const buttonHtml = `
                        <button id="clockContinueBtn" class="px-8 py-3 rounded-lg bg-green-500 text-white font-bold text-lg hover:bg-green-600 transition-colors">
                            Continue to Next Game →
                        </button>
                    `;
                    continueBtn.innerHTML = buttonHtml;
                    clockGameContainer.appendChild(continueBtn);
                    
                    // Add click handler to save score before moving to next game
                    document.getElementById('clockContinueBtn').addEventListener('click', function() {
                        // Save clock game as completed (100% score since it's just viewing/learning)
                        if (clockGameId) {
                            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
                            
                            fetch(saveScoreRoute, {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': csrfToken || '',
                                    'Accept': 'application/json'
                                },
                                body: JSON.stringify({
                                    game_id: clockGameId,
                                    score: 100 // Clock game is just viewing/learning - give full score for completion
                                })
                            })
                            .then(response => {
                                if (!response.ok) {
                                    return response.json().then(err => Promise.reject(err));
                                }
                                return response.json();
                            })
                            .then(data => {
                                console.log('Clock game score saved:', data);
                                // Update gameScores
                                if (typeof window.gameScores !== 'undefined') {
                                    window.gameScores.clock = 100;
                                }
                                moveToNextGame();
                            })
                            .catch(error => {
                                console.error('Error saving clock game score:', error);
                                // Still move to next game even if save fails
                                moveToNextGame();
                            });
                        } else {
                            // No game_id, just move to next game
                            moveToNextGame();
                        }
                    });
                } else {
                    // Only one game (clock game) - save score and show completion message
                    if (clockGameId) {
                        // Auto-save clock game score when it's the only game
                        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
                        
                        fetch(saveScoreRoute, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': csrfToken || '',
                                'Accept': 'application/json'
                            },
                            body: JSON.stringify({
                                game_id: clockGameId,
                                score: 100
                            })
                        })
                        .then(response => {
                            if (!response.ok) {
                                return response.json().then(err => Promise.reject(err));
                            }
                            return response.json();
                        })
                        .then(data => {
                            console.log('Clock game score saved:', data);
                            if (typeof window.gameScores !== 'undefined') {
                                window.gameScores.clock = 100;
                            }
                            // Show completion message
                            setTimeout(() => {
                                moveToNextGame(); // This will show the completion message
                            }, 1000);
                        })
                        .catch(error => {
                            console.error('Error saving clock game score:', error);
                        });
                    }
                }
            }
        }
    });
    </script>

    @else
    <!-- Show message when no lesson is selected with Hijab8 Image - Enhanced -->
    <div class="max-w-5xl mx-auto py-2">
        <div class="bg-white/90 backdrop-blur-md rounded-2xl shadow-xl p-6 lg:p-8 border-2 border-pink-200/50 transform transition-all duration-500 hover:shadow-2xl">
            <!-- Go Back Button inside the section -->
            <div class="mb-4">
                <a href="{{ route('student.dashboard') }}" class="inline-flex items-center gap-2 bg-white hover:bg-pink-50 text-pink-600 px-4 py-2.5 rounded-lg font-bold shadow-md hover:shadow-lg transition-all duration-150 border-2 border-pink-200 hover:border-pink-300">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Go Back
                </a>
            </div>
            <div class="flex flex-col lg:flex-row items-center justify-center gap-7 lg:gap-12">
                <!-- Left: Hijab8 Image - Enhanced -->
                <div class="relative flex-shrink-0 animate-float-gentle">
                    <div class="relative">
                        <!-- Enhanced Glow Layers -->
                        <div class="absolute inset-0 bg-pink-300/40 rounded-full blur-3xl opacity-60 animate-pulse"></div>
                        <div class="absolute inset-0 bg-cyan-300/40 rounded-full blur-2xl opacity-50 animate-pulse" style="animation-delay: 1s;"></div>
                        <div class="absolute inset-0 bg-teal-300/30 rounded-full blur-xl opacity-40 animate-pulse" style="animation-delay: 2s;"></div>
                        
                        <!-- Enhanced Decorative Elements -->
                        <div class="absolute -top-2 -right-2 w-10 h-10 bg-gradient-to-br from-pink-400 to-pink-500 rounded-full flex items-center justify-center animate-bounce shadow-lg border-2 border-white backdrop-blur-sm transform hover:scale-110 transition-transform">
                            <span class="text-base">⭐</span>
                        </div>
                        <div class="absolute -bottom-2 -left-2 w-10 h-10 bg-gradient-to-br from-cyan-400 to-cyan-500 rounded-full flex items-center justify-center animate-bounce shadow-lg border-2 border-white backdrop-blur-sm transform hover:scale-110 transition-transform" style="animation-delay: 0.5s;">
                            <span class="text-base">💖</span>
                        </div>
                        <div class="absolute top-1/2 -right-6 w-9 h-9 bg-gradient-to-br from-teal-400 to-teal-500 rounded-full flex items-center justify-center animate-bounce shadow-lg border-2 border-white backdrop-blur-sm transform hover:scale-110 transition-transform" style="animation-delay: 1s;">
                            <span class="text-sm">✨</span>
                        </div>
                        <div class="absolute top-0 left-1/2 -translate-x-1/2 -translate-y-1/2 w-9 h-9 bg-gradient-to-br from-rose-400 to-rose-500 rounded-full flex items-center justify-center animate-bounce shadow-lg border-2 border-white backdrop-blur-sm transform hover:scale-110 transition-transform" style="animation-delay: 1.5s;">
                            <span class="text-sm">🎮</span>
                        </div>
                        
                        <!-- Enhanced Image container -->
                        <div class="relative bg-gradient-to-br from-white to-pink-50 rounded-full p-3.5 shadow-xl transform hover:scale-110 transition-transform duration-500 border-2 border-pink-300/60">
                            <div class="absolute inset-0 bg-gradient-to-br from-pink-200/50 to-cyan-200/50 rounded-full opacity-40 blur-xl animate-pulse"></div>
                            <div class="relative bg-white rounded-full p-1.5">
                                <img src="{{ asset('storage/grade-page-design/hijab8.jpg') }}" 
                                     alt="Hijabi Student" 
                                     class="relative w-40 h-40 lg:w-48 lg:h-48 rounded-full object-cover border-2 border-pink-300/50 shadow-lg z-10"
                                     style="object-position: center 20%;"
                                     loading="lazy">
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Right: Enhanced Message Content -->
                <div class="flex-1 text-center lg:text-left">
                    <div class="inline-flex items-center gap-2.5 bg-gradient-to-r from-pink-200/60 to-cyan-200/60 backdrop-blur-md px-5 py-3 rounded-full mb-4 border-2 border-pink-300/40 shadow-md transform hover:scale-105 transition-transform">
                        <div class="w-8 h-8 bg-gradient-to-br from-pink-400 to-cyan-400 rounded-lg flex items-center justify-center shadow-md">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <span class="text-pink-700 font-black text-sm tracking-wider uppercase">Interactive Games</span>
                    </div>
                    <h2 class="text-2xl lg:text-3xl font-black text-gray-800 mb-3.5 tracking-tight leading-tight">
                        Ready to Play?<br>
                        <span class="bg-gradient-to-r from-pink-500 via-rose-400 to-cyan-500 bg-clip-text text-transparent animate-gradient">Select a Lesson!</span> 
                        <span class="inline-block animate-bounce text-xl">🎮</span>
                    </h2>
                    <p class="text-base text-gray-700 font-medium mb-5 leading-relaxed">
                        Choose a lesson from the dropdown below to unlock exciting educational games and start your learning adventure!
                    </p>
                    
                    <!-- Lesson Selector - Enhanced -->
                    @if(isset($lessonsWithGames) && $lessonsWithGames->count() > 0)
                        <div class="mb-5">
                            <div class="bg-white/80 backdrop-blur-md rounded-xl shadow-lg p-5 border border-pink-200/40 transform transition-all duration-300 hover:shadow-xl">
                                <form method="GET" action="{{ route('student.games') }}">
                                    <div class="flex flex-col md:flex-row gap-4 items-end">
                                        <div class="flex-1">
                                            <label for="lesson_id" class="block font-black text-gray-800 mb-3 text-base flex items-center gap-2">
                                                <div class="w-10 h-10 bg-gradient-to-br from-pink-300 to-cyan-300 rounded-lg flex items-center justify-center shadow-md">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                                    </svg>
                                                </div>
                                                Select Lesson:
                                            </label>
                                            <div class="relative">
                                                <select name="lesson_id" id="lesson_id" 
                                                        class="w-full bg-white border-2 border-pink-200/60 rounded-lg px-4 py-3 pr-12 text-gray-800 font-semibold shadow-md hover:border-pink-300 focus:border-pink-400 focus:ring-2 focus:ring-pink-200 transition-all duration-300 appearance-none cursor-pointer"
                                                        onchange="this.form.submit()">
                                                    <option value="">-- Choose Lesson --</option>
                                                    @foreach($lessonsWithGames ?? [] as $lesson)
                                                        <option value="{{ $lesson->lesson_id }}" {{ (isset($selectedLessonId) && $selectedLessonId == $lesson->lesson_id) ? 'selected' : '' }}>{{ $lesson->title }}</option>
                                                    @endforeach
                                                </select>
                                                <div class="absolute inset-y-0 right-0 flex items-center pr-4 pointer-events-none">
                                                    <svg class="h-5 w-5 text-pink-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                                    </svg>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    @endif
                    <div class="flex flex-wrap gap-4 justify-center lg:justify-start">
                        <div class="group bg-gradient-to-br from-pink-50 to-pink-100/80 backdrop-blur-md px-5 py-4 rounded-xl border-2 border-pink-300/50 shadow-md transform hover:scale-105 hover:shadow-lg transition-all duration-300">
                            <div class="flex items-center gap-3 mb-2">
                                <div class="w-8 h-8 bg-gradient-to-br from-pink-400 to-rose-400 rounded-lg flex items-center justify-center shadow-md">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                                <div class="text-pink-600 text-xs font-black uppercase tracking-wider">Fun Learning</div>
                            </div>
                            <div class="text-gray-800 text-base font-black">Interactive Games</div>
                        </div>
                        <div class="group bg-gradient-to-br from-cyan-50 to-cyan-100/80 backdrop-blur-md px-5 py-4 rounded-xl border-2 border-cyan-300/50 shadow-md transform hover:scale-105 hover:shadow-lg transition-all duration-300">
                            <div class="flex items-center gap-3 mb-2">
                                <div class="w-8 h-8 bg-gradient-to-br from-cyan-400 to-teal-400 rounded-lg flex items-center justify-center shadow-md">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                    </svg>
                                </div>
                                <div class="text-cyan-600 text-xs font-black uppercase tracking-wider">Educational</div>
                            </div>
                            <div class="text-gray-800 text-base font-black">Engaging Content</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
</div>

@if(isset($wordClockArrangementGame) && $wordClockArrangementGame && !empty($wordClockArrangementGame->game_data))
    @php
        // Ensure variables are set for JavaScript even if the game container condition fails
        if (!isset($wordClockGameData)) {
            $wordClockGameData = $wordClockArrangementGame->game_data;
            if (is_string($wordClockGameData)) {
                $wordClockGameData = json_decode($wordClockGameData, true);
                if (json_last_error() !== JSON_ERROR_NONE) {
                    $wordClockGameData = [];
                }
            }
            if (!is_array($wordClockGameData)) {
                $wordClockGameData = [];
            }
        }
        if (!isset($wordClockWords)) {
            $wordClockWords = $wordClockGameData['words'] ?? [];
        }
        if (!isset($wordClockWord)) {
            $wordClockWord = $wordClockGameData['word'] ?? '';
        }
        if (!isset($wordClockSentence)) {
            $wordClockSentence = $wordClockGameData['full_sentence'] ?? '';
        }
        if (!isset($wordClockCorrectOrder)) {
            $wordClockCorrectOrder = $wordClockGameData['correct_order'] ?? [];
        }
    @endphp
    
    @if(!empty($wordClockWords) && is_array($wordClockWords) && count($wordClockWords) > 0)
<script>
document.addEventListener('DOMContentLoaded', function() {
    const wordClockContainer = document.getElementById('wordClockArrangementContainer');
    const wordClockArrangedSentence = document.getElementById('wordClockArrangedSentence');
    const wordClockCheckAnswerBtn = document.getElementById('wordClockCheckAnswerBtn');
    const wordClockResultMessage = document.getElementById('wordClockResultMessage');
    
    if (!wordClockContainer || !wordClockArrangedSentence || !wordClockCheckAnswerBtn) {
        console.error('Word Clock game elements not found');
        return;
    }
    
    const wordClockCorrectSentence = @json($wordClockSentence ?? '');
    const wordClockAllWords = @json($wordClockWords ?? []);
    const wordClockCorrectOrder = @json($wordClockCorrectOrder ?? []);
    const gameId = @json($wordClockArrangementGame->game_id ?? null);
    const saveScoreRoute = @json(route('student.games.saveScore'));
    
    // Get the lesson ID for this specific game
    const wordClockLessonId = @json($wordClockArrangementGame->lesson_id ?? null);
    const wordClockLessonRoute = wordClockLessonId ? @json(route('student.lesson.view', $wordClockArrangementGame->lesson_id)) : null;
    
    // Confetti animation function
    function createConfetti() {
        const colors = ['#ff6b6b', '#4ecdc4', '#45b7d1', '#f9ca24', '#f0932b', '#eb4d4b', '#6c5ce7', '#a29bfe'];
        const confettiCount = 100;
        
        for (let i = 0; i < confettiCount; i++) {
            const confetti = document.createElement('div');
            confetti.style.position = 'fixed';
            confetti.style.width = '10px';
            confetti.style.height = '10px';
            confetti.style.backgroundColor = colors[Math.floor(Math.random() * colors.length)];
            confetti.style.left = Math.random() * 100 + '%';
            confetti.style.top = '-10px';
            confetti.style.borderRadius = '50%';
            confetti.style.pointerEvents = 'none';
            confetti.style.zIndex = '9999';
            confetti.style.opacity = '0.8';
            
            document.body.appendChild(confetti);
            
            const animationDuration = Math.random() * 3 + 2;
            const horizontalMovement = (Math.random() - 0.5) * 200;
            
            confetti.animate([
                { transform: 'translateY(0) translateX(0) rotate(0deg)', opacity: 0.8 },
                { transform: `translateY(${window.innerHeight + 100}px) translateX(${horizontalMovement}px) rotate(720deg)`, opacity: 0 }
            ], {
                duration: animationDuration * 1000,
                easing: 'cubic-bezier(0.5, 0, 0.5, 1)'
            }).onfinish = () => confetti.remove();
        }
    }
    
    // Success animation with score display
    function showSuccessAnimation(score) {
        // Create confetti
        createConfetti();
        
        // Create success overlay
        const overlay = document.createElement('div');
        overlay.style.position = 'fixed';
        overlay.style.top = '0';
        overlay.style.left = '0';
        overlay.style.width = '100%';
        overlay.style.height = '100%';
        overlay.style.backgroundColor = 'rgba(0, 0, 0, 0.5)';
        overlay.style.zIndex = '9998';
        overlay.style.display = 'flex';
        overlay.style.alignItems = 'center';
        overlay.style.justifyContent = 'center';
        overlay.style.animation = 'fadeIn 0.3s ease-in';
        
        const successBox = document.createElement('div');
        successBox.style.backgroundColor = 'white';
        successBox.style.padding = '40px';
        successBox.style.borderRadius = '20px';
        successBox.style.textAlign = 'center';
        successBox.style.boxShadow = '0 10px 40px rgba(0,0,0,0.3)';
        successBox.style.animation = 'scaleIn 0.5s ease-out';
        successBox.style.maxWidth = '400px';
        
        successBox.innerHTML = `
            <div style="font-size: 80px; margin-bottom: 20px;">🎉</div>
            <h2 style="color: #22c55e; font-size: 32px; margin-bottom: 10px; font-weight: bold;">إجابة صحيحة!</h2>
            <div style="font-size: 48px; color: #f59e0b; font-weight: bold; margin: 20px 0;">
                ${score} / 100
            </div>
            <p style="color: #666; font-size: 18px; margin-top: 10px;">أحسنت! تم حفظ النتيجة</p>
        `;
        
        overlay.appendChild(successBox);
        document.body.appendChild(overlay);
        
        // Remove overlay after 3 seconds
        setTimeout(() => {
            overlay.style.animation = 'fadeOut 0.3s ease-out';
            setTimeout(() => overlay.remove(), 300);
        }, 3000);
    }
    
    // Add CSS animations
    const style = document.createElement('style');
    style.textContent = `
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        @keyframes fadeOut {
            from { opacity: 1; }
            to { opacity: 0; }
        }
        @keyframes scaleIn {
            from { transform: scale(0.5); opacity: 0; }
            to { transform: scale(1); opacity: 1; }
        }
        .success-pulse {
            animation: pulse 0.5s ease-in-out;
        }
        @keyframes pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.1); }
        }
    `;
    document.head.appendChild(style);

    // Drag and drop functionality for reordering clocks
    let wordClockDraggedIndex = null;

    function setupWordClockDragAndDrop() {
        const clockItems = wordClockContainer.querySelectorAll('.word-clock-arrangement-item');
        
        clockItems.forEach((item) => {
            item.addEventListener('dragstart', function(e) {
                const items = Array.from(wordClockContainer.querySelectorAll('.word-clock-arrangement-item'));
                wordClockDraggedIndex = items.indexOf(this);
                this.style.opacity = '0.5';
                e.dataTransfer.effectAllowed = 'move';
            });

            item.addEventListener('dragend', function() {
                this.style.opacity = '1';
                const items = wordClockContainer.querySelectorAll('.word-clock-arrangement-item');
                items.forEach(i => i.classList.remove('drag-over'));
            });

            item.addEventListener('dragover', function(e) {
                e.preventDefault();
                e.dataTransfer.dropEffect = 'move';
                const items = Array.from(wordClockContainer.querySelectorAll('.word-clock-arrangement-item'));
                const currentDraggedIndex = items.indexOf(document.querySelector('.word-clock-arrangement-item[draggable="true"][style*="opacity: 0.5"]'));
                if (currentDraggedIndex !== null && items.indexOf(this) !== currentDraggedIndex) {
                    this.classList.add('drag-over');
                }
            });

            item.addEventListener('dragleave', function() {
                this.classList.remove('drag-over');
            });

            item.addEventListener('drop', function(e) {
                e.preventDefault();
                this.classList.remove('drag-over');

                if (wordClockDraggedIndex !== null) {
                    const items = Array.from(wordClockContainer.querySelectorAll('.word-clock-arrangement-item'));
                    const draggedItem = items[wordClockDraggedIndex];
                    const targetIndex = items.indexOf(this);

                    if (wordClockDraggedIndex !== targetIndex) {
                        // Move the dragged item to the target position
                        if (wordClockDraggedIndex < targetIndex) {
                            wordClockContainer.insertBefore(draggedItem, this.nextSibling);
                        } else {
                            wordClockContainer.insertBefore(draggedItem, this);
                        }

                        // Update sentence display
                        updateWordClockSentenceDisplay();
                    }
                }
            });
        });
    }

    // Initialize drag and drop
    if (wordClockContainer) {
        setupWordClockDragAndDrop();
    }

    function updateWordClockSentenceDisplay() {
        // Get current order from DOM (left to right)
        const items = Array.from(wordClockContainer.querySelectorAll('.word-clock-arrangement-item'));
        const currentOrder = items.map(item => item.dataset.word);
        
        if (currentOrder.length === 0) {
            wordClockArrangedSentence.innerHTML = '<span class="text-gray-400 italic">قم بترتيب الساعات من الأصغر إلى الأكبر...</span>';
            return;
        }

        wordClockArrangedSentence.innerHTML = currentOrder.map(word => 
            `<span class="px-3 py-1 bg-pink-100 border border-pink-300 rounded">${word}</span>`
        ).join('');
    }

    // Function to calculate score based on correct word positions
    function calculateScore(userOrder, correctOrder, isTimeOrdered, isSentenceCorrect) {
        if (isTimeOrdered && isSentenceCorrect) {
            return {
                total: 100,
                timeOrderScore: 50,
                wordPositionScore: 50,
                timeOrderDetails: { correct: 'all', total: 'all' },
                wordPositionDetails: { correct: userOrder.length, total: correctOrder.length }
            };
        }
        
        if (correctOrder.length === 0) {
            return {
                total: 0,
                timeOrderScore: 0,
                wordPositionScore: 0,
                timeOrderDetails: { correct: 0, total: 0 },
                wordPositionDetails: { correct: 0, total: 0 }
            };
        }
        
        let score = 0;
        const totalWords = correctOrder.length;
        let timeOrderScore = 0;
        let wordPositionScore = 0;
        let correctTimePairs = 0;
        let totalTimePairs = 0;
        let correctPositions = 0;
        
        // Calculate time ordering score (50% of total)
        const items = Array.from(wordClockContainer.querySelectorAll('.word-clock-arrangement-item'));
        if (isTimeOrdered) {
            timeOrderScore = 50;
            correctTimePairs = items.length > 1 ? items.length - 1 : 0;
            totalTimePairs = items.length > 1 ? items.length - 1 : 0;
        } else {
            // Partial credit for time ordering - check how many consecutive pairs are correct
            totalTimePairs = items.length > 1 ? items.length - 1 : 0;
            for (let i = 0; i < items.length - 1; i++) {
                const timeA = parseInt(items[i].dataset.hour) * 60 + parseInt(items[i].dataset.minute);
                const timeB = parseInt(items[i + 1].dataset.hour) * 60 + parseInt(items[i + 1].dataset.minute);
                if (timeA <= timeB) {
                    correctTimePairs++;
                }
            }
            if (totalTimePairs > 0) {
                timeOrderScore = Math.round((correctTimePairs / totalTimePairs) * 50);
            }
        }
        
        // Calculate word position score (50% of total)
        for (let i = 0; i < Math.min(userOrder.length, correctOrder.length); i++) {
            if (userOrder[i] === correctOrder[i]) {
                correctPositions++;
            }
        }
        wordPositionScore = Math.round((correctPositions / totalWords) * 50);
        
        score = timeOrderScore + wordPositionScore;
        
        return {
            total: Math.max(0, Math.min(100, score)),
            timeOrderScore: timeOrderScore,
            wordPositionScore: wordPositionScore,
            timeOrderDetails: { correct: correctTimePairs, total: totalTimePairs },
            wordPositionDetails: { correct: correctPositions, total: totalWords }
        };
    }

    // Check answer - verify clocks are ordered by time, then check words
    if (wordClockCheckAnswerBtn) {
        wordClockCheckAnswerBtn.addEventListener('click', function() {
            const items = Array.from(wordClockContainer.querySelectorAll('.word-clock-arrangement-item'));
            
            // Check if clocks are ordered by time (smallest to largest)
            let isTimeOrdered = true;
            for (let i = 0; i < items.length - 1; i++) {
                const timeA = parseInt(items[i].dataset.hour) * 60 + parseInt(items[i].dataset.minute);
                const timeB = parseInt(items[i + 1].dataset.hour) * 60 + parseInt(items[i + 1].dataset.minute);
                if (timeA > timeB) {
                    isTimeOrdered = false;
                    break;
                }
            }
            
            // Get the words in current order
            const userOrder = items.map(item => item.dataset.word);
            
            // Check if order matches correct sentence
            const userSentence = userOrder.join(' ');
            const isSentenceCorrect = userSentence.trim() === wordClockCorrectSentence.trim();

            // Calculate score with detailed breakdown
            const scoreDetails = calculateScore(userOrder, wordClockCorrectOrder, isTimeOrdered, isSentenceCorrect);
            const score = scoreDetails.total;
            
            // Store score for word clock arrangement game
            if (typeof window.gameScores !== 'undefined') {
                window.gameScores.wordclock = score;
            }

            wordClockResultMessage.classList.remove('hidden');
            
            // Save score to database (always save, regardless of correctness)
            if (gameId) {
                const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
                fetch(saveScoreRoute, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken || '',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        game_id: gameId,
                        score: score
                    })
                })
                .then(response => {
                    if (!response.ok) {
                        return response.json().then(err => Promise.reject(err));
                    }
                    return response.json();
                })
                .then(data => {
                    console.log('Score saved successfully:', data);
                })
                .catch(error => {
                    console.error('Error saving score:', error);
                });
            }
            
            if (isTimeOrdered && isSentenceCorrect) {
                // Perfect answer - show success animation
                showSuccessAnimation(score);
                
                // Add pulse animation to result message
                wordClockResultMessage.className = 'mt-6 p-4 rounded-lg text-center text-lg font-semibold bg-green-100 text-green-800 border border-green-300 success-pulse';
                wordClockResultMessage.innerHTML = '✓ إجابة صحيحة! أحسنت! <br><span style="font-size: 24px; color: #f59e0b; font-weight: bold;">النتيجة: ' + score + ' / 100</span>';
                
                // Disable the check button after correct answer
                wordClockCheckAnswerBtn.disabled = true;
                wordClockCheckAnswerBtn.style.opacity = '0.5';
                wordClockCheckAnswerBtn.style.cursor = 'not-allowed';
                // Move to next game after 2 seconds
                if (typeof moveToNextGame === 'function') {
                    setTimeout(() => moveToNextGame(), 2000);
                }
            } else {
                // Wrong answer - disable button, show correct answer, show score, and redirect
                wordClockCheckAnswerBtn.disabled = true;
                wordClockCheckAnswerBtn.style.opacity = '0.5';
                wordClockCheckAnswerBtn.style.cursor = 'not-allowed';
                
                // Partial or incorrect answer - show detailed score breakdown
                let errorMsg = '✗ إجابة غير صحيحة.<br><br>';
                errorMsg += '<div class="mt-4 p-3 bg-yellow-50 border border-yellow-300 rounded-lg">';
                errorMsg += '<strong>الإجابة الصحيحة هي:</strong><br>';
                errorMsg += '<span class="text-xl font-bold text-green-700">' + wordClockCorrectSentence + '</span>';
                errorMsg += '</div>';
                
                // Add detailed score breakdown
                errorMsg += '<div style="margin-top: 15px; padding: 15px; background: #fef3c7; border-radius: 8px; text-align: right; direction: rtl;">';
                errorMsg += '<div style="font-size: 22px; color: #f59e0b; font-weight: bold; margin-bottom: 10px;">النتيجة الإجمالية: ' + score + ' / 100</div>';
                errorMsg += '<div style="font-size: 16px; color: #78350f; margin: 8px 0;">';
                errorMsg += '• ترتيب الساعات: ' + scoreDetails.timeOrderScore + ' / 50';
                errorMsg += ' (' + scoreDetails.timeOrderDetails.correct + ' من ' + scoreDetails.timeOrderDetails.total + ' أزواج صحيحة)';
                errorMsg += '</div>';
                errorMsg += '<div style="font-size: 16px; color: #78350f; margin: 8px 0;">';
                errorMsg += '• ترتيب الكلمات: ' + scoreDetails.wordPositionScore + ' / 50';
                errorMsg += ' (' + scoreDetails.wordPositionDetails.correct + ' من ' + scoreDetails.wordPositionDetails.total + ' كلمة في المكان الصحيح)';
                errorMsg += '</div>';
                errorMsg += '</div>';
                
                wordClockResultMessage.className = 'mt-6 p-4 rounded-lg text-center text-lg font-semibold bg-red-100 text-red-800 border border-red-300';
                wordClockResultMessage.innerHTML = errorMsg;
                
                // Stay on this page. Continue to next game / completion popup.
                if (typeof moveToNextGame === 'function') {
                    setTimeout(() => moveToNextGame(), 2000);
                }
            }
        });
    }
});
</script>
@endif

@if(isset($scrambledClocksGame) && $scrambledClocksGame && $scrambledClocksGame->game_data)
<script>
document.addEventListener('DOMContentLoaded', function() {
    const clocksContainer = document.getElementById('clocksContainer');
    const arrangedSentence = document.getElementById('arrangedSentence');
    const checkAnswerBtn = document.getElementById('checkAnswerBtn');
    const resultMessage = document.getElementById('resultMessage');
    
    // Get the lesson ID for this specific game
    const scrambledClocksLessonId = @json($scrambledClocksGame->lesson_id ?? null);
    const scrambledClocksLessonRoute = scrambledClocksLessonId ? @json(route('student.lesson.view', $scrambledClocksGame->lesson_id)) : null;
    
    const correctSentence = @json($correctSentence);
    const allWords = @json($words);
    // Calculate correct order by sorting words by time (smallest to largest)
    const correctOrder = allWords
        .map((w, idx) => ({ word: w.word, time: w.hour * 60 + w.minute, index: idx }))
        .sort((a, b) => a.time - b.time)
        .map(w => w.word);

    // Drag and drop functionality for reordering clocks
    let draggedIndex = null;

    function setupDragAndDrop() {
        const clockItems = clocksContainer.querySelectorAll('.clock-word-item');
        
        clockItems.forEach((item) => {
            item.addEventListener('dragstart', function(e) {
                const items = Array.from(clocksContainer.querySelectorAll('.clock-word-item'));
                draggedIndex = items.indexOf(this);
                this.style.opacity = '0.5';
                e.dataTransfer.effectAllowed = 'move';
            });

            item.addEventListener('dragend', function() {
                this.style.opacity = '1';
                const items = clocksContainer.querySelectorAll('.clock-word-item');
                items.forEach(i => i.classList.remove('drag-over'));
            });

        item.addEventListener('dragover', function(e) {
            e.preventDefault();
            e.dataTransfer.dropEffect = 'move';
            const items = Array.from(clocksContainer.querySelectorAll('.clock-word-item'));
            const currentDraggedIndex = items.indexOf(document.querySelector('.clock-word-item[draggable="true"][style*="opacity: 0.5"]'));
            if (currentDraggedIndex !== null && items.indexOf(this) !== currentDraggedIndex) {
                this.classList.add('drag-over');
            }
        });

        item.addEventListener('dragleave', function() {
            this.classList.remove('drag-over');
        });

        item.addEventListener('drop', function(e) {
            e.preventDefault();
            this.classList.remove('drag-over');

            if (draggedIndex !== null) {
                const items = Array.from(clocksContainer.querySelectorAll('.clock-word-item'));
                const draggedItem = items[draggedIndex];
                const targetIndex = items.indexOf(this);

                if (draggedIndex !== targetIndex) {
                    // Move the dragged item to the target position
                    if (draggedIndex < targetIndex) {
                        clocksContainer.insertBefore(draggedItem, this.nextSibling);
                    } else {
                        clocksContainer.insertBefore(draggedItem, this);
                    }

                    // Update sentence display
                    updateSentenceDisplay();
                }
            }
        });
        });
    }

    // Initialize drag and drop
    setupDragAndDrop();


    function updateSentenceDisplay() {
        // Get current order from DOM (left to right)
        const items = Array.from(clocksContainer.querySelectorAll('.clock-word-item'));
        const currentOrder = items.map(item => item.dataset.word);
        
        if (currentOrder.length === 0) {
            arrangedSentence.innerHTML = '<span class="text-gray-400 italic">قم بترتيب الساعات من الأصغر إلى الأكبر...</span>';
            return;
        }

        arrangedSentence.innerHTML = currentOrder.map(word => 
            `<span class="px-3 py-1 bg-pink-100 border border-pink-300 rounded">${word}</span>`
        ).join('');
    }

    // Check answer - verify clocks are ordered by time, then check words
    checkAnswerBtn.addEventListener('click', function() {
        const items = Array.from(clocksContainer.querySelectorAll('.clock-word-item'));
        
        // Check if clocks are ordered by time (smallest to largest)
        let isTimeOrdered = true;
        for (let i = 0; i < items.length - 1; i++) {
            const timeA = parseInt(items[i].dataset.hour) * 60 + parseInt(items[i].dataset.minute);
            const timeB = parseInt(items[i + 1].dataset.hour) * 60 + parseInt(items[i + 1].dataset.minute);
            if (timeA > timeB) {
                isTimeOrdered = false;
                break;
            }
        }
        
        // Get the words in current order
        const userOrder = items.map(item => item.dataset.word);
        
        // Check if order matches correct sentence
        const userSentence = userOrder.join(' ');
        const isSentenceCorrect = userSentence.trim() === correctSentence.trim();

        resultMessage.classList.remove('hidden');
        if (isTimeOrdered && isSentenceCorrect) {
            resultMessage.className = 'mt-6 p-4 rounded-lg text-center text-lg font-semibold bg-green-100 text-green-800 border border-green-300';
            resultMessage.textContent = '✓ إجابة صحيحة! أحسنت!';
            // Move to next game after 2 seconds
            if (typeof moveToNextGame === 'function') {
                setTimeout(() => moveToNextGame(), 2000);
            }
        } else {
            // Wrong answer - disable button, show correct answer, show score, and redirect
            checkAnswerBtn.disabled = true;
            checkAnswerBtn.style.opacity = '0.5';
            checkAnswerBtn.style.cursor = 'not-allowed';
            
            // Set score to 0 for wrong answer
            if (typeof window.gameScores !== 'undefined') {
                window.gameScores.scrambledclocks = 0;
            }
            
            // Show correct answer and score
            let errorMsg = '✗ إجابة غير صحيحة.<br><br>';
            errorMsg += '<div class="mt-4 p-3 bg-yellow-50 border border-yellow-300 rounded-lg">';
            errorMsg += '<strong>الإجابة الصحيحة هي:</strong><br>';
            errorMsg += '<span class="text-xl font-bold text-green-700">' + correctSentence + '</span>';
            errorMsg += '</div>';
            errorMsg += '<div class="mt-4 p-3 bg-blue-50 border border-blue-300 rounded-lg">';
            errorMsg += '<strong>النتيجة:</strong> <span class="text-2xl font-bold text-blue-700">0</span>';
            errorMsg += '</div>';
            
            resultMessage.className = 'mt-6 p-4 rounded-lg text-center text-lg font-semibold bg-red-100 text-red-800 border border-red-300';
            resultMessage.innerHTML = errorMsg;
            
            // Stay on this page. Continue to next game / completion popup.
            if (typeof moveToNextGame === 'function') {
                setTimeout(() => moveToNextGame(), 2000);
            }
        }
    });
});
</script>
    @endif
@endif

@if(isset($wordSearchGame) && $wordSearchGame && !empty($wordSearchGame->grid_data))
<script>
document.addEventListener('DOMContentLoaded', function() {
    const grid = @json($grid ?? []);
    const wordPositions = @json($wordPositions ?? []);
    const words = @json($words ?? []);
    const gridSize = {{ $gridSize ?? 10 }};
    
    // Girl-friendly color palette (pinks, purples, pastels)
    const wordColors = [
        '#FFB6C1', // Light Pink
        '#FFC0CB', // Pink
        '#FF69B4', // Hot Pink
        '#FF1493', // Deep Pink
        '#DA70D6', // Orchid
        '#BA55D3', // Medium Orchid
        '#DDA0DD', // Plum
        '#EE82EE', // Violet
        '#DA70D6', // Orchid
        '#C71585', // Medium Violet Red
        '#FFB6E1', // Light Pink
        '#FF91A4', // Pink
        '#F0A3FF', // Lavender Pink
        '#E6A8D7', // Pink Lavender
        '#FFBFF9'  // Very Light Pink
    ];
    
    let selectedCells = [];
    let isSelecting = false;
    let foundWords = new Map(); // Map of wordIndex -> color
    let startCell = null;
    let selectionDirection = null;
    let score = 0;
    const scorePerWord = Math.round(100 / (words.length || 1)); // Calculate score per word
    
    const gridElement = document.getElementById('wordSearchGrid');
    const cells = gridElement.querySelectorAll('.word-search-cell');
    const wordItems = document.querySelectorAll('.word-item');
    const completionMessage = document.getElementById('completionMessageOverlay');
    
    // Function to close the completion overlay
    window.closeCompletionOverlay = function() {
        const overlay = document.getElementById('completionMessageOverlay');
        if (overlay) {
            overlay.classList.add('hidden');
            overlay.style.display = 'none';
            // Always call moveToNextGame - it will handle showing completion popup if last game
            if (typeof moveToNextGame === 'function') {
                setTimeout(() => moveToNextGame(), 500);
            }
        }
    };
    
    // Initialize color boxes
    wordItems.forEach((item, index) => {
        const colorBox = item.querySelector('.word-color-box');
        if (colorBox) {
            colorBox.style.backgroundColor = wordColors[index % wordColors.length];
            colorBox.style.borderColor = wordColors[index % wordColors.length];
        }
    });
    
    // Initialize cell event listeners
    cells.forEach(cell => {
        cell.addEventListener('mousedown', startSelection);
        cell.addEventListener('mouseenter', continueSelection);
        cell.addEventListener('mouseup', endSelection);
        cell.addEventListener('touchstart', startSelection, { passive: false });
        cell.addEventListener('touchmove', continueSelection, { passive: false });
        cell.addEventListener('touchend', endSelection, { passive: false });
    });
    
    function startSelection(e) {
        e.preventDefault();
        isSelecting = true;
        selectedCells = [];
        startCell = e.target.closest('.word-search-cell');
        // Allow selection even if cell is already found (words can share letters)
        if (startCell) {
            selectedCells.push(startCell);
            // Add selected class even if found (will show temporary selection)
            if (!startCell.classList.contains('selected')) {
                startCell.classList.add('selected');
            }
            selectionDirection = null;
        }
    }
    
    function continueSelection(e) {
        if (!isSelecting || !startCell) return;
        e.preventDefault();
        
        const cell = e.target.closest('.word-search-cell');
        // Allow selection through found cells (words can share letters and cross)
        if (!cell || cell === startCell) return;
        
        // Determine direction from start cell
        if (selectedCells.length === 1) {
            selectionDirection = getDirection(
                parseInt(startCell.dataset.row),
                parseInt(startCell.dataset.col),
                parseInt(cell.dataset.row),
                parseInt(cell.dataset.col)
            );
        }
        
        // Check if cell is in the same direction (allowing both forward and backward)
        if (selectionDirection) {
            const startRow = parseInt(startCell.dataset.row);
            const startCol = parseInt(startCell.dataset.col);
            const cellRow = parseInt(cell.dataset.row);
            const cellCol = parseInt(cell.dataset.col);
            
            const dRow = cellRow - startRow;
            const dCol = cellCol - startCol;
            
            // Check if cell is in the determined direction (much more forgiving for diagonals)
            let inDirection = false;
            if (selectionDirection.row === 0) {
                // Horizontal: check if same row and correct column direction
                inDirection = cellRow === startRow && Math.sign(dCol) === Math.sign(selectionDirection.col);
            } else if (selectionDirection.col === 0) {
                // Vertical: check if same column and correct row direction
                inDirection = cellCol === startCol && Math.sign(dRow) === Math.sign(selectionDirection.row);
            } else {
                // Diagonal: MUCH more forgiving - use the same logic as isValidDirection
                const absRow = Math.abs(dRow);
                const absCol = Math.abs(dCol);
                const maxDiff = Math.max(absRow, absCol);
                const minDiff = Math.min(absRow, absCol);
                const ratio = maxDiff > 0 ? minDiff / maxDiff : 0;
                
                // Check direction signs match (must go in same general direction)
                const correctDirection = Math.sign(dRow) === Math.sign(selectionDirection.row) && 
                                       Math.sign(dCol) === Math.sign(selectionDirection.col);
                
                if (correctDirection) {
                    // Allow perfect diagonal, near-diagonal (up to 40% deviation), or short distances
                    if (absRow === absCol) {
                        // Perfect diagonal
                        inDirection = true;
                    } else if (ratio >= 0.6) {
                        // Near-diagonal (very forgiving - up to 40% deviation)
                        inDirection = true;
                    } else if (maxDiff <= 3) {
                        // For short selections, be very forgiving
                        inDirection = true;
                    }
                }
            }
            
            if (inDirection) {
                // Clear previous selection except the start
                // Allow clearing even if found (temporary selection state)
                selectedCells.slice(1).forEach(c => {
                    // Only remove 'selected' class, keep 'found' class if present
                    c.classList.remove('selected');
                });
                selectedCells = [startCell];
                
                // Build selection path in the determined direction
                // Include found cells - words can share letters
                const path = buildPath(startCell, cell, selectionDirection);
                path.forEach(c => {
                    if (!selectedCells.includes(c)) {
                        selectedCells.push(c);
                        // Add selected class for visual feedback (even if already found)
                        if (!c.classList.contains('selected')) {
                            c.classList.add('selected');
                        }
                    }
                });
            }
        }
    }
    
    function endSelection(e) {
        if (!isSelecting) return;
        e.preventDefault();
        isSelecting = false;
        
        if (selectedCells.length >= 2) {
            checkWord();
        }
        clearSelection();
        startCell = null;
        selectionDirection = null;
    }
    
    function getDirection(startRow, startCol, endRow, endCol) {
        const dRow = endRow - startRow;
        const dCol = endCol - startCol;
        
        if (dRow === 0 && dCol === 0) return null;
        
        const absRow = Math.abs(dRow);
        const absCol = Math.abs(dCol);
        const maxDiff = Math.max(absRow, absCol);
        const minDiff = Math.min(absRow, absCol);
        const ratio = minDiff / maxDiff; // Ratio to determine how close to diagonal
        
        // Make diagonal detection MUCH more forgiving (up to 40% deviation)
        // This makes diagonal selection much easier
        const diagonalThreshold = 0.6; // Allow up to 40% deviation from perfect diagonal
        
        if (absRow === 0) {
            // Perfect horizontal
            return { row: 0, col: dCol > 0 ? 1 : -1 };
        } else if (absCol === 0) {
            // Perfect vertical
            return { row: dRow > 0 ? 1 : -1, col: 0 };
        } else if (ratio >= diagonalThreshold || maxDiff <= 2) {
            // Diagonal or near-diagonal (much more forgiving)
            // If ratio is high enough (close to 1:1) OR if the distance is small, treat as diagonal
            return { 
                row: dRow > 0 ? 1 : -1, 
                col: dCol > 0 ? 1 : -1 
            };
        } else if (absRow > absCol * 2) {
            // Much more vertical than horizontal
            return { row: dRow > 0 ? 1 : -1, col: 0 };
        } else if (absCol > absRow * 2) {
            // Much more horizontal than vertical
            return { row: 0, col: dCol > 0 ? 1 : -1 };
        } else {
            // Still treat as diagonal if reasonably close
            return { 
                row: dRow > 0 ? 1 : -1, 
                col: dCol > 0 ? 1 : -1 
            };
        }
    }
    
    function isValidDirection(cell, direction) {
        if (!startCell || selectedCells.length < 1) return false;
        
        const startRow = parseInt(startCell.dataset.row);
        const startCol = parseInt(startCell.dataset.col);
        const cellRow = parseInt(cell.dataset.row);
        const cellCol = parseInt(cell.dataset.col);
        
        const dRow = cellRow - startRow;
        const dCol = cellCol - startCol;
        
        // Check if cell is in the same direction (much more tolerant for diagonals)
        if (direction.row === 0) {
            // Horizontal: must be same row, correct column direction
            return dRow === 0 && Math.sign(dCol) === Math.sign(direction.col);
        } else if (direction.col === 0) {
            // Vertical: must be same column, correct row direction
            return dCol === 0 && Math.sign(dRow) === Math.sign(direction.row);
        } else {
            // Diagonal: MUCH more forgiving - allow significant deviations
            const absRow = Math.abs(dRow);
            const absCol = Math.abs(dCol);
            const maxDiff = Math.max(absRow, absCol);
            const minDiff = Math.min(absRow, absCol);
            const ratio = minDiff / maxDiff;
            
            // Check direction signs match (must go in same general direction)
            const correctDirection = Math.sign(dRow) === Math.sign(direction.row) && 
                                   Math.sign(dCol) === Math.sign(direction.col);
            
            if (!correctDirection) return false;
            
            // For diagonal, be very forgiving:
            // - Perfect diagonal (1:1 ratio)
            // - Near-diagonal (ratio >= 0.6, meaning up to 40% deviation)
            // - Small distances (allow more deviation for shorter selections)
            if (absRow === absCol) {
                // Perfect diagonal
                return true;
            } else if (ratio >= 0.6) {
                // Near-diagonal (very forgiving - up to 40% deviation)
                return true;
            } else if (maxDiff <= 3) {
                // For very short selections, be even more forgiving
                return true;
            }
            
            return false;
        }
    }
    
    function buildPath(startCell, endCell, direction) {
        const path = [];
        const startRow = parseInt(startCell.dataset.row);
        const startCol = parseInt(startCell.dataset.col);
        const endRow = parseInt(endCell.dataset.row);
        const endCol = parseInt(endCell.dataset.col);
        
        // Calculate actual distance to end cell
        const totalRowDiff = endRow - startRow;
        const totalColDiff = endCol - startCol;
        const absRowDiff = Math.abs(totalRowDiff);
        const absColDiff = Math.abs(totalColDiff);
        
        // For diagonals, use the maximum distance to ensure we reach the end cell
        // This makes diagonal selection much smoother
        let distance;
        if (direction.row === 0) {
            // Horizontal
            distance = absColDiff;
        } else if (direction.col === 0) {
            // Vertical
            distance = absRowDiff;
        } else {
            // Diagonal - use maximum to ensure smooth path to end
            distance = Math.max(absRowDiff, absColDiff);
        }
        
        // Build path from start to end, following the direction smoothly
        for (let i = 1; i <= distance; i++) {
            let row, col;
            
            if (direction.row === 0) {
                // Horizontal
                row = startRow;
                col = startCol + (direction.col * i);
            } else if (direction.col === 0) {
                // Vertical
                row = startRow + (direction.row * i);
                col = startCol;
            } else {
                // Diagonal - use smooth interpolation for near-diagonals
                // This makes selection much easier and smoother
                const progress = i / distance;
                
                // Interpolate based on actual end position for smoother paths
                row = Math.round(startRow + (totalRowDiff * progress));
                col = Math.round(startCol + (totalColDiff * progress));
                
                // For perfect diagonals, ensure we use direction properly
                if (absRowDiff === absColDiff) {
                    // Perfect diagonal: use direction directly
                    row = startRow + (direction.row * i);
                    col = startCol + (direction.col * i);
                }
            }
            
            // Check bounds
            if (row < 0 || row >= gridSize || col < 0 || col >= gridSize) break;
            
            const cell = document.querySelector(`[data-row="${row}"][data-col="${col}"]`);
            // Include found cells in path - words can share letters
            if (cell) {
                path.push(cell);
            }
        }
        
        return path;
    }
    
    function clearSelection() {
        selectedCells.forEach(cell => {
            // Remove selected class, but keep found class if present
            // This allows words to share letters - each word keeps its own color
            cell.classList.remove('selected');
        });
        selectedCells = [];
    }
    
    function checkWord() {
        if (selectedCells.length < 2) return;
        
        // Sort selected cells by position to ensure correct reading order
        // For RTL horizontal: sort by column descending (right to left)
        // For LTR horizontal: sort by column ascending (left to right)
        // For vertical: sort by row
        // For diagonal: sort based on direction
        const sortedCells = [...selectedCells].sort((a, b) => {
            const aRow = parseInt(a.dataset.row);
            const aCol = parseInt(a.dataset.col);
            const bRow = parseInt(b.dataset.row);
            const bCol = parseInt(b.dataset.col);
            
            // Determine if selection is horizontal, vertical, or diagonal
            const rowDiff = Math.abs(aRow - bRow);
            const colDiff = Math.abs(aCol - bCol);
            
            if (rowDiff === 0) {
                // Horizontal: for RTL, sort by column descending (right to left)
                // Check if selection goes right to left (RTL) or left to right (LTR)
                const isRTL = selectedCells[0] && selectedCells[selectedCells.length - 1] &&
                    parseInt(selectedCells[0].dataset.col) > parseInt(selectedCells[selectedCells.length - 1].dataset.col);
                return isRTL ? bCol - aCol : aCol - bCol;
            } else if (colDiff === 0) {
                // Vertical: sort by row
                return aRow - bRow;
            } else {
                // Diagonal: sort by the primary direction
                // Determine direction from the cells themselves
                const firstRow = parseInt(selectedCells[0].dataset.row);
                const firstCol = parseInt(selectedCells[0].dataset.col);
                
                // Calculate direction based on distance from first cell
                const aDistRow = aRow - firstRow;
                const aDistCol = aCol - firstCol;
                const bDistRow = bRow - firstRow;
                const bDistCol = bCol - firstCol;
                
                // For diagonal, use the maximum distance (row or col) to sort
                // This ensures proper ordering along the diagonal line
                const aMaxDist = Math.max(Math.abs(aDistRow), Math.abs(aDistCol));
                const bMaxDist = Math.max(Math.abs(bDistRow), Math.abs(bDistCol));
                
                // Sort by maximum distance from start
                if (aMaxDist !== bMaxDist) {
                    return aMaxDist - bMaxDist;
                }
                
                // If same distance, sort by row (or could use col)
                return aRow - bRow;
            }
        });
        
        // Get positions in sorted order
        const selectedPositions = sortedCells.map(cell => ({
            row: parseInt(cell.dataset.row),
            col: parseInt(cell.dataset.col)
        }));
        
        // Get word from sorted cells to ensure correct letter order
        // For RTL: letters should be read from right to left (ا on right, ة on left)
        const selectedWord = sortedCells.map(cell => cell.dataset.letter).join('');
        // Also check reversed (in case user selected in opposite direction)
        const selectedWordReversed = selectedWord.split('').reverse().join('');
        
        // Check if it matches any word in the list
        words.forEach((word, index) => {
            if (foundWords.has(index)) return; // Already found
            
            // For Arabic words, check both the selected order and reversed
            // This handles both RTL selection (right to left) and LTR selection (left to right)
            let wordMatches = false;
            
            // Check if selected word matches (forward or reverse)
            if (selectedWord === word || selectedWordReversed === word) {
                wordMatches = true;
            }
            
            if (wordMatches) {
                // Check if the selection matches the word's position
                const wordPos = wordPositions.find(wp => wp.word === word);
                if (wordPos && wordPos.positions) {
                    // Check if positions match (allowing for reverse and different directions)
                    const matches = checkPositionsMatch(selectedPositions, wordPos.positions);
                    
                    if (matches) {
                        markWordAsFound(index, wordPos);
                        return; // Stop checking once found
                    } else {
                        // If word matches but positions don't exactly match, still accept it
                        // This handles cases where grid was regenerated or positions are slightly off
                        markWordAsFound(index, wordPos);
                        return;
                    }
                } else {
                    // If no position data, just check the word (for backward compatibility)
                    markWordAsFound(index, null);
                    return; // Stop checking once found
                }
            }
        });
    }
    
    function checkPositionsMatch(selectedPos, wordPos) {
        if (selectedPos.length !== wordPos.length) {
            return false;
        }
        
        // Create sets of positions for comparison
        const wordPosSet = new Set(wordPos.map(p => `${p.row},${p.col}`));
        const selectedPosSet = new Set(selectedPos.map(p => `${p.row},${p.col}`));
        
        // Check if all positions match (allowing for any order)
        const allMatch = wordPosSet.size === selectedPosSet.size && 
            [...wordPosSet].every(p => selectedPosSet.has(p));
        
        if (allMatch) {
            return true;
        }
        
        // Check reverse order
        const reversedSelected = [...selectedPos].reverse();
        const reversedSelectedSet = new Set(reversedSelected.map(p => `${p.row},${p.col}`));
        
        const reverseMatch = wordPosSet.size === reversedSelectedSet.size && 
            [...wordPosSet].every(p => reversedSelectedSet.has(p));
        
        return reverseMatch;
    }
    
    function createSparkleAnimation(element) {
        const sparkles = 20;
        for (let i = 0; i < sparkles; i++) {
            const sparkle = document.createElement('div');
            sparkle.style.position = 'absolute';
            sparkle.style.width = '6px';
            sparkle.style.height = '6px';
            sparkle.style.backgroundColor = wordColors[Math.floor(Math.random() * wordColors.length)];
            sparkle.style.borderRadius = '50%';
            sparkle.style.pointerEvents = 'none';
            sparkle.style.zIndex = '9999';
            
            const rect = element.getBoundingClientRect();
            const x = rect.left + rect.width / 2;
            const y = rect.top + rect.height / 2;
            
            sparkle.style.left = x + 'px';
            sparkle.style.top = y + 'px';
            
            document.body.appendChild(sparkle);
            
            const angle = (Math.PI * 2 * i) / sparkles;
            const distance = 50 + Math.random() * 30;
            const endX = x + Math.cos(angle) * distance;
            const endY = y + Math.sin(angle) * distance;
            
            sparkle.animate([
                { transform: 'translate(0, 0) scale(1)', opacity: 1 },
                { transform: `translate(${endX - x}px, ${endY - y}px) scale(0)`, opacity: 0 }
            ], {
                duration: 600,
                easing: 'ease-out'
            }).onfinish = () => sparkle.remove();
        }
    }
    
    function createConfetti() {
        const colors = wordColors;
        const confettiCount = 150;
        const duration = 3000;
        
        for (let i = 0; i < confettiCount; i++) {
            const confetti = document.createElement('div');
            confetti.style.position = 'fixed';
            confetti.style.width = '10px';
            confetti.style.height = '10px';
            confetti.style.backgroundColor = colors[Math.floor(Math.random() * colors.length)];
            confetti.style.left = Math.random() * 100 + '%';
            confetti.style.top = '-10px';
            confetti.style.borderRadius = Math.random() > 0.5 ? '50%' : '0';
            confetti.style.pointerEvents = 'none';
            confetti.style.zIndex = '9999';
            confetti.style.opacity = '0.9';
            
            document.body.appendChild(confetti);
            
            const animationDuration = Math.random() * 2 + 1.5;
            const horizontalMovement = (Math.random() - 0.5) * 300;
            const rotation = Math.random() * 720;
            
            confetti.animate([
                { transform: 'translateY(0) translateX(0) rotate(0deg)', opacity: 0.9 },
                { transform: `translateY(${window.innerHeight + 100}px) translateX(${horizontalMovement}px) rotate(${rotation}deg)`, opacity: 0 }
            ], {
                duration: animationDuration * 1000,
                easing: 'cubic-bezier(0.5, 0, 0.5, 1)'
            }).onfinish = () => confetti.remove();
        }
    }
    
    function markWordAsFound(wordIndex, wordPos) {
        const color = wordColors[wordIndex % wordColors.length];
        foundWords.set(wordIndex, color);
        
        // Add score for finding a word
        score += scorePerWord;
        
        // Highlight cells with the word's color with animation
        const positionsToHighlight = wordPos && wordPos.positions 
            ? wordPos.positions 
            : selectedCells.map(cell => ({
                row: parseInt(cell.dataset.row),
                col: parseInt(cell.dataset.col)
            }));
        
        let animationDelay = 0;
        positionsToHighlight.forEach((pos, idx) => {
            const cell = document.querySelector(`[data-row="${pos.row}"][data-col="${pos.col}"]`);
            if (cell) {
                // Allow cells to be part of multiple words (words can share letters)
                cell.classList.add('found');
                
                // Animate cell highlight
                setTimeout(() => {
                    cell.style.transition = 'all 0.3s ease';
                    cell.style.backgroundColor = color;
                    cell.style.borderColor = color;
                    cell.style.borderWidth = '2px';
                    cell.style.transform = 'scale(1.1)';
                    
                    // Create sparkle animation on each cell
                    createSparkleAnimation(cell);
                    
                    setTimeout(() => {
                        cell.style.transform = 'scale(1)';
                    }, 300);
                }, animationDelay);
                
                animationDelay += 50; // Stagger animations
                cell.classList.remove('selected');
                
                // Store which words this cell belongs to
                if (!cell.dataset.foundWords) {
                    cell.dataset.foundWords = '';
                }
                cell.dataset.foundWords += (cell.dataset.foundWords ? ',' : '') + wordIndex;
            }
        });
        
        // Mark word in list with animation
        const wordItem = document.querySelector(`[data-word-index="${wordIndex}"]`);
        if (wordItem) {
            wordItem.style.transition = 'all 0.5s ease';
            wordItem.classList.add('bg-pink-100', 'border-pink-500');
            wordItem.style.transform = 'scale(1.05)';
            
            const indicator = wordItem.querySelector('.found-indicator');
            if (indicator) {
                indicator.classList.remove('hidden');
                indicator.style.animation = 'bounce 0.6s ease';
            }
            
            // Create sparkle animation on word item
            setTimeout(() => {
                createSparkleAnimation(wordItem);
                wordItem.style.transform = 'scale(1)';
            }, 300);
        }
        
        // Check if all words are found
        if (foundWords.size === words.length) {
                console.log('All words found! Total words:', words.length, 'Found:', foundWords.size);
                // Calculate final score - if all words are found, set to exactly 100 to avoid rounding errors
                const finalScore = 100;
                console.log('Final score calculated:', finalScore);
                
                // Store score for word search game
                if (typeof window.gameScores !== 'undefined') {
                    window.gameScores.wordsearch = finalScore;
                }
                
                setTimeout(() => {
                    // Show completion message overlay (centered on screen)
                    const overlay = document.getElementById('completionMessageOverlay');
                    const completionMessageBox = document.getElementById('completionMessage');
                    const finalScoreEl = document.getElementById('finalScore');
                    
                    if (overlay && completionMessageBox && finalScoreEl) {
                        // Set score text
                        finalScoreEl.textContent = `النتيجة: ${finalScore} / 100`;
                        
                        // Show overlay
                        overlay.classList.remove('hidden');
                        overlay.style.display = 'flex';
                        
                        // Reset transform and trigger animation
                        completionMessageBox.style.transform = 'scale(0)';
                        completionMessageBox.style.opacity = '0';
                        setTimeout(() => {
                            completionMessageBox.style.animation = 'scaleInBounce 0.6s ease forwards';
                            completionMessageBox.style.transform = '';
                            completionMessageBox.style.opacity = '';
                        }, 10);
                    }

                    // Create confetti celebration
                    if (typeof createConfetti === 'function') {
                        createConfetti();
                    }
                    
                    // Save score
                    const gameId = @json($wordSearchGame->game_id ?? null);
                    const saveScoreRoute = @json(route('student.games.saveScore'));
                    if (gameId) {
                        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
                        fetch(saveScoreRoute, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': csrfToken || '',
                                'Accept': 'application/json'
                            },
                            body: JSON.stringify({
                                game_id: gameId,
                                score: finalScore
                            })
                        })
                        .then(response => {
                            if (!response.ok) {
                                return response.json().then(err => Promise.reject(err));
                            }
                            return response.json();
                        })
                        .then(data => {
                            console.log('Word Search score saved successfully:', data);
                            // Update gameScores
                            if (typeof window.gameScores !== 'undefined') {
                                window.gameScores.wordsearch = finalScore;
                            }
                            // Auto-close the word search completion overlay and show final popup after 3 seconds
                            setTimeout(() => {
                                const overlay = document.getElementById('completionMessageOverlay');
                                if (overlay) {
                                    overlay.classList.add('hidden');
                                    overlay.style.display = 'none';
                                }
                                // Always call moveToNextGame - it will show completion popup if last game
                                if (typeof moveToNextGame === 'function') {
                                    console.log('Calling moveToNextGame after word search completion');
                                    moveToNextGame();
                                } else {
                                    console.error('moveToNextGame function not found!');
                                }
                            }, 3000); // Show word search completion for 3 seconds, then show final popup
                        })
                        .catch(error => {
                            console.error('Error saving Word Search score:', error);
                            // Even if save fails, still show the final completion popup
                            setTimeout(() => {
                                const overlay = document.getElementById('completionMessageOverlay');
                                if (overlay) {
                                    overlay.classList.add('hidden');
                                    overlay.style.display = 'none';
                                }
                                if (typeof moveToNextGame === 'function') {
                                    moveToNextGame();
                                }
                            }, 3000);
                        });
                    } else {
                        console.error('Word Search game_id is null - cannot save score');
                        // Even without game_id, show final completion popup after delay
                        setTimeout(() => {
                            const overlay = document.getElementById('completionMessageOverlay');
                            if (overlay) {
                                overlay.classList.add('hidden');
                                overlay.style.display = 'none';
                            }
                            if (typeof moveToNextGame === 'function') {
                                moveToNextGame();
                            }
                        }, 3000);
                    }
                }, animationDelay + 200);
        }
    }
    
    // Prevent text selection while dragging
    document.addEventListener('selectstart', function(e) {
        if (isSelecting) {
            e.preventDefault();
        }
    });
});
</script>
<style>
.word-search-cell {
    user-select: none;
    -webkit-user-select: none;
    -moz-user-select: none;
    -ms-user-select: none;
}
.word-search-cell.found {
    border-width: 2px !important;
    transition: all 0.3s ease !important;
}
.word-search-cell.selected {
    background-color: #FFB6E1 !important;
    border-color: #FF69B4 !important;
    border-width: 2px !important;
}
/* Allow selected class to show on found cells (for words that share letters) */
.word-search-cell.found.selected {
    /* Show selection border on top of found background */
    box-shadow: inset 0 0 0 2px #FF69B4;
    border-color: #FF69B4 !important;
}

/* Animations */
@keyframes scaleIn {
    from {
        transform: scale(0.8);
        opacity: 0;
    }
    to {
        transform: scale(1);
        opacity: 1;
    }
}

@keyframes pulse {
    0%, 100% {
        transform: scale(1);
    }
    50% {
        transform: scale(1.1);
    }
}

@keyframes bounce {
    0%, 100% {
        transform: translateY(0);
    }
    50% {
        transform: translateY(-10px);
    }
}

@keyframes fadeIn {
    from {
        opacity: 0;
    }
    to {
        opacity: 1;
    }
}

@keyframes scaleInBounce {
    0% {
        transform: scale(0);
        opacity: 0;
    }
    50% {
        transform: scale(1.15);
        opacity: 0.9;
    }
    75% {
        transform: scale(0.95);
    }
    100% {
        transform: scale(1);
        opacity: 1;
    }
}

.word-color-box {
    transition: all 0.3s ease;
}
.word-item.bg-pink-100 .word-color-box {
    border-width: 3px;
    box-shadow: 0 2px 8px rgba(255, 105, 180, 0.3);
    animation: pulse 1s ease infinite;
}
.word-color-box {
    transition: all 0.3s ease;
}
.word-item.bg-green-100 .word-color-box {
    border-width: 3px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.2);
}
</style>
@endif

@if(isset($selectedLessonId) && $selectedLessonId && $hasMcqPairs)
@vite(['resources/js/mcq-quiz.js'])
@endif
@if(isset($selectedLessonId) && $selectedLessonId && $hasScramblePairs)
@vite(['resources/js/scramble-quiz.js'])
@endif

<style>
    .quiz-progress-btn { min-width: 2.5rem; min-height: 2.5rem; border-radius: 9999px; display: flex; align-items: center; justify-content: center; font-weight: bold; font-size: 1.1rem; }
    .quiz-progress-btn.active { border: 2px solid #2563eb; background: #dbeafe; }
    .quiz-progress-btn.correct { background: #bbf7d0; color: #15803d; border: 2px solid #22c55e; }
    .quiz-progress-btn.wrong { background: #fecaca; color: #b91c1c; border: 2px solid #ef4444; }
    #scrambleBtn { background-color: #FC8EAC !important; color: white !important; }
    .clock-word-item:hover { transform: scale(1.05); transition: transform 0.2s; }
    .clock-word-item:active { transform: scale(0.95); }
    .clock-word-item.drag-over { border: 2px dashed #10b981; border-radius: 8px; padding: 4px; }
    .word-clock-arrangement-item:hover { transform: scale(1.05); transition: transform 0.2s; }
    .word-clock-arrangement-item:active { transform: scale(0.95); }
    .word-clock-arrangement-item.drag-over { border: 2px dashed #10b981; border-radius: 8px; padding: 4px; }
    
    /* Matching Pairs Game Styles */
    .matching-item {
        transition: all 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);
        min-height: 110px;
        display: flex;
        align-items: center;
        position: relative;
        animation: slideInFade 0.6s ease-out backwards;
        width: 100%;
    }
    
    /* Ensure consistent spacing between items */
    #leftItems, #rightItems {
        display: flex;
        flex-direction: column;
        gap: 1.25rem;
    }
    
    /* Better alignment for columns */
    .left-column, .right-column {
        display: flex;
        flex-direction: column;
        align-items: stretch;
    }
    
    #matchingPairsContainer {
        padding: 1rem 0;
    }
    
    /* Staggered entrance animation delays */
    .left-item:nth-child(1), .right-item:nth-child(1) { animation-delay: 0.1s; }
    .left-item:nth-child(2), .right-item:nth-child(2) { animation-delay: 0.2s; }
    .left-item:nth-child(3), .right-item:nth-child(3) { animation-delay: 0.3s; }
    .left-item:nth-child(4), .right-item:nth-child(4) { animation-delay: 0.4s; }
    .left-item:nth-child(5), .right-item:nth-child(5) { animation-delay: 0.5s; }
    .left-item:nth-child(6), .right-item:nth-child(6) { animation-delay: 0.6s; }
    .left-item:nth-child(7), .right-item:nth-child(7) { animation-delay: 0.7s; }
    .left-item:nth-child(8), .right-item:nth-child(8) { animation-delay: 0.8s; }
    .left-item:nth-child(n+9), .right-item:nth-child(n+9) { animation-delay: 0.9s; }
    
    @keyframes slideInFade {
        0% {
            opacity: 0;
            transform: translateY(30px) scale(0.9);
        }
        100% {
            opacity: 1;
            transform: translateY(0) scale(1);
        }
    }
    
    .matching-item .flex {
        width: 100%;
        height: 100%;
        align-items: center;
        justify-content: center;
        min-height: 100px;
    }
    
    /* Ensure items are properly aligned */
    .matching-item {
        box-sizing: border-box;
    }
    
    /* Better visual separation */
    .left-column, .right-column {
        position: relative;
    }
    
    .matching-item:hover {
        transform: scale(1.08) translateY(-3px);
        box-shadow: 0 12px 30px rgba(236, 72, 153, 0.3);
    }
    
    .matching-item img {
        flex-shrink: 0;
        transition: transform 0.3s ease;
    }
    
    .matching-item:hover img {
        transform: scale(1.1) rotate(2deg);
    }
    
    /* Selection animation */
    .matching-item.selected {
        animation: bounceSelect 0.4s ease, pulseGlow 1.5s ease-in-out infinite;
        z-index: 10;
    }
    
    @keyframes bounceSelect {
        0%, 100% { transform: scale(1); }
        50% { transform: scale(1.1); }
    }
    
    @keyframes pulseGlow {
        0%, 100% {
            box-shadow: 0 0 0 0 rgba(236, 72, 153, 0.7);
        }
        50% {
            box-shadow: 0 0 0 12px rgba(236, 72, 153, 0);
        }
    }
    
    /* Success animation */
    .matching-item.matched {
        animation: successBounce 0.6s ease, successGlow 2s ease-in-out infinite;
    }
    
    @keyframes successBounce {
        0% { transform: scale(1); }
        30% { transform: scale(1.15) rotate(2deg); }
        60% { transform: scale(1.1) rotate(-2deg); }
        100% { transform: scale(1); }
    }
    
    @keyframes successGlow {
        0%, 100% {
            box-shadow: 0 0 8px rgba(34, 197, 94, 0.5);
        }
        50% {
            box-shadow: 0 0 20px rgba(34, 197, 94, 0.8), 0 0 30px rgba(34, 197, 94, 0.6);
        }
    }
    
    @keyframes scaleIn {
        0% {
            transform: scale(0);
            opacity: 0;
        }
        50% {
            transform: scale(1.3);
        }
        100% {
            transform: scale(1);
            opacity: 1;
        }
    }
    
    .matching-item span {
        word-wrap: break-word;
        overflow-wrap: break-word;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    
    
    @keyframes shake {
        0%, 100% { transform: translateX(0) rotate(0deg); }
        10%, 30%, 50%, 70%, 90% { transform: translateX(-10px) rotate(-2deg); }
        20%, 40%, 60%, 80% { transform: translateX(10px) rotate(2deg); }
    }
    
    #connectionCanvas {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        pointer-events: none;
    }
    
    /* Line drawing animation */
    @keyframes drawLine {
        from {
            stroke-dasharray: 1000;
            stroke-dashoffset: 1000;
        }
        to {
            stroke-dashoffset: 0;
        }
    }
    
    #connectionCanvas path {
        stroke-dasharray: 1000;
        stroke-dashoffset: 1000;
        animation: drawLine 0.8s ease-out forwards;
        transition: stroke 0.3s ease, stroke-width 0.3s ease;
    }
    
    #connectionCanvas path.correct-line {
        animation: drawLine 0.8s ease-out forwards, linePulse 1.5s ease-in-out infinite;
    }
    
    @keyframes linePulse {
        0%, 100% {
            stroke-width: 3;
            opacity: 1;
        }
        50% {
            stroke-width: 4;
            opacity: 0.9;
        }
    }
    
    /* Score counter animation */
    @keyframes scorePop {
        0% { transform: scale(1); }
        50% { transform: scale(1.3); }
        100% { transform: scale(1); }
    }
    
    #matchingPairsScore {
        display: inline-block;
        transition: all 0.3s ease;
    }
    
    #matchingPairsScore.animate-score {
        animation: scorePop 0.5s ease;
        color: #ec4899;
        font-weight: bold;
    }
    
    /* Button animations */
    #submitMatchingPairsBtn, #resetMatchingPairsBtn {
        transition: all 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
        position: relative;
        overflow: hidden;
    }
    
    #submitMatchingPairsBtn:hover {
        transform: scale(1.05) translateY(-2px);
        box-shadow: 0 10px 20px rgba(34, 197, 94, 0.4);
    }
    
    #submitMatchingPairsBtn:active {
        transform: scale(0.98);
    }
    
    #resetMatchingPairsBtn:hover {
        transform: scale(1.05) translateY(-2px);
        box-shadow: 0 10px 20px rgba(168, 85, 247, 0.4);
    }
    
    #resetMatchingPairsBtn:active {
        transform: scale(0.98);
    }
    
    /* Game container entrance */
    .game-container[data-game-type="matchingpairs"] {
        animation: fadeInScale 0.5s ease-out;
    }
    
    @keyframes fadeInScale {
        0% {
            opacity: 0;
            transform: scale(0.95);
        }
        100% {
            opacity: 1;
            transform: scale(1);
        }
    }
    
    /* Column headers animation */
    .left-column h4, .right-column h4 {
        animation: fadeInDown 0.6s ease-out;
    }
    
    @keyframes fadeInDown {
        0% {
            opacity: 0;
            transform: translateY(-20px);
        }
        100% {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    /* Completion message animations */
    @keyframes slideUpBounce {
        0% {
            opacity: 0;
            transform: translateY(50px) scale(0.8);
        }
        60% {
            transform: translateY(-10px) scale(1.05);
        }
        100% {
            opacity: 1;
            transform: translateY(0) scale(1);
        }
    }
    
    @keyframes celebration {
        0%, 100% { transform: scale(1) rotate(0deg); }
        25% { transform: scale(1.2) rotate(-10deg); }
        75% { transform: scale(1.2) rotate(10deg); }
    }
    
    #matchingPairsCompletionMessage {
        animation: fadeInOverlay 0.3s ease;
    }
    
    @keyframes fadeInOverlay {
        from { opacity: 0; }
        to { opacity: 1; }
    }
    
    #matchingPairsCompletionMessage > div {
        animation: slideUpBounce 0.6s cubic-bezier(0.34, 1.56, 0.64, 1);
    }
    
    #matchingPairsCompletionMessage .text-6xl {
        animation: celebration 1s ease-in-out infinite;
        display: inline-block;
    }
    
    /* Sparkle effect for matched items */
    @keyframes sparkle {
        0%, 100% {
            opacity: 0;
            transform: scale(0) rotate(0deg);
        }
        50% {
            opacity: 1;
            transform: scale(1.2) rotate(180deg);
        }
    }
    
    .matching-item.matched::after {
        content: '✨';
        position: absolute;
        top: -8px;
        right: -8px;
        font-size: 20px;
        animation: sparkle 1.5s ease-in-out;
        z-index: 25;
        pointer-events: none;
    }
    
    /* Enhanced UI Animations */
    @keyframes gradient-shift {
        0%, 100% { background-position: 0% 50%; }
        50% { background-position: 100% 50%; }
    }
    
    .animate-gradient {
        background-size: 200% 200%;
        animation: gradient-shift 3s ease infinite;
    }
    
    @keyframes float-gentle {
        0%, 100% { transform: translateY(0px) rotate(0deg); }
        50% { transform: translateY(-10px) rotate(2deg); }
    }
    
    .animate-float-gentle {
        animation: float-gentle 4s ease-in-out infinite;
    }
    
    @keyframes glow-pulse {
        0%, 100% { opacity: 0.5; transform: scale(1); }
        50% { opacity: 0.8; transform: scale(1.05); }
    }
    
    /* Enhanced Select Dropdown Styling */
    select:focus {
        outline: none;
        box-shadow: 0 0 0 3px rgba(236, 72, 153, 0.1);
    }
    
    /* Smooth transitions for all interactive elements */
    * {
        transition-property: transform, opacity, box-shadow, border-color;
        transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
    }
    
    /* Enhanced card hover effects */
    .hover-lift {
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }
    
    .hover-lift:hover {
        transform: translateY(-4px);
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
    }
    
    /* Custom scrollbar for better aesthetics */
    ::-webkit-scrollbar {
        width: 10px;
        height: 10px;
    }
    
    ::-webkit-scrollbar-track {
        background: rgba(255, 182, 193, 0.1);
        border-radius: 10px;
    }
    
    ::-webkit-scrollbar-thumb {
        background: linear-gradient(to bottom, #f9a8d4, #67e8f9);
        border-radius: 10px;
        border: 2px solid rgba(255, 255, 255, 0.2);
    }
    
    ::-webkit-scrollbar-thumb:hover {
        background: linear-gradient(to bottom, #f472b6, #22d3ee);
    }
</style>
@endsection