@extends('layouts.app')

@section('content')
<div class="w-full min-h-screen px-4 sm:px-6 lg:px-8 py-8 bg-gradient-to-br from-pink-50 via-rose-50 to-pink-50 relative overflow-hidden">
    <!-- Decorative background elements -->
    <div class="absolute top-0 right-0 w-96 h-96 bg-gradient-to-br from-[#FC8EAC]/10 to-[#6EC6C5]/10 rounded-full blur-3xl -mr-48 -mt-48"></div>
    <div class="absolute bottom-0 left-0 w-96 h-96 bg-gradient-to-tr from-[#6EC6C5]/10 to-[#FC8EAC]/10 rounded-full blur-3xl -ml-48 -mb-48"></div>
    
    <div class="w-full mx-auto bg-white/90 backdrop-blur-sm rounded-2xl shadow-2xl p-8 relative z-10 border border-white/50">
    <!-- Go Back Button -->
    <div class="mb-6">
        <button onclick="goBackOrRedirect('{{ route('teacher.dashboard') }}')" 
                class="group flex items-center gap-2 px-6 py-3 rounded-xl font-semibold text-white shadow-lg hover:shadow-2xl transition-all duration-300 transform hover:scale-105 hover:-translate-y-0.5" 
                style="background: linear-gradient(135deg, #FC8EAC, #6EC6C5);">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 transform group-hover:-translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Go Back
        </button>
    </div>
    
    <!-- Header with icon -->
    <div class="flex items-center gap-3 mb-6">
        <div class="p-3 bg-gradient-to-br from-[#FC8EAC] to-[#40E0D0] rounded-xl shadow-lg">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
            </svg>
        </div>
        <div>
            <h2 class="text-3xl font-bold text-[#197D8C]">Emergency Absence Requests</h2>
            <p class="text-sm text-gray-500 mt-1">Manage your absence requests with ease</p>
        </div>
    </div>
    
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
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-[#197D8C] flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                    All Your Requests ({{ $allRequests->count() }})
                </h3>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 2xl:grid-cols-5 gap-4">
                @foreach($allRequests as $req)
                    <div class="group rounded-xl shadow-md hover:shadow-xl border border-[#48D1CC]/40 bg-gradient-to-br from-[#FC8EAC]/10 via-[#EC769A]/5 to-[#48D1CC]/15 p-4 flex flex-col gap-3 transition-all duration-300 transform hover:-translate-y-1 hover:scale-[1.02]">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-[#40E0D0]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                <span class="text-sm font-bold text-[#197D8C]">
                                    {{ \Carbon\Carbon::parse($req->start_date)->format('M j, Y') }} - {{ \Carbon\Carbon::parse($req->end_date)->format('M j, Y') }}
                                </span>
                            </div>
                            <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-semibold shadow-sm
                                {{ $req->status === 'pending' ? 'bg-yellow-100 text-yellow-800 border border-yellow-300 animate-pulse' : ($req->status === 'approved' ? 'bg-green-100 text-green-800 border border-green-300' : ($req->status === 'reassigned' ? 'bg-blue-100 text-blue-800 border border-blue-300' : 'bg-red-100 text-red-800 border border-red-300')) }}">
                                @if($req->status === 'pending')
                                    <svg class="h-3 w-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/></svg>
                                @elseif($req->status === 'approved')
                                    <svg class="h-3 w-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                                @elseif($req->status === 'rejected')
                                    <svg class="h-3 w-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/></svg>
                                @endif
                                {{ ucfirst($req->status) }}
                            </span>
                        </div>
                        <div class="flex items-start gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-[#40E0D0] mt-0.5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            <div class="text-sm text-gray-700 flex-1">{{ Str::limit($req->reason, 100) }}</div>
                        </div>
                        @if($req->status === 'rejected' && $req->rejection_reason)
                            <div class="mt-2 p-3 bg-red-50 border border-red-200 rounded-lg">
                                <div class="text-xs text-red-600 font-semibold mb-1 flex items-center gap-1">
                                    <svg class="h-3 w-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                                    Rejection Reason
                                </div>
                                <div class="text-red-700 text-sm">{{ $req->rejection_reason }}</div>
                            </div>
                        @endif
                        @if($req->status === 'pending')
                            <div class="mt-2">
                                <a href="{{ route('teacher.emergency.edit', $req->id) }}" class="group/edit inline-flex items-center gap-2 px-4 py-2 bg-gradient-to-r from-[#40E0D0] to-[#48D1CC] text-white rounded-lg font-semibold hover:shadow-lg transition-all duration-300 transform hover:scale-105 text-sm">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 transform group-hover/edit:rotate-12 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
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
        <div class="mb-6 bg-gradient-to-r from-[#FC8EAC]/20 via-[#EC769A]/15 to-[#48D1CC]/25 border border-[#48D1CC]/40 text-[#197D8C] px-6 py-4 rounded-xl flex items-center gap-3">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-[#40E0D0]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <span>You have not submitted any emergency absence requests yet.</span>
        </div>
    @endif
    <!-- Emergency Absence Request Modal Trigger Button -->
    <button type="button" onclick="document.getElementById('emergencyModal').classList.remove('hidden')" class="group w-full flex items-center justify-center gap-3 py-4 bg-gradient-to-r from-[#FC8EAC] via-[#EC769A] to-[#6EC6C5] text-white rounded-xl font-bold hover:opacity-90 transition-all duration-300 shadow-lg hover:shadow-2xl transform hover:scale-[1.02] mb-4">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 transform group-hover:rotate-90 transition-transform duration-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
        </svg>
        Fill Emergency Absence Request
    </button>

    <!-- Emergency Absence Request Modal -->
    <div id="emergencyModal" class="fixed inset-0 flex items-center justify-center bg-black/50 backdrop-blur-sm z-50 {{ $errors->any() ? '' : 'hidden' }} transition-opacity duration-300" onclick="if(event.target === this) document.getElementById('emergencyModal').classList.add('hidden')">
        <div class="bg-white rounded-2xl shadow-2xl p-8 w-full max-w-lg relative transform transition-all duration-300 scale-100" id="modalContent">
            <button type="button" onclick="window.closeEmergencyModal ? window.closeEmergencyModal() : document.getElementById('emergencyModal').classList.add('hidden')" class="absolute top-4 right-4 text-gray-400 hover:text-gray-700 hover:bg-gray-100 rounded-full p-1 transition-all duration-200">
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
            <div class="flex items-center gap-3 mb-6">
                <div class="p-2 bg-gradient-to-br from-[#FC8EAC] to-[#40E0D0] rounded-lg">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                </div>
                <h2 class="text-2xl font-bold text-[#197D8C]">Emergency Absence Request</h2>
            </div>
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
                    <label class="block text-gray-700 font-semibold mb-2 flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-[#40E0D0]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        Start Date
                    </label>
                    <input type="date" id="modal_start_date" name="start_date" value="{{ old('start_date') }}" class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:outline-none focus:border-[#40E0D0] focus:ring-2 focus:ring-[#40E0D0]/20 transition-all {{ $errors->has('start_date') ? 'border-red-500' : '' }}" required min="{{ \Carbon\Carbon::tomorrow()->toDateString() }}" placeholder="Select a date after today">
                    @error('start_date')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 font-semibold mb-2 flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-[#40E0D0]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        End Date
                    </label>
                    <input type="date" id="modal_end_date" name="end_date" value="{{ old('end_date') }}" class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:outline-none focus:border-[#40E0D0] focus:ring-2 focus:ring-[#40E0D0]/20 transition-all {{ $errors->has('end_date') ? 'border-red-500' : '' }}" required min="{{ \Carbon\Carbon::tomorrow()->toDateString() }}">
                    @error('end_date')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 font-semibold mb-2 flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-[#40E0D0]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        Reason
                    </label>
                    <textarea name="reason" rows="4" class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:outline-none focus:border-[#40E0D0] focus:ring-2 focus:ring-[#40E0D0]/20 transition-all {{ $errors->has('reason') ? 'border-red-500' : '' }}" required>{{ old('reason') }}</textarea>
                    @error('reason')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <button type="submit" class="w-full py-3 bg-gradient-to-r from-[#EC769A] via-[#FC8EAC] to-[#40E0D0] text-white rounded-lg font-bold hover:shadow-lg transition-all duration-300 transform hover:scale-[1.02] flex items-center justify-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    Submit Request
                </button>
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
        const modal = document.getElementById('emergencyModal');
        const content = document.getElementById('modalContent');
        if (modal && content) {
            modal.classList.remove('hidden');
            setTimeout(() => {
                content.style.transform = 'scale(1)';
                content.style.opacity = '1';
            }, 10);
        }
    });
@endif

// Smooth modal animations
document.addEventListener('DOMContentLoaded', function() {
    const modal = document.getElementById('emergencyModal');
    const content = document.getElementById('modalContent');
    
    if (!modal || !content) return;
    
    // Close modal when clicking outside
    modal.addEventListener('click', function(e) {
        if (e.target === modal) {
            closeModal();
        }
    });
    
    function closeModal() {
        if (content) {
            content.style.transform = 'scale(0.95)';
            content.style.opacity = '0';
        }
        setTimeout(() => {
            modal.classList.add('hidden');
        }, 200);
    }
    
    // Make closeModal available globally for onclick handlers
    window.closeEmergencyModal = closeModal;
});
</script>
@endpush
    </div>
</div>
@endsection
