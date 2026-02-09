@extends('layouts.admin')

@section('content')
<div class="min-h-screen" style="background: linear-gradient(135deg, #FFF4FA 0%, #FDF2F8 30%, #F0F9FF 70%, #E0F7FA 100%);">
    <!-- Header -->
    <div class="bg-gradient-to-r from-pink-200/90 via-rose-100/80 to-cyan-200/90 shadow-2xl border-b-4 border-pink-300/50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
            <div class="flex flex-col md:flex-row justify-between items-center gap-6">
                <div class="flex items-center gap-6 text-center md:text-left">
                    <!-- Requests Icon -->
                    <div class="hidden md:flex items-center justify-center w-24 h-24 rounded-3xl bg-gradient-to-br from-pink-500 via-rose-400 to-cyan-500 shadow-2xl transform hover:scale-105 transition-all duration-300 border-4 border-white/50">
                        <div class="text-6xl filter drop-shadow-2xl">✓</div>
                    </div>
                    <div>
                        <h1 class="text-5xl font-extrabold text-gray-800 mb-3 drop-shadow-lg flex items-center gap-4 justify-center md:justify-start">
                            <span class="md:hidden flex items-center justify-center w-20 h-20 rounded-2xl bg-gradient-to-br from-pink-500 via-rose-400 to-cyan-500 shadow-xl border-4 border-white/50">
                                <span class="text-5xl">✓</span>
                            </span>
                            <span class="bg-clip-text text-transparent bg-gradient-to-r from-pink-600 via-rose-500 to-cyan-600">Teacher Requests</span>
                        </h1>
                        <p class="text-gray-700 text-lg font-medium">Review and approve teacher applications</p>
                    </div>
                </div>
            </div>
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

    <!-- Warning Message (for email failures) -->
    @if (session('warning'))
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4">
            <div class="bg-yellow-500 text-white rounded-lg p-4 shadow-lg">
                ⚠️ {{ session('warning') }}
            </div>
        </div>
    @endif

    <!-- Error Message -->
    @if (session('error'))
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4">
            <div class="bg-red-500 text-white rounded-lg p-4 shadow-lg">
                ✗ {{ session('error') }}
            </div>
        </div>
    @endif

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-8">
        <!-- Pending Requests -->
        <div>
            <div class="mb-6">
                <h2 class="text-3xl font-extrabold mb-2 bg-clip-text text-transparent bg-gradient-to-r from-pink-600 via-rose-500 to-cyan-600">⏳ Pending Requests (<span id="pendingCount">{{ count($pending) }}</span>)</h2>
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

                                    <!-- Certification Picture -->
                                    @if(!empty($request['certification_file']))
                                    <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-lg p-4">
                                        <p class="text-gray-500 font-medium mb-3">📷 Certification Picture</p>
                                        @php
                                            $fileExtension = strtolower(pathinfo($request['certification_file'], PATHINFO_EXTENSION));
                                            $isImage = in_array($fileExtension, ['jpg', 'jpeg', 'png', 'gif']);
                                            $fileUrl = asset('storage/' . $request['certification_file']);
                                        @endphp
                                        <button 
                                            type="button"
                                            onclick="openCertificateModal('{{ $fileUrl }}', {{ $isImage ? 'true' : 'false' }})"
                                            class="inline-flex items-center gap-2 text-white font-semibold py-2 px-5 rounded-lg transition-all shadow-md hover:shadow-lg"
                                            style="background-color: #008B8B;"
                                            onmouseover="this.style.backgroundColor='#006666'"
                                            onmouseout="this.style.backgroundColor='#008B8B'"
                                        >
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                            </svg>
                                            View Certificate
                                        </button>
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
                <h2 class="text-3xl font-extrabold mb-2 bg-clip-text text-transparent bg-gradient-to-r from-green-600 via-emerald-500 to-teal-600">✅ Approved Teachers (<span id="approvedCount">{{ count($approved) }}</span>)</h2>
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
                <h2 class="text-3xl font-extrabold mb-2 bg-clip-text text-transparent bg-gradient-to-r from-orange-600 via-amber-500 to-yellow-600">❌ Rejected (<span id="rejectedCount">{{ count($rejected) }}</span>)</h2>
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

    <!-- Certificate Modal -->
    <div id="certificateModal" class="fixed inset-0 bg-black bg-opacity-90 z-50 hidden flex items-center justify-center">
        <div class="bg-white w-full h-full flex flex-col">
            <!-- Modal Header -->
            <div class="flex items-center justify-between p-4 border-b border-gray-200 bg-gradient-to-r from-blue-50 to-indigo-50 shadow-sm">
                <h3 class="text-xl font-bold text-gray-900">📷 Certification Certificate</h3>
                <button 
                    onclick="closeCertificateModal()" 
                    class="text-gray-500 hover:text-gray-700 transition-colors p-2 hover:bg-gray-100 rounded-lg"
                >
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            <!-- Modal Content -->
            <div class="flex-1 overflow-auto bg-gray-100 flex items-center justify-center p-4" style="min-height: 0;">
                <div id="certificateContent" class="w-full h-full flex items-center justify-center">
                    <!-- Content will be loaded here -->
                </div>
            </div>
            <!-- Modal Footer -->
            <div class="p-4 border-t border-gray-200 bg-gray-50 flex justify-end gap-3 shadow-sm">
                <a 
                    id="certificateDownloadLink" 
                    href="#" 
                    target="_blank" 
                    download
                    class="inline-flex items-center gap-2 bg-blue-500 hover:bg-blue-600 text-white font-semibold py-2 px-4 rounded-lg transition-all shadow-md hover:shadow-lg"
                >
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                    </svg>
                    Download
                </a>
                <button 
                    onclick="closeCertificateModal()" 
                    class="bg-gray-500 hover:bg-gray-600 text-white font-semibold py-2 px-4 rounded-lg transition-all shadow-md hover:shadow-lg"
                >
                    Close
                </button>
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

        // Certificate Modal Functions
        function openCertificateModal(fileUrl, isImage) {
            const modal = document.getElementById('certificateModal');
            const content = document.getElementById('certificateContent');
            const downloadLink = document.getElementById('certificateDownloadLink');
            
            // Set download link
            downloadLink.href = fileUrl;
            
            // Clear previous content
            content.innerHTML = '';
            
            if (isImage) {
                // Display image at full size, properly scaled to fit screen
                const img = document.createElement('img');
                img.src = fileUrl;
                img.alt = 'Certification Certificate';
                img.className = 'max-w-full max-h-full w-auto h-auto rounded-lg shadow-2xl';
                img.style.objectFit = 'contain';
                img.style.display = 'block';
                img.style.margin = '0 auto';
                
                // Ensure image loads and fits properly
                img.onload = function() {
                    // Calculate available space (viewport minus header and footer)
                    const availableHeight = window.innerHeight - 120; // Account for header and footer
                    const availableWidth = window.innerWidth - 40; // Account for padding
                    
                    // Set max dimensions to fit screen while maintaining aspect ratio
                    if (this.naturalWidth > availableWidth || this.naturalHeight > availableHeight) {
                        const widthRatio = availableWidth / this.naturalWidth;
                        const heightRatio = availableHeight / this.naturalHeight;
                        const ratio = Math.min(widthRatio, heightRatio);
                        
                        this.style.maxWidth = (this.naturalWidth * ratio) + 'px';
                        this.style.maxHeight = (this.naturalHeight * ratio) + 'px';
                    } else {
                        // If image is smaller than screen, show at natural size
                        this.style.maxWidth = this.naturalWidth + 'px';
                        this.style.maxHeight = this.naturalHeight + 'px';
                    }
                };
                
                content.appendChild(img);
            } else {
                // Display PDF in iframe - full height
                const iframe = document.createElement('iframe');
                iframe.src = fileUrl;
                iframe.className = 'w-full h-full rounded-lg shadow-lg border border-gray-300';
                iframe.style.minHeight = 'calc(100vh - 120px)';
                content.appendChild(iframe);
            }
            
            // Show modal
            modal.classList.remove('hidden');
            document.body.style.overflow = 'hidden'; // Prevent background scrolling
        }

        function closeCertificateModal() {
            const modal = document.getElementById('certificateModal');
            modal.classList.add('hidden');
            document.body.style.overflow = ''; // Restore scrolling
            
            // Clear content
            const content = document.getElementById('certificateContent');
            content.innerHTML = '';
        }

        // Close modal when clicking outside the content
        document.getElementById('certificateModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeCertificateModal();
            }
        });

        // Close modal with Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                const modal = document.getElementById('certificateModal');
                if (!modal.classList.contains('hidden')) {
                    closeCertificateModal();
                }
            }
        });
    </script>
</div>
@endsection
