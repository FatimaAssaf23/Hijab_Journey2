@extends('layouts.admin')

@section('content')
<div class="min-h-screen">
    <!-- Header -->
    <div class="bg-gradient-to-r from-[#FC8EAC] via-[#EC769A] to-[#6EC6C5] shadow-xl">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <a href="{{ route('admin.students.index') }}" 
                       class="bg-white/20 hover:bg-white/30 text-white p-2 rounded-lg transition-all">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                        </svg>
                    </a>
                    <div>
                        <h1 class="text-4xl font-extrabold text-white mb-2">👧 Student Profile</h1>
                        <p class="text-pink-100">View detailed information about {{ $student->user->first_name }} {{ $student->user->last_name }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Content -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Left Column - Profile Info -->
            <div class="lg:col-span-1 space-y-6">
                <!-- Profile Card -->
                <div class="bg-white rounded-2xl shadow-xl overflow-hidden border border-gray-100">
                    <div class="bg-gradient-to-r from-pink-500 to-teal-500 p-6 text-center">
                        <div class="w-24 h-24 mx-auto rounded-full bg-white/20 backdrop-blur-md flex items-center justify-center text-white text-4xl font-bold mb-4 border-4 border-white/30">
                            {{ strtoupper(substr($student->user->first_name ?? 'S', 0, 1)) }}
                        </div>
                        <h2 class="text-2xl font-bold text-white mb-1">{{ $student->user->first_name }} {{ $student->user->last_name }}</h2>
                        <p class="text-white/90 text-sm">👧 Student</p>
                    </div>
                    
                    <div class="p-6 space-y-4">
                        <!-- Contact Info -->
                        <div>
                            <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wide mb-3">Contact Information</h3>
                            <div class="space-y-3">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 bg-pink-100 rounded-lg flex items-center justify-center">
                                        <svg class="w-5 h-5 text-pink-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <div class="text-xs text-gray-500">Email</div>
                                        <div class="text-sm font-medium text-gray-900">{{ $student->user->email }}</div>
                                    </div>
                                </div>
                                
                                @if($student->user->phone_number)
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 bg-teal-100 rounded-lg flex items-center justify-center">
                                        <svg class="w-5 h-5 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <div class="text-xs text-gray-500">Phone</div>
                                        <div class="text-sm font-medium text-gray-900">{{ $student->user->phone_number }}</div>
                                    </div>
                                </div>
                                @endif

                                @if($student->user->country)
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <div class="text-xs text-gray-500">Country</div>
                                        <div class="text-sm font-medium text-gray-900">{{ $student->user->country }}</div>
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>

                        <div class="border-t pt-4">
                            <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wide mb-3">Enrollment Details</h3>
                            <div class="space-y-3">
                                <div class="flex items-center justify-between">
                                    <span class="text-sm text-gray-600">Date Joined</span>
                                    <span class="text-sm font-medium text-gray-900">
                                        {{ $student->user->date_joined ? $student->user->date_joined->format('M d, Y') : 'N/A' }}
                                    </span>
                                </div>
                                
                                @if($student->studentClass)
                                <div class="flex items-center justify-between">
                                    <span class="text-sm text-gray-600">Class</span>
                                    <span class="inline-flex items-center px-3 py-1 rounded-lg text-xs font-semibold bg-blue-50 text-blue-700 border border-blue-200">
                                        🎓 {{ $student->studentClass->class_name }}
                                    </span>
                                </div>
                                @endif

                                <div class="flex items-center justify-between">
                                    <span class="text-sm text-gray-600">Plan Type</span>
                                    <span class="inline-flex items-center px-3 py-1 rounded-lg text-xs font-semibold bg-purple-50 text-purple-700 border border-purple-200">
                                        💎 {{ ucfirst($student->plan_type ?? 'Basic') }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column - Stats & Activities -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Performance Overview -->
                <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
                    <div class="bg-gradient-to-r from-pink-500 to-rose-500 px-6 py-4">
                        <h2 class="text-xl font-bold text-white">📊 Performance Overview</h2>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                            <div class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-xl p-4 border border-blue-200">
                                <div class="text-sm text-blue-600 font-medium mb-1">Total Score</div>
                                <div class="text-2xl font-bold text-blue-900">{{ $student->total_score ?? 0 }}</div>
                            </div>
                            <div class="bg-gradient-to-br from-green-50 to-green-100 rounded-xl p-4 border border-green-200">
                                <div class="text-sm text-green-600 font-medium mb-1">Lessons Completed</div>
                                <div class="text-2xl font-bold text-green-900">{{ $completedLessons }} / {{ $lessonProgress }}</div>
                            </div>
                            <div class="bg-gradient-to-br from-purple-50 to-purple-100 rounded-xl p-4 border border-purple-200">
                                <div class="text-sm text-purple-600 font-medium mb-1">Quiz Attempts</div>
                                <div class="text-2xl font-bold text-purple-900">{{ $quizAttempts }}</div>
                            </div>
                            <div class="bg-gradient-to-br from-orange-50 to-orange-100 rounded-xl p-4 border border-orange-200">
                                <div class="text-sm text-orange-600 font-medium mb-1">Assignments</div>
                                <div class="text-2xl font-bold text-orange-900">{{ $assignmentSubmissions }}</div>
                            </div>
                        </div>

                        @if($grades->count() > 0 && $averageGrade)
                        <div class="mt-6 pt-6 border-t">
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-sm font-medium text-gray-700">Average Grade</span>
                                <span class="text-2xl font-bold text-gray-900">{{ round($averageGrade, 1) }}%</span>
                            </div>
                            <div class="bg-gray-200 rounded-full h-3 overflow-hidden">
                                <div class="bg-gradient-to-r from-pink-400 to-teal-400 h-3 rounded-full transition-all duration-500" 
                                     style="width: {{ min(100, $averageGrade) }}%"></div>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Personal Information -->
                <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
                    <div class="bg-gradient-to-r from-teal-500 to-cyan-500 px-6 py-4">
                        <h2 class="text-xl font-bold text-white">ℹ️ Personal Information</h2>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            @if($student->date_of_birth)
                            <div>
                                <div class="text-xs text-gray-500 uppercase tracking-wide mb-1">Date of Birth</div>
                                <div class="text-sm font-medium text-gray-900">{{ $student->date_of_birth->format('F d, Y') }}</div>
                                <div class="text-xs text-gray-500 mt-1">Age: {{ $student->date_of_birth->age }} years</div>
                            </div>
                            @endif

                            @if($student->city)
                            <div>
                                <div class="text-xs text-gray-500 uppercase tracking-wide mb-1">City</div>
                                <div class="text-sm font-medium text-gray-900">{{ $student->city }}</div>
                                @if($student->street)
                                <div class="text-xs text-gray-500 mt-1">{{ $student->street }}</div>
                                @endif
                            </div>
                            @endif

                            @if($student->language)
                            <div>
                                <div class="text-xs text-gray-500 uppercase tracking-wide mb-1">Language</div>
                                <div class="text-sm font-medium text-gray-900">{{ $student->language }}</div>
                            </div>
                            @endif

                            <div>
                                <div class="text-xs text-gray-500 uppercase tracking-wide mb-1">Subscription Status</div>
                                @php
                                    $isExpired = $student->subscription_expires_at && $student->subscription_expires_at < now();
                                    $statusClass = $isExpired ? 'bg-red-100 text-red-700 border-red-200' : 
                                                  ($student->subscription_status === 'active' ? 'bg-green-100 text-green-700 border-green-200' : 'bg-gray-100 text-gray-700 border-gray-200');
                                @endphp
                                <span class="inline-flex items-center px-3 py-1 rounded-lg text-xs font-semibold border {{ $statusClass }}">
                                    @if($isExpired)
                                        ⚠️ Expired
                                    @elseif($student->subscription_status === 'active')
                                        ✅ Active
                                    @else
                                        ⏸️ Inactive
                                    @endif
                                </span>
                                @if($student->subscription_expires_at)
                                <div class="text-xs text-gray-500 mt-1">
                                    Expires: {{ $student->subscription_expires_at->format('M d, Y') }}
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Payment History -->
                @if($payments->count() > 0)
                <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
                    <div class="bg-gradient-to-r from-green-500 to-emerald-500 px-6 py-4">
                        <h2 class="text-xl font-bold text-white">💳 Payment History</h2>
                    </div>
                    <div class="p-6">
                        <div class="overflow-x-auto">
                            <table class="w-full">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Date</th>
                                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Amount</th>
                                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Status</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200">
                                    @foreach($payments->take(10) as $payment)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-4 py-3 text-sm text-gray-900">
                                            {{ $payment->created_at->format('M d, Y') }}
                                        </td>
                                        <td class="px-4 py-3 text-sm font-medium text-gray-900">
                                            ${{ number_format($payment->amount ?? 0, 2) }}
                                        </td>
                                        <td class="px-4 py-3">
                                            <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-green-100 text-green-700">
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
