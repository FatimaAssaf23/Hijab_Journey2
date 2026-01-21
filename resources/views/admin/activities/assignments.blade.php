@extends('layouts.admin')

@section('content')
<div class="min-h-screen">
    <!-- Header -->
    <div class="bg-gradient-to-r from-purple-500 to-purple-600 shadow-xl">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-4xl font-extrabold text-white mb-2">Assignments Overview</h1>
                    <p class="text-purple-100">Aggregated statistics and monitoring data</p>
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
                <p class="text-gray-600 text-sm font-medium mb-2">Total Assignments</p>
                <p class="text-3xl font-bold text-gray-800">{{ $totalAssignments }}</p>
            </div>
            <div class="bg-white border border-gray-200 rounded-xl p-6 shadow-lg">
                <p class="text-gray-600 text-sm font-medium mb-2">Total Submissions</p>
                <p class="text-3xl font-bold text-gray-800">{{ $totalSubmissions }}</p>
            </div>
            <div class="bg-white border border-gray-200 rounded-xl p-6 shadow-lg">
                <p class="text-gray-600 text-sm font-medium mb-2">Graded</p>
                <p class="text-3xl font-bold text-green-600">{{ $totalGraded }}</p>
            </div>
            <div class="bg-white border border-gray-200 rounded-xl p-6 shadow-lg">
                <p class="text-gray-600 text-sm font-medium mb-2">Pending Grading</p>
                <p class="text-3xl font-bold text-orange-600">{{ $totalPending }}</p>
            </div>
        </div>

        <!-- Average Grade -->
        <div class="bg-white border border-gray-200 rounded-xl p-6 shadow-lg mb-8">
            <p class="text-gray-600 text-sm font-medium mb-2">Average Grade</p>
            <p class="text-4xl font-bold text-purple-600">{{ number_format($averageGrade, 1) }}%</p>
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
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Assignments</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Submissions</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Graded</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Avg Grade</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Submission Rate</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($classStats as $stat)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $stat['class_name'] }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $stat['teacher'] }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $stat['total_assignments'] }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $stat['total_submissions'] }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-green-600 font-medium">{{ $stat['total_graded'] }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $stat['average_grade'] }}%</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $stat['submission_rate'] }}%</td>
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
