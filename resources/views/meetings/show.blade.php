@extends('layouts.app')

@section('content')
<div class="w-full max-w-full mx-auto py-10 px-4 sm:px-6 lg:px-8">
    <div class="bg-gradient-to-br from-pink-50 via-white to-pink-100 shadow-2xl rounded-3xl p-10 border-2 border-pink-200">
        <h1 class="text-3xl font-extrabold text-pink-600 mb-6 drop-shadow">{{ $meeting->title }}</h1>

        <div class="space-y-4 mb-8">
            <div class="bg-white rounded-xl p-4 border border-pink-200">
                <strong class="text-pink-600">Class:</strong> 
                <span class="text-gray-700">{{ $meeting->studentClass->class_name ?? 'N/A' }}</span>
            </div>
            <div class="bg-white rounded-xl p-4 border border-pink-200">
                <strong class="text-pink-600">Teacher:</strong> 
                <span class="text-gray-700">
                    {{ $meeting->teacher->first_name }} {{ $meeting->teacher->last_name }}
                </span>
            </div>
            <div class="bg-white rounded-xl p-4 border border-pink-200">
                <strong class="text-pink-600">Date:</strong> 
                <span class="text-gray-700">
                    {{ $meeting->start_time ? $meeting->start_time->format('F d, Y') : 'Not set' }}
                </span>
            </div>
            <div class="bg-white rounded-xl p-4 border border-pink-200">
                <strong class="text-pink-600">Time:</strong> 
                <span class="text-gray-700">
                    @if($meeting->start_time && $meeting->end_time)
                        {{ $meeting->start_time->format('h:i A') }} - 
                        {{ $meeting->end_time->format('h:i A') }}
                    @else
                        Not set
                    @endif
                </span>
            </div>
            @if($meeting->description)
                <div class="bg-white rounded-xl p-4 border border-pink-200">
                    <strong class="text-pink-600">Description:</strong>
                    <p class="text-gray-700 mt-2">{{ $meeting->description }}</p>
                </div>
            @endif
            <div class="bg-white rounded-xl p-4 border border-pink-200">
                <strong class="text-pink-600">Status:</strong> 
                <span class="inline-block bg-blue-100 text-blue-800 text-xs font-semibold px-3 py-1 rounded-full">
                    {{ ucfirst($meeting->status) }}
                </span>
            </div>
        </div>

        @if(Auth::check() && Auth::user()->role === 'teacher')
                <div class="bg-white rounded-xl p-6 border-2 border-blue-200 mb-6">
                    <h3 class="text-2xl font-bold text-blue-700 mb-6 flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        Attendance Report
                    </h3>
                    
                    @php
                        $attendedStudentIds = $attendances->pluck('student_id')->toArray();
                        $absentStudents = $allStudents->whereNotIn('student_id', $attendedStudentIds);
                    @endphp

                    <div class="mb-4 grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                            <div class="text-sm text-green-600 font-semibold">Total Attended</div>
                            <div class="text-2xl font-bold text-green-700">{{ $attendances->count() }}</div>
                        </div>
                        <div class="bg-red-50 border border-red-200 rounded-lg p-4">
                            <div class="text-sm text-red-600 font-semibold">Absent</div>
                            <div class="text-2xl font-bold text-red-700">{{ $absentStudents->count() }}</div>
                        </div>
                        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                            <div class="text-sm text-blue-600 font-semibold">Total Students</div>
                            <div class="text-2xl font-bold text-blue-700">{{ $allStudents->count() }}</div>
                        </div>
                    </div>

                    @if($allStudents->count() === 0)
                        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-4">
                            <p class="text-yellow-800">No students are enrolled in this class yet.</p>
                        </div>
                    @endif

                    @if($attendances->count() > 0)
                        <div class="mb-6">
                            <h4 class="text-lg font-semibold text-gray-700 mb-3">Attended Students</h4>
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200 border border-gray-300 rounded-lg">
                                    <thead class="bg-gradient-to-r from-blue-500 to-blue-600 text-white">
                                        <tr>
                                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider">Student Name</th>
                                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider">Join Time</th>
                                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider">Leave Time</th>
                                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider">Duration</th>
                                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider">Status</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @foreach($attendances as $att)
                                            <tr class="hover:bg-gray-50">
                                                <td class="px-4 py-3 whitespace-nowrap text-sm font-medium text-gray-900">
                                                    {{ $att->student->user->first_name }} {{ $att->student->user->last_name }}
                                                </td>
                                                <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-700">
                                                    {{ $att->join_time->format('M d, Y h:i A') }}
                                                </td>
                                                <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-700">
                                                    @if($att->leave_time)
                                                        {{ $att->leave_time->format('M d, Y h:i A') }}
                                                    @else
                                                        <span class="text-blue-600 font-semibold">Still in meeting</span>
                                                    @endif
                                                </td>
                                                <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-700">
                                                    @if($att->duration_minutes)
                                                        {{ $att->duration_minutes }} minutes
                                                    @elseif($att->leave_time)
                                                        {{ $att->join_time->diffInMinutes($att->leave_time) }} minutes
                                                    @else
                                                        <span class="text-gray-400">-</span>
                                                    @endif
                                                </td>
                                                <td class="px-4 py-3 whitespace-nowrap">
                                                    @if($att->status)
                                                        <span class="inline-block px-3 py-1 rounded-full text-xs font-semibold {{ $att->status === 'on_time' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                                            {{ $att->status === 'on_time' ? 'On Time' : 'Late' }}
                                                        </span>
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
                    @endif

                    @if($absentStudents->count() > 0)
                        <div>
                            <h4 class="text-lg font-semibold text-gray-700 mb-3">Absent Students</h4>
                            <div class="bg-red-50 border border-red-200 rounded-lg p-4">
                                <ul class="space-y-2">
                                    @foreach($absentStudents as $student)
                                        <li class="text-sm text-gray-700 flex items-center gap-2">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                            </svg>
                                            {{ $student->user->first_name }} {{ $student->user->last_name }}
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    @endif
                </div>
        @endif

        @if(Auth::check() && Auth::user()->role === 'student' && $attendance)
                <div class="bg-white rounded-xl p-6 border-2 border-green-200 mb-6">
                    <h3 class="text-xl font-bold text-green-700 mb-4">Attendance Status</h3>
                    <div class="space-y-2">
                        <div class="flex justify-between">
                            <span class="text-gray-700 font-semibold">Join Time:</span>
                            <span class="text-gray-900">{{ $attendance->join_time->format('M d, Y h:i A') }}</span>
                        </div>
                        @if($attendance->leave_time)
                            <div class="flex justify-between">
                                <span class="text-gray-700 font-semibold">Leave Time:</span>
                                <span class="text-gray-900">{{ $attendance->leave_time->format('M d, Y h:i A') }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-700 font-semibold">Duration:</span>
                                <span class="text-gray-900">{{ $attendance->duration_minutes ?? 0 }} minutes</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-700 font-semibold">Status:</span>
                                <span class="inline-block px-3 py-1 rounded-full text-xs font-semibold {{ $attendance->status === 'on_time' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ $attendance->status === 'on_time' ? 'On Time' : 'Late' }}
                                </span>
                            </div>
                        @else
                            <div class="flex justify-between">
                                <span class="text-gray-700 font-semibold">Status:</span>
                                <span class="inline-block bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-xs font-semibold">
                                    Currently In Meeting
                                </span>
                            </div>
                        @endif
                    </div>
                </div>
        @endif

        @if($meeting->google_meet_link)
            <div class="text-center space-y-4">
                @auth
                    @if(auth()->user()->role === 'student')
                        @if(!$attendance)
                            <button id="joinMeetingBtn" 
                                    class="inline-block bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white font-bold py-4 px-8 rounded-xl shadow-lg text-lg transition-all duration-150">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 inline mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                                </svg>
                                Join Meeting
                            </button>
                        @elseif($attendance && !$attendance->leave_time)
                            <button id="leaveMeetingBtn" 
                                    class="inline-block bg-gradient-to-r from-red-500 to-red-600 hover:from-red-600 hover:to-red-700 text-white font-bold py-4 px-8 rounded-xl shadow-lg text-lg transition-all duration-150">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 inline mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                </svg>
                                Leave Meeting
                            </button>
                        @endif
                    @endif
                @endif
                
                <a href="{{ $meeting->google_meet_link }}" 
                   target="_blank"
                   class="inline-block bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 text-white font-bold py-4 px-8 rounded-xl shadow-lg text-lg transition-all duration-150">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 inline mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
                    </svg>
                    Join Google Meet
                </a>
            </div>
        @endif

        <div class="mt-8 text-center">
            <a href="{{ route('meetings.index') }}" 
               class="text-pink-600 hover:text-pink-700 font-semibold">
                ← Back to Meetings
            </a>
        </div>
    </div>
</div>

@auth
    @if(auth()->user()->role === 'student')
        <script>
            const meetingId = {{ $meeting->meeting_id }};
            let hasJoined = {{ $attendance ? 'true' : 'false' }};
            let hasLeft = {{ ($attendance && $attendance->leave_time) ? 'true' : 'false' }};
            let leaveRequestSent = false;

            // Join Meeting
            const joinBtn = document.getElementById('joinMeetingBtn');
            if (joinBtn) {
                joinBtn.addEventListener('click', async function() {
                    if (hasJoined) {
                        alert('You have already joined this meeting.');
                        return;
                    }

                    try {
                        const response = await fetch(`/meetings/${meetingId}/join`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                            }
                        });

                        const data = await response.json();

                        if (response.ok) {
                            hasJoined = true;
                            alert('Successfully joined the meeting!');
                            location.reload(); // Reload to show updated status
                        } else {
                            alert(data.error || 'Failed to join meeting.');
                        }
                    } catch (error) {
                        console.error('Error:', error);
                        alert('An error occurred while joining the meeting.');
                    }
                });
            }

            // Leave Meeting
            const leaveBtn = document.getElementById('leaveMeetingBtn');
            if (leaveBtn) {
                leaveBtn.addEventListener('click', async function() {
                    if (hasLeft || leaveRequestSent) {
                        return;
                    }

                    if (confirm('Are you sure you want to leave the meeting?')) {
                        await leaveMeeting();
                    }
                });
            }

            // Leave meeting function
            async function leaveMeeting() {
                if (leaveRequestSent) {
                    return;
                }

                leaveRequestSent = true;

                try {
                    const response = await fetch(`/meetings/${meetingId}/leave`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                        }
                    });

                    const data = await response.json();

                    if (response.ok) {
                        hasLeft = true;
                        alert('You have left the meeting.');
                        location.reload(); // Reload to show updated status
                    } else {
                        alert(data.error || 'Failed to leave meeting.');
                        leaveRequestSent = false;
                    }
                } catch (error) {
                    console.error('Error:', error);
                    // Use sendBeacon as fallback for page unload
                    if (navigator.sendBeacon) {
                        const formData = new FormData();
                        formData.append('_token', document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '');
                        navigator.sendBeacon(`/meetings/${meetingId}/leave`, formData);
                    }
                    leaveRequestSent = false;
                }
            }

            // Handle page unload (browser close, tab close, navigation)
            window.addEventListener('beforeunload', function(e) {
                if (hasJoined && !hasLeft && !leaveRequestSent) {
                    leaveRequestSent = true;
                    
                    // Use fetch with keepalive for reliable delivery during page unload
                    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
                    
                    // Try fetch with keepalive first (most reliable)
                    if (navigator.sendBeacon) {
                        const formData = new FormData();
                        formData.append('_token', csrfToken);
                        navigator.sendBeacon(`/meetings/${meetingId}/leave`, formData);
                    } else {
                        // Fallback: synchronous request (may not always work)
                        try {
                            const xhr = new XMLHttpRequest();
                            xhr.open('POST', `/meetings/${meetingId}/leave`, false);
                            xhr.setRequestHeader('X-CSRF-TOKEN', csrfToken);
                            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                            xhr.send(`_token=${encodeURIComponent(csrfToken)}`);
                        } catch (err) {
                            // Silently fail if we can't send the request
                            console.error('Could not send leave request:', err);
                        }
                    }
                }
            });

            // Handle visibility change (tab switch, minimize)
            document.addEventListener('visibilitychange', function() {
                if (document.hidden && hasJoined && !hasLeft && !leaveRequestSent) {
                    // Optional: You might want to leave when tab is hidden
                    // Uncomment the line below if you want this behavior
                    // leaveMeeting();
                }
            });
        </script>
    @endif
@endauth
@endsection
