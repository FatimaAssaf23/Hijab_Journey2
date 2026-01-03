<nav class="bg-gradient-to-r from-[#FC8EAC] via-[#EC769A] to-[#6EC6C5] shadow-2xl rounded-b-3xl border-b-4 border-[#F8C5C8]" style="box-shadow: 0 8px 32px 0 rgba(236,118,154,0.15);">
    <div class="max-w-7xl mx-auto px-6 sm:px-10 lg:px-16">
        <div class="flex items-center justify-between" style="height: 2.5cm;">
            <!-- Navbar content -->
            <div class="flex-1 flex items-center justify-center">
                <div class="hidden md:flex items-center gap-6" style="backdrop-filter: blur(2px);">
                                <div class="group relative">
                                    <button class="flex items-center gap-2 text-white font-semibold px-3 py-2 rounded-md hover:bg-white/10 transition focus:outline-none">
                                        Contacts
                                        <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                                    </button>
                                    <div class="absolute left-0 mt-2 w-64 bg-white rounded-xl shadow-2xl py-2 invisible group-hover:visible opacity-0 group-hover:opacity-100 transform -translate-y-1 group-hover:translate-y-0 transition-all">
                                        <a href="/meetings" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Meeting</a>
                                        <a href="/group-chat" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Group Chat</a>
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
                    <a href="/grades" class="font-semibold px-4 py-2 rounded-xl transition shadow-sm text-white hover:bg-white/10">Grades</a>
                    <a href="{{ route('teacher.emergency.create') }}" class="font-semibold px-4 py-2 rounded-xl transition shadow-sm text-white hover:bg-[#EC769A] bg-[#EC769A]">Emergency</a>
                @else
                    <a href="{{ route('student.dashboard') }}" class="text-white font-semibold px-4 py-2 rounded-lg hover:bg-white/30 transition" style="order: -1;">Dashboard</a>
                    <a href="/student/assignment" class="text-white font-semibold px-4 py-2 rounded-lg hover:bg-white/30 transition">Assignments</a>
                    <a href="/levels" class="text-white font-semibold px-4 py-2 rounded-lg hover:bg-white/30 transition">Levels</a>
                    <a href="/rewards" class="text-white font-semibold px-4 py-2 rounded-lg hover:bg-white/30 transition">Rewards</a>
                    <a href="/grades" class="text-white font-semibold px-4 py-2 rounded-lg hover:bg-white/30 transition">Grades</a>
                    <a href="/student/games" class="text-white font-semibold px-4 py-2 rounded-lg hover:bg-white/30 transition"><span>🎮</span> Games</a>
                @endif

                <!-- Assignments button removed, now in Tasks dropdown -->
            </div>


                @if(Auth::check() && Auth::user()->role === 'teacher')
                <a href="/progress" class="flex flex-row items-center gap-2 font-semibold px-4 py-2 rounded-xl transition shadow-sm text-white hover:bg-white/10 whitespace-nowrap">
                    <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="#197D8C"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3"/></svg>
                    <span class="whitespace-nowrap">Students</span> <span class="whitespace-nowrap">Progress</span>
                </a>
                @else
                <a href="/progress" class="flex flex-row items-center gap-2 font-semibold px-4 py-2 rounded-xl transition shadow-sm text-white hover:bg-white/10 whitespace-nowrap">
                    <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="#197D8C"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3"/></svg>
                    <span class="whitespace-nowrap">My</span> <span class="whitespace-nowrap">Progress</span>
                </a>
                @endif

            </div>

            <!-- Profile avatar at far right -->
            <div class="flex items-center justify-end flex-1 min-w-fit">
                @auth
                    <div class="relative group flex items-center h-full" x-data="{ showBioModal: false, showPasswordModal: false }">
                        <button class="focus:outline-none flex items-center justify-center w-16 h-16 rounded-full bg-white/90 ring-2 ring-[#7AD7C1] overflow-hidden shadow-lg transition-transform hover:scale-105">
                            <img src="{{ Auth::user()->profile_photo_url ?? '/images/default-avatar.svg' }}" alt="avatar" class="w-14 h-14 rounded-full object-cover" />
                        </button>
                        <!-- Profile dropdown -->
                        <div class="absolute right-0 top-14 w-56 bg-white rounded-xl shadow-2xl py-3 opacity-0 group-hover:opacity-100 invisible group-hover:visible transition-all z-50">
                            <div class="flex items-center gap-3 px-4 py-3 border-b border-gray-100">
                                <img src="{{ Auth::user()->profile_photo_url ?? '/images/default-avatar.svg' }}" alt="avatar" class="w-16 h-16 rounded-full object-cover ring-2 ring-[#7AD7C1]" />
                                <div>
                                    <div class="font-semibold text-gray-800">
                                        @if(Auth::user()->first_name ?? false)
                                            {{ Auth::user()->first_name }} {{ Auth::user()->last_name }}
                                        @else
                                            {{ Auth::user()->name }}
                                        @endif
                                    </div>
                                    <div class="mt-1 text-xs text-gray-600 italic max-w-[180px] truncate" title="{{ \App\Models\User::find(Auth::id())->bio }}">
                                        {{ (\App\Models\User::find(Auth::id())->bio) ? \App\Models\User::find(Auth::id())->bio : 'No bio yet.' }}
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
                <a href="{{ route('student.dashboard') }}" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:bg-gray-100">Dashboard</a>
                <a href="/lessons/manage" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:bg-gray-100">Lessons Management</a>
                <a href="/quizzes" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:bg-gray-100">Quizzes</a>
                <a href="/meetings" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:bg-gray-100">Meetings</a>
                <a href="/grades" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:bg-gray-100">Grades</a>
                <a href="/group-chat" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:bg-gray-100">Contacts</a>
                <a href="/inbox" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:bg-gray-100">Inbox</a>
            @else
                <a href="{{ route('student.dashboard') }}" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:bg-gray-100">Home / Dashboard</a>
                <a href="/levels" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:bg-gray-100">My Class / Levels</a>
                <a href="/lessons" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:bg-gray-100">Lessons</a>
                <a href="/grades" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:bg-gray-100">Reward / Grades</a>
                <a href="/meetings" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:bg-gray-100">Chat / Meeting</a>
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
