
@php
    $isTeacher = false;
    $existingRequest = \App\Models\TeacherRequest::where('user_id', Auth::id())->first();
    if ($existingRequest && $existingRequest->status === 'approved') {
        $isTeacher = true;
    }
@endphp

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Teacher Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if($isTeacher)
                <!-- Success/Error Messages -->
                @if (session('success'))
                    <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg">
                        ✓ {{ session('success') }}
                    </div>
                @endif
                @if (session('error'))
                    <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg">
                        ✗ {{ session('error') }}
                    </div>
                @endif

                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900 dark:text-gray-100">
                        <div class="flex items-center justify-between">
                            <div>
                                <h3 class="text-lg font-semibold">Welcome back, {{ Auth::user()->first_name ?? 'User' }}!</h3>
                                <p class="text-gray-600 dark:text-gray-400">{{ __("You're logged in as a teacher!") }}</p>
                            </div>
                            <div class="flex gap-2">
                                <a href="/lessons" class="text-gray-800 font-semibold px-4 py-2 rounded-lg hover:bg-white/30 transition flex items-center gap-2 {{ request()->is('lessons') ? 'bg-white/30' : '' }}">
                                    <span class="text-xl">📚</span> Lessons
                                </a>
                                <a href="/assignments" class="text-gray-800 font-semibold px-4 py-2 rounded-lg hover:bg-white/30 transition flex items-center gap-2">
                                    <span class="text-xl">📝</span> Assignments
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @else
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg text-center">
                    You do not have access to the Teacher Dashboard.
                </div>
            @endif
        </div>
    </div>

    <!-- Teacher Request Modal -->
    <div id="teacherRequestModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 hidden items-center justify-center p-4">
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-2xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">
            <!-- Modal Header -->
            <div class="bg-gradient-to-r from-pink-500 to-teal-500 p-6 rounded-t-2xl">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-2xl font-bold text-white">👩‍🏫 Become a Teacher</h2>
                        <p class="text-white/80 text-sm mt-1">Fill out the form below to apply</p>
                    </div>
                    <button onclick="closeTeacherRequestModal()" class="text-white hover:bg-white/20 p-2 rounded-lg transition">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Modal Body -->
            <form method="POST" action="{{ route('teacher-request.store') }}" class="p-6 space-y-5">
                @csrf

                <!-- Full Name -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                        Full Name <span class="text-red-500">*</span>
                    </label>
                    <input 
                        type="text" 
                        name="full_name" 
                        value="{{ old('full_name', (Auth::user()->first_name ?? '') . ' ' . (Auth::user()->last_name ?? '')) }}"
                        class="w-full border-2 border-gray-300 dark:border-gray-600 rounded-lg px-4 py-3 focus:outline-none focus:border-pink-500 dark:bg-gray-700 dark:text-white"
                        placeholder="Enter your full name"
                        required
                    >
                    @error('full_name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                <!-- Age -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                        Age <span class="text-red-500">*</span>
                    </label>
                    <input 
                        type="number" 
                        name="age" 
                        value="{{ old('age') }}"
                        min="18"
                        max="100"
                        class="w-full border-2 border-gray-300 dark:border-gray-600 rounded-lg px-4 py-3 focus:outline-none focus:border-pink-500 dark:bg-gray-700 dark:text-white"
                        placeholder="Your age (must be 18+)"
                        required
                    >
                    @error('age') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                <!-- Language -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                        Language <span class="text-red-500">*</span>
                    </label>
                    <select 
                        name="language"
                        class="w-full border-2 border-gray-300 dark:border-gray-600 rounded-lg px-4 py-3 focus:outline-none focus:border-pink-500 dark:bg-gray-700 dark:text-white"
                        required
                    >
                        <option value="">Select your primary teaching language</option>
                        <option value="Arabic" {{ old('language') == 'Arabic' ? 'selected' : '' }}>Arabic</option>
                        <option value="English" {{ old('language') == 'English' ? 'selected' : '' }}>English</option>
                        <option value="French" {{ old('language') == 'French' ? 'selected' : '' }}>French</option>
                        <option value="Urdu" {{ old('language') == 'Urdu' ? 'selected' : '' }}>Urdu</option>
                        <option value="Malay" {{ old('language') == 'Malay' ? 'selected' : '' }}>Malay</option>
                        <option value="Indonesian" {{ old('language') == 'Indonesian' ? 'selected' : '' }}>Indonesian</option>
                        <option value="Turkish" {{ old('language') == 'Turkish' ? 'selected' : '' }}>Turkish</option>
                        <option value="Other" {{ old('language') == 'Other' ? 'selected' : '' }}>Other</option>
                    </select>
                    @error('language') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                <!-- Specialization -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                        Specialization <span class="text-red-500">*</span>
                    </label>
                    <input 
                        type="text" 
                        name="specialization" 
                        value="{{ old('specialization') }}"
                        class="w-full border-2 border-gray-300 dark:border-gray-600 rounded-lg px-4 py-3 focus:outline-none focus:border-pink-500 dark:bg-gray-700 dark:text-white"
                        placeholder="e.g., Quran Recitation, Islamic Studies, Hijab Styling"
                        required
                    >
                    @error('specialization') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                <!-- Experience Years -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                        Years of Experience <span class="text-red-500">*</span>
                    </label>
                    <input 
                        type="number" 
                        name="experience_years" 
                        value="{{ old('experience_years') }}"
                        min="0"
                        max="50"
                        class="w-full border-2 border-gray-300 dark:border-gray-600 rounded-lg px-4 py-3 focus:outline-none focus:border-pink-500 dark:bg-gray-700 dark:text-white"
                        placeholder="Years of teaching experience"
                        required
                    >
                    @error('experience_years') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                <!-- University Major -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                        University Major <span class="text-red-500">*</span>
                    </label>
                    <input 
                        type="text" 
                        name="university_major" 
                        value="{{ old('university_major') }}"
                        class="w-full border-2 border-gray-300 dark:border-gray-600 rounded-lg px-4 py-3 focus:outline-none focus:border-pink-500 dark:bg-gray-700 dark:text-white"
                        placeholder="e.g., Islamic Studies, Education, Arabic Language"
                        required
                    >
                    @error('university_major') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                <!-- Courses Done -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                        Courses & Certifications Completed
                    </label>
                    <textarea 
                        name="courses_done"
                        rows="4"
                        class="w-full border-2 border-gray-300 dark:border-gray-600 rounded-lg px-4 py-3 focus:outline-none focus:border-pink-500 dark:bg-gray-700 dark:text-white"
                        placeholder="List any relevant courses, certifications, or training you've completed (one per line or comma-separated)"
                    >{{ old('courses_done') }}</textarea>
                    @error('courses_done') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                <!-- Submit Buttons -->
                <div class="flex gap-3 pt-4">
                    <button 
                        type="button"
                        onclick="closeTeacherRequestModal()"
                        class="flex-1 bg-gray-300 hover:bg-gray-400 text-gray-800 font-semibold py-3 rounded-lg transition-all"
                    >
                        Cancel
                    </button>
                    <button 
                        type="submit"
                        class="flex-1 bg-gradient-to-r from-pink-500 to-teal-500 hover:from-pink-600 hover:to-teal-600 text-white font-semibold py-3 rounded-lg transition-all shadow-lg"
                    >
                        Submit Request
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openTeacherRequestModal() {
            const modal = document.getElementById('teacherRequestModal');
            modal.classList.remove('hidden');
            modal.classList.add('flex');
            document.body.style.overflow = 'hidden';
        }

        function closeTeacherRequestModal() {
            const modal = document.getElementById('teacherRequestModal');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
            document.body.style.overflow = 'auto';
        }

        // Close modal on backdrop click
        document.getElementById('teacherRequestModal')?.addEventListener('click', function(e) {
            if (e.target === this) {
                closeTeacherRequestModal();
            }
        });

        // Close modal on Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeTeacherRequestModal();
            }
        });
    </script>
</x-app-layout>
