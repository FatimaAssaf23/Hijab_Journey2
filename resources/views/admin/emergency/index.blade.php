@extends('layouts.admin')

@section('content')
<div class="min-h-screen" style="background: linear-gradient(135deg, #FFF4FA 0%, #FDF2F8 30%, #F0F9FF 70%, #E0F7FA 100%);">
    <!-- Header -->
    <div class="bg-gradient-to-r from-pink-200/90 via-rose-100/80 to-cyan-200/90 shadow-2xl border-b-4 border-pink-300/50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
            <div class="flex flex-col md:flex-row justify-between items-center gap-6">
                <div class="flex items-center gap-6 text-center md:text-left">
                    <!-- Emergency Icon -->
                    <div class="hidden md:flex items-center justify-center w-24 h-24 rounded-3xl bg-gradient-to-br from-pink-500 via-rose-400 to-cyan-500 shadow-2xl transform hover:scale-105 transition-all duration-300 border-4 border-white/50">
                        <div class="text-6xl filter drop-shadow-2xl">⚠️</div>
                    </div>
                    <div>
                        <h1 class="text-5xl font-extrabold text-gray-800 mb-3 drop-shadow-lg flex items-center gap-4 justify-center md:justify-start">
                            <span class="md:hidden flex items-center justify-center w-20 h-20 rounded-2xl bg-gradient-to-br from-pink-500 via-rose-400 to-cyan-500 shadow-xl border-4 border-white/50">
                                <span class="text-5xl">⚠️</span>
                            </span>
                            <span class="bg-clip-text text-transparent bg-gradient-to-r from-pink-600 via-rose-500 to-cyan-600">Emergency Reassignment</span>
                        </h1>
                        <p class="text-gray-700 text-lg font-medium">Handle teacher unavailability and reassign coverage</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Content -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <h2 class="text-3xl font-bold mb-8 bg-clip-text text-transparent bg-gradient-to-r from-pink-600 via-rose-500 to-cyan-600">Emergency Absence Requests</h2>
    
    @if($unreadEmergencyRequestsCount > 0)
    <div class="mb-6 bg-gradient-to-r from-red-50 to-orange-50 border border-red-200 rounded-xl p-4 flex items-center gap-4 shadow-sm">
        <div class="bg-red-100 p-3 rounded-full">
            <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
            </svg>
        </div>
        <div class="flex-1">
            <h4 class="font-semibold text-red-800">You have {{ $unreadEmergencyRequestsCount }} unread emergency request{{ $unreadEmergencyRequestsCount > 1 ? 's' : '' }}!</h4>
            <p class="text-sm text-red-700">New requests are highlighted below.</p>
        </div>
    </div>
    @endif
    
    <div class="bg-white rounded-xl shadow-lg p-6">
        <table class="min-w-full table-auto">
            <thead>
                <tr class="bg-[#F8C5C8] text-[#197D8C]">
                    <th class="px-4 py-2 text-left">Teacher</th>
                    <th class="px-4 py-2 text-left">Start Date</th>
                    <th class="px-4 py-2 text-left">End Date</th>
                    <th class="px-4 py-2 text-left">Reason</th>
                    <th class="px-4 py-2 text-left">Requested At</th>
                    <th class="px-4 py-2 text-left">Status</th>
                    <th class="px-4 py-2 text-left">Affected Classes</th>
                    <th class="px-4 py-2 text-left">Reassign</th>
                </tr>
            </thead>
            <tbody>
                @forelse($requests as $request)
                    <tr class="border-b {{ !$request->is_read && $request->status === 'pending' ? 'bg-red-50' : '' }}">
                        <td class="px-4 py-2">
                            <div class="flex items-center gap-2">
                                @if($request->teacher)
                                    {{ $request->teacher->first_name }} {{ $request->teacher->last_name }}
                                @else
                                    Unknown
                                @endif
                                @if(!$request->is_read && $request->status === 'pending')
                                    <span class="bg-red-500 text-white text-xs px-2 py-1 rounded-full font-bold animate-pulse">NEW</span>
                                @endif
                            </div>
                        </td>
                        <td class="px-4 py-2">{{ $request->start_date }}</td>
                        <td class="px-4 py-2">{{ $request->end_date }}</td>
                        <td class="px-4 py-2">{{ $request->reason }}</td>
                        <td class="px-4 py-2">{{ $request->created_at->format('Y-m-d H:i') }}</td>
                        <td class="px-4 py-2">
                            <span class="px-3 py-1 rounded-full text-xs font-semibold {{ $request->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : ($request->status === 'approved' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800') }}">
                                {{ ucfirst($request->status) }}
                            </span>
                        </td>
                        <td class="px-4 py-2">
                            @if($request->affected_classes && count($request->affected_classes))
                                @foreach($request->affected_classes as $class)
                                    <span class="inline-block bg-pink-100 text-pink-800 px-2 py-1 rounded-full text-xs font-semibold mr-1 mb-1">{{ $class }}</span>
                                @endforeach
                            @else
                                <span class="text-gray-400">None</span>
                            @endif
                        </td>
                        <td class="px-4 py-4">
                            <div class="flex flex-col gap-3 min-w-[280px]">
                                <!-- Approval/Rejection Actions -->
                                @if($request->status === 'pending')
                                    <div class="flex flex-col gap-2">
                                        <form method="POST" action="{{ route('admin.emergency.approve', $request->id) }}" class="w-full">
                                            @csrf
                                            <button type="submit" class="w-full bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 text-white px-4 py-2 rounded-lg text-sm font-semibold shadow-md hover:shadow-lg transition-all duration-200 flex items-center justify-center gap-2">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                </svg>
                                                Approve
                                            </button>
                                        </form>
                                        <div class="w-full">
                                            <button type="button" onclick="showRejectReason({{ $request->id }})" id="reject-btn-{{ $request->id }}" class="w-full bg-gradient-to-r from-red-500 to-red-600 hover:from-red-600 hover:to-red-700 text-white px-4 py-2 rounded-lg text-sm font-semibold shadow-md hover:shadow-lg transition-all duration-200 flex items-center justify-center gap-2">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                                </svg>
                                                Reject
                                            </button>
                                            <form method="POST" action="{{ route('admin.emergency.reject', $request->id) }}" class="w-full hidden" id="reject-form-{{ $request->id }}">
                                                @csrf
                                                <div class="flex flex-col gap-2 mt-2">
                                                    <input type="text" name="rejection_reason" placeholder="Reason for rejection" class="w-full rounded-lg border-2 border-gray-300 focus:border-red-400 focus:ring-2 focus:ring-red-200 px-3 py-2 text-sm transition-all duration-200" required>
                                                    <button type="submit" class="w-full bg-gradient-to-r from-red-500 to-red-600 hover:from-red-600 hover:to-red-700 text-white px-4 py-2 rounded-lg text-sm font-semibold shadow-md hover:shadow-lg transition-all duration-200 flex items-center justify-center gap-2">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                                        </svg>
                                                        Confirm Reject
                                                    </button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                @endif
                                
                                <!-- Reassignment Section -->
                                @if($request->affected_classes && count($request->affected_classes))
                                    <div class="border-t border-gray-200 pt-3">
                                        <form method="POST" action="{{ route('admin.emergency.reassign') }}" class="space-y-3">
                                            @csrf
                                            <input type="hidden" name="emergency_request_id" value="{{ $request->id }}">
                                            <div class="flex flex-col gap-2">
                                                <label for="teacher_id_{{ $request->id }}" class="text-xs font-bold text-[#197D8C] uppercase tracking-wide flex items-center gap-1">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                                    </svg>
                                                    Reassign to Teacher
                                                </label>
                                                <select name="teacher_id" id="teacher_id_{{ $request->id }}" onchange="toggleReassignButton({{ $request->id }})" class="w-full rounded-lg border-2 border-gray-300 focus:border-[#EC769A] focus:ring-2 focus:ring-pink-200 px-3 py-2 text-sm font-medium transition-all duration-200 bg-white hover:border-gray-400">
                                                    <option value="">Select teacher</option>
                                                    @foreach(\App\Models\User::where('role', 'teacher')->where('user_id', '!=', $request->teacher_id)->get() as $teacher)
                                                        <option value="{{ $teacher->user_id }}">{{ $teacher->first_name }} {{ $teacher->last_name }}</option>
                                                    @endforeach
                                                </select>
                                                <button type="submit" id="reassign-btn-{{ $request->id }}" class="w-full bg-gradient-to-r from-[#EC769A] to-[#FC9EAC] hover:from-[#FC9EAC] hover:to-[#F8C5C8] text-white px-4 py-2.5 rounded-lg text-sm font-bold shadow-md hover:shadow-lg transition-all duration-200 flex items-center justify-center gap-2 hidden">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path>
                                                    </svg>
                                                    Reassign
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="text-center text-gray-400 py-6">No emergency requests found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Success Message -->
    @if (session('success'))
        <div class="mt-4">
            <div class="bg-green-500 text-white rounded-lg p-4 shadow-lg">
                ✓ {{ session('success') }}
            </div>
        </div>
    @endif
</div>

<script>
    function showRejectReason(requestId) {
        const rejectBtn = document.getElementById('reject-btn-' + requestId);
        const rejectForm = document.getElementById('reject-form-' + requestId);
        
        if (rejectForm.classList.contains('hidden')) {
            rejectForm.classList.remove('hidden');
            rejectBtn.classList.add('hidden');
            rejectForm.querySelector('input[name="rejection_reason"]').focus();
        }
    }

    function toggleReassignButton(requestId) {
        const selectElement = document.getElementById('teacher_id_' + requestId);
        const reassignBtn = document.getElementById('reassign-btn-' + requestId);
        
        if (selectElement.value && selectElement.value !== '') {
            reassignBtn.classList.remove('hidden');
        } else {
            reassignBtn.classList.add('hidden');
        }
    }
</script>
@endsection
