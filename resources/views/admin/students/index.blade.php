@extends('layouts.admin')

@section('content')
<div class="min-h-screen">
    <!-- Header -->
    <div class="bg-gradient-to-r from-[#FC8EAC] via-[#EC769A] to-[#6EC6C5] shadow-xl">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-4xl font-extrabold text-white mb-2">👧 All Students</h1>
                    <p class="text-pink-100">Manage and view all registered students</p>
                </div>
                <a href="{{ route('admin.students.export') }}" 
                   class="bg-white hover:bg-pink-50 text-pink-600 font-semibold px-6 py-3 rounded-xl shadow-lg transition-all flex items-center gap-2">
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
                    <input type="text" id="searchInput" placeholder="🔍 Search by name, email, class, or city..." 
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-pink-500 focus:border-transparent">
                </div>
                <div class="flex gap-2">
                    <select id="planFilter" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-pink-500">
                        <option value="">All Plans</option>
                        <option value="basic">Basic</option>
                        <option value="premium">Premium</option>
                        <option value="pro">Pro</option>
                    </select>
                    <select id="statusFilter" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-pink-500">
                        <option value="">All Status</option>
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                        <option value="expired">Expired</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Improved Students List -->
        <div class="bg-white rounded-2xl shadow-xl overflow-hidden border border-gray-100">
            <!-- Table Header -->
            <div class="bg-gradient-to-r from-pink-500 via-rose-500 to-teal-500 px-6 py-4">
                <div class="flex items-center justify-between">
                    <h2 class="text-xl font-bold text-white">Students List</h2>
                    <div class="text-white/90 text-sm">
                        <span class="font-semibold">{{ $students->count() }}</span> total students
                    </div>
                </div>
            </div>

            <!-- Students Table -->
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gradient-to-r from-gray-50 to-pink-50 border-b-2 border-pink-200">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider text-gray-700">Student</th>
                            <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider text-gray-700">Contact</th>
                            <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider text-gray-700">Class</th>
                            <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider text-gray-700">Score</th>
                            <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider text-gray-700">Plan</th>
                            <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider text-gray-700">Joined</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100" id="studentsTableBody">
                        @forelse($students as $student)
                        @php
                            $user = $student->user;
                            $class = $student->studentClass;
                            $planColors = [
                                'basic' => 'bg-blue-50 text-blue-700 border-blue-200',
                                'premium' => 'bg-purple-50 text-purple-700 border-purple-200',
                                'pro' => 'bg-amber-50 text-amber-700 border-amber-200'
                            ];
                            $planClass = $planColors[$student->plan_type ?? 'basic'] ?? 'bg-gray-50 text-gray-700 border-gray-200';
                        @endphp
                        @if($user)
                        <tr class="hover:bg-gradient-to-r hover:from-pink-50 hover:to-rose-50 transition-all duration-200 student-row group" 
                            data-name="{{ strtolower(($user->first_name ?? '') . ' ' . ($user->last_name ?? '')) }}"
                            data-email="{{ strtolower($user->email ?? '') }}"
                            data-class="{{ strtolower($class->class_name ?? '') }}"
                            data-city="{{ strtolower($student->city ?? '') }}"
                            data-plan="{{ $student->plan_type ?? '' }}"
                            data-status="{{ ($student->subscription_status ?? 'inactive') }}">
                            <!-- Student Info -->
                            <td class="px-6 py-5">
                                <div class="flex items-center gap-4">
                                    <div class="relative">
                                        <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-pink-400 via-rose-400 to-teal-400 flex items-center justify-center text-white font-bold text-lg shadow-lg group-hover:scale-110 transition-transform duration-200">
                                            {{ strtoupper(substr($user->first_name ?? 'S', 0, 1)) }}
                                        </div>
                                        <div class="absolute -bottom-1 -right-1 w-5 h-5 bg-green-400 rounded-full border-2 border-white"></div>
                                    </div>
                                        <div class="flex-1">
                                        <div class="flex items-center gap-2">
                                            <a href="{{ route('admin.students.show', $student->student_id) }}" 
                                               class="text-sm font-bold text-gray-900 group-hover:text-pink-600 transition-colors hover:underline">
                                                {{ $user->first_name ?? 'Unknown' }} {{ $user->last_name ?? '' }}
                                            </a>
                                            <a href="{{ route('admin.students.show', $student->student_id) }}" 
                                               class="text-pink-600 hover:text-pink-700 opacity-0 group-hover:opacity-100 transition-opacity">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                                </svg>
                                            </a>
                                        </div>
                                        <div class="text-xs text-gray-500 mt-0.5 flex items-center gap-1">
                                            <span>👧</span>
                                            <span>Student</span>
                                        </div>
                                    </div>
                                </div>
                            </td>
                            
                            <!-- Contact -->
                            <td class="px-6 py-5">
                                <div class="text-sm font-medium text-gray-900">{{ $user->email ?? 'N/A' }}</div>
                                @if($user->phone_number ?? null)
                                <div class="text-xs text-gray-500 mt-1 flex items-center gap-1">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                                    </svg>
                                    {{ $user->phone_number }}
                                </div>
                                @endif
                            </td>
                            
                            <!-- Class -->
                            <td class="px-6 py-5">
                                @if($class)
                                <span class="inline-flex items-center gap-2 px-4 py-2 rounded-lg text-xs font-semibold bg-blue-50 text-blue-700 border border-blue-200 shadow-sm">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                                    </svg>
                                    {{ $class->class_name }}
                                </span>
                                @else
                                <span class="inline-flex items-center px-3 py-1.5 rounded-lg text-xs font-medium bg-gray-100 text-gray-500 border border-gray-200">
                                    No Class
                                </span>
                                @endif
                            </td>
                            
                            <!-- Score -->
                            <td class="px-6 py-5">
                                <div class="flex items-center gap-3">
                                    <div class="text-center">
                                        <div class="text-lg font-bold text-gray-900">{{ $student->total_score ?? 0 }}</div>
                                        <div class="text-xs text-gray-500">points</div>
                                    </div>
                                    <div class="flex-1 min-w-[80px]">
                                        <div class="bg-gray-200 rounded-full h-2.5 overflow-hidden shadow-inner">
                                            <div class="bg-gradient-to-r from-pink-400 via-rose-400 to-teal-400 h-2.5 rounded-full transition-all duration-500 shadow-sm" 
                                                 style="width: {{ min(100, (($student->total_score ?? 0) / 1000) * 100) }}%"></div>
                                        </div>
                                        <div class="text-xs text-gray-400 mt-1 text-center">
                                            {{ min(100, round((($student->total_score ?? 0) / 1000) * 100)) }}%
                                        </div>
                                    </div>
                                </div>
                            </td>
                            
                            <!-- Plan -->
                            <td class="px-6 py-5">
                                <span class="inline-flex items-center gap-2 px-4 py-2 rounded-lg text-xs font-semibold border shadow-sm {{ $planClass }}">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                    </svg>
                                    {{ ucfirst($student->plan_type ?? 'Basic') }}
                                </span>
                            </td>
                            
                            <!-- Joined Date -->
                            <td class="px-6 py-5">
                                <div class="text-sm font-medium text-gray-900">{{ ($user->date_joined ?? null) ? $user->date_joined->format('M d, Y') : 'N/A' }}</div>
                                @if($user->date_joined ?? null)
                                <div class="text-xs text-gray-500 mt-1 flex items-center gap-1">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    {{ $user->date_joined->diffForHumans() }}
                                </div>
                                @endif
                            </td>
                        </tr>
                        @endif
                        @empty
                        <tr>
                            <td colspan="6" class="px-6 py-16 text-center">
                                <div class="flex flex-col items-center gap-3">
                                    <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center">
                                        <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                                        </svg>
                                    </div>
                                    <div class="text-gray-500 font-medium">No students found</div>
                                    <div class="text-sm text-gray-400">Try adjusting your search or filters</div>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
    // Search and Filter Functionality
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('searchInput');
        const planFilter = document.getElementById('planFilter');
        const statusFilter = document.getElementById('statusFilter');
        const tableBody = document.getElementById('studentsTableBody');
        const rows = tableBody.querySelectorAll('.student-row');

        function filterRows() {
            const searchTerm = searchInput.value.toLowerCase();
            const planValue = planFilter.value;
            const statusValue = statusFilter.value;

            rows.forEach(row => {
                const name = row.dataset.name || '';
                const email = row.dataset.email || '';
                const className = row.dataset.class || '';
                const city = row.dataset.city || '';
                const plan = row.dataset.plan || '';
                const status = row.dataset.status || '';

                const matchesSearch = !searchTerm || 
                    name.includes(searchTerm) || 
                    email.includes(searchTerm) || 
                    className.includes(searchTerm) || 
                    city.includes(searchTerm);

                const matchesPlan = !planValue || plan === planValue;
                const matchesStatus = !statusValue || status === statusValue;

                if (matchesSearch && matchesPlan && matchesStatus) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        }

        searchInput.addEventListener('input', filterRows);
        planFilter.addEventListener('change', filterRows);
        statusFilter.addEventListener('change', filterRows);
    });
</script>
@endsection
