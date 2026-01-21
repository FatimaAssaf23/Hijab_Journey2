@extends('layouts.app')

@section('content')
<div class="max-w-xl mx-auto mt-10 bg-white rounded-xl shadow-lg p-8">
    <!-- Go Back Button -->
    <div class="mb-6">
        <button onclick="goBackOrRedirect('{{ route('teacher.dashboard') }}')" 
                class="flex items-center gap-2 px-6 py-3 rounded-xl font-semibold text-white shadow-lg hover:shadow-xl transition-all duration-300 transform hover:scale-105" 
                style="background: linear-gradient(135deg, #FC8EAC, #6EC6C5);">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Go Back
        </button>
    </div>
    <h2 class="text-2xl font-bold mb-6 text-[#197D8C]">Emergency Absence Request Submitted</h2>
    <div class="mb-6">
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg">
            ✓ Your emergency absence request has been submitted!
        </div>
    </div>
    <div class="mb-4">
        <label class="block text-gray-700 font-semibold mb-2">Start Date</label>
        <div class="px-4 py-2 bg-gray-100 rounded">{{ $request->start_date }}</div>
    </div>
    <div class="mb-4">
        <label class="block text-gray-700 font-semibold mb-2">End Date</label>
        <div class="px-4 py-2 bg-gray-100 rounded">{{ $request->end_date }}</div>
    </div>
    <div class="mb-4">
        <label class="block text-gray-700 font-semibold mb-2">Reason</label>
        <div class="px-4 py-2 bg-gray-100 rounded">{{ $request->reason }}</div>
    </div>
    <div class="mb-4">
        <label class="block text-gray-700 font-semibold mb-2">Status</label>
        <div class="px-4 py-2 bg-yellow-100 text-yellow-800 rounded font-semibold">Pending</div>
    </div>
    <a href="{{ route('dashboard') }}" class="block w-full text-center py-2 bg-[#EC769A] text-white rounded-lg font-bold hover:bg-[#FC8EAC] transition mt-6">Back to Dashboard</a>
</div>
@endsection
