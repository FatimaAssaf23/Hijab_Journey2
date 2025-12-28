@extends('layouts.admin')

@section('content')
<div class="min-h-screen">
    <!-- Header -->
    <div class="shadow-xl" style="background: linear-gradient(90deg, #EC769A 0%, #FC9EAC 50%, #F8C5C8 100%);">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <h1 class="text-4xl font-extrabold text-white mb-2 drop-shadow-lg">✓ Teacher Requests</h1>
            <p class="text-white drop-shadow">Review and approve teacher applications</p>
        </div>
    </div>

    <!-- Success Message -->
    @if (session('success'))
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4">
            <div class="bg-green-500 text-white rounded-lg p-4 shadow-lg">
                ✓ {{ session('success') }}
            </div>
        </div>
    @endif

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-8">
        <!-- Pending Requests -->
        <div>
            <div class="mb-6">
                <h2 class="text-3xl font-extrabold mb-2 drop-shadow" style="color: #EC769A;">⏳ Pending Requests (<span id="pendingCount">{{ count($pending) }}</span>)</h2>
                <p class="text-gray-700 font-medium">Review and approve new teacher applications</p>
            </div>

            <div id="pendingContainer">
                @if(count($pending) > 0)
                    <div class="grid grid-cols-1 gap-6">
                        @foreach($pending as $request)
                            <div id="request-{{ $request['id'] }}" class="bg-white rounded-xl p-6 shadow-lg hover:shadow-xl transition-all border-l-4 {{ !($request['is_read'] ?? true) ? 'ring-2 ring-yellow-400' : '' }}" style="border-color: #FC9EAC;">
                                <div class="flex flex-col gap-6">
                                    <!-- Header Row -->
                                    <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-4">
                                        <div class="flex items-center gap-4">
                                            <div class="w-14 h-14 rounded-full bg-gradient-to-r from-pink-400 to-teal-400 flex items-center justify-center text-white text-2xl font-bold shadow">
                                                {{ strtoupper(substr($request['name'], 0, 1)) }}
                                            </div>
                                            <div>
                                                <div class="flex items-center gap-2">
                                                    <h3 class="text-xl font-bold text-gray-900">{{ $request['name'] }}</h3>
                                                    @if($request['is_guest'] ?? false)
                                                        <span class="bg-purple-100 text-purple-700 text-xs px-2 py-1 rounded-full font-medium">Guest Application</span>
                                                    @endif
                                                </div>
                                                <p class="text-gray-600 text-sm">{{ $request['email'] }}</p>
                                                @if(!empty($request['phone']))
                                                    <p class="text-gray-500 text-sm">📞 {{ $request['phone'] }}</p>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="flex gap-3 items-center flex-wrap">
                                            <form method="POST" action="{{ route('admin.requests.approve', $request['id']) }}" style="display:inline;">
                                                @csrf
                                                <button type="submit" class="text-white font-semibold py-2 px-5 rounded-lg transition-all shadow-md hover:shadow-lg" style="background-color: #008B8B;" onmouseover="this.style.backgroundColor='#006666'" onmouseout="this.style.backgroundColor='#008B8B'">
                                                    ✓ Approve
                                                </button>
                                            </form>
                                            <button type="button" onclick="toggleRejectForm({{ $request['id'] }})" class="text-white font-semibold py-2 px-5 rounded-lg transition-all shadow-md hover:shadow-lg" style="background-color: #EC769A;" onmouseover="this.style.backgroundColor='#d65f87'" onmouseout="this.style.backgroundColor='#EC769A'">
                                                ✗ Reject
                                            </button>
                                        </div>
                                    </div>

                                    <!-- Rejection Form (Hidden by default) -->
                                    <div id="reject-form-{{ $request['id'] }}" class="hidden bg-red-50 border border-red-200 rounded-lg p-4 mt-4">
                                        <form method="POST" action="{{ route('admin.requests.reject', $request['id']) }}">
                                            @csrf
                                            <label class="block text-sm font-semibold text-red-700 mb-2">
                                                📝 Reason for Rejection
                                            </label>
                                            <textarea 
                                                name="rejection_reason" 
                                                rows="3" 
                                                class="w-full px-4 py-3 border border-red-300 rounded-lg focus:ring-2 focus:ring-red-400 focus:border-red-400 transition-all resize-none"
                                                placeholder="Please provide a reason for rejecting this application..."
                                                required
                                            ></textarea>
                                            <div class="flex gap-3 mt-3">
                                                <button type="submit" class="text-white font-semibold py-2 px-5 rounded-lg transition-all shadow-md hover:shadow-lg bg-red-500 hover:bg-red-600">
                                                    Confirm Rejection
                                                </button>
                                                <button type="button" onclick="toggleRejectForm({{ $request['id'] }})" class="text-gray-700 font-semibold py-2 px-5 rounded-lg transition-all shadow-md hover:shadow-lg bg-gray-200 hover:bg-gray-300">
                                                    Cancel
                                                </button>
                                            </div>
                                        </form>
                                    </div>

                                    <!-- Details Grid -->
                                    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4 text-sm bg-gray-50 rounded-lg p-4">
                                        <div>
                                            <p class="text-gray-500 font-medium mb-1">👤 Age</p>
                                            <p class="font-semibold text-gray-800">{{ $request['age'] ?? 'N/A' }} years</p>
                                        </div>
                                        <div>
                                            <p class="text-gray-500 font-medium mb-1">🌐 Language</p>
                                            <p class="font-semibold text-gray-800">{{ $request['language'] ?? 'N/A' }}</p>
                                        </div>
                                        <div>
                                            <p class="text-gray-500 font-medium mb-1">📚 Specialization</p>
                                            <p class="font-semibold text-gray-800">{{ $request['specialization'] }}</p>
                                        </div>
                                        <div>
                                            <p class="text-gray-500 font-medium mb-1">⏱️ Experience</p>
                                            <p class="font-semibold text-gray-800">{{ $request['experience'] }}</p>
                                        </div>
                                        <div>
                                            <p class="text-gray-500 font-medium mb-1">🎓 University Major</p>
                                            <p class="font-semibold text-gray-800">{{ $request['university_major'] ?? 'N/A' }}</p>
                                        </div>
                                        <div>
                                            <p class="text-gray-500 font-medium mb-1">📅 Applied</p>
                                            <p class="font-semibold text-gray-800">{{ $request['appliedAt'] }}</p>
                                        </div>
                                    </div>

                                    <!-- Courses Done -->
                                    @if(!empty($request['courses_done']))
                                    <div class="bg-gradient-to-r from-pink-50 to-teal-50 rounded-lg p-4">
                                        <p class="text-gray-500 font-medium mb-2">📋 Courses & Certifications</p>
                                        <p class="text-gray-700">{{ $request['courses_done'] }}</p>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="rounded-lg p-12 text-center shadow-lg" style="background-color: #F4F4DD;">
                        <p class="text-lg text-gray-700 font-semibold">✅ All requests have been processed!</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Approved Teachers -->
        <div>
            <div class="mb-6">
                <h2 class="text-3xl font-extrabold mb-2 drop-shadow" style="color: #5cb85c;">✅ Approved Teachers (<span id="approvedCount">{{ count($approved) }}</span>)</h2>
                <p class="text-gray-700 font-medium">Successfully onboarded teachers</p>
            </div>


            <div id="approvedContainer" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($approved as $request)
                    <div class="bg-white border-2 rounded-xl p-6 shadow-lg" style="border-color: #5cb85c;">
                        <div class="flex items-start gap-4">
                            <div class="text-3xl">✅</div>
                            <div class="flex-1">
                                <h3 class="text-lg font-bold text-gray-900 mb-1">{{ $request['name'] }}</h3>
                                <p class="text-gray-700 text-sm">{{ $request['subject'] }}</p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Rejected Applications -->
        <div>
            <div class="mb-6">
                <h2 class="text-3xl font-extrabold mb-2 drop-shadow" style="color: #CCB083;">❌ Rejected (<span id="rejectedCount">{{ count($rejected) }}</span>)</h2>
                <p class="text-gray-700 font-medium">Application history</p>
            </div>

            <div id="rejectedContainer" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($rejected as $request)
                    <div class="bg-white border-2 rounded-xl p-6 shadow-lg" style="border-color: #CCB083;">
                        <div class="flex items-start gap-4">
                            <div class="text-3xl">❌</div>
                            <div class="flex-1">
                                <h3 class="text-lg font-bold text-gray-900 mb-1">{{ $request['name'] }}</h3>
                                <p class="text-gray-700 text-sm">{{ $request['subject'] }}</p>
                                @if(!empty($request['rejection_reason']))
                                    <p class="text-red-600 text-xs mt-2 italic">Reason: {{ $request['rejection_reason'] }}</p>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <script>
        // Toggle rejection form visibility
        function toggleRejectForm(requestId) {
            const form = document.getElementById('reject-form-' + requestId);
            if (form.classList.contains('hidden')) {
                // Hide all other open reject forms first
                document.querySelectorAll('[id^="reject-form-"]').forEach(f => {
                    f.classList.add('hidden');
                });
                form.classList.remove('hidden');
                form.querySelector('textarea').focus();
            } else {
                form.classList.add('hidden');
            }
        }

        // handleApprove removed: form now submits normally and reloads page

        function handleReject(event, requestId, name, subject) {
            event.preventDefault();

            // Remove from pending
            const requestElement = document.getElementById('request-' + requestId);
            requestElement.style.transition = 'all 0.3s ease';
            requestElement.style.opacity = '0';
            requestElement.style.transform = 'translateX(-100px)';

            setTimeout(() => {
                requestElement.remove();
                updatePendingCount(-1);
                checkPendingEmpty();

                // Add to rejected
                addToRejected(name, subject);

                // Submit form to backend
                event.target.submit();
            }, 300);

            return false;
        }

        function addToApproved(name, subject) {
            const approvedContainer = document.getElementById('approvedContainer');
            const newCard = document.createElement('div');
            newCard.className = 'bg-white border-2 rounded-xl p-6 shadow-lg opacity-0 transform scale-95';
            newCard.style.borderColor = '#5cb85c';
            newCard.innerHTML = `
                <div class="flex items-start gap-4">
                    <div class="text-3xl">✅</div>
                    <div class="flex-1">
                        <h3 class="text-lg font-bold text-gray-900 mb-1">${name}</h3>
                        <p class="text-gray-700 text-sm">${subject}</p>
                    </div>
                </div>
            `;

            approvedContainer.appendChild(newCard);

            setTimeout(() => {
                newCard.style.transition = 'all 0.3s ease';
                newCard.style.opacity = '1';
                newCard.style.transform = 'scale(1)';
            }, 50);

            updateApprovedCount(1);
        }

        function addToRejected(name, subject) {
            const rejectedContainer = document.getElementById('rejectedContainer');
            const newCard = document.createElement('div');
            newCard.className = 'bg-white border-2 rounded-xl p-6 shadow-lg opacity-0 transform scale-95';
            newCard.style.borderColor = '#CCB083';
            newCard.innerHTML = `
                <div class="flex items-start gap-4">
                    <div class="text-3xl">❌</div>
                    <div class="flex-1">
                        <h3 class="text-lg font-bold text-gray-900 mb-1">${name}</h3>
                        <p class="text-gray-700 text-sm">${subject}</p>
                    </div>
                </div>
            `;

            rejectedContainer.appendChild(newCard);

            setTimeout(() => {
                newCard.style.transition = 'all 0.3s ease';
                newCard.style.opacity = '1';
                newCard.style.transform = 'scale(1)';
            }, 50);

            updateRejectedCount(1);
        }

        function updatePendingCount(delta) {
            const countElement = document.getElementById('pendingCount');
            const currentCount = parseInt(countElement.textContent);
            countElement.textContent = currentCount + delta;
        }

        function updateApprovedCount(delta) {
            const countElement = document.getElementById('approvedCount');
            const currentCount = parseInt(countElement.textContent);
            countElement.textContent = currentCount + delta;
        }

        function updateRejectedCount(delta) {
            const countElement = document.getElementById('rejectedCount');
            const currentCount = parseInt(countElement.textContent);
            countElement.textContent = currentCount + delta;
        }

        function checkPendingEmpty() {
            const pendingContainer = document.getElementById('pendingContainer');
            const pendingCards = pendingContainer.querySelectorAll('[id^="request-"]');
            
            if (pendingCards.length === 0) {
                pendingContainer.innerHTML = `
                    <div class="rounded-lg p-12 text-center shadow-lg" style="background-color: #F4F4DD;">
                        <p class="text-lg text-gray-700 font-semibold">✅ All requests have been processed!</p>
                    </div>
                `;
            }
        }
    </script>
</div>
@endsection
