@extends('layouts.app')

@section('content')
<div class="w-full min-h-screen px-4 sm:px-6 lg:px-8 py-6 bg-gradient-to-br from-pink-50 via-rose-50 to-pink-50 relative overflow-hidden">
    <!-- Decorative background elements -->
    <div class="absolute top-0 right-0 w-96 h-96 bg-gradient-to-br from-[#FC8EAC]/10 to-[#6EC6C5]/10 rounded-full blur-3xl -mr-48 -mt-48"></div>
    <div class="absolute bottom-0 left-0 w-96 h-96 bg-gradient-to-tr from-[#6EC6C5]/10 to-[#FC8EAC]/10 rounded-full blur-3xl -ml-48 -mb-48"></div>
    
    <div class="w-full mx-auto bg-white/90 backdrop-blur-sm rounded-2xl shadow-2xl p-8 relative z-10 border border-white/50">
    <!-- Go Back Button -->
    <div class="mb-5">
        <button onclick="goBackOrRedirect('{{ route('teacher.emergency.create') }}')" 
                class="group flex items-center gap-2 px-5 py-2.5 rounded-xl font-semibold text-white shadow-lg hover:shadow-xl transition-all duration-300 transform hover:scale-105 hover:-translate-y-0.5" 
                style="background: linear-gradient(135deg, #FC8EAC, #6EC6C5);">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 transform group-hover:-translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Go Back
        </button>
    </div>
    
    <!-- Header with icon -->
    <div class="flex items-center gap-3 mb-6">
        <div class="p-3 bg-gradient-to-br from-[#FC8EAC] to-[#6EC6C5] rounded-xl shadow-lg">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
            </svg>
        </div>
        <div>
            <h2 class="text-3xl font-bold text-[#197D8C]">Edit Emergency Absence Request</h2>
            <p class="text-sm text-gray-500 mt-1">Update your absence request details</p>
        </div>
    </div>
    
    @if(session('error'))
        <div class="mb-5 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg">
            {{ session('error') }}
        </div>
    @endif
    
    @if($errors->any())
        <div class="mb-5 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg">
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
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-5">
            <div class="relative">
                <label class="block text-gray-700 font-semibold mb-2 flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-[#197D8C]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    Start Date
                </label>
                <input type="date" name="start_date" value="{{ old('start_date', $request->start_date) }}" 
                       class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:outline-none focus:border-[#FC8EAC] focus:ring-2 focus:ring-[#FC8EAC]/20 transition-all {{ $errors->has('start_date') ? 'border-red-500' : '' }}" 
                       required min="{{ \Carbon\Carbon::tomorrow()->toDateString() }}">
                @error('start_date')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
            
            <div class="relative">
                <label class="block text-gray-700 font-semibold mb-2 flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-[#197D8C]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    End Date
                </label>
                <input type="date" name="end_date" value="{{ old('end_date', $request->end_date) }}" 
                       class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:outline-none focus:border-[#FC8EAC] focus:ring-2 focus:ring-[#FC8EAC]/20 transition-all {{ $errors->has('end_date') ? 'border-red-500' : '' }}" 
                       required min="{{ \Carbon\Carbon::tomorrow()->toDateString() }}">
                @error('end_date')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>
        
        <div class="mb-5">
            <label class="block text-gray-700 font-semibold mb-2 flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-[#197D8C]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                Reason
            </label>
            <textarea name="reason" rows="4" 
                      class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:outline-none focus:border-[#FC8EAC] focus:ring-2 focus:ring-[#FC8EAC]/20 transition-all {{ $errors->has('reason') ? 'border-red-500' : '' }}" 
                      required>{{ old('reason', $request->reason) }}</textarea>
            @error('reason')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>
        
        <div class="mb-5 p-4 bg-gradient-to-r from-yellow-50 to-amber-50 border border-yellow-200 rounded-xl">
            <label class="block text-gray-700 font-semibold mb-2 flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-yellow-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                Status
            </label>
            <div class="px-4 py-2 bg-yellow-100 text-yellow-800 rounded-lg font-semibold inline-flex items-center gap-2 shadow-sm">
                <svg class="h-4 w-4 animate-pulse" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/></svg>
                {{ ucfirst($request->status) }}
            </div>
            <p class="text-xs text-gray-600 mt-2 flex items-center gap-1">
                <svg class="h-3 w-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                You can only edit requests with pending status.
            </p>
        </div>
        
        <div class="flex gap-3 mt-6">
            <button type="submit" class="group flex-1 py-3 bg-gradient-to-r from-[#FC8EAC] via-[#EC769A] to-[#6EC6C5] text-white rounded-xl font-bold hover:opacity-90 transition-all duration-300 shadow-lg hover:shadow-2xl flex items-center justify-center gap-2 transform hover:scale-[1.02]">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 transform group-hover:rotate-12 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
                Update Request
            </button>
            <a href="{{ route('teacher.emergency.create') }}" class="group flex-1 text-center py-3 bg-gray-200 text-gray-700 rounded-xl font-bold hover:bg-gray-300 transition-all duration-300 flex items-center justify-center gap-2 transform hover:scale-[1.02]">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
                Cancel
            </a>
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
</div>
@endsection
