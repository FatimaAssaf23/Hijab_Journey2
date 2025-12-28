@extends('layouts.app')

@section('content')
<div class="max-w-xl mx-auto mt-10 bg-white rounded-xl shadow-lg p-8">
    <h2 class="text-2xl font-bold mb-6 text-[#197D8C]">Your Latest Emergency Absence Request</h2>
    @if($request)
        <div class="mb-6">
            <div class="bg-blue-100 border border-blue-400 text-blue-700 px-4 py-3 rounded-lg">
                <strong>Status:</strong>
                <span class="font-semibold {{ $request->status === 'pending' ? 'text-yellow-700' : ($request->status === 'approved' ? 'text-green-700' : 'text-red-700') }}">
                    {{ ucfirst($request->status) }}
                </span>
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
    @else
        <div class="mb-6 bg-yellow-100 border border-yellow-400 text-yellow-700 px-4 py-3 rounded-lg">
            You have not submitted any emergency absence requests yet.
        </div>
    @endif
    <a href="{{ route('dashboard') }}" class="block w-full text-center py-2 bg-[#EC769A] text-white rounded-lg font-bold hover:bg-[#FC8EAC] transition mt-6">Back to Dashboard</a>
</div>
@endsection
