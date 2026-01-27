@extends('layouts.admin')

@section('content')
<div x-data="settingsPage()" class="max-w-7xl mx-auto mt-10 px-4">
    <!-- Header with Search and Quick Actions -->
    <div class="bg-gradient-to-r from-[#F8C5C8] via-[#FC8EAC] to-[#F8C5C8] rounded-2xl shadow-2xl p-8 mb-8">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h1 class="text-4xl font-extrabold text-[#2d3748] mb-2 flex items-center gap-3">
                    <span class="text-5xl">⚙️</span>
                    <span>Admin Settings</span>
                </h1>
                <p class="text-[#4a5568] text-lg">Control and moderate your website with powerful settings</p>
            </div>
            <div class="flex gap-3 flex-wrap">
                <button @click="enableAllNotifications()" class="bg-white/80 hover:bg-white text-[#2d3748] px-4 py-2 rounded-lg font-semibold transition-all backdrop-blur-sm shadow-md hover:shadow-lg">
                    🔔 Enable All Notifications
                </button>
                <button @click="disableAllNotifications()" class="bg-white/80 hover:bg-white text-[#2d3748] px-4 py-2 rounded-lg font-semibold transition-all backdrop-blur-sm shadow-md hover:shadow-lg">
                    🔕 Disable All Notifications
                </button>
            </div>
        </div>
        
        <!-- Search Bar -->
        <div class="mt-6">
            <div class="relative">
                <input type="text" 
                       x-model="searchQuery" 
                       @input="filterSettings()"
                       placeholder="🔍 Search settings..." 
                       class="w-full bg-white/95 backdrop-blur-sm border-2 border-white/70 rounded-xl px-6 py-4 pl-12 text-gray-800 placeholder-gray-500 focus:outline-none focus:ring-4 focus:ring-white/70 focus:border-white shadow-md">
                <svg class="absolute left-4 top-1/2 transform -translate-y-1/2 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div x-data="{ show: true }" 
             x-show="show" 
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 transform translate-y-2"
             x-transition:enter-end="opacity-100 transform translate-y-0"
             class="mb-6 p-4 bg-gradient-to-r from-green-100 to-emerald-100 border-2 border-green-400 text-green-800 rounded-xl shadow-lg">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                    </svg>
                    <span class="font-semibold">{{ session('success') }}</span>
                </div>
                <button @click="show = false" class="text-green-700 font-bold hover:text-green-900 text-xl">&times;</button>
            </div>
        </div>
    @endif

    @if($errors->any())
        <div class="mb-6 p-4 bg-red-100 border-2 border-red-400 text-red-800 rounded-xl shadow-lg">
            <ul class="list-disc list-inside">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Toast Notification -->
    <div x-show="showNotification" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 transform translate-y-2"
         x-transition:enter-end="opacity-100 transform translate-y-0"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 transform translate-y-0"
         x-transition:leave-end="opacity-0 transform translate-y-2"
         class="fixed top-4 right-4 bg-gradient-to-r from-blue-500 to-cyan-500 text-white px-6 py-4 rounded-xl shadow-2xl z-50 max-w-md"
         style="display: none;">
        <div class="flex items-center gap-3">
            <div class="flex-1">
                <p x-text="notificationMessage" class="font-semibold"></p>
            </div>
            <button @click="showNotification = false" class="text-white hover:text-gray-200 font-bold text-xl">&times;</button>
        </div>
    </div>

    <form method="POST" action="{{ route('admin.settings.update') }}" id="settingsForm" @submit="handleSubmit($event)">
        @csrf
        @method('POST')

        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
            @php
                $userSettings = $settings->get('user_management', collect());
                $notificationSettings = $settings->get('notifications', collect());
                $enabledNotifications = $notificationSettings->where('setting_value', '1')->count();
            @endphp
            <div class="bg-[#B5D7D5] rounded-xl p-4 border-2 border-[#B5D7D5]/50 shadow-lg hover:shadow-xl transition-all">
                <div class="text-3xl font-bold text-[#2d3748]">{{ $enabledNotifications }}/{{ $notificationSettings->count() }}</div>
                <div class="text-sm text-[#4a5568] font-semibold">Notifications Enabled</div>
            </div>
            <div class="bg-[#FFB9C6] rounded-xl p-4 border-2 border-[#FFB9C6]/50 shadow-lg hover:shadow-xl transition-all">
                <div class="text-3xl font-bold text-[#2d3748]">{{ $userSettings->where('setting_key', 'max_students_per_class')->first()->setting_value ?? '30' }}</div>
                <div class="text-sm text-[#4a5568] font-semibold">Max Students/Class</div>
            </div>
            <div class="bg-[#79BDBC] rounded-xl p-4 border-2 border-[#79BDBC]/50 shadow-lg hover:shadow-xl transition-all">
                <div class="text-3xl font-bold text-white">{{ $userSettings->where('setting_key', 'max_classes_per_teacher')->first()->setting_value ?? '5' }}</div>
                <div class="text-sm text-white/90 font-semibold">Max Classes/Teacher</div>
            </div>
        </div>

        <!-- User Management Settings -->
        <div x-show="shouldShowCategory('user_management', 'User Management', 'Control user registration, applications, and limits')" 
             x-transition
             class="mb-8 bg-gradient-to-br from-white to-[#F8C5C8]/20 rounded-2xl shadow-xl p-8 border-2 border-[#F8C5C8]/30 hover:shadow-2xl transition-all">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h2 class="text-2xl font-bold text-gray-800 mb-2 flex items-center gap-3">
                        <span class="text-3xl">👥</span>
                        <span>User Management</span>
                    </h2>
                    <p class="text-gray-600">Control user registration, applications, and limits</p>
                </div>
                <button type="button" @click="toggleCategory('user_management')" class="text-gray-500 hover:text-gray-700">
                    <svg class="w-6 h-6 transform transition-transform" :class="{ 'rotate-180': !categories.user_management }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </button>
            </div>
            
            <div x-show="categories.user_management && showCategory('user_management')" 
                 data-category="user_management"
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 class="space-y-6">
                @foreach($settings->get('user_management', collect()) as $setting)
                    <div x-show="matchesSearch('{{ $setting->setting_key }}', '{{ $setting->description }}')" 
                         data-setting-key="{{ $setting->setting_key }}"
                         data-setting-description="{{ $setting->description }}"
                         x-transition
                         class="bg-gradient-to-r from-gray-50 to-gray-100 rounded-xl p-6 border-2 border-gray-200 hover:border-pink-300 hover:shadow-lg transition-all">
                        @if($setting->setting_type === 'boolean')
                            <div class="flex items-center justify-between">
                                <div class="flex-1">
                                    <div class="flex items-center gap-3 mb-2">
                                        <label class="text-lg font-bold text-gray-800">
                                            {{ ucwords(str_replace('_', ' ', $setting->setting_key)) }}
                                        </label>
                                        <div class="relative group">
                                            <svg class="w-5 h-5 text-gray-400 cursor-help" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-8-3a1 1 0 00-.867.5 1 1 0 11-1.731-1A3 3 0 0113 8a3.001 3.001 0 01-2 2.83V11a1 1 0 11-2 0v-1a1 1 0 011-1 1 1 0 100-2zm0 8a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd"></path>
                                            </svg>
                                            <div class="absolute bottom-full left-1/2 transform -translate-x-1/2 mb-2 w-64 bg-gray-900 text-white text-xs rounded-lg p-3 opacity-0 group-hover:opacity-100 transition-opacity pointer-events-none z-10">
                                                {{ $setting->description }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <label class="relative inline-flex items-center cursor-pointer ml-4">
                                    <input type="checkbox" name="{{ $setting->setting_key }}" value="1" 
                                           {{ $setting->setting_value == '1' ? 'checked' : '' }}
                                           class="sr-only peer">
                                    <div class="w-14 h-7 bg-gray-300 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-pink-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-6 after:w-6 after:transition-all peer-checked:bg-gradient-to-r peer-checked:from-[#FC8EAC] peer-checked:to-[#6EC6C5]"></div>
                                </label>
                            </div>
                        @else
                            <label class="block text-lg font-bold text-gray-800 mb-3">
                                {{ ucwords(str_replace('_', ' ', $setting->setting_key)) }}
                            </label>
                            @if($setting->description)
                                <p class="text-sm text-gray-600 mb-3 flex items-center gap-2">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                                    </svg>
                                    {{ $setting->description }}
                                </p>
                            @endif
                            <div class="relative">
                                <input type="{{ $setting->setting_type === 'integer' ? 'number' : 'text' }}" 
                                       name="{{ $setting->setting_key }}" 
                                       value="{{ $setting->setting_value }}"
                                       class="w-full border-2 border-gray-300 rounded-xl px-4 py-3 focus:outline-none focus:border-pink-500 focus:ring-4 focus:ring-pink-200 transition-all"
                                       @if($setting->setting_type === 'integer') min="1" @endif>
                                @if($setting->setting_key === 'max_students_per_class')
                                    <div class="mt-2">
                                        <div class="flex justify-between text-xs text-gray-600 mb-1">
                                            <span>Current: {{ $setting->setting_value }}</span>
                                            <span>Recommended: 20-30</span>
                                        </div>
                                        <div class="w-full bg-gray-200 rounded-full h-2">
                                            <div class="bg-gradient-to-r from-pink-400 to-pink-600 h-2 rounded-full" style="width: {{ min(100, ($setting->setting_value / 50) * 100) }}%"></div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Notification Settings -->
        <div x-show="shouldShowCategory('notifications', 'Notifications', 'Configure email notifications and alerts')" 
             x-transition
             class="mb-8 bg-white rounded-2xl shadow-xl p-8 border-2 border-gray-100 hover:shadow-2xl transition-all">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h2 class="text-2xl font-bold text-gray-800 mb-2 flex items-center gap-3">
                        <span class="text-3xl">🔔</span>
                        <span>Notifications</span>
                    </h2>
                    <p class="text-gray-600">Configure email notifications and alerts</p>
                </div>
                <button type="button" @click="toggleCategory('notifications')" class="text-gray-500 hover:text-gray-700">
                    <svg class="w-6 h-6 transform transition-transform" :class="{ 'rotate-180': !categories.notifications }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </button>
            </div>
            
            <div x-show="categories.notifications && showCategory('notifications')" 
                 data-category="notifications"
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 class="space-y-6">
                @foreach($settings->get('notifications', collect()) as $setting)
                    <div x-show="matchesSearch('{{ $setting->setting_key }}', '{{ $setting->description }}')" 
                         data-setting-key="{{ $setting->setting_key }}"
                         data-setting-description="{{ $setting->description }}"
                         x-transition
                         class="bg-gradient-to-r from-yellow-50 to-orange-50 rounded-xl p-6 border-2 border-yellow-200 hover:border-yellow-400 hover:shadow-lg transition-all">
                        <div class="flex items-center justify-between">
                            <div class="flex-1">
                                <div class="flex items-center gap-3 mb-2">
                                    <label class="text-lg font-bold text-gray-800">
                                        {{ ucwords(str_replace('_', ' ', $setting->setting_key)) }}
                                    </label>
                                    <span class="px-2 py-1 bg-yellow-100 text-yellow-700 text-xs font-semibold rounded-full">Alert</span>
                                </div>
                                <p class="text-sm text-gray-600">{{ $setting->description }}</p>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer ml-4">
                                <input type="checkbox" name="{{ $setting->setting_key }}" value="1" 
                                       {{ $setting->setting_value == '1' ? 'checked' : '' }}
                                       class="sr-only peer">
                                <div class="w-14 h-7 bg-gray-300 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-yellow-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-6 after:w-6 after:transition-all peer-checked:bg-gradient-to-r peer-checked:from-yellow-400 peer-checked:to-orange-500"></div>
                            </label>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- System Settings -->
        <div x-show="shouldShowCategory('system', 'System Settings', 'General system configuration')" 
             x-transition
             class="mb-8 bg-white rounded-2xl shadow-xl p-8 border-2 border-gray-100 hover:shadow-2xl transition-all">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h2 class="text-2xl font-bold text-gray-800 mb-2 flex items-center gap-3">
                        <span class="text-3xl">🖥️</span>
                        <span>System Settings</span>
                    </h2>
                    <p class="text-gray-600">General system configuration</p>
                </div>
                <button type="button" @click="toggleCategory('system')" class="text-gray-500 hover:text-gray-700">
                    <svg class="w-6 h-6 transform transition-transform" :class="{ 'rotate-180': !categories.system }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </button>
            </div>
            
            <div x-show="categories.system && showCategory('system')" 
                 data-category="system"
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 class="space-y-6">
                @foreach($settings->get('system', collect()) as $setting)
                    <div x-show="matchesSearch('{{ $setting->setting_key }}', '{{ $setting->description }}')" 
                         data-setting-key="{{ $setting->setting_key }}"
                         data-setting-description="{{ $setting->description }}"
                         x-transition
                         class="bg-gradient-to-r from-purple-50 to-indigo-50 rounded-xl p-6 border-2 border-purple-200 hover:border-purple-400 hover:shadow-lg transition-all">
                        @if($setting->setting_type === 'boolean')
                            <div class="flex items-center justify-between">
                                <div class="flex-1">
                                    <div class="flex items-center gap-3 mb-2">
                                        <label class="text-lg font-bold text-gray-800">
                                            {{ ucwords(str_replace('_', ' ', $setting->setting_key)) }}
                                        </label>
                                        @if($setting->setting_key === 'site_maintenance_mode')
                                            <span class="px-2 py-1 bg-red-100 text-red-700 text-xs font-semibold rounded-full">⚠️ Critical</span>
                                        @endif
                                    </div>
                                    <p class="text-sm text-gray-600">{{ $setting->description }}</p>
                                </div>
                                <label class="relative inline-flex items-center cursor-pointer ml-4">
                                    <input type="checkbox" name="{{ $setting->setting_key }}" value="1" 
                                           {{ $setting->setting_value == '1' ? 'checked' : '' }}
                                           class="sr-only peer">
                                    <div class="w-14 h-7 bg-gray-300 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-purple-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-6 after:w-6 after:transition-all peer-checked:bg-gradient-to-r peer-checked:from-purple-500 peer-checked:to-indigo-500"></div>
                                </label>
                            </div>
                        @else
                            <label class="block text-lg font-bold text-gray-800 mb-3">
                                {{ ucwords(str_replace('_', ' ', $setting->setting_key)) }}
                            </label>
                            <p class="text-sm text-gray-600 mb-3">{{ $setting->description }}</p>
                            <input type="{{ $setting->setting_type === 'integer' ? 'number' : 'text' }}" 
                                   name="{{ $setting->setting_key }}" 
                                   value="{{ $setting->setting_value }}"
                                   class="w-full border-2 border-gray-300 rounded-xl px-4 py-3 focus:outline-none focus:border-purple-500 focus:ring-4 focus:ring-purple-200 transition-all"
                                   @if($setting->setting_type === 'integer') min="1" @endif>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Security Settings (Danger Zone) -->
        <div x-show="shouldShowCategory('security', 'Security Settings', 'Configure security and authentication')" 
             x-transition
             class="mb-8 bg-gradient-to-br from-red-50 to-pink-50 rounded-2xl shadow-xl p-8 border-4 border-red-200 hover:shadow-2xl transition-all">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h2 class="text-2xl font-bold text-gray-800 mb-2 flex items-center gap-3">
                        <span class="text-3xl">🔒</span>
                        <span>Security Settings</span>
                        <span class="px-3 py-1 bg-red-500 text-white text-sm font-bold rounded-full">Danger Zone</span>
                    </h2>
                    <p class="text-gray-600">Configure security and authentication</p>
                </div>
                <button type="button" @click="toggleCategory('security')" class="text-gray-500 hover:text-gray-700">
                    <svg class="w-6 h-6 transform transition-transform" :class="{ 'rotate-180': !categories.security }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </button>
            </div>
            
            <div x-show="categories.security && showCategory('security')" 
                 data-category="security"
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 class="space-y-6">
                @foreach($settings->get('security', collect()) as $setting)
                    <div x-show="matchesSearch('{{ $setting->setting_key }}', '{{ $setting->description }}')" 
                         data-setting-key="{{ $setting->setting_key }}"
                         data-setting-description="{{ $setting->description }}"
                         x-transition
                         class="bg-white rounded-xl p-6 border-2 border-red-200 hover:border-red-400 hover:shadow-lg transition-all">
                        <label class="block text-lg font-bold text-gray-800 mb-3">
                            {{ ucwords(str_replace('_', ' ', $setting->setting_key)) }}
                        </label>
                        <p class="text-sm text-gray-600 mb-3 flex items-center gap-2">
                            <svg class="w-4 h-4 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                            </svg>
                            {{ $setting->description }}
                        </p>
                        <input type="number" 
                               name="{{ $setting->setting_key }}" 
                               value="{{ $setting->setting_value }}"
                               class="w-full border-2 border-red-300 rounded-xl px-4 py-3 focus:outline-none focus:border-red-500 focus:ring-4 focus:ring-red-200 transition-all"
                               min="1">
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="bg-white rounded-2xl shadow-xl p-6 border-2 border-gray-100">
            <div class="flex items-center justify-between flex-wrap gap-4">
                <a href="{{ route('admin.dashboard') }}" class="text-gray-600 hover:text-gray-800 font-medium flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Back to Dashboard
                </a>
                <div class="flex gap-3">
                    <button type="button" 
                            @click="resetForm()" 
                            class="bg-gray-200 hover:bg-gray-300 text-gray-800 px-6 py-3 rounded-xl font-semibold transition-all transform hover:scale-105">
                        🔄 Reset Changes
                    </button>
                    <button type="submit" 
                            id="saveButton"
                            class="bg-gradient-to-r from-[#FC8EAC] via-[#EC769A] to-[#6EC6C5] text-white px-8 py-3 rounded-xl font-bold shadow-lg hover:shadow-2xl transition-all transform hover:scale-105 disabled:opacity-50 disabled:cursor-not-allowed">
                        💾 Save All Settings
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>

@push('scripts')
<script>
function settingsPage() {
    return {
        searchQuery: '',
        categories: {
            user_management: true,
            notifications: true,
            system: true,
            security: true
        },
        showNotification: false,
        notificationMessage: '',
        
        init() {
            // Initialize any default behavior
        },
        
        showCategory(category) {
            // Always respect the category toggle state
            // Auto-expansion is handled in shouldShowCategory via data attribute checking
            return this.categories[category];
        },
        
        shouldShowCategory(categoryKey, categoryName, categoryDescription) {
            if (!this.searchQuery || !this.searchQuery.trim()) {
                return true; // Show all categories when not searching
            }
            
            const query = this.searchQuery.toLowerCase().trim();
            let shouldShow = false;
            
            // Check if category name or description matches search
            if (this.matchesCategorySearch(categoryKey, categoryName, categoryDescription)) {
                shouldShow = true;
                // Auto-expand categories that match by name
                if (!this.categories[categoryKey]) {
                    this.categories[categoryKey] = true;
                }
            }
            
            // Check if any settings in this category match
            if (!shouldShow) {
                const categoryContainer = document.querySelector(`[data-category="${categoryKey}"]`);
                if (categoryContainer) {
                    const settings = categoryContainer.querySelectorAll('[data-setting-key]');
                    for (let setting of settings) {
                        const key = (setting.getAttribute('data-setting-key') || '').toLowerCase();
                        const desc = (setting.getAttribute('data-setting-description') || '').toLowerCase();
                        if (key.includes(query) || desc.includes(query)) {
                            shouldShow = true;
                            // Auto-expand categories that have matching settings
                            if (!this.categories[categoryKey]) {
                                this.categories[categoryKey] = true;
                            }
                            break;
                        }
                    }
                }
            }
            
            return shouldShow;
        },
        
        matchesCategorySearch(categoryKey, categoryName = '', categoryDescription = '') {
            if (!this.searchQuery) return true;
            const query = this.searchQuery.toLowerCase();
            
            // Map category keys to their display names if not provided
            const categoryNames = {
                'user_management': 'User Management',
                'notifications': 'Notifications',
                'system': 'System Settings',
                'security': 'Security Settings'
            };
            
            const name = categoryName || categoryNames[categoryKey] || categoryKey;
            const desc = categoryDescription || '';
            
            return name.toLowerCase().includes(query) || desc.toLowerCase().includes(query);
        },
        
        toggleCategory(category) {
            this.categories[category] = !this.categories[category];
        },
        
        matchesSearch(key, description) {
            if (!this.searchQuery) return true;
            const query = this.searchQuery.toLowerCase();
            return key.toLowerCase().includes(query) || description.toLowerCase().includes(query);
        },
        
        filterSettings() {
            // Settings are filtered via x-show in the template
            // Force Alpine to re-evaluate
            this.$nextTick(() => {
                // This ensures the DOM updates
            });
        },
        
        showToast(message) {
            this.notificationMessage = message;
            this.showNotification = true;
            setTimeout(() => {
                this.showNotification = false;
            }, 3000);
        },
        
        enableAllNotifications() {
            try {
                // Find all notification-related checkboxes
                const notificationSelectors = [
                    'input[name="email_notifications_enabled"]',
                    'input[name="notify_admin_on_teacher_requests"]',
                    'input[name="notify_admin_on_emergency_requests"]',
                    'input[name="notify_admin_on_new_registrations"]'
                ];
                
                let count = 0;
                notificationSelectors.forEach(selector => {
                    const checkbox = document.querySelector(selector);
                    if (checkbox) {
                        checkbox.checked = true;
                        count++;
                    }
                });
                
                if (count > 0) {
                    this.showToast(`✅ Enabled ${count} notification setting(s)! Don't forget to save.`);
                } else {
                    this.showToast('⚠️ No notification settings found.');
                }
            } catch (error) {
                console.error('Error enabling notifications:', error);
                this.showToast('❌ Error enabling notifications. Please try again.');
            }
        },
        
        disableAllNotifications() {
            try {
                // Find all notification-related checkboxes
                const notificationSelectors = [
                    'input[name="email_notifications_enabled"]',
                    'input[name="notify_admin_on_teacher_requests"]',
                    'input[name="notify_admin_on_emergency_requests"]',
                    'input[name="notify_admin_on_new_registrations"]'
                ];
                
                let count = 0;
                notificationSelectors.forEach(selector => {
                    const checkbox = document.querySelector(selector);
                    if (checkbox) {
                        checkbox.checked = false;
                        count++;
                    }
                });
                
                if (count > 0) {
                    this.showToast(`✅ Disabled ${count} notification setting(s)! Don't forget to save.`);
                } else {
                    this.showToast('⚠️ No notification settings found.');
                }
            } catch (error) {
                console.error('Error disabling notifications:', error);
                this.showToast('❌ Error disabling notifications. Please try again.');
            }
        },
        
        enableAllModeration() {
            try {
                // Find all moderation-related checkboxes
                const moderationSelectors = [
                    'input[name="require_approval_for_lessons"]',
                    'input[name="require_approval_for_assignments"]',
                    'input[name="require_approval_for_quizzes"]',
                    'input[name="require_approval_for_games"]'
                ];
                
                let count = 0;
                moderationSelectors.forEach(selector => {
                    const checkbox = document.querySelector(selector);
                    if (checkbox) {
                        checkbox.checked = true;
                        count++;
                    }
                });
                
                if (count > 0) {
                    this.showToast(`✅ Enabled ${count} moderation rule(s)! Don't forget to save.`);
                } else {
                    this.showToast('⚠️ No moderation settings found.');
                }
            } catch (error) {
                console.error('Error enabling moderation:', error);
                this.showToast('❌ Error enabling moderation. Please try again.');
            }
        },
        
        applyPreset(preset) {
            try {
                const presets = {
                    strict: {
                        'require_approval_for_lessons': true,
                        'require_approval_for_assignments': true,
                        'require_approval_for_quizzes': true,
                        'require_approval_for_games': true,
                        'auto_approve_teachers': false,
                        'require_email_verification': true
                    },
                    balanced: {
                        'require_approval_for_lessons': false,
                        'require_approval_for_assignments': true,
                        'require_approval_for_quizzes': true,
                        'require_approval_for_games': false,
                        'auto_approve_teachers': false,
                        'require_email_verification': false
                    },
                    open: {
                        'require_approval_for_lessons': false,
                        'require_approval_for_assignments': false,
                        'require_approval_for_quizzes': false,
                        'require_approval_for_games': false,
                        'auto_approve_teachers': true,
                        'require_email_verification': false
                    }
                };
                
                const settings = presets[preset];
                if (!settings) {
                    this.showToast('❌ Invalid preset selected.');
                    return;
                }
                
                let appliedCount = 0;
                Object.keys(settings).forEach(key => {
                    const checkbox = document.querySelector(`input[name="${key}"]`);
                    if (checkbox) {
                        checkbox.checked = settings[key];
                        appliedCount++;
                    }
                });
                
                if (appliedCount > 0) {
                    const presetNames = {
                        'strict': 'Strict Moderation',
                        'balanced': 'Balanced',
                        'open': 'Open Platform'
                    };
                    this.showToast(`✅ ${presetNames[preset]} preset applied! ${appliedCount} setting(s) updated. Don't forget to save.`);
                } else {
                    this.showToast('⚠️ No matching settings found for this preset.');
                }
            } catch (error) {
                console.error('Error applying preset:', error);
                this.showToast('❌ Error applying preset. Please try again.');
            }
        },
        
        resetForm() {
            if (confirm('Are you sure you want to reset all changes? This will reload the page and lose any unsaved changes.')) {
                document.getElementById('settingsForm').reset();
                window.location.reload();
            }
        },
        
        handleSubmit(event) {
            // Show loading state
            const submitButton = event.target.querySelector('button[type="submit"]');
            if (submitButton) {
                const originalText = submitButton.innerHTML;
                submitButton.disabled = true;
                submitButton.innerHTML = '⏳ Saving...';
                
                // Re-enable after 5 seconds as fallback (in case of error)
                setTimeout(() => {
                    submitButton.disabled = false;
                    submitButton.innerHTML = originalText;
                }, 5000);
            }
            
            // Form will submit normally
            return true;
        }
    }
}
</script>
@endpush
@endsection
