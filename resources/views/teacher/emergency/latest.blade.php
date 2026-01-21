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
    <h2 class="text-2xl font-bold mb-6 text-[#197D8C]">Your Latest Emergency Absence Request</h2>
    @if($request)
        <div class="mb-8">
            <div class="rounded-xl shadow-md border border-gray-200 bg-gradient-to-br from-[#F8FAFC] to-[#E0F2FE] p-6 flex flex-col gap-4">
                <div class="flex items-center justify-between">
                    <span class="text-lg font-bold text-[#197D8C] flex items-center gap-2">
                        <svg class="w-6 h-6 text-[#197D8C]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        Emergency Absence Request
                    </span>
                    <span data-emergency-status class="inline-block px-3 py-1 rounded-full text-xs font-semibold
                        {{ $request->status === 'pending' ? 'bg-yellow-100 text-yellow-800 border border-yellow-300' : ($request->status === 'approved' ? 'bg-green-100 text-green-800 border border-green-300' : 'bg-red-100 text-red-800 border border-red-300') }}">
                        {{ ucfirst($request->status) }}
                    </span>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <div class="text-xs text-gray-500 font-semibold mb-1">Start Date</div>
                        <div class="bg-white border border-gray-200 rounded-lg px-3 py-2 text-gray-800 font-medium">{{ \Carbon\Carbon::parse($request->start_date)->format('F j, Y') }}</div>
                    </div>
                    <div>
                        <div class="text-xs text-gray-500 font-semibold mb-1">End Date</div>
                        <div class="bg-white border border-gray-200 rounded-lg px-3 py-2 text-gray-800 font-medium">{{ \Carbon\Carbon::parse($request->end_date)->format('F j, Y') }}</div>
                    </div>
                </div>
                <div>
                    <div class="text-xs text-gray-500 font-semibold mb-1">Reason</div>
                    <div class="bg-white border border-gray-200 rounded-lg px-3 py-2 text-gray-800">{{ $request->reason }}</div>
                </div>
                <div data-rejection-reason style="{{ $request->status === 'rejected' && $request->rejection_reason ? '' : 'display:none;' }}">
                    <div class="text-xs text-red-500 font-semibold mb-1">Rejection Reason</div>
                    <div class="bg-red-50 border border-red-200 rounded-lg px-3 py-2 text-red-700 rejection-text">{{ $request->rejection_reason }}</div>
                </div>
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
    <div id="emergencyModal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-40 z-50 hidden">
        <div class="bg-white rounded-xl shadow-lg p-8 w-full max-w-lg relative">
            <button type="button" onclick="document.getElementById('emergencyModal').classList.add('hidden')" class="absolute top-2 right-2 text-gray-400 hover:text-gray-700 text-2xl">&times;</button>
            <h2 class="text-2xl font-bold mb-6 text-[#197D8C]">Emergency Absence Request</h2>
            <form method="POST" action="{{ route('teacher.emergency.store') }}">
                @csrf
                <div class="mb-4">
                    <label class="block text-gray-700 font-semibold mb-2">Start Date</label>
                    <input type="date" id="modal_start_date" name="start_date" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:border-pink-500" required min="{{ \Carbon\Carbon::tomorrow()->toDateString() }}" placeholder="Select a date after today">
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 font-semibold mb-2">End Date</label>
                    <input type="date" id="modal_end_date" name="end_date" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:border-pink-500" required min="{{ \Carbon\Carbon::tomorrow()->toDateString() }}">
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 font-semibold mb-2">Reason</label>
                    <textarea name="reason" rows="4" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:border-pink-500" required></textarea>
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
// AJAX polling for latest emergency request
function updateEmergencyRequestUI(data) {
    if (!data || !data.status) return;
    // Update status badge
    var statusSpan = document.querySelector('[data-emergency-status]');
    if (statusSpan) {
        statusSpan.textContent = data.status.charAt(0).toUpperCase() + data.status.slice(1);
        statusSpan.className = 'inline-block px-3 py-1 rounded-full text-xs font-semibold ' +
            (data.status === 'pending' ? 'bg-yellow-100 text-yellow-800 border border-yellow-300' :
            (data.status === 'approved' ? 'bg-green-100 text-green-800 border border-green-300' :
            'bg-red-100 text-red-800 border border-red-300'));
    }
    // Update rejection reason
    var rejectionDiv = document.querySelector('[data-rejection-reason]');
    if (rejectionDiv) {
        if (data.status === 'rejected' && data.rejection_reason) {
            rejectionDiv.style.display = '';
            rejectionDiv.querySelector('.rejection-text').textContent = data.rejection_reason;
        } else {
            rejectionDiv.style.display = 'none';
        }
    }
}

function pollLatestEmergencyRequest() {
    fetch('/api/teacher-latest-emergency-request.json', { cache: 'no-store' })
        .then(res => res.json())
        .then(data => updateEmergencyRequestUI(data));
}
setInterval(pollLatestEmergencyRequest, 10000); // Poll every 10 seconds
document.addEventListener('DOMContentLoaded', pollLatestEmergencyRequest);
</script>
@endpush
</div>
@endsection
