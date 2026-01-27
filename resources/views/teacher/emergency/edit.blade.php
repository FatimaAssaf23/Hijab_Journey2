@extends('layouts.app')

@section('content')
<div class="max-w-xl mx-auto mt-10 bg-white rounded-xl shadow-lg p-8">
    <!-- Go Back Button -->
    <div class="mb-6">
        <button onclick="goBackOrRedirect('{{ route('teacher.emergency.create') }}')" 
                class="flex items-center gap-2 px-6 py-3 rounded-xl font-semibold text-white shadow-lg hover:shadow-xl transition-all duration-300 transform hover:scale-105" 
                style="background: linear-gradient(135deg, #FC8EAC, #6EC6C5);">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Go Back
        </button>
    </div>
    <h2 class="text-2xl font-bold mb-6 text-[#197D8C]">Edit Emergency Absence Request</h2>
    
    @if(session('error'))
        <div class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg">
            {{ session('error') }}
        </div>
    @endif
    
    @if($errors->any())
        <div class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg">
            <ul class="list-disc list-inside">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    
    <form method="POST" action="{{ route('teacher.emergency.update', $request->id) }}">
        @csrf
        @method('PUT')
        
        <div class="mb-4">
            <label class="block text-gray-700 font-semibold mb-2">Start Date</label>
            <input type="date" name="start_date" value="{{ old('start_date', $request->start_date) }}" 
                   class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:border-pink-500 {{ $errors->has('start_date') ? 'border-red-500' : '' }}" 
                   required min="{{ \Carbon\Carbon::tomorrow()->toDateString() }}">
            @error('start_date')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>
        
        <div class="mb-4">
            <label class="block text-gray-700 font-semibold mb-2">End Date</label>
            <input type="date" name="end_date" value="{{ old('end_date', $request->end_date) }}" 
                   class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:border-pink-500 {{ $errors->has('end_date') ? 'border-red-500' : '' }}" 
                   required min="{{ \Carbon\Carbon::tomorrow()->toDateString() }}">
            @error('end_date')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>
        
        <div class="mb-4">
            <label class="block text-gray-700 font-semibold mb-2">Reason</label>
            <textarea name="reason" rows="4" 
                      class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:border-pink-500 {{ $errors->has('reason') ? 'border-red-500' : '' }}" 
                      required>{{ old('reason', $request->reason) }}</textarea>
            @error('reason')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>
        
        <div class="mb-4">
            <label class="block text-gray-700 font-semibold mb-2">Status</label>
            <div class="px-4 py-2 bg-yellow-100 text-yellow-800 rounded font-semibold">{{ ucfirst($request->status) }}</div>
            <p class="text-xs text-gray-500 mt-1">You can only edit requests with pending status.</p>
        </div>
        
        <div class="flex gap-3 mt-6">
            <button type="submit" class="flex-1 py-2 bg-[#3DD9C4] text-white rounded-lg font-bold hover:bg-[#25A99E] transition flex items-center justify-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
                Update Request
            </button>
            <a href="{{ route('teacher.emergency.create') }}" class="flex-1 text-center py-2 bg-gray-300 text-gray-700 rounded-lg font-bold hover:bg-gray-400 transition">Cancel</a>
        </div>
    </form>
    
    <script>
        // Enforce start date > today and end date >= start date
        document.querySelector('form').addEventListener('submit', function(e) {
            var start = document.querySelector('input[name="start_date"]').value;
            var end = document.querySelector('input[name="end_date"]').value;
            var today = new Date();
            var minDate = new Date(today.getFullYear(), today.getMonth(), today.getDate() + 1);
            var startDate = new Date(start);
            var endDate = new Date(end);
            
            if (startDate < minDate) {
                alert('Start date must be after today.');
                e.preventDefault();
                return false;
            }
            
            if (endDate < startDate) {
                alert('End date must be on or after the start date.');
                e.preventDefault();
                return false;
            }
        });
    </script>
</div>
@endsection
