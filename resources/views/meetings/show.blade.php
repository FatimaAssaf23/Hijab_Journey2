@extends('layouts.app')

@section('content')
<div class="w-full max-w-full mx-auto py-10 px-4 sm:px-6 lg:px-8">
    <div class="bg-gradient-to-br from-pink-50 via-white to-pink-100 shadow-2xl rounded-3xl p-10 border-2 border-pink-200">
        <div class="mb-6">
            <a href="{{ route('meetings.index') }}" 
               class="inline-flex items-center gap-2 bg-gradient-to-r from-pink-500 to-pink-600 hover:from-pink-600 hover:to-pink-700 text-white font-semibold py-2 px-4 rounded-lg shadow-md transition-all duration-200 mb-4">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Go Back to Meetings
            </a>
        </div>
        <h1 class="text-3xl font-extrabold text-pink-600 mb-8 drop-shadow">{{ $meeting->title }}</h1>

        <!-- Meeting Information Section -->
        <div class="bg-white rounded-xl p-6 border-2 border-pink-200 mb-6">
            <button onclick="toggleSection('meetingInfo')" class="w-full flex items-center justify-between text-left">
                <h2 class="text-xl font-bold text-pink-700 flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Meeting Information
                </h2>
                <svg id="meetingInfoIcon" xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-pink-600 transform transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                </svg>
            </button>
            <div id="meetingInfo" class="hidden mt-4">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="bg-pink-50 rounded-lg p-4 border border-pink-200">
                    <div class="text-sm font-semibold text-pink-600 mb-1">Class</div>
                    <div class="text-gray-800 font-medium">{{ $meeting->studentClass->class_name ?? 'N/A' }}</div>
                </div>
                <div class="bg-pink-50 rounded-lg p-4 border border-pink-200">
                    <div class="text-sm font-semibold text-pink-600 mb-1">Teacher</div>
                    <div class="text-gray-800 font-medium">{{ $meeting->teacher->first_name }} {{ $meeting->teacher->last_name }}</div>
                </div>
                <div class="bg-pink-50 rounded-lg p-4 border border-pink-200">
                    <div class="text-sm font-semibold text-pink-600 mb-1">Date</div>
                    <div class="text-gray-800 font-medium">
                        @if($meeting->start_time)
                            @php
                                $startDate = $meeting->start_time->setTimezone(config('app.timezone'));
                            @endphp
                            {{ $startDate->format('F d, Y') }}
                        @else
                            Not set
                        @endif
                    </div>
                </div>
                <div class="bg-pink-50 rounded-lg p-4 border border-pink-200">
                    <div class="text-sm font-semibold text-pink-600 mb-1">Time</div>
                    <div class="text-gray-800 font-medium">
                        @if($meeting->start_time && $meeting->end_time)
                            @php
                                $startTime = $meeting->start_time->setTimezone(config('app.timezone'));
                                $endTime = $meeting->end_time->setTimezone(config('app.timezone'));
                            @endphp
                            {{ $startTime->format('h:i A') }} - {{ $endTime->format('h:i A') }}
                        @else
                            Not set
                        @endif
                    </div>
                </div>
                <div class="bg-pink-50 rounded-lg p-4 border border-pink-200">
                    <div class="text-sm font-semibold text-pink-600 mb-1">Status</div>
                    <div>
                        <span class="inline-block bg-blue-100 text-blue-800 text-xs font-semibold px-3 py-1 rounded-full">
                            {{ ucfirst($meeting->status) }}
                        </span>
                    </div>
                </div>
            </div>
            @if($meeting->description)
                <div class="mt-4 bg-pink-50 rounded-lg p-4 border border-pink-200">
                    <div class="text-sm font-semibold text-pink-600 mb-2">Description</div>
                    <p class="text-gray-700">{{ $meeting->description }}</p>
                </div>
            @endif
            </div>
        </div>

        @if(Auth::check() && Auth::user()->role === 'teacher')
                <!-- Verification Code Section -->
                <div class="bg-gradient-to-r from-blue-50 to-indigo-50 border-2 border-blue-300 rounded-xl p-6 mb-6 shadow-lg">
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
                
                <!-- Attendance Report Section -->
                <div class="bg-white rounded-xl p-6 border-2 border-blue-200 mb-6">
                    <div class="flex items-center justify-between">
                        <button onclick="toggleSection('attendanceReport')" class="flex-1 flex items-center justify-between text-left">
                            <h3 class="text-2xl font-bold text-blue-700 flex items-center gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                Attendance Report
                            </h3>
                            <svg id="attendanceReportIcon" xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-600 transform transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>
                        <a href="{{ route('meetings.export-attendance', $meeting) }}" 
                           class="ml-4 inline-flex items-center gap-2 bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 text-white font-semibold py-2 px-4 rounded-lg shadow-md transition-all duration-200">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            Export to CSV
                        </a>
                    </div>
                    <div id="attendanceReport" class="hidden mt-6">
                    
                    @php
                        // Count students with status "present" (verified attendance)
                        // Include: status='present', is_verified=true, or legacy statuses (on_time, late)
                        $totalPresentCount = $attendances->filter(function($att) {
                            return $att->status === 'present' 
                                || ($att->is_verified && $att->status === 'present')
                                || ($att->is_verified) // If verified, count as present
                                || in_array($att->status, ['on_time', 'late']);
                        })->count();
                        
                        // Calculate absent students
                        // Absent = students not in attendances OR students with status 'absent' OR students with status 'pending' (if meeting ended)
                        $attendedStudentIds = $attendances->pluck('student_id')->toArray();
                        $absentStudents = $allStudents->filter(function($student) use ($attendances, $meeting, $attendedStudentIds) {
                            // Not in attendance records at all
                            if (!in_array($student->student_id, $attendedStudentIds)) {
                                return true;
                            }
                            
                            // Check attendance status
                            $att = $attendances->firstWhere('student_id', $student->student_id);
                            if ($att) {
                                // Absent if status is 'absent'
                                if ($att->status === 'absent') {
                                    return true;
                                }
                                // Absent if pending and meeting has ended
                                if ($att->status === 'pending' && $meeting->end_time && $meeting->end_time->isPast()) {
                                    return true;
                                }
                                // Not absent if present or verified
                                if ($att->status === 'present' || $att->is_verified) {
                                    return false;
                                }
                            }
                            
                            return false;
                        });
                    @endphp
                    <div class="mb-4 grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                            <div class="text-sm text-green-600 font-semibold">Total Attended</div>
                            <div class="text-2xl font-bold text-green-700">{{ $totalPresentCount }}</div>
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

                    @php
                        // Filter to show only students with status "present" (verified attendance)
                        // Show: status='present', is_verified=true, or legacy statuses (on_time, late)
                        $presentAttendances = $attendances->filter(function($att) {
                            return $att->status === 'present' 
                                || ($att->is_verified && $att->status === 'present')
                                || ($att->is_verified) // If verified, show in attended list
                                || in_array($att->status, ['on_time', 'late']);
                        });
                    @endphp
                    @if($presentAttendances->count() > 0)
                        <div class="mb-6">
                            <h4 class="text-lg font-semibold text-gray-700 mb-3">Attended Students</h4>
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200 border border-gray-300 rounded-lg">
                                    <thead class="bg-gradient-to-r from-blue-500 to-blue-600 text-white">
                                        <tr>
                                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider">Student Name</th>
                                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider">Joined At</th>
                                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider">Status</th>
                                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider">Code Verified</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @foreach($presentAttendances as $att)
                                            <tr class="hover:bg-gray-50">
                                                <td class="px-4 py-3 whitespace-nowrap text-sm font-medium text-gray-900">
                                                    {{ $att->student->user->first_name }} {{ $att->student->user->last_name }}
                                                </td>
                                                <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-700">
                                                    @if($att->join_time)
                                                        @php
                                                            // Convert to app timezone for display
                                                            $joinTime = $att->join_time->setTimezone(config('app.timezone'));
                                                        @endphp
                                                        {{ $joinTime->format('M d, Y h:i A') }}
                                                    @else
                                                        <span class="text-gray-400">-</span>
                                                    @endif
                                                </td>
                                                <td class="px-4 py-3 whitespace-nowrap">
                                                    @php
                                                        // Priority: present (verified) > on_time > late > pending > absent
                                                        $statusClass = 'bg-gray-100 text-gray-800';
                                                        $statusText = 'Pending';
                                                        
                                                        if ($att->status === 'present' || ($att->is_verified && $att->status === 'present')) {
                                                            // Verified and present - highest priority
                                                            $statusClass = 'bg-green-100 text-green-800';
                                                            $statusText = 'Present';
                                                        } elseif ($att->is_verified) {
                                                            // Verified but status might be different - still show as present
                                                            $statusClass = 'bg-green-100 text-green-800';
                                                            $statusText = 'Present';
                                                        } elseif ($att->status === 'on_time') {
                                                            $statusClass = 'bg-green-100 text-green-800';
                                                            $statusText = 'On Time';
                                                        } elseif ($att->status === 'late') {
                                                            $statusClass = 'bg-yellow-100 text-yellow-800';
                                                            $statusText = 'Late';
                                                        } elseif ($att->status === 'absent') {
                                                            $statusClass = 'bg-red-100 text-red-800';
                                                            $statusText = 'Absent';
                                                        }
                                                    @endphp
                                                    <span class="inline-block px-3 py-1 rounded-full text-xs font-semibold {{ $statusClass }}">
                                                        {{ $statusText }}
                                                    </span>
                                                </td>
                                                <td class="px-4 py-3 whitespace-nowrap">
                                                    @if($att->is_verified)
                                                        <span class="px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-800">
                                                            ✓ Verified
                                                        </span>
                                                    @else
                                                        <span class="px-3 py-1 rounded-full text-xs font-semibold bg-yellow-100 text-yellow-800">
                                                            Pending
                                                        </span>
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
                </div>

        @endif

        @if(Auth::check() && Auth::user()->role === 'student')
                <!-- Student Attendance Status Section -->
                <div class="bg-white rounded-xl p-6 border-2 border-green-200 mb-6">
                    <button onclick="toggleSection('studentStatus')" class="w-full flex items-center justify-between text-left mb-4">
                        <h3 class="text-xl font-bold text-green-700 flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Your Attendance Status
                        </h3>
                        <svg id="studentStatusIcon" xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-green-600 transform transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                    <div id="studentStatus" class="hidden">
                    <div class="space-y-3">
                        @if($attendance)
                            @if($attendance->join_time)
                                <div class="flex justify-between">
                                    <span class="text-gray-700 font-semibold">Joined At:</span>
                                    @php
                                        $joinTime = $attendance->join_time->setTimezone(config('app.timezone'));
                                    @endphp
                                    <span class="text-gray-900">{{ $joinTime->format('M d, Y h:i A') }}</span>
                                </div>
                            @endif
                            <div class="flex justify-between">
                                <span class="text-gray-700 font-semibold">Status:</span>
                                @php
                                    $statusClass = 'bg-gray-100 text-gray-800';
                                    $statusText = 'Pending';
                                    
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
                                        $statusClass = $attendance->status === 'on_time' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800';
                                        $statusText = $attendance->status === 'on_time' ? 'On Time' : 'Late';
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
                </div>
        @endif

        <!-- Google Meet Section -->
        @if($meeting->google_meet_link)
            <div class="bg-white rounded-xl p-6 border-2 border-purple-200 mb-6">
                <button onclick="toggleSection('googleMeet')" class="w-full flex items-center justify-between text-left mb-4">
                    <h3 class="text-xl font-bold text-purple-700 flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
                        </svg>
                        Confirm Attendance
                    </h3>
                    <svg id="googleMeetIcon" xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-purple-600 transform transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>
            <div id="googleMeet" class="hidden text-center space-y-4">
                @auth
                    @if(auth()->user()->role === 'student')
                        @if(!$attendance || ($attendance && isset($attendance->leave_time) && $attendance->leave_time))
                            {{-- Show join button if no attendance OR if they have left (allow rejoin) --}}
                            @if($attendance && isset($attendance->leave_time) && $attendance->leave_time)
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
                        @elseif($attendance && (!isset($attendance->leave_time) || !$attendance->leave_time))
                            {{-- Verification Code Section --}}
                            @if(!isset($attendance->is_verified) || !$attendance->is_verified)
                                @php
                                    $verificationAttempts = $attendance->verification_attempts ?? 0;
                                    $remainingAttempts = 2 - $verificationAttempts;
                                    $isAbsent = isset($attendance->status) && $attendance->status === 'absent';
                                @endphp
                                
                                @if($isAbsent || $verificationAttempts >= 2)
                                    <div class="mb-6 bg-red-50 border-2 border-red-300 rounded-xl p-6 max-w-2xl mx-auto">
                                        <h3 class="text-xl font-bold text-red-800 mb-3">❌ Verification Failed</h3>
                                        <p class="text-sm text-red-700 mb-2">
                                            You have exceeded the maximum number of verification attempts (2). You have been marked as absent.
                                        </p>
                                        <p class="text-xs text-red-600">
                                            Please contact your teacher if you believe this is an error.
                                        </p>
                                    </div>
                                @else
                                    <div class="mb-6 bg-yellow-50 border-2 border-yellow-300 rounded-xl p-6 max-w-2xl mx-auto">
                                        <h3 class="text-xl font-bold text-yellow-800 mb-3">🔐 Verify Your Presence</h3>
                                        <p class="text-sm text-yellow-700 mb-2">
                                            The teacher will send a verification code in the meeting chat. Please enter it below to confirm your attendance.
                                        </p>
                                        <p class="text-xs text-yellow-600 mb-4">
                                            Remaining attempts: <span id="remainingAttemptsDisplay" class="font-bold">{{ $remainingAttempts }}</span> / 2
                                        </p>
                                        <div class="flex gap-3 items-center">
                                            <input type="text" 
                                                   id="verificationCodeInput" 
                                                   placeholder="Enter verification code" 
                                                   maxlength="10"
                                                   class="flex-1 px-4 py-3 border-2 border-yellow-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-yellow-500 text-center text-lg font-mono uppercase tracking-wider">
                                            <button type="button" 
                                                    id="verifyCodeBtn"
                                                    onclick="verifyCode()"
                                                    class="bg-gradient-to-r from-yellow-500 to-yellow-600 hover:from-yellow-600 hover:to-yellow-700 text-white font-bold py-3 px-6 rounded-lg shadow-md transition-all duration-150">
                                                Verify
                                            </button>
                                        </div>
                                        <div id="verificationMessage" class="mt-3 text-sm font-semibold hidden"></div>
                                    </div>
                                @endif
                            @else
                                <div class="mb-6 bg-green-50 border-2 border-green-300 rounded-xl p-4 max-w-2xl mx-auto">
                                    <p class="text-green-800 font-semibold flex items-center justify-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        ✓ Your presence has been verified!
                                    </p>
                                </div>
                            @endif
                            
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
            </div>
        @endif

        <!-- JavaScript for Section Toggle -->
        <script>
            function toggleSection(sectionId) {
                const section = document.getElementById(sectionId);
                const icon = document.getElementById(sectionId + 'Icon');
                
                if (section.classList.contains('hidden')) {
                    section.classList.remove('hidden');
                    if (icon) {
                        icon.style.transform = 'rotate(180deg)';
                    }
                } else {
                    section.classList.add('hidden');
                    if (icon) {
                        icon.style.transform = 'rotate(0deg)';
                    }
                }
            }
        </script>

    </div>
</div>

@auth
    @if(auth()->user()->role === 'student')
        <script>
            (function() {
                const meetingId = {{ $meeting->meeting_id }};
                const googleMeetLink = '{{ $meeting->google_meet_link }}';
                
                // Check if student has joined (has attendance record with join_time)
                let hasJoined = {{ ($attendance && isset($attendance->join_time) && $attendance->join_time) ? 'true' : 'false' }};
                
                // Check if student has left (has leave_time set)
                let hasLeft = {{ ($attendance && isset($attendance->leave_time) && $attendance->leave_time) ? 'true' : 'false' }};
                
                let leaveRequestSent = false;
                let meetWindow = null;

                    // Join Meeting
                    const joinBtn = document.getElementById('joinMeetingBtn');
                    if (joinBtn) {
                        joinBtn.addEventListener('click', async function(e) {
                            e.preventDefault();
                            
                            if (hasJoined && !hasLeft) {
                                alert('You have already joined this meeting.');
                                return false;
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

                                if (response.ok && data.success) {
                                    hasJoined = true;
                                    hasLeft = false;
                                    
                                    // Store join status in sessionStorage
                                    sessionStorage.setItem('meetingJoined_' + meetingId, 'true');
                                    sessionStorage.setItem('joinTime_' + meetingId, new Date().toISOString());
                                    
                                    // Open Google Meet in popup window
                                    if (googleMeetLink) {
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
                                    }
                                    
                                    alert('✅ Successfully joined the meeting! You can now enter the verification code.');
                                    // Force reload after a short delay to ensure data is saved
                                    setTimeout(() => {
                                        location.reload(true);
                                    }, 500);
                                } else {
                                    // Even if there's an error, check if attendance exists
                                    if (data.attendance && data.attendance.join_time) {
                                        // Attendance exists, just reload to show verification form
                                        alert('You are already in the meeting. You can now enter the verification code.');
                                        setTimeout(() => {
                                            location.reload(true);
                                        }, 500);
                                    } else {
                                        alert(data.error || 'Failed to join meeting. Please try again.');
                                    }
                                }
                            } catch (error) {
                                console.error('Error:', error);
                                alert('An error occurred while joining the meeting.');
                            }
                            
                            return false;
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
                                if (leaveRequestSent) {
                                    return;
                                }

                                leaveRequestSent = true;

                                // Close popup if open
                                if (meetWindow && !meetWindow.closed) {
                                    meetWindow.close();
                                }

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
                        });
                    }

                    // Handle page unload
                    window.addEventListener('beforeunload', function(e) {
                        const justJoined = sessionStorage.getItem('justJoinedMeeting_' + meetingId);
                        if (justJoined === 'true') {
                            sessionStorage.removeItem('justJoinedMeeting_' + meetingId);
                            return;
                        }
                        
                        if (hasJoined && !hasLeft && !leaveRequestSent) {
                            leaveRequestSent = true;
                            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
                            
                            if (navigator.sendBeacon) {
                                const formData = new FormData();
                                formData.append('_token', csrfToken);
                                navigator.sendBeacon(`/meetings/${meetingId}/leave`, formData);
                            }
                        }
                    });

                    // Verification Code Function
                    window.verifyCode = async function() {
                        const codeInput = document.getElementById('verificationCodeInput');
                        const verifyBtn = document.getElementById('verifyCodeBtn');
                        const messageDiv = document.getElementById('verificationMessage');
                        
                        if (!codeInput || !codeInput.value.trim()) {
                            alert('Please enter a verification code.');
                            return;
                        }
                        
                        // Note: Server will check if student has joined - no need for frontend check

                        const code = codeInput.value.trim().toUpperCase();
                        verifyBtn.disabled = true;
                        verifyBtn.textContent = 'Verifying...';
                        messageDiv.classList.add('hidden');

                        try {
                            const response = await fetch(`/meetings/${meetingId}/verify-code`, {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                                },
                                body: JSON.stringify({ code: code })
                            });

                            const data = await response.json();

                            if (response.ok && data.success) {
                                messageDiv.textContent = data.message || 'Code verified successfully!';
                                messageDiv.className = 'mt-3 text-sm font-semibold text-green-700';
                                messageDiv.classList.remove('hidden');
                                codeInput.disabled = true;
                                verifyBtn.disabled = true;
                                verifyBtn.textContent = 'Verified ✓';
                                
                                // Reload page after 2 seconds to show updated status
                                setTimeout(() => {
                                    location.reload();
                                }, 2000);
                            } else {
                                // Check if marked as absent
                                if (data.marked_absent) {
                                    messageDiv.textContent = data.error || 'You have been marked as absent.';
                                    messageDiv.className = 'mt-3 text-sm font-semibold text-red-700';
                                    messageDiv.classList.remove('hidden');
                                    codeInput.disabled = true;
                                    verifyBtn.disabled = true;
                                    verifyBtn.textContent = 'Max Attempts Reached';
                                    
                                    // Reload page after 3 seconds to show absent status
                                    setTimeout(() => {
                                        location.reload();
                                    }, 3000);
                                } else {
                                    // Update remaining attempts display
                                    const remainingAttemptsDisplay = document.getElementById('remainingAttemptsDisplay');
                                    if (remainingAttemptsDisplay && data.remaining_attempts !== undefined) {
                                        remainingAttemptsDisplay.textContent = data.remaining_attempts;
                                    }
                                    
                                    let errorMessage = data.error || 'Invalid code. Please try again.';
                                    if (data.remaining_attempts !== undefined) {
                                        errorMessage += ` (${data.remaining_attempts} attempt(s) remaining)`;
                                    }
                                    
                                    messageDiv.textContent = errorMessage;
                                    messageDiv.className = 'mt-3 text-sm font-semibold text-red-700';
                                    messageDiv.classList.remove('hidden');
                                    codeInput.value = '';
                                    codeInput.focus();
                                    verifyBtn.disabled = false;
                                    verifyBtn.textContent = 'Verify';
                                }
                            }
                        } catch (error) {
                            console.error('Error verifying code:', error);
                            messageDiv.textContent = 'An error occurred. Please try again.';
                            messageDiv.className = 'mt-3 text-sm font-semibold text-red-700';
                            messageDiv.classList.remove('hidden');
                            verifyBtn.disabled = false;
                            verifyBtn.textContent = 'Verify';
                        }
                    };

                    // Allow Enter key to submit verification code
                    const codeInput = document.getElementById('verificationCodeInput');
                    if (codeInput) {
                        codeInput.addEventListener('keypress', function(e) {
                            if (e.key === 'Enter') {
                                window.verifyCode();
                            }
                        });
                    }
                })();
        </script>
    @endif

    @if(Auth::check() && Auth::user()->role === 'teacher')
        <script>
            const teacherMeetingId = {{ $meeting->meeting_id }};

            async function regenerateCode() {
                if (!confirm('Are you sure you want to regenerate the verification code? Students who already verified will need to verify again with the new code.')) {
                    return;
                }

                try {
                    const response = await fetch(`/meetings/${teacherMeetingId}/verification-code?regenerate=true`, {
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
    @endif
@endauth
@endsection
