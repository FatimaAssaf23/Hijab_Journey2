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
    
    /* Radio button card styling */
    input[type="radio"]:checked + div {
        border-width: 3px;
    }
    
    /* Step indicator animations */
    @keyframes stepComplete {
        0% { transform: scale(0); }
        50% { transform: scale(1.2); }
        100% { transform: scale(1); }
    }
    
    .step-complete {
        animation: stepComplete 0.5s ease-out;
    }
    
    /* Game card hover effects */
    .game-card-radio:checked ~ div,
    .game-card-radio:checked + div {
        transform: scale(1.05);
        box-shadow: 0 20px 40px -12px rgba(0, 0, 0, 0.25);
    }
</style>
@endpush

<div class="min-h-screen bg-gradient-to-br from-pink-50 via-rose-50/70 via-cyan-50/60 to-teal-50/50 relative overflow-hidden">
    <!-- Enhanced Animated Background Elements -->
    <div class="absolute inset-0 overflow-hidden pointer-events-none">
        <div class="absolute -top-40 -left-40 w-[500px] h-[500px] bg-pink-300/25 rounded-full opacity-15 blur-3xl animate-pulse"></div>
        <div class="absolute top-1/2 -right-40 w-[500px] h-[500px] bg-cyan-300/25 rounded-full opacity-15 blur-3xl animate-pulse" style="animation-delay: 1.5s;"></div>
        <div class="absolute bottom-0 left-1/2 w-[400px] h-[400px] bg-teal-300/20 rounded-full opacity-12 blur-3xl animate-pulse" style="animation-delay: 2.5s;"></div>
        <div class="absolute top-1/4 right-1/4 w-[300px] h-[300px] bg-rose-300/18 rounded-full opacity-10 blur-2xl animate-pulse" style="animation-delay: 3.5s;"></div>
    </div>
    
    <!-- Floating decorative elements -->
    <div class="absolute top-20 right-20 w-32 h-32 bg-pink-300/15 rounded-full blur-2xl animate-bounce" style="animation-duration: 6s;"></div>
    <div class="absolute bottom-20 left-20 w-40 h-40 bg-cyan-300/15 rounded-full blur-2xl animate-bounce" style="animation-duration: 8s; animation-delay: 2s;"></div>
    
    <div class="container mx-auto py-8 relative z-10">
        <!-- Enhanced Header Section -->
        <div class="max-w-7xl mx-auto mb-8">
            <div class="relative bg-gradient-to-br from-white/98 via-pink-50/96 to-cyan-50/96 backdrop-blur-xl rounded-3xl shadow-2xl p-8 border-2 border-pink-300/40 overflow-hidden transform transition-all duration-500 hover:shadow-3xl">
                <!-- Decorative pattern overlay -->
                <div class="absolute inset-0 opacity-6">
                    <div class="absolute inset-0" style="background-image: radial-gradient(circle, rgba(236, 72, 153, 0.2) 1px, transparent 1px); background-size: 30px 30px;"></div>
                </div>
                
                <!-- Animated gradient border -->
                <div class="absolute inset-0 rounded-3xl bg-gradient-to-r from-pink-400/20 via-rose-400/20 to-cyan-400/20 opacity-15 blur-xl animate-pulse"></div>
                
                <div class="relative flex items-center justify-between mb-6">
                    <div class="flex items-center gap-5">
                        <!-- Enhanced Go Back Button -->
                        <button onclick='goBackOrRedirect("{{ e(route('teacher.dashboard')) }}")' 
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
                            <div class="absolute inset-0 bg-gradient-to-br from-pink-400 to-cyan-500 rounded-2xl blur-lg opacity-45 animate-pulse"></div>
                            <div class="relative w-16 h-16 bg-gradient-to-br from-pink-500 via-rose-500 to-cyan-500 rounded-2xl flex items-center justify-center shadow-2xl transform hover:scale-110 hover:rotate-6 transition-all duration-300 border-2 border-white/40 shadow-pink-200/50">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-9 w-9 text-white drop-shadow-lg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                        </div>
                        
                        <!-- Enhanced Title Section -->
                        <div class="flex-1">
                            <div class="flex items-center gap-3 mb-3">
                                <h1 class="text-4xl font-black bg-gradient-to-r from-pink-600 via-rose-500 to-cyan-600 bg-clip-text text-transparent drop-shadow-lg">
                                    Game Creator
                                </h1>
                                <span class="text-2xl animate-bounce" style="animation-duration: 2s;">🎮</span>
                            </div>
                            <p class="text-gray-700 font-semibold text-lg mb-2 flex items-center gap-2">
                                <span class="w-2.5 h-2.5 bg-gradient-to-r from-pink-500 to-cyan-500 rounded-full animate-pulse shadow-lg shadow-pink-300/50"></span>
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

                <!-- Step-by-Step Game Creation Flow -->
                <div class="mt-8 pt-8 border-t-2 border-gradient-to-r from-pink-300/50 via-rose-300/50 to-cyan-300/50">
                    <form method="GET" action="" id="gameCreationForm">
                        @if(isset($selectedLessonId) && $selectedLessonId)
                            <input type="hidden" name="lesson_id" value="{{ $selectedLessonId }}" id="lesson_id_hidden">
                        @endif
                        
                        <!-- Step 1: Select Lesson -->
                        <div class="mb-8">
                            <div class="flex items-center gap-4 mb-6">
                                <div class="flex items-center justify-center w-12 h-12 rounded-full bg-gradient-to-br from-pink-500 to-rose-500 text-white font-bold text-lg shadow-lg {{ isset($selectedLessonId) && $selectedLessonId ? 'ring-4 ring-pink-200' : '' }}">
                                    @if(isset($selectedLessonId) && $selectedLessonId)
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                        </svg>
                                    @else
                                        1
                                    @endif
                                </div>
                                <div class="flex-1">
                                    <h3 class="text-xl font-bold text-gray-800 mb-1">Step 1: Choose Your Lesson</h3>
                                    <p class="text-sm text-gray-600">Select the lesson you want to create a game for</p>
                                </div>
                            </div>
                            <div class="relative group">
                                <div class="relative">
                                    <select name="lesson_id" id="lesson_id" 
                                            class="w-full bg-white/90 backdrop-blur-sm border-3 border-pink-300/60 rounded-2xl px-6 py-4 pr-14 text-gray-800 font-semibold text-lg shadow-lg hover:border-pink-400 hover:shadow-xl focus:border-pink-500 focus:ring-4 focus:ring-pink-200/50 transition-all duration-300 appearance-none cursor-pointer"
                                            onchange="document.querySelectorAll('input[name=\'game_type\']').forEach(r => r.checked = false); this.form.submit();">
                                        <option value="">📚 Select a lesson to begin...</option>
                                        @foreach($lessons ?? [] as $lesson)
                                            <option value="{{ $lesson->lesson_id }}" {{ (isset($selectedLessonId) && $selectedLessonId == $lesson->lesson_id) ? 'selected' : '' }}>{{ $lesson->title }}</option>
                                        @endforeach
                                    </select>
                                    <div class="absolute inset-y-0 right-0 flex items-center pr-6 pointer-events-none">
                                        <svg class="h-7 w-7 text-pink-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                                        </svg>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Step 2: Select Game Type (Only shown when lesson is selected) -->
                        @if(isset($selectedLessonId) && $selectedLessonId)
                        <div class="mb-8">
                            <div class="flex items-center gap-4 mb-6">
                                <div class="flex items-center justify-center w-12 h-12 rounded-full bg-gradient-to-br from-purple-500 to-indigo-500 text-white font-bold text-lg shadow-lg {{ isset($selectedGameType) && $selectedGameType ? 'ring-4 ring-purple-200' : '' }}">
                                    @if(isset($selectedGameType) && $selectedGameType)
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                        </svg>
                                    @else
                                        2
                                    @endif
                                </div>
                                <div class="flex-1">
                                    <h3 class="text-xl font-bold text-gray-800 mb-1">Step 2: Choose Game Type</h3>
                                    <p class="text-sm text-gray-600">Pick the type of interactive game you want to create</p>
                                </div>
                            </div>
                            
                            <!-- Game Type Cards Grid -->
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                                @php
                                    $gameTypes = [
                                        [
                                            'value' => 'word_search',
                                            'name' => 'Word Search',
                                            'icon' => '🔍',
                                            'color' => 'from-pink-400 to-rose-500',
                                            'bg' => 'from-pink-50 to-rose-50',
                                            'border' => 'border-pink-300',
                                            'selected' => (isset($selectedGameType) && $selectedGameType == 'word_search')
                                        ],
                                        [
                                            'value' => 'word_clock_arrangement',
                                            'name' => 'Word Clock',
                                            'icon' => '🕐',
                                            'color' => 'from-cyan-400 to-teal-500',
                                            'bg' => 'from-cyan-50 to-teal-50',
                                            'border' => 'border-cyan-300',
                                            'selected' => (isset($selectedGameType) && $selectedGameType == 'word_clock_arrangement')
                                        ],
                                        [
                                            'value' => 'matching_pairs',
                                            'name' => 'Matching Pairs',
                                            'icon' => '🔗',
                                            'color' => 'from-purple-400 to-indigo-500',
                                            'bg' => 'from-purple-50 to-indigo-50',
                                            'border' => 'border-purple-300',
                                            'selected' => (isset($selectedGameType) && $selectedGameType == 'matching_pairs')
                                        ],
                                        [
                                            'value' => 'scramble',
                                            'name' => 'Scrambled Letters',
                                            'icon' => '🔤',
                                            'color' => 'from-orange-400 to-amber-500',
                                            'bg' => 'from-orange-50 to-amber-50',
                                            'border' => 'border-orange-300',
                                            'selected' => (isset($selectedGameType) && $selectedGameType == 'scramble')
                                        ]
                                    ];
                                @endphp
                                
                                @foreach($gameTypes as $game)
                                <label class="relative cursor-pointer group">
                                    <input type="radio" name="game_type" value="{{ $game['value'] }}" 
                                           class="hidden game-type-radio" 
                                           {{ $game['selected'] ? 'checked' : '' }}
                                           onchange="this.closest('form').submit();">
                                    <div class="relative h-full bg-gradient-to-br {{ $game['bg'] }} border-3 {{ $game['border'] }} rounded-2xl p-6 shadow-lg hover:shadow-2xl transition-all duration-300 transform hover:scale-105 {{ $game['selected'] ? 'scale-105' : '' }}" 
                                         style="{{ $game['selected'] ? 'box-shadow: 0 0 0 4px rgba(236, 72, 153, 0.3);' : '' }}"
                                         data-game-value="{{ $game['value'] }}">
                                        <!-- Selected indicator -->
                                        <div class="absolute top-3 right-3 w-6 h-6 rounded-full bg-gradient-to-br {{ $game['color'] }} {{ $game['selected'] ? 'opacity-100' : 'opacity-0' }} transition-opacity duration-300 flex items-center justify-center">
                                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" />
                                            </svg>
                                        </div>
                                        
                                        <!-- Game Icon -->
                                        <div class="flex items-center justify-center w-16 h-16 mx-auto mb-4 bg-gradient-to-br {{ $game['color'] }} rounded-2xl shadow-lg transform group-hover:rotate-6 transition-transform duration-300">
                                            <span class="text-3xl">{{ $game['icon'] }}</span>
                                        </div>
                                        
                                        <!-- Game Name -->
                                        <h4 class="text-center font-bold text-gray-800 text-lg">{{ $game['name'] }}</h4>
                                        
                                        <!-- Hover effect overlay -->
                                        <div class="absolute inset-0 bg-gradient-to-br {{ $game['color'] }} opacity-0 group-hover:opacity-10 rounded-2xl transition-opacity duration-300"></div>
                                    </div>
                                </label>
                                @endforeach
                            </div>
                        </div>

                        <!-- Step 3: Assign to Class (Optional) -->
                        <div class="mb-6">
                            <div class="flex items-center gap-4 mb-4">
                                <div class="flex items-center justify-center w-12 h-12 rounded-full bg-gradient-to-br from-cyan-500 to-teal-500 text-white font-bold text-lg shadow-lg">
                                    3
                                </div>
                                <div class="flex-1">
                                    <h3 class="text-xl font-bold text-gray-800 mb-1">Step 3: Assign to Class <span class="text-sm font-normal text-gray-500">(Optional)</span></h3>
                                    <p class="text-sm text-gray-600">Optionally assign this game to a specific class</p>
                                </div>
                            </div>
                            <div class="relative">
                                <select name="class_id" id="class_id" 
                                        class="w-full bg-white/90 backdrop-blur-sm border-3 border-cyan-300/60 rounded-2xl px-6 py-4 pr-14 text-gray-800 font-semibold text-lg shadow-lg hover:border-cyan-400 hover:shadow-xl focus:border-cyan-500 focus:ring-4 focus:ring-cyan-200/50 transition-all duration-300 appearance-none cursor-pointer">
                                    <option value="">👥 Select a class (optional)...</option>
                                    @foreach($classes ?? [] as $class)
                                        <option value="{{ $class->class_id }}">{{ $class->class_name }}</option>
                                    @endforeach
                                </select>
                                <div class="absolute inset-y-0 right-0 flex items-center pr-6 pointer-events-none">
                                    <svg class="h-7 w-7 text-cyan-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                                    </svg>
                                </div>
                            </div>
                        </div>
                        @endif
                    </form>
                </div>
            </div>
        </div>

        @if(isset($selectedLessonId) && $selectedLessonId)
        <!-- Enhanced Word Search Game Section -->
        <div class="game-section max-w-7xl mx-auto mb-8" data-game-type="word_search" style="display: {{ (isset($selectedGameType) && $selectedGameType == 'word_search') ? 'block' : 'none' }};">
            <div class="relative bg-gradient-to-br from-white/96 via-pink-50/92 to-cyan-50/92 backdrop-blur-xl rounded-3xl shadow-2xl p-8 pt-10 border-2 border-pink-300/40 transform transition-all duration-500 hover:shadow-3xl hover:scale-[1.01] overflow-visible">
                <!-- Decorative elements -->
                <div class="absolute top-0 right-0 w-64 h-64 bg-gradient-to-br from-pink-300/18 to-rose-300/18 rounded-full blur-3xl -mr-32 -mt-32 pointer-events-none"></div>
                <div class="absolute bottom-0 left-0 w-48 h-48 bg-gradient-to-br from-cyan-300/15 to-teal-300/15 rounded-full blur-2xl -ml-24 -mb-24 pointer-events-none"></div>
                
                <div class="relative flex items-center gap-5 mb-6 pb-5 border-b-2 border-gradient-to-r from-pink-300/50 to-cyan-300/50 z-10">
                    <div class="relative">
                        <div class="absolute inset-0 bg-gradient-to-br from-pink-400 to-cyan-500 rounded-2xl blur-lg opacity-40 animate-pulse"></div>
                        <div class="relative w-16 h-16 bg-gradient-to-br from-pink-500 via-rose-500 to-cyan-500 rounded-2xl flex items-center justify-center shadow-xl transform hover:scale-110 hover:rotate-3 transition-all duration-300 border-2 border-white/40 shadow-pink-200/50">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-white drop-shadow-lg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </div>
                    </div>
                    <div class="flex-1">
                        <h3 class="text-2xl font-black bg-gradient-to-r from-pink-600 via-rose-500 to-cyan-600 bg-clip-text text-transparent mb-2 drop-shadow-sm">
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
                    <script>
                    // Define function immediately before button is rendered
                    if (typeof window.editWordSearchGame === 'undefined') {
                        window.editWordSearchGame = function() {
                            try {
                                const wordSearchSavedView = document.getElementById('wordSearchSavedView');
                                const wordSearchSection = document.getElementById('wordSearchSection');
                                
                                console.log('Edit Word Search function called');
                                
                                if (wordSearchSavedView) {
                                    wordSearchSavedView.style.setProperty('display', 'none', 'important');
                                    wordSearchSavedView.classList.add('hidden');
                                }
                                
                                if (wordSearchSection) {
                                    wordSearchSection.classList.remove('hidden');
                                    wordSearchSection.style.setProperty('display', 'block', 'important');
                                    wordSearchSection.style.setProperty('visibility', 'visible', 'important');
                                    wordSearchSection.style.setProperty('opacity', '1', 'important');
                                    
                                    const form = document.getElementById('wordSearchForm');
                                    if (form) {
                                        form.style.display = 'block';
                                    }
                                    
                                    void wordSearchSection.offsetHeight;
                                    
                                    setTimeout(() => {
                                        wordSearchSection.scrollIntoView({ behavior: 'smooth', block: 'start' });
                                        const firstInput = wordSearchSection.querySelector('input[type="text"]');
                                        if (firstInput) {
                                            setTimeout(() => firstInput.focus(), 200);
                                        }
                                    }, 150);
                                } else {
                                    alert('Form section not found. Please refresh the page.');
                                }
                                
                                return false;
                            } catch (error) {
                                console.error('Error in editWordSearchGame:', error);
                                alert('An error occurred. Please check the console for details.');
                                return false;
                            }
                        };
                    }
                    
                    // Remove Word Search Word Box Function
                    window.removeWordSearchWordBox = function(button) {
                        try {
                            const box = button.closest('.word-search-word-box');
                            if (!box) {
                                console.warn('Word box not found');
                                return false;
                            }
                            
                            const container = document.getElementById('wordSearchWordsBoxes');
                            if (!container) {
                                console.warn('Container not found');
                                return false;
                            }
                            
                            const allBoxes = container.querySelectorAll('.word-search-word-box');
                            if (allBoxes.length <= 1) {
                                alert('You must have at least one word.');
                                return false;
                            }
                            
                            box.remove();
                            console.log('Word box removed');
                            return false;
                        } catch (error) {
                            console.error('Error removing word box:', error);
                            return false;
                        }
                    };
                    </script>
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
                                <button type="button" id="viewWordSearchBtn" class="group relative px-6 py-3 rounded-2xl bg-gradient-to-r from-pink-200 via-pink-300 to-pink-300 text-gray-800 font-bold shadow-lg hover:shadow-2xl transform hover:scale-110 transition-all duration-300 flex items-center gap-2.5 overflow-hidden border-2 border-pink-200/50">
                                    <div class="absolute inset-0 bg-gradient-to-r from-transparent via-white/30 to-transparent transform -translate-x-full group-hover:translate-x-full transition-transform duration-700"></div>
                                    <svg class="h-5 w-5 relative z-10" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                    <span class="relative z-10">View</span>
                                </button>
                                <button type="button" id="editWordSearchBtn" onclick="return editWordSearchGame();" class="group relative px-6 py-3 rounded-2xl bg-gradient-to-r from-pink-200 via-pink-300 to-pink-300 text-gray-800 font-bold shadow-lg hover:shadow-2xl transform hover:scale-110 transition-all duration-300 flex items-center gap-2.5 overflow-hidden border-2 border-pink-200/50 cursor-pointer z-50">
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
                <div id="wordSearchSection" class="{{ (isset($wordSearchData) && !empty($wordSearchData['words'])) ? 'hidden' : '' }} relative z-10">
                    <form id="wordSearchForm" method="POST" action="{{ route('teacher.games.word-search.store') }}">
                        @csrf
                        @if(isset($wordSearchGame) && $wordSearchGame)
                            <input type="hidden" name="word_search_game_id" value="{{ $wordSearchGame->word_search_game_id ?? '' }}">
                        @endif
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
                            <div class="flex items-center justify-between mb-3">
                                <div class="flex items-center gap-2">
                                    <label class="block font-bold text-gray-800 text-lg flex items-center gap-2">
                                        <svg class="h-5 w-5 text-pink-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14" />
                                        </svg>
                                        Words to Find:
                                    </label>
                                    <p class="text-sm text-gray-600">Add words that students will search for in the puzzle.</p>
                                </div>
                                <div class="flex items-center gap-2">
                                    <button type="button" id="clearAllWordSearchBtn" class="px-4 py-2 rounded-xl bg-gradient-to-r from-orange-200 via-orange-300 to-orange-300 text-white font-semibold shadow-lg hover:from-orange-300 hover:via-orange-400 hover:to-orange-400 transform hover:scale-105 transition-all duration-200 flex items-center gap-2 border border-orange-200/50">
                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                        Clear All
                                    </button>
                                    <button type="button" id="viewWordSearchBtn" class="px-4 py-2 rounded-xl bg-gradient-to-r from-purple-200 via-purple-300 to-purple-300 text-white font-semibold shadow-lg hover:from-purple-300 hover:via-purple-400 hover:to-purple-400 transform hover:scale-105 transition-all duration-200 flex items-center gap-2 border border-purple-200/50">
                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                        View Game
                                    </button>
                                </div>
                            </div>
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
                                    <button type="button" class="removeWordSearchWordBox px-4 py-2.5 bg-gradient-to-r from-pink-200 via-pink-300 to-pink-300 text-gray-800 rounded-lg font-bold shadow-md hover:from-pink-300 hover:via-pink-400 hover:to-pink-400 transform hover:scale-105 transition-all duration-200 border border-pink-200/50" onclick="removeWordSearchWordBox(this)">&times;</button>
                                </div>
                            @endforeach
                        @else
                                <!-- Initial word box when no saved data -->
                                <div class="word-search-word-box flex items-center gap-3 mb-3 p-3 bg-pink-50 rounded-xl border-2 border-pink-200 hover:border-pink-300 transition-colors">
                                    <input type="text" name="word_search_words[]" 
                                           class="flex-1 bg-pink-50 border-2 border-pink-200 rounded-lg px-4 py-2.5 text-gray-800 font-medium hover:bg-pink-100 focus:border-pink-400 focus:ring-2 focus:ring-pink-200 focus:bg-pink-50 transition-all" 
                                           placeholder="Enter word" required>
                                    <button type="button" class="removeWordSearchWordBox px-4 py-2.5 bg-gradient-to-r from-pink-200 via-pink-300 to-pink-300 text-gray-800 rounded-lg font-bold shadow-md hover:from-pink-300 hover:via-pink-400 hover:to-pink-400 transform hover:scale-105 transition-all duration-200 border border-pink-200/50" onclick="removeWordSearchWordBox(this)">&times;</button>
                                </div>
                        @endif
                    </div>
                            <div class="flex flex-wrap gap-3 mt-3">
                                <button type="button" id="addWordSearchWordBox" class="px-5 py-2.5 rounded-xl bg-gradient-to-r from-pink-200 via-pink-300 to-pink-300 text-gray-800 font-semibold shadow-lg hover:from-pink-300 hover:via-pink-400 hover:to-pink-400 transform hover:scale-105 transition-all duration-200 flex items-center gap-2 w-fit border border-pink-200/50">
                                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                    </svg>
                                    Add Another Word
                                </button>
                            </div>
                        </div>

                        <div class="flex flex-wrap gap-3">
                            <button type="submit" class="px-8 py-3.5 rounded-xl bg-gradient-to-r from-pink-200 via-pink-300 to-pink-300 text-gray-800 font-bold text-lg shadow-xl hover:from-pink-300 hover:via-pink-400 hover:to-pink-400 transform hover:scale-105 transition-all duration-200 flex items-center justify-center gap-2 border border-pink-200/50">
                                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                                Save Word Search Game
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Word Clock Arrangement Game Section -->
        <div class="game-section max-w-7xl mx-auto mb-8" data-game-type="word_clock_arrangement" style="display: {{ (isset($selectedGameType) && $selectedGameType == 'word_clock_arrangement') ? 'block' : 'none' }};">
            <div class="bg-gradient-to-br from-pink-50/60 via-white/80 to-cyan-50/90 backdrop-blur-md rounded-2xl shadow-xl p-8 border border-cyan-200/40 transform transition-all duration-300 hover:shadow-2xl">
                <div class="flex items-center gap-4 mb-6 pb-4 border-b-2 border-pink-200/50">
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
                            <div class="flex items-center gap-2">
                                <button type="button" id="viewWordClockArrangementSavedBtn" class="px-5 py-2.5 rounded-xl bg-gradient-to-r from-purple-200 via-purple-300 to-purple-300 text-white font-semibold shadow-md hover:from-purple-300 hover:via-purple-400 hover:to-purple-400 transform hover:scale-105 transition-all duration-200 flex items-center gap-2 border border-purple-200/50">
                                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                    View
                                </button>
                                <button type="button" id="editWordClockArrangementBtn" class="px-5 py-2.5 rounded-xl bg-gradient-to-r from-pink-200 via-pink-300 to-pink-300 text-gray-800 font-semibold shadow-md hover:from-pink-300 hover:via-pink-400 hover:to-pink-400 transform hover:scale-105 transition-all duration-200 flex items-center gap-2 border border-pink-200/50">
                                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                    Edit
                                </button>
                            </div>
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
                    <script>
                    // Define helper functions BEFORE the button is rendered
                    if (typeof window.createClockSVG === 'undefined') {
                        window.createClockSVG = function(hour, minute, size = 70) {
                            const center = size / 2;
                            const radius = size / 2 - 3;
                            const hourHandLength = radius * 0.5;
                            const minuteHandLength = radius * 0.75;
                            
                            // Calculate angles
                            const hourAngle = ((hour % 12) * 30 + minute * 0.5 - 90) * Math.PI / 180;
                            const minuteAngle = (minute * 6 - 90) * Math.PI / 180;
                            
                            // Adjust viewBox to give padding for numbers
                            const viewBoxSize = size + 10;
                            const viewBoxOffset = 5;
                            const adjustedCenter = center + viewBoxOffset;
                            
                            // Calculate hand positions for adjusted center
                            const hourX = adjustedCenter + hourHandLength * Math.cos(hourAngle);
                            const hourY = adjustedCenter + hourHandLength * Math.sin(hourAngle);
                            const minuteX = adjustedCenter + minuteHandLength * Math.cos(minuteAngle);
                            const minuteY = adjustedCenter + minuteHandLength * Math.sin(minuteAngle);
                            
                            let svg = '<svg width="' + size + '" height="' + size + '" class="clock-svg-preview" viewBox="0 0 ' + viewBoxSize + ' ' + viewBoxSize + '" style="display: block; margin: 0 auto;"><circle cx="' + adjustedCenter + '" cy="' + adjustedCenter + '" r="' + (radius - 3) + '" fill="white" stroke="#333" stroke-width="2"/>';
                            
                            // Add numbers - positioned more inward
                            for (let i = 1; i <= 12; i++) {
                                const angle = ((i - 3) * 30) * Math.PI / 180;
                                const x = adjustedCenter + (radius - 10) * Math.cos(angle);
                                const y = adjustedCenter + (radius - 10) * Math.sin(angle);
                                const fontSize = size <= 60 ? 8 : 10;
                                svg += '<text x="' + x + '" y="' + (y + 4) + '" text-anchor="middle" font-size="' + fontSize + '" fill="#333" dominant-baseline="middle">' + i + '</text>';
                            }
                            
                            svg += '<line x1="' + adjustedCenter + '" y1="' + adjustedCenter + '" x2="' + hourX + '" y2="' + hourY + '" stroke="#333" stroke-width="' + (size <= 60 ? 2.5 : 3) + '" stroke-linecap="round"/><line x1="' + adjustedCenter + '" y1="' + adjustedCenter + '" x2="' + minuteX + '" y2="' + minuteY + '" stroke="#333" stroke-width="' + (size <= 60 ? 2 : 2.5) + '" stroke-linecap="round"/><circle cx="' + adjustedCenter + '" cy="' + adjustedCenter + '" r="' + (size <= 60 ? 2.5 : 3) + '" fill="#333"/></svg>';
                            
                            return svg;
                        };
                    }
                    
                    if (typeof window.updateClockPreview === 'undefined') {
                        window.updateClockPreview = function(container, hour, minute) {
                            const clockPreview = container.querySelector('.clock-preview');
                            if (clockPreview && typeof window.createClockSVG === 'function') {
                                // Use 86px to account for padding (110px container - 24px padding = 86px)
                                const size = 86;
                                clockPreview.innerHTML = window.createClockSVG(parseInt(hour) || 0, parseInt(minute) || 0, size);
                            }
                        };
                    }
                    
                    // Define addWordClockArrangementWordBox function BEFORE the button is rendered
                    if (typeof window.addWordClockArrangementWordBox === 'undefined') {
                        window.addWordClockArrangementWordBox = function(wordValue = '') {
                            const container = document.getElementById('wordClockArrangementWordsBoxes');
                            
                            if (!container) {
                                console.error('wordClockArrangementWordsBoxes container not found');
                                alert('Error: Could not find the word container. Please refresh the page.');
                                return;
                            }
                            
                            // Escape HTML to prevent XSS
                            const escapeHtml = function(text) {
                                const div = document.createElement('div');
                                div.textContent = text;
                                return div.innerHTML;
                            };
                            
                            const box = document.createElement('div');
                            box.className = 'word-clock-arrangement-word-box flex flex-col md:flex-row items-start md:items-center gap-4 p-5 border-2 border-cyan-200 rounded-xl bg-gradient-to-r from-pink-50/60 to-cyan-50/60 hover:border-cyan-300 transition-colors';
                            
                            // Build HTML structure
                            const wordInputHtml = '<div class="flex-1 w-full md:w-auto"><input type="text" name="word_clock_words[][word]" class="w-full bg-pink-50 border-2 border-pink-200 rounded-lg px-4 py-2.5 text-gray-800 font-medium hover:bg-pink-100 focus:border-cyan-400 focus:ring-2 focus:ring-cyan-200 focus:bg-pink-50 transition-all" value="' + escapeHtml(wordValue) + '" placeholder="Word" required></div>';
                            
                            const controlsHtml = '<div class="flex items-center gap-3 flex-wrap"><div class="flex items-center gap-2"><label class="text-sm font-semibold text-gray-700">Hour:</label><input type="number" name="word_clock_words[][hour]" class="w-20 bg-pink-50 border-2 border-pink-200 rounded-lg px-3 py-2 text-gray-800 font-medium hour-input hover:bg-pink-100 focus:border-cyan-400 focus:ring-2 focus:ring-cyan-200 focus:bg-pink-50 transition-all" value="0" min="0" max="11" placeholder="0-11" required></div><div class="flex items-center gap-2"><label class="text-sm font-semibold text-gray-700">Minute:</label><input type="number" name="word_clock_words[][minute]" class="w-20 bg-pink-50 border-2 border-pink-200 rounded-lg px-3 py-2 text-gray-800 font-medium minute-input hover:bg-pink-100 focus:border-cyan-400 focus:ring-2 focus:ring-cyan-200 focus:bg-pink-50 transition-all" value="0" min="0" max="59" placeholder="0-59" required oninput="if(this.value > 59) this.value = 59; if(this.value < 0) this.value = 0;" onblur="if(this.value > 59) this.value = 59; if(this.value < 0) this.value = 0;" onkeypress="if(event.key && !isNaN(event.key) && parseInt(this.value + event.key) > 59) event.preventDefault();"></div><div class="clock-preview ml-2 bg-pink-50 rounded-lg border-2 border-pink-200 shadow-sm flex items-center justify-center" style="width: 110px; height: 110px; min-width: 110px; min-height: 110px; overflow: hidden; flex-shrink: 0;"></div><button type="button" class="removeWordClockArrangementWordBox px-4 py-2.5 bg-gradient-to-r from-pink-200 via-pink-300 to-pink-300 text-gray-800 rounded-lg font-bold shadow-md hover:from-pink-300 hover:via-pink-400 hover:to-pink-400 transform hover:scale-105 transition-all duration-200 border border-pink-200/50">&times;</button></div>';
                            
                            box.innerHTML = wordInputHtml + controlsHtml;
                            
                            // Add clock SVG to the preview div
                            const clockPreview = box.querySelector('.clock-preview');
                            if (clockPreview) {
                                if (typeof window.createClockSVG === 'function') {
                                    clockPreview.innerHTML = window.createClockSVG(0, 0, 86);
                                } else {
                                    clockPreview.innerHTML = '<svg width="86" height="86" class="clock-svg-preview" viewBox="0 0 100 100" style="display: block; margin: 0 auto;"><circle cx="50" cy="50" r="28" fill="white" stroke="#333" stroke-width="2.5"/><circle cx="50" cy="50" r="3" fill="#333"/></svg>';
                                }
                            }
                            
                            container.appendChild(box);
                            
                            // Add event listeners for clock preview updates
                            const hourInput = box.querySelector('.hour-input');
                            const minuteInput = box.querySelector('.minute-input');
                            
                            if (hourInput && minuteInput && typeof window.updateClockPreview === 'function') {
                                hourInput.addEventListener('input', function() {
                                    window.updateClockPreview(box, hourInput.value, minuteInput.value);
                                });
                                
                                minuteInput.addEventListener('input', function() {
                                    // Validate minute value (0-59)
                                    let minuteValue = parseInt(this.value) || 0;
                                    if (minuteValue > 59) {
                                        this.value = 59;
                                        minuteValue = 59;
                                    } else if (minuteValue < 0) {
                                        this.value = 0;
                                        minuteValue = 0;
                                    }
                                    window.updateClockPreview(box, hourInput.value, minuteValue);
                                });
                                
                                minuteInput.addEventListener('blur', function() {
                                    // Clamp value on blur if user somehow entered invalid value
                                    let minuteValue = parseInt(this.value) || 0;
                                    if (minuteValue > 59) {
                                        this.value = 59;
                                    } else if (minuteValue < 0) {
                                        this.value = 0;
                                    }
                                });
                                
                                minuteInput.addEventListener('keypress', function(e) {
                                    // Prevent typing if the resulting value would be > 59
                                    const currentValue = this.value;
                                    const key = e.key;
                                    
                                    // Allow: backspace, delete, tab, escape, enter, and decimal point
                                    if ([8, 9, 27, 13, 46, 110, 190].indexOf(e.keyCode) !== -1 ||
                                        // Allow: Ctrl+A, Ctrl+C, Ctrl+V, Ctrl+X
                                        (e.keyCode === 65 && e.ctrlKey === true) ||
                                        (e.keyCode === 67 && e.ctrlKey === true) ||
                                        (e.keyCode === 86 && e.ctrlKey === true) ||
                                        (e.keyCode === 88 && e.ctrlKey === true) ||
                                        // Allow: home, end, left, right
                                        (e.keyCode >= 35 && e.keyCode <= 39)) {
                                        return;
                                    }
                                    
                                    // Ensure that it is a number and stop the keypress
                                    if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
                                        e.preventDefault();
                                        return;
                                    }
                                    
                                    // Check if the resulting value would exceed 59
                                    const newValue = currentValue + key;
                                    if (parseInt(newValue) > 59) {
                                        e.preventDefault();
                                        return;
                                    }
                                });
                            }
                        };
                    }
                    
                    // Define split sentence handler BEFORE the button is rendered
                    if (typeof window.handleSplitSentenceClick === 'undefined') {
                        window.handleSplitSentenceClick = function(e) {
                            if (e) {
                                e.preventDefault();
                                e.stopPropagation();
                            }
                            console.log('=== Split Sentence button clicked (inline handler) ===');
                            
                            const sentenceTextarea = document.getElementById('word_clock_sentence');
                            const container = document.getElementById('wordClockArrangementWordsBoxes');
                            
                            if (!sentenceTextarea) {
                                alert('Sentence textarea not found. Please refresh the page.');
                                return false;
                            }
                            
                            const sentence = sentenceTextarea.value.trim();
                            if (!sentence) {
                                alert('Please enter a sentence first.');
                                return false;
                            }
                            
                            const words = sentence.split(/\s+/).filter(w => w.trim() !== '');
                            if (words.length === 0) {
                                alert('No words found in the sentence.');
                                return false;
                            }
                            
                            if (!container) {
                                alert('Error: Could not find the word container. Please refresh the page.');
                                return false;
                            }
                            
                            container.innerHTML = '';
                            
                            words.forEach((word) => {
                                if (typeof window.addWordClockArrangementWordBox === 'function') {
                                    window.addWordClockArrangementWordBox(word);
                                } else {
                                    console.error('addWordClockArrangementWordBox function still not available');
                                    alert('Error: Function not loaded. Please refresh the page.');
                                }
                            });
                            
                            return false;
                        };
                    }
                    </script>
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
                            <button type="button" id="splitSentenceBtn" onclick="handleSplitSentenceClick(event)" class="mt-3 px-5 py-2.5 rounded-xl bg-[#EC769A] text-white font-semibold shadow-lg hover:bg-[#d8658a] transform hover:scale-105 transition-all duration-200 flex items-center gap-2 w-fit">
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                </svg>
                                Split Sentence into Words
                            </button>
                        </div>

                        <div class="mb-6">
                            <div class="flex items-center justify-between mb-3">
                                <label class="block font-bold text-gray-800 text-lg flex items-center gap-2">
                                    <svg class="h-5 w-5 text-cyan-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    Words with Clock Times:
                                </label>
                                <div class="flex flex-wrap gap-3">
                                    <button type="button" id="addWordClockArrangementWordBox" class="px-5 py-2.5 rounded-xl bg-gradient-to-r from-pink-200 via-pink-300 to-pink-300 text-gray-800 font-semibold shadow-lg hover:from-pink-300 hover:via-pink-400 hover:to-pink-400 transform hover:scale-105 transition-all duration-200 flex items-center gap-2 w-fit border border-pink-200/50">
                                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                        </svg>
                                        Add Another Word
                                    </button>
                                    <button type="button" id="clearAllWordClockBtn" class="px-5 py-2.5 rounded-xl bg-gradient-to-r from-red-200 via-red-300 to-red-300 text-white font-semibold shadow-lg hover:from-red-300 hover:via-red-400 hover:to-red-400 transform hover:scale-105 transition-all duration-200 flex items-center gap-2 w-fit border border-red-200/50">
                                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                        Clear All
                                    </button>
                                </div>
                            </div>
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
                                                           min="0" max="59" placeholder="0-59" required
                                                           oninput="if(this.value > 59) this.value = 59; if(this.value < 0) this.value = 0;"
                                                           onblur="if(this.value > 59) this.value = 59; if(this.value < 0) this.value = 0;"
                                                           onkeypress="if(event.key && !isNaN(event.key) && parseInt(this.value + event.key) > 59) event.preventDefault();">
                                                </div>
                                                <div class="clock-preview ml-2 bg-pink-50 rounded-lg border-2 border-pink-200 shadow-sm flex items-center justify-center" style="width: 110px; height: 110px; min-width: 110px; min-height: 110px; overflow: hidden; flex-shrink: 0;">
                                                    <svg width="86" height="86" class="clock-svg-preview" viewBox="0 0 100 100" style="display: block; margin: 0 auto;">
                                                        <circle cx="50" cy="50" r="28" fill="white" stroke="#333" stroke-width="2.5"/>
                                                        <!-- Clock numbers (simplified) -->
                                                        @for($i = 1; $i <= 12; $i++)
                                                            @php
                                                                $angle = ($i - 3) * 30 * M_PI / 180;
                                                                $x = 50 + 20 * cos($angle);
                                                                $y = 50 + 20 * sin($angle);
                                                            @endphp
                                                            <text x="{{ $x }}" y="{{ $y + 5 }}" text-anchor="middle" font-size="9" fill="#333" dominant-baseline="middle">{{ $i }}</text>
                                                        @endfor
                                                        <!-- Hour hand -->
                                                        @php
                                                            $hourAngle = (($wordData['hour'] % 12) * 30 + $wordData['minute'] * 0.5 - 90) * M_PI / 180;
                                                            $hourX = 50 + 14 * cos($hourAngle);
                                                            $hourY = 50 + 14 * sin($hourAngle);
                                                        @endphp
                                                        <line x1="50" y1="50" x2="{{ $hourX }}" y2="{{ $hourY }}" stroke="#333" stroke-width="3" stroke-linecap="round"/>
                                                        <!-- Minute hand -->
                                                        @php
                                                            $minuteAngle = ($wordData['minute'] * 6 - 90) * M_PI / 180;
                                                            $minuteX = 50 + 20 * cos($minuteAngle);
                                                            $minuteY = 50 + 20 * sin($minuteAngle);
                                                        @endphp
                                                        <line x1="50" y1="50" x2="{{ $minuteX }}" y2="{{ $minuteY }}" stroke="#333" stroke-width="2.5" stroke-linecap="round"/>
                                                        <!-- Center dot -->
                                                        <circle cx="50" cy="50" r="3" fill="#333"/>
                                                    </svg>
                                                </div>
                                                <button type="button" class="removeWordClockArrangementWordBox px-4 py-2.5 bg-gradient-to-r from-pink-200 via-pink-300 to-pink-300 text-gray-800 rounded-lg font-bold shadow-md hover:from-pink-300 hover:via-pink-400 hover:to-pink-400 transform hover:scale-105 transition-all duration-200 border border-pink-200/50">&times;</button>
                                            </div>
                                        </div>
                            @endforeach
                        @endif
                    </div>
                        </div>

                        <div class="flex flex-wrap gap-3">
                            <button type="submit" class="px-8 py-3.5 rounded-xl bg-gradient-to-r from-pink-200 via-pink-300 to-pink-300 text-gray-800 font-bold text-lg shadow-xl hover:from-pink-300 hover:via-pink-400 hover:to-pink-400 transform hover:scale-105 transition-all duration-200 flex items-center justify-center gap-2 border border-pink-200/50">
                                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                                Save Word Clock Arrangement Game
                            </button>
                            <button type="button" id="viewWordClockBtn" class="px-8 py-3.5 rounded-xl bg-gradient-to-r from-teal-200 via-teal-300 to-teal-300 text-white font-bold text-lg shadow-xl hover:from-teal-300 hover:via-teal-400 hover:to-teal-400 transform hover:scale-105 transition-all duration-200 flex items-center justify-center gap-2 border border-teal-200/50">
                                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                                View Game
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Matching Pairs Game Section -->
        <div class="game-section max-w-7xl mx-auto mb-8" data-game-type="matching_pairs" style="display: {{ (isset($selectedGameType) && $selectedGameType == 'matching_pairs') ? 'block' : 'none' }};">
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
                    <div id="matchingPairsSavedView" class="mb-6 p-6 bg-gradient-to-r from-cyan-50 to-teal-50 border-2 border-cyan-300 rounded-xl shadow-lg">
                        <div class="flex justify-between items-center mb-4">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-gradient-to-br from-cyan-400 to-teal-500 rounded-lg flex items-center justify-center shadow-md">
                                    <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                    </svg>
                                </div>
                                <h4 class="text-xl font-bold text-cyan-800">Saved Matching Pairs Game</h4>
                            </div>
                            <button type="button" id="editMatchingPairsBtn" class="px-5 py-2.5 rounded-xl bg-gradient-to-r from-pink-200 via-pink-300 to-pink-300 text-gray-800 font-semibold shadow-md hover:from-pink-300 hover:via-pink-400 hover:to-pink-400 transform hover:scale-105 transition-all duration-200 flex items-center gap-2 border border-pink-200/50">
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                                Edit
                            </button>
                        </div>
                @if(!empty($matchingPairsData['title']))
                    <div class="mb-3">
                        <strong class="text-cyan-800">Title:</strong> 
                        <span class="text-cyan-900 font-semibold text-lg" dir="rtl">{{ $matchingPairsData['title'] }}</span>
                    </div>
                @endif
                        <div class="grid gap-4">
                            @foreach($matchingPairsData['pairs'] as $index => $pair)
                                <div class="border-2 border-cyan-200 rounded-xl p-4 bg-white shadow-sm hover:shadow-md transition-shadow">
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
                                                <button type="button" class="removeMatchingPairBox px-4 py-2 bg-gradient-to-r from-pink-200 via-pink-300 to-pink-300 text-gray-800 rounded-lg font-bold shadow-md hover:from-pink-300 hover:via-pink-400 hover:to-pink-400 transform hover:scale-105 transition-all duration-200 border border-pink-200/50">&times;</button>
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
                                @else
                                    <!-- Initial pair box when no saved data -->
                                    <div class="matching-pair-box border-2 border-teal-200 rounded-xl p-5 bg-gradient-to-r from-pink-50/50 to-teal-50/50 hover:border-teal-300 transition-colors">
                                        <div class="flex justify-between items-center mb-4">
                                            <span class="font-bold text-teal-700 text-lg flex items-center gap-2">
                                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                                </svg>
                                                Pair 1
                                            </span>
                                            <button type="button" class="removeMatchingPairBox px-4 py-2 bg-gradient-to-r from-pink-200 via-pink-300 to-pink-300 text-gray-800 rounded-lg font-bold shadow-md hover:from-pink-300 hover:via-pink-400 hover:to-pink-400 transform hover:scale-105 transition-all duration-200 border border-pink-200/50">&times;</button>
                                        </div>
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                                            <div class="space-y-3">
                                                <label class="block text-sm font-bold text-gray-700">Left Item (Text):</label>
                                                <input type="text" 
                                                       name="pairs[0][left_item_text]" 
                                                       class="w-full bg-pink-50 border-2 border-pink-200 rounded-lg px-4 py-2.5 text-gray-800 font-medium hover:bg-pink-100 focus:border-teal-400 focus:ring-2 focus:ring-teal-200 focus:bg-pink-50 transition-all" 
                                                       placeholder="Text for left column" 
                                                       dir="rtl">
                                                <label class="block text-sm font-bold text-gray-700 mt-3">Left Item (Image):</label>
                                                <input type="file" 
                                                       name="pairs[0][left_item_image]" 
                                                       class="w-full border-2 border-gray-300 rounded-lg px-4 py-2.5 text-gray-800 font-medium focus:border-teal-400 focus:ring-2 focus:ring-teal-200 transition-all file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-teal-50 file:text-teal-700 hover:file:bg-teal-100" 
                                                       accept="image/*">
                                            </div>
                                            <div class="space-y-3">
                                                <label class="block text-sm font-bold text-gray-700">Right Item (Text):</label>
                                                <input type="text" 
                                                       name="pairs[0][right_item_text]" 
                                                       class="w-full bg-pink-50 border-2 border-pink-200 rounded-lg px-4 py-2.5 text-gray-800 font-medium hover:bg-pink-100 focus:border-teal-400 focus:ring-2 focus:ring-teal-200 focus:bg-pink-50 transition-all" 
                                                       placeholder="Text for right column" 
                                                       dir="rtl">
                                                <label class="block text-sm font-bold text-gray-700 mt-3">Right Item (Image):</label>
                                                <input type="file" 
                                                       name="pairs[0][right_item_image]" 
                                                       class="w-full border-2 border-gray-300 rounded-lg px-4 py-2.5 text-gray-800 font-medium focus:border-teal-400 focus:ring-2 focus:ring-teal-200 transition-all file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-teal-50 file:text-teal-700 hover:file:bg-teal-100" 
                                                       accept="image/*">
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                            <div class="flex flex-wrap gap-3 mt-4">
                                <button type="button" id="addMatchingPairBox" class="px-5 py-2.5 rounded-xl bg-gradient-to-r from-pink-200 via-pink-300 to-pink-300 text-gray-800 font-semibold shadow-lg hover:from-pink-300 hover:via-pink-400 hover:to-pink-400 transform hover:scale-105 transition-all duration-200 flex items-center gap-2 w-fit border border-pink-200/50">
                                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                    </svg>
                                    Add Pair
                                </button>
                                <button type="button" id="clearAllMatchingPairsBtn" class="px-5 py-2.5 rounded-xl bg-gradient-to-r from-red-200 via-red-300 to-red-300 text-white font-semibold shadow-lg hover:from-red-300 hover:via-red-400 hover:to-red-400 transform hover:scale-105 transition-all duration-200 flex items-center gap-2 w-fit border border-red-200/50">
                                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                    Clear All
                                </button>
                            </div>
                        </div>

                        <div class="flex flex-wrap gap-3">
                            <button type="submit" class="px-8 py-3.5 rounded-xl bg-gradient-to-r from-pink-200 via-pink-300 to-pink-300 text-gray-800 font-bold text-lg shadow-xl hover:from-pink-300 hover:via-pink-400 hover:to-pink-400 transform hover:scale-105 transition-all duration-200 flex items-center justify-center gap-2 border border-pink-200/50">
                                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                                Save Matching Pairs Game
                            </button>
                            <button type="button" id="viewMatchingPairsBtn" class="px-8 py-3.5 rounded-xl bg-gradient-to-r from-teal-200 via-teal-300 to-teal-300 text-white font-bold text-lg shadow-xl hover:from-teal-300 hover:via-teal-400 hover:to-teal-400 transform hover:scale-105 transition-all duration-200 flex items-center justify-center gap-2 border border-teal-200/50">
                                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                                View Game
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Scrambled Letters Game - Word/Definition Pairs Section -->
        <div class="game-section max-w-7xl mx-auto mb-8" data-game-type="scramble" style="display: {{ (isset($selectedGameType) && $selectedGameType == 'scramble') ? 'block' : 'none' }};">
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
                            <button type="button" class="addPairBtn px-6 py-3 bg-gradient-to-r from-pink-200 via-pink-300 to-pink-300 text-gray-800 font-bold rounded-xl shadow-lg hover:from-pink-300 hover:via-pink-400 hover:to-pink-400 transform hover:scale-105 transition-all duration-200 flex items-center justify-center gap-2 border border-pink-200/50">
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                </svg>
                                Add
                            </button>
                        </div>
                        <div class="mt-3">
                            <button type="button" id="clearAllScrambledPairsBtn" class="px-6 py-3 bg-gradient-to-r from-red-200 via-red-300 to-red-300 text-white font-bold rounded-xl shadow-lg hover:from-red-300 hover:via-red-400 hover:to-red-400 transform hover:scale-105 transition-all duration-200 flex items-center justify-center gap-2 border border-red-200/50">
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                                Clear All
                            </button>
                        </div>
                    </div>
                    <div class="pairs-list space-y-3 mb-6"></div>
                    <div class="flex flex-wrap gap-3">
                        <button type="submit" class="px-8 py-3.5 rounded-xl bg-gradient-to-r from-pink-200 via-pink-300 to-pink-300 text-gray-800 font-bold text-lg shadow-xl hover:from-pink-300 hover:via-pink-400 hover:to-pink-400 transform hover:scale-105 transition-all duration-200 flex items-center justify-center gap-2 border border-pink-200/50">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            Save Scrambled Letters Pairs
                        </button>
                        <button type="button" id="viewScrambledLettersBtn" class="px-8 py-3.5 rounded-xl bg-gradient-to-r from-teal-200 via-teal-300 to-teal-300 text-white font-bold text-lg shadow-xl hover:from-teal-300 hover:via-teal-400 hover:to-teal-400 transform hover:scale-105 transition-all duration-200 flex items-center justify-center gap-2 border border-teal-200/50">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                            View Game
                        </button>
                    </div>
                </form>
            </div>
        </div>
        @endif
    </div>
</div>

<!-- Preview Modals for Game Views -->
<!-- Word Search Preview Modal -->
<div id="wordSearchPreviewModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden items-center justify-center p-4" style="display: none;">
    <div class="bg-white rounded-2xl shadow-2xl max-w-4xl w-full max-h-[90vh] overflow-y-auto">
        <div class="sticky top-0 bg-gradient-to-r from-pink-500 to-cyan-600 text-white p-6 rounded-t-2xl flex justify-between items-center">
            <h3 class="text-2xl font-bold">Word Search Game Preview</h3>
            <button type="button" id="closeWordSearchPreview" class="text-white hover:text-gray-200 text-3xl font-bold w-10 h-10 flex items-center justify-center rounded-full hover:bg-white hover:bg-opacity-20 transition-all">
                ×
            </button>
        </div>
        <div class="p-6">
            <div id="wordSearchPreviewContent" class="space-y-4">
                <!-- Preview content will be generated here -->
            </div>
        </div>
    </div>
</div>

<!-- Word Clock Arrangement Preview Modal -->
<div id="wordClockPreviewModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden items-center justify-center p-4" style="display: none;">
    <div class="bg-white rounded-2xl shadow-2xl max-w-4xl w-full max-h-[90vh] overflow-y-auto">
        <div class="sticky top-0 bg-gradient-to-r from-cyan-500 to-teal-600 text-white p-6 rounded-t-2xl flex justify-between items-center">
            <h3 class="text-2xl font-bold">Word Clock Arrangement Game Preview</h3>
            <button type="button" id="closeWordClockPreview" class="text-white hover:text-gray-200 text-3xl font-bold w-10 h-10 flex items-center justify-center rounded-full hover:bg-white hover:bg-opacity-20 transition-all">
                ×
            </button>
        </div>
        <div class="p-6">
            <div id="wordClockPreviewContent" class="space-y-4">
                <!-- Preview content will be generated here -->
            </div>
        </div>
    </div>
</div>

<!-- Matching Pairs Preview Modal -->
<div id="matchingPairsPreviewModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden items-center justify-center p-4" style="display: none;">
    <div class="bg-white rounded-2xl shadow-2xl max-w-4xl w-full max-h-[90vh] overflow-y-auto">
        <div class="sticky top-0 bg-gradient-to-r from-teal-500 to-cyan-600 text-white p-6 rounded-t-2xl flex justify-between items-center">
            <h3 class="text-2xl font-bold">Matching Pairs Game Preview</h3>
            <button type="button" id="closeMatchingPairsPreview" class="text-white hover:text-gray-200 text-3xl font-bold w-10 h-10 flex items-center justify-center rounded-full hover:bg-white hover:bg-opacity-20 transition-all">
                ×
            </button>
        </div>
        <div class="p-6">
            <div id="matchingPairsPreviewContent" class="space-y-4">
                <!-- Preview content will be generated here -->
            </div>
        </div>
    </div>
</div>

<!-- Scrambled Letters Preview Modal -->
<div id="scrambledLettersPreviewModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden items-center justify-center p-4" style="display: none;">
    <div class="bg-white rounded-2xl shadow-2xl max-w-4xl w-full max-h-[90vh] overflow-y-auto">
        <div class="sticky top-0 bg-gradient-to-r from-pink-500 to-teal-600 text-white p-6 rounded-t-2xl flex justify-between items-center">
            <h3 class="text-2xl font-bold">Scrambled Letters Game Preview</h3>
            <button type="button" id="closeScrambledLettersPreview" class="text-white hover:text-gray-200 text-3xl font-bold w-10 h-10 flex items-center justify-center rounded-full hover:bg-white hover:bg-opacity-20 transition-all">
                ×
            </button>
        </div>
        <div class="p-6">
            <div id="scrambledLettersPreviewContent" class="space-y-4">
                <!-- Preview content will be generated here -->
            </div>
        </div>
    </div>
</div>

<script>
console.log('=== GAMES PAGE SCRIPT LOADING ===');

// Global split sentence handler - defined early so onclick can access it
window.handleSplitSentenceClick = function(e) {
    if (e) {
        e.preventDefault();
        e.stopPropagation();
    }
    console.log('=== Split Sentence button clicked (inline handler) ===');
    
    const sentenceTextarea = document.getElementById('word_clock_sentence');
    const container = document.getElementById('wordClockArrangementWordsBoxes');
    
    if (!sentenceTextarea) {
        alert('Sentence textarea not found. Please refresh the page.');
        return false;
    }
    
    const sentence = sentenceTextarea.value.trim();
    if (!sentence) {
        alert('Please enter a sentence first.');
        return false;
    }
    
    const words = sentence.split(/\s+/).filter(w => w.trim() !== '');
    if (words.length === 0) {
        alert('No words found in the sentence.');
        return false;
    }
    
    if (!container) {
        alert('Error: Could not find the word container. Please refresh the page.');
        return false;
    }
    
    container.innerHTML = '';
    
    words.forEach((word) => {
        if (typeof window.addWordClockArrangementWordBox === 'function') {
            window.addWordClockArrangementWordBox(word);
        } else {
            console.error('addWordClockArrangementWordBox function not available, using fallback');
            // Fallback: create word box manually
            const box = document.createElement('div');
            box.className = 'word-clock-arrangement-word-box flex flex-col md:flex-row items-start md:items-center gap-4 p-5 border-2 border-cyan-200 rounded-xl bg-gradient-to-r from-pink-50/60 to-cyan-50/60 hover:border-cyan-300 transition-colors';
            const escapedWord = word.replace(/"/g, '&quot;').replace(/'/g, '&#39;').replace(/</g, '&lt;').replace(/>/g, '&gt;');
            box.innerHTML = '<div class="flex-1 w-full md:w-auto"><input type="text" name="word_clock_words[][word]" class="w-full bg-pink-50 border-2 border-pink-200 rounded-lg px-4 py-2.5 text-gray-800 font-medium hover:bg-pink-100 focus:border-cyan-400 focus:ring-2 focus:ring-cyan-200 focus:bg-pink-50 transition-all" value="' + escapedWord + '" placeholder="Word" required></div><div class="flex items-center gap-3 flex-wrap"><div class="flex items-center gap-2"><label class="text-sm font-semibold text-gray-700">Hour:</label><input type="number" name="word_clock_words[][hour]" class="w-20 bg-pink-50 border-2 border-pink-200 rounded-lg px-3 py-2 text-gray-800 font-medium hour-input hover:bg-pink-100 focus:border-cyan-400 focus:ring-2 focus:ring-cyan-200 focus:bg-pink-50 transition-all" value="0" min="0" max="11" placeholder="0-11" required></div><div class="flex items-center gap-2"><label class="text-sm font-semibold text-gray-700">Minute:</label><input type="number" name="word_clock_words[][minute]" class="w-20 bg-pink-50 border-2 border-pink-200 rounded-lg px-3 py-2 text-gray-800 font-medium minute-input hover:bg-pink-100 focus:border-cyan-400 focus:ring-2 focus:ring-cyan-200 focus:bg-pink-50 transition-all" value="0" min="0" max="59" placeholder="0-59" required></div><div class="clock-preview ml-2 p-2 bg-pink-50 rounded-lg border-2 border-pink-200 shadow-sm" style="width: 70px; height: 70px;"><svg width="60" height="60" class="clock-svg-preview"><circle cx="30" cy="30" r="27" fill="white" stroke="#333" stroke-width="2"/><circle cx="30" cy="30" r="2" fill="#333"/></svg></div><button type="button" class="removeWordClockArrangementWordBox px-4 py-2.5 bg-gradient-to-r from-pink-200 via-pink-300 to-pink-300 text-gray-800 rounded-lg font-bold shadow-md hover:from-pink-300 hover:via-pink-400 hover:to-pink-400 transform hover:scale-105 transition-all duration-200 border border-pink-200/50">&times;</button></div>';
            container.appendChild(box);
        }
    });
    
    return false;
};

console.log('handleSplitSentenceClick function defined:', typeof window.handleSplitSentenceClick);

// Word Search Game Edit Function - MUST be defined FIRST so onclick handlers can access it
// Only define if not already defined (may be defined inline before button)
if (typeof window.editWordSearchGame === 'undefined') {
    window.editWordSearchGame = function() {
    try {
        const wordSearchSavedView = document.getElementById('wordSearchSavedView');
        const wordSearchSection = document.getElementById('wordSearchSection');
        
        console.log('Edit Word Search function called');
        console.log('wordSearchSavedView:', wordSearchSavedView);
        console.log('wordSearchSection:', wordSearchSection);
        
        if (wordSearchSavedView) {
            wordSearchSavedView.style.setProperty('display', 'none', 'important');
            wordSearchSavedView.classList.add('hidden');
            console.log('Hidden wordSearchSavedView');
        } else {
            console.warn('wordSearchSavedView not found');
        }
        
        if (wordSearchSection) {
            // Remove hidden class first
            wordSearchSection.classList.remove('hidden');
            
            // Force visibility with !important to override Tailwind's hidden class
            wordSearchSection.style.setProperty('display', 'block', 'important');
            wordSearchSection.style.setProperty('visibility', 'visible', 'important');
            wordSearchSection.style.setProperty('opacity', '1', 'important');
            wordSearchSection.style.setProperty('height', 'auto', 'important');
            
            console.log('Shown wordSearchSection');
            console.log('wordSearchSection classes:', wordSearchSection.className);
            console.log('wordSearchSection display:', window.getComputedStyle(wordSearchSection).display);
            
            // Ensure form fields are visible and accessible
            const form = document.getElementById('wordSearchForm');
            if (form) {
                form.style.display = 'block';
                const inputs = form.querySelectorAll('input, select, textarea');
                inputs.forEach(input => {
                    input.style.display = '';
                    input.removeAttribute('disabled');
                });
            }
            
            // Force a reflow to ensure the change takes effect
            void wordSearchSection.offsetHeight;
            
            // Scroll to the form section smoothly
            setTimeout(() => {
                wordSearchSection.scrollIntoView({ behavior: 'smooth', block: 'start', inline: 'nearest' });
                // Focus on the first input field for better UX
                const firstInput = wordSearchSection.querySelector('input[type="text"], input[name*="word"]');
                if (firstInput) {
                    setTimeout(() => firstInput.focus(), 200);
                }
            }, 150);
        } else {
            console.error('wordSearchSection not found');
            alert('Form section not found. Please refresh the page.');
        }
        
        return false; // Prevent any default behavior
    } catch (error) {
        console.error('Error in editWordSearchGame:', error);
        alert('An error occurred. Please check the console for details.');
        return false;
    }
    };
}

// Remove Word Search Word Box Function - Global function for onclick handlers
if (typeof window.removeWordSearchWordBox === 'undefined') {
    window.removeWordSearchWordBox = function(button) {
        try {
            const box = button.closest('.word-search-word-box');
            if (!box) {
                console.warn('Word box not found');
                return false;
            }
            
            const container = document.getElementById('wordSearchWordsBoxes');
            if (!container) {
                console.warn('Container not found');
                return false;
            }
            
            const allBoxes = container.querySelectorAll('.word-search-word-box');
            if (allBoxes.length <= 1) {
                alert('You must have at least one word.');
                return false;
            }
            
            box.remove();
            console.log('Word box removed');
            return false;
        } catch (error) {
            console.error('Error removing word box:', error);
            return false;
        }
    };
}

// TEST: Verify script is loading
console.log('=== GAMES PAGE SCRIPT LOADING ===');
console.log('editWordSearchGame function defined:', typeof window.editWordSearchGame);
console.log('removeWordSearchWordBox function defined:', typeof window.removeWordSearchWordBox);

// Global event delegation handler - works for ALL buttons even if loaded dynamically
(function() {
    'use strict';
    
    console.log('=== GLOBAL EVENT HANDLER SETUP ===');
    
    // Unified click handler for all add buttons
    document.addEventListener('click', function(e) {
        console.log('=== CLICK DETECTED ===', e.target);
        const target = e.target;
        const button = target.closest('button[id], button[class*="add"], button[class*="Add"]') || target;
        
        console.log('Button ID:', button.id, 'Button classes:', button.className);
        
        // Split Sentence Button
        if (button.id === 'splitSentenceBtn' || target.closest('#splitSentenceBtn') || target.id === 'splitSentenceBtn') {
            e.preventDefault();
            e.stopPropagation();
            console.log('=== Split Sentence button clicked (global handler) ===');
            const sentenceTextarea = document.getElementById('word_clock_sentence');
            const container = document.getElementById('wordClockArrangementWordsBoxes');
            
            if (!sentenceTextarea) {
                alert('Sentence textarea not found. Please refresh the page.');
                return false;
            }
            
            const sentence = sentenceTextarea.value.trim();
            if (!sentence) {
                alert('Please enter a sentence first.');
                return false;
            }
            
            const words = sentence.split(/\s+/).filter(w => w.trim() !== '');
            if (words.length === 0) {
                alert('No words found in the sentence.');
                return false;
            }
            
            if (!container) {
                alert('Error: Could not find the word container. Please refresh the page.');
                return false;
            }
            
            container.innerHTML = '';
            
            words.forEach((word) => {
                if (typeof window.addWordClockArrangementWordBox === 'function') {
                    window.addWordClockArrangementWordBox(word);
                } else {
                    console.error('addWordClockArrangementWordBox function not available');
                }
            });
            
            return false;
        }
        
        // Word Search Add Button
        if (button.id === 'addWordSearchWordBox' || target.closest('#addWordSearchWordBox') || target.id === 'addWordSearchWordBox') {
            e.preventDefault();
            e.stopPropagation();
            console.log('=== Word Search Add button clicked ===');
            const container = document.getElementById('wordSearchWordsBoxes');
            if (container) {
                const box = document.createElement('div');
                box.className = 'word-search-word-box flex items-center gap-3 mb-3 p-3 bg-gray-50 rounded-xl border-2 border-gray-200 hover:border-pink-300 transition-colors';
                box.innerHTML = '<input type="text" name="word_search_words[]" class="flex-1 border-2 border-gray-300 rounded-lg px-4 py-2.5 text-gray-800 font-medium focus:border-pink-400 focus:ring-2 focus:ring-pink-200 transition-all" placeholder="Enter word" required><button type="button" class="removeWordSearchWordBox px-4 py-2.5 bg-gradient-to-r from-pink-200 via-pink-300 to-pink-300 text-gray-800 rounded-lg font-bold shadow-md hover:from-pink-300 hover:via-pink-400 hover:to-pink-400 transform hover:scale-105 transition-all duration-200" onclick="removeWordSearchWordBox(this)">&times;</button>';
                container.appendChild(box);
            }
            return false;
        }
        
        // Matching Pairs Add Button
        if (button.id === 'addMatchingPairBox' || target.closest('#addMatchingPairBox') || target.id === 'addMatchingPairBox') {
            e.preventDefault();
            e.stopPropagation();
            e.stopImmediatePropagation();
            
            const btn = target.closest('#addMatchingPairBox') || target;
            if (btn.dataset.processing === 'true') {
                console.log('Add Pair button already being processed, skipping');
                return false;
            }
            btn.dataset.processing = 'true';
            
            console.log('=== Matching Pairs Add button clicked ===');
            const container = document.getElementById('matchingPairsBoxes');
            if (container) {
                const existingPairs = container.querySelectorAll('.matching-pair-box');
                const pairIndex = existingPairs.length;
                
                // Check if there are existing pairs and validate the last one
                if (existingPairs.length > 0) {
                    const lastPair = existingPairs[existingPairs.length - 1];
                    const leftText = lastPair.querySelector('input[name*="[left_item_text]"]')?.value.trim() || '';
                    const rightText = lastPair.querySelector('input[name*="[right_item_text]"]')?.value.trim() || '';
                    const leftImage = lastPair.querySelector('input[name*="[left_item_image]"]');
                    const rightImage = lastPair.querySelector('input[name*="[right_item_image]"]');
                    
                    // Check if last pair has at least one field filled
                    const hasLeftText = leftText.length > 0;
                    const hasRightText = rightText.length > 0;
                    const hasLeftImage = leftImage && leftImage.files && leftImage.files.length > 0;
                    const hasRightImage = rightImage && rightImage.files && rightImage.files.length > 0;
                    const hasImagePreview = lastPair.querySelector('img'); // Check for existing saved image
                    
                    const isFilled = hasLeftText || hasRightText || hasLeftImage || hasRightImage || hasImagePreview;
                    
                    if (!isFilled) {
                        alert('Please fill in Pair ' + existingPairs.length + ' before adding another pair. Each pair must have at least one field (text or image) filled.');
                        btn.dataset.processing = 'false';
                        return false;
                    }
                }
                
                const box = document.createElement('div');
                box.className = 'matching-pair-box border-2 border-teal-200 rounded-xl p-5 bg-gradient-to-r from-pink-50/50 to-teal-50/50 hover:border-teal-300 transition-colors';
                box.innerHTML = '<div class="flex justify-between items-center mb-4"><span class="font-bold text-teal-700 text-lg flex items-center gap-2"><svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" /></svg>Pair ' + (pairIndex + 1) + '</span><button type="button" class="removeMatchingPairBox px-4 py-2 bg-gradient-to-r from-pink-200 via-pink-300 to-pink-300 text-gray-800 rounded-lg font-bold shadow-md hover:from-pink-300 hover:via-pink-400 hover:to-pink-400 transform hover:scale-105 transition-all duration-200 border border-pink-200/50">&times;</button></div><div class="grid grid-cols-1 md:grid-cols-2 gap-5"><div class="space-y-3"><label class="block text-sm font-bold text-gray-700">Left Item (Text):</label><input type="text" name="pairs[' + pairIndex + '][left_item_text]" class="w-full bg-pink-50 border-2 border-pink-200 rounded-lg px-4 py-2.5 text-gray-800 font-medium hover:bg-pink-100 focus:border-teal-400 focus:ring-2 focus:ring-teal-200 focus:bg-pink-50 transition-all" placeholder="Text for left column" dir="rtl"><label class="block text-sm font-bold text-gray-700 mt-3">Left Item (Image):</label><input type="file" name="pairs[' + pairIndex + '][left_item_image]" class="w-full border-2 border-gray-300 rounded-lg px-4 py-2.5 text-gray-800 font-medium focus:border-teal-400 focus:ring-2 focus:ring-teal-200 transition-all file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-teal-50 file:text-teal-700 hover:file:bg-teal-100" accept="image/*"></div><div class="space-y-3"><label class="block text-sm font-bold text-gray-700">Right Item (Text):</label><input type="text" name="pairs[' + pairIndex + '][right_item_text]" class="w-full bg-pink-50 border-2 border-pink-200 rounded-lg px-4 py-2.5 text-gray-800 font-medium hover:bg-pink-100 focus:border-teal-400 focus:ring-2 focus:ring-teal-200 focus:bg-pink-50 transition-all" placeholder="Text for right column" dir="rtl"><label class="block text-sm font-bold text-gray-700 mt-3">Right Item (Image):</label><input type="file" name="pairs[' + pairIndex + '][right_item_image]" class="w-full border-2 border-gray-300 rounded-lg px-4 py-2.5 text-gray-800 font-medium focus:border-teal-400 focus:ring-2 focus:ring-teal-200 transition-all file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-teal-50 file:text-teal-700 hover:file:bg-teal-100" accept="image/*"></div></div>';
                container.appendChild(box);
            }
            btn.dataset.processing = 'false';
            return false;
        }
        
        // Clock Game Add Button
        if (button.id === 'addWordClockArrangementWordBox' || target.closest('#addWordClockArrangementWordBox') || target.id === 'addWordClockArrangementWordBox') {
            e.preventDefault();
            e.stopPropagation();
            console.log('=== Clock Game Add button clicked ===');
            if (typeof window.addWordClockArrangementWordBox === 'function') {
                window.addWordClockArrangementWordBox();
            } else {
                // Fallback if function doesn't exist
                const container = document.getElementById('wordClockArrangementWordsBoxes');
                if (container) {
                    alert('Clock game function not loaded. Please refresh the page.');
                }
            }
            return false;
        }
        
        // Scrambled Letters Add Button
        if (target.classList.contains('addPairBtn') || target.closest('.addPairBtn') || button.classList.contains('addPairBtn')) {
            e.preventDefault();
            e.stopPropagation();
            e.stopImmediatePropagation();
            console.log('=== Add Pair button clicked ===');
            const btn = target.closest('.addPairBtn') || target;
            
            // Check if button is already being processed
            if (btn.dataset.processing === 'true') {
                console.log('Button already being processed in main handler, skipping');
                return false;
            }
            
            // Mark button as processing
            btn.dataset.processing = 'true';
            
            const form = btn.closest('form');
            if (form) {
                const pairsList = form.querySelector('.pairs-list');
                
                // Find the editable input fields - look for inputs that are siblings of the button
                const buttonContainer = btn.parentElement; // Get the immediate parent (the div with flex classes)
                let wordInput = null;
                let defInput = null;
                
                // First, try to find inputs in the same container as the button (siblings)
                if (buttonContainer) {
                    const inputs = buttonContainer.querySelectorAll('input[name="words[]"], input[name="definitions[]"]');
                    inputs.forEach(input => {
                        if (!input.readOnly && input.name === 'words[]' && !wordInput) {
                            wordInput = input;
                        }
                        if (!input.readOnly && input.name === 'definitions[]' && !defInput) {
                            defInput = input;
                        }
                    });
                }
                
                // Fallback: find any non-readonly inputs in the form, but exclude those in .pairs-list
                if (!wordInput || !defInput) {
                    const allWordInputs = form.querySelectorAll('input[name="words[]"]');
                    const allDefInputs = form.querySelectorAll('input[name="definitions[]"]');
                    wordInput = Array.from(allWordInputs).find(input => {
                        return !input.readOnly && !input.closest('.pairs-list');
                    });
                    defInput = Array.from(allDefInputs).find(input => {
                        return !input.readOnly && !input.closest('.pairs-list');
                    });
                }
                
                if (pairsList && wordInput && defInput) {
                    const wordVal = wordInput.value.trim();
                    const defVal = defInput.value.trim();
                    console.log('Values:', { wordVal, defVal, wordLength: wordVal.length, defLength: defVal.length });
                    if (wordVal && defVal) {
                        const pairDiv = document.createElement('div');
                        pairDiv.className = 'flex flex-col md:flex-row gap-3 p-4 bg-gradient-to-r from-pink-50 to-teal-50 border-2 border-teal-200 rounded-xl hover:border-teal-300 transition-colors';
                        const escapedWord = wordVal.replace(/'/g, "&#39;").replace(/"/g, "&quot;").replace(/</g, "&lt;").replace(/>/g, "&gt;");
                        const escapedDef = defVal.replace(/'/g, "&#39;").replace(/"/g, "&quot;").replace(/</g, "&lt;").replace(/>/g, "&gt;");
                        pairDiv.innerHTML = '<input type="text" name="words[]" value="' + escapedWord + '" class="flex-1 border-2 border-teal-300 rounded-lg px-4 py-2.5 text-teal-900 font-semibold bg-pink-50" required readonly><input type="text" name="definitions[]" value="' + escapedDef + '" class="flex-2 border-2 border-teal-300 rounded-lg px-4 py-2.5 text-teal-900 font-semibold bg-pink-50" required readonly><button type="button" class="removePairBtn px-4 py-2.5 bg-gradient-to-r from-pink-200 via-pink-300 to-pink-300 text-gray-800 rounded-lg font-bold shadow-md hover:from-pink-300 hover:via-pink-400 hover:to-pink-400 transform hover:scale-105 transition-all duration-200 border border-pink-200/50">-</button>';
                        pairsList.appendChild(pairDiv);
                        wordInput.value = '';
                        defInput.value = '';
                        pairDiv.querySelector('.removePairBtn').onclick = function() { pairDiv.remove(); };
                        // Mark button as not processing after successful addition
                        btn.dataset.processing = 'false';
                    } else {
                        console.error('Validation failed:', { wordVal, defVal });
                        btn.dataset.processing = 'false';
                        alert('Please enter both word and definition before adding.');
                    }
                } else {
                    console.error('Could not find required elements:', { pairsList, wordInput, defInput });
                    btn.dataset.processing = 'false';
                    alert('Error: Could not find input fields. Please refresh the page.');
                }
            }
            return false;
        }
        
        // Remove buttons
        if (target.classList.contains('removeWordSearchWordBox') || target.closest('.removeWordSearchWordBox')) {
            e.preventDefault();
            const box = target.closest('.word-search-word-box');
            if (box) {
                const container = document.getElementById('wordSearchWordsBoxes');
                if (container && container.querySelectorAll('.word-search-word-box').length > 1) {
                    box.remove();
                } else {
                    alert('You must have at least one word.');
                }
            }
            return false;
        }
        
        if (target.classList.contains('removeMatchingPairBox') || target.closest('.removeMatchingPairBox')) {
            e.preventDefault();
            const box = target.closest('.matching-pair-box');
            if (box) {
                box.remove();
                const container = document.getElementById('matchingPairsBoxes');
                if (container) {
                    const remainingBoxes = container.querySelectorAll('.matching-pair-box');
                    remainingBoxes.forEach(function(box, index) {
                        const span = box.querySelector('span.font-bold');
                        if (span) {
                            span.innerHTML = '<svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" /></svg>Pair ' + (index + 1);
                        }
                        const inputs = box.querySelectorAll('input[type="text"], input[type="file"]');
                        inputs.forEach(function(input) {
                            input.name = input.name.replace(/pairs\[\d+\]/, 'pairs[' + index + ']');
                        });
                    });
                }
            }
            return false;
        }
        
        if (target.classList.contains('removePairBtn') || target.closest('.removePairBtn')) {
            e.preventDefault();
            const pairDiv = target.closest('div.flex.flex-col, div.flex-row');
            if (pairDiv && pairDiv.classList.contains('bg-gradient-to-r')) {
                pairDiv.remove();
            }
            return false;
        }
    });
})();

document.addEventListener('DOMContentLoaded', function() {
    console.log('Games page JavaScript loaded');
    
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
    
    // Update class_id fields and validate before form submissions
    const wordSearchForm = document.getElementById('wordSearchForm');
    if (wordSearchForm) {
        wordSearchForm.addEventListener('submit', function(e) {
            // Update class_id fields
            updateClassIdFields(classSelector ? classSelector.value : '');
            
            // Validate that at least one word is entered
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
    
    // Note: wordClockArrangementForm submit handler is defined later in the code
    // to handle validation and re-indexing
    
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
    
    // Word Search Game functionality - Event listener
    const editWordSearchBtn = document.getElementById('editWordSearchBtn');
    const viewWordSearchBtn = document.getElementById('viewWordSearchBtn');
    const wordSearchSavedView = document.getElementById('wordSearchSavedView');
    const wordSearchSection = document.getElementById('wordSearchSection');
    
    console.log('Word Search Elements:', {
        editWordSearchBtn: !!editWordSearchBtn,
        viewWordSearchBtn: !!viewWordSearchBtn,
        wordSearchSavedView: !!wordSearchSavedView,
        wordSearchSection: !!wordSearchSection
    });
    
    if (editWordSearchBtn) {
        editWordSearchBtn.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            window.editWordSearchGame();
        });
    } else {
        console.warn('Edit Word Search button not found');
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
                let modalHTML = '<div class="bg-gradient-to-br from-pink-50 to-cyan-50 rounded-xl p-6">';
                if (wordSearchGameData.title) {
                    modalHTML += '<h4 class="text-xl font-bold text-purple-600 mb-4" dir="rtl">' + wordSearchGameData.title.replace(/"/g, '&quot;').replace(/'/g, '&#39;').replace(/</g, '&lt;').replace(/>/g, '&gt;') + '</h4>';
                }
                modalHTML += '<div class="flex flex-col lg:flex-row gap-8"><div class="flex-1"><h5 class="text-lg font-bold mb-3">Word Search Grid</h5><div class="inline-block border-2 border-gray-300 bg-white p-2 rounded-lg" style="direction: ltr;">';
                
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
                
                modalHTML += '</div></div><div class="lg:w-64"><h5 class="text-lg font-bold mb-3" dir="rtl">Words to Find:</h5><div class="space-y-2" dir="rtl">';
                
                // Render words list
                if (wordSearchGameData.words && wordSearchGameData.words.length > 0) {
                    wordSearchGameData.words.forEach(word => {
                        const escapedWord = String(word).replace(/"/g, '&quot;').replace(/'/g, '&#39;').replace(/</g, '&lt;').replace(/>/g, '&gt;');
                        modalHTML += '<div class="p-3 border-2 border-gray-300 rounded-lg bg-white"><span class="font-semibold text-lg" dir="rtl">' + escapedWord + '</span></div>';
                    });
                } else {
                    modalHTML += '<p class="text-gray-500">No words available</p>';
                }
                
                modalHTML += '</div></div></div></div>';
                
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
    function handleAddWordSearchWordBox(e) {
        e.preventDefault();
        e.stopPropagation();
        console.log('Add Word Search Word Box button clicked');
        
        const wordSearchWordsBoxes = document.getElementById('wordSearchWordsBoxes');
        if (!wordSearchWordsBoxes) {
            console.error('wordSearchWordsBoxes container not found');
            alert('Error: Could not find the words container. Please refresh the page.');
            return;
        }
        
        const box = document.createElement('div');
        box.className = 'word-search-word-box flex items-center gap-3 mb-3 p-3 bg-gray-50 rounded-xl border-2 border-gray-200 hover:border-pink-300 transition-colors';
        box.innerHTML = `
            <input type="text" name="word_search_words[]" class="flex-1 border-2 border-gray-300 rounded-lg px-4 py-2.5 text-gray-800 font-medium focus:border-pink-400 focus:ring-2 focus:ring-pink-200 transition-all" placeholder="Enter word" required>
            <button type="button" class="removeWordSearchWordBox px-4 py-2.5 bg-gradient-to-r from-pink-200 via-pink-300 to-pink-300 text-gray-800 rounded-lg font-bold shadow-md hover:from-pink-300 hover:via-pink-400 hover:to-pink-400 transform hover:scale-105 transition-all duration-200" onclick="removeWordSearchWordBox(this)">&times;</button>
        `;
        wordSearchWordsBoxes.appendChild(box);
        console.log('Word box added successfully');
    }
    
    // Use event delegation for dynamic elements
    document.addEventListener('click', function(e) {
        if (e.target.id === 'addWordSearchWordBox' || e.target.closest('#addWordSearchWordBox')) {
            handleAddWordSearchWordBox(e);
        }
    });
    
    // Also attach directly if element exists
    const addWordSearchWordBox = document.getElementById('addWordSearchWordBox');
    if (addWordSearchWordBox) {
        addWordSearchWordBox.addEventListener('click', handleAddWordSearchWordBox);
    }

    // Remove word box logic
    const wordSearchWordsBoxes = document.getElementById('wordSearchWordsBoxes');
    if (wordSearchWordsBoxes) {
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
            console.log('=== Word Clock Arrangement Form Submitting ===');
            
            // Get all word boxes from the form container
            const container = document.getElementById('wordClockArrangementWordsBoxes');
            if (!container) {
                console.error('Word clock arrangement container not found');
                e.preventDefault();
                alert('Error: Form container not found. Please refresh the page.');
                return false;
            }
            
            // Get all boxes as an array (not NodeList) so we can modify it
            let wordBoxes = Array.from(container.querySelectorAll('.word-clock-arrangement-word-box'));
            let hasEmptyBoxes = false;
            let validBoxes = [];
            
            // First pass: remove completely empty boxes and validate
            wordBoxes = wordBoxes.filter(function(box) {
                const wordInput = box.querySelector('input[name*="word"]');
                const hourInput = box.querySelector('input[name*="hour"]');
                const minuteInput = box.querySelector('input[name*="minute"]');
                
                // Check if all fields are empty
                const word = wordInput ? wordInput.value.trim() : '';
                const hour = hourInput ? hourInput.value : '';
                const minute = minuteInput ? minuteInput.value : '';
                
                if (!word && !hour && !minute) {
                    // Completely empty box - remove it
                    console.log('Removing empty box');
                    box.remove();
                    return false; // Don't include in filtered array
                }
                
                // Check if any field is missing
                if (!word || hour === '' || minute === '') {
                    hasEmptyBoxes = true;
                    console.log('Found incomplete box:', { word, hour, minute });
                    return true; // Keep it for now to show error
                } else {
                    validBoxes.push({ word, hour, minute });
                    return true; // Keep valid boxes
                }
            });
            
            if (hasEmptyBoxes) {
                e.preventDefault();
                alert('Please fill in all fields (word, hour, and minute) for each word, or remove empty word boxes.');
                return false;
            }
            
            if (validBoxes.length === 0) {
                e.preventDefault();
                alert('Please add at least one word with clock time.');
                return false;
            }
            
            console.log('Valid boxes count:', validBoxes.length);
            console.log('Valid boxes:', validBoxes);
            
            // Re-index remaining boxes - get fresh list after removals
            const remainingBoxes = Array.from(container.querySelectorAll('.word-clock-arrangement-word-box'));
            console.log('Remaining boxes after cleanup:', remainingBoxes.length);
            
            remainingBoxes.forEach(function(box, index) {
                // Find inputs - try multiple selectors to be sure we find them
                let wordInput = box.querySelector('input[name*="[word]"]') || 
                               box.querySelector('input[name*="word"]') ||
                               box.querySelector('input[type="text"]');
                let hourInput = box.querySelector('input[name*="[hour]"]') || 
                               box.querySelector('input[name*="hour"]') ||
                               box.querySelector('input.hour-input');
                let minuteInput = box.querySelector('input[name*="[minute]"]') || 
                                 box.querySelector('input[name*="minute"]') ||
                                 box.querySelector('input.minute-input');
                
                // If still not found, try finding by position in the box
                if (!wordInput || !hourInput || !minuteInput) {
                    const allInputs = box.querySelectorAll('input');
                    if (allInputs.length >= 3) {
                        wordInput = wordInput || allInputs[0];
                        hourInput = hourInput || allInputs[1];
                        minuteInput = minuteInput || allInputs[2];
                    }
                }
                
                if (wordInput) {
                    wordInput.name = `word_clock_words[${index}][word]`;
                    console.log(`Re-indexed word input ${index}:`, wordInput.name, '=', wordInput.value);
                } else {
                    console.error(`Could not find word input in box ${index}`);
                }
                if (hourInput) {
                    hourInput.name = `word_clock_words[${index}][hour]`;
                    console.log(`Re-indexed hour input ${index}:`, hourInput.name, '=', hourInput.value);
                } else {
                    console.error(`Could not find hour input in box ${index}`);
                }
                if (minuteInput) {
                    minuteInput.name = `word_clock_words[${index}][minute]`;
                    console.log(`Re-indexed minute input ${index}:`, minuteInput.name, '=', minuteInput.value);
                } else {
                    console.error(`Could not find minute input in box ${index}`);
                }
            });
            
            // Verify the re-indexing worked
            const finalBoxes = Array.from(container.querySelectorAll('.word-clock-arrangement-word-box'));
            console.log('Final boxes count:', finalBoxes.length);
            
            // Double-check that all inputs are properly named
            let allInputsNamed = true;
            finalBoxes.forEach(function(box, index) {
                const wordInput = box.querySelector('input[name*="word"]');
                const hourInput = box.querySelector('input[name*="hour"]');
                const minuteInput = box.querySelector('input[name*="minute"]');
                
                const expectedWordName = `word_clock_words[${index}][word]`;
                const expectedHourName = `word_clock_words[${index}][hour]`;
                const expectedMinuteName = `word_clock_words[${index}][minute]`;
                
                if (wordInput && wordInput.name !== expectedWordName) {
                    console.warn(`Word input ${index} has wrong name: ${wordInput.name}, expected: ${expectedWordName}`);
                    wordInput.name = expectedWordName;
                }
                if (hourInput && hourInput.name !== expectedHourName) {
                    console.warn(`Hour input ${index} has wrong name: ${hourInput.name}, expected: ${expectedHourName}`);
                    hourInput.name = expectedHourName;
                }
                if (minuteInput && minuteInput.name !== expectedMinuteName) {
                    console.warn(`Minute input ${index} has wrong name: ${minuteInput.name}, expected: ${expectedMinuteName}`);
                    minuteInput.name = expectedMinuteName;
                }
            });
            
            // Log final form data before submission
            const formData = new FormData(wordClockArrangementForm);
            console.log('Form data being submitted:');
            let wordClockWordsFound = false;
            let wordClockWordsCount = 0;
            for (let [key, value] of formData.entries()) {
                console.log(key, ':', value);
                if (key.startsWith('word_clock_words[')) {
                    wordClockWordsFound = true;
                    if (key.includes('[word]')) {
                        wordClockWordsCount++;
                    }
                }
            }
            
            console.log('Word clock words found in form:', wordClockWordsCount);
            
            if (!wordClockWordsFound || wordClockWordsCount === 0) {
                console.error('ERROR: word_clock_words not found in form data!');
                e.preventDefault();
                alert('Error: No word data found. Please add at least one word with clock time.');
                return false;
            }
            
            // Update class ID fields before submission
            if (typeof updateClassIdFields === 'function') {
                const classSelector = document.getElementById('class_id');
                if (classSelector) {
                    updateClassIdFields(classSelector.value || '');
                } else {
                    updateClassIdFields('');
                }
            }
            
            // Don't prevent default - allow form to submit
            console.log('Form submission proceeding with', wordClockWordsCount, 'words...');
        });
    }

    // Split sentence into words
    console.log('Setting up split sentence button...');
    const splitSentenceBtn = document.getElementById('splitSentenceBtn');
    const wordClockSentence = document.getElementById('word_clock_sentence');
    const wordClockArrangementWordsBoxes = document.getElementById('wordClockArrangementWordsBoxes');
    
    console.log('Split button found:', !!splitSentenceBtn);
    console.log('Sentence textarea found:', !!wordClockSentence);
    console.log('Words container found:', !!wordClockArrangementWordsBoxes);
    
    function handleSplitSentence() {
        console.log('Split button clicked!');
        const sentence = wordClockSentence ? wordClockSentence.value.trim() : '';
        console.log('Sentence value:', sentence);
        
        if (!sentence) {
            alert('Please enter a sentence first.');
            return;
        }
        
        // Split by spaces and filter empty strings
        const words = sentence.split(/\s+/).filter(w => w.trim() !== '');
        console.log('Split words:', words);
        
        if (words.length === 0) {
            alert('No words found in the sentence.');
            return;
        }
        
        // Get or find the container
        let container = wordClockArrangementWordsBoxes;
        if (!container) {
            container = document.getElementById('wordClockArrangementWordsBoxes');
        }
        
        if (!container) {
            console.error('wordClockArrangementWordsBoxes container not found');
            alert('Error: Could not find the word container. Please refresh the page.');
            return;
        }
        
        console.log('Container found, clearing and adding words...');
        
        // Clear existing word boxes (optional - you might want to ask for confirmation)
        container.innerHTML = '';
        
        // Create word boxes for each word
        words.forEach((word, index) => {
            console.log('Adding word box for:', word);
            if (typeof window.addWordClockArrangementWordBox === 'function') {
                window.addWordClockArrangementWordBox(word);
            } else {
                console.error('addWordClockArrangementWordBox function not found');
                alert('Error: Function not loaded. Please refresh the page.');
            }
        });
        
        console.log('Split complete!');
    }
    
    if (splitSentenceBtn && wordClockSentence) {
        console.log('Attaching event listener to split button...');
        splitSentenceBtn.addEventListener('click', handleSplitSentence);
        // Also add direct onclick as backup
        splitSentenceBtn.onclick = function(e) {
            e.preventDefault();
            e.stopPropagation();
            handleSplitSentence();
            return false;
        };
        console.log('Split button event listeners attached successfully');
    } else {
        console.error('Split button or sentence textarea not found!', {
            splitSentenceBtn: !!splitSentenceBtn,
            wordClockSentence: !!wordClockSentence
        });
    }

    // Function to create clock SVG
    function createClockSVG(hour, minute, size = 86) {
        const center = size / 2;
        const radius = size / 2 - 4;
        const hourHandLength = radius * 0.5;
        const minuteHandLength = radius * 0.75;
        
        // Calculate angles
        const hourAngle = ((hour % 12) * 30 + minute * 0.5 - 90) * Math.PI / 180;
        const minuteAngle = (minute * 6 - 90) * Math.PI / 180;
        
        // Adjust viewBox to give padding for numbers (100x100 for size 86)
        const viewBoxSize = 100;
        const viewBoxOffset = 7;
        const adjustedCenter = center + viewBoxOffset;
        
        // Calculate hand positions for adjusted center
        const hourX = adjustedCenter + hourHandLength * Math.cos(hourAngle);
        const hourY = adjustedCenter + hourHandLength * Math.sin(hourAngle);
        const minuteX = adjustedCenter + minuteHandLength * Math.cos(minuteAngle);
        const minuteY = adjustedCenter + minuteHandLength * Math.sin(minuteAngle);
        
        let svg = `<svg width="${size}" height="${size}" class="clock-svg-preview" viewBox="0 0 ${viewBoxSize} ${viewBoxSize}" style="display: block; margin: 0 auto;">
            <circle cx="${adjustedCenter}" cy="${adjustedCenter}" r="${radius - 2}" fill="white" stroke="#333" stroke-width="2.5"/>`;
        
        // Add numbers - positioned more inward
        for (let i = 1; i <= 12; i++) {
            const angle = ((i - 3) * 30) * Math.PI / 180;
            const x = adjustedCenter + (radius - 8) * Math.cos(angle);
            const y = adjustedCenter + (radius - 8) * Math.sin(angle);
            const fontSize = 9;
            svg += `<text x="${x}" y="${y + 5}" text-anchor="middle" font-size="${fontSize}" fill="#333" dominant-baseline="middle">${i}</text>`;
        }
        
        svg += `<line x1="${adjustedCenter}" y1="${adjustedCenter}" x2="${hourX}" y2="${hourY}" stroke="#333" stroke-width="3" stroke-linecap="round"/>
            <line x1="${adjustedCenter}" y1="${adjustedCenter}" x2="${minuteX}" y2="${minuteY}" stroke="#333" stroke-width="2.5" stroke-linecap="round"/>
            <circle cx="${adjustedCenter}" cy="${adjustedCenter}" r="3" fill="#333"/>
        </svg>`;
        
        return svg;
    }

    // Function to update clock preview
    function updateClockPreview(container, hour, minute) {
        const clockPreview = container.querySelector('.clock-preview');
        if (clockPreview) {
            // Use 86px to account for padding (110px container - 24px padding = 86px)
            const size = 86;
            clockPreview.innerHTML = createClockSVG(parseInt(hour) || 0, parseInt(minute) || 0, size);
        }
    }

    // Add word box - Make it globally available
    window.addWordClockArrangementWordBox = function(wordValue = '') {
        // Get or find the container
        let container = wordClockArrangementWordsBoxes;
        if (!container) {
            container = document.getElementById('wordClockArrangementWordsBoxes');
        }
        
        if (!container) {
            console.error('wordClockArrangementWordsBoxes container not found');
            alert('Error: Could not find the word container. Please refresh the page.');
            return;
        }
        
        // Escape HTML to prevent XSS
        const escapeHtml = function(text) {
            const div = document.createElement('div');
            div.textContent = text;
            return div.innerHTML;
        };
        
        const box = document.createElement('div');
        box.className = 'word-clock-arrangement-word-box flex flex-col md:flex-row items-start md:items-center gap-4 p-5 border-2 border-cyan-200 rounded-xl bg-gradient-to-r from-pink-50/60 to-cyan-50/60 hover:border-cyan-300 transition-colors';
        box.innerHTML = `
            <div class="flex-1 w-full md:w-auto">
                <input type="text" name="word_clock_words[][word]" 
                       class="w-full bg-pink-50 border-2 border-pink-200 rounded-lg px-4 py-2.5 text-gray-800 font-medium hover:bg-pink-100 focus:border-cyan-400 focus:ring-2 focus:ring-cyan-200 focus:bg-pink-50 transition-all" 
                       value="${escapeHtml(wordValue)}" 
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
                           min="0" max="59" placeholder="0-59" required
                           oninput="if(this.value > 59) this.value = 59; if(this.value < 0) this.value = 0;"
                           onblur="if(this.value > 59) this.value = 59; if(this.value < 0) this.value = 0;"
                           onkeypress="if(event.key && !isNaN(event.key) && parseInt(this.value + event.key) > 59) event.preventDefault();">
                </div>
                <div class="clock-preview ml-2 bg-pink-50 rounded-lg border-2 border-pink-200 shadow-sm flex items-center justify-center" style="width: 110px; height: 110px; min-width: 110px; min-height: 110px; overflow: hidden; flex-shrink: 0;">
                    ${createClockSVG(0, 0, 86)}
                </div>
                <button type="button" class="removeWordClockArrangementWordBox px-4 py-2.5 bg-gradient-to-r from-pink-200 via-pink-300 to-pink-300 text-gray-800 rounded-lg font-bold shadow-md hover:from-pink-300 hover:via-pink-400 hover:to-pink-400 transform hover:scale-105 transition-all duration-200 border border-pink-200/50">&times;</button>
            </div>
        `;
        container.appendChild(box);
        
        // Add event listeners for clock preview updates
        const hourInput = box.querySelector('.hour-input');
        const minuteInput = box.querySelector('.minute-input');
        
        hourInput.addEventListener('input', function() {
            updateClockPreview(box, hourInput.value, minuteInput.value);
        });
        
        minuteInput.addEventListener('input', function() {
            // Validate minute value (0-59)
            let minuteValue = parseInt(this.value) || 0;
            if (minuteValue > 59) {
                this.value = 59;
                minuteValue = 59;
            } else if (minuteValue < 0) {
                this.value = 0;
                minuteValue = 0;
            }
            updateClockPreview(box, hourInput.value, minuteValue);
        });
        
        minuteInput.addEventListener('blur', function() {
            // Clamp value on blur if user somehow entered invalid value
            let minuteValue = parseInt(this.value) || 0;
            if (minuteValue > 59) {
                this.value = 59;
            } else if (minuteValue < 0) {
                this.value = 0;
            }
        });
        
        minuteInput.addEventListener('keypress', function(e) {
            // Prevent typing if the resulting value would be > 59
            const currentValue = this.value;
            const key = e.key;
            
            // Allow: backspace, delete, tab, escape, enter, and decimal point
            if ([8, 9, 27, 13, 46, 110, 190].indexOf(e.keyCode) !== -1 ||
                // Allow: Ctrl+A, Ctrl+C, Ctrl+V, Ctrl+X
                (e.keyCode === 65 && e.ctrlKey === true) ||
                (e.keyCode === 67 && e.ctrlKey === true) ||
                (e.keyCode === 86 && e.ctrlKey === true) ||
                (e.keyCode === 88 && e.ctrlKey === true) ||
                // Allow: home, end, left, right
                (e.keyCode >= 35 && e.keyCode <= 39)) {
                return;
            }
            
            // Ensure that it is a number and stop the keypress
            if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
                e.preventDefault();
                return;
            }
            
            // Check if the resulting value would exceed 59
            const newValue = currentValue + key;
            if (parseInt(newValue) > 59) {
                e.preventDefault();
                return;
            }
        });
    }

    // Add word box button
    function handleAddWordClockArrangementWordBox(e) {
        e.preventDefault();
        e.stopPropagation();
        console.log('Add Word Clock Arrangement Word Box button clicked');
        addWordClockArrangementWordBox();
    }
    
    // Use event delegation for dynamic elements
    document.addEventListener('click', function(e) {
        if (e.target.id === 'addWordClockArrangementWordBox' || e.target.closest('#addWordClockArrangementWordBox')) {
            handleAddWordClockArrangementWordBox(e);
        }
    });
    
    // Also attach directly if element exists
    const addWordClockArrangementWordBoxBtn = document.getElementById('addWordClockArrangementWordBox');
    if (addWordClockArrangementWordBoxBtn) {
        addWordClockArrangementWordBoxBtn.addEventListener('click', handleAddWordClockArrangementWordBox);
    }

    // Remove word box logic - use event delegation on document to handle dynamically added elements
    if (wordClockArrangementWordsBoxes) {
        wordClockArrangementWordsBoxes.addEventListener('click', function(e) {
            if (e.target.classList.contains('removeWordClockArrangementWordBox')) {
                const box = e.target.closest('.word-clock-arrangement-word-box');
                if (box) box.remove();
            }
        });
    } else {
        // Fallback: use document-level event delegation if container doesn't exist yet
        document.addEventListener('click', function(e) {
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
                // Validate minute value (0-59)
                let minuteValue = parseInt(this.value) || 0;
                if (minuteValue > 59) {
                    this.value = 59;
                    minuteValue = 59;
                } else if (minuteValue < 0) {
                    this.value = 0;
                    minuteValue = 0;
                }
                updateClockPreview(box, hourInput.value, minuteValue);
            });
            
            minuteInput.addEventListener('blur', function() {
                // Clamp value on blur if user somehow entered invalid value
                let minuteValue = parseInt(this.value) || 0;
                if (minuteValue > 59) {
                    this.value = 59;
                } else if (minuteValue < 0) {
                    this.value = 0;
                }
            });
            
            minuteInput.addEventListener('keypress', function(e) {
                // Prevent typing if the resulting value would be > 59
                const currentValue = this.value;
                const key = e.key;
                
                // Allow: backspace, delete, tab, escape, enter, and decimal point
                if ([8, 9, 27, 13, 46, 110, 190].indexOf(e.keyCode) !== -1 ||
                    // Allow: Ctrl+A, Ctrl+C, Ctrl+V, Ctrl+X
                    (e.keyCode === 65 && e.ctrlKey === true) ||
                    (e.keyCode === 67 && e.ctrlKey === true) ||
                    (e.keyCode === 86 && e.ctrlKey === true) ||
                    (e.keyCode === 88 && e.ctrlKey === true) ||
                    // Allow: home, end, left, right
                    (e.keyCode >= 35 && e.keyCode <= 39)) {
                    return;
                }
                
                // Ensure that it is a number and stop the keypress
                if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
                    e.preventDefault();
                    return;
                }
                
                // Check if the resulting value would exceed 59
                const newValue = currentValue + key;
                if (parseInt(newValue) > 59) {
                    e.preventDefault();
                    return;
                }
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
        editMatchingPairsBtn.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            console.log('Edit Matching Pairs clicked (addEventListener)');
            if (matchingPairsSavedView) {
                matchingPairsSavedView.style.display = 'none';
                console.log('Saved view hidden');
            }
            if (matchingPairsSection) {
                matchingPairsSection.classList.remove('hidden');
                console.log('Matching pairs section shown');
            }
        });
        
        // Also add direct onclick as backup
        editMatchingPairsBtn.onclick = function(e) {
            e.preventDefault();
            e.stopPropagation();
            console.log('Edit Matching Pairs clicked (direct onclick)');
            const savedView = document.getElementById('matchingPairsSavedView');
            const section = document.getElementById('matchingPairsSection');
            if (savedView) {
                savedView.style.display = 'none';
                console.log('Saved view hidden (onclick)');
            }
            if (section) {
                section.classList.remove('hidden');
                console.log('Matching pairs section shown (onclick)');
            }
            return false;
        };
        console.log('Edit Matching Pairs button handlers attached');
    } else {
        console.log('Edit Matching Pairs button not found in DOMContentLoaded');
    }

    // Add/Remove Matching Pair Boxes
    function handleAddMatchingPairBox(e) {
        if (e) {
            e.preventDefault();
            e.stopPropagation();
            e.stopImmediatePropagation();
        }
        
        const btn = e ? (e.target.closest('#addMatchingPairBox') || e.target) : document.getElementById('addMatchingPairBox');
        if (btn && btn.dataset.processing === 'true') {
            console.log('Add Pair button already being processed, skipping');
            return false;
        }
        if (btn) {
            btn.dataset.processing = 'true';
        }
        
        console.log('Add Matching Pair Box button clicked');
        
        const matchingPairsBoxes = document.getElementById('matchingPairsBoxes');
        if (!matchingPairsBoxes) {
            console.error('matchingPairsBoxes container not found');
            alert('Error: Could not find the pairs container. Please refresh the page.');
            if (btn) btn.dataset.processing = 'false';
            return;
        }
        
        const existingPairs = matchingPairsBoxes.querySelectorAll('.matching-pair-box');
        const pairIndex = existingPairs.length;
        
        // Check if there are existing pairs and validate the last one
        if (existingPairs.length > 0) {
            const lastPair = existingPairs[existingPairs.length - 1];
            const leftText = lastPair.querySelector('input[name*="[left_item_text]"]')?.value.trim() || '';
            const rightText = lastPair.querySelector('input[name*="[right_item_text]"]')?.value.trim() || '';
            const leftImage = lastPair.querySelector('input[name*="[left_item_image]"]');
            const rightImage = lastPair.querySelector('input[name*="[right_item_image]"]');
            
            // Check if last pair has at least one field filled
            const hasLeftText = leftText.length > 0;
            const hasRightText = rightText.length > 0;
            const hasLeftImage = leftImage && leftImage.files && leftImage.files.length > 0;
            const hasRightImage = rightImage && rightImage.files && rightImage.files.length > 0;
            const hasImagePreview = lastPair.querySelector('img'); // Check for existing saved image
            
            const isFilled = hasLeftText || hasRightText || hasLeftImage || hasRightImage || hasImagePreview;
            
            if (!isFilled) {
                alert('Please fill in Pair ' + existingPairs.length + ' before adding another pair. Each pair must have at least one field (text or image) filled.');
                if (btn) btn.dataset.processing = 'false';
                return false;
            }
        }
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
                    <button type="button" class="removeMatchingPairBox px-4 py-2 bg-gradient-to-r from-pink-200 via-pink-300 to-pink-300 text-gray-800 rounded-lg font-bold shadow-md hover:from-pink-300 hover:via-pink-400 hover:to-pink-400 transform hover:scale-105 transition-all duration-200 border border-pink-200/50">&times;</button>
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
        
        if (btn) btn.dataset.processing = 'false';
        return false;
    }
    
    // Event delegation and direct attachment removed - main handler at line 1753 handles all clicks with processing flag

    // Remove matching pair box logic - Use event delegation
    document.addEventListener('click', function(e) {
        if (e.target && e.target.classList.contains('removeMatchingPairBox')) {
            const box = e.target.closest('.matching-pair-box');
            if (box) {
                box.remove();
                // Re-index remaining pairs
                const container = document.getElementById('matchingPairsBoxes');
                if (container) {
                    const remainingBoxes = container.querySelectorAll('.matching-pair-box');
                    remainingBoxes.forEach(function(box, index) {
                        const span = box.querySelector('span.font-bold');
                        if (span) {
                            span.innerHTML = '<svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" /></svg>Pair ' + (index + 1);
                        }
                        const inputs = box.querySelectorAll('input[type="text"], input[type="file"]');
                        inputs.forEach(function(input) {
                            const name = input.name;
                            const newName = name.replace(/pairs\[\d+\]/, 'pairs[' + index + ']');
                            input.name = newName;
                        });
                    });
                }
            }
        }
    });

    // Existing logic for word/definition pairs
    function handleAddPairBtn(e) {
        const btn = e.target.closest('.addPairBtn') || e.target;
        
        // Prevent multiple handlers from processing the same click
        e.stopImmediatePropagation();
        
        // Check if button is already being processed
        if (btn.dataset.processing === 'true') {
            console.log('Button already being processed in handleAddPairBtn, skipping');
            return;
        }
        
        // Mark button as processing
        btn.dataset.processing = 'true';
        
        e.preventDefault();
        e.stopPropagation();
        console.log('Add Pair button clicked');
            
            // Find the form container
            const container = btn.closest('form');
            if (!container) {
                console.error('Form container not found for addPairBtn');
                btn.dataset.processing = 'false';
                return;
            }
            
            // Find the pairs list container
            const pairsList = container.querySelector('.pairs-list');
            if (!pairsList) {
                console.error('Pairs list container not found');
                return;
            }
            
            // Find the input fields - look for inputs that are siblings of the button
            const buttonContainer = btn.parentElement; // Get the immediate parent (the div with flex classes)
            let wordInput = null;
            let defInput = null;
            
            // First, try to find inputs in the same container as the button (siblings)
            if (buttonContainer) {
                const inputs = buttonContainer.querySelectorAll('input[name="words[]"], input[name="definitions[]"]');
                inputs.forEach(input => {
                    if (!input.readOnly && input.name === 'words[]' && !wordInput) {
                        wordInput = input;
                    }
                    if (!input.readOnly && input.name === 'definitions[]' && !defInput) {
                        defInput = input;
                    }
                });
            }
            
            // Fallback: find any non-readonly inputs in the form, but exclude those in .pairs-list
            if (!wordInput || !defInput) {
                const allWordInputs = container.querySelectorAll('input[name="words[]"]');
                const allDefInputs = container.querySelectorAll('input[name="definitions[]"]');
                wordInput = Array.from(allWordInputs).find(input => {
                    return !input.readOnly && !input.closest('.pairs-list');
                });
                defInput = Array.from(allDefInputs).find(input => {
                    return !input.readOnly && !input.closest('.pairs-list');
                });
            }
            
            if (!wordInput || !defInput) {
                console.error('Word or definition input not found');
                alert('Error: Could not find input fields. Please refresh the page.');
                return;
            }
            
            // Get values and trim whitespace
            const wordValue = wordInput.value.trim();
            const defValue = defInput.value.trim();
            console.log('Values in handleAddPairBtn:', { wordValue, defValue, wordLength: wordValue.length, defLength: defValue.length });
            
            if (!wordValue || !defValue) {
                console.error('Validation failed:', { wordValue, defValue });
                alert('Please enter both word and definition before adding.');
                return;
            }
            
            // Escape HTML to prevent XSS
            const escapeHtml = function(text) {
                const div = document.createElement('div');
                div.textContent = text;
                return div.innerHTML;
            };
            
            // Create the pair div
            const pairDiv = document.createElement('div');
            pairDiv.className = 'flex flex-col md:flex-row gap-3 p-4 bg-gradient-to-r from-pink-50 to-teal-50 border-2 border-teal-200 rounded-xl hover:border-teal-300 transition-colors';
            pairDiv.innerHTML = `
                <input type='text' name='words[]' value='${escapeHtml(wordValue)}' class='flex-1 border-2 border-teal-300 rounded-lg px-4 py-2.5 text-teal-900 font-semibold bg-pink-50' required readonly> 
                <input type='text' name='definitions[]' value='${escapeHtml(defValue)}' class='flex-2 border-2 border-teal-300 rounded-lg px-4 py-2.5 text-teal-900 font-semibold bg-pink-50' required readonly> 
                <button type='button' class='removePairBtn px-4 py-2.5 bg-gradient-to-r from-pink-200 via-pink-300 to-pink-300 text-gray-800 rounded-lg font-bold shadow-md hover:from-pink-300 hover:via-pink-400 hover:to-pink-400 transform hover:scale-105 transition-all duration-200'>-</button>
            `;
            
            pairsList.appendChild(pairDiv);
            
            // Clear the input fields
            wordInput.value = '';
            defInput.value = '';
            
            // Add remove functionality
            const removeBtn = pairDiv.querySelector('.removePairBtn');
            if (removeBtn) {
                removeBtn.onclick = function() { 
                    pairDiv.remove(); 
                };
            }
            
            // Mark button as not processing after successful addition
            btn.dataset.processing = 'false';
    }
    
    // Use event delegation for dynamic elements
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('addPairBtn') || e.target.closest('.addPairBtn')) {
            handleAddPairBtn(e);
        }
    });
    
    // Also attach directly if elements exist
    document.querySelectorAll('.addPairBtn').forEach(function(btn) {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            e.stopImmediatePropagation();
            
            // Check if button is already being processed
            if (btn.dataset.processing === 'true') {
                console.log('Button already being processed in direct listener, skipping');
                return;
            }
            
            // Mark button as processing
            btn.dataset.processing = 'true';
            
            // Find the form container
            const container = btn.closest('form');
            if (!container) {
                console.error('Form container not found for addPairBtn');
                return;
            }
            
            // Find the pairs list container
            const pairsList = container.querySelector('.pairs-list');
            if (!pairsList) {
                console.error('Pairs list container not found');
                return;
            }
            
            // Find the input fields - look for inputs that are siblings of the button
            const buttonContainer = btn.parentElement; // Get the immediate parent (the div with flex classes)
            let wordInput = null;
            let defInput = null;
            
            // First, try to find inputs in the same container as the button (siblings)
            if (buttonContainer) {
                const inputs = buttonContainer.querySelectorAll('input[name="words[]"], input[name="definitions[]"]');
                inputs.forEach(input => {
                    if (!input.readOnly && input.name === 'words[]' && !wordInput) {
                        wordInput = input;
                    }
                    if (!input.readOnly && input.name === 'definitions[]' && !defInput) {
                        defInput = input;
                    }
                });
            }
            
            // Fallback: find any non-readonly inputs in the form, but exclude those in .pairs-list
            if (!wordInput || !defInput) {
                const allWordInputs = container.querySelectorAll('input[name="words[]"]');
                const allDefInputs = container.querySelectorAll('input[name="definitions[]"]');
                wordInput = Array.from(allWordInputs).find(input => {
                    return !input.readOnly && !input.closest('.pairs-list');
                });
                defInput = Array.from(allDefInputs).find(input => {
                    return !input.readOnly && !input.closest('.pairs-list');
                });
            }
            
            if (!wordInput || !defInput) {
                console.error('Word or definition input not found');
                alert('Error: Could not find input fields. Please refresh the page.');
                return;
            }
            
            // Get values and trim whitespace
            const wordValue = wordInput.value.trim();
            const defValue = defInput.value.trim();
            console.log('Values in direct listener:', { wordValue, defValue, wordLength: wordValue.length, defLength: defValue.length });
            
            if (!wordValue || !defValue) {
                console.error('Validation failed:', { wordValue, defValue });
                alert('Please enter both word and definition before adding.');
                return;
            }
            
            // Escape HTML to prevent XSS
            const escapeHtml = function(text) {
                const div = document.createElement('div');
                div.textContent = text;
                return div.innerHTML;
            };
            
            // Create the pair div
            const pairDiv = document.createElement('div');
            pairDiv.className = 'flex flex-col md:flex-row gap-3 p-4 bg-gradient-to-r from-pink-50 to-teal-50 border-2 border-teal-200 rounded-xl hover:border-teal-300 transition-colors';
            pairDiv.innerHTML = `
                <input type='text' name='words[]' value='${escapeHtml(wordValue)}' class='flex-1 border-2 border-teal-300 rounded-lg px-4 py-2.5 text-teal-900 font-semibold bg-pink-50' required readonly> 
                <input type='text' name='definitions[]' value='${escapeHtml(defValue)}' class='flex-2 border-2 border-teal-300 rounded-lg px-4 py-2.5 text-teal-900 font-semibold bg-pink-50' required readonly> 
                <button type='button' class='removePairBtn px-4 py-2.5 bg-gradient-to-r from-pink-200 via-pink-300 to-pink-300 text-gray-800 rounded-lg font-bold shadow-md hover:from-pink-300 hover:via-pink-400 hover:to-pink-400 transform hover:scale-105 transition-all duration-200'>-</button>
            `;
            
            pairsList.appendChild(pairDiv);
            
            // Clear the input fields
            wordInput.value = '';
            defInput.value = '';
            
            // Add remove functionality
            const removeBtn = pairDiv.querySelector('.removePairBtn');
            if (removeBtn) {
                removeBtn.onclick = function() { 
                    pairDiv.remove(); 
                };
            }
            
            // Mark button as not processing after successful addition
            btn.dataset.processing = 'false';
    });
    });
    
    // COMPREHENSIVE FIX: Direct event handlers for all add buttons
    // This ensures buttons work even if previous handlers failed
    setTimeout(function() {
        console.log('Initializing add button handlers...');
        
        // Word Search Add Button
        const addWordSearchBtn = document.getElementById('addWordSearchWordBox');
        if (addWordSearchBtn) {
            addWordSearchBtn.onclick = function(e) {
                e.preventDefault();
                e.stopPropagation();
                console.log('Word Search Add button clicked (direct handler)');
                const container = document.getElementById('wordSearchWordsBoxes');
                if (container) {
                    const box = document.createElement('div');
                    box.className = 'word-search-word-box flex items-center gap-3 mb-3 p-3 bg-gray-50 rounded-xl border-2 border-gray-200 hover:border-pink-300 transition-colors';
                    box.innerHTML = '<input type="text" name="word_search_words[]" class="flex-1 border-2 border-gray-300 rounded-lg px-4 py-2.5 text-gray-800 font-medium focus:border-pink-400 focus:ring-2 focus:ring-pink-200 transition-all" placeholder="Enter word" required><button type="button" class="removeWordSearchWordBox px-4 py-2.5 bg-gradient-to-r from-pink-200 via-pink-300 to-pink-300 text-gray-800 rounded-lg font-bold shadow-md hover:from-pink-300 hover:via-pink-400 hover:to-pink-400 transform hover:scale-105 transition-all duration-200" onclick="removeWordSearchWordBox(this)">&times;</button>';
                    container.appendChild(box);
                }
            };
            console.log('Word Search button handler attached');
        }
        
        // Matching Pairs Add Button - More robust handler
        function attachMatchingPairsHandler() {
            const addMatchingPairBtn = document.getElementById('addMatchingPairBox');
            if (addMatchingPairBtn) {
                // Remove any existing handlers
                addMatchingPairBtn.onclick = null;
                // Attach new handler
                addMatchingPairBtn.onclick = function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    console.log('Matching Pairs Add button clicked (direct handler)');
                    const container = document.getElementById('matchingPairsBoxes');
                    if (!container) {
                        console.error('matchingPairsBoxes container not found');
                        alert('Error: Could not find the pairs container. Please refresh the page.');
                        return;
                    }
                    const pairIndex = container.querySelectorAll('.matching-pair-box').length;
                    const box = document.createElement('div');
                    box.className = 'matching-pair-box border-2 border-teal-200 rounded-xl p-5 bg-gradient-to-r from-pink-50/50 to-teal-50/50 hover:border-teal-300 transition-colors';
                    box.innerHTML = '<div class="flex justify-between items-center mb-4"><span class="font-bold text-teal-700 text-lg flex items-center gap-2"><svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" /></svg>Pair ' + (pairIndex + 1) + '</span><button type="button" class="removeMatchingPairBox px-4 py-2 bg-gradient-to-r from-pink-200 via-pink-300 to-pink-300 text-gray-800 rounded-lg font-bold shadow-md hover:from-pink-300 hover:via-pink-400 hover:to-pink-400 transform hover:scale-105 transition-all duration-200 border border-pink-200/50">&times;</button></div><div class="grid grid-cols-1 md:grid-cols-2 gap-5"><div class="space-y-3"><label class="block text-sm font-bold text-gray-700">Left Item (Text):</label><input type="text" name="pairs[' + pairIndex + '][left_item_text]" class="w-full bg-pink-50 border-2 border-pink-200 rounded-lg px-4 py-2.5 text-gray-800 font-medium hover:bg-pink-100 focus:border-teal-400 focus:ring-2 focus:ring-teal-200 focus:bg-pink-50 transition-all" placeholder="Text for left column" dir="rtl"><label class="block text-sm font-bold text-gray-700 mt-3">Left Item (Image):</label><input type="file" name="pairs[' + pairIndex + '][left_item_image]" class="w-full border-2 border-gray-300 rounded-lg px-4 py-2.5 text-gray-800 font-medium focus:border-teal-400 focus:ring-2 focus:ring-teal-200 transition-all file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-teal-50 file:text-teal-700 hover:file:bg-teal-100" accept="image/*"></div><div class="space-y-3"><label class="block text-sm font-bold text-gray-700">Right Item (Text):</label><input type="text" name="pairs[' + pairIndex + '][right_item_text]" class="w-full bg-pink-50 border-2 border-pink-200 rounded-lg px-4 py-2.5 text-gray-800 font-medium hover:bg-pink-100 focus:border-teal-400 focus:ring-2 focus:ring-teal-200 focus:bg-pink-50 transition-all" placeholder="Text for right column" dir="rtl"><label class="block text-sm font-bold text-gray-700 mt-3">Right Item (Image):</label><input type="file" name="pairs[' + pairIndex + '][right_item_image]" class="w-full border-2 border-gray-300 rounded-lg px-4 py-2.5 text-gray-800 font-medium focus:border-teal-400 focus:ring-2 focus:ring-teal-200 transition-all file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-teal-50 file:text-teal-700 hover:file:bg-teal-100" accept="image/*"></div></div>';
                    container.appendChild(box);
                    console.log('Pair box added successfully. Total pairs: ' + (pairIndex + 1));
                };
                console.log('Matching Pairs button handler attached');
            } else {
                console.log('Matching Pairs button not found, will retry...');
            }
        }
        
        // Try to attach immediately
        attachMatchingPairsHandler();
        
        // Also use event delegation as backup
        document.addEventListener('click', function(e) {
            if (e.target && (e.target.id === 'addMatchingPairBox' || e.target.closest('#addMatchingPairBox'))) {
                e.preventDefault();
                e.stopPropagation();
                console.log('Matching Pairs Add button clicked (event delegation)');
                const container = document.getElementById('matchingPairsBoxes');
                if (!container) {
                    console.error('matchingPairsBoxes container not found');
                    return;
                }
                const pairIndex = container.querySelectorAll('.matching-pair-box').length;
                const box = document.createElement('div');
                box.className = 'matching-pair-box border-2 border-teal-200 rounded-xl p-5 bg-gradient-to-r from-pink-50/50 to-teal-50/50 hover:border-teal-300 transition-colors';
                box.innerHTML = '<div class="flex justify-between items-center mb-4"><span class="font-bold text-teal-700 text-lg flex items-center gap-2"><svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" /></svg>Pair ' + (pairIndex + 1) + '</span><button type="button" class="removeMatchingPairBox px-4 py-2 bg-gradient-to-r from-pink-200 via-pink-300 to-pink-300 text-gray-800 rounded-lg font-bold shadow-md hover:from-pink-300 hover:via-pink-400 hover:to-pink-400 transform hover:scale-105 transition-all duration-200 border border-pink-200/50">&times;</button></div><div class="grid grid-cols-1 md:grid-cols-2 gap-5"><div class="space-y-3"><label class="block text-sm font-bold text-gray-700">Left Item (Text):</label><input type="text" name="pairs[' + pairIndex + '][left_item_text]" class="w-full bg-pink-50 border-2 border-pink-200 rounded-lg px-4 py-2.5 text-gray-800 font-medium hover:bg-pink-100 focus:border-teal-400 focus:ring-2 focus:ring-teal-200 focus:bg-pink-50 transition-all" placeholder="Text for left column" dir="rtl"><label class="block text-sm font-bold text-gray-700 mt-3">Left Item (Image):</label><input type="file" name="pairs[' + pairIndex + '][left_item_image]" class="w-full border-2 border-gray-300 rounded-lg px-4 py-2.5 text-gray-800 font-medium focus:border-teal-400 focus:ring-2 focus:ring-teal-200 transition-all file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-teal-50 file:text-teal-700 hover:file:bg-teal-100" accept="image/*"></div><div class="space-y-3"><label class="block text-sm font-bold text-gray-700">Right Item (Text):</label><input type="text" name="pairs[' + pairIndex + '][right_item_text]" class="w-full bg-pink-50 border-2 border-pink-200 rounded-lg px-4 py-2.5 text-gray-800 font-medium hover:bg-pink-100 focus:border-teal-400 focus:ring-2 focus:ring-teal-200 focus:bg-pink-50 transition-all" placeholder="Text for right column" dir="rtl"><label class="block text-sm font-bold text-gray-700 mt-3">Right Item (Image):</label><input type="file" name="pairs[' + pairIndex + '][right_item_image]" class="w-full border-2 border-gray-300 rounded-lg px-4 py-2.5 text-gray-800 font-medium focus:border-teal-400 focus:ring-2 focus:ring-teal-200 transition-all file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-teal-50 file:text-teal-700 hover:file:bg-teal-100" accept="image/*"></div></div>';
                container.appendChild(box);
            }
        });
        
        // Re-attach handler when section becomes visible
        const editMatchingPairsBtn = document.getElementById('editMatchingPairsBtn');
        if (editMatchingPairsBtn) {
            const originalHandler = editMatchingPairsBtn.onclick;
            editMatchingPairsBtn.onclick = function() {
                if (originalHandler) originalHandler();
                setTimeout(attachMatchingPairsHandler, 100);
            };
        }
        
        // Clock Game Add Button
        const addClockWordBtn = document.getElementById('addWordClockArrangementWordBox');
        if (addClockWordBtn) {
            addClockWordBtn.onclick = function(e) {
                e.preventDefault();
                e.stopPropagation();
                console.log('Clock Game Add button clicked (direct handler)');
                if (typeof addWordClockArrangementWordBox === 'function') {
                    addWordClockArrangementWordBox();
                }
            };
            console.log('Clock Game button handler attached');
        }
        
        // Scrambled Letters Add Button
        document.querySelectorAll('.addPairBtn').forEach(function(btn) {
            btn.onclick = function(e) {
                e.preventDefault();
                e.stopPropagation();
                e.stopImmediatePropagation();
                console.log('Add Pair button clicked (direct handler)');
                
                // Check if button is already being processed
                if (btn.dataset.processing === 'true') {
                    console.log('Button already being processed in setTimeout handler, skipping');
                    return;
                }
                
                // Mark button as processing
                btn.dataset.processing = 'true';
                
                const form = btn.closest('form');
                if (form) {
                    const pairsList = form.querySelector('.pairs-list');
                    
                    // Find the editable input fields - look for inputs that are siblings of the button
                    const buttonContainer = btn.parentElement; // Get the immediate parent (the div with flex classes)
                    let wordInput = null;
                    let defInput = null;
                    
                    // First, try to find inputs in the same container as the button (siblings)
                    if (buttonContainer) {
                        const inputs = buttonContainer.querySelectorAll('input[name="words[]"], input[name="definitions[]"]');
                        inputs.forEach(input => {
                            if (!input.readOnly && input.name === 'words[]' && !wordInput) {
                                wordInput = input;
                            }
                            if (!input.readOnly && input.name === 'definitions[]' && !defInput) {
                                defInput = input;
                            }
                        });
                    }
                    
                    // Fallback: find any non-readonly inputs in the form, but exclude those in .pairs-list
                    if (!wordInput || !defInput) {
                        const allWordInputs = form.querySelectorAll('input[name="words[]"]');
                        const allDefInputs = form.querySelectorAll('input[name="definitions[]"]');
                        wordInput = Array.from(allWordInputs).find(input => {
                            return !input.readOnly && !input.closest('.pairs-list');
                        });
                        defInput = Array.from(allDefInputs).find(input => {
                            return !input.readOnly && !input.closest('.pairs-list');
                        });
                    }
                    
                    if (pairsList && wordInput && defInput) {
                        const wordVal = wordInput.value.trim();
                        const defVal = defInput.value.trim();
                        console.log('Values:', { wordVal, defVal, wordLength: wordVal.length, defLength: defVal.length });
                        if (wordVal && defVal) {
                            const pairDiv = document.createElement('div');
                            pairDiv.className = 'flex flex-col md:flex-row gap-3 p-4 bg-gradient-to-r from-pink-50 to-teal-50 border-2 border-teal-200 rounded-xl hover:border-teal-300 transition-colors';
                            const wordValEscaped = wordVal.replace(/'/g, "&#39;").replace(/"/g, "&quot;").replace(/</g, "&lt;").replace(/>/g, "&gt;");
                            const defValEscaped = defVal.replace(/'/g, "&#39;").replace(/"/g, "&quot;").replace(/</g, "&lt;").replace(/>/g, "&gt;");
                            pairDiv.innerHTML = '<input type="text" name="words[]" value="' + wordValEscaped + '" class="flex-1 border-2 border-teal-300 rounded-lg px-4 py-2.5 text-teal-900 font-semibold bg-pink-50" required readonly><input type="text" name="definitions[]" value="' + defValEscaped + '" class="flex-2 border-2 border-teal-300 rounded-lg px-4 py-2.5 text-teal-900 font-semibold bg-pink-50" required readonly><button type="button" class="removePairBtn px-4 py-2.5 bg-gradient-to-r from-pink-200 via-pink-300 to-pink-300 text-gray-800 rounded-lg font-bold shadow-md hover:from-pink-300 hover:via-pink-400 hover:to-pink-400 transform hover:scale-105 transition-all duration-200">-</button>';
                            pairsList.appendChild(pairDiv);
                            wordInput.value = '';
                            defInput.value = '';
                            pairDiv.querySelector('.removePairBtn').onclick = function() { pairDiv.remove(); };
                            // Mark button as not processing after successful addition
                            btn.dataset.processing = 'false';
                        } else {
                            console.error('Validation failed:', { wordVal, defVal });
                            btn.dataset.processing = 'false';
                            alert('Please enter both word and definition before adding.');
                        }
                    } else {
                        console.error('Could not find required elements:', { pairsList, wordInput, defInput });
                        btn.dataset.processing = 'false';
                        alert('Error: Could not find input fields. Please refresh the page.');
                    }
                }
            };
        });
        console.log('All add button handlers initialized');
    }, 100);
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

<!-- SIMPLE FIX: Direct button handlers -->
<script>
console.log('=== SIMPLE FIX SCRIPT LOADING ===');

// Wait for page to be fully loaded
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initButtons);
} else {
    initButtons();
}

function initButtons() {
    console.log('=== INITIALIZING BUTTONS ===');
    
    // Function to add word search word
    function addWordSearchWord() {
        console.log('Adding word search word');
        const container = document.getElementById('wordSearchWordsBoxes');
        if (!container) {
            console.error('wordSearchWordsBoxes not found');
            return;
        }
        const box = document.createElement('div');
        box.className = 'word-search-word-box flex items-center gap-3 mb-3 p-3 bg-gray-50 rounded-xl border-2 border-gray-200 hover:border-pink-300 transition-colors';
                box.innerHTML = '<input type="text" name="word_search_words[]" class="flex-1 border-2 border-gray-300 rounded-lg px-4 py-2.5 text-gray-800 font-medium focus:border-pink-400 focus:ring-2 focus:ring-pink-200 transition-all" placeholder="Enter word" required><button type="button" class="removeWordSearchWordBox px-4 py-2.5 bg-gradient-to-r from-pink-200 via-pink-300 to-pink-300 text-gray-800 rounded-lg font-bold shadow-md hover:from-pink-300 hover:via-pink-400 hover:to-pink-400 transform hover:scale-105 transition-all duration-200 border border-pink-200/50" onclick="removeWordSearchWordBox(this)">&times;</button>';
        container.appendChild(box);
    }
    
    // Function to remove matching pair and re-index
    function removeMatchingPair(btn) {
        console.log('Removing matching pair');
        const box = btn.closest('.matching-pair-box');
        if (!box) {
            console.error('Could not find matching-pair-box');
            return;
        }
        
        const container = document.getElementById('matchingPairsBoxes');
        if (container) {
            const allBoxes = container.querySelectorAll('.matching-pair-box');
            if (allBoxes.length <= 1) {
                alert('You must have at least one matching pair.');
                return;
            }
        }
        
        box.remove();
        
        // Re-index remaining pairs
        if (container) {
            const remainingBoxes = container.querySelectorAll('.matching-pair-box');
            remainingBoxes.forEach(function(box, index) {
                const span = box.querySelector('span.font-bold');
                if (span) {
                    span.innerHTML = '<svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" /></svg>Pair ' + (index + 1);
                }
                const inputs = box.querySelectorAll('input[type="text"], input[type="file"]');
                inputs.forEach(function(input) {
                    const name = input.name;
                    const newName = name.replace(/pairs\[\d+\]/, 'pairs[' + index + ']');
                    input.name = newName;
                });
            });
            console.log('Pairs re-indexed. Total: ' + remainingBoxes.length);
        }
    }
    
    // Function to add matching pair
    function addMatchingPair() {
        console.log('Adding matching pair');
        const container = document.getElementById('matchingPairsBoxes');
        if (!container) {
            console.error('matchingPairsBoxes not found');
            return;
        }
        const pairIndex = container.querySelectorAll('.matching-pair-box').length;
        const box = document.createElement('div');
        box.className = 'matching-pair-box border-2 border-teal-200 rounded-xl p-5 bg-gradient-to-r from-pink-50/50 to-teal-50/50 hover:border-teal-300 transition-colors';
        box.innerHTML = '<div class="flex justify-between items-center mb-4"><span class="font-bold text-teal-700 text-lg flex items-center gap-2"><svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" /></svg>Pair ' + (pairIndex + 1) + '</span><button type="button" class="removeMatchingPairBox px-4 py-2 bg-gradient-to-r from-pink-200 via-pink-300 to-pink-300 text-gray-800 rounded-lg font-bold shadow-md hover:from-pink-300 hover:via-pink-400 hover:to-pink-400 transform hover:scale-105 transition-all duration-200 border border-pink-200/50">&times;</button></div><div class="grid grid-cols-1 md:grid-cols-2 gap-5"><div class="space-y-3"><label class="block text-sm font-bold text-gray-700">Left Item (Text):</label><input type="text" name="pairs[' + pairIndex + '][left_item_text]" class="w-full bg-pink-50 border-2 border-pink-200 rounded-lg px-4 py-2.5 text-gray-800 font-medium hover:bg-pink-100 focus:border-teal-400 focus:ring-2 focus:ring-teal-200 focus:bg-pink-50 transition-all" placeholder="Text for left column" dir="rtl"><label class="block text-sm font-bold text-gray-700 mt-3">Left Item (Image):</label><input type="file" name="pairs[' + pairIndex + '][left_item_image]" class="w-full border-2 border-gray-300 rounded-lg px-4 py-2.5 text-gray-800 font-medium focus:border-teal-400 focus:ring-2 focus:ring-teal-200 transition-all file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-teal-50 file:text-teal-700 hover:file:bg-teal-100" accept="image/*"></div><div class="space-y-3"><label class="block text-sm font-bold text-gray-700">Right Item (Text):</label><input type="text" name="pairs[' + pairIndex + '][right_item_text]" class="w-full bg-pink-50 border-2 border-pink-200 rounded-lg px-4 py-2.5 text-gray-800 font-medium hover:bg-pink-100 focus:border-teal-400 focus:ring-2 focus:ring-teal-200 focus:bg-pink-50 transition-all" placeholder="Text for right column" dir="rtl"><label class="block text-sm font-bold text-gray-700 mt-3">Right Item (Image):</label><input type="file" name="pairs[' + pairIndex + '][right_item_image]" class="w-full border-2 border-gray-300 rounded-lg px-4 py-2.5 text-gray-800 font-medium focus:border-teal-400 focus:ring-2 focus:ring-teal-200 transition-all file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-teal-50 file:text-teal-700 hover:file:bg-teal-100" accept="image/*"></div></div>';
        // Attach event listener to the remove button
        const removeBtn = box.querySelector('.removeMatchingPairBox');
        if (removeBtn) {
            removeBtn.onclick = function() {
                if (typeof window.removeMatchingPair === 'function') {
                    window.removeMatchingPair(this);
                } else {
                    const pairBox = this.closest('.matching-pair-box');
                    if (pairBox) {
                        pairBox.remove();
                        // Re-index remaining pairs
                        const container = document.getElementById('matchingPairsBoxes');
                        if (container) {
                            const remainingBoxes = container.querySelectorAll('.matching-pair-box');
                            remainingBoxes.forEach(function(b, i) {
                                const span = b.querySelector('span.font-bold');
                                if (span) {
                                    span.innerHTML = '<svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" /></svg>Pair ' + (i + 1);
                                }
                                const inputs = b.querySelectorAll('input[type="text"], input[type="file"]');
                                inputs.forEach(function(inp) {
                                    inp.name = inp.name.replace(/pairs\[\d+\]/, 'pairs[' + i + ']');
                                });
                            });
                        }
                    }
                }
                return false;
            };
        }
        container.appendChild(box);
    }
    
    // Make removeMatchingPair globally available BEFORE creating buttons
    window.removeMatchingPair = removeMatchingPair;
    
    // Also attach handlers to existing remove buttons
    document.querySelectorAll('.removeMatchingPairBox').forEach(function(btn) {
        if (!btn.onclick || btn.onclick.toString().indexOf('removeMatchingPair') === -1) {
            btn.onclick = function(e) {
                e.preventDefault();
                e.stopPropagation();
                if (typeof window.removeMatchingPair === 'function') {
                    window.removeMatchingPair(this);
                } else {
                    this.closest('.matching-pair-box').remove();
                }
                return false;
            };
        }
    });
    
    // Function to add scrambled pair
    function addScrambledPair(btn, e) {
        // Prevent multiple handlers from processing the same click
        if (e) {
            e.stopImmediatePropagation();
        }
        
        // Check if button is already being processed
        if (btn.dataset.processing === 'true') {
            console.log('Button already being processed, skipping');
            return;
        }
        
        // Mark button as processing
        btn.dataset.processing = 'true';
        
        console.log('Adding scrambled pair');
        const form = btn.closest('form');
        if (!form) {
            console.error('Form not found');
            btn.dataset.processing = 'false';
            return;
        }
        const pairsList = form.querySelector('.pairs-list');
        
        // Find the editable input fields - look for inputs that are siblings of the button or in the same immediate parent
        // The button is in a div with class "flex flex-col md:flex-row gap-3", so we look for inputs in that same div
        const buttonContainer = btn.parentElement; // Get the immediate parent (the div with flex classes)
        let wordInput = null;
        let defInput = null;
        
        // First, try to find inputs in the same container as the button (siblings)
        if (buttonContainer) {
            const inputs = buttonContainer.querySelectorAll('input[name="words[]"], input[name="definitions[]"]');
            inputs.forEach(input => {
                if (!input.readOnly && input.name === 'words[]' && !wordInput) {
                    wordInput = input;
                }
                if (!input.readOnly && input.name === 'definitions[]' && !defInput) {
                    defInput = input;
                }
            });
        }
        
        // Fallback: find any non-readonly inputs in the form, but exclude those in .pairs-list
        if (!wordInput || !defInput) {
            const allWordInputs = form.querySelectorAll('input[name="words[]"]');
            const allDefInputs = form.querySelectorAll('input[name="definitions[]"]');
            wordInput = Array.from(allWordInputs).find(input => {
                return !input.readOnly && !input.closest('.pairs-list');
            });
            defInput = Array.from(allDefInputs).find(input => {
                return !input.readOnly && !input.closest('.pairs-list');
            });
        }
        
        console.log('Found inputs:', { wordInput, defInput, wordValue: wordInput?.value, defValue: defInput?.value });
        
        if (!pairsList || !wordInput || !defInput) {
            console.error('Could not find required elements:', { pairsList, wordInput, defInput });
            btn.dataset.processing = 'false';
            alert('Error: Could not find input fields. Please refresh the page.');
            return;
        }
        
        const wordVal = wordInput.value.trim();
        const defVal = defInput.value.trim();
        console.log('Trimmed values:', { wordVal, defVal, wordLength: wordVal.length, defLength: defVal.length });
        
        if (!wordVal || !defVal) {
            console.error('Validation failed - empty values:', { wordVal, defVal });
            btn.dataset.processing = 'false';
            alert('Please enter both word and definition before adding.');
            return;
        }
        const pairDiv = document.createElement('div');
        pairDiv.className = 'flex flex-col md:flex-row gap-3 p-4 bg-gradient-to-r from-pink-50 to-teal-50 border-2 border-teal-200 rounded-xl hover:border-teal-300 transition-colors';
        const escapedWord = wordVal.replace(/'/g, "&#39;").replace(/"/g, "&quot;").replace(/</g, "&lt;").replace(/>/g, "&gt;");
        const escapedDef = defVal.replace(/'/g, "&#39;").replace(/"/g, "&quot;").replace(/</g, "&lt;").replace(/>/g, "&gt;");
        pairDiv.innerHTML = '<input type="text" name="words[]" value="' + escapedWord + '" class="flex-1 border-2 border-teal-300 rounded-lg px-4 py-2.5 text-teal-900 font-semibold bg-pink-50" required readonly><input type="text" name="definitions[]" value="' + escapedDef + '" class="flex-2 border-2 border-teal-300 rounded-lg px-4 py-2.5 text-teal-900 font-semibold bg-pink-50" required readonly><button type="button" class="removePairBtn px-4 py-2.5 bg-gradient-to-r from-pink-200 via-pink-300 to-pink-300 text-gray-800 rounded-lg font-bold shadow-md hover:from-pink-300 hover:via-pink-400 hover:to-pink-400 transform hover:scale-105 transition-all duration-200" onclick="this.closest(\'div\').remove()">-</button>';
        pairsList.appendChild(pairDiv);
        wordInput.value = '';
        defInput.value = '';
        
        // Clear processing flag after successful addition
        btn.dataset.processing = 'false';
    }
    
    // Show/hide game sections based on selected game type
    function updateGameSections() {
        // Get the checked radio button value
        const checkedRadio = document.querySelector('input[name="game_type"]:checked');
        const selectedGameType = checkedRadio ? checkedRadio.value : '';
        const gameSections = document.querySelectorAll('.game-section');
        
        gameSections.forEach(section => {
            const sectionType = section.getAttribute('data-game-type');
            if (selectedGameType && sectionType === selectedGameType) {
                section.style.display = 'block';
            } else {
                section.style.display = 'none';
            }
        });
    }
    
    // Initialize on page load
    document.addEventListener('DOMContentLoaded', function() {
        updateGameSections();
        
        // Update when game type radio buttons change
        const gameTypeRadios = document.querySelectorAll('input[name="game_type"]');
        gameTypeRadios.forEach(radio => {
            radio.addEventListener('change', function() {
                updateGameSections();
            });
        });
    });
    
    // Attach handlers
    setTimeout(function() {
        const btn1 = document.getElementById('addWordSearchWordBox');
        if (btn1) {
            btn1.onclick = function(e) { e.preventDefault(); console.log('Word Search clicked'); addWordSearchWord(); return false; };
            console.log('Word Search button handler attached');
        }
        
        // Removed duplicate handler - using main handler with processing flag
        // const btn2 = document.getElementById('addMatchingPairBox');
        // if (btn2) {
        //     btn2.onclick = function(e) { e.preventDefault(); console.log('Matching Pairs clicked'); addMatchingPair(); return false; };
        //     console.log('Matching Pairs button handler attached');
        // }
        
        const btn3 = document.getElementById('addWordClockArrangementWordBox');
        if (btn3) {
            btn3.onclick = function(e) { e.preventDefault(); console.log('Clock Game clicked'); if (typeof window.addWordClockArrangementWordBox === 'function') window.addWordClockArrangementWordBox(); return false; };
            console.log('Clock Game button handler attached');
        }
        
        document.querySelectorAll('.addPairBtn').forEach(function(btn) {
            btn.onclick = function(e) { e.preventDefault(); console.log('Add Pair clicked'); addScrambledPair(this, e); return false; };
        });
        console.log('Add Pair buttons: ' + document.querySelectorAll('.addPairBtn').length);
        
        // Edit Matching Pairs Button
        const editMatchingPairsBtn = document.getElementById('editMatchingPairsBtn');
        if (editMatchingPairsBtn) {
            editMatchingPairsBtn.onclick = function(e) {
                e.preventDefault();
                e.stopPropagation();
                console.log('Edit Matching Pairs button clicked');
                const savedView = document.getElementById('matchingPairsSavedView');
                const section = document.getElementById('matchingPairsSection');
                if (savedView) {
                    savedView.style.display = 'none';
                    console.log('Saved view hidden');
                }
                if (section) {
                    section.classList.remove('hidden');
                    console.log('Matching pairs section shown');
                }
                return false;
            };
            console.log('Edit Matching Pairs button handler attached');
        } else {
            console.log('Edit Matching Pairs button not found');
        }
        
        // Add event delegation for remove buttons (works for dynamically added buttons)
        document.addEventListener('click', function(e) {
            if (e.target && (e.target.classList.contains('removeMatchingPairBox') || e.target.closest('.removeMatchingPairBox'))) {
                e.preventDefault();
                e.stopPropagation();
                console.log('Remove Matching Pair clicked (event delegation)');
                const btn = e.target.classList.contains('removeMatchingPairBox') ? e.target : e.target.closest('.removeMatchingPairBox');
                if (btn && typeof window.removeMatchingPair === 'function') {
                    window.removeMatchingPair(btn);
                } else {
                    // Fallback
                    const box = e.target.closest('.matching-pair-box');
                    if (box) {
                        box.remove();
                        console.log('Pair removed (fallback)');
                    }
                }
                return false;
            }
        });
        
        console.log('=== ALL HANDLERS ATTACHED ===');
    }, 1000);
}

// Clear All Functions for each game
function clearAllWordSearch() {
    if (confirm('Are you sure you want to clear all word search words? This action cannot be undone.')) {
        const container = document.getElementById('wordSearchWordsBoxes');
        if (container) {
            const boxes = container.querySelectorAll('.word-search-word-box');
            // Keep only the first box and clear its value
            boxes.forEach((box, index) => {
                if (index === 0) {
                    // Clear the first box's input
                    const input = box.querySelector('input[type="text"]');
                    if (input) {
                        input.value = '';
                    }
                } else {
                    // Remove all other boxes
                    box.remove();
                }
            });
            // If no boxes exist, create one empty box
            if (boxes.length === 0) {
                const newBox = document.createElement('div');
                newBox.className = 'word-search-word-box flex items-center gap-3 mb-3 p-3 bg-pink-50 rounded-xl border-2 border-pink-200 hover:border-pink-300 transition-colors';
                newBox.innerHTML = '<input type="text" name="word_search_words[]" class="flex-1 bg-pink-50 border-2 border-pink-200 rounded-lg px-4 py-2.5 text-gray-800 font-medium hover:bg-pink-100 focus:border-pink-400 focus:ring-2 focus:ring-pink-200 focus:bg-pink-50 transition-all" placeholder="Enter word" required><button type="button" class="removeWordSearchWordBox px-4 py-2.5 bg-gradient-to-r from-pink-200 via-pink-300 to-pink-300 text-gray-800 rounded-lg font-bold shadow-md hover:from-pink-300 hover:via-pink-400 hover:to-pink-400 transform hover:scale-105 transition-all duration-200 border border-pink-200/50" onclick="removeWordSearchWordBox(this)">&times;</button>';
                container.appendChild(newBox);
            }
        }
        // Also clear the title input if it exists
        const titleInput = document.getElementById('word_search_title');
        if (titleInput) {
            titleInput.value = '';
        }
        // Reset grid size to default
        const gridSizeSelect = document.getElementById('grid_size');
        if (gridSizeSelect) {
            gridSizeSelect.value = '10';
        }
    }
}

function clearAllWordClock() {
    if (confirm('Are you sure you want to clear all word clock arrangement words? This action cannot be undone.')) {
        const container = document.getElementById('wordClockArrangementWordsBoxes');
        if (container) {
            container.innerHTML = '';
        }
        // Also clear the sentence input if it exists
        const sentenceInput = document.getElementById('word_clock_sentence');
        if (sentenceInput) {
            sentenceInput.value = '';
        }
    }
}

function clearAllMatchingPairs() {
    if (confirm('Are you sure you want to clear all matching pairs? This action cannot be undone.')) {
        const container = document.getElementById('matchingPairsBoxes');
        if (container) {
            container.innerHTML = '';
        }
        // Also clear the title input if it exists
        const titleInput = document.getElementById('matching_pairs_title');
        if (titleInput) {
            titleInput.value = '';
        }
    }
}

function clearAllScrambledPairs() {
    if (confirm('Are you sure you want to clear all scrambled letter pairs? This action cannot be undone.')) {
        // Clear the pairs list
        const form = document.querySelector('form[action*="teacher.games.store"]');
        if (form) {
            const pairsList = form.querySelector('.pairs-list');
            if (pairsList) {
                pairsList.innerHTML = '';
            }
            // Clear the input fields
            const wordInput = form.querySelector('input[name="words[]"]:not([readonly])');
            const defInput = form.querySelector('input[name="definitions[]"]:not([readonly])');
            if (wordInput) wordInput.value = '';
            if (defInput) defInput.value = '';
        }
    }
}

// Attach event listeners for Clear All buttons
document.addEventListener('DOMContentLoaded', function() {
    // Word Search Clear All
    const clearWordSearchBtn = document.getElementById('clearAllWordSearchBtn');
    if (clearWordSearchBtn) {
        clearWordSearchBtn.addEventListener('click', clearAllWordSearch);
    }
    
    // Word Clock Clear All
    const clearWordClockBtn = document.getElementById('clearAllWordClockBtn');
    if (clearWordClockBtn) {
        clearWordClockBtn.addEventListener('click', clearAllWordClock);
    }
    
    // Matching Pairs Clear All
    const clearMatchingPairsBtn = document.getElementById('clearAllMatchingPairsBtn');
    if (clearMatchingPairsBtn) {
        clearMatchingPairsBtn.addEventListener('click', clearAllMatchingPairs);
    }
    
    // Scrambled Letters Clear All
    const clearScrambledPairsBtn = document.getElementById('clearAllScrambledPairsBtn');
    if (clearScrambledPairsBtn) {
        clearScrambledPairsBtn.addEventListener('click', clearAllScrambledPairs);
    }
});

// Also attach in setTimeout for dynamic elements
setTimeout(function() {
    const clearWordSearchBtn = document.getElementById('clearAllWordSearchBtn');
    if (clearWordSearchBtn && !clearWordSearchBtn.onclick) {
        clearWordSearchBtn.onclick = clearAllWordSearch;
    }
    
    const clearWordClockBtn = document.getElementById('clearAllWordClockBtn');
    if (clearWordClockBtn && !clearWordClockBtn.onclick) {
        clearWordClockBtn.onclick = clearAllWordClock;
    }
    
    const clearMatchingPairsBtn = document.getElementById('clearAllMatchingPairsBtn');
    if (clearMatchingPairsBtn && !clearMatchingPairsBtn.onclick) {
        clearMatchingPairsBtn.onclick = clearAllMatchingPairs;
    }
    
    const clearScrambledPairsBtn = document.getElementById('clearAllScrambledPairsBtn');
    if (clearScrambledPairsBtn && !clearScrambledPairsBtn.onclick) {
        clearScrambledPairsBtn.onclick = clearAllScrambledPairs;
    }
}, 100);

// View Game Preview Functions
function viewWordSearchGame() {
    const modal = document.getElementById('wordSearchPreviewModal');
    const content = document.getElementById('wordSearchPreviewContent');
    
    // Get form data
    const title = document.getElementById('word_search_title')?.value || 'Untitled Word Search';
    const gridSize = document.getElementById('grid_size')?.value || '10';
    const wordBoxes = document.querySelectorAll('.word-search-word-box input[type="text"]');
    const words = Array.from(wordBoxes).map(input => input.value.trim()).filter(word => word);
    
    if (words.length === 0) {
        alert('Please add at least one word before viewing the preview.');
        return;
    }
    
    // Generate preview HTML
    let previewHTML = `
        <div class="mb-6">
            <h4 class="text-xl font-bold text-gray-800 mb-2">${title}</h4>
            <p class="text-gray-600 mb-4">Grid Size: ${gridSize}x${gridSize}</p>
        </div>
        <div class="mb-6">
            <h5 class="text-lg font-semibold text-gray-700 mb-3">Words to Find:</h5>
            <div class="flex flex-wrap gap-2">
    `;
    
    words.forEach(word => {
        previewHTML += `<span class="px-4 py-2 bg-pink-100 text-pink-800 rounded-lg font-semibold border-2 border-pink-300">${word}</span>`;
    });
    
    previewHTML += `
            </div>
        </div>
        <div class="bg-gray-50 p-6 rounded-xl border-2 border-gray-200">
            <p class="text-gray-600 text-center italic">Word Search Grid Preview</p>
            <p class="text-sm text-gray-500 text-center mt-2">(The actual grid will be generated when students play the game)</p>
        </div>
        <div class="mt-6 p-4 bg-blue-50 border-l-4 border-blue-400 rounded">
            <p class="text-blue-800"><strong>Note:</strong> This is a preview. The actual game will show a ${gridSize}x${gridSize} grid with words hidden in various directions.</p>
        </div>
    `;
    
    content.innerHTML = previewHTML;
    modal.style.display = 'flex';
}

function viewWordClockGame() {
    const modal = document.getElementById('wordClockPreviewModal');
    const content = document.getElementById('wordClockPreviewContent');
    
    // Get form data
    const sentence = document.getElementById('word_clock_sentence')?.value || '';
    const wordBoxes = document.querySelectorAll('#wordClockArrangementWordsBoxes > div');
    const words = [];
    
    wordBoxes.forEach(box => {
        const wordInput = box.querySelector('input[name*="[word]"]');
        const hourInput = box.querySelector('input[name*="[hour]"]');
        const minuteInput = box.querySelector('input[name*="[minute]"]');
        if (wordInput && wordInput.value.trim()) {
            words.push({
                word: wordInput.value.trim(),
                hour: hourInput?.value || '0',
                minute: minuteInput?.value || '0'
            });
        }
    });
    
    if (words.length === 0) {
        alert('Please add at least one word before viewing the preview.');
        return;
    }
    
    // Generate preview HTML
    let previewHTML = `
        <div class="mb-6">
            <h4 class="text-xl font-bold text-gray-800 mb-2">Word Clock Arrangement Game</h4>
            ${sentence ? `<p class="text-gray-600 mb-4">${sentence}</p>` : ''}
        </div>
        <div class="mb-6">
            <h5 class="text-lg font-semibold text-gray-700 mb-3">Words and Times:</h5>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
    `;
    
    words.forEach(item => {
        previewHTML += `
            <div class="p-4 bg-cyan-50 rounded-xl border-2 border-cyan-200">
                <div class="flex items-center justify-between">
                    <span class="text-lg font-bold text-cyan-800">${item.word}</span>
                    <span class="text-cyan-600 font-semibold">${String(item.hour).padStart(2, '0')}:${String(item.minute).padStart(2, '0')}</span>
                </div>
            </div>
        `;
    });
    
    previewHTML += `
            </div>
        </div>
        <div class="mt-6 p-4 bg-blue-50 border-l-4 border-blue-400 rounded">
            <p class="text-blue-800"><strong>Note:</strong> Students will arrange words on a clock face according to the specified times.</p>
        </div>
    `;
    
    content.innerHTML = previewHTML;
    modal.style.display = 'flex';
}

function viewWordClockArrangementSaved() {
    const modal = document.getElementById('wordClockPreviewModal');
    const content = document.getElementById('wordClockPreviewContent');
    const savedView = document.getElementById('wordClockArrangementSavedView');
    
    if (!savedView) {
        alert('Saved game data not found.');
        return;
    }
    
    // Extract data from saved view
    const wordElement = savedView.querySelector('span.text-green-900.font-semibold.text-lg');
    const sentenceElement = savedView.querySelector('span.text-green-900.font-medium');
    const wordSpans = savedView.querySelectorAll('span.px-4.py-2.bg-green-200');
    
    const word = wordElement?.textContent.trim() || '';
    const sentence = sentenceElement?.textContent.trim() || '';
    const words = [];
    
    wordSpans.forEach(span => {
        const text = span.textContent.trim();
        // Parse format: "word (HH:MM)"
        const match = text.match(/^(.+?)\s*\((\d{2}):(\d{2})\)$/);
        if (match) {
            words.push({
                word: match[1].trim(),
                hour: match[2],
                minute: match[3]
            });
        }
    });
    
    if (words.length === 0) {
        alert('No words found in saved game.');
        return;
    }
    
    // Generate preview HTML
    let previewHTML = `
        <div class="mb-6">
            <h4 class="text-xl font-bold text-gray-800 mb-2">Word Clock Arrangement Game</h4>
            ${word ? `<p class="text-gray-700 mb-2"><strong>Word:</strong> ${word}</p>` : ''}
            ${sentence ? `<p class="text-gray-600 mb-4">${sentence}</p>` : ''}
        </div>
        <div class="mb-6">
            <h5 class="text-lg font-semibold text-gray-700 mb-3">Words and Times:</h5>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
    `;
    
    words.forEach(item => {
        previewHTML += `
            <div class="p-4 bg-cyan-50 rounded-xl border-2 border-cyan-200">
                <div class="flex items-center justify-between">
                    <span class="text-lg font-bold text-cyan-800">${item.word}</span>
                    <span class="text-cyan-600 font-semibold">${item.hour}:${item.minute}</span>
                </div>
            </div>
        `;
    });
    
    previewHTML += `
            </div>
        </div>
        <div class="mt-6 p-4 bg-blue-50 border-l-4 border-blue-400 rounded">
            <p class="text-blue-800"><strong>Note:</strong> Students will arrange words on a clock face according to the specified times.</p>
        </div>
    `;
    
    content.innerHTML = previewHTML;
    modal.style.display = 'flex';
}

function viewMatchingPairsGame() {
    const modal = document.getElementById('matchingPairsPreviewModal');
    const content = document.getElementById('matchingPairsPreviewContent');
    
    // Get form data
    const title = document.getElementById('matching_pairs_title')?.value || 'Untitled Matching Pairs';
    const pairBoxes = document.querySelectorAll('.matching-pair-box');
    const pairs = [];
    
    pairBoxes.forEach(box => {
        const leftText = box.querySelector('input[name*="[left_item_text]"]')?.value || '';
        const rightText = box.querySelector('input[name*="[right_item_text]"]')?.value || '';
        const leftImage = box.querySelector('input[name*="[left_item_image]"]');
        const rightImage = box.querySelector('input[name*="[right_item_image]"]');
        
        if (leftText || rightText || (leftImage && leftImage.files.length > 0) || (rightImage && rightImage.files.length > 0)) {
            pairs.push({
                leftText: leftText,
                rightText: rightText,
                leftImage: leftImage?.files[0] ? URL.createObjectURL(leftImage.files[0]) : null,
                rightImage: rightImage?.files[0] ? URL.createObjectURL(rightImage.files[0]) : null
            });
        }
    });
    
    if (pairs.length === 0) {
        alert('Please add at least one matching pair before viewing the preview.');
        return;
    }
    
    // Generate preview HTML
    let previewHTML = `
        <div class="mb-6">
            <h4 class="text-xl font-bold text-gray-800 mb-2">${title}</h4>
        </div>
        <div class="mb-6">
            <h5 class="text-lg font-semibold text-gray-700 mb-3">Matching Pairs:</h5>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
    `;
    
    pairs.forEach((pair, index) => {
        previewHTML += `
            <div class="p-4 bg-teal-50 rounded-xl border-2 border-teal-200">
                <div class="text-center font-semibold text-teal-700 mb-3">Pair ${index + 1}</div>
                <div class="grid grid-cols-2 gap-3">
                    <div class="text-center">
                        <p class="text-sm font-bold text-gray-700 mb-2">Left Item</p>
                        ${pair.leftImage ? `<img src="${pair.leftImage}" alt="Left" class="w-24 h-24 object-cover rounded-lg mx-auto border-2 border-gray-300">` : ''}
                        ${pair.leftText ? `<p class="mt-2 text-gray-800 font-medium">${pair.leftText}</p>` : ''}
                    </div>
                    <div class="text-center">
                        <p class="text-sm font-bold text-gray-700 mb-2">Right Item</p>
                        ${pair.rightImage ? `<img src="${pair.rightImage}" alt="Right" class="w-24 h-24 object-cover rounded-lg mx-auto border-2 border-gray-300">` : ''}
                        ${pair.rightText ? `<p class="mt-2 text-gray-800 font-medium">${pair.rightText}</p>` : ''}
                    </div>
                </div>
            </div>
        `;
    });
    
    previewHTML += `
            </div>
        </div>
        <div class="mt-6 p-4 bg-blue-50 border-l-4 border-blue-400 rounded">
            <p class="text-blue-800"><strong>Note:</strong> Students will match items from the left column with items from the right column.</p>
        </div>
    `;
    
    content.innerHTML = previewHTML;
    modal.style.display = 'flex';
}

function viewScrambledLettersGame() {
    const modal = document.getElementById('scrambledLettersPreviewModal');
    const content = document.getElementById('scrambledLettersPreviewContent');
    
    // Get form data
    const pairsList = document.querySelector('.pairs-list');
    const pairDivs = pairsList ? pairsList.querySelectorAll('div') : [];
    const pairs = [];
    
    pairDivs.forEach(div => {
        const wordInput = div.querySelector('input[name="words[]"]');
        const defInput = div.querySelector('input[name="definitions[]"]');
        if (wordInput && defInput && wordInput.value && defInput.value) {
            pairs.push({
                word: wordInput.value,
                definition: defInput.value
            });
        }
    });
    
    if (pairs.length === 0) {
        alert('Please add at least one word/definition pair before viewing the preview.');
        return;
    }
    
    // Generate preview HTML
    let previewHTML = `
        <div class="mb-6">
            <h4 class="text-xl font-bold text-gray-800 mb-2">Scrambled Letters Game</h4>
        </div>
        <div class="mb-6">
            <h5 class="text-lg font-semibold text-gray-700 mb-3">Word/Definition Pairs:</h5>
            <div class="space-y-4">
    `;
    
    pairs.forEach((pair, index) => {
        // Scramble the word for preview
        const scrambled = pair.word.split('').sort(() => Math.random() - 0.5).join('');
        previewHTML += `
            <div class="p-4 bg-pink-50 rounded-xl border-2 border-pink-200">
                <div class="mb-2">
                    <span class="text-sm font-semibold text-gray-600">Pair ${index + 1}:</span>
                </div>
                <div class="mb-3">
                    <p class="text-lg font-bold text-pink-800 mb-1">Scrambled Word:</p>
                    <p class="text-2xl font-mono text-pink-600 tracking-wider">${scrambled}</p>
                </div>
                <div>
                    <p class="text-lg font-bold text-gray-700 mb-1">Definition:</p>
                    <p class="text-gray-800">${pair.definition}</p>
                </div>
                <div class="mt-3 pt-3 border-t border-pink-200">
                    <p class="text-sm text-gray-600"><strong>Answer:</strong> <span class="text-teal-700 font-semibold">${pair.word}</span></p>
                </div>
            </div>
        `;
    });
    
    previewHTML += `
            </div>
        </div>
        <div class="mt-6 p-4 bg-blue-50 border-l-4 border-blue-400 rounded">
            <p class="text-blue-800"><strong>Note:</strong> Students will see scrambled words and need to unscramble them based on the definitions.</p>
        </div>
    `;
    
    content.innerHTML = previewHTML;
    modal.style.display = 'flex';
}

// Attach event listeners for View buttons
document.addEventListener('DOMContentLoaded', function() {
    // Word Search View
    const viewWordSearchBtn = document.getElementById('viewWordSearchBtn');
    if (viewWordSearchBtn) {
        viewWordSearchBtn.addEventListener('click', viewWordSearchGame);
    }
    
    // Word Clock View
    const viewWordClockBtn = document.getElementById('viewWordClockBtn');
    if (viewWordClockBtn) {
        viewWordClockBtn.addEventListener('click', viewWordClockGame);
    }
    
    // Word Clock Arrangement Saved View
    const viewWordClockArrangementSavedBtn = document.getElementById('viewWordClockArrangementSavedBtn');
    if (viewWordClockArrangementSavedBtn) {
        viewWordClockArrangementSavedBtn.addEventListener('click', viewWordClockArrangementSaved);
    }
    
    // Matching Pairs View
    const viewMatchingPairsBtn = document.getElementById('viewMatchingPairsBtn');
    if (viewMatchingPairsBtn) {
        viewMatchingPairsBtn.addEventListener('click', viewMatchingPairsGame);
    }
    
    // Scrambled Letters View
    const viewScrambledLettersBtn = document.getElementById('viewScrambledLettersBtn');
    if (viewScrambledLettersBtn) {
        viewScrambledLettersBtn.addEventListener('click', viewScrambledLettersGame);
    }
    
    // Close modal buttons
    const closeWordSearchPreview = document.getElementById('closeWordSearchPreview');
    if (closeWordSearchPreview) {
        closeWordSearchPreview.addEventListener('click', function() {
            document.getElementById('wordSearchPreviewModal').style.display = 'none';
        });
    }
    
    const closeWordClockPreview = document.getElementById('closeWordClockPreview');
    if (closeWordClockPreview) {
        closeWordClockPreview.addEventListener('click', function() {
            document.getElementById('wordClockPreviewModal').style.display = 'none';
        });
    }
    
    const closeMatchingPairsPreview = document.getElementById('closeMatchingPairsPreview');
    if (closeMatchingPairsPreview) {
        closeMatchingPairsPreview.addEventListener('click', function() {
            document.getElementById('matchingPairsPreviewModal').style.display = 'none';
        });
    }
    
    const closeScrambledLettersPreview = document.getElementById('closeScrambledLettersPreview');
    if (closeScrambledLettersPreview) {
        closeScrambledLettersPreview.addEventListener('click', function() {
            document.getElementById('scrambledLettersPreviewModal').style.display = 'none';
        });
    }
    
    // Close modals when clicking outside
    document.getElementById('wordSearchPreviewModal')?.addEventListener('click', function(e) {
        if (e.target === this) {
            this.style.display = 'none';
        }
    });
    
    document.getElementById('wordClockPreviewModal')?.addEventListener('click', function(e) {
        if (e.target === this) {
            this.style.display = 'none';
        }
    });
    
    document.getElementById('matchingPairsPreviewModal')?.addEventListener('click', function(e) {
        if (e.target === this) {
            this.style.display = 'none';
        }
    });
    
    document.getElementById('scrambledLettersPreviewModal')?.addEventListener('click', function(e) {
        if (e.target === this) {
            this.style.display = 'none';
        }
    });
});

// Also attach in setTimeout for dynamic elements
setTimeout(function() {
    const viewWordSearchBtn = document.getElementById('viewWordSearchBtn');
    if (viewWordSearchBtn && !viewWordSearchBtn.onclick) {
        viewWordSearchBtn.onclick = viewWordSearchGame;
    }
    
    const viewWordClockBtn = document.getElementById('viewWordClockBtn');
    if (viewWordClockBtn && !viewWordClockBtn.onclick) {
        viewWordClockBtn.onclick = viewWordClockGame;
    }
    
    const viewWordClockArrangementSavedBtn = document.getElementById('viewWordClockArrangementSavedBtn');
    if (viewWordClockArrangementSavedBtn && !viewWordClockArrangementSavedBtn.onclick) {
        viewWordClockArrangementSavedBtn.onclick = viewWordClockArrangementSaved;
    }
    
    const viewMatchingPairsBtn = document.getElementById('viewMatchingPairsBtn');
    if (viewMatchingPairsBtn && !viewMatchingPairsBtn.onclick) {
        viewMatchingPairsBtn.onclick = viewMatchingPairsGame;
    }
    
    const viewScrambledLettersBtn = document.getElementById('viewScrambledLettersBtn');
    if (viewScrambledLettersBtn && !viewScrambledLettersBtn.onclick) {
        viewScrambledLettersBtn.onclick = viewScrambledLettersGame;
    }
}, 100);
</script>

@endsection
