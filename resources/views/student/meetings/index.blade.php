@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold mb-6">My Meetings</h1>
    
    @if($enrollments->count() > 0)
        <div class="grid gap-6">
            @foreach($enrollments as $enrollment)
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-xl font-semibold mb-2">{{ $enrollment->meeting->title }}</h3>
                <p class="text-gray-600 mb-2">Teacher: {{ $enrollment->meeting->teacher->first_name }} {{ $enrollment->meeting->teacher->last_name }}</p>
                <p class="text-gray-600 mb-2">Date: {{ $enrollment->meeting->scheduled_at->format('M d, Y H:i') }}</p>
                <p class="text-gray-600 mb-4">
                    Status: 
                    <span class="px-3 py-1 rounded-full text-sm font-medium
                        @if($enrollment->attendance_status === 'present') bg-green-100 text-green-800
                        @elseif($enrollment->attendance_status === 'absent') bg-red-100 text-red-800
                        @else bg-yellow-100 text-yellow-800
                        @endif">
                        {{ ucfirst($enrollment->attendance_status) }}
                    </span>
                </p>
                
                @php
                    $now = now();
                    $meetingStart = $enrollment->meeting->scheduled_at;
                    $meetingEnd = $meetingStart->copy()->addMinutes($enrollment->meeting->duration_minutes);
                    $isMeetingTime = $now >= $meetingStart && $now <= $meetingEnd;
                @endphp
                
                @if($isMeetingTime && $enrollment->attendance_status === 'pending')
                <button onclick="openMeetingPopup({{ $enrollment->meeting->id }}, '{{ $enrollment->meeting->google_meet_link }}')" 
                        class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded">
                    Join Meeting
                </button>
                @elseif($enrollment->attendance_status !== 'pending')
                <p class="text-gray-500 italic">Meeting completed</p>
                @else
                <p class="text-gray-500 italic">Meeting not started yet</p>
                @endif
            </div>
            @endforeach
        </div>
        
        <div class="mt-6">
            {{ $enrollments->links() }}
        </div>
    @else
        <div class="bg-white rounded-lg shadow-md p-6 text-center">
            <p class="text-gray-500">No meetings found.</p>
        </div>
    @endif
</div>

<script>
function openMeetingPopup(meetingId, meetUrl) {
    const width = 1200;
    const height = 800;
    const left = (screen.width - width) / 2;
    const top = (screen.height - height) / 2;
    
    window.open(
        `/student/meetings/${meetingId}/join`,
        'GoogleMeetWindow',
        `width=${width},height=${height},left=${left},top=${top},resizable=yes,scrollbars=yes`
    );
}
</script>
@endsection
