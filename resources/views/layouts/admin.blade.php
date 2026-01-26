<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>Admin - {{ config('app.name', 'Hijab Journey') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        
        <!-- ApexCharts -->
        <script src="https://unpkg.com/apexcharts@3.44.0/dist/apexcharts.min.js" crossorigin="anonymous"></script>
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen flex flex-col bg-gradient-to-br from-pink-50 via-rose-50 to-pink-100">
            <!-- Admin Navbar -->
            <nav class="bg-gradient-to-r from-[#FC8EAC] via-[#EC769A] to-[#6EC6C5] shadow-xl sticky top-0 z-50">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="flex items-center justify-between h-16">
                        <!-- Logo -->
                        <div class="flex items-center gap-4">
                            <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-full bg-white/90 flex items-center justify-center shadow-lg">
                                    <span class="text-xl">🧕</span>
                                </div>
                                <div class="flex flex-col leading-tight">
                                    <span class="font-extrabold text-white text-lg tracking-tight">Hijab Journey</span>
                                    <span class="text-xs text-white/80">Admin Panel</span>
                                </div>
                            </a>
                        </div>

                        <!-- Main Navigation -->
                        <div class="hidden md:flex items-center space-x-1">
                            <!-- Dashboard -->
                            <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-2 text-white font-semibold px-4 py-2 rounded-lg hover:bg-white/20 transition {{ request()->routeIs('admin.dashboard') ? 'bg-white/20' : '' }}">
                                <span>🏠</span>
                                Dashboard
                            </a>

                            <!-- Lessons -->
                            <a href="{{ route('admin.lessons') }}" class="flex items-center gap-2 text-white font-semibold px-4 py-2 rounded-lg hover:bg-white/20 transition {{ request()->routeIs('admin.lessons*') ? 'bg-white/20' : '' }}">
                                <span>📚</span>
                                Lessons
                            </a>

                            <!-- Classes -->
                            <a href="{{ route('admin.classes') }}" class="flex items-center gap-2 text-white font-semibold px-4 py-2 rounded-lg hover:bg-white/20 transition {{ request()->routeIs('admin.classes*') ? 'bg-white/20' : '' }}">
                                <span>🎓</span>
                                Classes
                            </a>


                            <!-- Users Dropdown -->
                            <div class="group relative">
                                <button class="flex items-center gap-2 text-white font-semibold px-4 py-2 rounded-lg hover:bg-white/20 transition {{ request()->routeIs('admin.students*') || request()->routeIs('admin.teachers*') ? 'bg-white/20' : '' }}">
                                    <span>👥</span>
                                    Users
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                </button>
                                <div class="absolute left-0 mt-2 w-48 bg-white rounded-xl shadow-2xl py-2 invisible group-hover:visible opacity-0 group-hover:opacity-100 transform -translate-y-1 group-hover:translate-y-0 transition-all z-50">
                                    <a href="{{ route('admin.students.index') }}" class="flex items-center gap-3 px-4 py-2 text-sm text-gray-700 hover:bg-pink-50">
                                        <span>👧</span> Students
                                    </a>
                                    <a href="{{ route('admin.teachers.index') }}" class="flex items-center gap-3 px-4 py-2 text-sm text-gray-700 hover:bg-pink-50">
                                        <span>👩‍🏫</span> Teachers
                                    </a>
                                </div>
                            </div>

                            <!-- Requests -->
                            <a href="{{ route('admin.requests') }}" class="flex items-center gap-2 text-white font-semibold px-4 py-2 rounded-lg hover:bg-white/20 transition {{ request()->routeIs('admin.requests*') ? 'bg-white/20' : '' }}">
                                <span>📝</span>
                                Requests
                                @php
                                    $pendingCount = \App\Models\TeacherRequest::where('status', 'pending')->count();
                                @endphp
                                @if($pendingCount > 0)
                                    <span class="bg-red-500 text-white text-xs font-bold px-2 py-0.5 rounded-full">{{ $pendingCount }}</span>
                                @endif
                            </a>

                            <!-- Emergency -->
                            <a href="{{ route('admin.emergency.index') }}" class="flex items-center gap-2 text-white font-semibold px-4 py-2 rounded-lg hover:bg-white/20 transition {{ request()->routeIs('admin.emergency.index') ? 'bg-white/20' : '' }}">
                                <span>🚨</span>
                                Emergency
                            </a>

                            <!-- More Dropdown -->
                            <div class="group relative">
                                <button class="flex items-center gap-2 text-white font-semibold px-4 py-2 rounded-lg hover:bg-white/20 transition">
                                    <span>📋</span>
                                    More
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                </button>
                                <div class="absolute right-0 mt-2 w-52 bg-white rounded-xl shadow-2xl py-2 invisible group-hover:visible opacity-0 group-hover:opacity-100 transform -translate-y-1 group-hover:translate-y-0 transition-all z-50">
                                    <a href="{{ route('admin.assignments') }}" class="flex items-center gap-3 px-4 py-2 text-sm text-gray-700 hover:bg-pink-50 {{ request()->routeIs('admin.assignments*') ? 'bg-pink-100' : '' }}">
                                        <span>📄</span> Assignments
                                    </a>
                                    <a href="{{ route('admin.quizzes') }}" class="flex items-center gap-3 px-4 py-2 text-sm text-gray-700 hover:bg-pink-50 {{ request()->routeIs('admin.quizzes*') ? 'bg-pink-100' : '' }}">
                                        <span>❓</span> Quizzes
                                    </a>
                                    <a href="{{ route('admin.games') }}" class="flex items-center gap-3 px-4 py-2 text-sm text-gray-700 hover:bg-pink-50 {{ request()->routeIs('admin.games*') ? 'bg-pink-100' : '' }}">
                                        <span>🎮</span> Games
                                    </a>
                                    <hr class="my-2 border-gray-200">
                                    <a href="#" class="flex items-center gap-3 px-4 py-2 text-sm text-gray-700 hover:bg-pink-50">
                                        <span>💳</span> Payments
                                    </a>
                                    <a href="{{ route('admin.schedule.index') }}" class="flex items-center gap-3 px-4 py-2 text-sm text-gray-700 hover:bg-pink-50 {{ request()->routeIs('admin.schedule*') ? 'bg-pink-100' : '' }}">
                                        <span>📅</span> Schedule
                                    </a>
                                </div>
                            </div>
                        </div>

                        <!-- Right Side - Profile -->
                        <div class="flex items-center gap-4">
                            <!-- Notifications removed as requested -->

                            <!-- Profile Dropdown -->
                            <div class="group relative">
                                @php
                                    $admin = auth()->user();
                                    $adminProfile = \App\Models\AdminProfile::where('user_id', $admin->user_id)->first();
                                    $profilePhoto = $adminProfile && $adminProfile->profile_photo_path ? asset('storage/' . $adminProfile->profile_photo_path) : asset('images/default-profile.png');
                                @endphp
                                <button class="flex items-center gap-2 text-white hover:bg-white/20 px-3 py-2 rounded-lg transition">
                                    <img src="{{ $profilePhoto }}" alt="Profile Photo" class="w-8 h-8 rounded-full object-cover border-2 border-white shadow">
                                    <span class="font-semibold hidden sm:block">{{ $admin->first_name ?? 'Admin' }}</span>
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                </button>
                                <div class="absolute right-0 mt-2 w-64 bg-white rounded-xl shadow-2xl py-2 invisible group-hover:visible opacity-0 group-hover:opacity-100 transform -translate-y-1 group-hover:translate-y-0 transition-all z-50">
                                    <div class="flex flex-col items-center px-4 py-4 border-b border-gray-200">
                                        <img src="{{ $profilePhoto }}" alt="Profile Photo" class="w-16 h-16 rounded-full object-cover border-2 border-pink-300 mb-2">
                                        <div class="font-bold text-gray-800 text-lg">{{ $admin->first_name }} {{ $admin->last_name }}</div>
                                        <div class="text-xs text-gray-500 mb-1">{{ $admin->email }}</div>
                                        @if($adminProfile && $adminProfile->bio)
                                            <div class="text-sm text-gray-700 text-center mt-2">{{ $adminProfile->bio }}</div>
                                        @endif
                                    </div>
                                    <a href="{{ route('admin.profile') }}" class="flex items-center gap-3 px-4 py-2 text-sm text-gray-700 hover:bg-pink-50 {{ request()->routeIs('admin.profile') ? 'bg-pink-100' : '' }}">
                                        <span>👤</span> Profile
                                    </a>
                                    <a href="{{ route('admin.settings') }}" class="flex items-center gap-3 px-4 py-2 text-sm text-gray-700 hover:bg-pink-50 {{ request()->routeIs('admin.settings') ? 'bg-pink-100' : '' }}">
                                        <span>⚙️</span> Settings
                                    </a>
                                    <hr class="my-2 border-gray-200">
                                    <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-4 py-2 text-sm text-gray-700 hover:bg-pink-50">
                                        <span>🏠</span> Back to Dashboard
                                    </a>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="w-full flex items-center gap-3 px-4 py-2 text-sm text-red-600 hover:bg-red-50">
                                            <span>🚪</span> Logout
                                        </button>
                                    </form>
                                </div>
                            </div>

                            <!-- Mobile menu button -->
                            <button id="mobile-menu-btn" class="md:hidden text-white hover:bg-white/20 p-2 rounded-lg transition">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Mobile Menu -->
                <div id="mobile-menu" class="hidden md:hidden bg-pink-700/95 backdrop-blur">
                    <div class="px-4 py-3 space-y-2">
                        <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 text-white px-4 py-3 rounded-lg hover:bg-white/20">
                            <span>🏠</span> Dashboard
                        </a>
                        <a href="{{ route('admin.lessons') }}" class="flex items-center gap-3 text-white px-4 py-3 rounded-lg hover:bg-white/20">
                            <span>📚</span> Lessons
                        </a>
                        <a href="{{ route('admin.classes') }}" class="flex items-center gap-3 text-white px-4 py-3 rounded-lg hover:bg-white/20">
                            <span>🎓</span> Classes
                        </a>
                        <a href="{{ route('admin.students.index') }}" class="flex items-center gap-3 text-white px-4 py-3 rounded-lg hover:bg-white/20">
                            <span>👧</span> Students
                        </a>
                        <a href="{{ route('admin.teachers.index') }}" class="flex items-center gap-3 text-white px-4 py-3 rounded-lg hover:bg-white/20">
                            <span>👩‍🏫</span> Teachers
                        </a>
                        <a href="{{ route('admin.requests') }}" class="flex items-center gap-3 text-white px-4 py-3 rounded-lg hover:bg-white/20">
                            <span>📝</span> Teacher Requests
                        </a>
                        <a href="{{ route('admin.emergency.index') }}" class="flex items-center gap-3 text-white px-4 py-3 rounded-lg hover:bg-white/20">
                            <span>🚨</span> Emergency
                        </a>
                    </div>
                </div>
            </nav>

            <!-- Page Content -->
            <main class="flex-1">
                @yield('content')
            </main>

            <!-- Footer -->
            <footer class="bg-white/50 backdrop-blur border-t border-pink-200">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
                    <div class="flex justify-between items-center text-sm text-gray-600">
                        <span>© {{ date('Y') }} Hijab Journey. Admin Panel</span>
                        <span>Made with 💖</span>
                    </div>
                </div>
            </footer>
        </div>

        <script>
            // Mobile menu toggle
            document.getElementById('mobile-menu-btn')?.addEventListener('click', function() {
                document.getElementById('mobile-menu')?.classList.toggle('hidden');
            });
        </script>
        @stack('scripts')
    </body>
</html>
