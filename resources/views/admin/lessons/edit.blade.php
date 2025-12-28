@extends('layouts.admin')

@section('content')
<div class="min-h-screen">
    <!-- Header -->
    <div class="bg-gradient-to-r from-pink-600 via-pink-400 to-teal-400 shadow-xl">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <h1 class="text-4xl font-extrabold text-white mb-2">✏️ Edit Lesson</h1>
            <p class="text-pink-100">Update lesson details</p>
        </div>
    </div>

    <!-- Form -->
    <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="bg-white rounded-2xl p-8 shadow-xl">
            <form method="POST" action="{{ route('admin.lessons.update', $lesson['id']) }}" enctype="multipart/form-data">
                @csrf
                @method('PATCH')

                <div class="space-y-6">
                    <!-- Title -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Lesson Title</label>
                        <input type="text" name="title" value="{{ old('title', $lesson['title']) }}" class="w-full border-2 border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:border-pink-500" required>
                        @error('title') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <!-- Icon -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Icon Emoji</label>
                        <input type="hidden" name="icon" id="selectedIcon" value="{{ old('icon', $lesson['icon']) }}" required>
                        
                        <!-- Selected emoji display with change button -->
                        <div id="selectedEmojiContainer" class="mb-3">
                            <div class="flex items-center gap-3 p-4 border-2 border-pink-400 rounded-lg bg-gradient-to-br from-pink-50 to-purple-50">
                                <span id="chosenEmoji" class="text-4xl">{{ old('icon', $lesson['icon']) }}</span>
                                <span class="text-gray-600 font-medium">Selected Icon</span>
                                <button type="button" onclick="toggleEmojiGrid()" class="ml-auto bg-pink-500 hover:bg-pink-600 text-white px-4 py-2 rounded-lg text-sm font-semibold transition-all">
                                    Change
                                </button>
                            </div>
                        </div>

                        <!-- Emoji grid (hidden by default) -->
                        <div id="emojiGrid" class="hidden grid grid-cols-5 gap-3 p-4 border-2 border-gray-300 rounded-lg bg-gradient-to-br from-pink-50 to-purple-50">
                            @php
                                $emojis = [
                                    '🧕', '🤲', '🙏', '🕋', '🕌',
                                    '📿', '🌙', '⭐', '💧', '💦',
                                    '📖', '📚', '✏️', '💡', '✨',
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
                            emojiGridVisible = !emojiGridVisible;
                            
                            if (emojiGridVisible) {
                                grid.classList.remove('hidden');
                                grid.classList.add('grid');
                            } else {
                                grid.classList.add('hidden');
                                grid.classList.remove('grid');
                            }
                        }

                        function selectIcon(emoji) {
                            document.getElementById('selectedIcon').value = emoji;
                            document.getElementById('chosenEmoji').textContent = emoji;
                            
                            // Hide the emoji grid
                            document.getElementById('emojiGrid').classList.add('hidden');
                            document.getElementById('emojiGrid').classList.remove('grid');
                            emojiGridVisible = false;
                        }
                    </script>

                    <!-- Skills -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Number of Skills</label>
                        <input type="number" name="skills" value="{{ old('skills', $lesson['skills']) }}" min="0" class="w-full border-2 border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:border-pink-500" required>
                        @error('skills') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <!-- Hidden Level ID -->
                    <input type="hidden" name="levelId" value="{{ $lesson['levelId'] }}">

                    <!-- Description -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Description (Optional)</label>
                        <textarea name="description" rows="3" class="w-full border-2 border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:border-pink-500" placeholder="Brief description of the lesson...">{{ old('description', $lesson['description'] ?? '') }}</textarea>
                        @error('description') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <!-- Current File Preview -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Current Lesson Content</label>
                        @if(isset($lesson['content_url']) && $lesson['content_url'])
                            @php
                                $fileExtension = pathinfo($lesson['content_url'], PATHINFO_EXTENSION);
                                $isVideo = in_array(strtolower($fileExtension), ['mp4', 'mov', 'avi', 'webm']);
                                $isPdf = strtolower($fileExtension) === 'pdf';
                            @endphp
                            
                            <div class="bg-gradient-to-br from-pink-50 to-purple-50 rounded-lg p-4 border-2 border-pink-200">
                                @if($isVideo)
                                    <div class="mb-3">
                                        <video controls class="w-full rounded-lg max-h-64">
                                            <source src="{{ asset('storage/' . $lesson['content_url']) }}" type="video/{{ $fileExtension }}">
                                            Your browser does not support the video tag.
                                        </video>
                                    </div>
                                @elseif($isPdf)
                                    <div class="flex items-center gap-3 mb-3">
                                        <span class="text-4xl">📄</span>
                                        <div>
                                            <p class="font-semibold text-gray-800">PDF Document</p>
                                            <a href="{{ asset('storage/' . $lesson['content_url']) }}" target="_blank" class="text-pink-500 hover:text-pink-600 text-sm font-medium">View PDF →</a>
                                        </div>
                                    </div>
                                @endif
                                <p class="text-sm text-gray-600">📁 {{ basename($lesson['content_url']) }}</p>
                            </div>
                        @else
                            <div class="bg-gray-100 rounded-lg p-4 border-2 border-dashed border-gray-300 text-center">
                                <span class="text-4xl">📭</span>
                                <p class="text-gray-500 mt-2">No content uploaded yet</p>
                            </div>
                        @endif
                    </div>

                    <!-- Content File Upload -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">{{ isset($lesson['content_url']) && $lesson['content_url'] ? 'Replace Lesson Content' : 'Upload Lesson Content' }} (PDF or Video)</label>
                        <input type="file" name="content_file" accept=".pdf,.mp4,.mov,.avi" class="w-full border-2 border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:border-pink-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-pink-50 file:text-pink-700 hover:file:bg-pink-100">
                        <p class="text-gray-500 text-xs mt-1">{{ isset($lesson['content_url']) && $lesson['content_url'] ? 'Leave empty to keep current file.' : '' }} Accepted formats: PDF, MP4, MOV, AVI (Max 50MB)</p>
                        @error('content_file') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <!-- Duration -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Duration (Minutes, Optional)</label>
                        <input type="number" name="duration_minutes" value="{{ old('duration_minutes', $lesson['duration_minutes'] ?? '') }}" class="w-full border-2 border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:border-pink-500" placeholder="e.g., 45">
                        @error('duration_minutes') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>
                </div>

                <!-- Buttons -->
                <div class="flex gap-3 mt-8">
                    <a href="{{ route('admin.lessons') }}" class="flex-1 bg-gray-300 hover:bg-gray-400 text-gray-800 font-semibold py-3 rounded-lg transition-all text-center">
                        Cancel
                    </a>
                    <button type="submit" class="flex-1 bg-gradient-to-r from-pink-500 to-teal-400 hover:shadow-lg text-white font-semibold py-3 rounded-lg transition-all">
                        Update Lesson
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
