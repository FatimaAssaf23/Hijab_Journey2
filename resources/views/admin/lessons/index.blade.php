@extends('layouts.admin')

@section('content')
<div class="min-h-screen">
    <!-- Header -->
    <div class="bg-gradient-to-r from-[#FC8EAC] via-[#EC769A] to-[#6EC6C5] shadow-xl">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-4xl font-extrabold text-white mb-2">📚 Lessons Manager</h1>
                    <p class="text-pink-100">Manage lessons by grade level • Click "Add Lesson" under each level to add</p>
                </div>
                <a href="{{ route('admin.lessons.create') }}" class="bg-[#EC769A] hover:bg-[#FC8EAC] text-white px-6 py-3 rounded-xl font-bold shadow-lg hover:shadow-xl transition-all flex items-center gap-2 text-lg">
                    <span class="text-2xl">➕</span> Create New Lesson
                </a>
            </div>
        </div>
    </div>

    <!-- Success Message -->
    @if (session('success'))
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4">
            <div class="bg-emerald-500/20 backdrop-blur border border-emerald-300/30 rounded-lg p-4 text-emerald-300">
                ✓ {{ session('success') }}
            </div>
        </div>
    @endif

    <!-- Lessons by Level -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-8">
        @forelse($groupedLessons as $levelId => $group)
            <div>
                <!-- Level Header -->
                <div class="rounded-2xl px-6 py-4 mb-4 flex items-start justify-between" style="background: linear-gradient(90deg, #F8C5C8 0%, #FC8EAC 50%, #EC769A 100%); color: #222;">
                    <div class="flex justify-between items-center">
                        <div class="flex items-center gap-2">
                            <h2 class="text-2xl font-extrabold mb-1">{{ $group['level']['name'] }}</h2>
                            <button onclick="editLevelName('{{ $levelId }}', '{{ addslashes($group['level']['name']) }}')" class="ml-2 px-2 py-1 rounded bg-[#EC769A] hover:bg-[#FC8EAC] text-white text-xs font-bold transition-all">Edit Name</button>
                            <div class="text-sm opacity-70 ml-4">{{ count($group['lessons']) }} lessons</div>
                        </div>
                        <form id="edit-level-name-form" method="POST" action="{{ route('admin.levels.updateName') }}" style="display:none;">
                            @csrf
                            <input type="hidden" name="level_id" id="edit-level-id">
                            <input type="hidden" name="level_name" id="edit-level-name">
                        </form>
                        <script>
                        function editLevelName(levelId, currentName) {
                            var newName = prompt('Enter new level name:', currentName);
                            if (newName && newName.trim() !== '' && newName !== currentName) {
                                document.getElementById('edit-level-id').value = levelId;
                                document.getElementById('edit-level-name').value = newName;
                                document.getElementById('edit-level-name-form').submit();
                            }
                        }
                        </script>
                        <a href="{{ route('admin.lessons.create', ['level' => $levelId]) }}" class="bg-white/90 hover:bg-white text-gray-800 px-4 py-2 rounded-lg font-semibold shadow hover:shadow-lg transition-all flex items-center gap-2" style="margin-left:1cm;">
                            <span>➕</span> Add Lesson
                        </a>
                        <style>
                        .add-lesson-btn-move {
                            margin-left: 1cm;
                        }
                        </style>
                        <script>
                        // Add the class to the Add Lesson button after page load
                        document.addEventListener('DOMContentLoaded', function() {
                            document.querySelectorAll('a[href*="admin.lessons.create"]').forEach(function(btn) {
                                btn.classList.add('add-lesson-btn-move');
                            });
                        });
                        </script>
                        </a>
                    </div>
                </div>

                <!-- Lessons Grid -->
                @if(count($group['lessons']) > 0)
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($group['lessons'] as $lesson)
                            @php
                                $cardColor = 'bg-white border border-[#F8C5C8]';
                            @endphp
                            <div class="relative {{ $cardColor }} rounded-2xl p-6 shadow-lg hover:shadow-xl transition-all transform hover:scale-105">
                                <!-- Card Header -->
                                <div class="flex justify-between items-start mb-4">
                                    <div class="text-5xl">{{ $lesson['icon'] }}</div>
                                    <div class="flex gap-2">
                                        <a href="{{ route('admin.lessons.edit', $lesson['id']) }}" class="bg-blue-500 hover:bg-blue-600 text-white p-2 rounded-lg transition-all">
                                            ✏️
                                        </a>
                                        <form method="POST" action="{{ route('admin.lessons.delete', $lesson['id']) }}" style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" onclick="return confirm('Delete this lesson?')" class="bg-red-500 hover:bg-red-600 text-white p-2 rounded-lg transition-all">
                                                🗑️
                                            </button>
                                        </form>
                                    </div>
                                </div>

                                <!-- Card Content -->
                                <div>
                                    <h3 class="text-xl font-bold text-gray-800 mb-2">{{ $lesson['title'] }}</h3>
                                    <div class="bg-black/20 rounded-lg px-3 py-2 inline-block">
                                        <span class="text-gray-700 font-semibold">{{ $lesson['skills'] }} skills</span>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="bg-white/10 backdrop-blur rounded-lg p-8 text-center text-white/70">
                        No lessons for this level. Add one to get started!
                    </div>
                @endif
            </div>
        @empty
            <div class="text-center text-white/70">No lessons available</div>
        @endforelse
    </div>
</div>
@endsection
