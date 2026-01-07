@extends('layouts.app')

@section('content')


<div class="container mx-auto py-8">
    <!-- Clock Game Button and Section -->
    <button id="clockGameBtn" class="mb-6 px-4 py-2 rounded bg-blue-500 text-white">Clock Game</button>
    <div id="clockGameSection" class="{{ (isset($clockGame) && $clockGame && !empty($clockGame->game_data['words'])) ? 'hidden' : ((isset($selectedLessonId) && $selectedLessonId) ? '' : 'hidden') }} border rounded p-6 mb-8 bg-gray-50">
        <h3 class="text-xl font-bold mb-4">Clock Game Sentence & Lesson</h3>
        <form id="clockGameForm" method="POST" action="{{ route('teacher.games.clock.store') }}">
            @csrf
            <div class="mb-4">
                <label for="clock_lesson_id" class="block font-semibold mb-1">Select Lesson:</label>
                <select name="clock_lesson_id" id="clock_lesson_id" class="form-select border rounded px-3 py-2 w-full" required>
                    <option value="">-- Choose Lesson --</option>
                    @foreach($lessons ?? [] as $lesson)
                        <option value="{{ $lesson->lesson_id }}" {{ (isset($selectedLessonId) && $selectedLessonId == $lesson->lesson_id) ? 'selected' : '' }}>{{ $lesson->title }}</option>
                    @endforeach
                    @if(isset($clockGame) && $clockGame && !empty($clockGame->game_data['words']))
                        @php
                            $clockGameData = is_string($clockGame->game_data ?? null) ? json_decode($clockGame->game_data, true) : ($clockGame->game_data ?? []);
                        @endphp
                        @if(isset($clockGameData['words']) && is_array($clockGameData['words']))
                            <div id="clockGameSavedView" class="mt-4 p-4 bg-green-50 border rounded">
                                <h4 class="font-bold mb-2 text-green-700">Saved Clock Game Words:</h4>
                                <div class="flex flex-wrap gap-2 mb-2">
                                    @foreach($clockGameData['words'] as $word)
                                        <span class="px-3 py-1 bg-green-200 rounded text-green-900">{{ $word }}</span>
                                    @endforeach
                                </div>
                                <button type="button" id="editClockGameBtn" class="px-4 py-2 rounded bg-yellow-400 text-black">Edit</button>
                            </div>
                            <script>
                            document.addEventListener('DOMContentLoaded', function() {
                                var editBtn = document.getElementById('editClockGameBtn');
                                var savedView = document.getElementById('clockGameSavedView');
                                var clockGameSection = document.getElementById('clockGameSection');
                                if (editBtn) {
                                    editBtn.addEventListener('click', function() {
                                        savedView.style.display = 'none';
                                        clockGameSection.style.display = '';
                                        // Optionally, pre-fill the input boxes with saved words
                                        var words = @json($clockGameData['words']);
                                        var boxes = document.getElementById('clockWordsBoxes');
                                        boxes.innerHTML = '';
                                        words.forEach(function(word, idx) {
                                            var box = document.createElement('div');
                                            box.className = 'flex items-center gap-1 mb-1 clock-word-box';
                                            box.innerHTML = `<input type=\"text\" name=\"clock_words[]\" class=\"form-input border rounded px-3 py-2\" value=\"${word}\" placeholder=\"Word ${idx+1}\" required>\n                                                <button type=\"button\" class=\"removeClockWordBox px-2 py-1 bg-red-200 text-red-800 rounded\">&times;</button>`;
                                            boxes.appendChild(box);
                                        });
                                    });
                                }
                            });
                            </script>
                        @endif
                    @endif
                </select>
            </div>
            <div class="mb-4" id="clockWordsBoxes">
                <!-- Dynamic word boxes will be added here -->
            </div>
            <button type="button" id="addClockWordBox" class="mb-4 px-4 py-2 rounded bg-green-500 text-white">Add Another Word</button>
            <button type="submit" class="px-4 py-2 rounded bg-blue-500 text-white">Save Clock Game</button>
        </form>
    </div>
    <h2 class="text-2xl font-bold mb-6">Word/Definition Pairs</h2>



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
    <form id="createGroupForm" method="POST" action="{{ route('teacher.games.store') }}">
        @csrf
        <input type="hidden" name="create_groups" value="1">
        <input type="hidden" name="lesson_id" value="{{ $selectedLessonId }}">
        <div class="flex items-center gap-2 mb-0">
            <label for="group_name" class="block font-semibold mb-1">Group Name:</label>
            <input type="text" name="group_name" id="group_name" class="form-input border rounded px-3 py-2 w-48" required>
            <button type="submit" class="px-4 py-2 rounded bg-[#7AD7C1] text-white">Create Group</button>
        </div>
    </form>
    @endif


    @if(isset($selectedLessonId) && $selectedLessonId)
    <div id="groupsContainer">
        @if($groups->count() > 0)
            @foreach($groups as $group)
                <div class="group-box border rounded p-4 mb-4 bg-white shadow">
                    <h4 class="font-bold mb-2">{{ $group->name }}</h4>
                    <!-- Show saved pairs for this group -->
                    @php
                        $groupPairs = \App\Models\GroupWordPair::where('lesson_group_id', $group->id)->get();
                    @endphp
                    @if($groupPairs->count() > 0)
                        <div class="mb-4">
                            <h5 class="font-semibold mb-1">Saved Pairs:</h5>
                            <div class="grid gap-2 md:grid-cols-2">
                                @foreach ($groupPairs as $pair)
                                    <div class="pair-row border rounded p-2 flex flex-col gap-1 relative" data-id="{{ $pair->id }}" style="background-color:#D1F7F3;">
                                        <div class="font-bold word-text">{{ $pair->word }}</div>
                                        <div class="text-gray-700 def-text">{{ $pair->definition }}</div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @else
                        <div class="mb-4 text-gray-500">No word pairs found for this group.</div>
                    @endif
                    <!-- Add new pairs form for this group -->
                    <form method="POST" action="{{ route('teacher.games.store') }}?lesson_id={{ $selectedLessonId }}&group_id={{ $group->id }}">
                        @csrf
                        <input type="hidden" name="group_id" value="{{ $group->id }}">
                        <div class="flex flex-col md:flex-row gap-2 mb-2">
                            <input type="text" name="words[]" class="form-input border rounded px-3 py-2 w-full md:w-1/3" placeholder="Word" required>
                            <input type="text" name="definitions[]" class="form-input border rounded px-3 py-2 w-full md:w-2/3" placeholder="Definition" required>
                            <button type="button" class="addPairBtn px-2 py-1 bg-[#F8C5C8] text-[#b91c1c] rounded">+</button>
                        </div>
                        <div class="pairs-list"></div>
                        <button type="submit" class="mt-2 px-4 py-2 rounded bg-[#7AD7C1] text-white">Save Pairs</button>
                    </form>
                </div>
            @endforeach
        @else
            <div class="text-gray-500">No groups found for this lesson. Please create a group.</div>
        @endif
    </div>
    <!-- Group Name and Create Group button moved inline above -->
    @endif

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Clock Game Section Toggle
    const clockGameBtn = document.getElementById('clockGameBtn');
    const clockGameSection = document.getElementById('clockGameSection');
    clockGameBtn.addEventListener('click', function() {
        clockGameSection.classList.toggle('hidden');
    });

    // Add/Remove Clock Word Boxes
    const addClockWordBox = document.getElementById('addClockWordBox');
    const clockWordsBoxes = document.getElementById('clockWordsBoxes');
    addClockWordBox.addEventListener('click', function() {
        const count = clockWordsBoxes.querySelectorAll('.clock-word-box').length + 1;
        const box = document.createElement('div');
        box.className = 'flex items-center gap-1 mb-1 clock-word-box';
        box.innerHTML = `<input type="text" name="clock_words[]" class="form-input border rounded px-3 py-2" placeholder="Word ${count}" required>
            <button type="button" class="removeClockWordBox px-2 py-1 bg-red-200 text-red-800 rounded">&times;</button>`;
        clockWordsBoxes.appendChild(box);
    });

    // Remove word box logic
    clockWordsBoxes.addEventListener('click', function(e) {
        if (e.target.classList.contains('removeClockWordBox')) {
            const box = e.target.closest('.clock-word-box');
            if (box) box.remove();
        }
    });

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
