@extends('layouts.admin')

@section('content')
<div class="min-h-screen" style="background: linear-gradient(135deg, #FFF4FA 0%, #FDF2F8 30%, #F0F9FF 70%, #E0F7FA 100%);">
    <!-- Header -->
    <div class="bg-gradient-to-r from-pink-200/90 via-rose-100/80 to-cyan-200/90 shadow-2xl border-b-4 border-pink-300/50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            <div class="flex items-center gap-4">
                <a href="{{ route('admin.students.index') }}" 
                   class="bg-white/60 hover:bg-white/80 backdrop-blur-sm text-gray-700 p-3 rounded-xl transition-all duration-200 shadow-md hover:shadow-lg">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                </a>
                <div>
                    <h1 class="text-3xl font-bold text-gray-800 mb-1 drop-shadow-sm">Student Profile</h1>
                    <p class="text-gray-700 text-sm font-medium">{{ $student->user->first_name }} {{ $student->user->last_name }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Content -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
            
            <!-- Left Column - Profile Card -->
            <div class="lg:col-span-4">
                <div class="bg-white rounded-2xl shadow-xl overflow-hidden border border-gray-100 sticky top-6">
                    <!-- Profile Header -->
                    <div class="bg-gradient-to-br from-pink-300 via-rose-200 to-cyan-300 p-8 text-center relative overflow-hidden">
                        <div class="absolute inset-0 bg-white/20"></div>
                        <div class="relative">
                            <div class="w-28 h-28 mx-auto rounded-full bg-white/70 backdrop-blur-md flex items-center justify-center text-gray-700 text-5xl font-bold mb-4 border-4 border-white/70 shadow-lg">
                                {{ strtoupper(substr($student->user->first_name ?? 'S', 0, 1)) }}
                            </div>
                            <h2 class="text-2xl font-bold text-gray-800 mb-2">{{ $student->user->first_name }} {{ $student->user->last_name }}</h2>
                            <div class="inline-flex items-center gap-2 px-4 py-1.5 bg-white/70 backdrop-blur-sm rounded-full text-gray-700 text-sm font-medium shadow-sm">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z"/>
                                </svg>
                                Student
                            </div>
                        </div>
                    </div>
                    
                    <!-- Profile Details -->
                    <div class="p-6 space-y-6">
                        <!-- Contact Information -->
                        <div>
                            <h3 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-4 flex items-center gap-2">
                                <span class="w-1 h-4 bg-gradient-to-b from-pink-300 to-cyan-300 rounded-full"></span>
                                Contact Information
                            </h3>
                            <div class="space-y-4">
                                <div class="flex items-start gap-3 p-3 rounded-xl bg-pink-50 hover:bg-pink-100 transition-colors border border-pink-100">
                                    <div class="w-10 h-10 bg-gradient-to-br from-pink-300 to-rose-300 rounded-lg flex items-center justify-center flex-shrink-0 shadow-sm">
                                        <svg class="w-5 h-5 text-pink-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                        </svg>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <div class="text-xs text-gray-500 mb-0.5">Email</div>
                                        <div class="text-sm font-semibold text-gray-900 truncate">{{ $student->user->email }}</div>
                                    </div>
                                </div>
                                
                                @if($student->user->country)
                                <div class="flex items-start gap-3 p-3 rounded-xl bg-cyan-50 hover:bg-cyan-100 transition-colors border border-cyan-100">
                                    <div class="w-10 h-10 bg-gradient-to-br from-cyan-300 to-teal-300 rounded-lg flex items-center justify-center flex-shrink-0 shadow-sm">
                                        <svg class="w-5 h-5 text-cyan-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <div class="text-xs text-gray-500 mb-0.5">Country</div>
                                        <div class="text-sm font-semibold text-gray-900">{{ $student->user->country }}</div>
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>

                        <!-- Enrollment Details -->
                        <div class="pt-6 border-t border-gray-200">
                            <h3 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-4 flex items-center gap-2">
                                <span class="w-1 h-4 bg-gradient-to-b from-rose-300 to-pink-300 rounded-full"></span>
                                Enrollment Details
                            </h3>
                            <div class="space-y-3">
                                <div class="flex items-center justify-between p-3 rounded-lg bg-gray-50">
                                    <span class="text-sm text-gray-600 font-medium">Date Joined</span>
                                    <span class="text-sm font-bold text-gray-900">
                                        {{ $student->user->date_joined ? $student->user->date_joined->format('M d, Y') : 'N/A' }}
                                    </span>
                                </div>
                                
                                @if($student->studentClass)
                                <div class="flex items-center justify-between p-3 rounded-lg bg-gray-50">
                                    <span class="text-sm text-gray-600 font-medium">Class</span>
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-bold bg-cyan-100 text-cyan-700 border border-cyan-300 shadow-sm">
                                        <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M10.394 2.08a1 1 0 00-.788 0l-7 3a1 1 0 000 1.84L5.25 8.051a.999.999 0 01.356-.257l4-1.714a1 1 0 11.788 1.838L7.667 9.088l1.94.831a1 1 0 00.787 0l7-3a1 1 0 000-1.838l-7-3zM3.31 9.397L5 10.12v4.102a8.969 8.969 0 00-1.05-.174 1 1 0 01-.89-.89 11.115 11.115 0 01.25-3.762zM9.3 16.573A9.026 9.026 0 007 14.935v-3.957l1.818.78a3 3 0 002.364 0l5.508-2.361a11.026 11.026 0 01.25 3.762 1 1 0 01-.89.89 8.968 8.968 0 00-5.35 2.524 1 1 0 01-1.4 0zM6 18a1 1 0 001-1v-2.065a8.935 8.935 0 00-2-.712V17a1 1 0 001 1z"/>
                                        </svg>
                                        {{ $student->studentClass->class_name }}
                                    </span>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column - Stats & Information -->
            <div class="lg:col-span-8 space-y-6">
                
                <!-- Performance Metrics -->
                <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
                    <div class="bg-gradient-to-r from-pink-200/90 via-rose-100/80 to-cyan-200/90 px-6 py-5 border-b-2 border-pink-300/50">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-white/60 backdrop-blur-sm rounded-lg flex items-center justify-center shadow-sm">
                                <svg class="w-6 h-6 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                                </svg>
                            </div>
                            <h2 class="text-xl font-bold text-gray-800">Performance Overview</h2>
                        </div>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
                            <!-- Total Score -->
                            <div class="group relative bg-gradient-to-br from-cyan-50 to-cyan-100 rounded-xl p-5 border-2 border-cyan-200 hover:border-cyan-300 transition-all duration-200 hover:shadow-lg">
                                <div class="flex items-center justify-between mb-2">
                                    <div class="w-10 h-10 bg-gradient-to-br from-cyan-300 to-teal-300 rounded-lg flex items-center justify-center shadow-sm">
                                        <svg class="w-5 h-5 text-cyan-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/>
                                        </svg>
                                    </div>
                                </div>
                                <div class="text-xs text-cyan-600 font-semibold uppercase tracking-wide mb-1">Total Score</div>
                                <div class="text-3xl font-bold text-cyan-900">{{ $student->total_score ?? 0 }}</div>
                            </div>

                            <!-- Lessons Completed -->
                            <div class="group relative bg-gradient-to-br from-pink-50 to-rose-50 rounded-xl p-5 border-2 border-pink-200 hover:border-pink-300 transition-all duration-200 hover:shadow-lg">
                                <div class="flex items-center justify-between mb-2">
                                    <div class="w-10 h-10 bg-gradient-to-br from-pink-300 to-rose-300 rounded-lg flex items-center justify-center shadow-sm">
                                        <svg class="w-5 h-5 text-pink-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                                        </svg>
                                    </div>
                                </div>
                                <div class="text-xs text-pink-600 font-semibold uppercase tracking-wide mb-1">Lessons</div>
                                <div class="text-3xl font-bold text-pink-900">{{ $completedLessons }}<span class="text-lg text-pink-600">/{{ $lessonProgress }}</span></div>
                            </div>

                            <!-- Quiz Attempts -->
                            <div class="group relative bg-gradient-to-br from-rose-50 to-pink-50 rounded-xl p-5 border-2 border-rose-200 hover:border-rose-300 transition-all duration-200 hover:shadow-lg">
                                <div class="flex items-center justify-between mb-2">
                                    <div class="w-10 h-10 bg-gradient-to-br from-rose-300 to-pink-300 rounded-lg flex items-center justify-center shadow-sm">
                                        <svg class="w-5 h-5 text-rose-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                        </svg>
                                    </div>
                                </div>
                                <div class="text-xs text-rose-600 font-semibold uppercase tracking-wide mb-1">Quiz Attempts</div>
                                <div class="text-3xl font-bold text-rose-900">{{ $quizAttempts }}</div>
                            </div>

                            <!-- Assignments -->
                            <div class="group relative bg-gradient-to-br from-cyan-50 to-teal-50 rounded-xl p-5 border-2 border-cyan-200 hover:border-cyan-300 transition-all duration-200 hover:shadow-lg">
                                <div class="flex items-center justify-between mb-2">
                                    <div class="w-10 h-10 bg-gradient-to-br from-cyan-300 to-teal-300 rounded-lg flex items-center justify-center shadow-sm">
                                        <svg class="w-5 h-5 text-cyan-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                        </svg>
                                    </div>
                                </div>
                                <div class="text-xs text-cyan-600 font-semibold uppercase tracking-wide mb-1">Assignments</div>
                                <div class="text-3xl font-bold text-cyan-900">{{ $assignmentSubmissions }}</div>
                            </div>
                        </div>

                        <!-- Average Grade Progress -->
                        @if($grades->count() > 0 && $averageGrade)
                        <div class="pt-6 border-t border-gray-200">
                            <div class="flex items-center justify-between mb-3">
                                <div class="flex items-center gap-2">
                                    <div class="w-8 h-8 bg-gradient-to-br from-pink-300 to-cyan-300 rounded-lg flex items-center justify-center">
                                        <svg class="w-4 h-4 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                                        </svg>
                                    </div>
                                    <span class="text-sm font-bold text-gray-700">Average Grade</span>
                                </div>
                                <span class="text-2xl font-bold bg-gradient-to-r from-pink-500 to-cyan-500 bg-clip-text text-transparent">{{ round($averageGrade, 1) }}%</span>
                            </div>
                            <div class="relative bg-gray-200 rounded-full h-4 overflow-hidden shadow-inner">
                                <div class="absolute inset-0 bg-gradient-to-r from-pink-300 via-rose-300 to-cyan-300 h-4 rounded-full transition-all duration-1000 ease-out" 
                                     style="width: {{ min(100, $averageGrade) }}%"></div>
                                <div class="absolute inset-0 flex items-center justify-center">
                                    <span class="text-xs font-bold text-gray-700">{{ round($averageGrade, 1) }}%</span>
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Personal Information -->
                <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
                    <div class="bg-gradient-to-r from-cyan-200/90 to-teal-200/90 px-6 py-5 border-b-2 border-cyan-300/50">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-white/60 backdrop-blur-sm rounded-lg flex items-center justify-center shadow-sm">
                                <svg class="w-6 h-6 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                </svg>
                            </div>
                            <h2 class="text-xl font-bold text-gray-800">Personal Information</h2>
                        </div>
                    </div>
                    <div class="p-6 space-y-4">
                        @if($student->date_of_birth)
                        <div class="p-4 rounded-xl bg-gradient-to-br from-pink-50 to-rose-50 border border-pink-200">
                            <div class="text-xs text-gray-500 uppercase tracking-wide mb-1 font-semibold">Date of Birth</div>
                            <div class="text-base font-bold text-gray-900 mb-1">{{ $student->date_of_birth->format('F d, Y') }}</div>
                            <div class="text-xs text-gray-500">Age: <span class="font-semibold text-gray-700">{{ $student->date_of_birth->age }} years</span></div>
                        </div>
                        @endif

                        @if($student->city)
                        <div class="p-4 rounded-xl bg-gradient-to-br from-cyan-50 to-teal-50 border border-cyan-200">
                            <div class="text-xs text-gray-500 uppercase tracking-wide mb-1 font-semibold">Location</div>
                            <div class="text-base font-bold text-gray-900">{{ $student->city }}</div>
                            @if($student->street)
                            <div class="text-xs text-gray-500 mt-1">{{ $student->street }}</div>
                            @endif
                        </div>
                        @endif

                        @if($student->language)
                        <div class="p-4 rounded-xl bg-gradient-to-br from-rose-50 to-pink-50 border border-rose-200">
                            <div class="text-xs text-gray-500 uppercase tracking-wide mb-1 font-semibold">Language</div>
                            <div class="text-base font-bold text-gray-900">{{ $student->language }}</div>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Payment History -->
                @if($payments->count() > 0)
                <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
                    <div class="bg-gradient-to-r from-pink-200/90 via-rose-100/80 to-cyan-200/90 px-6 py-5 border-b-2 border-pink-300/50">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-white/60 backdrop-blur-sm rounded-lg flex items-center justify-center shadow-sm">
                                <svg class="w-6 h-6 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-6 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                                </svg>
                            </div>
                            <h2 class="text-xl font-bold text-gray-800">Payment History</h2>
                        </div>
                    </div>
                    <div class="p-6">
                        <div class="overflow-x-auto">
                            <table class="w-full">
                                <thead>
                                    <tr class="bg-gradient-to-r from-gray-50 to-gray-100">
                                        <th class="px-5 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Date</th>
                                        <th class="px-5 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Amount</th>
                                        <th class="px-5 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Status</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200">
                                    @foreach($payments->take(10) as $payment)
                                    <tr class="hover:bg-gradient-to-r hover:from-pink-50 hover:to-cyan-50 transition-colors">
                                        <td class="px-5 py-4 text-sm font-medium text-gray-900">
                                            {{ $payment->created_at->format('M d, Y') }}
                                        </td>
                                        <td class="px-5 py-4 text-sm font-bold text-gray-900">
                                            ${{ number_format($payment->amount ?? 0, 2) }}
                                        </td>
                                        <td class="px-5 py-4">
                                            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-bold bg-green-100 text-green-700 border border-green-300 shadow-sm">
                                                <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                                </svg>
                                                Paid
                                            </span>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                @endif

            </div>
        </div>
    </div>
</div>
@endsection
