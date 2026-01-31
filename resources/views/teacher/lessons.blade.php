@extends('layouts.app')

@section('content')
<div class="mt-6 mb-8 relative overflow-hidden rounded-2xl shadow-lg border border-pink-100/60 backdrop-blur-sm bg-gradient-to-br from-pink-50/90 via-rose-50/80 to-cyan-50/90">
    <!-- Decorative Elements -->
    <div class="absolute top-0 right-0 w-64 h-64 bg-pink-200/15 rounded-full blur-3xl -mr-32 -mt-32"></div>
    <div class="absolute bottom-0 left-0 w-48 h-48 bg-cyan-200/15 rounded-full blur-3xl -ml-24 -mb-24"></div>
    
    <div class="relative w-full px-4 sm:px-6 lg:px-8 py-8 flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
        <div class="flex items-center gap-4 flex-1">
            <!-- Go Back Button -->
            <a href="{{ route('teacher.dashboard') }}" 
               class="flex items-center gap-2 px-6 py-3 rounded-xl font-semibold text-pink-600 bg-white/95 shadow-sm hover:shadow-md transition-all duration-300 transform hover:scale-105 border border-pink-200/50">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                <span class="hidden sm:inline">Go Back</span>
            </a>
            
            <div class="flex items-center gap-4">
                <div class="bg-white/70 backdrop-blur-md rounded-2xl p-3 shadow-sm border border-pink-200/50">
                    <span class="text-4xl">📚</span>
                </div>
                <div>
                    <h1 class="text-4xl sm:text-5xl font-bold text-pink-700 mb-2">Lessons Management</h1>
                    <p class="text-pink-500 text-base font-semibold">Manage lesson visibility and track student progress</p>
                </div>
            </div>
        </div>
        
        <div>
            @if($hasSchedule)
                <a href="{{ route('teacher.schedule.show') }}" 
                   class="flex items-center gap-2 px-6 py-3 rounded-xl font-semibold text-pink-600 bg-white/95 shadow-sm hover:shadow-md transition-all duration-300 transform hover:scale-105 border border-pink-200/50">
                    <span class="text-xl">📅</span>
                    <span class="hidden sm:inline">View Schedule</span>
                    <span class="sm:hidden">Schedule</span>
                </a>
            @endif
        </div>
    </div>
</div>

@if(!$hasSchedule)
<div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 mb-6 rounded-lg">
    <div class="flex items-center">
        <div class="flex-shrink-0">
            <span class="text-2xl">💡</span>
        </div>
        <div class="ml-3">
            <p class="text-sm text-yellow-700">
                <strong>Tip:</strong> Your schedule will be automatically generated when you unlock your first lesson for a class.
            </p>
        </div>
    </div>
</div>
@endif

<div class="w-full min-h-screen px-4 sm:px-6 lg:px-8 py-5" x-data="lessonManager()">
    <!-- Success/Error Messages -->
    @if (session('success'))
        <div class="mb-6 bg-gradient-to-r from-green-50 to-emerald-50 border-l-4 border-green-500 text-green-800 px-6 py-4 rounded-xl shadow-lg animate-fade-in">
            <div class="flex items-center gap-3">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span class="font-semibold">{{ session('success') }}</span>
            </div>
        </div>
    @endif

    @if (session('error'))
        <div class="mb-6 bg-gradient-to-r from-red-50 to-rose-50 border-l-4 border-red-500 text-red-800 px-6 py-4 rounded-xl shadow-lg animate-fade-in">
            <div class="flex items-center gap-3">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
                <span class="font-semibold">{{ session('error') }}</span>
            </div>
        </div>
    @endif

    @if (session('info'))
        <div class="mb-6 bg-gradient-to-r from-blue-50 to-cyan-50 border-l-4 border-blue-500 text-blue-800 px-6 py-4 rounded-xl shadow-lg animate-fade-in">
            <div class="flex items-center gap-3">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span class="font-semibold">{{ session('info') }}</span>
            </div>
        </div>
    @endif

    <!-- Search and Filter Bar -->
    <div class="relative rounded-2xl shadow-lg p-6 mb-8 overflow-hidden border border-pink-100/60 bg-white/95 backdrop-blur-md">
        <!-- Decorative Background Elements -->
        <div class="absolute top-0 right-0 w-40 h-40 bg-pink-200/10 rounded-full blur-2xl -mr-20 -mt-20"></div>
        <div class="absolute bottom-0 left-0 w-32 h-32 bg-cyan-200/10 rounded-full blur-2xl -ml-16 -mb-16"></div>
        
        <div class="relative flex flex-col md:flex-row gap-4 items-center">
            <div class="flex-1 w-full">
                <div class="relative">
                    <input type="text" 
                           x-model="searchQuery" 
                           @input="filterLessons()"
                           placeholder="Search lessons by title, description, or level..." 
                           class="w-full px-4 py-3 pl-12 rounded-xl border-2 border-pink-200/40 bg-white focus:border-pink-300 focus:ring-2 focus:ring-pink-300/20 focus:outline-none transition-all shadow-sm text-pink-700 placeholder-pink-300">
                    <svg class="absolute left-4 top-1/2 transform -translate-y-1/2 h-5 w-5 text-pink-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </div>
            </div>
            <div class="flex gap-3">
                <select x-model="filterLevel" @change="filterLessons()" class="px-4 py-3 rounded-xl border-2 border-pink-200/40 bg-white focus:border-pink-300 focus:ring-2 focus:ring-pink-300/20 focus:outline-none transition-all shadow-sm text-pink-700 font-medium cursor-pointer hover:bg-pink-50">
                    <option value="">All Levels</option>
                    @foreach($levels as $level)
                        <option value="{{ $level->level_id }}">Level {{ $level->level_number ?? $level->level_id }}</option>
                    @endforeach
                </select>
                <select x-model="filterVisibility" @change="filterLessons()" class="px-4 py-3 rounded-xl border-2 border-pink-200/40 bg-white focus:border-pink-300 focus:ring-2 focus:ring-pink-300/20 focus:outline-none transition-all shadow-sm text-pink-700 font-medium cursor-pointer hover:bg-pink-50">
                    <option value="">All Lessons</option>
                    <option value="visible">Visible Only</option>
                    <option value="hidden">Hidden Only</option>
                </select>
            </div>
        </div>
        <div class="relative mt-4 text-sm text-pink-600 font-semibold" x-show="filteredCount !== null">
            Showing <span x-text="filteredCount" class="font-bold text-lg text-pink-500"></span> lesson(s)
        </div>
    </div>

    <!-- Lessons by Level -->
    @foreach($levels as $level)
        <div class="mb-8 w-full" 
             x-data="{ showLessons: true }"
             x-show="shouldShowLevel({{ $level->level_id }})"
             x-transition>
            <div class="rounded-2xl px-6 py-4 mb-4 flex flex-col md:flex-row items-start md:items-center justify-between gap-4 bg-gradient-to-r from-pink-200/70 via-rose-200/60 to-cyan-200/70 shadow-lg border border-pink-200/50">
                <div class="flex-1">
                    <h2 class="text-2xl font-extrabold mb-1 text-pink-700">Level {{ $level->level_number ?? $level->level_id }}</h2>
                    <div class="text-base text-pink-600 mb-1">{{ $level->level_name }}</div>
                    <div class="text-sm text-pink-500">{{ $level->description ?? 'Curriculum' }} &bull; {{ $level->lessons->count() }} lessons</div>
                </div>
                <button @click="showLessons = !showLessons" 
                        type="button" 
                        class="px-4 py-2 rounded-xl font-semibold text-pink-700 bg-white/80 backdrop-blur-sm hover:bg-white/90 transition shadow-md border border-pink-200/60" 
                        x-text="showLessons ? 'Hide Lessons' : 'Show Lessons'">
                </button>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6" 
                 x-show="showLessons" 
                 x-transition>
                @forelse($level->lessons as $lesson)
                    @php
                        $stats = $lessonStats[$lesson->lesson_id] ?? [];
                        $visibleCount = $lesson->classLessonVisibilities->where('is_visible', true)->count();
                    @endphp
                    <div class="lesson-card rounded-2xl shadow-lg p-6 flex flex-col gap-3 w-full transform transition-all duration-300 hover:scale-105 hover:shadow-xl bg-white/90 backdrop-blur-sm border border-pink-200/40"
                         x-show="shouldShowLesson('{{ strtolower($lesson->title) }}', '{{ strtolower($lesson->description) }}', {{ $level->level_id }}, {{ $visibleCount > 0 ? 'true' : 'false' }})"
                         x-transition>
                        <!-- Lesson Header -->
                        <div class="flex items-center gap-3 mb-2">
                            <span class="text-4xl">{{ $lesson->icon ?? '📘' }}</span>
                            <div class="flex-1 min-w-0">
                                <div class="font-bold text-lg text-pink-700 truncate">{{ $lesson->title }}</div>
                                <div class="text-xs text-pink-500">Duration: {{ ($lesson->duration_minutes === null || $lesson->duration_minutes == 0) ? '0' : $lesson->duration_minutes }} min</div>
                            </div>
                        </div>
                        
                        <!-- Description -->
                        <div class="text-gray-700 text-sm mb-2 line-clamp-2">{{ $lesson->description }}</div>
                        
                        <!-- Statistics Badge -->
                        @if(!empty($stats) && $stats['total_students'] > 0)
                            <div class="bg-white/60 rounded-lg p-3 mb-2">
                                <div class="grid grid-cols-2 gap-2 text-xs">
                                    <div>
                                        <div class="text-gray-600">Students</div>
                                        <div class="font-bold text-pink-500">{{ $stats['total_students'] }}</div>
                                    </div>
                                    <div>
                                        <div class="text-gray-600">Completed</div>
                                        <div class="font-bold text-green-600">{{ $stats['completed_count'] }}</div>
                                    </div>
                                </div>
                                @if($stats['completion_rate'] > 0)
                                    <div class="mt-2">
                                        <div class="flex justify-between text-xs mb-1">
                                            <span class="text-gray-600">Completion</span>
                                            <span class="font-bold">{{ $stats['completion_rate'] }}%</span>
                                        </div>
                                        <div class="w-full bg-gray-200 rounded-full h-2">
                                            <div class="bg-green-500 h-2 rounded-full transition-all duration-300" 
                                                 style="width: {{ $stats['completion_rate'] }}%"></div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        @else
                            <div class="bg-white/60 rounded-lg p-3 mb-2 text-center text-xs text-gray-500">
                                No student progress yet
                            </div>
                        @endif
                        
                        <!-- Class Visibility -->
                        <div class="mb-2">
                            <div class="font-semibold text-sm text-pink-700 mb-2 flex items-center gap-2">
                                <span>Class Visibility:</span>
                                <span class="text-xs bg-white/60 px-2 py-1 rounded">
                                    {{ $visibleCount }}/{{ $classes->count() }} visible
                                </span>
                            </div>
                            <ul class="space-y-2 max-h-32 overflow-y-auto">
                                @foreach($classes as $class)
                                    @php
                                        $visibility = $lesson->classLessonVisibilities->firstWhere('class_id', $class->class_id);
                                        $isVisible = $visibility && $visibility->is_visible;
                                    @endphp
                                    <li class="flex items-center justify-between gap-2 bg-white/40 rounded-lg p-2">
                                        <span class="text-gray-800 text-sm font-medium truncate flex-1">{{ $class->class_name }}</span>
                                        <form method="POST" 
                                              action="{{ $isVisible ? route('teacher.lessons.lock', $lesson->lesson_id) : route('teacher.lessons.unlock', $lesson->lesson_id) }}" 
                                              class="inline"
                                              onsubmit="return confirm('{{ $isVisible ? 'Hide' : 'Show' }} this lesson for {{ $class->class_name }}?')">
                                            @csrf
                                            <input type="hidden" name="class_id" value="{{ $class->class_id }}">
                                            <button type="submit" 
                                                    class="px-3 py-1 rounded text-xs font-bold focus:outline-none transition-colors duration-200 min-w-[60px]
                                                    {{ $isVisible
                                                        ? 'bg-pink-300 text-pink-800 hover:bg-pink-400'
                                                        : 'bg-cyan-300 text-cyan-800 hover:bg-cyan-400' }}">
                                                {{ $isVisible ? '🔒 Hide' : '🔓 Show' }}
                                            </button>
                                        </form>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                        
                        <!-- Action Buttons -->
                        <div class="flex gap-2 mt-auto">
                            <a href="{{ route('teacher.lessons.view', $lesson->lesson_id) }}" 
                               target="_blank" 
                               title="View lesson content" 
                               class="flex-1 flex items-center justify-center gap-2 px-4 py-2 rounded-lg bg-gradient-to-r from-pink-300 to-cyan-300 text-pink-800 font-bold hover:from-pink-400 hover:to-cyan-400 transition text-sm shadow-sm">
                                <span class="text-lg">📖</span> View
                            </a>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full text-gray-400 italic text-center py-8">No lessons in this level.</div>
                @endforelse
            </div>
        </div>
    @endforeach
    
    @if($levels->isEmpty())
        <div class="text-center py-16">
            <span class="text-6xl mb-4 block">📚</span>
            <h3 class="text-2xl font-bold text-gray-700 mb-2">No Lessons Available</h3>
            <p class="text-gray-500">Lessons will appear here once they are added to the system.</p>
        </div>
    @endif
</div>

<script>
function lessonManager() {
    return {
        searchQuery: '',
        filterLevel: '',
        filterVisibility: '',
        filteredCount: null,
        
        filterLessons() {
            // Count visible lessons
            const lessonCards = document.querySelectorAll('.lesson-card');
            let visibleCount = 0;
            
            lessonCards.forEach(card => {
                const title = card.querySelector('.font-bold').textContent.toLowerCase();
                const description = card.querySelector('.text-gray-700')?.textContent.toLowerCase() || '';
                const levelId = card.closest('[x-data*="showLessons"]').getAttribute('x-show')?.includes('level') ? 
                    card.closest('[x-data*="showLessons"]').getAttribute('x-show').match(/\d+/)?.[0] : '';
                const isVisible = card.textContent.includes('visible') && 
                    card.querySelector('button')?.textContent.includes('Hide');
                
                const matchesSearch = !this.searchQuery || 
                    title.includes(this.searchQuery.toLowerCase()) || 
                    description.includes(this.searchQuery.toLowerCase());
                const matchesLevel = !this.filterLevel || levelId === this.filterLevel;
                const matchesVisibility = !this.filterVisibility || 
                    (this.filterVisibility === 'visible' && isVisible) ||
                    (this.filterVisibility === 'hidden' && !isVisible);
                
                if (matchesSearch && matchesLevel && matchesVisibility) {
                    visibleCount++;
                }
            });
            
            this.filteredCount = visibleCount;
        },
        
        shouldShowLevel(levelId) {
            if (this.filterLevel && String(this.filterLevel) !== String(levelId)) {
                return false;
            }
            return true;
        },
        
        shouldShowLesson(title, description, levelId, isVisible) {
            const searchLower = this.searchQuery.toLowerCase();
            const matchesSearch = !this.searchQuery || 
                title.includes(searchLower) || 
                description.includes(searchLower);
            const matchesLevel = !this.filterLevel || String(this.filterLevel) === String(levelId);
            const matchesVisibility = !this.filterVisibility || 
                (this.filterVisibility === 'visible' && isVisible) ||
                (this.filterVisibility === 'hidden' && !isVisible);
            
            return matchesSearch && matchesLevel && matchesVisibility;
        }
    }
}
</script>

<style>
.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
</style>
@endsection
