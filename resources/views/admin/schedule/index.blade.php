@extends('layouts.admin')

@section('content')
<div class="min-h-screen relative overflow-hidden">
    <!-- Background Pattern -->
    <div class="absolute inset-0 opacity-5">
        <div class="absolute top-0 left-0 w-full h-full" style="background-image: radial-gradient(circle, rgba(252,142,172,0.4) 1px, transparent 1px); background-size: 40px 40px;"></div>
    </div>

    <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Header Section -->
        <div class="mb-8 animate-fade-in">
            <div class="bg-gradient-to-br from-pink-200/80 via-rose-100/70 to-cyan-200/80 rounded-2xl shadow-xl overflow-hidden transform transition-all duration-500 hover:shadow-2xl border border-pink-200/50 backdrop-blur-sm p-6 lg:p-8">
                <div class="flex flex-col lg:flex-row items-center justify-between gap-4">
                    <div class="flex-1">
                        <div class="inline-flex items-center gap-2 bg-white/40 backdrop-blur-md px-3 py-1.5 rounded-full mb-3 border border-pink-300/30 shadow-md">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-pink-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            <span class="text-pink-700 font-semibold text-xs tracking-wide">SCHEDULE MANAGEMENT</span>
                        </div>
                        <h1 class="text-3xl lg:text-4xl font-black text-gray-800 mb-2 tracking-tight">
                            Event Calendar & Schedule
                        </h1>
                        <p class="text-sm lg:text-base text-gray-700 font-medium">
                            Manage and organize events, tasks, and deadlines for teachers
                        </p>
                    </div>
                    <button id="addEventBtn" class="bg-gradient-to-r from-pink-500 to-cyan-500 text-white font-bold px-6 py-3 rounded-xl shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-300 flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        Add New Event
                    </button>
                </div>
            </div>
        </div>

        <!-- Main Content Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
            <!-- Calendar Section -->
            <div class="lg:col-span-2">
                <div class="bg-white/90 backdrop-blur-md rounded-2xl shadow-xl border border-pink-200/40 overflow-hidden">
                    <!-- Calendar Header -->
                    <div class="bg-gradient-to-r from-pink-400 via-rose-400 to-cyan-400 p-4">
                        <div class="flex items-center justify-between">
                            <button id="prevMonth" class="text-white hover:bg-white/20 p-2 rounded-lg transition">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                                </svg>
                            </button>
                            <div class="text-center">
                                <h2 id="currentMonth" class="text-xl font-black text-white"></h2>
                                <p id="currentYear" class="text-sm text-white/90"></p>
                            </div>
                            <button id="nextMonth" class="text-white hover:bg-white/20 p-2 rounded-lg transition">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                </svg>
                            </button>
                        </div>
                    </div>

                    <!-- Calendar Body -->
                    <div class="p-4">
                        <!-- Day Headers -->
                        <div class="grid grid-cols-7 gap-2 mb-2">
                            @foreach(['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'] as $day)
                            <div class="text-center text-xs font-bold text-gray-600 py-2">{{ $day }}</div>
                            @endforeach
                        </div>

                        <!-- Calendar Days -->
                        <div id="calendarDays" class="grid grid-cols-7 gap-2">
                            <!-- Calendar days will be generated by JavaScript -->
                        </div>
                    </div>
                </div>
            </div>

            <!-- Events List Section -->
            <div class="lg:col-span-1">
                <div class="bg-white/90 backdrop-blur-md rounded-2xl shadow-xl border border-cyan-200/40 overflow-hidden">
                    <div class="bg-gradient-to-r from-cyan-400 to-teal-400 p-4">
                        <h3 class="text-lg font-black text-white">Upcoming Events</h3>
                        <p class="text-xs text-white/90">{{ now()->format('M d, Y') }}</p>
                    </div>
                    <div class="p-4 max-h-[600px] overflow-y-auto">
                        <div id="eventsList" class="space-y-3">
                            <!-- Events will be loaded here -->
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Events Table -->
        <div class="bg-white/90 backdrop-blur-md rounded-2xl shadow-xl border border-pink-200/40 overflow-hidden">
            <div class="bg-gradient-to-r from-purple-400 via-pink-400 to-cyan-400 p-4">
                <h3 class="text-lg font-black text-white">All Events</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Title</th>
                            <th class="px-6 py-3 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Date</th>
                            <th class="px-6 py-3 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Time</th>
                            <th class="px-6 py-3 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Type</th>
                            <th class="px-6 py-3 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="eventsTableBody" class="bg-white divide-y divide-gray-200">
                        <!-- Events will be loaded here -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Add/Edit Event Modal -->
<div id="eventModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 hidden flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full transform transition-all">
        <div class="bg-gradient-to-r from-pink-400 to-cyan-400 p-6 rounded-t-2xl">
            <div class="flex items-center justify-between">
                <h3 id="modalTitle" class="text-xl font-black text-white">Add New Event</h3>
                <button id="closeModal" class="text-white hover:bg-white/20 p-2 rounded-lg transition">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
        <form id="eventForm" class="p-6 space-y-4">
            <input type="hidden" id="eventId">
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2">Event Title *</label>
                <input type="text" id="eventTitle" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-pink-500 focus:border-transparent">
            </div>
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2">Description</label>
                <textarea id="eventDescription" rows="3" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-pink-500 focus:border-transparent"></textarea>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Date *</label>
                    <input type="date" id="eventDate" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-pink-500 focus:border-transparent">
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Time</label>
                    <input type="time" id="eventTime" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-pink-500 focus:border-transparent">
                </div>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Type</label>
                    <select id="eventType" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-pink-500 focus:border-transparent">
                        <option value="task">Task</option>
                        <option value="reminder">Reminder</option>
                        <option value="deadline">Deadline</option>
                        <option value="meeting">Meeting</option>
                        <option value="lesson">Lesson</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Color</label>
                    <input type="color" id="eventColor" value="#F472B6" class="w-full h-10 border border-gray-300 rounded-lg cursor-pointer">
                </div>
            </div>
            <div class="flex gap-3 pt-4">
                <button type="submit" class="flex-1 bg-gradient-to-r from-pink-500 to-cyan-500 text-white font-bold py-3 rounded-lg hover:shadow-lg transition">
                    Save Event
                </button>
                <button type="button" id="cancelBtn" class="px-6 py-3 border border-gray-300 text-gray-700 font-semibold rounded-lg hover:bg-gray-50 transition">
                    Cancel
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
let currentDate = new Date();
let selectedDate = null;
let editingEventId = null;
const events = @json($events);

function renderCalendar() {
    const year = currentDate.getFullYear();
    const month = currentDate.getMonth();
    
    document.getElementById('currentMonth').textContent = currentDate.toLocaleDateString('en-US', { month: 'long' });
    document.getElementById('currentYear').textContent = year;
    
    const firstDay = new Date(year, month, 1);
    const lastDay = new Date(year, month + 1, 0);
    const daysInMonth = lastDay.getDate();
    const startingDayOfWeek = firstDay.getDay();
    
    const calendarDays = document.getElementById('calendarDays');
    calendarDays.innerHTML = '';
    
    // Empty cells for days before month starts
    for (let i = 0; i < startingDayOfWeek; i++) {
        const emptyCell = document.createElement('div');
        emptyCell.className = 'aspect-square';
        calendarDays.appendChild(emptyCell);
    }
    
    // Days of the month
    for (let day = 1; day <= daysInMonth; day++) {
        const dayCell = document.createElement('div');
        const dateStr = `${year}-${String(month + 1).padStart(2, '0')}-${String(day).padStart(2, '0')}`;
        const dayEvents = events.filter(e => e.event_date === dateStr);
        const isToday = new Date(year, month, day).toDateString() === new Date().toDateString();
        
        dayCell.className = `aspect-square p-1 rounded-lg border-2 transition-all cursor-pointer hover:shadow-md ${
            isToday ? 'bg-gradient-to-br from-pink-400 to-cyan-400 text-white border-pink-500' : 
            'bg-gray-50 border-gray-200 hover:border-pink-300'
        }`;
        
        dayCell.innerHTML = `
            <div class="text-xs font-bold ${isToday ? 'text-white' : 'text-gray-800'}">${day}</div>
            ${dayEvents.length > 0 ? `<div class="flex gap-0.5 mt-1 flex-wrap">${dayEvents.slice(0, 3).map(e => 
                `<div class="w-1.5 h-1.5 rounded-full" style="background-color: ${e.color}"></div>`
            ).join('')}${dayEvents.length > 3 ? '<span class="text-[8px] text-gray-500">+</span>' : ''}</div>` : ''}
        `;
        
        dayCell.addEventListener('click', () => {
            selectedDate = dateStr;
            document.getElementById('eventDate').value = dateStr;
            document.getElementById('addEventBtn').click();
        });
        
        calendarDays.appendChild(dayCell);
    }
}

function renderEventsList() {
    const upcomingEvents = events
        .filter(e => new Date(e.event_date) >= new Date().setHours(0,0,0,0))
        .sort((a, b) => new Date(a.event_date) - new Date(b.event_date))
        .slice(0, 10);
    
    const eventsList = document.getElementById('eventsList');
    if (upcomingEvents.length === 0) {
        eventsList.innerHTML = '<p class="text-gray-500 text-sm text-center py-8">No upcoming events</p>';
        return;
    }
    
    eventsList.innerHTML = upcomingEvents.map(event => `
        <div class="bg-gradient-to-r from-pink-50 to-cyan-50 rounded-lg p-3 border border-pink-200/40 hover:shadow-md transition">
            <div class="flex items-start justify-between mb-2">
                <div class="flex-1">
                    <div class="flex items-center gap-2 mb-1">
                        <div class="w-3 h-3 rounded-full" style="background-color: ${event.color}"></div>
                        <h4 class="font-bold text-sm text-gray-800">${event.title}</h4>
                    </div>
                    <p class="text-xs text-gray-600">${new Date(event.event_date).toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' })}</p>
                    ${event.event_time ? `<p class="text-xs text-gray-500">${event.event_time}</p>` : ''}
                </div>
            </div>
        </div>
    `).join('');
}

function renderEventsTable() {
    const tbody = document.getElementById('eventsTableBody');
    if (events.length === 0) {
        tbody.innerHTML = '<tr><td colspan="6" class="px-6 py-8 text-center text-gray-500">No events found</td></tr>';
        return;
    }
    
    tbody.innerHTML = events.map(event => `
        <tr class="hover:bg-pink-50/50 transition">
            <td class="px-6 py-4 whitespace-nowrap">
                <div class="flex items-center gap-2">
                    <div class="w-3 h-3 rounded-full" style="background-color: ${event.color}"></div>
                    <span class="text-sm font-semibold text-gray-900">${event.title}</span>
                </div>
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                ${new Date(event.event_date).toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' })}
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                ${event.event_time || '-'}
            </td>
            <td class="px-6 py-4 whitespace-nowrap">
                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-pink-100 text-pink-800">${event.event_type}</span>
            </td>
            <td class="px-6 py-4 whitespace-nowrap">
                <span class="px-2 py-1 text-xs font-semibold rounded-full ${event.is_active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800'}">
                    ${event.is_active ? 'Active' : 'Inactive'}
                </span>
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                <div class="flex items-center gap-2">
                    <button onclick="editEvent(${event.event_id})" class="text-pink-600 hover:text-pink-800">Edit</button>
                    <button onclick="deleteEvent(${event.event_id})" class="text-red-600 hover:text-red-800">Delete</button>
                </div>
            </td>
        </tr>
    `).join('');
}

function openModal(eventId = null) {
    editingEventId = eventId;
    const modal = document.getElementById('eventModal');
    const form = document.getElementById('eventForm');
    
    if (eventId) {
        const event = events.find(e => e.event_id == eventId);
        document.getElementById('modalTitle').textContent = 'Edit Event';
        document.getElementById('eventId').value = event.event_id;
        document.getElementById('eventTitle').value = event.title;
        document.getElementById('eventDescription').value = event.description || '';
        document.getElementById('eventDate').value = event.event_date;
        document.getElementById('eventTime').value = event.event_time || '';
        document.getElementById('eventType').value = event.event_type;
        document.getElementById('eventColor').value = event.color;
    } else {
        document.getElementById('modalTitle').textContent = 'Add New Event';
        form.reset();
        document.getElementById('eventId').value = '';
        if (selectedDate) {
            document.getElementById('eventDate').value = selectedDate;
        }
    }
    
    modal.classList.remove('hidden');
}

function closeModal() {
    document.getElementById('eventModal').classList.add('hidden');
    editingEventId = null;
    selectedDate = null;
}

async function saveEvent(formData) {
    const url = editingEventId 
        ? `/admin/schedule/${editingEventId}`
        : '/admin/schedule';
    const method = editingEventId ? 'PUT' : 'POST';
    
    try {
        const response = await fetch(url, {
            method: method,
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify(formData)
        });
        
        const data = await response.json();
        if (data.success) {
            location.reload();
        } else {
            alert('Error: ' + (data.message || 'Failed to save event'));
        }
    } catch (error) {
        alert('Error: ' + error.message);
    }
}

async function deleteEvent(eventId) {
    if (!confirm('Are you sure you want to delete this event?')) return;
    
    try {
        const response = await fetch(`/admin/schedule/${eventId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        });
        
        const data = await response.json();
        if (data.success) {
            location.reload();
        } else {
            alert('Error: ' + (data.message || 'Failed to delete event'));
        }
    } catch (error) {
        alert('Error: ' + error.message);
    }
}

function editEvent(eventId) {
    openModal(eventId);
}

// Event Listeners
document.getElementById('addEventBtn').addEventListener('click', () => openModal());
document.getElementById('closeModal').addEventListener('click', closeModal);
document.getElementById('cancelBtn').addEventListener('click', closeModal);
document.getElementById('prevMonth').addEventListener('click', () => {
    currentDate.setMonth(currentDate.getMonth() - 1);
    renderCalendar();
});
document.getElementById('nextMonth').addEventListener('click', () => {
    currentDate.setMonth(currentDate.getMonth() + 1);
    renderCalendar();
});
document.getElementById('eventForm').addEventListener('submit', (e) => {
    e.preventDefault();
    const formData = {
        title: document.getElementById('eventTitle').value,
        description: document.getElementById('eventDescription').value,
        event_date: document.getElementById('eventDate').value,
        event_time: document.getElementById('eventTime').value,
        event_type: document.getElementById('eventType').value,
        color: document.getElementById('eventColor').value,
    };
    saveEvent(formData);
});

// Initialize
renderCalendar();
renderEventsList();
renderEventsTable();
</script>
@endpush

@push('styles')
<style>
    @keyframes fade-in {
        from { opacity: 0; }
        to { opacity: 1; }
    }
    .animate-fade-in {
        animation: fade-in 0.6s ease-out;
    }
</style>
@endpush
@endsection
