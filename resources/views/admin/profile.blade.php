@extends('layouts.admin')

@section('content')
<div class="max-w-2xl mx-auto mt-10">
    <div x-data="{ open: true }" x-show="open" class="bg-white rounded-xl shadow-lg p-8 relative">
        <button @click="open = false" class="absolute top-4 right-4 text-gray-400 hover:text-gray-700 text-2xl font-bold focus:outline-none">&times;</button>
        <h1 class="text-3xl font-bold text-[#197D8C] mb-4">Admin Profile</h1>
        @if(session('success'))
            <div x-data="{ show: true }" x-show="show" class="mb-4 p-3 bg-green-100 text-green-800 rounded shadow">
                <span>{{ session('success') }}</span>
                <button type="button" @click="show = false" class="float-right text-green-700 font-bold">&times;</button>
            </div>
        @endif
        <form method="POST" action="{{ route('admin.profile.update') }}" enctype="multipart/form-data">
            @csrf
            @method('POST')
            <div class="flex items-center gap-6 mb-6">
                <div>
                    <img src="{{ $adminProfile->profile_photo_path ? asset('storage/' . $adminProfile->profile_photo_path) : asset('images/default-profile.png') }}" alt="Profile Photo" class="w-20 h-20 rounded-full object-cover border border-gray-300">
                    <input type="file" name="profile_photo" class="mt-2 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-pink-50 file:text-pink-700 hover:file:bg-pink-100" accept="image/*">
                </div>
                <div>
                    <div class="text-xl font-semibold text-gray-800">{{ $admin->first_name }} {{ $admin->last_name }}</div>
                    <div class="text-gray-500">{{ $admin->email }}</div>
                    <div class="text-pink-600 font-medium mt-1">Role: Admin</div>
                </div>
            </div>
            <div class="mb-4">
                <label class="block text-sm font-semibold text-gray-700 mb-2">Bio</label>
                <textarea name="bio" rows="3" class="w-full border-2 border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:border-pink-500" placeholder="Write something about yourself...">{{ old('bio', $adminProfile->bio) }}</textarea>
            </div>
            <div class="mt-6">
                <button type="submit" class="bg-gradient-to-r from-[#FC8EAC] via-[#EC769A] to-[#6EC6C5] text-white px-6 py-2 rounded-lg font-semibold shadow hover:shadow-lg transition">Save Changes</button>
            </div>
        </form>
    </div>
</div>
@endsection
