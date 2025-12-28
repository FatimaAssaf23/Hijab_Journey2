@extends('layouts.app')

@section('content')
<div class="max-w-xl mx-auto mt-10 bg-white rounded-xl shadow-lg p-8">
    <h2 class="text-2xl font-bold mb-6 text-[#197D8C]">Emergency Absence Request</h2>
    <form method="POST" action="{{ route('teacher.emergency.store') }}">
        @csrf
        <div class="mb-4">
            <label class="block text-gray-700 font-semibold mb-2">Start Date</label>
            <input type="date" name="start_date" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:border-pink-500" required min="{{ \Carbon\Carbon::tomorrow()->toDateString() }}">
        </div>
        <div class="mb-4">
            <label class="block text-gray-700 font-semibold mb-2">End Date</label>
            <input type="date" name="end_date" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:border-pink-500" required min="{{ \Carbon\Carbon::tomorrow()->toDateString() }}">
        </div>
        <div class="mb-4">
            <label class="block text-gray-700 font-semibold mb-2">Reason</label>
            <textarea name="reason" rows="4" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:border-pink-500" required></textarea>
        </div>
        <button type="submit" class="w-full py-2 bg-[#EC769A] text-white rounded-lg font-bold hover:bg-[#FC8EAC] transition">Submit Request</button>
    </form>
</div>
@endsection
