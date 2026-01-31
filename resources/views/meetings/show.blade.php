@extends('layouts.app')

@section('content')
<style>
    #attendanceModal {
        position: fixed !important;
        top: 0 !important;
        left: 0 !important;
        right: 0 !important;
        bottom: 0 !important;
        z-index: 999999 !important;
        background-color: rgba(0, 0, 0, 0.7) !important;
    }
    #attendanceModal .bg-white {
        position: relative;
        z-index: 1000000 !important;
        animation: modalSlideIn 0.3s ease-out;
    }
    @keyframes modalSlideIn {
        from {
            opacity: 0;
            transform: translateY(-50px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
</style>
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
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-2xl font-bold text-blue-700 flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            Attendance Report
                        </h3>
                        <a href="{{ route('meetings.export-attendance', $meeting) }}" 
                           class="inline-flex items-center gap-2 bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 text-white font-semibold py-2 px-4 rounded-lg shadow-md transition-all duration-200">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            Export to CSV
                        </a>
                    </div>
                    
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
                                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider">Joined At</th>
                                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider">Last Confirmed</th>
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
                                                    @if($att->joined_at)
                                                        {{ $att->joined_at->format('M d, Y h:i A') }}
                                                    @elseif($att->join_time)
                                                        {{ $att->join_time->format('M d, Y h:i A') }}
                                                    @else
                                                        <span class="text-gray-400">-</span>
                                                    @endif
                                                </td>
                                                <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-700">
                                                    @if($att->last_confirmed_at)
                                                        {{ $att->last_confirmed_at->format('M d, Y h:i A') }}
                                                    @else
                                                        <span class="text-gray-400">-</span>
                                                    @endif
                                                </td>
                                                <td class="px-4 py-3 whitespace-nowrap">
                                                    @php
                                                        $statusClass = 'bg-gray-100 text-gray-800';
                                                        $statusText = 'Pending';
                                                        if ($att->status === 'present') {
                                                            $statusClass = 'bg-green-100 text-green-800';
                                                            $statusText = 'Present';
                                                        } elseif ($att->status === 'absent') {
                                                            $statusClass = 'bg-red-100 text-red-800';
                                                            $statusText = 'Absent';
                                                        } elseif (in_array($att->status, ['on_time', 'late'])) {
                                                            // Legacy status
                                                            $statusClass = $att->status === 'on_time' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800';
                                                            $statusText = $att->status === 'on_time' ? 'On Time' : 'Late';
                                                        }
                                                    @endphp
                                                    <span class="inline-block px-3 py-1 rounded-full text-xs font-semibold {{ $statusClass }}">
                                                        {{ $statusText }}
                                                    </span>
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

                <!-- Manual Attendance Marking Section -->
                <div class="bg-white rounded-xl p-6 border-2 border-purple-200 mb-6">
                    <h3 class="text-2xl font-bold text-purple-700 mb-4 flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                        </svg>
                        Mark Attendance Manually
                    </h3>
                    <p class="text-sm text-gray-600 mb-4">You can manually mark students as present, late, or absent if needed.</p>
                    
                    @if($allStudents->count() > 0)
                        <div class="space-y-3">
                            @foreach($allStudents as $student)
                                @php
                                    $studentAttendance = $attendances->firstWhere('student_id', $student->student_id);
                                    $isPresent = $studentAttendance !== null;
                                @endphp
                                <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg border border-gray-200 hover:bg-gray-100 transition-colors">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-full bg-gradient-to-br {{ $isPresent ? 'from-green-400 to-green-500' : 'from-gray-300 to-gray-400' }} flex items-center justify-center text-white font-bold text-sm">
                                            {{ strtoupper(substr($student->user->first_name, 0, 1)) }}
                                        </div>
                                        <div>
                                            <span class="font-semibold text-gray-800">
                                                {{ $student->user->first_name }} {{ $student->user->last_name }}
                                            </span>
                                            @if($isPresent)
                                                <span class="ml-2 text-xs bg-green-100 text-green-800 px-2 py-1 rounded-full font-semibold">Present</span>
                                                @if($studentAttendance->status)
                                                    <span class="ml-2 text-xs px-2 py-1 rounded-full font-semibold {{ $studentAttendance->status === 'on_time' ? 'bg-blue-100 text-blue-800' : 'bg-yellow-100 text-yellow-800' }}">
                                                        {{ $studentAttendance->status === 'on_time' ? 'On Time' : 'Late' }}
                                                    </span>
                                                @endif
                                            @else
                                                <span class="ml-2 text-xs bg-red-100 text-red-800 px-2 py-1 rounded-full font-semibold">Absent</span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="flex gap-2">
                                        <button onclick="markAttendance({{ $meeting->meeting_id }}, {{ $student->student_id }}, 'present')" 
                                                class="px-4 py-2 bg-green-500 hover:bg-green-600 text-white rounded-lg text-sm font-semibold transition-colors {{ $isPresent ? 'opacity-50 cursor-not-allowed' : '' }}"
                                                {{ $isPresent ? 'disabled' : '' }}
                                                title="{{ $isPresent ? 'Already marked as present' : 'Mark as Present' }}">
                                            Present
                                        </button>
                                        <button onclick="markAttendance({{ $meeting->meeting_id }}, {{ $student->student_id }}, 'late')" 
                                                class="px-4 py-2 bg-yellow-500 hover:bg-yellow-600 text-white rounded-lg text-sm font-semibold transition-colors"
                                                title="Mark as Late">
                                            Late
                                        </button>
                                        <button onclick="markAttendance({{ $meeting->meeting_id }}, {{ $student->student_id }}, 'absent')" 
                                                class="px-4 py-2 bg-red-500 hover:bg-red-600 text-white rounded-lg text-sm font-semibold transition-colors {{ !$isPresent ? 'opacity-50 cursor-not-allowed' : '' }}"
                                                {{ !$isPresent ? 'disabled' : '' }}
                                                title="{{ !$isPresent ? 'Already marked as absent' : 'Mark as Absent' }}">
                                            Absent
                                        </button>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                            <p class="text-yellow-800">No students are enrolled in this class yet.</p>
                        </div>
                    @endif
                </div>
        @endif

        @if(Auth::check() && Auth::user()->role === 'student')
                <div class="bg-white rounded-xl p-6 border-2 border-green-200 mb-6">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-xl font-bold text-green-700">Your Attendance Status</h3>
                        @if($attendance && $attendance->joined_at && !$attendance->leave_time)
                            <button type="button" id="testAttendanceCheckBtn" 
                                    class="text-xs bg-yellow-500 hover:bg-yellow-600 text-white font-semibold py-2 px-4 rounded-lg transition-colors">
                                🧪 Test Attendance Check
                            </button>
                        @endif
                    </div>
                    <div class="space-y-2">
                        @if($attendance)
                            @if($attendance->joined_at)
                                <div class="flex justify-between">
                                    <span class="text-gray-700 font-semibold">Joined At:</span>
                                    <span class="text-gray-900">{{ $attendance->joined_at->format('M d, Y h:i A') }}</span>
                                </div>
                            @endif
                            @if($attendance->last_confirmed_at)
                                <div class="flex justify-between">
                                    <span class="text-gray-700 font-semibold">Last Confirmed:</span>
                                    <span class="text-gray-900">{{ $attendance->last_confirmed_at->format('M d, Y h:i A') }}</span>
                                </div>
                            @endif
                            <div class="flex justify-between">
                                <span class="text-gray-700 font-semibold">Status:</span>
                                @php
                                    // Prioritize new automatic attendance statuses
                                    $statusClass = 'bg-gray-100 text-gray-800';
                                    $statusText = 'Pending';
                                    
                                    // If student is currently in meeting (has joined_at but no leave_time),
                                    // always show as 'Pending' until they respond to checks or leave
                                    $isCurrentlyInMeeting = $attendance->joined_at && !$attendance->leave_time;
                                    
                                    if ($isCurrentlyInMeeting) {
                                        // Student is currently in meeting - show as Pending
                                        // unless they have confirmed presence and status is 'present'
                                        if ($attendance->status === 'present' && $attendance->last_confirmed_at) {
                                            $statusClass = 'bg-green-100 text-green-800';
                                            $statusText = 'Present';
                                        } else {
                                            $statusClass = 'bg-yellow-100 text-yellow-800';
                                            $statusText = 'Pending';
                                        }
                                    } else {
                                        // Student has left - show final status
                                        if ($attendance->status === 'present') {
                                            $statusClass = 'bg-green-100 text-green-800';
                                            $statusText = 'Present';
                                        } elseif ($attendance->status === 'absent') {
                                            $statusClass = 'bg-red-100 text-red-800';
                                            $statusText = 'Absent';
                                        } elseif ($attendance->status === 'pending') {
                                            $statusClass = 'bg-yellow-100 text-yellow-800';
                                            $statusText = 'Pending';
                                        } elseif (in_array($attendance->status, ['on_time', 'late'])) {
                                            // Legacy status
                                            $statusClass = $attendance->status === 'on_time' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800';
                                            $statusText = $attendance->status === 'on_time' ? 'On Time' : 'Late';
                                        }
                                    }
                                @endphp
                                <span class="inline-block px-3 py-1 rounded-full text-xs font-semibold {{ $statusClass }}" id="attendanceStatusBadge">
                                    {{ $statusText }}
                                </span>
                            </div>
                        @else
                            <div class="flex justify-between">
                                <span class="text-gray-700 font-semibold">Status:</span>
                                <span class="inline-block bg-gray-100 text-gray-800 px-3 py-1 rounded-full text-xs font-semibold" id="attendanceStatusBadge">
                                    Not Joined
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
                        @if(!$attendance || ($attendance && $attendance->leave_time))
                            {{-- Show join button if no attendance OR if they have left (allow rejoin) --}}
                            @if($attendance && $attendance->leave_time)
                                <div class="mb-4 bg-yellow-50 border border-yellow-200 rounded-lg p-4 max-w-2xl mx-auto text-left">
                                    <p class="text-sm text-yellow-800 mb-2">
                                        <strong>⚠️ You have left the meeting.</strong> Click "Rejoin Meeting" to continue.
                                    </p>
                                </div>
                            @else
                                <div class="mb-4 bg-blue-50 border border-blue-200 rounded-lg p-4 max-w-2xl mx-auto text-left">
                                    <p class="text-sm text-blue-800 mb-2">
                                        <strong>📌 Important:</strong> When you click "Join Meeting":
                                    </p>
                                    <ul class="text-sm text-blue-700 list-disc list-inside space-y-1">
                                        <li>Google Meet will open in a new window/tab</li>
                                        <li><strong>Keep this LMS page open</strong> in the background (don't close it!)</li>
                                        <li>Attendance will be tracked automatically every 5 minutes</li>
                                        <li>You'll see a confirmation popup asking if you're still in the meeting</li>
                                    </ul>
                                </div>
                            @endif
                            <button type="button" id="joinMeetingBtn" 
                                    onclick="return false;"
                                    class="inline-block bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white font-bold py-4 px-8 rounded-xl shadow-lg text-lg transition-all duration-150">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 inline mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                                </svg>
                                {{ $attendance && $attendance->leave_time ? 'Rejoin Meeting' : 'Join Meeting' }}
                            </button>
                        @elseif($attendance && !$attendance->leave_time)
                            <button type="button" id="leaveMeetingBtn" 
                                    class="inline-block bg-gradient-to-r from-red-500 to-red-600 hover:from-red-600 hover:to-red-700 text-white font-bold py-4 px-8 rounded-xl shadow-lg text-lg transition-all duration-150">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 inline mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                </svg>
                                Leave Meeting
                            </button>
                        @endif
                    @endif
                @endif
                
                @if(auth()->user()->role === 'teacher' || !auth()->check())
                    <a href="{{ $meeting->google_meet_link }}" 
                       target="_blank"
                       class="inline-block bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 text-white font-bold py-4 px-8 rounded-xl shadow-lg text-lg transition-all duration-150">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 inline mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
                        </svg>
                        Join Google Meet
                    </a>
                @endif
            </div>
        @endif

        <!-- Attendance Check Modal -->
        <div id="attendanceModal" class="fixed inset-0 bg-black bg-opacity-70 flex items-center justify-center hidden" style="display: none !important; z-index: 999999 !important; position: fixed !important; top: 0 !important; left: 0 !important; right: 0 !important; bottom: 0 !important;">
            <div class="bg-white rounded-xl p-8 max-w-md w-full mx-4 shadow-2xl border-4 border-blue-500" style="position: relative !important; z-index: 1000000 !important;">
                <div class="text-center mb-6">
                    <div class="inline-block bg-blue-100 rounded-full p-3 mb-4">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                        </svg>
                    </div>
                    <h3 class="text-3xl font-bold text-gray-800 mb-2">Are you still in the meeting?</h3>
                    <p class="text-gray-600 mb-6">Please confirm your presence to continue tracking attendance.</p>
                </div>
                <div class="flex flex-col gap-3">
                    <button id="confirmPresenceBtn" 
                            class="bg-green-500 hover:bg-green-600 text-white font-bold py-4 px-8 rounded-lg transition-colors text-lg shadow-lg">
                        ✅ Yes, I'm here
                    </button>
                    <button id="markAbsentBtn" 
                            class="bg-red-500 hover:bg-red-600 text-white font-bold py-3 px-8 rounded-lg transition-colors">
                        ❌ No, I'm not in the meeting
                    </button>
                </div>
                <p class="text-sm text-gray-500 mt-6 text-center font-semibold" id="modalCountdown">⏱️ This will close automatically in 5 seconds...</p>
            </div>
        </div>

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
            console.log('=== ATTENDANCE SCRIPT LOADING ===');
            (function() {
                console.log('=== ATTENDANCE SCRIPT INITIALIZING ===');
                // Wait for DOM to be ready
                if (document.readyState === 'loading') {
                    console.log('DOM is loading, waiting for DOMContentLoaded...');
                    document.addEventListener('DOMContentLoaded', function() {
                        console.log('DOMContentLoaded fired, initializing...');
                        initAttendanceSystem();
                    });
                } else {
                    console.log('DOM already ready, initializing immediately...');
                    initAttendanceSystem();
                }

                function initAttendanceSystem() {
                    const meetingId = {{ $meeting->meeting_id }};
                    const meetingEndTime = @if($meeting->end_time) new Date('{{ $meeting->end_time->toIso8601String() }}') @else null @endif;
                    const googleMeetLink = '{{ $meeting->google_meet_link }}';
                    
                    // Check if student has joined (has attendance record with joined_at)
                    let hasJoined = {{ ($attendance && $attendance->joined_at) ? 'true' : 'false' }};
                    
                    // Check if student has left (has leave_time set)
                    // For automatic attendance, only check leave_time, not old join_time system
                    // IMPORTANT: If they have leave_time but it was set very recently (within last 5 seconds),
                    // they might have accidentally left due to page reload - allow them to rejoin
                    let hasLeft = {{ ($attendance && $attendance->leave_time) ? 'true' : 'false' }};
                    
                    // If they have left but it was very recent (within 10 seconds), 
                    // treat it as if they're still in the meeting (might be accidental leave from page reload)
                    @if($attendance && $attendance->leave_time && $attendance->joined_at)
                        @php
                            $timeSinceLeave = $attendance->leave_time->diffInSeconds(now());
                            $timeSinceJoin = $attendance->joined_at->diffInSeconds($attendance->leave_time);
                        @endphp
                        @if($timeSinceLeave < 10 && $timeSinceJoin < 10)
                            // They left very quickly after joining - likely accidental (page reload)
                            console.log('⚠️ Detected quick leave after join ({{ $timeSinceJoin }}s) - treating as still in meeting');
                            hasLeft = false;
                        @endif
                    @endif
                    
                    console.log('Attendance record check:');
                    console.log('- Attendance exists:', {{ $attendance ? 'true' : 'false' }});
                    console.log('- joined_at:', '{{ $attendance && $attendance->joined_at ? $attendance->joined_at->toIso8601String() : "null" }}');
                    console.log('- leave_time:', '{{ $attendance && $attendance->leave_time ? $attendance->leave_time->toIso8601String() : "null" }}');
                    console.log('- hasJoined:', hasJoined);
                    console.log('- hasLeft:', hasLeft);
                    let leaveRequestSent = false;
                    let attendanceCheckInterval = null;
                    let modalTimeout = null;
                    let meetWindow = null;
                    let currentCheckNumber = 0;
                    let checkResponses = {
                        present: 0,
                        absent: 0,
                        no_response: 0
                    };

                    // Join Meeting - Opens Google Meet in popup
                    console.log('Initializing attendance system...');
                    console.log('Meeting ID:', meetingId);
                    console.log('Google Meet Link:', googleMeetLink);
                    console.log('Has Joined:', hasJoined);
                    
                    const joinBtn = document.getElementById('joinMeetingBtn');
                    console.log('Join button found:', joinBtn !== null);
                    
                    // Function to handle join meeting
                    async function handleJoinMeeting(e) {
                        console.log('=== JOIN MEETING CLICKED ===');
                        console.log('Event:', e);
                        
                        if (e) {
                            e.preventDefault();
                            e.stopPropagation();
                            e.stopImmediatePropagation();
                            if (e.cancelable) {
                                e.preventDefault();
                            }
                        }
                        
                        // Prevent any navigation
                        if (window.event) {
                            window.event.preventDefault();
                            window.event.stopPropagation();
                            window.event.returnValue = false;
                        }
                        
                        console.log('Join Meeting button clicked!');
                        console.log('Current state - hasJoined:', hasJoined, 'hasLeft:', hasLeft);
                        
                        // Allow joining even if hasJoined is true, as long as they have left
                        // The server will handle the rejoin logic
                        if (hasJoined && !hasLeft) {
                            alert('You have already joined this meeting.');
                            return false;
                        }

                        try {
                            console.log('Sending join request to server...');
                            const response = await fetch(`/meetings/${meetingId}/join`, {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                                }
                            });

                            const data = await response.json();
                            console.log('Server response:', data);

                            if (response.ok) {
                                hasJoined = true;
                                hasLeft = false; // Reset hasLeft when rejoining
                                console.log('Successfully joined meeting. Opening popup...');
                                console.log('✅ hasJoined set to:', hasJoined);
                                console.log('✅ hasLeft reset to:', hasLeft);
                                
                                // Update status badge immediately
                                updateAttendanceStatus('Pending');
                                
                                // Open Google Meet in popup window
                                if (googleMeetLink) {
                                    console.log('Attempting to open:', googleMeetLink);
                                    
                                    // Try to open as popup with more specific parameters
                                    const popupFeatures = [
                                        'width=1280',
                                        'height=720',
                                        'left=' + (screen.width / 2 - 640),
                                        'top=' + (screen.height / 2 - 360),
                                        'resizable=yes',
                                        'scrollbars=yes',
                                        'status=yes',
                                        'toolbar=no',
                                        'menubar=no',
                                        'location=no',
                                        'directories=no'
                                    ].join(',');
                                    
                                    meetWindow = window.open(
                                        googleMeetLink,
                                        'GoogleMeet_' + meetingId,
                                        popupFeatures
                                    );
                                    
                                    // Check if popup was opened successfully
                                    setTimeout(function() {
                                        if (!meetWindow || meetWindow.closed || typeof meetWindow.closed == 'undefined') {
                                            console.error('Popup was blocked or closed!');
                                            alert('⚠️ Popup was blocked!\n\nPlease:\n1. Allow popups for this site\n2. Or manually open Google Meet in a new window\n3. Keep this LMS page open for attendance tracking');
                                        } else {
                                            console.log('Google Meet opened successfully in popup window');
                                            
                                            // Try to focus the popup
                                            try {
                                                meetWindow.focus();
                                            } catch (e) {
                                                console.warn('Could not focus popup:', e);
                                            }
                                            
                                            // Check if it's actually a popup (not a tab)
                                            // If window.opener exists and is not null, it's likely a popup
                                            if (meetWindow.opener === null) {
                                                console.warn('Window might have opened as a tab instead of popup');
                                            }
                                        }
                                    }, 100);
                                } else {
                                    console.warn('No Google Meet link provided');
                                }
                                
                                // Start attendance check loop
                                startAttendanceChecks();
                                
                                // Update status badge
                                updateAttendanceStatus('Pending');
                                
                                // Set flag to prevent beforeunload from triggering leave
                                sessionStorage.setItem('justJoinedMeeting_' + meetingId, 'true');
                                
                                // Show success message
                                alert('✅ Successfully joined the meeting!\n\n📌 IMPORTANT:\n- Keep this LMS page open in the background (don\'t close it!)\n- Google Meet has opened in a new window/tab\n- Attendance will be tracked automatically every 5 minutes\n- You\'ll see a confirmation popup asking if you\'re still in the meeting');
                                
                                // Delay reload slightly to ensure popup opens first
                                setTimeout(function() {
                                    location.reload();
                                }, 1000);
                            } else {
                                alert(data.error || 'Failed to join meeting.');
                            }
                        } catch (error) {
                            console.error('Error:', error);
                            alert('An error occurred while joining the meeting.');
                        }
                        
                        return false;
                    }
                    
                    if (joinBtn) {
                        // Remove any existing handlers first
                        joinBtn.onclick = null;
                        
                        // Add event listener with capture to ensure it runs first
                        joinBtn.addEventListener('click', handleJoinMeeting, true);
                        
                        // Also add onclick as primary handler (runs first)
                        joinBtn.onclick = function(e) {
                            e = e || window.event;
                            if (e) {
                                e.preventDefault();
                                e.stopPropagation();
                                e.stopImmediatePropagation();
                            }
                            handleJoinMeeting(e);
                            return false;
                        };
                        
                        // Make absolutely sure it's not a link
                        joinBtn.href = null;
                        joinBtn.removeAttribute('href');
                        
                        console.log('Join button handlers attached successfully');
                    } else {
                        console.error('Join button not found!');
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

                        console.log('=== LEAVING MEETING ===');
                        console.log('Current status before leaving:', hasJoined, hasLeft);

                        // Stop attendance checks immediately
                        console.log('Stopping attendance checks...');
                        stopAttendanceChecks();

                        // Close popup if open
                        if (meetWindow && !meetWindow.closed) {
                            console.log('Closing Google Meet popup window...');
                            meetWindow.close();
                        }

                        try {
                            console.log('Sending leave request to server...');
                            const response = await fetch(`/meetings/${meetingId}/leave`, {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                                }
                            });

                            const data = await response.json();
                            console.log('Leave response:', data);

                            if (response.ok) {
                                hasLeft = true;
                                
                                // Show appropriate message based on attendance status
                                let message = 'You have left the meeting.';
                                if (data.attendance && data.attendance.status === 'absent') {
                                    message = 'You have left the meeting.\n\n⚠️ Note: You left before confirming your presence, so you were marked as absent.';
                                } else if (data.attendance && data.attendance.status === 'present') {
                                    message = 'You have left the meeting.\n\n✅ Your attendance was recorded as present.';
                                }
                                
                                alert(message);
                                location.reload();
                            } else {
                                alert(data.error || 'Failed to leave meeting.');
                                leaveRequestSent = false;
                            }
                        } catch (error) {
                            console.error('Error leaving meeting:', error);
                            leaveRequestSent = false;
                        }
                    }

                    // Start attendance checks (every 5 minutes)
                    function startAttendanceChecks() {
                        console.log('=== STARTING ATTENDANCE CHECKS ===');
                        
                        // Clear any existing interval
                        if (attendanceCheckInterval) {
                            console.log('Clearing existing interval');
                            clearInterval(attendanceCheckInterval);
                        }

                        // Don't check immediately - wait for first interval
                        // This prevents modal from showing right after page load
                        
                        // Set interval for 5 minutes (300000 ms)
                        // For testing, you can change this to 60000 (1 minute) or 30000 (30 seconds)
                        const checkInterval = 300000; // 5 minutes in milliseconds (300000)
                        
                        // For testing: Use 30 seconds instead of 5 minutes
                        // const checkInterval = 30000; // 30 seconds for testing
                        
                        attendanceCheckInterval = setInterval(function() {
                            console.log('=== ⏰ ATTENDANCE CHECK INTERVAL FIRED ===');
                            console.log('Current time:', new Date().toLocaleTimeString());
                            console.log('hasJoined:', hasJoined, 'hasLeft:', hasLeft);
                            if (hasJoined && !hasLeft) {
                                console.log('✅ Conditions met, calling checkAttendance()...');
                                checkAttendance();
                            } else {
                                console.log('❌ Skipping check - hasJoined:', hasJoined, 'hasLeft:', hasLeft);
                            }
                        }, checkInterval);
                        
                        console.log('✅ Attendance check interval set. ID:', attendanceCheckInterval);
                        console.log('⏰ Next check in ' + (checkInterval / 1000 / 60) + ' minutes');
                        
                        // Verify interval is actually set
                        if (attendanceCheckInterval) {
                            console.log('✅ Interval confirmed active');
                        } else {
                            console.error('❌ ERROR: Interval not set!');
                        }
                    }

                    // Stop attendance checks
                    function stopAttendanceChecks() {
                if (attendanceCheckInterval) {
                    clearInterval(attendanceCheckInterval);
                    attendanceCheckInterval = null;
                }
                if (modalTimeout) {
                    clearTimeout(modalTimeout);
                    modalTimeout = null;
                }
                hideModal();
            }

                    // Check if meeting has ended
                    function isMeetingEnded() {
                if (!meetingEndTime || meetingEndTime === 'null') {
                    return false;
                }
                const endTime = new Date(meetingEndTime);
                return new Date() >= endTime;
            }

                    // Attendance check function
                    function checkAttendance() {
                        console.log('=== 🔔 CHECKING ATTENDANCE ===');
                        console.log('Current time:', new Date().toLocaleTimeString());
                        
                        // Stop if meeting has ended
                        if (isMeetingEnded()) {
                            console.log('❌ Meeting has ended, stopping checks');
                            stopAttendanceChecks();
                            return;
                        }

                        // Check if student is still joined
                        if (!hasJoined) {
                            console.log('❌ Student not joined, skipping check');
                            return;
                        }
                        
                        // Note: We don't check hasLeft here because we want to allow checks
                        // even if leave_time is set (might be old data)

                        // Increment check number
                        currentCheckNumber++;
                        console.log('✅ Starting check number:', currentCheckNumber);
                        console.log('✅ Meeting is active, showing modal...');
                        
                        // Show modal
                        showModal();
                    }

                    // Show attendance confirmation modal
                    function showModal() {
                        console.log('=== 🎯 SHOWING MODAL ===');
                        const modal = document.getElementById('attendanceModal');
                        if (!modal) {
                            console.error('❌ ERROR: Modal element not found!');
                            console.error('Looking for element with ID: attendanceModal');
                            alert('ERROR: Attendance modal not found. Please refresh the page.');
                            return;
                        }

                        console.log('✅ Modal element found, displaying...');
                        
                        // Immediately remove hidden class and inline style
                        modal.classList.remove('hidden');
                        modal.removeAttribute('style');
                        
                        // Set all styles with !important
                        modal.style.setProperty('display', 'flex', 'important');
                        modal.style.setProperty('z-index', '999999', 'important');
                        modal.style.setProperty('position', 'fixed', 'important');
                        modal.style.setProperty('top', '0', 'important');
                        modal.style.setProperty('left', '0', 'important');
                        modal.style.setProperty('right', '0', 'important');
                        modal.style.setProperty('bottom', '0', 'important');
                        modal.style.setProperty('width', '100%', 'important');
                        modal.style.setProperty('height', '100%', 'important');
                        modal.style.setProperty('background-color', 'rgba(0, 0, 0, 0.7)', 'important');
                        modal.style.setProperty('visibility', 'visible', 'important');
                        modal.style.setProperty('opacity', '1', 'important');
                        
                        // Ensure modal is in the DOM
                        if (!document.body.contains(modal)) {
                            document.body.appendChild(modal);
                        }
                        
                        // Force focus and bring to front
                        modal.focus();
                        
                        // Verify visibility immediately
                        const computedStyle = window.getComputedStyle(modal);
                        console.log('✅ Modal visibility check:');
                        console.log('Modal display:', computedStyle.display);
                        console.log('Modal z-index:', computedStyle.zIndex);
                        console.log('Modal visibility:', computedStyle.visibility);
                        console.log('Modal opacity:', computedStyle.opacity);
                        
                        // If still not visible, show alert
                        if (computedStyle.display === 'none' || computedStyle.visibility === 'hidden' || computedStyle.opacity === '0') {
                            console.error('❌ Modal still not visible after all attempts!');
                            alert('⚠️ Attendance check popup could not be displayed. Please check browser console for details.');
                        } else {
                            console.log('✅ Modal should now be visible!');
                        }
                        
                        let countdown = 5;
                        const countdownElement = document.getElementById('modalCountdown');
                        
                        // Clear any existing timeout
                        if (modalTimeout) {
                            clearInterval(modalTimeout);
                        }
                        
                        // Update countdown
                        if (countdownElement) {
                            countdownElement.textContent = `⏱️ This will close automatically in ${countdown} seconds...`;
                        }

                        console.log('Starting countdown timer for check #' + currentCheckNumber);
                        // Auto-close after 5 seconds
                        modalTimeout = setInterval(function() {
                            countdown--;
                            if (countdownElement) {
                                countdownElement.textContent = `⏱️ This will close automatically in ${countdown} seconds...`;
                            }
                            if (countdown <= 0) {
                                console.log('Countdown reached 0, marking as no response for check #' + currentCheckNumber);
                                clearInterval(modalTimeout);
                                modalTimeout = null;
                                // Mark as no response if not confirmed
                                markNoResponse();
                            }
                        }, 1000);
                    }

                    // Hide modal
                    function hideModal() {
                        console.log('Hiding modal...');
                        const modal = document.getElementById('attendanceModal');
                        if (modal) {
                            modal.style.cssText = 'display: none !important;';
                            modal.classList.add('hidden');
                        }
                        if (modalTimeout) {
                            clearInterval(modalTimeout);
                            modalTimeout = null;
                        }
                    }

                    // Confirm presence
                    const confirmPresenceBtn = document.getElementById('confirmPresenceBtn');
                    if (confirmPresenceBtn) {
                        confirmPresenceBtn.addEventListener('click', async function() {
                            console.log('Confirm presence button clicked for check #' + currentCheckNumber);
                            hideModal();
                            
                            try {
                                const response = await fetch(`/meetings/${meetingId}/confirm-presence`, {
                                    method: 'POST',
                                    headers: {
                                        'Content-Type': 'application/json',
                                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                                    }
                                });

                                const data = await response.json();

                                if (response.ok) {
                                    console.log('Presence confirmed successfully for check #' + data.check_number);
                                    checkResponses.present++;
                                    
                                    // Update status based on final status from server
                                    if (data.final_status === 'present') {
                                        updateAttendanceStatus('Present');
                                    } else if (data.final_status === 'absent') {
                                        updateAttendanceStatus('Absent');
                                    } else {
                                        updateAttendanceStatus('Pending');
                                    }
                                    
                                    // Refresh status from server to ensure accuracy
                                    setTimeout(refreshAttendanceStatus, 500);
                                    
                                    // Show check statistics
                                    const totalChecks = checkResponses.present + checkResponses.absent + checkResponses.no_response;
                                    const presentPercentage = totalChecks > 0 ? Math.round((checkResponses.present / totalChecks) * 100) : 0;
                                    alert(`✅ Check #${data.check_number} confirmed!\n\n📊 Statistics:\n- Present: ${checkResponses.present}/${totalChecks} (${presentPercentage}%)\n- Current Status: ${data.final_status === 'present' ? 'Present' : 'Pending'}`);
                                } else {
                                    console.error('Failed to confirm presence:', data.error);
                                    alert('Failed to confirm presence. Please try again.');
                                }
                            } catch (error) {
                                console.error('Error confirming presence:', error);
                                alert('An error occurred. Please try again.');
                            }
                        });
                    }
                    
                    // Mark as absent button
                    const markAbsentBtn = document.getElementById('markAbsentBtn');
                    if (markAbsentBtn) {
                        markAbsentBtn.addEventListener('click', async function() {
                            console.log('Mark absent button clicked');
                            if (confirm('Are you sure you want to mark yourself as absent?')) {
                                await markAbsent();
                            }
                        });
                    }

                    // Mark as absent (student clicked "No, I'm not in the meeting")
                    async function markAbsent() {
                        console.log('Mark absent button clicked for check #' + currentCheckNumber);
                        hideModal();
                        
                        try {
                            const response = await fetch(`/meetings/${meetingId}/mark-absent`, {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                                },
                                body: JSON.stringify({
                                    response_type: 'absent'
                                })
                            });

                            const data = await response.json();

                            if (response.ok) {
                                console.log('Marked as absent for check #' + data.check_number);
                                checkResponses.absent++;
                                
                                // Update status based on final status from server
                                if (data.final_status === 'present') {
                                    updateAttendanceStatus('Present');
                                } else if (data.final_status === 'absent') {
                                    updateAttendanceStatus('Absent');
                                } else {
                                    updateAttendanceStatus('Pending');
                                }
                                
                                const totalChecks = checkResponses.present + checkResponses.absent + checkResponses.no_response;
                                alert(`❌ Check #${data.check_number} marked as absent.\n\n📊 Current Status: ${data.final_status === 'present' ? 'Present' : 'Absent'}`);
                            } else {
                                console.error('Failed to mark absent:', data.error);
                            }
                        } catch (error) {
                            console.error('Error marking absent:', error);
                        }
                    }
                    
                    // Mark as no response (modal auto-closed)
                    async function markNoResponse() {
                        console.log('No response for check #' + currentCheckNumber);
                        hideModal();
                        
                        try {
                            const response = await fetch(`/meetings/${meetingId}/mark-absent`, {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                                },
                                body: JSON.stringify({
                                    response_type: 'no_response'
                                })
                            });

                            const data = await response.json();

                            if (response.ok) {
                                console.log('No response recorded for check #' + data.check_number);
                                checkResponses.no_response++;
                                
                                // Update status based on final status from server
                                if (data.final_status === 'present') {
                                    updateAttendanceStatus('Present');
                                } else if (data.final_status === 'absent') {
                                    updateAttendanceStatus('Absent');
                                } else {
                                    updateAttendanceStatus('Pending');
                                }
                            } else {
                                console.error('Failed to record no response:', data.error);
                            }
                        } catch (error) {
                            console.error('Error recording no response:', error);
                        }
                    }

                    // Update attendance status badge
                    function updateAttendanceStatus(status) {
                        console.log('Updating attendance status badge to:', status);
                        const badge = document.getElementById('attendanceStatusBadge');
                        if (!badge) {
                            console.warn('Status badge not found');
                            return;
                        }

                        badge.textContent = status;
                        
                        // Update badge color
                        badge.className = 'inline-block px-3 py-1 rounded-full text-xs font-semibold ';
                        if (status === 'Present') {
                            badge.className += 'bg-green-100 text-green-800';
                        } else if (status === 'Absent') {
                            badge.className += 'bg-red-100 text-red-800';
                        } else if (status === 'Pending') {
                            badge.className += 'bg-yellow-100 text-yellow-800';
                        } else {
                            badge.className += 'bg-gray-100 text-gray-800';
                        }
                        
                        console.log('Status badge updated successfully');
                    }
                    
                    // Function to refresh attendance status from server
                    async function refreshAttendanceStatus() {
                        try {
                            const response = await fetch(`/meetings/${meetingId}`, {
                                method: 'GET',
                                headers: {
                                    'Accept': 'text/html',
                                }
                            });
                            
                            if (response.ok) {
                                // Parse the response to get updated status
                                const text = await response.text();
                                const parser = new DOMParser();
                                const doc = parser.parseFromString(text, 'text/html');
                                const statusBadge = doc.getElementById('attendanceStatusBadge');
                                
                                if (statusBadge) {
                                    const statusText = statusBadge.textContent.trim();
                                    updateAttendanceStatus(statusText);
                                }
                            }
                        } catch (error) {
                            console.error('Error refreshing status:', error);
                        }
                    }

                    // Initialize attendance checks if already joined
                    console.log('=== CHECKING IF SHOULD START ATTENDANCE CHECKS ===');
                    console.log('hasJoined:', hasJoined);
                    console.log('hasLeft:', hasLeft);
                    console.log('attendanceCheckInterval:', attendanceCheckInterval);
                    
                    // Update status badge on page load
                    @if($attendance && $attendance->joined_at && !$attendance->leave_time)
                        // Student is in meeting - show Pending status
                        updateAttendanceStatus('Pending');
                    @elseif($attendance && $attendance->status)
                        // Show actual status from database
                        @if($attendance->status === 'present')
                            updateAttendanceStatus('Present');
                        @elseif($attendance->status === 'absent')
                            updateAttendanceStatus('Absent');
                        @else
                            updateAttendanceStatus('Pending');
                        @endif
                    @endif
                    
                    if (hasJoined && !hasLeft) {
                        console.log('✅ Student has joined, starting attendance checks...');
                        startAttendanceChecks();
                        console.log('✅ Attendance checks started. Interval ID:', attendanceCheckInterval);
                        
                        // QUICK TEST: Test modal after 10 seconds (for testing only)
                        setTimeout(function() { 
                            console.log('🧪 QUICK TEST: Checking conditions...');
                            console.log('  - hasJoined:', hasJoined);
                            console.log('  - hasLeft:', hasLeft);
                            if (hasJoined && !hasLeft) { 
                                console.log('🧪 QUICK TEST: Triggering attendance check after 10 seconds');
                                checkAttendance(); 
                            } else {
                                console.log('🧪 QUICK TEST: Skipped - hasJoined:', hasJoined, 'hasLeft:', hasLeft);
                            }
                        }, 10000); // 10 seconds for quick testing
                    } else {
                        console.log('❌ Not starting attendance checks - hasJoined:', hasJoined, 'hasLeft:', hasLeft);
                    }
                    
                    // Test button to manually trigger attendance check
                    const testBtn = document.getElementById('testAttendanceCheckBtn');
                    if (testBtn) {
                        testBtn.addEventListener('click', function() {
                            console.log('🧪 Test button clicked - triggering attendance check');
                            console.log('Current state - hasJoined:', hasJoined, 'hasLeft:', hasLeft);
                            if (hasJoined && !hasLeft) {
                                checkAttendance();
                            } else {
                                alert('⚠️ You must be joined to the meeting first. Please click "Join Meeting" button.');
                            }
                        });
                    }
                    
                    // Add global test function for debugging (can be called from browser console)
                    window.testAttendanceModal = function() {
                        console.log('🧪 Manual test: Showing attendance modal');
                        showModal();
                    };
                    
                    // Add global test function to check status
                    window.checkAttendanceStatus = function() {
                        console.log('Current attendance state:');
                        console.log('  - hasJoined:', hasJoined);
                        console.log('  - hasLeft:', hasLeft);
                        console.log('  - attendanceCheckInterval:', attendanceCheckInterval);
                        console.log('  - currentCheckNumber:', currentCheckNumber);
                        console.log('  - checkResponses:', checkResponses);
                    };

                    // Check every minute if meeting has ended
                    setInterval(function() {
                        if (hasJoined && !hasLeft && isMeetingEnded()) {
                            stopAttendanceChecks();
                        }
                    }, 60000); // Check every minute

                    // Handle page unload (browser close, tab close, navigation)
                    window.addEventListener('beforeunload', function(e) {
                        // Don't trigger leave if we just joined (page reload after join)
                        const justJoined = sessionStorage.getItem('justJoinedMeeting_' + meetingId);
                        if (justJoined === 'true') {
                            sessionStorage.removeItem('justJoinedMeeting_' + meetingId);
                            console.log('⚠️ Skipping leave on beforeunload - just joined, this is a page reload');
                            return;
                        }
                        
                        // Also check if this is a navigation within the same site (not a true leave)
                        // Only trigger leave on actual browser close/tab close
                        // Note: We can't perfectly detect this, but we can be more conservative
                        
                        if (hasJoined && !hasLeft && !leaveRequestSent) {
                            console.log('Page unloading - marking as left');
                            leaveRequestSent = true;
                            
                            // Stop attendance checks
                            stopAttendanceChecks();
                            
                            // Use sendBeacon as fallback for page unload
                            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
                            
                            if (navigator.sendBeacon) {
                                const formData = new FormData();
                                formData.append('_token', csrfToken);
                                navigator.sendBeacon(`/meetings/${meetingId}/leave`, formData);
                            }
                        }
                    });
                }
            })();
        </script>
    @endif

    @if(Auth::check() && Auth::user()->role === 'teacher')
        <script>
            // Manual attendance marking function
            async function markAttendance(meetingId, studentId, status) {
                if (!confirm(`Are you sure you want to mark this student as ${status}?`)) {
                    return;
                }

                try {
                    const response = await fetch(`/meetings/${meetingId}/mark-attendance`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        },
                        body: JSON.stringify({
                            student_id: studentId,
                            status: status
                        })
                    });

                    const data = await response.json();

                    if (response.ok && data.success) {
                        alert(data.message || 'Attendance marked successfully!');
                        location.reload(); // Reload to show updated status
                    } else {
                        alert(data.error || 'Failed to mark attendance.');
                    }
                } catch (error) {
                    console.error('Error:', error);
                    alert('An error occurred while marking attendance.');
                }
            }
        </script>
    @endif
@endauth
@endsection
