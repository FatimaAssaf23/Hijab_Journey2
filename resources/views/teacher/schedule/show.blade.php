@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-pink-50 via-white to-pink-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="bg-gradient-to-r from-[#FC8EAC] via-[#EC769A] to-[#6EC6C5] shadow-xl rounded-2xl p-6 mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-4xl font-extrabold text-white mb-2">📅 My Schedule</h1>
                    <p class="text-pink-100">
                        @if($schedule->studentClass)
                            {{ $schedule->studentClass->class_name }}
                        @endif
                    </p>
                </div>
                <a href="{{ route('teacher.lessons.manage') }}" class="bg-white/20 hover:bg-white/30 text-white px-6 py-3 rounded-xl font-bold transition">
                    ← Back to Lessons
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
                    <p class="text-gray-600">Started: {{ $schedule->started_at ? $schedule->started_at->format('M d, Y') : 'N/A' }}</p>
                </div>
            </div>
        </div>

        <!-- Schedule Events -->
        <div class="space-y-6">
            @php
                $eventsByMonth = $schedule->scheduledEvents->groupBy(function($event) {
                    return \Carbon\Carbon::parse($event->release_date)->format('Y-m');
                });
            @endphp

            @forelse($eventsByMonth as $month => $events)
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
                            <div class="flex items-center gap-4 p-4 rounded-lg border-2 {{ $typeColors[$event->event_type] ?? 'bg-gray-100 border-gray-300' }} hover:shadow-md transition">
                                <div class="text-3xl">{{ $typeIcons[$event->event_type] ?? '📌' }}</div>
                                <div class="flex-1">
                                    <div class="font-bold text-gray-800">{{ $event->event_title }}</div>
                                    <div class="text-sm text-gray-600">
                                        @if($event->level)
                                            Level: {{ $event->level->level_name }}
                                        @endif
                                    </div>
                                    @if($event->admin_notes)
                                        <div class="text-xs text-purple-600 mt-1">Note: {{ $event->admin_notes }}</div>
                                    @endif
                                </div>
                                <div class="text-right">
                                    <div class="font-semibold text-gray-800">{{ \Carbon\Carbon::parse($event->release_date)->format('M d, Y') }}</div>
                                    <div class="text-sm">
                                        <span class="px-2 py-1 rounded {{ $event->status === 'released' ? 'bg-green-500 text-white' : 'bg-gray-200' }}">
                                            {{ ucfirst($event->status) }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @empty
                <div class="bg-white rounded-2xl shadow-lg p-8 text-center">
                    <p class="text-gray-600 text-lg">No scheduled events yet.</p>
                </div>
            @endforelse
        </div>
    </div>
</div>
@endsection
