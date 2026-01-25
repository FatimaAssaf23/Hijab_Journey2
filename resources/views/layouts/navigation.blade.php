<nav class="bg-gradient-to-r from-[#FC8EAC] via-[#EC769A] to-[#6EC6C5] shadow-2xl rounded-b-3xl border-b-4 border-[#F8C5C8] relative z-50" style="box-shadow: 0 8px 32px 0 rgba(236,118,154,0.15);">
    <div class="max-w-7xl mx-auto px-6 sm:px-10 lg:px-16">
        <div class="flex items-center justify-between" style="height: 2.5cm;">
            <!-- Navbar content -->
            <div class="flex-1 flex items-center justify-center">
                <div class="hidden md:flex items-center gap-6" style="backdrop-filter: blur(2px);">
                                <div class="group relative">
                                    <button class="flex items-center gap-2 text-white font-semibold px-3 py-2 rounded-md hover:bg-white/10 transition focus:outline-none">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                                        </svg>
                                        Contacts
                                        <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                                    </button>
                                    <div class="absolute left-0 mt-2 w-64 bg-white rounded-xl shadow-2xl py-2 invisible group-hover:visible opacity-0 group-hover:opacity-100 transform -translate-y-1 group-hover:translate-y-0 transition-all z-50">
                                        <a href="/meetings" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Meeting</a>
                                        <a href="{{ route('group-chat.index') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Group Chat</a>
                                        <a href="/contact-instructor" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Contact Instructor</a>
                                    </div>
                                </div>
                @if(Auth::check() && Auth::user()->role === 'teacher')
                    <a href="/teacher/dashboard" class="flex items-center gap-3 text-base font-semibold text-white bg-transparent border-0 shadow-none px-0 py-0 order-first">Dashboard</a>
                    <a href="/teacher/classes" class="flex items-center gap-1 text-base font-semibold px-4 py-2 rounded-xl transition shadow-sm text-white hover:bg-white/10">Classes</a>
                    <div class="group relative">
                        <button class="flex items-center gap-1 text-base font-semibold px-4 py-2 rounded-xl transition shadow-sm text-white hover:bg-white/10 focus:outline-none">
                            Tasks
                            <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                        </button>
                        <div class="absolute left-0 mt-2 w-48 bg-white rounded-xl shadow-2xl py-2 invisible group-hover:visible opacity-0 group-hover:opacity-100 transform -translate-y-1 group-hover:translate-y-0 transition-all z-50">
                            <a href="/assignments" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Assignments</a>
                            <a href="/quizzes" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Quizzes</a>
                            <a href="/games" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Games</a>
                        </div>
                    </div>
                    <!-- Games button removed, now in Tasks dropdown -->
                    <a href="{{ route('teacher.lessons.manage') }}" class="flex items-center gap-1 text-base font-semibold px-4 py-2 rounded-xl transition shadow-sm text-white hover:bg-white/10">Lessons Management</a>
                    <!-- Quizzes button removed, now in Tasks dropdown -->
                    <a href="{{ route('teacher.grades') }}" class="font-semibold px-4 py-2 rounded-xl transition shadow-sm text-white hover:bg-white/10">Grades</a>
                    <a href="{{ route('teacher.emergency.create') }}" class="font-semibold px-4 py-2 rounded-xl transition text-white hover:bg-white/10">Emergency</a>
                @else
                    <a href="{{ route('student.dashboard') }}" class="flex items-center gap-2 text-white font-semibold px-4 py-2 rounded-lg hover:bg-white/30 transition" style="order: -1;">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                        </svg>
                        Dashboard
                    </a>
                    <div class="group relative">
                        <button class="flex items-center gap-2 text-white font-semibold px-4 py-2 rounded-lg hover:bg-white/30 transition focus:outline-none">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                            </svg>
                            Tasks
                            <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                        </button>
                        <div class="absolute left-0 mt-2 w-48 bg-white rounded-xl shadow-2xl py-2 invisible group-hover:visible opacity-0 group-hover:opacity-100 transform -translate-y-1 group-hover:translate-y-0 transition-all z-50">
                            <a href="/student/assignment" class="flex items-center gap-2 px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                                Assignments
                            </a>
                            <a href="/student/quizzes" class="flex items-center gap-2 px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                                </svg>
                                Quiz
                            </a>
                        </div>
                    </div>
                    <a href="/levels" class="flex items-center gap-2 text-white font-semibold px-4 py-2 rounded-lg hover:bg-white/30 transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                        </svg>
                        Levels
                    </a>
                    <a href="{{ route('student.rewards') }}" class="flex items-center gap-2 text-white font-semibold px-4 py-2 rounded-lg hover:bg-white/30 transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/>
                        </svg>
                        Rewards
                    </a>
                    <a href="{{ route('student.grades') }}" class="flex items-center gap-2 text-white font-semibold px-4 py-2 rounded-lg hover:bg-white/30 transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/>
                        </svg>
                        Grades
                    </a>
                @endif

                <!-- Assignments button removed, now in Tasks dropdown -->
            </div>


                @if(Auth::check() && Auth::user()->role === 'teacher')
                <a href="{{ route('teacher.progress') }}" class="flex flex-row items-center gap-2 font-semibold px-4 py-2 rounded-xl transition shadow-sm text-white hover:bg-white/10 whitespace-nowrap">
                    <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="#197D8C"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3"/></svg>
                    <span class="whitespace-nowrap">Students</span> <span class="whitespace-nowrap">Progress</span>
                </a>
                @else
                <a href="{{ route('student.progress') }}" class="flex flex-row items-center gap-2 font-semibold px-4 py-2 rounded-xl transition shadow-sm text-white hover:bg-white/10 whitespace-nowrap">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                    </svg>
                    <span class="whitespace-nowrap">My</span> <span class="whitespace-nowrap">Progress</span>
                </a>
                @endif

            </div>

            <!-- Profile avatar at far right -->
            <div class="flex items-center justify-end flex-1 min-w-fit">
                @auth
                    <div class="relative group flex items-center h-full" x-data="{ showBioModal: false, showPasswordModal: false }">
                        <button class="focus:outline-none flex items-center justify-center w-16 h-16 rounded-full bg-white/90 ring-2 ring-[#7AD7C1] overflow-hidden shadow-lg transition-transform hover:scale-105">
                            @if(Auth::user()->role === 'teacher')
                                @php
                                    $teacherProfile = \App\Models\TeacherProfile::where('user_id', Auth::id())->first();
                                    $profilePhoto = $teacherProfile && $teacherProfile->profile_photo_path ? asset('storage/' . $teacherProfile->profile_photo_path) : asset('images/default-avatar.svg');
                                @endphp
                                <img src="{{ $profilePhoto }}" alt="avatar" class="w-14 h-14 rounded-full object-cover" />
                            @else
                                <img src="{{ Auth::user()->profile_photo_url ?? '/images/default-avatar.svg' }}" alt="avatar" class="w-14 h-14 rounded-full object-cover" />
                            @endif
                        </button>
                        <!-- Profile dropdown -->
                        <div class="absolute right-0 top-14 w-56 bg-white rounded-xl shadow-2xl py-3 opacity-0 group-hover:opacity-100 invisible group-hover:visible transition-all z-50">
                            <div class="flex items-center gap-3 px-4 py-3 border-b border-gray-100">
                                @if(Auth::user()->role === 'teacher')
                                    @php
                                        $teacherProfile = \App\Models\TeacherProfile::where('user_id', Auth::id())->first();
                                        $profilePhoto = $teacherProfile && $teacherProfile->profile_photo_path ? asset('storage/' . $teacherProfile->profile_photo_path) : asset('images/default-avatar.svg');
                                    @endphp
                                    <img src="{{ $profilePhoto }}" alt="avatar" class="w-16 h-16 rounded-full object-cover ring-2 ring-[#7AD7C1]" />
                                @else
                                    <img src="{{ Auth::user()->profile_photo_url ?? '/images/default-avatar.svg' }}" alt="avatar" class="w-16 h-16 rounded-full object-cover ring-2 ring-[#7AD7C1]" />
                                @endif
                                <div>
                                    <div class="font-semibold text-gray-800">
                                        @if(Auth::user()->first_name ?? false)
                                            {{ Auth::user()->first_name }} {{ Auth::user()->last_name }}
                                        @else
                                            {{ Auth::user()->name }}
                                        @endif
                                    </div>
                                    <div class="mt-1 text-xs text-gray-600 italic max-w-[180px] truncate" title="{{ Auth::user()->role === 'teacher' ? optional(\App\Models\TeacherProfile::where('user_id', Auth::id())->first())->bio : Auth::user()->bio }}">
                                        {{ Auth::user()->role === 'teacher' ? (optional(\App\Models\TeacherProfile::where('user_id', Auth::id())->first())->bio ?: 'No bio yet.') : (Auth::user()->bio ?: 'No bio yet.') }}
                                    </div>
                                    <a href="{{ route('profile.edit') }}" class="hidden"></a>
                                    <button type="button" @click="showBioModal = true" class="inline-block mt-2 text-xs text-[#7AD7C1] font-medium">My Bio</button>
                                    <!-- My Bio Modal -->
                                    <div x-show="showBioModal" style="display: none;" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-40">
                                        <div class="bg-white rounded-lg shadow-xl p-6 w-full max-w-md relative">
                                            <button @click="showBioModal = false" class="absolute top-2 right-2 text-gray-400 hover:text-gray-700 text-2xl font-bold">&times;</button>
                                            <h3 class="text-lg font-semibold mb-4 text-gray-800">Edit My Bio</h3>
                                            <form method="POST" action="{{ route('profile.update') }}">
                                                @csrf
                                                @method('PATCH')
                                                <input type="hidden" name="name" value="{{ Auth::user()->first_name ? Auth::user()->first_name . ' ' . Auth::user()->last_name : Auth::user()->name }}">
                                                <input type="hidden" name="email" value="{{ Auth::user()->email }}">
                                                <textarea name="bio" rows="5" class="w-full rounded border border-gray-300 p-2 focus:ring focus:ring-[#7AD7C1] focus:border-[#7AD7C1]" placeholder="Write your bio here...">{{ old('bio', Auth::user()->bio) }}</textarea>
                                                <button type="submit" class="mt-4 w-full py-2 bg-[#7AD7C1] text-white rounded shadow hover:bg-[#5ec1a6] transition">Save Bio</button>
                                            </form>
                                        </div>
                                    </div>
                                    <button type="button" @click="showPasswordModal = true" class="inline-block mt-2 text-xs text-[#7AD7C1] font-medium">Manage My Account</button>
                                    <!-- Manage My Account Modal -->
                                    <div x-show="showPasswordModal" style="display: none;" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-40">
                                        <div class="bg-white rounded-lg shadow-xl p-6 w-full max-w-md relative">
                                            <button @click="showPasswordModal = false" class="absolute top-2 right-2 text-gray-400 hover:text-gray-700 text-2xl font-bold">&times;</button>
                                            <h3 class="text-lg font-semibold mb-4 text-gray-800">Change Password</h3>
                                            <form method="POST" action="{{ route('profile.update') }}">
                                                @csrf
                                                @method('PATCH')
                                                <input type="hidden" name="name" value="{{ Auth::user()->first_name ? Auth::user()->first_name . ' ' . Auth::user()->last_name : Auth::user()->name }}">
                                                <input type="hidden" name="email" value="{{ Auth::user()->email }}">
                                                <input type="hidden" name="bio" value="{{ Auth::user()->bio }}">
                                                <div class="mb-4">
                                                    <label for="current_password" class="block text-sm text-gray-700 mb-1">Current Password</label>
                                                    <input id="current_password" name="current_password" type="password" class="w-full rounded border border-gray-300 p-2 focus:ring focus:ring-[#7AD7C1] focus:border-[#7AD7C1]" required>
                                                </div>
                                                <div class="mb-4">
                                                    <label for="password" class="block text-sm text-gray-700 mb-1">New Password</label>
                                                    <input id="password" name="password" type="password" class="w-full rounded border border-gray-300 p-2 focus:ring focus:ring-[#7AD7C1] focus:border-[#7AD7C1]" required>
                                                </div>
                                                <div class="mb-4">
                                                    <label for="password_confirmation" class="block text-sm text-gray-700 mb-1">Confirm New Password</label>
                                                    <input id="password_confirmation" name="password_confirmation" type="password" class="w-full rounded border border-gray-300 p-2 focus:ring focus:ring-[#7AD7C1] focus:border-[#7AD7C1]" required>
                                                </div>
                                                <button type="submit" class="mt-2 w-full py-2 bg-[#7AD7C1] text-white rounded shadow hover:bg-[#5ec1a6] transition">Save Password</button>
                                            </form>
                                        </div>
                                    </div>
                                    <a href="{{ route('profile.photo.form') }}" class="inline-block mt-2 text-xs text-[#7AD7C1] font-medium">Upload profile picture</a>
                                </div>
                            </div>
                            <div class="px-3 py-2">
                                <!-- Logout button moved to navbar -->
                            </div>
                        </div>
                    </div>
                @else
                    <a href="{{ route('login') }}" class="text-white font-medium">Login</a>
                @endauth
                @auth
                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf
                        <button type="submit" class="px-4 py-2 rounded-xl text-base font-semibold text-white bg-gradient-to-r from-pink-400 to-teal-400 hover:bg-pink-500 transition shadow-sm ml-2">Logout</button>
                    </form>
                @endauth
                <!-- Mobile menu button -->
                <div class="md:hidden ml-2">
                    <button onclick="document.getElementById('mobile-menu').classList.toggle('hidden')" class="text-white p-2 rounded-md hover:bg-white/10">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Mobile menu -->
    <div id="mobile-menu" class="md:hidden hidden bg-white">
        <div class="px-4 pt-4 pb-2">
            @auth
                <div class="flex items-center gap-3">
                    <img src="{{ Auth::user()->profile_photo_url ?? '/images/default-avatar.svg' }}" class="w-12 h-12 rounded-full object-cover ring-2 ring-pink-200" />
                    <div>
                        <div class="font-medium">{{ Auth::user()->name }}</div>
                        <div class="text-sm text-gray-500">{{ Auth::user()->email }}</div>
                    </div>
                </div>
            @endauth
        </div>
        <div class="px-2 pb-4 space-y-1">
            @if(Auth::check() && Auth::user()->role === 'teacher')
                <a href="{{ route('teacher.dashboard') }}" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:bg-gray-100">Dashboard</a>
                <a href="/lessons/manage" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:bg-gray-100">Lessons Management</a>
                <a href="/quizzes" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:bg-gray-100">Quizzes</a>
                <a href="/meetings" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:bg-gray-100">Meetings</a>
                <a href="{{ route('teacher.grades') }}" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:bg-gray-100">Grades</a>
                <a href="{{ route('teacher.progress') }}" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:bg-gray-100">Students Progress</a>
                <a href="/group-chat" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:bg-gray-100">Contacts</a>
                <a href="/inbox" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:bg-gray-100">Inbox</a>
            @else
                <a href="{{ route('student.dashboard') }}" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:bg-gray-100">Home / Dashboard</a>
                <a href="/levels" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:bg-gray-100">My Class / Levels</a>
                <a href="/lessons" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:bg-gray-100">Lessons</a>
                <a href="/grades" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:bg-gray-100">Reward / Grades</a>
                <a href="{{ route('student.dashboard') }}" class="flex items-center gap-2 px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:bg-gray-100">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                    </svg>
                    Home / Dashboard
                </a>
                <div class="px-3 py-2 text-base font-medium text-gray-700 border-b border-gray-200 flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                    Tasks
                </div>
                <a href="/student/assignment" class="flex items-center gap-2 px-6 py-2 text-sm text-gray-600 hover:bg-gray-100">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    Assignments
                </a>
                <a href="/student/quizzes" class="flex items-center gap-2 px-6 py-2 text-sm text-gray-600 hover:bg-gray-100">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                    </svg>
                    Quiz
                </a>
                <a href="/levels" class="flex items-center gap-2 px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:bg-gray-100">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                    </svg>
                    My Class / Levels
                </a>
                <a href="/lessons" class="flex items-center gap-2 px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:bg-gray-100">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                    </svg>
                    Lessons
                </a>
                <a href="{{ route('student.grades') }}" class="flex items-center gap-2 px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:bg-gray-100">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/>
                    </svg>
                    Reward / Grades
                </a>
                <a href="/meetings" class="flex items-center gap-2 px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:bg-gray-100">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                    </svg>
                    Chat / Meeting
                </a>
            @endif
            @auth
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full text-left px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:bg-gray-100">Logout</button>
                </form>
            @endauth
        </div>
    </div>
</nav>
