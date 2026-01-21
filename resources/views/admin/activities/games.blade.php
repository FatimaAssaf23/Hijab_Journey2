@extends('layouts.admin')

@section('content')
<div class="min-h-screen">
    <!-- Header -->
    <div class="bg-gradient-to-r from-pink-500 to-pink-600 shadow-xl">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-4xl font-extrabold text-white mb-2">Games Overview</h1>
                    <p class="text-pink-100">Aggregated statistics and monitoring data</p>
                </div>
                <a href="{{ route('admin.dashboard') }}" class="bg-white/20 hover:bg-white/30 text-white px-4 py-2 rounded-lg transition-all">
                    ← Back to Dashboard
                </a>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <div class="bg-white border border-gray-200 rounded-xl p-6 shadow-lg">
                <p class="text-gray-600 text-sm font-medium mb-2">Total Games</p>
                <p class="text-3xl font-bold text-gray-800">{{ $totalGames }}</p>
            </div>
            <div class="bg-white border border-gray-200 rounded-xl p-6 shadow-lg">
                <p class="text-gray-600 text-sm font-medium mb-2">Total Progresses</p>
                <p class="text-3xl font-bold text-gray-800">{{ $totalProgresses }}</p>
            </div>
            <div class="bg-white border border-gray-200 rounded-xl p-6 shadow-lg">
                <p class="text-gray-600 text-sm font-medium mb-2">Completed</p>
                <p class="text-3xl font-bold text-green-600">{{ $completedProgresses }}</p>
            </div>
            <div class="bg-white border border-gray-200 rounded-xl p-6 shadow-lg">
                <p class="text-gray-600 text-sm font-medium mb-2">Completion Rate</p>
                <p class="text-3xl font-bold text-pink-600">{{ $completionRate }}%</p>
            </div>
        </div>

        <!-- Average Score -->
        <div class="bg-white border border-gray-200 rounded-xl p-6 shadow-lg mb-8">
            <p class="text-gray-600 text-sm font-medium mb-2">Average Score</p>
            <p class="text-4xl font-bold text-pink-600">{{ number_format($averageScore, 1) }}</p>
        </div>

        <!-- Game Type Breakdown -->
        <div class="bg-white border border-gray-200 rounded-xl p-6 shadow-lg mb-8">
            <h2 class="text-2xl font-bold text-gray-800 mb-6">Games by Type</h2>
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4">
                @foreach($gameTypeCounts as $type => $count)
                    @if($count > 0)
                    <div class="text-center p-4 bg-gray-50 rounded-lg">
                        <p class="text-sm text-gray-600 mb-1">{{ ucfirst(str_replace('_', ' ', $type)) }}</p>
                        <p class="text-2xl font-bold text-gray-800">{{ $count }}</p>
                    </div>
                    @endif
                @endforeach
            </div>
        </div>

        <!-- Class Statistics -->
        <div class="bg-white border border-gray-200 rounded-xl p-6 shadow-lg">
            <h2 class="text-2xl font-bold text-gray-800 mb-6">Statistics by Class</h2>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Class</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Teacher</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Games</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Progresses</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Completed</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Avg Score</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Completion Rate</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($classStats as $stat)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $stat['class_name'] }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $stat['teacher'] }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $stat['total_games'] }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $stat['total_progresses'] }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-green-600 font-medium">{{ $stat['completed'] }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $stat['average_score'] }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $stat['completion_rate'] }}%</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="px-6 py-4 text-center text-sm text-gray-500">No class statistics available</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
