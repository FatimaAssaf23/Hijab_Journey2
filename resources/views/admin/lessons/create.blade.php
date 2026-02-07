@extends('layouts.admin')

@section('content')
<div class="min-h-screen">
    <!-- Header -->
    <div class="bg-gradient-to-r from-pink-200 via-pink-100 to-teal-200 shadow-xl">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="flex items-center gap-4 mb-4">
                <a href="{{ route('admin.lessons') }}" class="bg-white/40 hover:bg-white/50 text-gray-700 font-semibold px-4 py-2 rounded-lg transition-all flex items-center gap-2 backdrop-blur-sm">
                    <span>←</span> Go Back
                </a>
            </div>
            <h1 class="text-4xl font-extrabold text-gray-700 mb-2">+ Add New Lesson</h1>
            @php
                $selectedLevel = collect($levels)->firstWhere('id', request('level', 1));
            @endphp
            <p class="text-gray-600">Adding to: <span class="font-bold text-gray-700">{{ $selectedLevel['name'] ?? 'Level 1' }}</span></p>
        </div>
    </div>

    <!-- Form -->
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="bg-white rounded-2xl p-10 shadow-xl">
            <form method="POST" action="{{ route('admin.lessons.store') }}" enctype="multipart/form-data">
                @csrf

                <div class="space-y-6">
                    <!-- Title -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Lesson Title</label>
                        <input type="text" name="title" value="{{ old('title') }}" class="w-full border-2 border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:border-pink-300" placeholder="e.g., Addition" required>
                        @error('title') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <!-- Icon -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Icon Emoji</label>
                        <input type="hidden" name="icon" id="selectedIcon" value="{{ old('icon') }}" required>
                        
                        <!-- Selected emoji display with change button -->
                        <div id="selectedEmojiContainer" class="hidden mb-3">
                            <div class="flex items-center gap-3 p-4 border-2 border-pink-200 rounded-lg bg-gradient-to-br from-pink-50 to-purple-50">
                                <span id="chosenEmoji" class="text-4xl"></span>
                                <span class="text-gray-600 font-medium">Selected Icon</span>
                                <button type="button" onclick="toggleEmojiGrid()" class="ml-auto bg-pink-300 hover:bg-pink-400 text-gray-700 px-4 py-2 rounded-lg text-sm font-semibold transition-all">
                                    Change
                                </button>
                            </div>
                        </div>

                        <!-- Toggle button to show emoji grid -->
                        <button type="button" id="showEmojiBtn" onclick="toggleEmojiGrid()" class="w-full mb-3 bg-gradient-to-r from-pink-300 via-pink-200 to-teal-300 hover:from-pink-200 hover:to-teal-200 text-gray-700 px-4 py-3 rounded-lg font-semibold transition-all flex items-center justify-center gap-2">
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
                                    class="emoji-btn text-2xl p-3 rounded-lg hover:bg-pink-100 hover:scale-110 transition-all border-2 border-transparent focus:outline-none bg-white shadow-sm"
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

                        // Ensure URL and file upload are mutually exclusive
                        document.addEventListener('DOMContentLoaded', function() {
                            const contentUrlInput = document.querySelector('input[name="content_url"]');
                            const contentFileInput = document.getElementById('content_file');

                            if (contentFileInput) {
                                contentFileInput.addEventListener('change', function() {
                                    if (this.files && this.files.length > 0) {
                                        // Clear URL if file is selected
                                        if (contentUrlInput) {
                                            contentUrlInput.value = '';
                                        }
                                    }
                                });
                            }

                            if (contentUrlInput) {
                                contentUrlInput.addEventListener('input', function() {
                                    if (this.value.trim() !== '') {
                                        // Clear file input if URL is entered
                                        if (contentFileInput) {
                                            contentFileInput.value = '';
                                        }
                                    }
                                });
                            }
                        });
                    </script>

                    <!-- Skills and Level (Inline) -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Skills -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Number of Skills</label>
                            <input type="number" name="skills" value="{{ old('skills') }}" min="0" class="w-full border-2 border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:border-pink-300" placeholder="e.g., 21" required>
                            @error('skills') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <!-- Level Selection -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Level</label>
                            <select name="levelId" class="w-full border-2 border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:border-pink-300 bg-white">
                                @for($i = 1; $i <= 10; $i++)
                                    <option value="{{ $i }}" {{ request('level', 1) == $i ? 'selected' : '' }}>Level {{ $i }}</option>
                                @endfor
                            </select>
                        </div>
                    </div>

                    <!-- Description -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Description (Optional)</label>
                        <textarea name="description" rows="3" class="w-full border-2 border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:border-pink-300" placeholder="Brief description of the lesson...">{{ old('description') }}</textarea>
                        @error('description') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <!-- Content URL (YouTube or other URL) -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Lesson Content URL (YouTube or other link) <span class="text-gray-400 font-normal">(Optional)</span></label>
                        <input type="url" name="content_url" value="{{ old('content_url') }}" class="w-full border-2 border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:border-pink-300" placeholder="https://www.youtube.com/watch?v=... or https://youtu.be/...">
                        <p class="text-gray-500 text-xs mt-1">Enter a YouTube link or any other content URL.</p>
                        @error('content_url') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <!-- OR Upload Lesson Content File -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">OR Upload Lesson Content File <span class="text-gray-400 font-normal">(Optional)</span></label>
                        <input type="file" name="content_file" id="content_file" class="w-full border-2 border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:border-pink-300 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-pink-50 file:text-pink-500 hover:file:bg-pink-100 file:cursor-pointer">
                        <p class="text-gray-500 text-xs mt-1">Leave empty to keep current file. Accepted formats: PDF, MP4, MOV, AVI (Max 100MB).</p>
                        <p class="text-gray-500 text-xs mt-1">URL above or file upload, not both.</p>
                        @error('content_file') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <!-- Duration -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Duration (Minutes, Optional)</label>
                        <input type="number" name="duration_minutes" value="{{ old('duration_minutes') }}" class="w-full border-2 border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:border-pink-300" placeholder="e.g., 45">
                        @error('duration_minutes') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>
                </div>

                <!-- Buttons -->
                <div class="flex gap-3 mt-8">
                    <a href="{{ route('admin.lessons') }}" class="flex-1 bg-gray-300 hover:bg-gray-400 text-gray-800 font-semibold py-3 rounded-lg transition-all text-center">
                        Cancel
                    </a>
                    <button type="submit" class="flex-1 bg-gradient-to-r from-pink-300 to-teal-300 hover:shadow-lg text-gray-700 font-semibold py-3 rounded-lg transition-all">
                        Create Lesson
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
