@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Success/Error Messages -->
    @if(session('success'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 px-4 py-3 rounded mb-4 shadow-md">
            <div class="flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                {{ session('success') }}
            </div>
        </div>
    @endif
    
    @if(session('error'))
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 px-4 py-3 rounded mb-4 shadow-md">
            <div class="flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
                {{ session('error') }}
            </div>
        </div>
    @endif
    
    <!-- Header Section with Actions -->
    <div class="mb-6">
        <div class="flex items-center justify-between mb-4">
            <a href="{{ route('teacher.meetings.index') }}" class="inline-flex items-center gap-2 bg-gray-500 hover:bg-gray-600 text-white font-semibold py-2 px-4 rounded-lg shadow-md transition-all duration-200">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Back to Meetings
            </a>
            
            <!-- Edit and Delete Buttons -->
            @if(auth()->id() == $meeting->teacher_id)
                <div class="flex gap-2">
                    @if(!in_array($meeting->status ?? 'scheduled', ['completed', 'cancelled']))
                        <a href="{{ route('teacher.meetings.edit', $meeting->meeting_id) }}" 
                           class="inline-flex items-center gap-2 bg-yellow-500 hover:bg-yellow-600 text-white font-semibold py-2 px-4 rounded-lg shadow-md transition-all duration-200">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                            </svg>
                            Edit Meeting
                        </a>
                    @endif
                    
                    @if(($meeting->status ?? 'scheduled') !== 'ongoing')
                        <form action="{{ route('teacher.meetings.destroy', $meeting->meeting_id) }}" method="POST" 
                              onsubmit="return confirm('Are you sure you want to delete this meeting? This action cannot be undone.');"
                              class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" 
                                    class="inline-flex items-center gap-2 bg-red-500 hover:bg-red-600 text-white font-semibold py-2 px-4 rounded-lg shadow-md transition-all duration-200">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                                Delete Meeting
                            </button>
                        </form>
                    @endif
                </div>
            @endif
        </div>
        
        <h1 class="text-3xl font-bold">{{ $meeting->title }}</h1>
        <p class="text-gray-600 mt-2">
            Scheduled: 
            @php
                $scheduledTime = $meeting->scheduled_at->setTimezone(config('app.timezone'));
            @endphp
            {{ $scheduledTime->format('M d, Y H:i') }}
        </p>
        <p class="text-gray-600">Duration: {{ $meeting->duration_minutes }} minutes</p>
        <p class="text-gray-600">
            Status: 
            <span class="px-3 py-1 rounded-full text-sm font-medium
                @if($meeting->status === 'completed') bg-green-100 text-green-800
                @elseif($meeting->status === 'cancelled') bg-red-100 text-red-800
                @elseif($meeting->status === 'ongoing') bg-blue-100 text-blue-800
                @else bg-yellow-100 text-yellow-800
                @endif">
                {{ ucfirst($meeting->status) }}
            </span>
        </p>
    </div>
    
    {{-- Verification Code Section --}}
    <div class="bg-gradient-to-r from-blue-50 to-indigo-50 border-2 border-blue-300 rounded-xl p-6 mb-6 shadow-lg">
        <div class="flex items-center justify-between">
            <div>
                <h3 class="text-xl font-bold text-blue-800 mb-2">🔐 Meeting Verification Code</h3>
                <p class="text-sm text-blue-700 mb-3">
                    Share this code with students in the meeting chat. Students must enter this code to verify their presence.
                </p>
                <div class="flex items-center gap-4">
                    <div class="bg-white px-6 py-4 rounded-lg border-2 border-blue-400 shadow-md">
                        <p class="text-xs text-gray-600 mb-1">Verification Code</p>
                        <p id="verificationCodeDisplay" class="text-3xl font-mono font-bold text-blue-600 tracking-wider">
                            {{ $meeting->verification_code ?? 'Not Generated' }}
                        </p>
                    </div>
                    <button onclick="regenerateCode()" 
                            class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 px-6 rounded-lg shadow-md transition-all duration-200">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                        </svg>
                        Regenerate Code
                    </button>
                    <button onclick="copyCode()" 
                            class="bg-green-600 hover:bg-green-700 text-white font-semibold py-3 px-6 rounded-lg shadow-md transition-all duration-200">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" />
                        </svg>
                        Copy Code
                    </button>
                </div>
            </div>
        </div>
    </div>
    
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="px-6 py-4 bg-gray-50 border-b">
            <h2 class="text-xl font-semibold">Student Attendance</h2>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Student</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Joined At</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Code Verified</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Confirmations</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @php
                        // Get all students - use allStudents if available, otherwise use enrollments
                        $studentsToShow = isset($allStudents) && $allStudents->count() > 0 ? $allStudents : collect($meeting->enrollments)->map(function($enrollment) {
                            $student = \App\Models\Student::where('user_id', $enrollment->student_id)->with('user')->first();
                            return $student;
                        })->filter();
                    @endphp
                    @foreach($studentsToShow as $student)
                    @php
                        // Find enrollment for this student
                        $enrollment = $meeting->enrollments->firstWhere('student_id', $student->user->user_id);
                        
                        // Find attendance record
                        $attendance = isset($attendances) ? $attendances->get($student->student_id) : null;
                        
                        if (!$attendance) {
                            $attendance = \App\Models\MeetingAttendance::where('meeting_id', $meeting->meeting_id)
                                ->where('student_id', $student->student_id)
                                ->first();
                        }
                    @endphp
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">
                                {{ $student->user->first_name }} {{ $student->user->last_name }}
                            </div>
                            <div class="text-sm text-gray-500">{{ $student->user->email }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @php
                                // Determine status based on verification and attendance
                                $displayStatus = 'absent';
                                $statusClass = 'bg-red-100 text-red-800';
                                
                                if ($attendance) {
                                    if ($attendance->is_verified && $attendance->status === 'present') {
                                        $displayStatus = 'present';
                                        $statusClass = 'bg-green-100 text-green-800';
                                    } elseif ($attendance->status === 'present') {
                                        $displayStatus = 'present';
                                        $statusClass = 'bg-green-100 text-green-800';
                                    } elseif ($attendance->status === 'absent') {
                                        $displayStatus = 'absent';
                                        $statusClass = 'bg-red-100 text-red-800';
                                    } elseif ($attendance->join_time && !$attendance->is_verified) {
                                        $displayStatus = 'pending verification';
                                        $statusClass = 'bg-yellow-100 text-yellow-800';
                                    } elseif ($attendance->join_time) {
                                        $displayStatus = 'joined';
                                        $statusClass = 'bg-blue-100 text-blue-800';
                                    }
                                } elseif ($enrollment && $enrollment->attendance_status === 'present') {
                                    $displayStatus = 'present';
                                    $statusClass = 'bg-green-100 text-green-800';
                                } elseif ($enrollment && $enrollment->attendance_status === 'absent') {
                                    $displayStatus = 'absent';
                                    $statusClass = 'bg-red-100 text-red-800';
                                } elseif ($enrollment && $enrollment->joined_at) {
                                    $displayStatus = 'joined';
                                    $statusClass = 'bg-blue-100 text-blue-800';
                                }
                            @endphp
                            <span class="px-3 py-1 rounded-full text-sm font-medium {{ $statusClass }}">
                                {{ ucfirst(str_replace('_', ' ', $displayStatus)) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            @if($attendance && $attendance->join_time)
                                @php
                                    $joinTime = $attendance->join_time->setTimezone(config('app.timezone'));
                                @endphp
                                {{ $joinTime->format('H:i:s') }}
                            @elseif($enrollment && $enrollment->joined_at)
                                @php
                                    $joinTime = $enrollment->joined_at->setTimezone(config('app.timezone'));
                                @endphp
                                {{ $joinTime->format('H:i:s') }}
                            @else
                                <span class="text-gray-400">Not joined</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($attendance && $attendance->is_verified)
                                <span class="px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800 flex items-center gap-1 w-fit">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                    </svg>
                                    Verified
                                </span>
                            @elseif($attendance && $attendance->join_time)
                                <span class="px-3 py-1 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800">
                                    Not Verified
                                </span>
                            @else
                                <span class="px-3 py-1 rounded-full text-sm font-medium bg-gray-100 text-gray-800">
                                    Not Joined
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            @if($enrollment)
                                {{ $enrollment->confirmations->where('is_confirmed', true)->count() }} / 
                                {{ $enrollment->confirmations->count() }}
                            @else
                                <span class="text-gray-400">-</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    
    <div class="mt-6 text-center">
        <a href="{{ route('teacher.meetings.index') }}" class="inline-flex items-center gap-2 bg-gray-500 hover:bg-gray-600 text-white font-semibold py-2 px-6 rounded-lg shadow-md transition-all duration-200">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Go Back to Meetings
        </a>
    </div>
</div>

<script>
    const meetingId = {{ $meeting->meeting_id }};

    async function regenerateCode() {
        if (!confirm('Are you sure you want to regenerate the verification code? Students who already verified will need to verify again with the new code.')) {
            return;
        }

        try {
            const response = await fetch(`/meetings/${meetingId}/verification-code?regenerate=true`, {
                method: 'GET',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                }
            });

            const data = await response.json();

            if (response.ok && data.success) {
                document.getElementById('verificationCodeDisplay').textContent = data.verification_code;
                alert('Verification code regenerated successfully!');
            } else {
                alert('Failed to regenerate code. Please try again.');
            }
        } catch (error) {
            console.error('Error regenerating code:', error);
            alert('An error occurred. Please try again.');
        }
    }

    function copyCode() {
        const code = document.getElementById('verificationCodeDisplay').textContent.trim();
        
        if (code === 'Not Generated') {
            alert('No verification code available.');
            return;
        }

        navigator.clipboard.writeText(code).then(() => {
            alert('Verification code copied to clipboard!');
        }).catch(err => {
            // Fallback for older browsers
            const textArea = document.createElement('textarea');
            textArea.value = code;
            document.body.appendChild(textArea);
            textArea.select();
            document.execCommand('copy');
            document.body.removeChild(textArea);
            alert('Verification code copied to clipboard!');
        });
    }
</script>
@endsection
