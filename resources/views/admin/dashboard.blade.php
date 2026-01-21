@extends('layouts.admin')

@section('content')
<div class="min-h-screen">
    <!-- Header -->
    <div class="bg-gradient-to-r from-[#FC8EAC] via-[#EC769A] to-[#6EC6C5] shadow-xl">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-4xl font-extrabold text-white mb-2">Admin Dashboard</h1>
                    <p class="text-pink-100">Manage lessons, classes, and teacher assignments</p>
                </div>
                
                <!-- Notification Bell -->
                @if($unreadRequestsCount > 0)
                <div class="relative" x-data="{ open: false }">
                    <button @click="open = !open" class="relative bg-white/20 hover:bg-white/30 p-3 rounded-full transition-all">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                        </svg>
                        <span class="absolute -top-1 -right-1 bg-red-500 text-white text-xs font-bold w-5 h-5 flex items-center justify-center rounded-full animate-pulse">
                            {{ $unreadRequestsCount }}
                        </span>
                    </button>
                    
                    <!-- Dropdown -->
                    <div x-show="open" @click.away="open = false" x-transition 
                         class="absolute right-0 mt-2 w-80 bg-white rounded-xl shadow-2xl border border-gray-100 z-50 overflow-hidden">
                        <div class="bg-gradient-to-r from-pink-500 to-teal-500 px-4 py-3">
                            <h3 class="text-white font-semibold">New Teacher Requests</h3>
                        </div>
                        <div class="max-h-64 overflow-y-auto">
                            @foreach($unreadRequests as $request)
                            <a href="{{ route('admin.requests') }}" class="block px-4 py-3 hover:bg-gray-50 border-b border-gray-100 transition-colors">
                                <div class="flex items-start gap-3">
                                    <div class="w-10 h-10 bg-gradient-to-br from-pink-400 to-teal-400 rounded-full flex items-center justify-center text-white font-bold text-sm flex-shrink-0">
                                        {{ substr($request->full_name ?? 'U', 0, 1) }}
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="font-semibold text-gray-800 truncate">{{ $request->full_name ?? 'Unknown' }}</p>
                                        <p class="text-sm text-gray-500 truncate">{{ $request->specialization ?? 'Teacher Application' }}</p>
                                        <p class="text-xs text-gray-400 mt-1">{{ $request->created_at->diffForHumans() }}</p>
                                    </div>
                                    <span class="bg-yellow-100 text-yellow-700 text-xs px-2 py-1 rounded-full font-medium">New</span>
                                </div>
                            </a>
                            @endforeach
                        </div>
                        <a href="{{ route('admin.requests') }}" class="block px-4 py-3 text-center text-pink-600 hover:bg-pink-50 font-medium transition-colors">
                            View All Requests →
                        </a>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- New Teacher Request Alert Banner -->
    @if($unreadRequestsCount > 0)
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-6">
        <div class="bg-gradient-to-r from-yellow-50 to-orange-50 border border-yellow-200 rounded-xl p-4 flex items-center gap-4 shadow-sm">
            <div class="bg-yellow-100 p-3 rounded-full">
                <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
            </div>
            <div class="flex-1">
                <h4 class="font-semibold text-yellow-800">You have {{ $unreadRequestsCount }} new teacher application{{ $unreadRequestsCount > 1 ? 's' : '' }}!</h4>
                <p class="text-sm text-yellow-700">Review and respond to pending teacher requests.</p>
            </div>
            <a href="{{ route('admin.requests') }}" class="bg-yellow-500 hover:bg-yellow-600 text-white font-semibold px-4 py-2 rounded-lg transition-all">
                Review Now
            </a>
        </div>
    </div>
    @endif

    <!-- New Student Registration Alert Banner -->
    @if($unreadNewStudentsCount > 0)
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-6">
        <div class="bg-gradient-to-r from-green-50 to-emerald-50 border border-green-200 rounded-xl p-4 flex items-center gap-4 shadow-sm">
            <div class="bg-green-100 p-3 rounded-full">
                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                </svg>
            </div>
            <div class="flex-1">
                <h4 class="font-semibold text-green-800">You have {{ $unreadNewStudentsCount }} new student registration{{ $unreadNewStudentsCount > 1 ? 's' : '' }}!</h4>
                <p class="text-sm text-green-700">New students have joined the platform.</p>
            </div>
            <form method="POST" action="{{ route('admin.mark-students-read') }}" class="inline">
                @csrf
                <button type="submit" class="bg-green-500 hover:bg-green-600 text-white font-semibold px-4 py-2 rounded-lg transition-all">
                    Mark as Read
                </button>
            </form>
        </div>
    </div>
    @endif

    <!-- Stats Cards -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- All Stats Cards in One Row -->
        <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-6 gap-6 mb-8">
            <!-- Assignments Card -->
            <div class="bg-gradient-to-br from-[#F8C5C8] to-[#F8C5C8]/90 border border-[#F8C5C8] rounded-xl p-6 hover:shadow-lg transition-all">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-white text-sm font-medium drop-shadow-sm">📝 Assignments</p>
                        <p class="text-3xl font-bold text-white mt-2">{{ $totalAssignments }}</p>
                    </div>
                    <a href="{{ route('admin.activities.assignments') }}" class="bg-white/20 hover:bg-white/30 text-white p-2 rounded-lg transition-all">
                        →
                    </a>
                </div>
            </div>

            <!-- Quizzes Card -->
            <div class="bg-gradient-to-br from-[#FC8EAC] to-[#FC8EAC]/90 border border-[#FC8EAC] rounded-xl p-6 hover:shadow-lg transition-all">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-white text-sm font-medium drop-shadow-sm">❓ Quizzes</p>
                        <p class="text-3xl font-bold text-white mt-2">{{ $totalQuizzes }}</p>
                    </div>
                    <a href="{{ route('admin.activities.quizzes') }}" class="bg-white/20 hover:bg-white/30 text-white p-2 rounded-lg transition-all">
                        →
                    </a>
                </div>
            </div>

            <!-- Games Card -->
            <div class="bg-gradient-to-br from-[#EC769A] to-[#EC769A]/90 border border-[#EC769A] rounded-xl p-6 hover:shadow-lg transition-all">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-white text-sm font-medium drop-shadow-sm">🎮 Games</p>
                        <p class="text-3xl font-bold text-white mt-2">{{ $totalGames }}</p>
                    </div>
                    <a href="{{ route('admin.activities.games') }}" class="bg-white/20 hover:bg-white/30 text-white p-2 rounded-lg transition-all">
                        →
                    </a>
                </div>
            </div>

            <!-- Lessons Card -->
            <div class="bg-[#79BDBC] border border-[#79BDBC] rounded-xl p-6 hover:border-[#B5D7D5] transition-all">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-white text-sm font-medium drop-shadow-sm">📚 Lessons</p>
                        <p class="text-3xl font-bold text-white mt-2">{{ $lessonsCount }}</p>
                    </div>
                    <a href="{{ route('admin.lessons') }}" class="bg-white hover:bg-[#B5D7D5] text-[#197D8C] p-3 rounded-lg transition-all">
                        →
                    </a>
                </div>
            </div>

            <!-- Teacher Requests Card -->
            <div class="bg-[#B5D7D5] border border-[#79BDBC] rounded-xl p-6 hover:border-[#79BDBC] transition-all">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-[#197D8C] text-sm font-medium drop-shadow-sm">✓ Requests</p>
                        <p class="text-3xl font-bold text-[#197D8C] mt-2">{{ $teacherRequestsCount }}</p>
                    </div>
                    <a href="{{ route('admin.requests') }}" class="bg-[#79BDBC] hover:bg-[#B5D7D5] text-white p-3 rounded-lg transition-all">
                        →
                    </a>
                </div>
            </div>

            <!-- Emergency Cases Card -->
            <div class="bg-[#FBCFDD] border border-[#FFB9C6] rounded-xl p-6 hover:border-[#FFB9C6] transition-all">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-[#C2185B] text-sm font-medium drop-shadow-sm">⚠️ Emergency</p>
                        <p class="text-3xl font-bold text-[#C2185B] mt-2">{{ $emergencyCasesCount }}</p>
                    </div>
                    <a href="{{ route('admin.emergency') }}" class="bg-[#FFB9C6] hover:bg-[#FBCFDD] text-white p-3 rounded-lg transition-all">
                        →
                    </a>
                </div>
            </div>
        </div>

        <!-- Charts Side by Side: Users Distribution and Teachers Approval Status -->
        <div class="mb-8">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Users Distribution Chart -->
                <div class="bg-white border border-gray-200 rounded-xl p-6 shadow-lg hover:shadow-xl transition-all">
                    <div class="flex flex-col items-center h-full">
                        <p class="text-gray-700 text-xl font-semibold mb-6 text-center">Users Distribution</p>
                        <div class="relative w-52 h-52 mb-6 flex-shrink-0">
                        <svg class="transform -rotate-90 w-52 h-52" viewBox="0 0 100 100">
                            @php
                                $total = $studentsCount + $teachersCount;
                                $studentsPercentage = $total > 0 ? ($studentsCount / $total) * 100 : 0;
                                $teachersPercentage = $total > 0 ? ($teachersCount / $total) * 100 : 0;
                                
                                // Calculate angles for students segment
                                $studentsAngle = ($studentsPercentage / 100) * 360;
                                $studentsLargeArc = $studentsPercentage > 50 ? 1 : 0;
                                $studentsX = 50 + 50 * cos(deg2rad($studentsAngle - 90));
                                $studentsY = 50 + 50 * sin(deg2rad($studentsAngle - 90));
                                
                                // Calculate angles for teachers segment (starts where students ends)
                                $teachersStartAngle = $studentsAngle - 90;
                                $teachersEndAngle = $teachersStartAngle + (($teachersPercentage / 100) * 360);
                                $teachersLargeArc = $teachersPercentage > 50 ? 1 : 0;
                                $teachersX = 50 + 50 * cos(deg2rad($teachersEndAngle));
                                $teachersY = 50 + 50 * sin(deg2rad($teachersEndAngle));
                            @endphp
                            
                            <!-- Students segment -->
                            @if($studentsCount > 0)
                            <path d="M 50 50 L 50 0 A 50 50 0 {{ $studentsLargeArc }} 1 {{ $studentsX }} {{ $studentsY }} Z" 
                                  fill="#79BDBC" 
                                  class="transition-all duration-300"/>
                            @endif
                            
                            <!-- Teachers segment -->
                            @if($teachersCount > 0)
                            <path d="M 50 50 L {{ $studentsX }} {{ $studentsY }} A 50 50 0 {{ $teachersLargeArc }} 1 {{ $teachersX }} {{ $teachersY }} Z" 
                                  fill="#FFB9C6" 
                                  class="transition-all duration-300"/>
                            @endif
                        </svg>
                        <div class="absolute inset-0 flex items-center justify-center">
                            <div class="text-center">
                                <p class="text-2xl font-bold text-gray-700">{{ $total }}</p>
                                <p class="text-sm text-gray-500">Total Users</p>
                            </div>
                        </div>
                    </div>
                    
                        <!-- Legend -->
                        <div class="flex flex-wrap justify-center gap-6 mt-auto">
                            <div class="flex items-center gap-2">
                                <div class="w-4 h-4 rounded-full bg-[#79BDBC]"></div>
                                <span class="text-sm text-gray-700">
                                    <span class="font-semibold">Students:</span> {{ $studentsCount }} 
                                    @if($total > 0)
                                    ({{ number_format($studentsPercentage, 1) }}%)
                                    @endif
                                </span>
                            </div>
                            <div class="flex items-center gap-2">
                                <div class="w-4 h-4 rounded-full bg-[#FFB9C6]"></div>
                                <span class="text-sm text-gray-700">
                                    <span class="font-semibold">Teachers:</span> {{ $teachersCount }}
                                    @if($total > 0)
                                    ({{ number_format($teachersPercentage, 1) }}%)
                                    @endif
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Teachers Approval Status Chart -->
                <div class="bg-white border border-gray-200 rounded-xl p-6 shadow-lg hover:shadow-xl transition-all">
                    <div class="flex flex-col items-center h-full">
                        <p class="text-gray-700 text-xl font-semibold mb-6 text-center">Teachers Approval Status</p>
                        <div id="teachersApprovalChart" class="w-64 h-64 mx-auto flex-shrink-0"></div>
                        
                        <!-- Legend -->
                        <div class="flex flex-wrap justify-center gap-6 mt-auto">
                            <div class="flex items-center gap-2">
                                <div class="w-4 h-4 rounded-full bg-[#79BDBC]"></div>
                                <span class="text-sm text-gray-700">
                                    <span class="font-semibold">Approved:</span> <span class="text-[#197D8C] font-bold">{{ $approvedTeachersCount }}</span>
                                </span>
                            </div>
                            <div class="flex items-center gap-2">
                                <div class="w-4 h-4 rounded-full bg-[#EC769A]"></div>
                                <span class="text-sm text-gray-700">
                                    <span class="font-semibold">Rejected:</span> <span class="text-[#C2185B] font-bold">{{ $rejectedTeachersCount }}</span>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Class Status Bar Chart -->
        <div class="mb-8">
            <div class="bg-white border border-gray-200 rounded-xl p-6 shadow-lg hover:shadow-xl transition-all">
                <p class="text-gray-700 text-xl font-semibold mb-6">Classes by Status</p>
                
                @php
                    $maxCount = max($fullClassesCount, $activeClassesCount, $emptyClassesCount, 1);
                    $barHeight = 200; // Maximum bar height in pixels
                @endphp
                
                <div class="flex items-end justify-center gap-8 h-64">
                    <!-- Full Classes Bar -->
                    <div class="flex flex-col items-center gap-2 flex-1">
                        <div class="relative w-full flex items-end justify-center" style="height: {{ $barHeight }}px;">
                            <div class="w-full max-w-20 bg-gradient-to-t from-[#C2185B] to-[#EC769A] rounded-t-lg transition-all duration-300 hover:opacity-90" 
                                 style="height: {{ $maxCount > 0 ? ($fullClassesCount / $maxCount) * $barHeight : 0 }}px;">
                            </div>
                        </div>
                        <div class="text-center">
                            <p class="text-2xl font-bold text-[#C2185B]">{{ $fullClassesCount }}</p>
                            <p class="text-sm text-gray-600 mt-1">Full</p>
                        </div>
                    </div>

                    <!-- Active Classes Bar -->
                    <div class="flex flex-col items-center gap-2 flex-1">
                        <div class="relative w-full flex items-end justify-center" style="height: {{ $barHeight }}px;">
                            <div class="w-full max-w-20 bg-gradient-to-t from-[#6EC6C5] to-[#79BDBC] rounded-t-lg transition-all duration-300 hover:opacity-90" 
                                 style="height: {{ $maxCount > 0 ? ($activeClassesCount / $maxCount) * $barHeight : 0 }}px;">
                            </div>
                        </div>
                        <div class="text-center">
                            <p class="text-2xl font-bold text-[#197D8C]">{{ $activeClassesCount }}</p>
                            <p class="text-sm text-gray-600 mt-1">Active</p>
                        </div>
                    </div>

                    <!-- Empty Classes Bar -->
                    <div class="flex flex-col items-center gap-2 flex-1">
                        <div class="relative w-full flex items-end justify-center" style="height: {{ $barHeight }}px;">
                            <div class="w-full max-w-20 bg-gradient-to-t from-[#E5E7EB] to-[#D1D5DB] rounded-t-lg transition-all duration-300 hover:opacity-90" 
                                 style="height: {{ $maxCount > 0 ? ($emptyClassesCount / $maxCount) * $barHeight : 0 }}px;">
                            </div>
                        </div>
                        <div class="text-center">
                            <p class="text-2xl font-bold text-gray-600">{{ $emptyClassesCount }}</p>
                            <p class="text-sm text-gray-600 mt-1">Empty</p>
                        </div>
                    </div>
                </div>

                <!-- Legend -->
                <div class="flex flex-wrap justify-center gap-6 mt-6 pt-6 border-t border-gray-200">
                    <div class="flex items-center gap-2">
                        <div class="w-4 h-4 rounded bg-gradient-to-r from-[#C2185B] to-[#EC769A]"></div>
                        <span class="text-sm text-gray-700">Full Classes: {{ $fullClassesCount }}</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <div class="w-4 h-4 rounded bg-gradient-to-r from-[#6EC6C5] to-[#79BDBC]"></div>
                        <span class="text-sm text-gray-700">Active Classes: {{ $activeClassesCount }}</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <div class="w-4 h-4 rounded bg-gradient-to-r from-[#E5E7EB] to-[#D1D5DB]"></div>
                        <span class="text-sm text-gray-700">Empty Classes: {{ $emptyClassesCount }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Learning Activities Overview Section -->
        <div class="mb-8">
            <h2 class="text-2xl font-bold text-gray-800 mb-6">Learning Activities Overview</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Assignments Overview -->
                <div class="bg-white border border-gray-200 rounded-xl p-6 shadow-lg hover:shadow-xl transition-all">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold text-gray-800">📝 Assignments</h3>
                        <a href="{{ route('admin.activities.assignments') }}" class="text-purple-600 hover:text-purple-700 text-sm font-medium">View Details →</a>
                    </div>
                    <div class="space-y-3">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Total Count:</span>
                            <span class="font-bold text-gray-800">{{ $activitiesOverview['assignments']['total_count'] }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Avg Participation:</span>
                            <span class="font-bold text-gray-800">{{ $activitiesOverview['assignments']['avg_participation_rate'] }}%</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Avg Score:</span>
                            <span class="font-bold text-gray-800">{{ $activitiesOverview['assignments']['avg_score'] }}%</span>
                        </div>
                        <div class="flex justify-between items-center pt-2 border-t border-gray-200">
                            <span class="text-gray-600">Status:</span>
                            @php
                                $status = $activitiesOverview['assignments']['status'];
                                $statusColor = $status === 'Healthy' ? 'bg-green-100 text-green-800' : ($status === 'Needs Review' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800');
                            @endphp
                            <span class="px-3 py-1 rounded-full text-xs font-semibold {{ $statusColor }}">{{ $status }}</span>
                        </div>
                    </div>
                </div>

                <!-- Quizzes Overview -->
                <div class="bg-white border border-gray-200 rounded-xl p-6 shadow-lg hover:shadow-xl transition-all">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold text-gray-800">❓ Quizzes</h3>
                        <a href="{{ route('admin.activities.quizzes') }}" class="text-orange-600 hover:text-orange-700 text-sm font-medium">View Details →</a>
                    </div>
                    <div class="space-y-3">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Total Count:</span>
                            <span class="font-bold text-gray-800">{{ $activitiesOverview['quizzes']['total_count'] }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Avg Participation:</span>
                            <span class="font-bold text-gray-800">{{ $activitiesOverview['quizzes']['avg_participation_rate'] }}%</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Avg Score:</span>
                            <span class="font-bold text-gray-800">{{ $activitiesOverview['quizzes']['avg_score'] }}%</span>
                        </div>
                        <div class="flex justify-between items-center pt-2 border-t border-gray-200">
                            <span class="text-gray-600">Status:</span>
                            @php
                                $status = $activitiesOverview['quizzes']['status'];
                                $statusColor = $status === 'Healthy' ? 'bg-green-100 text-green-800' : ($status === 'Needs Review' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800');
                            @endphp
                            <span class="px-3 py-1 rounded-full text-xs font-semibold {{ $statusColor }}">{{ $status }}</span>
                        </div>
                    </div>
                </div>

                <!-- Games Overview -->
                <div class="bg-white border border-gray-200 rounded-xl p-6 shadow-lg hover:shadow-xl transition-all">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold text-gray-800">🎮 Games</h3>
                        <a href="{{ route('admin.activities.games') }}" class="text-pink-600 hover:text-pink-700 text-sm font-medium">View Details →</a>
                    </div>
                    <div class="space-y-3">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Total Count:</span>
                            <span class="font-bold text-gray-800">{{ $activitiesOverview['games']['total_count'] }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Avg Participation:</span>
                            <span class="font-bold text-gray-800">{{ $activitiesOverview['games']['avg_participation_rate'] }}%</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Avg Score:</span>
                            <span class="font-bold text-gray-800">{{ $activitiesOverview['games']['avg_score'] }}</span>
                        </div>
                        <div class="flex justify-between items-center pt-2 border-t border-gray-200">
                            <span class="text-gray-600">Status:</span>
                            @php
                                $status = $activitiesOverview['games']['status'];
                                $statusColor = $status === 'Healthy' ? 'bg-green-100 text-green-800' : ($status === 'Needs Review' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800');
                            @endphp
                            <span class="px-3 py-1 rounded-full text-xs font-semibold {{ $statusColor }}">{{ $status }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Engagement & Performance Section -->
        <div class="mb-8">
            <h2 class="text-2xl font-bold text-gray-800 mb-6">Engagement & Performance</h2>
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Assignment Submissions Chart -->
                <div class="bg-white border border-gray-200 rounded-2xl p-6 shadow-lg" style="background: linear-gradient(to bottom, #fefefe, #fafafa);">
                    <div class="flex items-center gap-2 mb-4">
                        <div class="relative">
                            <!-- School building with brown base and green roof -->
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" viewBox="0 0 24 24">
                                <!-- Green roof -->
                                <path d="M12 3L2 9v2h20V9L12 3z" fill="#22C55E"/>
                                <!-- Brown base -->
                                <path d="M2 11h20v9H2v-9z" fill="#8B4513"/>
                                <!-- Windows -->
                                <rect x="5" y="13" width="3" height="3" fill="#FEF3C7" stroke="#374151" stroke-width="0.5"/>
                                <rect x="10" y="13" width="3" height="3" fill="#FEF3C7" stroke="#374151" stroke-width="0.5"/>
                                <rect x="15" y="13" width="3" height="3" fill="#FEF3C7" stroke="#374151" stroke-width="0.5"/>
                                <!-- Door -->
                                <rect x="10.5" y="16" width="3" height="4" fill="#FEF3C7" stroke="#374151" stroke-width="0.5"/>
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-800">Assignment Submissions Over Time</h3>
                    </div>
                    <div id="assignmentSubmissionsChart" style="height: 300px;"></div>
                </div>

                <!-- Quiz Attempts Chart -->
                <div class="bg-white border border-gray-200 rounded-xl p-6 shadow-lg">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Quiz Attempts Over Time</h3>
                    <div id="quizAttemptsChart" style="height: 300px;"></div>
                </div>

                <!-- Quiz Average Scores Chart -->
                <div class="bg-white border border-gray-200 rounded-xl p-6 shadow-lg">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Quiz Average Scores Over Time</h3>
                    <div id="quizAvgScoresChart" style="height: 300px;"></div>
                </div>

                <!-- Game Play Counts Chart -->
                <div class="bg-white border border-gray-200 rounded-xl p-6 shadow-lg">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Game Play Counts Over Time</h3>
                    <div id="gamePlayCountsChart" style="height: 300px;"></div>
                </div>
            </div>
        </div>

        <!-- Alerts & Action Needed Section -->
        @if(count($alerts) > 0)
        <div class="mb-8">
            <h2 class="text-2xl font-bold text-gray-800 mb-6">Alerts & Action Needed</h2>
            <div class="space-y-4">
                @if(isset($alerts['assignments_zero_submissions']) && count($alerts['assignments_zero_submissions']) > 0)
                <div class="bg-red-50 border border-red-200 rounded-xl p-6">
                    <h3 class="text-lg font-semibold text-red-800 mb-3 flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                        </svg>
                        Assignments with Zero Submissions
                    </h3>
                    <ul class="space-y-2">
                        @foreach(array_slice($alerts['assignments_zero_submissions'], 0, 5) as $alert)
                        <li class="text-sm text-red-700">• {{ $alert['title'] }} (Class: {{ $alert['class'] }})</li>
                        @endforeach
                    </ul>
                    @if(count($alerts['assignments_zero_submissions']) > 5)
                    <p class="text-xs text-red-600 mt-2">...and {{ count($alerts['assignments_zero_submissions']) - 5 }} more</p>
                    @endif
                </div>
                @endif

                @if(isset($alerts['quizzes_low_scores']) && count($alerts['quizzes_low_scores']) > 0)
                <div class="bg-yellow-50 border border-yellow-200 rounded-xl p-6">
                    <h3 class="text-lg font-semibold text-yellow-800 mb-3 flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                        </svg>
                        Quizzes with Low Average Scores
                    </h3>
                    <ul class="space-y-2">
                        @foreach(array_slice($alerts['quizzes_low_scores'], 0, 5) as $alert)
                        <li class="text-sm text-yellow-700">• {{ $alert['title'] }} (Class: {{ $alert['class'] }})</li>
                        @endforeach
                    </ul>
                    @if(count($alerts['quizzes_low_scores']) > 5)
                    <p class="text-xs text-yellow-600 mt-2">...and {{ count($alerts['quizzes_low_scores']) - 5 }} more</p>
                    @endif
                </div>
                @endif

                @if(isset($alerts['games_never_played']) && count($alerts['games_never_played']) > 0)
                <div class="bg-blue-50 border border-blue-200 rounded-xl p-6">
                    <h3 class="text-lg font-semibold text-blue-800 mb-3 flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Games Never Played by Students
                    </h3>
                    <ul class="space-y-2">
                        @foreach(array_slice($alerts['games_never_played'], 0, 5) as $alert)
                        <li class="text-sm text-blue-700">• {{ $alert['title'] }}</li>
                        @endforeach
                    </ul>
                    @if(count($alerts['games_never_played']) > 5)
                    <p class="text-xs text-blue-600 mt-2">...and {{ count($alerts['games_never_played']) - 5 }} more</p>
                    @endif
                </div>
                @endif

                @if(isset($alerts['inactive_teachers']) && count($alerts['inactive_teachers']) > 0)
                <div class="bg-purple-50 border border-purple-200 rounded-xl p-6">
                    <h3 class="text-lg font-semibold text-purple-800 mb-3 flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Inactive Teachers (No activity in last 3 months)
                    </h3>
                    <ul class="space-y-2">
                        @foreach(array_slice($alerts['inactive_teachers'], 0, 5) as $alert)
                        <li class="text-sm text-purple-700">• {{ $alert['title'] }}</li>
                        @endforeach
                    </ul>
                    @if(count($alerts['inactive_teachers']) > 5)
                    <p class="text-xs text-purple-600 mt-2">...and {{ count($alerts['inactive_teachers']) - 5 }} more</p>
                    @endif
                </div>
                @endif
            </div>
        </div>
        @endif

        <!-- Quick Actions section removed as requested -->
    </div>
</div>

@push('scripts')
<script src="https://unpkg.com/apexcharts@3.44.0/dist/apexcharts.min.js" crossorigin="anonymous"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Get data from backend
    const approvedValue = {{ $approvedTeachersCount }};
    const rejectedValue = {{ $rejectedTeachersCount }};
    
    // Calculate maximum value for scaling
    const maxValue = Math.max(approvedValue, rejectedValue, 10);
    
    // Calculate percentages for display (scaled to maxValue)
    const approvedPercent = maxValue > 0 ? Math.round((approvedValue / maxValue) * 100) : 0;
    const rejectedPercent = maxValue > 0 ? Math.round((rejectedValue / maxValue) * 100) : 0;
    
    // Teachers Approval Chart
    var teachersApprovalOptions = {
        series: [approvedPercent, rejectedPercent],
        chart: {
            height: 256,
            type: 'radialBar',
            offsetY: 0,
        },
        plotOptions: {
            radialBar: {
                startAngle: 0,
                endAngle: 360,
                track: {
                    background: '#e5e7eb',
                    strokeWidth: '97%',
                    margin: 5,
                },
                dataLabels: {
                    name: {
                        show: false
                    },
                    value: {
                        show: false
                    }
                },
                hollow: {
                    size: '40%',
                }
            }
        },
        fill: {
            type: 'gradient',
            gradient: {
                shade: 'light',
                type: 'horizontal',
                shadeIntensity: 0.5,
                gradientToColors: ['#6EC6C5', '#EC769A'],
                inverseColors: false,
                opacityFrom: 1,
                opacityTo: 1,
                stops: [0, 100]
            }
        },
        colors: ['#79BDBC', '#FFB9C6'],
        labels: ['Approved', 'Rejected'],
        stroke: {
            lineCap: 'round'
        },
        legend: {
            show: false
        }
    };

    var teachersApprovalChart = new ApexCharts(document.querySelector("#teachersApprovalChart"), teachersApprovalOptions);
    teachersApprovalChart.render();

    // Engagement Charts Data
    const engagementData = @json($engagementData);

    // Helper function to filter data - only Nov and Dec 2025 (2 months including Dec), then all 2026
    function filterDataFromDec2025(dataArray, labelsArray) {
        if (!dataArray || !labelsArray || dataArray.length === 0) {
            return { data: [], labels: [] };
        }
        
        var filteredData = [];
        var filteredLabels = [];
        var seenLabels = new Set();
        
        // Month order for sorting
        var monthOrder = {
            'Jan': 1, 'Feb': 2, 'Mar': 3, 'Apr': 4, 'May': 5, 'Jun': 6,
            'Jul': 7, 'Aug': 8, 'Sep': 9, 'Oct': 10, 'Nov': 11, 'Dec': 12
        };
        
        // Helper function to create sort key from label
        function getSortKey(label, monthOrder) {
            var yearMatch = label.match(/(\d{4})/);
            var monthMatch = label.match(/(Jan|Feb|Mar|Apr|May|Jun|Jul|Aug|Sep|Oct|Nov|Dec)/i);
            
            var year = yearMatch ? parseInt(yearMatch[1]) : 2025;
            var month = monthMatch ? (monthOrder[monthMatch[1]] || 1) : 1;
            
            return year * 100 + month;
        }
        
        // Filter data from Dec 2025 onwards
        for (var i = 0; i < labelsArray.length; i++) {
            var label = labelsArray[i] || '';
            if (label) {
                var yearMatch = label.match(/(\d{4})/);
                var year = yearMatch ? parseInt(yearMatch[1]) : 0;
                var monthMatch = label.match(/(Jan|Feb|Mar|Apr|May|Jun|Jul|Aug|Sep|Oct|Nov|Dec)/i);
                var month = monthMatch ? (monthOrder[monthMatch[1]] || 0) : 0;
                
                // Include if year > 2025, or year == 2025 and month >= 11 (Nov and Dec 2025 only - 2 months including Dec)
                var shouldInclude = false;
                if (year > 2025) {
                    shouldInclude = true;
                } else if (year === 2025 && month >= 11) {
                    shouldInclude = true;
                }
                
                if (shouldInclude && !seenLabels.has(label)) {
                    filteredData.push({
                        value: dataArray[i] || 0,
                        label: label,
                        sortKey: getSortKey(label, monthOrder)
                    });
                    seenLabels.add(label);
                }
            }
        }
        
        // Sort by date (earliest first)
        filteredData.sort(function(a, b) {
            return a.sortKey - b.sortKey;
        });
        
        return {
            data: filteredData.map(function(item) { return item.value; }),
            labels: filteredData.map(function(item) { return item.label; })
        };
    }

    // Assignment Submissions Chart - Horizontal Bar Chart with Gradient
    if (document.querySelector("#assignmentSubmissionsChart")) {
        // Prepare data for horizontal bar chart
        // Group submissions by class or time period for comparison
        var submissionData = engagementData.assignment_submissions.data;
        var submissionLabels = engagementData.assignment_submissions.labels;
        
        // If we have time-based data, we'll show the latest periods as bars
        // Otherwise, we'll use the data as-is
        var barData = [];
        var barLabels = [];
        
        // Filter data from Dec 2025 onwards
        var filteredSubmissions = filterDataFromDec2025(submissionData, submissionLabels);
        barData = filteredSubmissions.data;
        barLabels = filteredSubmissions.labels;
        
        // Reverse so most recent appears at top (for horizontal bar chart)
        barData = barData.reverse();
        barLabels = barLabels.reverse();
        
        // If no data found, show placeholder
        if (barData.length === 0) {
            barData = [0, 0];
            barLabels = ['No Data', 'No Data'];
        }
        
        // Calculate max value for x-axis
        var maxDataValue = Math.max.apply(null, barData);
        var xAxisMax = Math.max(100, Math.ceil(maxDataValue / 20) * 20); // Round up to nearest 20, minimum 100
        
        var assignmentSubmissionsOptions = {
            series: [{
                name: 'Submissions',
                data: barData
            }],
            chart: {
                type: 'bar',
                height: 300,
                toolbar: { show: false },
                horizontal: true,
                background: 'transparent'
            },
            plotOptions: {
                bar: {
                    borderRadius: 8,
                    horizontal: true,
                    dataLabels: {
                        position: 'end'
                    },
                    barHeight: '60%'
                }
            },
            dataLabels: {
                enabled: true,
                formatter: function(val) {
                    return val;
                },
                style: {
                    colors: ['#fff'],
                    fontSize: '12px',
                    fontWeight: 'bold'
                },
                offsetX: 10,
                offsetY: 0
            },
            xaxis: {
                categories: barLabels,
                labels: {
                    style: {
                        fontSize: '11px',
                        colors: '#6B7280'
                    }
                },
                min: 0,
                max: xAxisMax,
                tickAmount: 5
            },
            yaxis: {
                labels: {
                    style: {
                        fontSize: '11px',
                        colors: '#6B7280'
                    }
                }
            },
            fill: {
                type: 'gradient',
                gradient: {
                    shade: 'light',
                    type: 'horizontal',
                    shadeIntensity: 0.5,
                    gradientToColors: ['#7DD3FC'],
                    inverseColors: false,
                    opacityFrom: 1,
                    opacityTo: 1,
                    stops: [0, 100],
                    colorStops: [
                        {
                            offset: 0,
                            color: '#EC4899',
                            opacity: 1
                        },
                        {
                            offset: 100,
                            color: '#7DD3FC',
                            opacity: 1
                        }
                    ]
                },
                colors: ['#EC4899']
            },
            colors: ['#EC4899'],
            tooltip: {
                theme: 'light',
                y: {
                    formatter: function(val) {
                        return val + ' submissions';
                    }
                }
            },
            grid: {
                borderColor: '#F3F4F6',
                strokeDashArray: 3,
                xaxis: {
                    lines: {
                        show: true
                    }
                },
                yaxis: {
                    lines: {
                        show: false
                    }
                }
            }
        };
        var assignmentSubmissionsChart = new ApexCharts(document.querySelector("#assignmentSubmissionsChart"), assignmentSubmissionsOptions);
        assignmentSubmissionsChart.render();
    }

    // Quiz Attempts Chart - Creative Stacked Area with Multiple Gradients
    if (document.querySelector("#quizAttemptsChart")) {
        // Filter data from Dec 2025 onwards
        var filteredQuizAttempts = filterDataFromDec2025(
            engagementData.quiz_attempts.data,
            engagementData.quiz_attempts.labels
        );
        
        var quizAttemptsOptions = {
            series: [{
                name: 'Quiz Attempts',
                data: filteredQuizAttempts.data
            }],
            chart: {
                type: 'area',
                height: 300,
                toolbar: { show: false },
                zoom: { enabled: false },
                animations: {
                    enabled: true,
                    easing: 'easeinout',
                    speed: 800
                }
            },
            stroke: {
                curve: 'smooth',
                width: 4,
                lineCap: 'round'
            },
            fill: {
                type: 'gradient',
                gradient: {
                    shade: 'dark',
                    shadeIntensity: 0.7,
                    opacityFrom: 0.8,
                    opacityTo: 0.2,
                    stops: [0, 30, 70, 100],
                    colorStops: [
                        {
                            offset: 0,
                            color: '#F97316',
                            opacity: 0.9
                        },
                        {
                            offset: 50,
                            color: '#FB923C',
                            opacity: 0.7
                        },
                        {
                            offset: 100,
                            color: '#FDBA74',
                            opacity: 0.3
                        }
                    ]
                }
            },
            markers: {
                size: 6,
                colors: ['#F97316'],
                strokeColors: '#fff',
                strokeWidth: 2,
                hover: {
                    size: 8
                }
            },
            xaxis: {
                categories: filteredQuizAttempts.labels,
                labels: {
                    style: {
                        fontSize: '11px',
                        fontWeight: 500
                    }
                }
            },
            yaxis: {
                labels: {
                    style: {
                        fontSize: '11px'
                    }
                }
            },
            colors: ['#F97316'],
            grid: {
                borderColor: '#F3F4F6',
                strokeDashArray: 3,
                padding: {
                    top: 10,
                    right: 10,
                    bottom: 10,
                    left: 10
                }
            },
            tooltip: {
                theme: 'dark',
                y: {
                    formatter: function(val) {
                        return val + ' attempts';
                    }
                }
            }
        };
        var quizAttemptsChart = new ApexCharts(document.querySelector("#quizAttemptsChart"), quizAttemptsOptions);
        quizAttemptsChart.render();
    }

    // Quiz Average Scores Chart - Creative Radar/Spider Chart with Time Series
    if (document.querySelector("#quizAvgScoresChart")) {
        // Filter data from Dec 2025 onwards
        var filteredQuizScores = filterDataFromDec2025(
            engagementData.quiz_avg_scores.data,
            engagementData.quiz_avg_scores.labels
        );
        
        var quizAvgScoresOptions = {
            series: [{
                name: 'Average Score %',
                data: filteredQuizScores.data
            }],
            chart: {
                type: 'radar',
                height: 300,
                toolbar: { show: false },
                animations: {
                    enabled: true,
                    easing: 'easeinout',
                    speed: 800
                }
            },
            xaxis: {
                categories: filteredQuizScores.labels,
                labels: {
                    style: {
                        fontSize: '10px',
                        fontWeight: 500
                    }
                }
            },
            yaxis: {
                min: 0,
                max: 100,
                tickAmount: 5,
                labels: {
                    formatter: function(val) {
                        return val + '%';
                    },
                    style: {
                        fontSize: '10px'
                    }
                }
            },
            colors: ['#10B981'],
            fill: {
                opacity: 0.3,
                colors: ['#10B981']
            },
            stroke: {
                width: 3,
                colors: ['#10B981'],
                curve: 'smooth'
            },
            markers: {
                size: 5,
                colors: ['#10B981'],
                strokeColors: '#fff',
                strokeWidth: 2,
                hover: {
                    size: 7
                }
            },
            plotOptions: {
                radar: {
                    polygons: {
                        strokeColors: '#E5E7EB',
                        fill: {
                            colors: ['#F9FAFB', '#fff']
                        }
                    }
                }
            },
            tooltip: {
                theme: 'dark',
                y: {
                    formatter: function(val) {
                        return val + '%';
                    }
                }
            }
        };
        var quizAvgScoresChart = new ApexCharts(document.querySelector("#quizAvgScoresChart"), quizAvgScoresOptions);
        quizAvgScoresChart.render();
    }

    // Game Play Counts Chart - Creative Step Line Chart with Filled Markers
    if (document.querySelector("#gamePlayCountsChart")) {
        // Filter data from Dec 2025 onwards
        var filteredGamePlays = filterDataFromDec2025(
            engagementData.game_play_counts.data,
            engagementData.game_play_counts.labels
        );
        
        var gamePlayCountsOptions = {
            series: [{
                name: 'Game Play Counts',
                data: filteredGamePlays.data
            }],
            chart: {
                type: 'line',
                height: 300,
                toolbar: { show: false },
                zoom: {
                    enabled: false
                },
                animations: {
                    enabled: true,
                    easing: 'easeinout',
                    speed: 800
                }
            },
            stroke: {
                curve: 'stepline',
                width: 4,
                lineCap: 'round'
            },
            fill: {
                type: 'gradient',
                gradient: {
                    shade: 'dark',
                    shadeIntensity: 0.5,
                    type: 'vertical',
                    opacityFrom: 0.7,
                    opacityTo: 0.2,
                    stops: [0, 50, 100],
                    colorStops: [
                        {
                            offset: 0,
                            color: '#EC4899',
                            opacity: 0.8
                        },
                        {
                            offset: 50,
                            color: '#F472B6',
                            opacity: 0.5
                        },
                        {
                            offset: 100,
                            color: '#FBCFE8',
                            opacity: 0.2
                        }
                    ]
                }
            },
            markers: {
                size: [8, 12, 10, 10, 10, 10, 10, 10, 10, 10, 10, 8],
                colors: ['#EC4899'],
                strokeColors: '#fff',
                strokeWidth: 3,
                hover: {
                    size: 14,
                    sizeOffset: 4
                },
                shape: 'circle'
            },
            dataLabels: {
                enabled: true,
                offsetY: -10,
                style: {
                    fontSize: '11px',
                    fontWeight: 600,
                    colors: ['#EC4899']
                },
                formatter: function(val) {
                    return val;
                }
            },
            xaxis: {
                categories: filteredGamePlays.labels,
                labels: {
                    rotate: -45,
                    rotateAlways: false,
                    style: {
                        fontSize: '10px',
                        fontWeight: 500
                    }
                }
            },
            yaxis: {
                labels: {
                    style: {
                        fontSize: '10px'
                    }
                }
            },
            colors: ['#EC4899'],
            grid: {
                borderColor: '#F3F4F6',
                strokeDashArray: 4,
                xaxis: {
                    lines: {
                        show: true
                    }
                },
                yaxis: {
                    lines: {
                        show: true
                    }
                },
                padding: {
                    top: 10,
                    right: 10,
                    bottom: 10,
                    left: 10
                }
            },
            tooltip: {
                theme: 'dark',
                y: {
                    formatter: function(val) {
                        return val + ' plays';
                    }
                }
            }
        };
        var gamePlayCountsChart = new ApexCharts(document.querySelector("#gamePlayCountsChart"), gamePlayCountsOptions);
        gamePlayCountsChart.render();
    }
});
</script>
@endpush
@endsection
