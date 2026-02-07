@extends('layouts.admin')

@section('content')
<div class="min-h-screen" style="background: linear-gradient(135deg, #FFF4FA 0%, #FDF2F8 30%, #F0F9FF 70%, #E0F7FA 100%);">
    <!-- Header -->
    <div class="bg-gradient-to-r from-pink-200/90 via-rose-100/80 to-cyan-200/90 shadow-2xl border-b-4 border-pink-300/50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
            <div class="flex flex-col md:flex-row justify-between items-center gap-6">
                <div class="flex items-center gap-6 text-center md:text-left">
                    <!-- Students Icon -->
                    <div class="hidden md:flex items-center justify-center w-24 h-24 rounded-3xl bg-gradient-to-br from-pink-500 via-rose-400 to-cyan-500 shadow-2xl transform hover:scale-105 transition-all duration-300 border-4 border-white/50">
                        <div class="text-6xl filter drop-shadow-2xl">🧕</div>
                    </div>
                    <div>
                        <h1 class="text-5xl font-extrabold text-gray-800 mb-3 drop-shadow-lg flex items-center gap-4 justify-center md:justify-start">
                            <span class="md:hidden flex items-center justify-center w-20 h-20 rounded-2xl bg-gradient-to-br from-pink-500 via-rose-400 to-cyan-500 shadow-xl border-4 border-white/50">
                                <span class="text-5xl">🧕</span>
                            </span>
                            <span class="bg-clip-text text-transparent bg-gradient-to-r from-pink-600 via-rose-500 to-cyan-600">All Students</span>
                        </h1>
                        <p class="text-gray-700 text-lg font-medium">Manage and view all registered students</p>
                    </div>
                    <!-- Students Count Badge -->
                    <div class="px-4 py-2 bg-white/30 backdrop-blur-sm rounded-full text-gray-800 font-semibold shadow-lg border border-white/50" id="studentCountBadge">
                        <span class="text-2xl" id="studentCount">{{ $students->count() }}</span>
                        <span class="ml-2 text-sm">Students</span>
                    </div>
                </div>
                <a href="{{ route('admin.students.export') }}" 
                   class="bg-gradient-to-r from-pink-400 to-rose-400 hover:from-pink-500 hover:to-rose-500 text-white px-8 py-4 rounded-2xl font-bold shadow-xl hover:shadow-2xl transition-all transform hover:scale-105 flex items-center gap-3 text-lg border-2 border-pink-300/50">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    Export Excel
                </a>
            </div>
        </div>
    </div>

    <!-- Content -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Search and Filter Bar -->
        <div class="bg-white rounded-xl shadow-lg p-4 mb-6">
            <div class="flex flex-col md:flex-row gap-4 items-center">
                <div class="flex-1 w-full">
                    <input type="text" id="searchInput" placeholder="🔍 Search by name, email, or class..." 
                           class="w-full px-4 py-2 border border-gray-100 rounded-lg focus:ring-2 focus:ring-pink-500 focus:border-transparent">
                </div>
                <div class="flex gap-2">
                    <select id="statusFilter" class="px-4 py-2 border border-gray-100 rounded-lg focus:ring-2 focus:ring-pink-500">
                        <option value="">All Status</option>
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                        <option value="expired">Expired</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Creative Students Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4" id="studentsGrid">
            @forelse($students as $student)
            @php
                $user = $student->user;
                $class = $student->studentClass;
                $planColors = [
                    'basic' => ['bg' => 'bg-blue-500', 'text' => 'text-blue-700', 'border' => 'border-blue-100', 'badge' => 'bg-blue-100'],
                    'premium' => ['bg' => 'bg-purple-500', 'text' => 'text-purple-700', 'border' => 'border-purple-100', 'badge' => 'bg-purple-100'],
                    'pro' => ['bg' => 'bg-amber-500', 'text' => 'text-amber-700', 'border' => 'border-amber-100', 'badge' => 'bg-amber-100']
                ];
                $planData = $planColors[$student->plan_type ?? 'basic'] ?? $planColors['basic'];
                
                $lastActivity = $student->last_activity_at;
                if ($lastActivity) {
                    $now = \Carbon\Carbon::now()->startOfDay();
                    $lastActivityDate = \Carbon\Carbon::parse($lastActivity)->startOfDay();
                    $inactiveDays = $lastActivityDate->diffInDays($now);
                    if ($lastActivityDate->isFuture()) {
                        $inactiveDays = 0;
                    }
                    $isRecent = $inactiveDays == 0;
                    $isThisWeek = $inactiveDays > 0 && $inactiveDays <= 7;
                    $isLongInactive = $inactiveDays > 30;
                }
                
                $profileImage = $user->profile_photo_url ?? ($user->profile_image_url ?? null);
                
                // Determine background color - light pink or light turquoise if no profile
                $hasProfile = $profileImage && $profileImage != asset('images/default-avatar.svg');
                $bgColor = $hasProfile ? '' : (($student->student_id % 2 == 0) ? 'bg-gradient-to-br from-pink-200 via-pink-100 to-rose-100' : 'bg-gradient-to-br from-cyan-200 via-teal-100 to-cyan-100');
            @endphp
            @if($user)
            @php
                $activityStatus = $student->activity_status ?? 'expired';
                $statusColors = [
                    'active' => ['bg' => 'bg-green-100', 'text' => 'text-green-700', 'border' => 'border-green-100'],
                    'inactive' => ['bg' => 'bg-orange-100', 'text' => 'text-orange-700', 'border' => 'border-orange-100'],
                    'expired' => ['bg' => 'bg-red-100', 'text' => 'text-red-700', 'border' => 'border-red-100']
                ];
                $statusData = $statusColors[$activityStatus] ?? $statusColors['expired'];
            @endphp
            <div class="student-card group relative bg-white rounded-2xl shadow-lg hover:shadow-xl transition-all duration-500 transform hover:-translate-y-1 overflow-hidden border border-gray-100"
                 data-name="{{ strtolower(($user->first_name ?? '') . ' ' . ($user->last_name ?? '')) }}"
                 data-email="{{ strtolower($user->email ?? '') }}"
                 data-class="{{ strtolower($class->class_name ?? '') }}"
                 data-city="{{ strtolower($student->city ?? '') }}"
                 data-plan="{{ $student->plan_type ?? '' }}"
                 data-status="{{ $activityStatus }}">
                
                <!-- Top Gradient Header with Blurred Background -->
                <div class="relative h-28 overflow-hidden">
                    <!-- Blurred Profile Background -->
                    @if($hasProfile)
                        <div class="absolute inset-0 bg-cover bg-center" 
                             style="background-image: url('{{ $profileImage }}'); filter: blur(40px) brightness(1.1); transform: scale(1.2);"></div>
                        <div class="absolute inset-0 bg-white/20"></div>
                    @else
                        <!-- Light Pink or Light Turquoise Background -->
                        <div class="absolute inset-0 {{ $bgColor }}"></div>
                    @endif
                    
                    <!-- Profile Picture - Centered -->
                    <div class="absolute inset-0 flex items-center justify-center">
                        <div class="relative z-10">
                            @if($hasProfile)
                                <img src="{{ $profileImage }}" 
                                     alt="{{ $user->first_name }} {{ $user->last_name }}"
                                     class="w-16 h-16 rounded-full object-cover border-2 border-white shadow-xl ring-2 ring-white/50">
                            @else
                                <div class="w-16 h-16 rounded-full bg-white/30 backdrop-blur-sm flex items-center justify-center {{ ($student->student_id % 2 == 0) ? 'text-pink-700' : 'text-cyan-700' }} font-bold text-2xl border-2 border-white shadow-xl ring-2 ring-white/50">
                                    {{ strtoupper(substr($user->first_name ?? 'S', 0, 1)) }}
                                </div>
                            @endif
                            
                            <!-- Status Indicator -->
                            @if($lastActivity && $isRecent)
                            <div class="absolute -bottom-0.5 -right-0.5 w-5 h-5 bg-green-500 rounded-full border-2 border-white shadow-md"></div>
                            @elseif($lastActivity && $isLongInactive)
                            <div class="absolute -bottom-0.5 -right-0.5 w-5 h-5 bg-red-500 rounded-full border-2 border-white shadow-md"></div>
                            @else
                            <div class="absolute -bottom-0.5 -right-0.5 w-5 h-5 bg-gray-400 rounded-full border-2 border-white shadow-md"></div>
                            @endif
                        </div>
                    </div>
                </div>
                
                <!-- Content Section -->
                <div class="p-4 bg-white">
                    <!-- Name and Role -->
                    <div class="text-center mb-3">
                        <a href="{{ route('admin.students.show', $student->student_id) }}" 
                           class="text-lg font-bold text-gray-900 hover:text-pink-600 transition-colors block mb-1 group-hover:scale-105 transform duration-300">
                            {{ $user->first_name ?? 'Unknown' }} {{ $user->last_name ?? '' }}
                        </a>
                        <!-- Status Badge -->
                        <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full {{ $statusData['bg'] }} {{ $statusData['border'] }} border text-xs font-semibold {{ $statusData['text'] }} mt-2">
                            <span class="w-2 h-2 rounded-full {{ $activityStatus === 'active' ? 'bg-green-500' : ($activityStatus === 'inactive' ? 'bg-orange-500' : 'bg-red-500') }}"></span>
                            <span class="capitalize">{{ $activityStatus }}</span>
                        </div>
                    </div>
                    
                    <!-- Information Grid -->
                    <div class="space-y-2.5">
                        <!-- Contact Info -->
                        <div class="grid grid-cols-1 gap-2">
                            <div class="flex items-center gap-2 p-2 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                                <div class="w-8 h-8 rounded-lg bg-pink-100 flex items-center justify-center flex-shrink-0">
                                    <svg class="w-4 h-4 text-pink-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                    </svg>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-xs text-gray-500 mb-0.5">Email</p>
                                    <p class="text-xs font-semibold text-gray-900 truncate">{{ $user->email ?? 'N/A' }}</p>
                                </div>
                            </div>
                            
                        </div>
                        
                        <!-- Class -->
                        <div class="p-2 bg-blue-50 rounded-lg border border-blue-100">
                            <p class="text-xs text-blue-600 font-semibold mb-0.5">Class</p>
                            @if($class)
                            <p class="text-xs font-bold text-blue-900">{{ $class->class_name }}</p>
                            @else
                            <p class="text-xs font-bold text-gray-500">No Class</p>
                            @endif
                        </div>
                        
                        <!-- Activity & Joined -->
                        <div class="grid grid-cols-2 gap-2 pt-2 border-t border-gray-100">
                            <!-- Last Activity -->
                            <div>
                                <p class="text-xs text-gray-500 mb-1.5">Last Activity</p>
                                @if($lastActivity)
                                    <div class="flex items-center gap-2">
                                        <span class="w-2.5 h-2.5 rounded-full {{ $isRecent ? 'bg-green-500' : ($isLongInactive ? 'bg-red-500' : 'bg-orange-500') }}"></span>
                                        <p class="text-xs font-semibold {{ $isRecent ? 'text-green-600' : ($isLongInactive ? 'text-red-600' : 'text-orange-600') }}">
                                            @if($isRecent)
                                                Active
                                            @else
                                                {{ $inactiveDays }}d ago
                                            @endif
                                        </p>
                                    </div>
                                    <p class="text-xs text-gray-400 mt-1">{{ $lastActivity->format('M d') }}</p>
                                @else
                                    <p class="text-xs font-semibold text-red-600">Never active</p>
                                @endif
                            </div>
                            
                            <!-- Joined -->
                            <div>
                                <p class="text-xs text-gray-500 mb-1.5">Joined</p>
                                <p class="text-xs font-semibold text-gray-900">
                                    {{ ($user->date_joined ?? null) ? $user->date_joined->format('M d, Y') : 'N/A' }}
                                </p>
                                @if($user->date_joined ?? null)
                                <p class="text-xs text-gray-400 mt-1">{{ $user->date_joined->diffForHumans() }}</p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endif
            @empty
            <div class="col-span-full">
                <div class="bg-white rounded-3xl shadow-xl p-16 text-center border border-gray-100">
                    <div class="flex flex-col items-center gap-4">
                        <div class="w-20 h-20 bg-gradient-to-br from-pink-100 to-teal-100 rounded-full flex items-center justify-center">
                            <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                            </svg>
                        </div>
                        <div class="text-gray-500 font-semibold text-lg">No students found</div>
                        <div class="text-sm text-gray-400">Try adjusting your search or filters</div>
                    </div>
                </div>
            </div>
            @endforelse
        </div>
    </div>
</div>

<script>
    // Search and Filter Functionality
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('searchInput');
        const statusFilter = document.getElementById('statusFilter');
        const studentsGrid = document.getElementById('studentsGrid');
        const cards = studentsGrid.querySelectorAll('.student-card');
        const studentCountElement = document.getElementById('studentCount');

        function filterCards() {
            const searchTerm = searchInput.value.toLowerCase();
            const statusValue = statusFilter.value;
            let visibleCount = 0;

            cards.forEach(card => {
                const name = card.dataset.name || '';
                const email = card.dataset.email || '';
                const className = card.dataset.class || '';
                const city = card.dataset.city || '';
                const status = card.dataset.status || '';

                const matchesSearch = !searchTerm || 
                    name.includes(searchTerm) || 
                    email.includes(searchTerm) || 
                    className.includes(searchTerm) || 
                    city.includes(searchTerm);

                const matchesStatus = !statusValue || status === statusValue;

                if (matchesSearch && matchesStatus) {
                    card.style.display = '';
                    visibleCount++;
                } else {
                    card.style.display = 'none';
                }
            });

            // Update student count badge
            if (studentCountElement) {
                studentCountElement.textContent = visibleCount;
            }
        }

        searchInput.addEventListener('input', filterCards);
        statusFilter.addEventListener('change', filterCards);
    });
</script>
@endsection
