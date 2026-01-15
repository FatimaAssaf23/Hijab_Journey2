@extends('layouts.app')

@section('content')


<div class="container mx-auto py-8">
    <h2 class="text-2xl font-bold mb-6">Word/Definition Pairs</h2>
    
    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif
    
    @if(session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
            {{ session('error') }}
        </div>
    @endif
    
    @if($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif



    <form method="GET" action="">
        <div class="flex flex-col md:flex-row gap-4 mb-4 items-end">
            <div>
                <label for="lesson_id" class="block font-semibold mb-1">Select Lesson:</label>
                <select name="lesson_id" id="lesson_id" class="form-select border rounded px-3 py-2 w-full" onchange="this.form.submit()">
                    <option value="">-- Choose Lesson --</option>
                    @foreach($lessons ?? [] as $lesson)
                        <option value="{{ $lesson->lesson_id }}" {{ (isset($selectedLessonId) && $selectedLessonId == $lesson->lesson_id) ? 'selected' : '' }}>{{ $lesson->title }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </form>

    @if(isset($selectedLessonId) && $selectedLessonId)
    <!-- Word Search Game Section -->
    <div class="border rounded p-6 mb-8 bg-gray-50 max-w-2xl">
        <h3 class="text-xl font-bold mb-4">Word Search Puzzle</h3>
        @php
            $wordSearchData = null;
            if (isset($wordSearchGame) && $wordSearchGame) {
                $wordSearchData = [
                    'title' => $wordSearchGame->title ?? '',
                    'words' => is_array($wordSearchGame->words) ? $wordSearchGame->words : [],
                    'grid_size' => $wordSearchGame->grid_size ?? 10
                ];
            }
        @endphp
        @if($wordSearchData && !empty($wordSearchData['words']))
            <div id="wordSearchSavedView" class="mb-4 p-4 bg-green-50 border rounded">
                <h4 class="font-bold mb-2 text-green-700">Saved Word Search Game:</h4>
                @if(!empty($wordSearchData['title']))
                    <div class="mb-3">
                        <strong class="text-green-800">Title:</strong> 
                        <span class="text-green-900 font-semibold text-lg" dir="rtl">{{ $wordSearchData['title'] }}</span>
                    </div>
                @endif
                <div class="flex flex-wrap gap-2 mb-2">
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
                        <span class="px-3 py-1 bg-green-200 rounded text-green-900" dir="rtl">{{ $cleanWord }}</span>
                    @endforeach
                </div>
                <div class="text-sm text-gray-600 mb-2">Grid Size: {{ $wordSearchData['grid_size'] }}x{{ $wordSearchData['grid_size'] }}</div>
                <button type="button" id="editWordSearchBtn" class="px-4 py-2 rounded bg-yellow-400 text-black">Edit</button>
            </div>
        @endif
        <div id="wordSearchSection" class="{{ (isset($wordSearchData) && !empty($wordSearchData['words'])) ? 'hidden' : '' }}">
            <form id="wordSearchForm" method="POST" action="{{ route('teacher.games.word-search.store') }}">
                @csrf
                <input type="hidden" name="word_search_lesson_id" value="{{ $selectedLessonId }}">
                
                <div class="mb-4">
                    <label for="word_search_title" class="block font-semibold mb-1" dir="rtl">عنوان اللعبة / Title:</label>
                    <input type="text" 
                           name="word_search_title" 
                           id="word_search_title" 
                           class="form-input border rounded px-3 py-2 w-96 max-w-full" 
                           value="{{ $wordSearchData['title'] ?? '' }}" 
                           placeholder="أدخل العنوان" 
                           dir="rtl">
                    <p class="text-sm text-gray-600 mt-1" dir="rtl">(اختياري)</p>
                </div>
                
                <div class="mb-4">
                    <label for="grid_size" class="block font-semibold mb-1">Grid Size:</label>
                    <select name="grid_size" id="grid_size" class="form-select border rounded px-3 py-2 w-48">
                        <option value="8" {{ (isset($wordSearchData) && $wordSearchData['grid_size'] == 8) ? 'selected' : '' }}>8x8</option>
                        <option value="10" {{ (isset($wordSearchData) && $wordSearchData['grid_size'] == 10) ? 'selected' : 'selected' }}>10x10</option>
                        <option value="12" {{ (isset($wordSearchData) && $wordSearchData['grid_size'] == 12) ? 'selected' : '' }}>12x12</option>
                        <option value="15" {{ (isset($wordSearchData) && $wordSearchData['grid_size'] == 15) ? 'selected' : '' }}>15x15</option>
                    </select>
                    <p class="text-sm text-gray-600 mt-1">Choose the size of the word search grid.</p>
                </div>
                
                <div class="mb-4">
                    <label class="block font-semibold mb-2">Words to Find:</label>
                    <p class="text-sm text-gray-600 mb-2">Add words that students will search for in the puzzle.</p>
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
                                <div class="word-search-word-box flex items-center gap-2 mb-2">
                                    <input type="text" name="word_search_words[]" 
                                           class="form-input border rounded px-3 py-2 flex-1" 
                                           value="{{ $cleanWord }}" 
                                           placeholder="Word" required>
                                    <button type="button" class="removeWordSearchWordBox px-2 py-1 bg-red-200 text-red-800 rounded">&times;</button>
                                </div>
                            @endforeach
                        @else
                            <!-- Initial word box when no saved data -->
                            <div class="word-search-word-box flex items-center gap-2 mb-2">
                                <input type="text" name="word_search_words[]" 
                                       class="form-input border rounded px-3 py-2 flex-1" 
                                       placeholder="Word" required>
                                <button type="button" class="removeWordSearchWordBox px-2 py-1 bg-red-200 text-red-800 rounded">&times;</button>
                            </div>
                        @endif
                    </div>
                    <button type="button" id="addWordSearchWordBox" class="mt-2 px-4 py-2 rounded bg-green-500 text-white">Add Another Word</button>
                </div>

                <button type="submit" class="px-4 py-2 rounded bg-blue-500 text-white">Save Word Search Game</button>
            </form>
        </div>
    </div>

    <!-- Word Clock Arrangement Game Section -->
    <div class="border rounded p-6 mb-8 bg-gray-50">
        <h3 class="text-xl font-bold mb-4">Word Clock Arrangement Game</h3>
        @php
            $wordClockArrangementData = null;
            if (isset($wordClockArrangementGame) && $wordClockArrangementGame && $wordClockArrangementGame->game_data) {
                $wordClockArrangementData = is_string($wordClockArrangementGame->game_data) ? json_decode($wordClockArrangementGame->game_data, true) : $wordClockArrangementGame->game_data;
            }
        @endphp
        @if($wordClockArrangementData && isset($wordClockArrangementData['words']) && !empty($wordClockArrangementData['words']))
            <div id="wordClockArrangementSavedView" class="mb-4 p-4 bg-green-50 border rounded">
                <h4 class="font-bold mb-2 text-green-700">Saved Word Clock Arrangement Game:</h4>
                <div class="mb-2">
                    <strong>Word:</strong> <span class="text-green-900">{{ $wordClockArrangementData['word'] ?? '' }}</span>
                </div>
                <div class="mb-2">
                    <strong>Sentence/Definition:</strong> <span class="text-green-900">{{ $wordClockArrangementData['full_sentence'] ?? '' }}</span>
                </div>
                <div class="flex flex-wrap gap-2 mb-2">
                    @foreach($wordClockArrangementData['words'] as $idx => $wordData)
                        <span class="px-3 py-1 bg-green-200 rounded text-green-900">
                            {{ $wordData['word'] }} ({{ str_pad($wordData['hour'], 2, '0', STR_PAD_LEFT) }}:{{ str_pad($wordData['minute'], 2, '0', STR_PAD_LEFT) }})
                        </span>
                    @endforeach
                </div>
                <button type="button" id="editWordClockArrangementBtn" class="px-4 py-2 rounded bg-yellow-400 text-black">Edit</button>
            </div>
        @endif
        <div id="wordClockArrangementSection" class="{{ (isset($wordClockArrangementData) && !empty($wordClockArrangementData['words'])) ? 'hidden' : '' }}">
            <form id="wordClockArrangementForm" method="POST" action="{{ route('teacher.games.word-clock-arrangement.store') }}">
                @csrf
                <input type="hidden" name="word_clock_lesson_id" value="{{ $selectedLessonId }}">
                
                <div class="mb-4">
                    <label for="word_clock_word" class="block font-semibold mb-1">Word:</label>
                    <input type="text" name="word_clock_word" id="word_clock_word" 
                           class="form-input border rounded px-3 py-2 w-full" 
                           value="{{ $wordClockArrangementData['word'] ?? '' }}" 
                           placeholder="Enter a word (e.g., التقليد)" required>
                </div>

                <div class="mb-4">
                    <label for="word_clock_sentence" class="block font-semibold mb-1">Sentence / Definition:</label>
                    <textarea name="word_clock_sentence" id="word_clock_sentence" 
                              class="form-input border rounded px-3 py-2 w-full" 
                              rows="3" 
                              placeholder="Enter the full sentence or definition" required>{{ $wordClockArrangementData['full_sentence'] ?? '' }}</textarea>
                    <p class="text-sm text-gray-600 mt-1">This sentence will be automatically split into words. Each word will need a clock time assigned.</p>
                    <button type="button" id="splitSentenceBtn" class="mt-2 px-4 py-2 rounded bg-blue-500 text-white">Split Sentence into Words</button>
                </div>

                <div class="mb-4">
                    <label class="block font-semibold mb-2">Words with Clock Times:</label>
                    <div id="wordClockArrangementWordsBoxes">
                        @if(isset($wordClockArrangementData) && !empty($wordClockArrangementData['words']))
                            @foreach($wordClockArrangementData['words'] as $wordData)
                                <div class="word-clock-arrangement-word-box flex items-center gap-2 mb-4 p-3 border rounded bg-white">
                                    <div class="flex-1">
                                        <input type="text" name="word_clock_words[][word]" 
                                               class="form-input border rounded px-3 py-2 w-full" 
                                               value="{{ $wordData['word'] }}" 
                                               placeholder="Word" required>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <label class="text-sm">Hour:</label>
                                        <input type="number" name="word_clock_words[][hour]" 
                                               class="form-input border rounded px-3 py-2 w-20 hour-input" 
                                               value="{{ $wordData['hour'] }}" 
                                               min="0" max="11" placeholder="0-11" required>
                                        <label class="text-sm">Minute:</label>
                                        <input type="number" name="word_clock_words[][minute]" 
                                               class="form-input border rounded px-3 py-2 w-20 minute-input" 
                                               value="{{ $wordData['minute'] }}" 
                                               min="0" max="59" placeholder="0-59" required>
                                        <div class="clock-preview ml-2" style="width: 60px; height: 60px;">
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
                                        <button type="button" class="removeWordClockArrangementWordBox px-2 py-1 bg-red-200 text-red-800 rounded">&times;</button>
                                    </div>
                                </div>
                            @endforeach
                        @endif
                    </div>
                    <button type="button" id="addWordClockArrangementWordBox" class="mt-2 px-4 py-2 rounded bg-green-500 text-white">Add Another Word</button>
                </div>

                <button type="submit" class="px-4 py-2 rounded bg-blue-500 text-white">Save Word Clock Arrangement Game</button>
            </form>
        </div>
    </div>

    <!-- Matching Pairs Game Section -->
    <div class="border rounded p-6 mb-8 bg-gray-50 max-w-4xl">
        <h3 class="text-xl font-bold mb-4">Matching Pairs Game</h3>
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
            <div id="matchingPairsSavedView" class="mb-4 p-4 bg-green-50 border rounded">
                <div class="flex justify-between items-center mb-4">
                    <h4 class="font-bold text-green-700">Saved Matching Pairs Game</h4>
                    <button type="button" id="editMatchingPairsBtn" class="px-4 py-2 rounded bg-yellow-400 text-black">Edit</button>
                </div>
                @if(!empty($matchingPairsData['title']))
                    <div class="mb-3">
                        <strong class="text-green-800">Title:</strong> 
                        <span class="text-green-900 font-semibold text-lg" dir="rtl">{{ $matchingPairsData['title'] }}</span>
                    </div>
                @endif
                <div class="grid gap-4">
                    @foreach($matchingPairsData['pairs'] as $index => $pair)
                        <div class="border rounded p-3 bg-white">
                            <div class="grid grid-cols-2 gap-4">
                                <div class="flex items-center gap-2">
                                    <span class="font-semibold text-gray-600">{{ $index + 1 }}.</span>
                                    @if($pair['left_item_image'])
                                        <img src="{{ $pair['left_item_image'] }}" alt="Left item" class="w-16 h-16 object-cover rounded border border-gray-300">
                                    @endif
                                    @if($pair['left_item_text'])
                                        <span class="text-gray-800" dir="rtl">{{ $pair['left_item_text'] }}</span>
                                    @endif
                                </div>
                                <div class="flex items-center gap-2">
                                    <span class="text-gray-400">→</span>
                                    @if($pair['right_item_image'])
                                        <img src="{{ $pair['right_item_image'] }}" alt="Right item" class="w-16 h-16 object-cover rounded border border-gray-300">
                                    @endif
                                    @if($pair['right_item_text'])
                                        <span class="text-gray-800" dir="rtl">{{ $pair['right_item_text'] }}</span>
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
                
                <div class="mb-4">
                    <label for="matching_pairs_title" class="block font-semibold mb-1" dir="rtl">عنوان اللعبة / Title (اختياري):</label>
                    <input type="text" 
                           name="title" 
                           id="matching_pairs_title" 
                           class="form-input border rounded px-3 py-2 w-96 max-w-full" 
                           value="{{ $matchingPairsData['title'] ?? '' }}" 
                           placeholder="أدخل العنوان" 
                           dir="rtl">
                </div>
                
                <div class="mb-4">
                    <label class="block font-semibold mb-2">Matching Pairs:</label>
                    <div id="matchingPairsBoxes">
                        @if(isset($matchingPairsData) && !empty($matchingPairsData['pairs']))
                            @foreach($matchingPairsData['pairs'] as $index => $pair)
                                <div class="matching-pair-box border rounded p-4 mb-4 bg-white">
                                    <div class="flex justify-between items-center mb-2">
                                        <span class="font-semibold text-gray-600">Pair {{ $index + 1 }}</span>
                                        <button type="button" class="removeMatchingPairBox px-2 py-1 bg-red-200 text-red-800 rounded">&times;</button>
                                    </div>
                                    <div class="grid grid-cols-2 gap-4">
                                        <div>
                                            <label class="block text-sm font-semibold mb-1">Left Item (Text):</label>
                                            <input type="text" 
                                                   name="pairs[{{ $index }}][left_item_text]" 
                                                   class="form-input border rounded px-3 py-2 w-full" 
                                                   value="{{ $pair['left_item_text'] ?? '' }}" 
                                                   placeholder="Text for left column" 
                                                   dir="rtl">
                                            <label class="block text-sm font-semibold mb-1 mt-2">Left Item (Image):</label>
                                            <input type="file" 
                                                   name="pairs[{{ $index }}][left_item_image]" 
                                                   class="form-input border rounded px-3 py-2 w-full" 
                                                   accept="image/*">
                                            @if($pair['left_item_image'])
                                                <img src="{{ $pair['left_item_image'] }}" alt="Current left image" class="mt-2 w-20 h-20 object-cover rounded border">
                                            @endif
                                        </div>
                                        <div>
                                            <label class="block text-sm font-semibold mb-1">Right Item (Text):</label>
                                            <input type="text" 
                                                   name="pairs[{{ $index }}][right_item_text]" 
                                                   class="form-input border rounded px-3 py-2 w-full" 
                                                   value="{{ $pair['right_item_text'] ?? '' }}" 
                                                   placeholder="Text for right column" 
                                                   dir="rtl">
                                            <label class="block text-sm font-semibold mb-1 mt-2">Right Item (Image):</label>
                                            <input type="file" 
                                                   name="pairs[{{ $index }}][right_item_image]" 
                                                   class="form-input border rounded px-3 py-2 w-full" 
                                                   accept="image/*">
                                            @if($pair['right_item_image'])
                                                <img src="{{ $pair['right_item_image'] }}" alt="Current right image" class="mt-2 w-20 h-20 object-cover rounded border">
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @endif
                    </div>
                    <button type="button" id="addMatchingPairBox" class="mt-2 px-4 py-2 rounded bg-green-500 text-white">+ Add Pair</button>
                </div>

                <button type="submit" class="px-4 py-2 rounded bg-blue-500 text-white">Save Matching Pairs Game</button>
            </form>
        </div>
    </div>

    <!-- Scrambled Letters Game - Word/Definition Pairs Section -->
    <div class="border rounded p-6 mb-8 bg-white shadow">
        <h3 class="text-xl font-bold mb-4">Scrambled Letters Game - Word/Definition Pairs</h3>
        
        <!-- Show saved pairs for Scrambled Letters -->
        @if(isset($scramblePairs) && $scramblePairs->count() > 0)
            <div class="mb-4">
                <h5 class="font-semibold mb-2">Saved Pairs:</h5>
                <div class="grid gap-2 md:grid-cols-2">
                    @foreach ($scramblePairs as $pair)
                        <div class="pair-row border rounded p-2 flex flex-col gap-1 relative" data-id="{{ $pair->id }}" style="background-color:#D1F7F3;">
                            <div class="font-bold word-text">{{ $pair->word }}</div>
                            <div class="text-gray-700 def-text">{{ $pair->definition }}</div>
                        </div>
                    @endforeach
                </div>
            </div>
        @else
            <div class="mb-4 text-gray-500">No word pairs found for Scrambled Letters game.</div>
        @endif
        
        <!-- Add new pairs form for Scrambled Letters -->
        <form method="POST" action="{{ route('teacher.games.store') }}">
            @csrf
            <input type="hidden" name="lesson_id" value="{{ $selectedLessonId }}">
            <input type="hidden" name="game_type" value="scramble">
            <div class="flex flex-col md:flex-row gap-2 mb-2">
                <input type="text" name="words[]" class="form-input border rounded px-3 py-2 w-full md:w-1/3" placeholder="Word" required>
                <input type="text" name="definitions[]" class="form-input border rounded px-3 py-2 w-full md:w-2/3" placeholder="Definition" required>
                <button type="button" class="addPairBtn px-2 py-1 bg-[#F8C5C8] text-[#b91c1c] rounded">+</button>
            </div>
            <div class="pairs-list"></div>
            <button type="submit" class="mt-2 px-4 py-2 rounded bg-[#7AD7C1] text-white">Save Scrambled Letters Pairs</button>
        </form>
    </div>
    @endif

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Word Search Game functionality
    const editWordSearchBtn = document.getElementById('editWordSearchBtn');
    const wordSearchSavedView = document.getElementById('wordSearchSavedView');
    const wordSearchSection = document.getElementById('wordSearchSection');
    
    if (editWordSearchBtn) {
        editWordSearchBtn.addEventListener('click', function() {
            if (wordSearchSavedView) wordSearchSavedView.style.display = 'none';
            if (wordSearchSection) wordSearchSection.classList.remove('hidden');
        });
    }

    // Add/Remove Word Search Word Boxes
    const addWordSearchWordBox = document.getElementById('addWordSearchWordBox');
    const wordSearchWordsBoxes = document.getElementById('wordSearchWordsBoxes');
    
    if (addWordSearchWordBox && wordSearchWordsBoxes) {
        addWordSearchWordBox.addEventListener('click', function() {
            const box = document.createElement('div');
            box.className = 'word-search-word-box flex items-center gap-2 mb-2';
            box.innerHTML = `
                <input type="text" name="word_search_words[]" class="form-input border rounded px-3 py-2 flex-1" placeholder="Word" required>
                <button type="button" class="removeWordSearchWordBox px-2 py-1 bg-red-200 text-red-800 rounded">&times;</button>
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
    function createClockSVG(hour, minute, size = 60) {
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
            clockPreview.innerHTML = createClockSVG(parseInt(hour) || 0, parseInt(minute) || 0, 60);
        }
    }

    // Add word box
    function addWordClockArrangementWordBox(wordValue = '') {
        const box = document.createElement('div');
        box.className = 'word-clock-arrangement-word-box flex items-center gap-2 mb-4 p-3 border rounded bg-white';
        box.innerHTML = `
            <div class="flex-1">
                <input type="text" name="word_clock_words[][word]" 
                       class="form-input border rounded px-3 py-2 w-full" 
                       value="${wordValue}" 
                       placeholder="Word" required>
            </div>
            <div class="flex items-center gap-2">
                <label class="text-sm">Hour:</label>
                <input type="number" name="word_clock_words[][hour]" 
                       class="form-input border rounded px-3 py-2 w-20 hour-input" 
                       value="0" 
                       min="0" max="11" placeholder="0-11" required>
                <label class="text-sm">Minute:</label>
                <input type="number" name="word_clock_words[][minute]" 
                       class="form-input border rounded px-3 py-2 w-20 minute-input" 
                       value="0" 
                       min="0" max="59" placeholder="0-59" required>
                <div class="clock-preview ml-2" style="width: 60px; height: 60px;">
                    ${createClockSVG(0, 0, 60)}
                </div>
                <button type="button" class="removeWordClockArrangementWordBox px-2 py-1 bg-red-200 text-red-800 rounded">&times;</button>
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
            box.className = 'matching-pair-box border rounded p-4 mb-4 bg-white';
            box.innerHTML = `
                <div class="flex justify-between items-center mb-2">
                    <span class="font-semibold text-gray-600">Pair ${pairIndex + 1}</span>
                    <button type="button" class="removeMatchingPairBox px-2 py-1 bg-red-200 text-red-800 rounded">&times;</button>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold mb-1">Left Item (Text):</label>
                        <input type="text" 
                               name="pairs[${pairIndex}][left_item_text]" 
                               class="form-input border rounded px-3 py-2 w-full" 
                               placeholder="Text for left column" 
                               dir="rtl">
                        <label class="block text-sm font-semibold mb-1 mt-2">Left Item (Image):</label>
                        <input type="file" 
                               name="pairs[${pairIndex}][left_item_image]" 
                               class="form-input border rounded px-3 py-2 w-full" 
                               accept="image/*">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold mb-1">Right Item (Text):</label>
                        <input type="text" 
                               name="pairs[${pairIndex}][right_item_text]" 
                               class="form-input border rounded px-3 py-2 w-full" 
                               placeholder="Text for right column" 
                               dir="rtl">
                        <label class="block text-sm font-semibold mb-1 mt-2">Right Item (Image):</label>
                        <input type="file" 
                               name="pairs[${pairIndex}][right_item_image]" 
                               class="form-input border rounded px-3 py-2 w-full" 
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
                pairDiv.className = 'flex gap-2 mb-2';
                pairDiv.innerHTML = `<input type='text' name='words[]' value='${wordInput.value}' class='form-input border rounded px-3 py-2 w-full md:w-1/3' required readonly> <input type='text' name='definitions[]' value='${defInput.value}' class='form-input border rounded px-3 py-2 w-full md:w-2/3' required readonly> <button type='button' class='removePairBtn px-2 py-1 bg-gray-300 text-gray-700 rounded'>-</button>`;
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

@endsection
