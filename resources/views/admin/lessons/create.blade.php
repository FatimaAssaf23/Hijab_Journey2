@extends('layouts.admin')

@section('content')
<div class="min-h-screen">
    <!-- Header -->
    <div class="bg-gradient-to-r from-[#FC8EAC] via-[#EC769A] to-[#6EC6C5] shadow-xl">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <h1 class="text-4xl font-extrabold text-white mb-2">+ Add New Lesson</h1>
            @php
                $selectedLevel = collect($levels)->firstWhere('id', request('level', 1));
            @endphp
            <p class="text-pink-100">Adding to: <span class="font-bold text-white">{{ $selectedLevel['name'] ?? 'Level 1' }}</span></p>
        </div>
    </div>

    <!-- Form -->
    <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="bg-white rounded-2xl p-8 shadow-xl">
            <form method="POST" action="{{ route('admin.lessons.store') }}" enctype="multipart/form-data">
                @csrf

                <div class="space-y-6">
                    <!-- Title -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Lesson Title</label>
                        <input type="text" name="title" value="{{ old('title') }}" class="w-full border-2 border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:border-pink-500" placeholder="e.g., Addition" required>
                        @error('title') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <!-- Icon -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Icon Emoji</label>
                        <input type="hidden" name="icon" id="selectedIcon" value="{{ old('icon') }}" required>
                        
                        <!-- Selected emoji display with change button -->
                        <div id="selectedEmojiContainer" class="hidden mb-3">
                            <div class="flex items-center gap-3 p-4 border-2 border-pink-400 rounded-lg bg-gradient-to-br from-pink-50 to-purple-50">
                                <span id="chosenEmoji" class="text-4xl"></span>
                                <span class="text-gray-600 font-medium">Selected Icon</span>
                                <button type="button" onclick="toggleEmojiGrid()" class="ml-auto bg-pink-500 hover:bg-pink-600 text-white px-4 py-2 rounded-lg text-sm font-semibold transition-all">
                                    Change
                                </button>
                            </div>
                        </div>

                        <!-- Toggle button to show emoji grid -->
                        <button type="button" id="showEmojiBtn" onclick="toggleEmojiGrid()" class="w-full mb-3 bg-gradient-to-r from-[#FC8EAC] via-[#EC769A] to-[#6EC6C5] hover:from-[#EC769A] hover:to-[#6EC6C5] text-white px-4 py-3 rounded-lg font-semibold transition-all flex items-center justify-center gap-2">
                            <span>🎨</span> Choose an Icon
                        </button>

                        <!-- Emoji grid (hidden by default) -->
                        <div id="emojiGrid" class="hidden grid grid-cols-5 gap-3 p-4 border-2 border-gray-300 rounded-lg bg-gradient-to-br from-pink-50 to-purple-50">
                            @php
                                $emojis = [
                                    // Row 1: Hijab & Prayer
                                    '🧕', '🤲', '🙏', '🕋', '🕌',
                                    // Row 2: Islamic symbols
                                    '📿', '🌙', '⭐', '💧', '💦',
                                    // Row 3: Learning
                                    '📖', '📚', '✏️', '💡', '✨',
                                    // Row 4: Good character
                                    '❤️', '🤝', '🌸', '🌺', '🕊️'
                                ];
                            @endphp
                            @foreach($emojis as $emoji)
                                <button type="button" 
                                    onclick="selectIcon('{{ $emoji }}')" 
                                    class="emoji-btn text-2xl p-3 rounded-lg hover:bg-pink-200 hover:scale-110 transition-all border-2 border-transparent focus:outline-none bg-white shadow-sm"
                                    data-emoji="{{ $emoji }}">
                                    {{ $emoji }}
                                </button>
                            @endforeach
                        </div>
                        @error('icon') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <script>
                        let emojiGridVisible = false;

                        function toggleEmojiGrid() {
                            const grid = document.getElementById('emojiGrid');
                            const showBtn = document.getElementById('showEmojiBtn');
                            
                            emojiGridVisible = !emojiGridVisible;
                            
                            if (emojiGridVisible) {
                                grid.classList.remove('hidden');
                                grid.classList.add('grid');
                                showBtn.innerHTML = '<span>✕</span> Hide Icons';
                            } else {
                                grid.classList.add('hidden');
                                grid.classList.remove('grid');
                                showBtn.innerHTML = '<span>🎨</span> Choose an Icon';
                            }
                        }

                        function selectIcon(emoji) {
                            document.getElementById('selectedIcon').value = emoji;
                            document.getElementById('chosenEmoji').textContent = emoji;
                            
                            // Show selected container, hide the show button
                            document.getElementById('selectedEmojiContainer').classList.remove('hidden');
                            document.getElementById('showEmojiBtn').classList.add('hidden');
                            
                            // Hide the emoji grid
                            document.getElementById('emojiGrid').classList.add('hidden');
                            document.getElementById('emojiGrid').classList.remove('grid');
                            emojiGridVisible = false;
                        }
                    </script>

                    <!-- Skills -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Number of Skills</label>
                        <input type="number" name="skills" value="{{ old('skills') }}" min="0" class="w-full border-2 border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:border-pink-500" placeholder="e.g., 21" required>
                        @error('skills') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <!-- Level Selection -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Level</label>
                        <select name="levelId" class="w-full border-2 border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:border-pink-500 bg-white">
                            @foreach($levels as $level)
                                <option value="{{ $level['id'] }}" {{ request('level', 1) == $level['id'] ? 'selected' : '' }}>{{ $level['name'] }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Description -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Description (Optional)</label>
                        <textarea name="description" rows="3" class="w-full border-2 border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:border-pink-500" placeholder="Brief description of the lesson...">{{ old('description') }}</textarea>
                        @error('description') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <!-- Content File Upload -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Lesson Content (PDF or Video)</label>
                        <input type="file" name="content_file" accept=".pdf,.mp4,.mov,.avi" class="w-full border-2 border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:border-pink-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-pink-50 file:text-pink-700 hover:file:bg-pink-100">
                        <p class="text-gray-500 text-xs mt-1">Accepted formats: PDF, MP4, MOV, AVI (Max 50MB)</p>
                        @error('content_file') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <!-- Duration -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Duration (Minutes, Optional)</label>
                        <input type="number" name="duration_minutes" value="{{ old('duration_minutes') }}" class="w-full border-2 border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:border-pink-500" placeholder="e.g., 45">
                        @error('duration_minutes') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>
                </div>

                <!-- Buttons -->
                <div class="flex gap-3 mt-8">
                    <a href="{{ route('admin.lessons') }}" class="flex-1 bg-gray-300 hover:bg-gray-400 text-gray-800 font-semibold py-3 rounded-lg transition-all text-center">
                        Cancel
                    </a>
                    <button type="submit" class="flex-1 bg-gradient-to-r from-[#FC8EAC] via-[#EC769A] to-[#6EC6C5] hover:from-[#EC769A] hover:to-[#6EC6C5] text-white font-semibold py-3 rounded-lg transition-all">
                        Create Lesson
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
