@extends('layouts.admin')

@section('content')
<div class="min-h-screen" style="background: linear-gradient(135deg, #FFF4FA 0%, #FDF2F8 30%, #F0F9FF 70%, #E0F7FA 100%);">
    <!-- Header -->
    <div class="bg-gradient-to-r from-pink-200/90 via-rose-100/80 to-cyan-200/90 shadow-2xl border-b-4 border-pink-300/50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
            <div class="flex flex-col md:flex-row justify-between items-center gap-6">
                <div class="flex items-center gap-6 text-center md:text-left">
                    <!-- Lessons Icon -->
                    <div class="hidden md:flex items-center justify-center w-24 h-24 rounded-3xl bg-gradient-to-br from-pink-500 via-rose-400 to-cyan-500 shadow-2xl transform hover:scale-105 transition-all duration-300 border-4 border-white/50">
                        <div class="text-6xl filter drop-shadow-2xl">📖</div>
                    </div>
                    <div>
                        <h1 class="text-5xl font-extrabold text-gray-800 mb-3 drop-shadow-lg flex items-center gap-4 justify-center md:justify-start">
                            <span class="md:hidden flex items-center justify-center w-20 h-20 rounded-2xl bg-gradient-to-br from-pink-500 via-rose-400 to-cyan-500 shadow-xl border-4 border-white/50">
                                <span class="text-5xl">📖</span>
                            </span>
                            <span class="bg-clip-text text-transparent bg-gradient-to-r from-pink-600 via-rose-500 to-cyan-600">Lessons Manager</span>
                        </h1>
                        <p class="text-gray-700 text-lg font-medium">Manage lessons by grade level • Click "Add Lesson" under each level to add</p>
                    </div>
                </div>
                <a href="{{ route('admin.lessons.create') }}" class="bg-gradient-to-r from-pink-400 to-rose-400 hover:from-pink-500 hover:to-rose-500 text-white px-8 py-4 rounded-2xl font-bold shadow-xl hover:shadow-2xl transition-all transform hover:scale-105 flex items-center gap-3 text-lg border-2 border-pink-300/50">
                    <span class="text-2xl">➕</span> Create New Lesson
                </a>
            </div>
        </div>
    </div>

    <!-- Success Message -->
    @if (session('success'))
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-6">
            <div class="bg-gradient-to-r from-green-50 to-emerald-50 border-l-4 border-green-500 text-green-800 px-6 py-4 rounded-xl shadow-lg backdrop-blur-lg">
                <div class="flex items-center gap-3">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span class="font-semibold">{{ session('success') }}</span>
                </div>
            </div>
        </div>
    @endif

    <!-- Search Field -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-6">
        <div class="bg-white/90 backdrop-blur-md rounded-xl shadow-xl border-2 border-pink-200/50 overflow-hidden">
            <!-- Toggle Button -->
            <button 
                type="button" 
                onclick="toggleSearchSection()" 
                class="w-full flex items-center justify-between p-3 hover:bg-pink-50/50 transition-all"
                id="searchToggleBtn"
            >
                <span class="text-gray-700 font-semibold flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                    Search Lessons
                </span>
                <svg 
                    xmlns="http://www.w3.org/2000/svg" 
                    class="h-5 w-5 text-gray-600 transition-transform duration-300" 
                    id="searchArrow"
                    fill="none" 
                    viewBox="0 0 24 24" 
                    stroke="currentColor"
                >
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                </svg>
            </button>
            
            <!-- Search Form (Collapsible) -->
            <div id="searchSection" class="border-t border-pink-200/50">
                <form method="GET" action="{{ route('admin.lessons') }}" class="p-4">
                    <div class="flex flex-col md:flex-row gap-3 items-center">
                        <div class="flex-1 relative">
                            <input 
                                type="text" 
                                name="search" 
                                value="{{ $searchQuery ?? '' }}" 
                                placeholder="Search lessons by title or description..." 
                                class="w-full border-2 border-gray-300 rounded-lg px-4 py-2 pl-10 focus:outline-none focus:border-pink-300 text-gray-700"
                                id="searchInput"
                            >
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400 absolute left-3 top-1/2 transform -translate-y-1/2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </div>
                        <div class="flex gap-2">
                            <button 
                                type="submit" 
                                class="bg-gradient-to-r from-pink-300 to-teal-300 hover:shadow-lg text-gray-700 font-semibold px-5 py-2 rounded-lg transition-all"
                            >
                                Search
                            </button>
                            @if(isset($searchQuery) && !empty($searchQuery))
                                <a 
                                    href="{{ route('admin.lessons') }}" 
                                    class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold px-5 py-2 rounded-lg transition-all"
                                >
                                    Clear
                                </a>
                            @endif
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Level Name Edit Form (outside loop) -->
    <form id="edit-level-name-form" method="POST" action="{{ route('admin.levels.updateName') }}" style="display:none;">
        @csrf
        <input type="hidden" name="level_id" id="edit-level-id">
        <input type="hidden" name="level_name" id="edit-level-name">
    </form>

    <!-- Lessons by Level -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 space-y-10">
        @forelse($groupedLessons as $levelId => $group)
            <div class="transform transition-all duration-300">
                <!-- Level Header -->
                <div class="rounded-3xl px-8 py-6 mb-6 shadow-2xl border-2 border-pink-200/50 backdrop-blur-sm bg-gradient-to-br from-pink-200/90 via-rose-100/80 to-cyan-200/90">
                    <div class="flex flex-col md:flex-row justify-between items-center gap-4 w-full">
                        <div class="flex flex-col md:flex-row items-center md:items-start gap-4">
                            <h2 class="text-3xl font-extrabold text-gray-800 drop-shadow-lg mb-2">{{ $group['level']['name'] }}</h2>
                            <div class="flex items-center gap-3">
                                <button onclick="editLevelName('{{ $levelId }}', '{{ addslashes($group['level']['name']) }}')" class="px-4 py-2 rounded-xl bg-white/90 hover:bg-white text-pink-600 text-sm font-bold transition-all transform hover:scale-105 shadow-md hover:shadow-lg border-2 border-pink-300/50">
                                    ✏️ Edit Name
                                </button>
                                <div class="bg-white/80 backdrop-blur-sm rounded-xl px-4 py-2 text-cyan-700 font-bold shadow-md border-2 border-cyan-300/50">
                                    {{ count($group['lessons']) }} {{ count($group['lessons']) === 1 ? 'lesson' : 'lessons' }}
                                </div>
                            </div>
                        </div>
                        <a href="{{ route('admin.lessons.create', ['level' => $levelId]) }}" class="bg-gradient-to-r from-white/95 to-white hover:from-white hover:to-cyan-50 text-pink-600 px-6 py-3 rounded-xl font-bold shadow-lg hover:shadow-xl transition-all transform hover:scale-105 flex items-center gap-2 border-2 border-pink-300/50">
                            <span class="text-xl">➕</span> Add Lesson
                        </a>
                    </div>
                </div>

                <!-- Lessons Grid -->
                @if(count($group['lessons']) > 0)
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($group['lessons'] as $lesson)
                            <div class="group relative bg-white/90 backdrop-blur-md rounded-3xl p-6 shadow-xl hover:shadow-2xl transition-all duration-300 transform hover:scale-105 border-2 border-pink-200/50 hover:border-cyan-300/50" style="will-change: transform;">
                                <!-- Gradient Top Border -->
                                <div class="absolute top-0 left-0 right-0 h-1.5 bg-gradient-to-r from-pink-400 via-rose-400 to-cyan-400 rounded-t-3xl"></div>
                                
                                <!-- Card Header -->
                                <div class="flex justify-between items-start mb-5 mt-2">
                                    <div class="text-6xl transform group-hover:scale-110 transition-transform duration-300 filter drop-shadow-lg icon-render-fix">{{ $lesson['icon'] }}</div>
                                    <div class="flex gap-2">
                                        <a href="{{ route('admin.lessons.edit', $lesson['id']) }}" class="bg-gradient-to-r from-cyan-400 to-teal-400 hover:from-cyan-500 hover:to-teal-500 text-white p-3 rounded-xl transition-all transform hover:scale-110 shadow-lg hover:shadow-xl border-2 border-cyan-300/50">
                                            <span class="text-lg">✏️</span>
                                        </a>
                                        <form method="POST" action="{{ route('admin.lessons.delete', $lesson['id']) }}" style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" onclick="return confirm('Are you sure you want to delete this lesson?')" class="bg-gradient-to-r from-pink-400 to-rose-400 hover:from-pink-500 hover:to-rose-500 text-white p-3 rounded-xl transition-all transform hover:scale-110 shadow-lg hover:shadow-xl border-2 border-pink-300/50">
                                                <span class="text-lg">🗑️</span>
                                            </button>
                                        </form>
                                    </div>
                                </div>

                                <!-- Card Content -->
                                <div>
                                    <h3 class="text-2xl font-extrabold text-gray-800 mb-4 group-hover:text-pink-600 transition-colors">{{ $lesson['title'] }}</h3>
                                    @if($lesson['description'])
                                        <p class="text-gray-600 mb-4 text-sm line-clamp-2">{{ $lesson['description'] }}</p>
                                    @endif
                                    <div class="flex flex-wrap gap-3">
                                        <div class="bg-gradient-to-r from-cyan-50 to-teal-50 rounded-xl px-4 py-2 shadow-md border-2 border-cyan-200/50">
                                            <span class="text-cyan-700 font-bold text-sm">{{ $lesson['skills'] }} skills</span>
                                        </div>
                                        @if($lesson['duration_minutes'])
                                            <div class="bg-gradient-to-r from-pink-50 to-rose-50 rounded-xl px-4 py-2 shadow-md border-2 border-pink-200/50">
                                                <span class="text-pink-700 font-bold text-sm">⏱️ {{ $lesson['duration_minutes'] }} min</span>
                                            </div>
                                        @endif
                                    </div>
                                </div>

                                <!-- Hover Effect Overlay -->
                                <div class="absolute inset-0 bg-gradient-to-br from-pink-200/0 to-cyan-200/0 group-hover:from-pink-200/10 group-hover:to-cyan-200/10 rounded-3xl transition-all duration-300 pointer-events-none"></div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="bg-gradient-to-br from-pink-100/90 via-rose-50/80 to-cyan-100/90 backdrop-blur-lg rounded-3xl p-12 text-center border-2 border-pink-300/50 shadow-xl">
                        <div class="text-6xl mb-4">📖</div>
                        <p class="text-gray-700 text-lg font-semibold">No lessons for this level yet.</p>
                        <p class="text-gray-500 mt-2">Click "Add Lesson" above to get started!</p>
                    </div>
                @endif
            </div>
        @empty
            <div class="bg-gradient-to-br from-pink-100/90 via-rose-50/80 to-cyan-100/90 backdrop-blur-lg rounded-3xl p-12 text-center border-2 border-pink-300/50 shadow-xl">
                <div class="text-6xl mb-4">📚</div>
                <p class="text-gray-700 text-xl font-bold">No lessons available</p>
                <p class="text-gray-500 mt-2">Start by creating your first lesson!</p>
            </div>
        @endforelse
    </div>
</div>

<style>
    @keyframes float {
        0%, 100% { transform: translateY(0px); }
        50% { transform: translateY(-10px); }
    }
    
    @keyframes iconPulse {
        0%, 100% { transform: scale(1) rotate(0deg); }
        25% { transform: scale(1.05) rotate(-2deg); }
        75% { transform: scale(1.05) rotate(2deg); }
    }
    
    /* Header icon subtle animation on hover */
    .bg-gradient-to-br.from-pink-500:hover {
        animation: iconPulse 2s ease-in-out infinite;
    }
    
    /* Force icon rendering - fix for icons not appearing until hover/scroll */
    .icon-render-fix {
        will-change: transform;
        backface-visibility: hidden;
        -webkit-font-smoothing: antialiased;
        -moz-osx-font-smoothing: grayscale;
        display: inline-block;
        opacity: 1 !important;
        visibility: visible !important;
        transform: translateZ(0) scale(1);
        position: relative;
        z-index: 1;
        /* Force immediate rendering */
        -webkit-transform: translateZ(0) scale(1);
        -moz-transform: translateZ(0) scale(1);
        -ms-transform: translateZ(0) scale(1);
    }
    
    /* Hover animation */
    .group:hover .icon-render-fix {
        transform: translateZ(0) scale(1.1);
        animation: float 2s ease-in-out infinite;
    }
</style>

<script>
function editLevelName(levelId, currentName) {
    var newName = prompt('Enter new level name:', currentName);
    if (newName && newName.trim() !== '' && newName !== currentName) {
        document.getElementById('edit-level-id').value = levelId;
        document.getElementById('edit-level-name').value = newName.trim();
        document.getElementById('edit-level-name-form').submit();
    }
}

function toggleSearchSection() {
    const searchSection = document.getElementById('searchSection');
    const searchArrow = document.getElementById('searchArrow');
    
    if (searchSection.style.display === 'none') {
        searchSection.style.display = 'block';
        searchArrow.style.transform = 'rotate(0deg)';
    } else {
        searchSection.style.display = 'none';
        searchArrow.style.transform = 'rotate(180deg)';
    }
}

// Initialize: Hide search section if no search query
document.addEventListener('DOMContentLoaded', function() {
    const searchQuery = "{{ $searchQuery ?? '' }}";
    if (!searchQuery || searchQuery.trim() === '') {
        document.getElementById('searchSection').style.display = 'none';
        document.getElementById('searchArrow').style.transform = 'rotate(180deg)';
    }
});
</script>
@endsection
