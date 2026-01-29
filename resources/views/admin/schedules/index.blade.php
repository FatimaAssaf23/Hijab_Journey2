@extends('layouts.admin')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="bg-gradient-to-r from-[#FC8EAC] via-[#EC769A] to-[#6EC6C5] shadow-xl rounded-2xl p-6 mb-8">
            <h1 class="text-4xl font-extrabold text-white mb-2">🎓 Active Course Schedules</h1>
            <p class="text-pink-100">Manage all teacher schedules</p>
        </div>

        <!-- Filters -->
        <div class="bg-white rounded-2xl shadow-lg p-6 mb-6">
            <form method="GET" action="{{ route('admin.schedules.index') }}" class="flex gap-4 items-end">
                <div class="flex-1">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Search Teacher</label>
                    <input type="text" name="search" value="{{ request('search') }}" 
                           placeholder="Search by teacher name..." 
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#EC769A] focus:border-transparent">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                    <select name="status" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#EC769A]">
                        <option value="">All</option>
                        <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                        <option value="paused" {{ request('status') === 'paused' ? 'selected' : '' }}>Paused</option>
                        <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Completed</option>
                    </select>
                </div>
                <button type="submit" class="bg-[#6EC6C5] hover:bg-[#197D8C] text-white px-6 py-3 rounded-lg font-bold transition">
                    Filter
                </button>
            </form>
        </div>

        <!-- Schedules List -->
        <div class="space-y-4">
            @forelse($schedules as $schedule)
                <div class="bg-white rounded-2xl shadow-lg p-6 hover:shadow-xl transition">
                    <div class="flex items-center justify-between">
                        <div class="flex-1">
                            <div class="flex items-center gap-3 mb-2">
                                <h3 class="text-2xl font-bold text-gray-800">
                                    {{ $schedule->teacher->first_name }} {{ $schedule->teacher->last_name }}
                                </h3>
                                <span class="px-3 py-1 rounded-full text-sm font-semibold
                                    {{ $schedule->status === 'active' ? 'bg-green-100 text-green-800' : 
                                       ($schedule->status === 'paused' ? 'bg-yellow-100 text-yellow-800' : 'bg-gray-100 text-gray-800') }}">
                                    {{ ucfirst($schedule->status) }}
                                </span>
                            </div>
                            <div class="text-gray-600 space-y-1">
                                @if($schedule->studentClass)
                                    <p><strong>Class:</strong> {{ $schedule->studentClass->class_name }}</p>
                                @else
                                    <p><strong>Scope:</strong> All Classes</p>
                                @endif
                                <p><strong>Started:</strong> {{ $schedule->started_at->format('M d, Y') }}</p>
                                <p><strong>Total Events:</strong> {{ $schedule->scheduledEvents->count() }}</p>
                                <p><strong>Pending:</strong> {{ $schedule->scheduledEvents->where('status', 'pending')->count() }}</p>
                            </div>
                        </div>
                        <div class="flex gap-3">
                            <a href="{{ route('admin.schedules.show', $schedule->schedule_id) }}" 
                               class="bg-[#6EC6C5] hover:bg-[#197D8C] text-white px-6 py-3 rounded-xl font-bold transition">
                                View/Edit Schedule
                            </a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="bg-white rounded-2xl shadow-lg p-12 text-center">
                    <div class="text-6xl mb-4">📅</div>
                    <h2 class="text-2xl font-bold text-gray-800 mb-2">No Schedules Found</h2>
                    <p class="text-gray-600">No schedules match your filters.</p>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if($schedules->hasPages())
            <div class="mt-6">
                {{ $schedules->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
