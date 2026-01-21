@extends('layouts.admin')

@section('content')
<div class="min-h-screen">
    <!-- Header -->
    <div class="bg-gradient-to-r from-[#FC8EAC] via-[#EC769A] to-[#6EC6C5] shadow-xl">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-4xl font-extrabold text-white mb-2">👩‍🏫 All Teachers</h1>
                    <p class="text-pink-100">Manage and view all registered teachers</p>
                </div>
                <a href="{{ route('admin.teachers.export') }}" 
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
        <!-- Search Bar -->
        <div class="bg-white rounded-xl shadow-lg p-4 mb-6">
            <input type="text" id="searchInput" placeholder="🔍 Search by name, email, language, or bio..." 
                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-pink-500 focus:border-transparent">
        </div>

        <!-- Improved Teachers List -->
        <div class="bg-white rounded-2xl shadow-xl overflow-hidden border border-gray-100">
            <!-- Table Header -->
            <div class="bg-gradient-to-r from-pink-500 via-rose-500 to-teal-500 px-6 py-4">
                <div class="flex items-center justify-between">
                    <h2 class="text-xl font-bold text-white">Teachers List</h2>
                    <div class="text-white/90 text-sm">
                        <span class="font-semibold">{{ $teachers->count() }}</span> total teachers
                    </div>
                </div>
            </div>

            <!-- Teachers Table -->
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gradient-to-r from-gray-50 to-pink-50 border-b-2 border-pink-200">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider text-gray-700">Teacher</th>
                            <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider text-gray-700">Contact</th>
                            <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider text-gray-700">Language</th>
                            <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider text-gray-700">Classes</th>
                            <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider text-gray-700">Bio</th>
                            <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider text-gray-700">Joined</th>
                            <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider text-gray-700">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100" id="teachersTableBody">
                        @forelse($teachers as $teacher)
                        @php
                            $user = $teacher->user;
                            $profile = $user ? ($user->teacherProfile ?? null) : null;
                            $classesCount = $teacher->classes_count ?? 0;
                        @endphp
                        @if($user)
                        <tr class="hover:bg-gradient-to-r hover:from-pink-50 hover:to-rose-50 transition-all duration-200 teacher-row group" 
                            data-name="{{ strtolower(($user->first_name ?? '') . ' ' . ($user->last_name ?? '')) }}"
                            data-email="{{ strtolower($user->email ?? '') }}"
                            data-language="{{ strtolower($user->language ?? '') }}"
                            data-bio="{{ strtolower($profile->bio ?? '') }}">
                            <!-- Teacher Info -->
                            <td class="px-6 py-5">
                                <div class="flex items-center gap-4">
                                    <div class="relative">
                                        @if($profile && $profile->profile_photo_path)
                                        <img src="{{ asset('storage/' . $profile->profile_photo_path) }}" 
                                             alt="{{ $user->first_name ?? 'Teacher' }}" 
                                             class="w-12 h-12 rounded-xl object-cover border-2 border-pink-300 shadow-lg group-hover:scale-110 transition-transform duration-200">
                                        @else
                                        <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-pink-400 via-rose-400 to-teal-400 flex items-center justify-center text-white font-bold text-lg shadow-lg group-hover:scale-110 transition-transform duration-200">
                                            {{ strtoupper(substr($user->first_name ?? ($user->email ?? 'T'), 0, 1)) }}
                                        </div>
                                        @endif
                                        <div class="absolute -bottom-1 -right-1 w-5 h-5 bg-green-400 rounded-full border-2 border-white"></div>
                                    </div>
                                    <div class="flex-1">
                                        <div class="flex items-center gap-2">
                                            <a href="{{ route('admin.teachers.show', $teacher->teacher_id) }}" 
                                               class="text-sm font-bold text-gray-900 group-hover:text-pink-600 transition-colors hover:underline">
                                                {{ $user->first_name ?? 'N/A' }} {{ $user->last_name ?? '' }}
                                            </a>
                                            <a href="{{ route('admin.teachers.show', $teacher->teacher_id) }}" 
                                               class="text-pink-600 hover:text-pink-700 opacity-0 group-hover:opacity-100 transition-opacity">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                                </svg>
                                            </a>
                                        </div>
                                        @if($profile && $profile->bio)
                                        <div class="text-xs text-gray-500 mt-0.5 truncate max-w-xs">{{ Str::limit($profile->bio, 35) }}</div>
                                        @endif
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
                                @else
                                <div class="text-xs text-gray-400 mt-1">No phone</div>
                                @endif
                            </td>
                            
                            <!-- Language -->
                            <td class="px-6 py-5">
                                @if($user->language ?? null)
                                <span class="inline-flex items-center gap-2 px-3 py-1.5 rounded-lg text-xs font-semibold bg-purple-50 text-purple-700 border border-purple-200 shadow-sm">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5h12M9 3v2m1.048 9.5A18.022 18.022 0 016.412 9m6.088 9h7M11 21l5-10 5 10M12.751 5C11.783 10.77 8.07 15.61 3 18.129"/>
                                    </svg>
                                    {{ $user->language }}
                                </span>
                                @else
                                <span class="text-sm text-gray-400">N/A</span>
                                @endif
                            </td>
                            
                            <!-- Classes -->
                            <td class="px-6 py-5">
                                @if($classesCount > 0)
                                <span class="inline-flex items-center gap-2 px-4 py-2 rounded-lg text-xs font-semibold bg-blue-50 text-blue-700 border border-blue-200 shadow-sm">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                                    </svg>
                                    {{ $classesCount }} {{ Str::plural('Class', $classesCount) }}
                                </span>
                                @else
                                <span class="inline-flex items-center px-3 py-1.5 rounded-lg text-xs font-medium bg-gray-100 text-gray-500 border border-gray-200">
                                    No Classes
                                </span>
                                @endif
                            </td>
                            
                            <!-- Bio -->
                            <td class="px-6 py-5">
                                @if($profile && $profile->bio)
                                <div class="text-sm text-gray-700 max-w-xs">
                                    <div class="line-clamp-2">{{ Str::limit($profile->bio, 80) }}</div>
                                </div>
                                @else
                                <span class="text-sm text-gray-400 italic">No bio available</span>
                                @endif
                            </td>
                            
                            <!-- Joined Date -->
                            <td class="px-6 py-5 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{ $user->date_joined ? $user->date_joined->format('M d, Y') : 'N/A' }}</div>
                                @if($user->date_joined ?? null)
                                <div class="text-xs text-gray-500 mt-1 flex items-center gap-1">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    {{ $user->date_joined->diffForHumans() }}
                                </div>
                                @endif
                            </td>
                            
                            <!-- Status -->
                            <td class="px-6 py-5">
                                @if($classesCount > 0)
                                <span class="inline-flex items-center px-3 py-1.5 rounded-lg text-xs font-semibold bg-green-50 text-green-700 border border-green-200 shadow-sm">
                                    ✅ Active
                                </span>
                                @else
                                <span class="inline-flex items-center px-3 py-1.5 rounded-lg text-xs font-semibold bg-yellow-50 text-yellow-700 border border-yellow-200 shadow-sm">
                                    ⏳ Available
                                </span>
                                @endif
                            </td>
                        </tr>
                        @endif
                        @empty
                        <tr>
                            <td colspan="7" class="px-6 py-16 text-center">
                                <div class="flex flex-col items-center gap-3">
                                    <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center">
                                        <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                                        </svg>
                                    </div>
                                    <div class="text-gray-500 font-medium">No teachers found</div>
                                    <div class="text-sm text-gray-400">Try adjusting your search</div>
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
    // Search Functionality
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('searchInput');
        const tableBody = document.getElementById('teachersTableBody');
        const rows = tableBody.querySelectorAll('.teacher-row');

        searchInput.addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();

            rows.forEach(row => {
                const name = row.dataset.name || '';
                const email = row.dataset.email || '';
                const language = row.dataset.language || '';
                const bio = row.dataset.bio || '';

                const matches = !searchTerm || 
                    name.includes(searchTerm) || 
                    email.includes(searchTerm) || 
                    language.includes(searchTerm) || 
                    bio.includes(searchTerm);

                row.style.display = matches ? '' : 'none';
            });
        });
    });
</script>
@endsection
