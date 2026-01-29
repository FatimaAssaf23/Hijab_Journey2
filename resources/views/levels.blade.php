@extends('layouts.app')

@push('styles')
<style>
    /* Header Animation */
    @keyframes fadeInDown {
        from {
            opacity: 0;
            transform: translateY(-20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* Level Card Animation */
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

    /* Lesson Card Animation */
    @keyframes fadeInScale {
        from {
            opacity: 0;
            transform: scale(0.9);
        }
        to {
            opacity: 1;
            transform: scale(1);
        }
    }

    /* Icon Bounce Animation */
    @keyframes iconBounce {
        0%, 100% {
            transform: translateY(0);
        }
        50% {
            transform: translateY(-5px);
        }
    }

    /* Pulse Animation */
    @keyframes pulse {
        0%, 100% {
            opacity: 1;
        }
        50% {
            opacity: 0.7;
        }
    }

    /* Shimmer Effect */
    @keyframes shimmer {
        0% {
            background-position: -1000px 0;
        }
        100% {
            background-position: 1000px 0;
        }
    }

    /* Apply animations */
    .header-animate {
        animation: fadeInDown 0.6s ease-out;
    }

    .level-card {
        animation: slideInUp 0.6s ease-out;
        animation-fill-mode: both;
    }

    .lesson-card {
        animation: fadeInScale 0.5s ease-out;
        animation-fill-mode: both;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .lesson-card:hover {
        transform: translateY(-8px) scale(1.02);
        box-shadow: 0 10px 25px rgba(236, 72, 153, 0.2);
        border-color: #ec4899;
    }

    .lesson-icon {
        display: inline-block;
        animation: iconBounce 2s ease-in-out infinite;
        animation-delay: calc(var(--delay, 0) * 0.1s);
    }

    .level-card {
        transition: all 0.3s ease;
    }

    .level-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 24px rgba(236, 72, 153, 0.15);
    }

    .view-button {
        position: relative;
        overflow: hidden;
        transition: all 0.3s ease;
    }

    .view-button::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
        transition: left 0.5s;
    }

    .view-button:hover::before {
        left: 100%;
    }

    .view-button:hover {
        transform: scale(1.05);
        box-shadow: 0 6px 20px rgba(110, 198, 197, 0.4);
    }

    /* Stagger animation delays */
    .level-card:nth-child(1) { animation-delay: 0.1s; }
    .level-card:nth-child(2) { animation-delay: 0.2s; }
    .level-card:nth-child(3) { animation-delay: 0.3s; }
    .level-card:nth-child(4) { animation-delay: 0.4s; }
    .level-card:nth-child(5) { animation-delay: 0.5s; }
    .level-card:nth-child(6) { animation-delay: 0.6s; }

    .lesson-card:nth-child(1) { animation-delay: 0.1s; }
    .lesson-card:nth-child(2) { animation-delay: 0.2s; }
    .lesson-card:nth-child(3) { animation-delay: 0.3s; }
    .lesson-card:nth-child(4) { animation-delay: 0.4s; }
    .lesson-card:nth-child(5) { animation-delay: 0.5s; }
    .lesson-card:nth-child(6) { animation-delay: 0.6s; }
    .lesson-card:nth-child(7) { animation-delay: 0.7s; }
    .lesson-card:nth-child(8) { animation-delay: 0.8s; }

    /* Gradient background animation */
    .gradient-bg {
        background: linear-gradient(-45deg, #fce7f3, #fdf2f8, #fce7f3, #fdf2f8);
        background-size: 400% 400%;
        animation: gradientShift 15s ease infinite;
    }

    @keyframes gradientShift {
        0% {
            background-position: 0% 50%;
        }
        50% {
            background-position: 100% 50%;
        }
        100% {
            background-position: 0% 50%;
        }
    }

    /* Badge styles */
    .badge {
        display: inline-flex;
        align-items: center;
        gap: 0.25rem;
        padding: 0.375rem 0.75rem;
        border-radius: 9999px;
        font-size: 0.75rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }

    .badge-primary {
        background: linear-gradient(135deg, #ec4899, #f472b6);
        color: white;
        box-shadow: 0 2px 8px rgba(236, 72, 153, 0.3);
    }

    .badge-success {
        background: linear-gradient(135deg, #10b981, #059669);
        color: white;
        box-shadow: 0 2px 8px rgba(16, 185, 129, 0.3);
    }

    .badge-info {
        background: linear-gradient(135deg, #6EC6C5, #197D8C);
        color: white;
        box-shadow: 0 2px 8px rgba(110, 198, 197, 0.3);
    }

    /* Decorative elements */
    .decorative-circle {
        position: absolute;
        border-radius: 50%;
        opacity: 0.1;
        pointer-events: none;
    }

    .level-card {
        position: relative;
        overflow: hidden;
    }

    .level-card::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -50%;
        width: 200%;
        height: 200%;
        background: radial-gradient(circle, rgba(236, 72, 153, 0.1) 0%, transparent 70%);
        pointer-events: none;
    }

    /* Enhanced lesson card */
    .lesson-card {
        background: linear-gradient(135deg, #ffffff 0%, #fdf2f8 100%);
        border-left: 4px solid transparent;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .lesson-card:hover {
        border-left-color: #ec4899;
        background: linear-gradient(135deg, #ffffff 0%, #fce7f3 100%);
    }

    /* Skill tags */
    .skill-tag {
        display: inline-block;
        padding: 0.25rem 0.5rem;
        background: rgba(236, 72, 153, 0.1);
        color: #ec4899;
        border-radius: 0.375rem;
        font-size: 0.625rem;
        font-weight: 600;
        margin: 0.125rem;
    }

    /* Stats card */
    .stats-card {
        background: linear-gradient(135deg, rgba(236, 72, 153, 0.1), rgba(110, 198, 197, 0.1));
        border-radius: 1rem;
        padding: 1rem;
        backdrop-filter: blur(10px);
    }

    /* Decorative divider */
    .divider {
        height: 2px;
        background: linear-gradient(90deg, transparent, #ec4899, transparent);
        margin: 1.5rem 0;
        border-radius: 2px;
    }

    /* Floating particles effect */
    @keyframes float {
        0%, 100% {
            transform: translateY(0) rotate(0deg);
        }
        50% {
            transform: translateY(-20px) rotate(180deg);
        }
    }

    .floating-icon {
        animation: float 6s ease-in-out infinite;
    }

    /* Glow effect */
    .glow-effect {
        box-shadow: 0 0 20px rgba(236, 72, 153, 0.3);
    }

    .level-card:hover .glow-effect {
        box-shadow: 0 0 30px rgba(236, 72, 153, 0.5);
    }

    /* Line clamp utility */
    .line-clamp-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    /* Enhanced header styling */
    .header-animate {
        backdrop-filter: blur(10px);
        background: linear-gradient(135deg, rgba(252, 231, 243, 0.9), rgba(253, 242, 248, 0.9));
    }

    /* Character image - circular and mini */
    .character-image-circular {
        width: 100px !important;
        height: 100px !important;
        border-radius: 50% !important;
        object-fit: cover !important;
        object-position: center;
        border: 3px solid rgba(236, 72, 153, 0.3);
        box-shadow: 0 4px 15px rgba(236, 72, 153, 0.2);
        transition: all 0.3s ease;
        background: linear-gradient(135deg, rgba(252, 231, 243, 0.9), rgba(253, 242, 248, 0.9));
        padding: 2px;
        display: block;
    }

    .character-image-circular:hover {
        transform: scale(1.1);
        box-shadow: 0 6px 20px rgba(236, 72, 153, 0.3);
        border-color: rgba(236, 72, 153, 0.5);
    }

    @media (max-width: 640px) {
        .character-image-circular {
            width: 70px !important;
            height: 70px !important;
        }
    }
</style>
@endpush

@section('content')
<div class="relative min-h-screen py-8 px-4" style="background: linear-gradient(135deg, #FFF4FA 0%, #FDF2F8 50%, #F0F9FF 100%);">
    <!-- Decorative Background Elements -->
    <div class="fixed inset-0 overflow-hidden pointer-events-none z-0">
        <div class="absolute top-20 left-10 w-72 h-72 bg-pink-200/20 rounded-full blur-3xl floating-icon" style="animation-delay: 0s;"></div>
        <div class="absolute top-60 right-20 w-96 h-96 bg-cyan-200/20 rounded-full blur-3xl floating-icon" style="animation-delay: 2s;"></div>
        <div class="absolute bottom-20 left-1/4 w-80 h-80 bg-purple-200/20 rounded-full blur-3xl floating-icon" style="animation-delay: 4s;"></div>
    </div>
    
    <div class="w-full max-w-full mx-auto relative z-10 px-4 sm:px-6 lg:px-8">
    <!-- Header Section with Go Back Button and Title -->
    <div class="header-animate bg-gradient-to-r from-pink-100 via-pink-50 to-pink-100 rounded-2xl shadow-xl p-6 mb-8 border-2 border-pink-200 gradient-bg relative overflow-hidden">
        <!-- Decorative elements -->
        <div class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-br from-pink-300/20 to-transparent rounded-bl-full"></div>
        <div class="absolute bottom-0 left-0 w-24 h-24 bg-gradient-to-tr from-cyan-300/20 to-transparent rounded-tr-full"></div>
        
        <div class="flex flex-col sm:flex-row items-start sm:items-center gap-4 relative z-10">
            <a href="{{ route('student.dashboard') }}" class="inline-flex items-center gap-2 bg-white hover:bg-pink-50 text-pink-600 px-5 py-3 rounded-xl font-bold shadow-md hover:shadow-xl transition-all duration-300 border-2 border-pink-200 hover:border-pink-300 hover:scale-105 hover:-translate-y-1 backdrop-blur-sm">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Go Back
            </a>
            <div class="flex-1 flex items-center gap-4">
                <div class="flex-1">
                    <div class="flex items-center gap-3 flex-wrap">
                        <div class="flex items-center gap-2">
                            <span class="text-4xl floating-icon" style="animation-delay: 0s;">🎓</span>
                            <h1 class="text-3xl sm:text-4xl font-extrabold bg-gradient-to-r from-pink-600 to-pink-800 bg-clip-text text-transparent">Levels</h1>
                        </div>
                        <span class="badge badge-primary animate-pulse">
                            <span>📚</span>
                            <span>{{ $levels->count() }} Levels</span>
                        </span>
                    </div>
                    <p class="text-sm text-gray-600 mt-2 flex items-center gap-2">
                        <span class="floating-icon">✨</span>
                        <span>Browse lessons by level and start your learning journey</span>
                        <span class="floating-icon" style="animation-delay: 1s;">🚀</span>
                    </p>
                </div>
                <!-- Character Image - Circular and Mini -->
                <div class="flex-shrink-0">
                    <img src="{{ asset('storage/levels_page_design/hijab9.jpg') }}" 
                         alt="Learning Character" 
                         class="character-image-circular">
                </div>
            </div>
        </div>
    </div>

    <!-- Error Message Display -->
    @if(session('error'))
        <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-500 rounded-lg shadow-md">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-red-700 font-semibold">{{ session('error') }}</p>
                </div>
            </div>
        </div>
    @endif

    <!-- Statistics Section -->
    @php
        // Lessons are already filtered by ClassLessonVisibility in the route
        $totalLessons = $levels->sum(function($level) {
            return $level->lessons->count();
        });
    @endphp
    <div class="stats-card mb-8 level-card" style="animation-delay: 0.05s;">
        <div class="flex flex-wrap items-center justify-center gap-6 text-center">
            <div class="flex-1 min-w-[150px]">
                <div class="text-3xl font-extrabold text-pink-600 mb-1">{{ $levels->count() }}</div>
                <div class="text-sm text-gray-600 font-semibold">Total Levels</div>
            </div>
            <div class="w-px h-12 bg-pink-200"></div>
            <div class="flex-1 min-w-[150px]">
                <div class="text-3xl font-extrabold text-cyan-600 mb-1">{{ $totalLessons }}</div>
                <div class="text-sm text-gray-600 font-semibold">Available Lessons</div>
            </div>
            <div class="w-px h-12 bg-pink-200"></div>
            <div class="flex-1 min-w-[150px]">
                <div class="text-3xl font-extrabold text-green-600 mb-1">🎯</div>
                <div class="text-sm text-gray-600 font-semibold">Start Learning</div>
            </div>
        </div>
    </div>
    @foreach($levels as $levelIndex => $level)
        @php
            // Lessons are already filtered by ClassLessonVisibility in the route
            $lessonCount = $level->lessons->count();
        @endphp
        <div class="level-card mb-8 p-6 rounded-xl shadow-lg bg-gradient-to-br from-pink-50 via-white to-pink-50 border-2 border-pink-200 glow-effect">
            <!-- Level Header with Badge -->
            <div class="flex items-start justify-between mb-4">
                <div class="flex-1">
                    <div class="flex items-center gap-3 mb-2">
                        <span class="text-3xl floating-icon" style="animation-delay: {{ $levelIndex * 0.2 }}s;">🎯</span>
                        <h2 class="text-2xl font-extrabold text-pink-700">{{ $level->level_name }}</h2>
                        <span class="badge badge-info">
                            <span>📖</span>
                            <span>{{ $lessonCount }} {{ $lessonCount === 1 ? 'Lesson' : 'Lessons' }}</span>
                        </span>
                    </div>
                    <p class="text-gray-700 mb-3 ml-11 text-base leading-relaxed">{{ $level->description }}</p>
                </div>
            </div>
            
            <!-- Decorative Divider -->
            <div class="divider"></div>
            
            <!-- Lessons Grid -->
            <div class="flex flex-wrap gap-4">
                @foreach($level->lessons as $lessonIndex => $lesson)
                    {{-- Lessons are already filtered by ClassLessonVisibility in the route --}}
                        @php
                            // Check prerequisites for this lesson
                            $prerequisitesMet = true;
                            $prerequisiteMessage = null;
                            if (isset($student) && $student) {
                                \Log::info("DEBUG VIEW: Checking prerequisites for lesson display. Lesson ID: {$lesson->lesson_id}, Lesson Title: '{$lesson->title}', Lesson Order: {$lesson->lesson_order}, Level ID: {$lesson->level_id}, Student ID: {$student->student_id}");
                                
                                $prerequisiteStatus = $lesson->getPrerequisiteStatus($student->student_id, 60);
                                $prerequisitesMet = $prerequisiteStatus['met'];
                                $prerequisiteMessage = $prerequisiteStatus['message'];
                                
                                \Log::info("DEBUG VIEW: Prerequisite check result for lesson {$lesson->lesson_id} - Met: " . ($prerequisitesMet ? 'true' : 'false') . ", Message: " . ($prerequisiteMessage ?? 'null'));
                            }
                        @endphp
                        <div class="lesson-card bg-white border border-gray-200 rounded-xl p-5 shadow-md w-64 relative overflow-hidden {{ !$prerequisitesMet ? 'opacity-75' : '' }}">
                            @if(!$prerequisitesMet)
                                <!-- Lock Overlay -->
                                <div class="absolute inset-0 bg-gray-100/80 rounded-xl z-20 flex items-center justify-center">
                                    <div class="text-center p-4">
                                        <div class="text-4xl mb-2">🔒</div>
                                        <p class="text-xs text-gray-600 font-semibold">Locked</p>
                                    </div>
                                </div>
                            @endif
                            
                            <!-- Decorative corner accent -->
                            <div class="absolute top-0 right-0 w-20 h-20 bg-gradient-to-br from-pink-200/30 to-transparent rounded-bl-full"></div>
                            
                            <!-- Lesson Header -->
                            <div class="flex items-start gap-3 mb-3 relative z-10">
                                <div class="flex-shrink-0">
                                    <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-pink-100 to-cyan-100 flex items-center justify-center shadow-sm">
                                        <span class="text-2xl lesson-icon" style="--delay: {{ $lessonIndex }};">{{ $lesson->icon ?? '📘' }}</span>
                                    </div>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <h3 class="font-bold text-gray-800 text-base mb-1 leading-tight">{{ $lesson->title }}</h3>
                                    <div class="flex items-center gap-2 flex-wrap">
                                        <span class="skill-tag">
                                            <span>⭐</span> {{ $lesson->skills }} Skills
                                        </span>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Lesson Description -->
                            <div class="text-gray-600 text-sm mb-4 line-clamp-2 relative z-10">
                                {{ $lesson->description }}
                            </div>
                            
                            @if(!$prerequisitesMet && $prerequisiteMessage)
                                <!-- Prerequisite Warning -->
                                <div class="mb-4 p-3 bg-yellow-50 border border-yellow-200 rounded-lg relative z-10">
                                    <p class="text-xs text-yellow-800 font-semibold">{{ $prerequisiteMessage }}</p>
                                    <p class="text-xs text-gray-600 mt-1">Check Laravel logs for detailed debugging information.</p>
                                    @if(isset($student) && $student)
                                        <a href="/debug/lesson-unlock/{{ $lesson->lesson_id }}" target="_blank" class="text-xs text-blue-600 underline mt-2 inline-block">🔍 Debug Info</a>
                                    @endif
                                </div>
                            @endif
                            
                            <!-- Lesson Meta Info -->
                            <div class="flex items-center justify-between mb-4 pb-4 border-b border-gray-100 relative z-10">
                                <div class="flex items-center gap-2 text-xs text-gray-500">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <span>{{ $lesson->duration_minutes ?? '-' }} min</span>
                                </div>
                                <div class="flex items-center gap-1">
                                    <span class="text-xs text-pink-500 font-semibold">📚</span>
                                </div>
                            </div>
                            
                            <!-- Action Button -->
                            <div class="relative z-10">
                                @if($prerequisitesMet)
                                    <a href="/lessons/{{ $lesson->lesson_id }}/view" class="view-button w-full inline-flex items-center justify-center gap-2 px-4 py-3 rounded-xl bg-gradient-to-r from-[#6EC6C5] to-[#197D8C] text-white font-bold hover:from-[#197D8C] hover:to-[#6EC6C5] transition-all duration-300 shadow-lg hover:shadow-xl transform hover:scale-105">
                                        <span class="text-lg">📖</span>
                                        <span>Start Lesson</span>
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                        </svg>
                                    </a>
                                @else
                                    <button disabled class="w-full inline-flex items-center justify-center gap-2 px-4 py-3 rounded-xl bg-gray-300 text-gray-500 font-bold cursor-not-allowed">
                                        <span class="text-lg">🔒</span>
                                        <span>Locked</span>
                                    </button>
                                @endif
                            </div>
                        </div>
                @endforeach
            </div>
        </div>
    @endforeach
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Add scroll-triggered animations
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };

        const observer = new IntersectionObserver(function(entries) {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.style.opacity = '1';
                    entry.target.style.transform = 'translateY(0)';
                }
            });
        }, observerOptions);

        // Observe all lesson cards
        document.querySelectorAll('.lesson-card').forEach(card => {
            observer.observe(card);
        });

        // Add click ripple effect to lesson cards
        document.querySelectorAll('.lesson-card').forEach(card => {
            card.addEventListener('click', function(e) {
                if (e.target.tagName !== 'A') {
                    const ripple = document.createElement('span');
                    const rect = this.getBoundingClientRect();
                    const size = Math.max(rect.width, rect.height);
                    const x = e.clientX - rect.left - size / 2;
                    const y = e.clientY - rect.top - size / 2;
                    
                    ripple.style.width = ripple.style.height = size + 'px';
                    ripple.style.left = x + 'px';
                    ripple.style.top = y + 'px';
                    ripple.style.position = 'absolute';
                    ripple.style.borderRadius = '50%';
                    ripple.style.background = 'rgba(236, 72, 153, 0.3)';
                    ripple.style.transform = 'scale(0)';
                    ripple.style.animation = 'ripple 0.6s ease-out';
                    ripple.style.pointerEvents = 'none';
                    
                    this.style.position = 'relative';
                    this.style.overflow = 'hidden';
                    this.appendChild(ripple);
                    
                    setTimeout(() => ripple.remove(), 600);
                }
            });
        });

        // Add parallax effect to header on scroll
        let lastScroll = 0;
        const header = document.querySelector('.header-animate');
        
        window.addEventListener('scroll', function() {
            const currentScroll = window.pageYOffset;
            if (header) {
                if (currentScroll > lastScroll && currentScroll > 50) {
                    header.style.transform = 'translateY(-10px)';
                    header.style.opacity = '0.95';
                } else {
                    header.style.transform = 'translateY(0)';
                    header.style.opacity = '1';
                }
            }
            lastScroll = currentScroll;
        });

        // Add hover sound effect simulation (visual feedback)
        document.querySelectorAll('.view-button').forEach(button => {
            button.addEventListener('mouseenter', function() {
                this.style.transform = 'scale(1.1) rotate(2deg)';
            });
            button.addEventListener('mouseleave', function() {
                this.style.transform = 'scale(1) rotate(0deg)';
            });
        });
    });
</script>
<style>
    @keyframes ripple {
        to {
            transform: scale(4);
            opacity: 0;
        }
    }
</style>
@endpush
@endsection
