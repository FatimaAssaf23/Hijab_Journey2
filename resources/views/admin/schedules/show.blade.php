@extends('layouts.admin')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="bg-gradient-to-r from-[#FC8EAC] via-[#EC769A] to-[#6EC6C5] shadow-xl rounded-2xl p-6 mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-4xl font-extrabold text-white mb-2">📅 Schedule Editor</h1>
                    <p class="text-pink-100">
                        {{ $schedule->teacher->first_name }} {{ $schedule->teacher->last_name }}
                        @if($schedule->studentClass)
                            - {{ $schedule->studentClass->class_name }}
                        @endif
                    </p>
                </div>
                <a href="{{ route('admin.schedules.index') }}" class="bg-white/20 hover:bg-white/30 text-white px-6 py-3 rounded-xl font-bold transition">
                    ← Back to All Schedules
                </a>
            </div>
        </div>

        <!-- Schedule Info -->
        <div class="bg-white rounded-2xl shadow-lg p-6 mb-6">
            <div class="flex items-center justify-between">
                <div>
                    <div class="flex items-center gap-3 mb-2">
                        <span class="w-4 h-4 rounded-full {{ $schedule->status === 'active' ? 'bg-green-500' : ($schedule->status === 'paused' ? 'bg-yellow-500' : 'bg-gray-500') }}"></span>
                        <span class="text-2xl font-bold text-gray-800">Status: {{ ucfirst($schedule->status) }}</span>
                    </div>
                    <p class="text-gray-600">Started: {{ $schedule->started_at->format('M d, Y') }}</p>
                </div>
            </div>
        </div>

        <!-- Schedule Timeline -->
        <div class="space-y-6">
            @foreach($eventsByMonth as $month => $events)
                <div class="bg-white rounded-2xl shadow-lg p-6">
                    <h2 class="text-2xl font-bold text-[#197D8C] mb-4">
                        {{ \Carbon\Carbon::parse($month . '-01')->format('F Y') }}
                    </h2>
                    <div class="space-y-3">
                        @foreach($events as $event)
                            @php
                                $typeColors = [
                                    'lesson' => 'bg-blue-100 border-blue-300',
                                    'assignment' => 'bg-orange-100 border-orange-300',
                                    'quiz' => 'bg-green-100 border-green-300'
                                ];
                                $typeIcons = [
                                    'lesson' => '📘',
                                    'assignment' => '📝',
                                    'quiz' => '📊'
                                ];
                            @endphp
                            <div class="flex items-center gap-4 p-4 rounded-lg border-2 {{ $typeColors[$event->event_type] ?? 'bg-gray-100 border-gray-300' }} hover:shadow-md transition"
                                 onclick="editEvent({{ $event->event_id }})" style="cursor: pointer;">
                                <div class="text-3xl">{{ $typeIcons[$event->event_type] ?? '📌' }}</div>
                                <div class="flex-1">
                                    <div class="font-bold text-gray-800">{{ $event->event_title }}</div>
                                    <div class="text-sm text-gray-600">
                                        @if($event->level)
                                            Level: {{ $event->level->level_name }}
                                        @endif
                                    </div>
                                    @if($event->admin_notes)
                                        <div class="text-xs text-purple-600 mt-1">Admin Note: {{ $event->admin_notes }}</div>
                                    @endif
                                </div>
                                <div class="text-right">
                                    <div class="font-semibold text-gray-800">{{ $event->release_date->format('M d, Y') }}</div>
                                    <div class="text-sm">
                                        <span class="px-2 py-1 rounded {{ $event->status === 'released' ? 'bg-green-500 text-white' : 'bg-gray-200' }}">
                                            {{ ucfirst($event->status) }}
                                        </span>
                                    </div>
                                </div>
                                <div class="flex gap-2">
                                    @if($event->edited_by_admin)
                                        <span class="text-xs bg-purple-100 text-purple-800 px-2 py-1 rounded" title="Edited by Admin">
                                            ⚙️
                                        </span>
                                    @endif
                                    <button onclick="event.stopPropagation(); deleteEvent({{ $event->event_id }})" 
                                            class="text-red-600 hover:text-red-800 px-2 py-1 rounded hover:bg-red-50">
                                        🗑️
                                    </button>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>

<!-- Edit Event Modal -->
<div id="edit-event-modal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center">
    <div class="bg-white rounded-2xl shadow-2xl p-6 max-w-md w-full mx-4">
        <h3 class="text-2xl font-bold text-gray-800 mb-4">Edit Event</h3>
        <form id="edit-event-form">
            <input type="hidden" id="edit-event-id">
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Release Date</label>
                    <input type="date" id="edit-release-date" required
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#EC769A]">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Admin Notes</label>
                    <textarea id="edit-admin-notes" rows="3"
                              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#EC769A]"></textarea>
                </div>
            </div>
            <div class="flex gap-3 mt-6">
                <button type="submit" class="flex-1 bg-[#6EC6C5] hover:bg-[#197D8C] text-white px-6 py-3 rounded-lg font-bold transition">
                    Save Changes
                </button>
                <button type="button" onclick="closeEditModal()" class="flex-1 bg-gray-300 hover:bg-gray-400 text-gray-800 px-6 py-3 rounded-lg font-bold transition">
                    Cancel
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function editEvent(eventId) {
    // Fetch event data and populate modal
    fetch(`/admin/schedules/events/${eventId}`)
        .then(response => response.json())
        .then(data => {
            document.getElementById('edit-event-id').value = eventId;
            document.getElementById('edit-release-date').value = data.event.release_date;
            document.getElementById('edit-admin-notes').value = data.event.admin_notes || '';
            document.getElementById('edit-event-modal').classList.remove('hidden');
        });
}

function closeEditModal() {
    document.getElementById('edit-event-modal').classList.add('hidden');
}

document.getElementById('edit-event-form').addEventListener('submit', function(e) {
    e.preventDefault();
    const eventId = document.getElementById('edit-event-id').value;
    const formData = {
        release_date: document.getElementById('edit-release-date').value,
        admin_notes: document.getElementById('edit-admin-notes').value,
        _token: '{{ csrf_token() }}'
    };
    
    fetch(`{{ url('/admin/schedules/events') }}/${eventId}`, {
        method: 'PUT',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify(formData)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        } else {
            alert('Error: ' + data.message);
        }
    });
});

function deleteEvent(eventId) {
    if (!confirm('Are you sure you want to delete this event?')) return;
    
    fetch(`{{ url('/admin/schedules/events') }}/${eventId}`, {
        method: 'DELETE',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({
            shift_subsequent: false
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        } else {
            alert('Error: ' + data.message);
        }
    });
}
</script>
@endsection
