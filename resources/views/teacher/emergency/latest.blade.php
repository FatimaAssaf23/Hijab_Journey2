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
    <h2 class="text-2xl font-bold mb-6 text-[#197D8C]">Emergency Absence Requests</h2>
    
    @if($errors->any())
        <div class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg">
            <ul class="list-disc list-inside">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    
    @if(isset($allRequests) && $allRequests->count() > 0)
        <div class="mb-6">
            <h3 class="text-lg font-semibold text-gray-700 mb-3">All Your Requests ({{ $allRequests->count() }})</h3>
            <div class="space-y-4">
                @foreach($allRequests as $req)
                    <div class="rounded-xl shadow-md border border-gray-200 bg-gradient-to-br from-[#F8FAFC] to-[#E0F2FE] p-4 flex flex-col gap-3">
                        <div class="flex items-center justify-between">
                            <span class="text-sm font-bold text-[#197D8C]">
                                {{ \Carbon\Carbon::parse($req->start_date)->format('M j, Y') }} - {{ \Carbon\Carbon::parse($req->end_date)->format('M j, Y') }}
                            </span>
                            <span class="inline-block px-3 py-1 rounded-full text-xs font-semibold
                                {{ $req->status === 'pending' ? 'bg-yellow-100 text-yellow-800 border border-yellow-300' : ($req->status === 'approved' ? 'bg-green-100 text-green-800 border border-green-300' : ($req->status === 'reassigned' ? 'bg-blue-100 text-blue-800 border border-blue-300' : 'bg-red-100 text-red-800 border border-red-300')) }}">
                                {{ ucfirst($req->status) }}
                            </span>
                        </div>
                        <div class="text-sm text-gray-600">{{ Str::limit($req->reason, 100) }}</div>
                        @if($req->status === 'rejected' && $req->rejection_reason)
                            <div class="mt-2">
                                <div class="text-xs text-red-500 font-semibold mb-1">Rejection Reason</div>
                                <div class="bg-red-50 border border-red-200 rounded-lg px-3 py-2 text-red-700 text-sm">{{ $req->rejection_reason }}</div>
                            </div>
                        @endif
                        @if($req->status === 'pending')
                            <div class="mt-2">
                                <a href="{{ route('teacher.emergency.edit', $req->id) }}" class="inline-flex items-center gap-2 px-4 py-2 bg-[#3DD9C4] text-white rounded-lg font-semibold hover:bg-[#25A99E] transition text-sm">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                    Edit Request
                                </a>
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>
    @else
        <div class="mb-6 bg-yellow-100 border border-yellow-400 text-yellow-700 px-4 py-3 rounded-lg">
            You have not submitted any emergency absence requests yet.
        </div>
    @endif
    <!-- Emergency Absence Request Modal Trigger Button -->
    <button type="button" onclick="document.getElementById('emergencyModal').classList.remove('hidden')" class="block w-full text-center py-2 bg-[#3DD9C4] text-white rounded-lg font-bold hover:bg-[#25A99E] transition mb-4">Fill Emergency Absence Request</button>

    <!-- Emergency Absence Request Modal -->
    <div id="emergencyModal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-40 z-50 {{ $errors->any() ? '' : 'hidden' }}">
        <div class="bg-white rounded-xl shadow-lg p-8 w-full max-w-lg relative">
            <button type="button" onclick="document.getElementById('emergencyModal').classList.add('hidden')" class="absolute top-2 right-2 text-gray-400 hover:text-gray-700 text-2xl">&times;</button>
            <h2 class="text-2xl font-bold mb-6 text-[#197D8C]">Emergency Absence Request</h2>
            <form method="POST" action="{{ route('teacher.emergency.store') }}">
                @csrf
                @if($errors->any())
                    <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg">
                        <ul class="list-disc list-inside text-sm">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <div class="mb-4">
                    <label class="block text-gray-700 font-semibold mb-2">Start Date</label>
                    <input type="date" id="modal_start_date" name="start_date" value="{{ old('start_date') }}" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:border-pink-500 {{ $errors->has('start_date') ? 'border-red-500' : '' }}" required min="{{ \Carbon\Carbon::tomorrow()->toDateString() }}" placeholder="Select a date after today">
                    @error('start_date')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 font-semibold mb-2">End Date</label>
                    <input type="date" id="modal_end_date" name="end_date" value="{{ old('end_date') }}" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:border-pink-500 {{ $errors->has('end_date') ? 'border-red-500' : '' }}" required min="{{ \Carbon\Carbon::tomorrow()->toDateString() }}">
                    @error('end_date')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 font-semibold mb-2">Reason</label>
                    <textarea name="reason" rows="4" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:border-pink-500 {{ $errors->has('reason') ? 'border-red-500' : '' }}" required>{{ old('reason') }}</textarea>
                    @error('reason')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <button type="submit" class="w-full py-2 bg-[#EC769A] text-white rounded-lg font-bold hover:bg-[#FC8EAC] transition">Submit Request</button>
            </form>
            <script>
                // Enforce start date > today on browsers that ignore min
                document.querySelector('#emergencyModal form').addEventListener('submit', function(e) {
                    var start = document.getElementById('modal_start_date').value;
                    var today = new Date();
                    var minDate = new Date(today.getFullYear(), today.getMonth(), today.getDate() + 1);
                    var startDate = new Date(start);
                    if (startDate < minDate) {
                        alert('Start date must be after today.');
                        e.preventDefault();
                    }
                });
            </script>
            </form>
        </div>
    </div>
@push('scripts')
<script>
// Auto-open modal if there are validation errors
@if($errors->any())
    document.addEventListener('DOMContentLoaded', function() {
        document.getElementById('emergencyModal').classList.remove('hidden');
    });
@endif
</script>
@endpush
</div>
@endsection
