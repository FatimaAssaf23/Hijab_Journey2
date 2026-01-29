@extends('layouts.app')

@section('content')
<div class="relative h-full overflow-y-auto">
    <!-- Background Image Layer - Background3 -->
    <div class="absolute inset-0" style="background-image: url('{{ asset('storage/Teacher_Dashboard/background3.jpg') }}'); background-size: cover; background-position: center center; background-repeat: no-repeat; background-attachment: fixed; opacity: 0.9;"></div>
    
    <!-- Comfortable overlay for better content readability while keeping background visible and clear -->
    <div class="absolute inset-0 bg-gradient-to-b from-white/60 via-white/40 to-white/60 pointer-events-none"></div>
    
    <!-- Animated Background Elements (very subtle) -->
    <div class="absolute inset-0 overflow-hidden pointer-events-none">
        <div class="absolute -top-40 -left-40 w-[400px] h-[400px] bg-pink-200/10 rounded-full opacity-10 blur-3xl animate-pulse"></div>
        <div class="absolute top-1/2 -right-40 w-[400px] h-[400px] bg-cyan-200/10 rounded-full opacity-10 blur-3xl animate-pulse" style="animation-delay: 1.5s;"></div>
        <div class="absolute -bottom-40 left-1/3 w-[400px] h-[400px] bg-teal-200/10 rounded-full opacity-10 blur-3xl animate-pulse" style="animation-delay: 3s;"></div>
    </div>

    <!-- Floating decorative shapes (very subtle) -->
    <div class="absolute top-20 right-20 w-32 h-32 bg-pink-200/8 rounded-full blur-3xl animate-bounce pointer-events-none" style="animation-duration: 4s;"></div>
    <div class="absolute bottom-20 left-20 w-40 h-40 bg-cyan-200/8 rounded-full blur-3xl animate-bounce pointer-events-none" style="animation-duration: 5s; animation-delay: 1s;"></div>
    <div class="absolute top-1/2 left-1/4 w-28 h-28 bg-teal-200/8 rounded-full blur-2xl animate-bounce pointer-events-none" style="animation-duration: 6s; animation-delay: 2s;"></div>

    <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 pt-8">
        <!-- Hero Header Section -->
        <div class="mb-8 animate-fade-in">
            <div class="relative w-full max-w-7xl mx-auto bg-gradient-to-br from-pink-200/80 via-rose-100/70 to-cyan-200/80 rounded-2xl shadow-xl overflow-hidden transform transition-all duration-500 hover:shadow-2xl border border-pink-200/50 backdrop-blur-sm">
                <!-- Pattern Overlay -->
                <div class="absolute inset-0 opacity-5">
                    <div class="absolute top-0 left-0 w-full h-full" style="background-image: radial-gradient(circle, rgba(255,255,255,0.4) 1px, transparent 1px); background-size: 25px 25px;"></div>
                </div>
                
                <div class="relative flex flex-col lg:flex-row items-center justify-between p-6 lg:p-8">
                    <div class="flex-1 text-center lg:text-left mb-5 lg:mb-0 z-10">
                        <div class="inline-flex items-center gap-2 bg-white/40 backdrop-blur-md px-3 py-1.5 rounded-full mb-3 border border-pink-300/30 shadow-md animate-slide-in-left">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 text-pink-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                            <span class="text-pink-700 font-semibold text-xs tracking-wide">TEACHER DASHBOARD</span>
                        </div>
                        <h1 class="text-2xl lg:text-3xl font-black text-gray-800 mb-2 tracking-tight leading-tight animate-slide-in-left" style="animation-delay: 0.1s;">
                            Welcome Back,<br>
                            <span class="bg-gradient-to-r from-pink-500 to-cyan-500 bg-clip-text text-transparent">{{ Auth::user()->first_name }}!</span> ✨
                        </h1>
                        <p class="text-sm lg:text-base text-gray-700 font-medium italic animate-slide-in-left" style="animation-delay: 0.2s;">
                            "Teaching is the art of assisting discovery."
                        </p>
                    </div>
                    
                    <!-- Right: Decorative Icon -->
                    <div class="relative flex-shrink-0 mt-5 lg:mt-0 animate-slide-in-right">
                        <div class="relative">
                            <div class="absolute inset-0 bg-pink-300/30 rounded-full blur-2xl opacity-50 animate-pulse"></div>
                            <div class="absolute inset-0 bg-cyan-300/30 rounded-full blur-xl opacity-40 animate-pulse" style="animation-delay: 1s;"></div>
                            
                            <div class="relative bg-white rounded-full p-1.5 shadow-xl transform hover:scale-105 transition-transform duration-500 border-2 border-pink-200/60">
                                <div class="absolute inset-0 bg-gradient-to-br from-pink-200/40 to-cyan-200/40 rounded-full opacity-30 blur-lg"></div>
                                <div class="relative w-48 h-48 lg:w-60 lg:h-60 rounded-full bg-gradient-to-br from-pink-300 to-cyan-300 flex items-center justify-center border border-pink-200/40 shadow-lg z-10">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-24 h-24 lg:w-32 lg:h-32 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                    </svg>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Schedule & Calendar Section - Horizontal Layout -->
        <div class="mb-6 animate-fade-in-up" style="animation-delay: 0.1s;">
            <div class="bg-white/90 backdrop-blur-md rounded-xl shadow-xl border border-purple-200/40 overflow-hidden transform transition-all duration-300 hover:shadow-2xl">
                <!-- Header -->
                <div class="bg-gradient-to-r from-purple-400 via-pink-400 to-cyan-400 p-3">
                    <div class="flex items-center justify-between flex-wrap gap-2">
                        <div class="flex items-center gap-2">
                            <div class="w-8 h-8 bg-white/20 backdrop-blur-md rounded-lg flex items-center justify-center shadow-md">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-base lg:text-lg font-black text-white">Schedule & Calendar</h3>
                                <p class="text-xs text-white/90">{{ now()->format('M d, Y') }}</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <div class="text-xs font-semibold text-white/90">{{ now()->format('F Y') }}</div>
                        </div>
                    </div>
                </div>

                <!-- Content - Horizontal Layout -->
                <div class="p-3 lg:p-4">
                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
                        <!-- Mini Calendar -->
                        <div class="lg:col-span-1">
                            <div class="mb-2">
                                <h4 class="text-xs font-bold text-gray-700 mb-2 flex items-center gap-1.5">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 text-purple-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                    Calendar
                                </h4>
                            </div>
                            <div class="grid grid-cols-7 gap-0.5 mb-1">
                                @foreach(['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'] as $day)
                                <div class="text-center text-[10px] font-bold text-gray-600 py-0.5">{{ substr($day, 0, 1) }}</div>
                                @endforeach
                            </div>
                            <div class="grid grid-cols-7 gap-0.5">
                                @php
                                    $currentMonth = now()->month;
                                    $currentYear = now()->year;
                                    $firstDay = \Carbon\Carbon::create($currentYear, $currentMonth, 1);
                                    $lastDay = $firstDay->copy()->endOfMonth();
                                    $startDate = $firstDay->copy()->startOfWeek();
                                    $endDate = $lastDay->copy()->endOfWeek();
                                    $calendarEventsArray = isset($calendarEvents) && $calendarEvents ? $calendarEvents->toArray() : [];
                                @endphp
                                @for($date = $startDate->copy(); $date <= $endDate; $date->addDay())
                                    @php
                                        $dateStr = $date->format('Y-m-d');
                                        $isCurrentMonth = $date->month == $currentMonth;
                                        $isToday = $date->isToday();
                                        $hasEvents = isset($calendarEventsArray[$dateStr]);
                                        $eventData = $hasEvents ? $calendarEventsArray[$dateStr] : null;
                                        $scheduleEvents = $eventData && isset($eventData['scheduleEvents']) ? $eventData['scheduleEvents'] : collect();
                                        $allDayEvents = $eventData && isset($eventData['events']) ? $eventData['events'] : collect();
                                        
                                        // Build tooltip text
                                        $tooltipText = '';
                                        if ($hasEvents && $allDayEvents) {
                                            $tooltipParts = [];
                                            foreach ($allDayEvents as $event) {
                                                $timeStr = isset($event['event_time']) && $event['event_time'] ? ' (' . $event['event_time'] . ')' : '';
                                                $tooltipParts[] = $event['title'] . $timeStr;
                                            }
                                            $tooltipText = implode("\n", $tooltipParts);
                                        }
                                    @endphp
                                    <div class="relative aspect-square flex items-center justify-center text-[10px] font-semibold rounded transition-all duration-200 group
                                        {{ $isCurrentMonth ? 'text-gray-800' : 'text-gray-400' }}
                                        {{ $isToday ? 'bg-gradient-to-br from-pink-400 to-cyan-400 text-white shadow-md scale-105 z-10 font-black' : '' }}
                                        {{ $hasEvents && !$isToday ? 'bg-pink-100/60 hover:bg-pink-200/80 cursor-pointer' : 'hover:bg-gray-100' }}
                                        {{ !$isCurrentMonth ? 'opacity-50' : '' }}"
                                        @if($hasEvents && $tooltipText) 
                                            data-tooltip="{{ htmlspecialchars($tooltipText) }}"
                                        @endif>
                                        <span>{{ $date->day }}</span>
                                        @if($hasEvents && !$isToday)
                                            <div class="absolute bottom-0 left-1/2 transform -translate-x-1/2 flex gap-0.5 flex-wrap justify-center max-w-full">
                                                @if(isset($eventData['assignments']) && $eventData['assignments'] > 0)
                                                    <div class="w-1 h-1 rounded-full bg-pink-500" title="Assignments"></div>
                                                @endif
                                                @if(isset($eventData['quizzes']) && $eventData['quizzes'] > 0)
                                                    <div class="w-1 h-1 rounded-full bg-cyan-500" title="Quizzes"></div>
                                                @endif
                                                @if($scheduleEvents && $scheduleEvents->count() > 0)
                                                    @foreach($scheduleEvents->take(3) as $scheduleEvent)
                                                        <div class="w-1 h-1 rounded-full" style="background-color: {{ $scheduleEvent['color'] ?? '#9333EA' }}" title="{{ $scheduleEvent['title'] ?? 'Schedule Event' }}"></div>
                                                    @endforeach
                                                    @if($scheduleEvents->count() > 3)
                                                        <span class="text-[6px] text-gray-500">+</span>
                                                    @endif
                                                @endif
                                            </div>
                                        @endif
                                        
                                        <!-- Hover Tooltip -->
                                        @if($hasEvents && $tooltipText)
                                            <div class="absolute bottom-full left-1/2 transform -translate-x-1/2 mb-2 w-56 bg-gray-900 text-white text-xs rounded-lg shadow-2xl p-2.5 z-50 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 pointer-events-none">
                                                <div class="font-bold mb-1.5 text-center border-b border-gray-700 pb-1 text-[11px]">
                                                    {{ $date->format('M d, Y') }}
                                                </div>
                                                <div class="space-y-1.5 max-h-48 overflow-y-auto custom-scrollbar">
                                                    @foreach($allDayEvents as $event)
                                                        <div class="flex items-start gap-2">
                                                            <div class="w-2 h-2 rounded-full mt-1 flex-shrink-0" style="background-color: {{ $event['type'] == 'assignment' ? '#EC4899' : ($event['type'] == 'quiz' ? '#06B6D4' : ($event['color'] ?? '#9333EA')) }}"></div>
                                                            <div class="flex-1 min-w-0">
                                                                <div class="font-semibold text-[11px] break-words leading-tight">{{ strlen($event['title']) > 30 ? substr($event['title'], 0, 30) . '...' : $event['title'] }}</div>
                                                                @if(isset($event['event_time']) && $event['event_time'])
                                                                    <div class="text-[9px] text-gray-300 mt-0.5">{{ $event['event_time'] }}</div>
                                                                @endif
                                                                @if(isset($event['description']) && $event['description'])
                                                                    <div class="text-[9px] text-gray-400 mt-0.5 break-words leading-tight">{{ strlen($event['description']) > 40 ? substr($event['description'], 0, 40) . '...' : $event['description'] }}</div>
                                                                @endif
                                                            </div>
                                                        </div>
                                                        @if(!$loop->last)
                                                            <div class="border-t border-gray-700/50 my-1"></div>
                                                        @endif
                                                    @endforeach
                                                </div>
                                                <div class="absolute bottom-0 left-1/2 transform -translate-x-1/2 translate-y-full">
                                                    <div class="w-0 h-0 border-l-[5px] border-r-[5px] border-t-[5px] border-transparent border-t-gray-900"></div>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                @endfor
                            </div>
                            <!-- Legend -->
                            <div class="flex items-center gap-3 mt-2 pt-2 border-t border-gray-200 flex-wrap">
                                <div class="flex items-center gap-1">
                                    <div class="w-1.5 h-1.5 rounded-full bg-pink-500"></div>
                                    <span class="text-[10px] text-gray-600 font-medium">Assignments</span>
                                </div>
                                <div class="flex items-center gap-1">
                                    <div class="w-1.5 h-1.5 rounded-full bg-cyan-500"></div>
                                    <span class="text-[10px] text-gray-600 font-medium">Quizzes</span>
                                </div>
                                <div class="flex items-center gap-1">
                                    <div class="w-1.5 h-1.5 rounded-full bg-purple-500"></div>
                                    <span class="text-[10px] text-gray-600 font-medium">Schedule</span>
                                </div>
                            </div>
                        </div>

                        <!-- Upcoming Events -->
                        <div class="lg:col-span-2">
                            <div class="mb-2">
                                <h4 class="text-xs font-bold text-gray-700 mb-2 flex items-center gap-1.5">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 text-pink-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                                    </svg>
                                    Upcoming Events
                                </h4>
                            </div>
                            <div class="space-y-2 max-h-48 overflow-y-auto pr-2">
                                @php
                                    $next7Days = collect();
                                    $assignmentsList = isset($upcomingAssignmentsList) ? $upcomingAssignmentsList : collect();
                                    $quizzesList = isset($upcomingQuizzesList) ? $upcomingQuizzesList : collect();
                                    $scheduleList = isset($scheduleEventsList) ? $scheduleEventsList : collect();
                                    for($i = 0; $i < 7; $i++) {
                                        $day = now()->addDays($i);
                                        $dayStr = $day->format('Y-m-d');
                                        $dayEvents = $assignmentsList->concat($quizzesList)->concat($scheduleList)
                                            ->where('date', $dayStr)
                                            ->take(5);
                                        if($dayEvents->count() > 0) {
                                            $next7Days->push([
                                                'date' => $day,
                                                'dateStr' => $dayStr,
                                                'events' => $dayEvents
                                            ]);
                                        }
                                    }
                                @endphp
                                @if($next7Days->count() > 0)
                                    @foreach($next7Days as $dayData)
                                        <div class="bg-gradient-to-r from-pink-50 via-purple-50 to-cyan-50 rounded-lg p-2.5 border border-pink-200/40 hover:shadow-sm transition-all duration-200">
                                            <div class="flex items-center justify-between mb-1.5">
                                                <div class="flex items-center gap-2">
                                                    <div class="w-7 h-7 bg-gradient-to-br from-pink-400 to-cyan-400 rounded-lg flex items-center justify-center shadow-sm">
                                                        <span class="text-white font-black text-[10px]">{{ $dayData['date']->format('d') }}</span>
                                                    </div>
                                                    <div>
                                                        <div class="text-xs font-bold text-gray-800">
                                                            {{ $dayData['date']->format('M d') }}
                                                            @if($dayData['date']->isToday())
                                                                <span class="text-pink-600 font-semibold">(Today)</span>
                                                            @elseif($dayData['date']->isTomorrow())
                                                                <span class="text-cyan-600 font-semibold">(Tomorrow)</span>
                                                            @endif
                                                        </div>
                                                        <div class="text-[10px] text-gray-500">
                                                            {{ $dayData['events']->count() }} event(s)
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="space-y-1.5">
                                                @foreach($dayData['events'] as $event)
                                                    <div class="flex items-center gap-2 bg-white/60 rounded-md p-1.5 hover:bg-white/80 transition-colors">
                                                        <div class="w-1.5 h-1.5 rounded-full {{ $event['type'] == 'assignment' ? 'bg-pink-500' : ($event['type'] == 'quiz' ? 'bg-cyan-500' : (isset($event['color']) ? '' : 'bg-purple-500')) }} flex-shrink-0" @if(isset($event['color']) && $event['type'] == 'schedule') style="background-color: {{ $event['color'] }}" @endif></div>
                                                        <div class="flex-1 min-w-0">
                                                            <div class="text-xs font-semibold text-gray-800 truncate">{{ $event['title'] }}</div>
                                                            <div class="text-[10px] text-gray-500">
                                                                {{ $event['class'] }}
                                                                @if(isset($event['event_time']) && $event['event_time'])
                                                                    • {{ $event['event_time'] }}
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endforeach
                                @else
                                    <div class="bg-gradient-to-r from-gray-50 to-gray-100 rounded-lg p-6 text-center border border-gray-200">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-gray-400 mx-auto mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                        <p class="text-xs text-gray-600 font-medium">No upcoming events</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Stats Cards Grid -->
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-3 lg:gap-4 mb-8">
            <!-- Total Classes Card -->
            <div class="bg-white/90 backdrop-blur-md rounded-xl shadow-xl p-4 lg:p-5 border border-pink-200/40 transform transition-all duration-300 hover:shadow-2xl hover:-translate-y-1 hover:scale-105 animate-fade-in-up" style="animation-delay: 0.1s;">
                <div class="flex items-center justify-between mb-3">
                    <div class="w-10 h-10 lg:w-12 lg:h-12 bg-gradient-to-br from-pink-300 to-rose-300 rounded-lg flex items-center justify-center shadow-md transform rotate-3 hover:rotate-6 transition-transform">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 lg:h-6 lg:w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                        </svg>
                    </div>
                    <div class="text-2xl lg:text-3xl opacity-20">🎓</div>
                </div>
                <div class="text-pink-600 text-[10px] lg:text-xs font-bold uppercase tracking-wider mb-1.5">Total Classes</div>
                <div class="text-2xl lg:text-3xl font-black text-gray-800 mb-1">{{ $totalClasses }}</div>
                <div class="text-[10px] lg:text-xs text-gray-600 font-medium">Active classes</div>
            </div>

            <!-- Total Students Card -->
            <div class="bg-white/90 backdrop-blur-md rounded-xl shadow-xl p-4 lg:p-5 border border-cyan-200/40 transform transition-all duration-300 hover:shadow-2xl hover:-translate-y-1 hover:scale-105 animate-fade-in-up" style="animation-delay: 0.2s;">
                <div class="flex items-center justify-between mb-3">
                    <div class="w-10 h-10 lg:w-12 lg:h-12 bg-gradient-to-br from-cyan-300 to-teal-300 rounded-lg flex items-center justify-center shadow-md transform -rotate-3 hover:-rotate-6 transition-transform">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 lg:h-6 lg:w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                    </div>
                    <div class="text-2xl lg:text-3xl opacity-20">👥</div>
                </div>
                <div class="text-cyan-600 text-[10px] lg:text-xs font-bold uppercase tracking-wider mb-1.5">Total Students</div>
                <div class="text-2xl lg:text-3xl font-black text-gray-800 mb-1">{{ $totalStudents }}</div>
                <div class="text-[10px] lg:text-xs text-gray-600 font-medium">Students across classes</div>
            </div>

            <!-- Total Assignments Card -->
            <div class="bg-white/90 backdrop-blur-md rounded-xl shadow-xl p-4 lg:p-5 border border-pink-200/40 transform transition-all duration-300 hover:shadow-2xl hover:-translate-y-1 hover:scale-105 animate-fade-in-up" style="animation-delay: 0.3s;">
                <div class="flex items-center justify-between mb-3">
                    <div class="w-10 h-10 lg:w-12 lg:h-12 bg-gradient-to-br from-pink-300 to-cyan-300 rounded-lg flex items-center justify-center shadow-md transform rotate-3 hover:rotate-6 transition-transform">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 lg:h-6 lg:w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                    </div>
                    <div class="text-2xl lg:text-3xl opacity-20">📝</div>
                </div>
                <div class="text-pink-600 text-[10px] lg:text-xs font-bold uppercase tracking-wider mb-1.5">Total Assignments</div>
                <div class="text-2xl lg:text-3xl font-black text-gray-800 mb-1">{{ $totalAssignments }}</div>
                <div class="text-[10px] lg:text-xs text-gray-600 font-medium">Assignments created</div>
            </div>

            <!-- Total Quizzes Card -->
            <div class="bg-white/90 backdrop-blur-md rounded-xl shadow-xl p-4 lg:p-5 border border-teal-200/40 transform transition-all duration-300 hover:shadow-2xl hover:-translate-y-1 hover:scale-105 animate-fade-in-up" style="animation-delay: 0.4s;">
                <div class="flex items-center justify-between mb-3">
                    <div class="w-10 h-10 lg:w-12 lg:h-12 bg-gradient-to-br from-teal-300 to-cyan-300 rounded-lg flex items-center justify-center shadow-md transform -rotate-3 hover:-rotate-6 transition-transform">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 lg:h-6 lg:w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div class="text-2xl lg:text-3xl opacity-20">❓</div>
                </div>
                <div class="text-teal-600 text-[10px] lg:text-xs font-bold uppercase tracking-wider mb-1.5">Total Quizzes</div>
                <div class="text-2xl lg:text-3xl font-black text-gray-800 mb-1">{{ $totalQuizzes }}</div>
                <div class="text-[10px] lg:text-xs text-gray-600 font-medium">Quizzes created</div>
            </div>

            <!-- Pending Assignment Grading Card -->
            <div class="bg-white/90 backdrop-blur-md rounded-xl shadow-xl p-4 lg:p-5 border border-red-200/40 transform transition-all duration-300 hover:shadow-2xl hover:-translate-y-1 hover:scale-105 animate-fade-in-up" style="animation-delay: 0.5s;">
                <div class="flex items-center justify-between mb-3">
                    <div class="w-10 h-10 lg:w-12 lg:h-12 bg-gradient-to-br from-red-400 to-orange-400 rounded-lg flex items-center justify-center shadow-md transform rotate-3 hover:rotate-6 transition-transform">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 lg:h-6 lg:w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                    </div>
                    <div class="text-2xl lg:text-3xl opacity-20">📝</div>
                </div>
                <div class="text-red-600 text-[10px] lg:text-xs font-bold uppercase tracking-wider mb-1.5">Pending Grading</div>
                <div class="text-2xl lg:text-3xl font-black text-gray-800 mb-1">{{ $pendingGrading ?? 0 }}</div>
                <div class="text-[10px] lg:text-xs text-gray-600 font-medium">Awaiting review</div>
            </div>

            <!-- Average Grade Card -->
            <div class="bg-gradient-to-br from-pink-200/90 via-rose-200/80 to-cyan-200/90 backdrop-blur-md rounded-xl shadow-xl p-4 lg:p-5 border border-pink-200/60 transform transition-all duration-300 hover:shadow-2xl hover:-translate-y-1 hover:scale-105 animate-fade-in-up" style="animation-delay: 0.6s;">
                <div class="flex items-center justify-between mb-3">
                    <div class="w-10 h-10 lg:w-12 lg:h-12 bg-white/50 backdrop-blur-md rounded-lg flex items-center justify-center shadow-md transform -rotate-3 hover:-rotate-6 transition-transform border border-white/60">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 lg:h-6 lg:w-6 text-pink-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                        </svg>
                    </div>
                    <div class="text-2xl lg:text-3xl opacity-30">📈</div>
                </div>
                <div class="text-pink-700 text-[10px] lg:text-xs font-bold uppercase tracking-wider mb-1.5">Average Grade</div>
                <div class="text-2xl lg:text-3xl font-black bg-gradient-to-r from-pink-600 to-cyan-600 bg-clip-text text-transparent mb-1">{{ number_format($averageGrade, 1) }}%</div>
                <div class="text-[10px] lg:text-xs text-gray-700 font-medium">Average across all</div>
            </div>
        </div>

        <!-- Charts Grid Section -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
            <!-- Level Lessons Chart (Existing - Resized) -->
            <div class="bg-white/90 backdrop-blur-md rounded-2xl shadow-xl p-6 border border-pink-200/40 transform transition-all duration-300 hover:shadow-2xl animate-fade-in-up" style="animation-delay: 0.7s;">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-10 h-10 bg-gradient-to-br from-pink-300 to-cyan-300 rounded-lg flex items-center justify-center shadow-md">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-black text-gray-800">Lessons by Level</h3>
                </div>
                <div class="w-full" style="position: relative; height: 300px;">
                    <canvas id="levelLessonsChart"></canvas>
                </div>
            </div>

            <!-- Student Distribution Chart (Doughnut) -->
            @if(count($classNames) > 0)
            <div class="bg-white/90 backdrop-blur-md rounded-2xl shadow-xl p-6 border border-cyan-200/40 transform transition-all duration-300 hover:shadow-2xl animate-fade-in-up" style="animation-delay: 0.8s;">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-10 h-10 bg-gradient-to-br from-cyan-300 to-teal-300 rounded-lg flex items-center justify-center shadow-md">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-black text-gray-800">Student Distribution</h3>
                </div>
                <div id="studentDistributionChart" style="height: 300px;"></div>
            </div>
            @else
            <div class="bg-white/90 backdrop-blur-md rounded-2xl shadow-xl p-6 border border-cyan-200/40 flex items-center justify-center" style="height: 300px;">
                <div class="text-center">
                    <div class="text-6xl mb-4">📊</div>
                    <p class="text-gray-600 font-medium">No classes yet</p>
                </div>
            </div>
            @endif
        </div>

        <!-- Bottom Charts Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
            <!-- Assignments & Quizzes by Class (Bar Chart) -->
            @if(count($classNames) > 0)
            <div class="bg-white/90 backdrop-blur-md rounded-2xl shadow-xl p-6 border border-pink-200/40 transform transition-all duration-300 hover:shadow-2xl animate-fade-in-up" style="animation-delay: 0.9s;">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-10 h-10 bg-gradient-to-br from-pink-300 to-cyan-300 rounded-lg flex items-center justify-center shadow-md">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-black text-gray-800">Assignments & Quizzes by Class</h3>
                </div>
                <div id="assignmentsQuizzesChart" style="height: 300px;"></div>
            </div>
            @endif

            <!-- Upcoming Scheduled Activities (Next 3 Months) -->
            @if($totalClasses > 0)
            <div class="bg-white/90 backdrop-blur-md rounded-2xl shadow-xl p-6 border border-cyan-200/40 transform transition-all duration-300 hover:shadow-2xl animate-fade-in-up" style="animation-delay: 1s;">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-10 h-10 bg-gradient-to-br from-purple-400 to-pink-400 rounded-lg flex items-center justify-center shadow-md">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-black text-gray-800">Upcoming Activities (Next 6 Months)</h3>
                </div>
                <div id="activityOverTimeChart" style="height: 300px;"></div>
            </div>
            @else
            <div class="bg-white/90 backdrop-blur-md rounded-2xl shadow-xl p-6 border border-cyan-200/40 flex items-center justify-center" style="height: 300px;">
                <div class="text-center">
                    <div class="text-6xl mb-4">📅</div>
                    <p class="text-gray-600 font-medium">No upcoming activities yet</p>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

@push('styles')
<style>
    @keyframes fade-in {
        from {
            opacity: 0;
        }
        to {
            opacity: 1;
        }
    }
    
    @keyframes fade-in-up {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    @keyframes slide-in-left {
        from {
            opacity: 0;
            transform: translateX(-20px);
        }
        to {
            opacity: 1;
            transform: translateX(0);
        }
    }
    
    @keyframes slide-in-right {
        from {
            opacity: 0;
            transform: translateX(20px);
        }
        to {
            opacity: 1;
            transform: translateX(0);
        }
    }
    
    .animate-fade-in {
        animation: fade-in 0.6s ease-out;
    }
    
    .animate-fade-in-up {
        animation: fade-in-up 0.6s ease-out forwards;
        opacity: 0;
    }
    
    .animate-slide-in-left {
        animation: slide-in-left 0.6s ease-out forwards;
    }
    
    .animate-slide-in-right {
        animation: slide-in-right 0.6s ease-out forwards;
    }
    
    /* Schedule scrollbar styling */
    .max-h-48::-webkit-scrollbar {
        width: 4px;
    }
    
    .max-h-48::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 10px;
    }
    
    .max-h-48::-webkit-scrollbar-thumb {
        background: linear-gradient(to bottom, #F472B6, #06B6D4);
        border-radius: 10px;
    }
    
    .max-h-48::-webkit-scrollbar-thumb:hover {
        background: linear-gradient(to bottom, #EC4899, #0891B2);
    }
    
    /* Custom scrollbar for tooltip */
    .custom-scrollbar::-webkit-scrollbar {
        width: 6px;
    }
    
    .custom-scrollbar::-webkit-scrollbar-track {
        background: rgba(255, 255, 255, 0.1);
        border-radius: 10px;
    }
    
    .custom-scrollbar::-webkit-scrollbar-thumb {
        background: rgba(255, 255, 255, 0.3);
        border-radius: 10px;
    }
    
    .custom-scrollbar::-webkit-scrollbar-thumb:hover {
        background: rgba(255, 255, 255, 0.5);
    }
</style>
@endpush

@push('scripts')
<!-- Chart.js for existing level lessons chart -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<!-- ApexCharts for additional charts -->
<script src="https://unpkg.com/apexcharts@3.44.0/dist/apexcharts.min.js" crossorigin="anonymous"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Existing Level Lessons Chart (Resized)
    const levelCtx = document.getElementById('levelLessonsChart');
    if (levelCtx) {
        const levelNames = @json($levelNames);
        const lessonCounts = @json($lessonCounts);
        
        new Chart(levelCtx, {
            type: 'line',
            data: {
                labels: levelNames,
                datasets: [{
                    label: 'Number of Lessons',
                    data: lessonCounts,
                    borderColor: '#6EC6C5',
                    backgroundColor: 'rgba(110, 198, 197, 0.2)',
                    borderWidth: 2,
                    fill: true,
                    tension: 0.4,
                    pointRadius: 5,
                    pointBackgroundColor: '#6EC6C5',
                    pointBorderColor: '#ffffff',
                    pointBorderWidth: 2,
                    pointHoverRadius: 7,
                    pointHoverBackgroundColor: '#197D8C',
                    pointHoverBorderColor: '#ffffff',
                    pointHoverBorderWidth: 2
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                animation: {
                    duration: 1500,
                    easing: 'easeInOutQuart'
                },
                plugins: {
                    legend: {
                        display: true,
                        position: 'top',
                        labels: {
                            font: {
                                size: 12
                            }
                        }
                    },
                    tooltip: {
                        backgroundColor: 'rgba(0, 0, 0, 0.8)',
                        padding: 12,
                        titleFont: {
                            size: 14,
                            weight: 'bold'
                        },
                        bodyFont: {
                            size: 13
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Number of Lessons',
                            font: {
                                size: 14,
                                weight: 'bold'
                            }
                        },
                        ticks: {
                            stepSize: 1,
                            precision: 0
                        },
                        grid: {
                            color: 'rgba(0, 0, 0, 0.05)'
                        }
                    },
                    x: {
                        title: {
                            display: true,
                            text: 'Level',
                            font: {
                                size: 14,
                                weight: 'bold'
                            }
                        },
                        grid: {
                            display: false
                        },
                        ticks: {
                            maxRotation: 45,
                            minRotation: 45,
                            autoSkip: false
                        }
                    }
                }
            }
        });
    }

    // Student Distribution Chart (Doughnut)
    @if(count($classNames) > 0)
    const classNames = @json($classNames);
    const studentCounts = @json($studentCountsByClass);
    
    const studentDistributionOptions = {
        series: studentCounts,
        chart: {
            type: 'donut',
            height: 300,
            animations: {
                enabled: true,
                easing: 'easeinout',
                speed: 800
            }
        },
        labels: classNames,
        colors: ['#F472B6', '#6EC6C5', '#EC769A', '#79BDBC', '#FBCFDD', '#B5D7D5', '#FFB9C6', '#67E8F9'],
        dataLabels: {
            enabled: true,
            formatter: function (val) {
                return val.toFixed(1) + "%"
            },
            style: {
                fontSize: '12px',
                fontWeight: 'bold'
            }
        },
        legend: {
            position: 'bottom',
            fontSize: '12px',
            fontFamily: 'inherit'
        },
        tooltip: {
            y: {
                formatter: function (val) {
                    return val + " students"
                }
            }
        },
        plotOptions: {
            pie: {
                donut: {
                    size: '65%',
                    labels: {
                        show: true,
                        name: {
                            show: true,
                            fontSize: '14px',
                            fontWeight: 'bold'
                        },
                        value: {
                            show: true,
                            fontSize: '16px',
                            fontWeight: 'bold',
                            formatter: function (val) {
                                return val
                            }
                        },
                        total: {
                            show: true,
                            label: 'Total Students',
                            fontSize: '14px',
                            fontWeight: 'bold',
                            formatter: function (w) {
                                return w.globals.seriesTotals.reduce((a, b) => a + b, 0)
                            }
                        }
                    }
                }
            }
        }
    };
    
    const studentDistributionChart = new ApexCharts(document.querySelector("#studentDistributionChart"), studentDistributionOptions);
    studentDistributionChart.render();
    @endif

    // Assignments & Quizzes by Class (Bar Chart)
    @if(count($classNames) > 0)
    const assignmentsData = @json($assignmentsByClass);
    const quizzesData = @json($quizzesByClass);
    
    const assignmentsQuizzesOptions = {
        series: [
            {
                name: 'Assignments',
                data: assignmentsData
            },
            {
                name: 'Quizzes',
                data: quizzesData
            }
        ],
        chart: {
            type: 'bar',
            height: 300,
            stacked: false,
            animations: {
                enabled: true,
                easing: 'easeinout',
                speed: 800
            },
            toolbar: {
                show: false
            }
        },
        plotOptions: {
            bar: {
                horizontal: false,
                columnWidth: '55%',
                borderRadius: 8,
                dataLabels: {
                    position: 'top'
                }
            }
        },
        dataLabels: {
            enabled: true,
            formatter: function (val) {
                return val
            },
            offsetY: -20,
            style: {
                fontSize: '12px',
                colors: ["#374151"]
            }
        },
        stroke: {
            show: true,
            width: 2,
            colors: ['transparent']
        },
        xaxis: {
            categories: classNames,
            labels: {
                rotate: -45,
                rotateAlways: true,
                style: {
                    fontSize: '11px'
                }
            }
        },
        yaxis: {
            title: {
                text: 'Count'
            }
        },
        fill: {
            opacity: 1,
            type: 'gradient',
            gradient: {
                shade: 'light',
                type: 'vertical',
                shadeIntensity: 0.5,
                gradientToColors: ['#F472B6', '#6EC6C5'],
                inverseColors: false,
                opacityFrom: 1,
                opacityTo: 1,
                stops: [0, 100]
            }
        },
        colors: ['#EC769A', '#79BDBC'],
        legend: {
            position: 'top',
            horizontalAlign: 'right'
        },
        tooltip: {
            y: {
                formatter: function (val) {
                    return val
                }
            }
        }
    };
    
    const assignmentsQuizzesChart = new ApexCharts(document.querySelector("#assignmentsQuizzesChart"), assignmentsQuizzesOptions);
    assignmentsQuizzesChart.render();
    @endif

    // Upcoming Scheduled Activities (Next 3 Months)
    @if($totalClasses > 0)
    const activityLabels = @json($activityOverTimeLabels ?? []);
    const upcomingAssignmentsData = @json($upcomingAssignments ?? []);
    const upcomingQuizzesData = @json($upcomingQuizzes ?? []);
    
    const activityOverTimeOptions = {
        series: [{
            name: 'Assignments Due',
            data: upcomingAssignmentsData
        }, {
            name: 'Quizzes Available',
            data: upcomingQuizzesData
        }],
        chart: {
            type: 'line',
            height: 300,
            zoom: {
                enabled: false
            },
            animations: {
                enabled: true,
                easing: 'easeinout',
                speed: 800
            },
            toolbar: {
                show: false
            }
        },
        dataLabels: {
            enabled: true,
            style: {
                fontSize: '11px',
                fontWeight: 'bold'
            }
        },
        stroke: {
            curve: 'smooth',
            width: 3
        },
        colors: ['#EC4899', '#06B6D4'],
        xaxis: {
            categories: activityLabels,
            title: {
                text: 'Upcoming Months'
            },
            labels: {
                style: {
                    fontSize: '10px'
                },
                rotate: -45,
                rotateAlways: true
            }
        },
        yaxis: {
            title: {
                text: 'Number of Activities'
            },
            labels: {
                formatter: function (val) {
                    return Math.floor(val)
                }
            }
        },
        tooltip: {
            shared: true,
            intersect: false,
            y: {
                formatter: function (val) {
                    return val + " items"
                }
            }
        },
        legend: {
            position: 'top',
            horizontalAlign: 'right',
            floating: false,
            fontSize: '12px',
            offsetY: -5
        },
        markers: {
            size: 6,
            hover: {
                size: 8
            }
        },
        grid: {
            borderColor: '#e7e7e7',
            strokeDashArray: 3
        }
    };
    
    const activityOverTimeChart = new ApexCharts(document.querySelector("#activityOverTimeChart"), activityOverTimeOptions);
    activityOverTimeChart.render();
    @endif
});
</script>
@endpush
@endsection
