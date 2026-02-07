@extends('layouts.app')

@section('content')
<div class="min-h-screen relative overflow-hidden bg-gradient-to-br from-pink-50 via-rose-50 to-cyan-50">
    <!-- Animated Background Elements -->
    <div class="absolute inset-0 overflow-hidden pointer-events-none">
        <div class="absolute top-20 left-10 w-72 h-72 bg-pink-300/20 rounded-full blur-3xl animate-pulse"></div>
        <div class="absolute bottom-20 right-10 w-96 h-96 bg-cyan-300/20 rounded-full blur-3xl animate-pulse" style="animation-delay: 1s;"></div>
        <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-80 h-80 bg-rose-300/10 rounded-full blur-3xl animate-pulse" style="animation-delay: 2s;"></div>
    </div>

    <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Creative Header with Floating Elements -->
        <div class="mb-10 animate-fade-in">
            <div class="relative bg-gradient-to-r from-pink-200/90 via-rose-200/90 to-cyan-200/90 rounded-3xl shadow-2xl overflow-hidden transform transition-all duration-500 hover:shadow-3xl border-2 border-pink-300/60 backdrop-blur-md p-8">
                <!-- Floating decorative circles -->
                <div class="absolute top-4 right-4 w-20 h-20 bg-white/30 rounded-full blur-xl"></div>
                <div class="absolute bottom-4 left-4 w-16 h-16 bg-cyan-300/40 rounded-full blur-lg"></div>
                
                <div class="relative flex flex-col lg:flex-row items-center justify-between gap-6">
                    <div class="flex-1 text-center lg:text-left">
                        <div class="inline-flex items-center gap-3 bg-white/70 backdrop-blur-md px-6 py-3 rounded-full mb-4 border-2 border-pink-300/50 shadow-lg transform hover:scale-105 transition">
                            <div class="w-3 h-3 bg-pink-500 rounded-full animate-pulse"></div>
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-pink-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            <span class="text-pink-700 font-black text-sm tracking-wider">STUDENT SCHEDULE</span>
                        </div>
                        <h1 class="text-5xl lg:text-6xl font-black text-gray-800 mb-3 tracking-tight bg-gradient-to-r from-pink-600 to-cyan-600 bg-clip-text text-transparent">
                            Student Schedule
                        </h1>
                        <p class="text-gray-700 font-bold text-lg flex items-center gap-2">
                            <span class="w-2 h-2 bg-pink-500 rounded-full animate-pulse"></span>
                            Manage and view all your schedule events and meetings
                        </p>
                    </div>
                    <button onclick="openCreateModal()" class="group relative bg-gradient-to-r from-pink-400 via-rose-400 to-cyan-400 hover:from-pink-500 hover:via-rose-500 hover:to-cyan-500 text-white font-black py-5 px-10 rounded-2xl shadow-2xl transform transition-all duration-300 hover:scale-110 hover:rotate-1 flex items-center gap-3 overflow-hidden">
                        <span class="absolute inset-0 bg-white/20 transform -skew-x-12 -translate-x-full group-hover:translate-x-full transition duration-1000"></span>
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 relative z-10" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        <span class="relative z-10 text-lg">Add New Event</span>
                    </button>
                </div>
            </div>
        </div>

        <!-- Full Calendar View -->
        <div class="max-w-7xl mx-auto">
            <div class="bg-white/90 backdrop-blur-md rounded-3xl shadow-2xl border-2 border-pink-200/50 overflow-hidden">
                <!-- Calendar Header -->
                <div class="bg-gradient-to-br from-pink-300/90 to-cyan-300/90 p-6 relative overflow-hidden">
                    <div class="absolute top-0 right-0 w-64 h-64 bg-white/20 rounded-full -mr-32 -mt-32"></div>
                    <div class="relative flex items-center justify-between">
                        <button id="prevMonth" class="text-gray-700 hover:bg-white/50 p-3 rounded-xl transition transform hover:scale-110 bg-white/60 backdrop-blur-sm">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                            </svg>
                        </button>
                        <div class="text-center">
                            <h2 id="currentMonth" class="text-3xl font-black text-gray-800"></h2>
                            <p id="currentYear" class="text-lg text-gray-700 font-bold"></p>
                        </div>
                        <button id="nextMonth" class="text-gray-700 hover:bg-white/50 p-3 rounded-xl transition transform hover:scale-110 bg-white/60 backdrop-blur-sm">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        </button>
                    </div>
                </div>
                
                <!-- Calendar Body -->
                <div class="p-6">
                    <div class="grid grid-cols-7 gap-3 mb-4">
                        @foreach(['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'] as $day)
                        <div class="text-center text-base font-black text-pink-600 py-2 bg-pink-50/50 rounded-lg">{{ $day }}</div>
                        @endforeach
                    </div>
                    <div id="calendarDays" class="grid grid-cols-7 gap-3">
                        <!-- Calendar days will be generated by JavaScript -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Creative Modal with Animation -->
<div id="eventModal" class="fixed inset-0 bg-black/60 backdrop-blur-md hidden z-50 flex items-center justify-center p-4 animate-modal-fade">
    <div class="bg-white rounded-3xl shadow-2xl max-w-md w-full p-8 border-2 border-pink-200/50 transform transition-all animate-modal-slide relative overflow-hidden">
        <!-- Decorative background elements -->
        <div class="absolute top-0 right-0 w-40 h-40 bg-pink-200/20 rounded-full blur-2xl -mr-20 -mt-20"></div>
        <div class="absolute bottom-0 left-0 w-32 h-32 bg-cyan-200/20 rounded-full blur-2xl -ml-16 -mb-16"></div>
        
        <div class="relative">
            <div class="flex justify-between items-center mb-6">
                <h3 id="modalTitle" class="text-3xl font-black bg-gradient-to-r from-pink-600 to-cyan-600 bg-clip-text text-transparent">Add New Event</h3>
                <button onclick="closeModal()" class="text-gray-500 hover:text-pink-600 transition transform hover:scale-110 hover:rotate-90">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            <form id="eventForm" onsubmit="saveEvent(event)">
                <input type="hidden" id="eventId" name="event_id">
                <div class="space-y-5">
                    <div>
                        <label class="block text-sm font-black text-gray-700 mb-2 flex items-center gap-2">
                            <span class="w-2 h-2 bg-pink-500 rounded-full"></span>
                            Title *
                        </label>
                        <input type="text" id="eventTitle" name="title" required class="w-full px-4 py-3 border-2 border-pink-200 rounded-xl focus:ring-2 focus:ring-pink-400 focus:border-pink-400 transition transform focus:scale-105">
                    </div>
                    <div>
                        <label class="block text-sm font-black text-gray-700 mb-2 flex items-center gap-2">
                            <span class="w-2 h-2 bg-cyan-500 rounded-full"></span>
                            Description
                        </label>
                        <textarea id="eventDescription" name="description" rows="3" class="w-full px-4 py-3 border-2 border-pink-200 rounded-xl focus:ring-2 focus:ring-pink-400 focus:border-pink-400 transition"></textarea>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-black text-gray-700 mb-2">Date *</label>
                            <input type="date" id="eventDate" name="event_date" required class="w-full px-4 py-3 border-2 border-pink-200 rounded-xl focus:ring-2 focus:ring-pink-400 focus:border-pink-400 transition">
                        </div>
                        <div>
                            <label class="block text-sm font-black text-gray-700 mb-2">Time</label>
                            <input type="time" id="eventTime" name="event_time" class="w-full px-4 py-3 border-2 border-pink-200 rounded-xl focus:ring-2 focus:ring-pink-400 focus:border-pink-400 transition">
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-black text-gray-700 mb-2">Type</label>
                            <select id="eventType" name="event_type" class="w-full px-4 py-3 border-2 border-pink-200 rounded-xl focus:ring-2 focus:ring-pink-400 focus:border-pink-400 transition">
                                <option value="event">Event</option>
                                <option value="class">Class</option>
                                <option value="meeting">Meeting</option>
                                <option value="deadline">Deadline</option>
                                <option value="reminder">Reminder</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-black text-gray-700 mb-2">Color</label>
                            <input type="color" id="eventColor" name="color" value="#EC4899" class="w-full h-12 border-2 border-pink-200 rounded-xl focus:ring-2 focus:ring-pink-400 focus:border-pink-400 transition cursor-pointer">
                        </div>
                    </div>
                </div>
                <div class="mt-8 flex gap-4">
                    <button type="submit" class="flex-1 bg-gradient-to-r from-pink-400 via-rose-400 to-cyan-400 hover:from-pink-500 hover:via-rose-500 hover:to-cyan-500 text-white font-black py-4 px-6 rounded-xl shadow-lg transition transform hover:scale-105 hover:shadow-2xl">
                        Save Event
                    </button>
                    <button type="button" onclick="closeModal()" class="flex-1 bg-gray-200 hover:bg-gray-300 text-gray-800 font-black py-4 px-6 rounded-xl transition transform hover:scale-105">
                        Cancel
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
let currentDate = new Date();
let events = [];
let editingEventId = null;

try {
    events = @json($events);
    if (!Array.isArray(events)) {
        console.error('Events is not an array:', events);
        events = [];
    }
} catch (error) {
    console.error('Error parsing events JSON:', error);
    events = [];
}

function updateStats() {
    // Stats removed - calendar only view
}

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
    
    for (let i = 0; i < startingDayOfWeek; i++) {
        const emptyCell = document.createElement('div');
        emptyCell.className = 'min-h-[140px] bg-gray-50/30 rounded-xl';
        calendarDays.appendChild(emptyCell);
    }
    
    for (let day = 1; day <= daysInMonth; day++) {
        const dayCell = document.createElement('div');
        const dateStr = `${year}-${String(month + 1).padStart(2, '0')}-${String(day).padStart(2, '0')}`;
        const dayEvents = events.filter(e => e.event_date === dateStr);
        const isToday = new Date(year, month, day).toDateString() === new Date().toDateString();
        
        let backgroundColor = '';
        let textColor = 'text-gray-800';
        let borderColor = 'border-transparent';
        
        if (dayEvents.length > 0) {
            backgroundColor = dayEvents[0].color;
            textColor = 'text-white';
            borderColor = 'border-pink-400';
        } else if (isToday) {
            backgroundColor = 'bg-gradient-to-br from-pink-300 to-cyan-300';
            textColor = 'text-white';
            borderColor = 'border-pink-500';
        } else {
            backgroundColor = 'bg-gray-50';
            textColor = 'text-gray-800';
            borderColor = 'border-transparent';
        }
        
        dayCell.className = `min-h-[140px] p-2.5 rounded-xl border-2 transition-all hover:shadow-xl hover:scale-[1.02] flex flex-col ${borderColor} ${textColor} text-sm font-bold`;
        
        if (dayEvents.length > 0 && backgroundColor.startsWith('#')) {
            dayCell.style.backgroundColor = backgroundColor;
        } else if (backgroundColor) {
            dayCell.className += ` ${backgroundColor}`;
        }
        
        // Day number
        const dayNumber = document.createElement('div');
        dayNumber.className = `text-lg font-black mb-2 ${dayEvents.length > 0 ? 'text-white drop-shadow-md' : 'text-gray-800'}`;
        dayNumber.textContent = day;
        dayCell.appendChild(dayNumber);
        
        // Events list
        if (dayEvents.length > 0) {
            const eventsContainer = document.createElement('div');
            eventsContainer.className = 'flex-1 space-y-2 overflow-y-auto max-h-[100px]';
            
            dayEvents.forEach((event, idx) => {
                const eventItem = document.createElement('div');
                const isMeeting = event.source === 'meeting';
                const bgColor = isMeeting ? 'bg-purple-600/95' : 'bg-white/95';
                const textColor = isMeeting ? 'text-white' : 'text-gray-800';
                
                eventItem.className = `px-2 py-2 rounded-lg ${bgColor} ${textColor} backdrop-blur-sm hover:opacity-90 transition cursor-pointer border border-white/20 shadow-sm`;
                eventItem.title = event.title + (event.event_time ? ' at ' + event.event_time : '');
                
                // Show icon
                const icon = isMeeting ? '📹' : 
                            event.event_type === 'assignment' ? '📝' :
                            event.event_type === 'quiz' ? '📋' :
                            event.event_type === 'deadline' ? '⏰' :
                            event.event_type === 'reminder' ? '🔔' : '📅';
                
                // Truncate title if too long
                const maxTitleLength = 18;
                const eventTitle = event.title.length > maxTitleLength ? event.title.substring(0, maxTitleLength) + '...' : event.title;
                
                eventItem.innerHTML = `
                    <div class="flex items-start gap-1.5 mb-1">
                        <span class="text-sm flex-shrink-0">${icon}</span>
                        <div class="flex-1 min-w-0">
                            ${event.event_time ? `
                                <div class="text-[10px] font-bold ${isMeeting ? 'text-purple-100' : 'text-gray-600'} mb-0.5">
                                    ${event.event_time}
                                </div>
                            ` : ''}
                            <div class="font-bold text-xs leading-tight ${isMeeting ? 'text-white' : 'text-gray-800'}">
                                ${eventTitle}
                            </div>
                        </div>
                        ${!isMeeting ? `
                            <button onclick="event.stopPropagation(); deleteEvent(${event.event_id})" 
                                    class="ml-1 p-1 rounded hover:bg-red-500/20 transition flex-shrink-0" 
                                    title="Delete event">
                                <svg class="w-3 h-3 ${textColor === 'text-white' ? 'text-white' : 'text-red-600'} hover:text-red-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                            </button>
                        ` : ''}
                    </div>
                `;
                
                eventItem.addEventListener('click', (e) => {
                    // Don't trigger edit if delete button was clicked
                    if (e.target.closest('button')) {
                        return;
                    }
                    e.stopPropagation();
                    if (isMeeting) {
                        window.location.href = '/meetings';
                    } else {
                        editEvent(event.event_id);
                    }
                });
                
                eventsContainer.appendChild(eventItem);
            });
            
            dayCell.appendChild(eventsContainer);
        } else {
            dayCell.className += ' items-center justify-center';
        }
        
        calendarDays.appendChild(dayCell);
    }
}

let currentFilter = 'all';

function filterEvents(filter) {
    currentFilter = filter;
    
    // Update active button
    document.querySelectorAll('.filter-btn').forEach(btn => {
        btn.classList.remove('active', 'bg-gradient-to-r', 'from-pink-400', 'to-cyan-400', 'text-white', 'shadow-lg');
        btn.classList.add('bg-white', 'text-gray-700', 'border-2', 'border-pink-200');
    });
    
    const activeBtn = document.getElementById(`filter-${filter}`);
    if (activeBtn) {
        activeBtn.classList.add('active', 'bg-gradient-to-r', 'from-pink-400', 'to-cyan-400', 'text-white', 'shadow-lg');
        activeBtn.classList.remove('bg-white', 'text-gray-700', 'border-2', 'border-pink-200');
    }
    
    renderEvents();
}

function getEventTypeIcon(eventType) {
    const icons = {
        'meeting': `<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" /></svg>`,
        'assignment': `<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>`,
        'quiz': `<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" /></svg>`,
        'deadline': `<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>`,
        'reminder': `<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" /></svg>`,
        'class': `<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" /></svg>`,
        'event': `<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>`
    };
    return icons[eventType] || icons['event'];
}

function getFilteredEvents() {
    const now = new Date();
    now.setHours(0, 0, 0, 0);
    const today = new Date(now);
    const weekFromNow = new Date(now);
    weekFromNow.setDate(weekFromNow.getDate() + 7);
    const monthFromNow = new Date(now);
    monthFromNow.setMonth(monthFromNow.getMonth() + 1);
    
    let filtered = [...events];
    
    switch(currentFilter) {
        case 'today':
            filtered = events.filter(e => {
                const eventDate = new Date(e.event_date);
                eventDate.setHours(0, 0, 0, 0);
                return eventDate.getTime() === today.getTime();
            });
            break;
        case 'week':
            filtered = events.filter(e => {
                const eventDate = new Date(e.event_date);
                eventDate.setHours(0, 0, 0, 0);
                return eventDate >= today && eventDate <= weekFromNow;
            });
            break;
        case 'month':
            filtered = events.filter(e => {
                const eventDate = new Date(e.event_date);
                eventDate.setHours(0, 0, 0, 0);
                return eventDate >= today && eventDate <= monthFromNow;
            });
            break;
        case 'upcoming':
            filtered = events.filter(e => {
                const eventDate = new Date(e.event_date);
                eventDate.setHours(0, 0, 0, 0);
                return eventDate >= today;
            });
            break;
        case 'past':
            filtered = events.filter(e => {
                const eventDate = new Date(e.event_date);
                eventDate.setHours(0, 0, 0, 0);
                return eventDate < today;
            });
            break;
    }
    
    return filtered.sort((a, b) => {
        const dateA = new Date(a.event_date + (a.event_time ? ' ' + a.event_time : ''));
        const dateB = new Date(b.event_date + (b.event_time ? ' ' + b.event_time : ''));
        return dateA - dateB;
    });
}

function groupEventsByDate(filteredEvents) {
    const groups = {};
    const now = new Date();
    now.setHours(0, 0, 0, 0);
    
    filteredEvents.forEach(event => {
        const eventDate = new Date(event.event_date);
        eventDate.setHours(0, 0, 0, 0);
        const dateKey = eventDate.toISOString().split('T')[0];
        
        if (!groups[dateKey]) {
            const diffTime = eventDate - now;
            const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
            
            let label = eventDate.toLocaleDateString('en-US', { 
                weekday: 'long', 
                month: 'long', 
                day: 'numeric', 
                year: 'numeric' 
            });
            
            if (diffDays === 0) {
                label = 'Today - ' + label;
            } else if (diffDays === 1) {
                label = 'Tomorrow - ' + label;
            } else if (diffDays === -1) {
                label = 'Yesterday - ' + label;
            } else if (diffDays > 0 && diffDays <= 7) {
                label = `In ${diffDays} days - ` + label;
            } else if (diffDays < 0) {
                label = `${Math.abs(diffDays)} days ago - ` + label;
            }
            
            groups[dateKey] = {
                label: label,
                date: eventDate,
                events: [],
                isToday: diffDays === 0,
                isPast: diffDays < 0
            };
        }
        
        groups[dateKey].events.push(event);
    });
    
    // Sort groups by date
    return Object.keys(groups).sort().map(key => groups[key]);
}

function renderEvents() {
    const container = document.getElementById('eventsContainer');
    const filteredEvents = getFilteredEvents();
    
    if (filteredEvents.length === 0) {
        container.innerHTML = `
            <div class="bg-white/80 backdrop-blur-md rounded-3xl p-16 text-center border-2 border-pink-200/50 shadow-lg">
                <div class="text-6xl mb-4">📅</div>
                <p class="text-gray-500 font-bold text-xl mb-2">No events found</p>
                <p class="text-gray-400 text-sm">Try selecting a different filter or create a new event</p>
            </div>
        `;
        return;
    }
    
    const groupedEvents = groupEventsByDate(filteredEvents);
    
    container.innerHTML = groupedEvents.map((group, groupIndex) => {
        const sortedGroupEvents = group.events.sort((a, b) => {
            const timeA = a.event_time || '00:00';
            const timeB = b.event_time || '00:00';
            return timeA.localeCompare(timeB);
        });
        
        return `
            <div class="space-y-4">
                <div class="flex items-center gap-4 mb-4" data-date="${group.date.toISOString().split('T')[0]}">
                    <div class="h-1 w-12 ${group.isToday ? 'bg-gradient-to-r from-pink-500 to-cyan-500' : group.isPast ? 'bg-gray-400' : 'bg-gradient-to-r from-pink-300 to-cyan-300'} rounded-full"></div>
                    <h3 class="text-2xl font-black ${group.isToday ? 'text-pink-600' : 'text-gray-700'}">${group.label}</h3>
                    <div class="flex-1 h-0.5 ${group.isToday ? 'bg-gradient-to-r from-pink-200 to-cyan-200' : 'bg-gray-200'} rounded-full"></div>
                    <span class="px-4 py-1.5 text-sm font-black rounded-full ${group.isToday ? 'bg-pink-100 text-pink-700' : 'bg-gray-100 text-gray-600'}">${group.events.length} ${group.events.length === 1 ? 'event' : 'events'}</span>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    ${sortedGroupEvents.map((event, eventIndex) => {
                        const eventDate = new Date(event.event_date);
                        eventDate.setHours(0, 0, 0, 0);
                        const today = new Date();
                        today.setHours(0, 0, 0, 0);
                        const isMeeting = event.source === 'meeting';
                        const isPast = eventDate < today;
                        const isToday = eventDate.getTime() === today.getTime();
                        
                        return `
                            <div class="group relative bg-white/90 backdrop-blur-md rounded-2xl p-5 border-2 ${isPast ? 'border-gray-200 opacity-75' : isToday ? (isMeeting ? 'border-purple-300 hover:border-purple-400' : 'border-pink-300 hover:border-pink-400') : (isMeeting ? 'border-purple-200 hover:border-purple-400' : 'border-pink-200 hover:border-pink-400')} hover:shadow-xl transition-all ${isMeeting ? '' : 'cursor-pointer'}" ${isMeeting ? '' : `onclick="editEvent(${event.event_id})"`}>
                                <div class="absolute top-0 right-0 w-24 h-24 ${isMeeting ? 'bg-purple-200/20' : 'bg-pink-200/20'} rounded-full blur-xl -mr-12 -mt-12"></div>
                                <div class="relative">
                                    <div class="flex items-start gap-3 mb-3">
                                        <div class="flex-shrink-0 w-12 h-12 rounded-xl flex items-center justify-center text-white shadow-lg ${isMeeting ? 'bg-gradient-to-br from-purple-500 to-purple-600' : ''}" style="${!isMeeting ? `background: linear-gradient(135deg, ${event.color}, ${event.color}dd);` : ''}">
                                            ${getEventTypeIcon(event.event_type || 'event')}
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <div class="flex items-start justify-between gap-2 mb-1">
                                                <h4 class="font-black text-lg text-gray-800 group-hover:text-pink-600 transition truncate">${event.title}</h4>
                                                ${isMeeting ? '<span class="px-2 py-1 text-xs font-black rounded-lg bg-purple-100 text-purple-700 flex-shrink-0">Meeting</span>' : ''}
                                            </div>
                                            <div class="flex items-center gap-3 text-xs text-gray-600 font-bold flex-wrap">
                                                ${event.event_time ? `
                                                    <span class="flex items-center gap-1 ${isPast ? 'text-gray-500' : 'text-cyan-600'}">
                                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                        </svg>
                                                        ${event.event_time}
                                                    </span>
                                                ` : ''}
                                                <span class="px-2 py-0.5 rounded-md ${isMeeting ? 'bg-purple-50 text-purple-600' : 'bg-pink-50 text-pink-600'} text-xs font-black">
                                                    ${event.event_type || 'event'}
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    ${event.description ? `<p class="text-sm text-gray-600 line-clamp-2 mb-3">${event.description}</p>` : ''}
                                    <div class="flex items-center justify-between pt-3 border-t border-gray-100">
                                        <div class="flex items-center gap-2">
                                            ${isPast ? '<span class="text-xs text-gray-500 font-bold">Past</span>' : isToday ? '<span class="text-xs text-pink-600 font-bold">Today</span>' : '<span class="text-xs text-green-600 font-bold">Upcoming</span>'}
                                        </div>
                                        <div class="flex items-center gap-2">
                                            ${event.source !== 'meeting' ? `
                                                <button onclick="event.stopPropagation(); editEvent(${event.event_id})" class="px-3 py-1.5 bg-pink-100 hover:bg-pink-200 text-pink-700 font-bold text-xs rounded-lg transition transform hover:scale-110">Edit</button>
                                                <button onclick="event.stopPropagation(); deleteEvent(${event.event_id})" class="px-3 py-1.5 bg-red-100 hover:bg-red-200 text-red-700 font-bold text-xs rounded-lg transition transform hover:scale-110">Delete</button>
                                            ` : `
                                                <a href="/meetings" class="px-3 py-1.5 bg-purple-100 hover:bg-purple-200 text-purple-700 font-bold text-xs rounded-lg transition transform hover:scale-110">View Meeting</a>
                                            `}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        `;
                    }).join('')}
                </div>
            </div>
        `;
    }).join('');
}

function openCreateModal() {
    editingEventId = null;
    document.getElementById('modalTitle').textContent = 'Add New Event';
    document.getElementById('eventForm').reset();
    document.getElementById('eventId').value = '';
    document.getElementById('eventColor').value = '#EC4899';
    const modal = document.getElementById('eventModal');
    modal.classList.remove('hidden');
    setTimeout(() => modal.classList.add('animate-modal-slide'), 10);
}

function closeModal() {
    const modal = document.getElementById('eventModal');
    modal.classList.add('hidden');
    editingEventId = null;
}

function editEvent(eventId) {
    if (!eventId || eventId === 'null') return;
    
    // Handle meeting events (format: 'meeting_123')
    if (typeof eventId === 'string' && eventId.startsWith('meeting_')) {
        alert('Meetings can only be edited from the Meetings page.');
        return;
    }
    
    const event = events.find(e => e.event_id == eventId);
    if (!event) {
        console.error('Event not found:', eventId);
        return;
    }
    
    editingEventId = eventId;
    document.getElementById('modalTitle').textContent = 'Edit Event';
    document.getElementById('eventId').value = event.event_id;
    document.getElementById('eventTitle').value = event.title;
    document.getElementById('eventDescription').value = event.description || '';
    document.getElementById('eventDate').value = event.event_date;
    document.getElementById('eventTime').value = event.event_time || '';
    document.getElementById('eventType').value = event.event_type || 'event';
    document.getElementById('eventColor').value = event.color || '#EC4899';
    const modal = document.getElementById('eventModal');
    modal.classList.remove('hidden');
    setTimeout(() => modal.classList.add('animate-modal-slide'), 10);
}

async function saveEvent(e) {
    e.preventDefault();
    
    const formData = new FormData(e.target);
    const data = {
        title: formData.get('title'),
        description: formData.get('description'),
        event_date: formData.get('event_date'),
        event_time: formData.get('event_time'),
        event_type: formData.get('event_type'),
        color: formData.get('color'),
    };
    
    const url = editingEventId 
        ? `/teacher/personal-schedule/${editingEventId}`
        : '/teacher/personal-schedule';
    const method = editingEventId ? 'PUT' : 'POST';
    
    try {
        const response = await fetch(url, {
            method: method,
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify(data)
        });
        
        const result = await response.json();
        
        if (result.success) {
            if (editingEventId) {
                const index = events.findIndex(e => e.event_id === editingEventId);
                if (index !== -1) {
                    events[index] = result.event;
                }
            } else {
                events.push(result.event);
            }
            
            renderCalendar();
            updateStats();
            closeModal();
            
            alert(result.message);
        } else {
            alert('Error: ' + result.message);
        }
    } catch (error) {
        console.error('Error:', error);
        alert('An error occurred. Please try again.');
    }
}

async function deleteEvent(eventId) {
    if (!confirm('Are you sure you want to delete this event?')) return;
    
    try {
        const response = await fetch(`/teacher/personal-schedule/${eventId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        });
        
        const result = await response.json();
        
        if (result.success) {
            events = events.filter(e => e.event_id !== eventId);
            renderCalendar();
            updateStats();
            alert(result.message);
        } else {
            alert('Error: ' + result.message);
        }
    } catch (error) {
        console.error('Error:', error);
        alert('An error occurred. Please try again.');
    }
}

document.getElementById('prevMonth').addEventListener('click', () => {
    currentDate.setMonth(currentDate.getMonth() - 1);
    renderCalendar();
});
document.getElementById('nextMonth').addEventListener('click', () => {
    currentDate.setMonth(currentDate.getMonth() + 1);
    renderCalendar();
});

renderCalendar();
updateStats();
</script>
@endpush

@push('styles')
<style>
    @keyframes fade-in {
        from { 
            opacity: 0; 
            transform: translateY(20px);
        }
        to { 
            opacity: 1; 
            transform: translateY(0);
        }
    }
    
    @keyframes modal-fade {
        from { opacity: 0; }
        to { opacity: 1; }
    }
    
    @keyframes modal-slide {
        from { 
            opacity: 0;
            transform: scale(0.9) translateY(-20px);
        }
        to { 
            opacity: 1;
            transform: scale(1) translateY(0);
        }
    }
    
    .animate-fade-in {
        animation: fade-in 0.6s ease-out;
    }
    
    .animate-modal-fade {
        animation: modal-fade 0.3s ease-out;
    }
    
    .animate-modal-slide {
        animation: modal-slide 0.4s ease-out;
    }
    
    .line-clamp-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    
    ::-webkit-scrollbar {
        width: 8px;
    }
    ::-webkit-scrollbar-track {
        background: #fdf2f8;
        border-radius: 10px;
    }
    ::-webkit-scrollbar-thumb {
        background: linear-gradient(180deg, #f472b6, #06b6d4);
        border-radius: 10px;
    }
    ::-webkit-scrollbar-thumb:hover {
        background: linear-gradient(180deg, #ec4899, #0891b2);
    }
</style>
@endpush
@endsection
