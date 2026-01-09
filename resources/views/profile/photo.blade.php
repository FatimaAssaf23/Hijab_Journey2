@extends('layouts.app')

@section('content')
<div x-data="{ open: true }" x-show="open" class="max-w-xl mx-auto mt-10 bg-white p-8 rounded shadow relative">
    <button @click="open = false" class="absolute top-4 right-4 text-gray-400 hover:text-gray-700 text-2xl font-bold focus:outline-none">&times;</button>
    <h2 class="text-2xl font-bold mb-6 text-center text-gray-800">Upload Profile Picture</h2>

    @if(session('success'))
        <div class="mb-4 p-3 bg-green-100 text-green-800 rounded">
            {{ session('success') }}
        </div>
    @endif

    <div class="flex flex-col items-center mb-6">
        @php
            $photoUrl = isset($profilePhotoUrl) ? $profilePhotoUrl : Auth::user()->profile_photo_url;
            // Add cache buster to force browser to reload image
            $photoUrl .= (strpos($photoUrl, '?') !== false ? '&' : '?') . 't=' . time();
        @endphp
        <img src="{{ $photoUrl }}" alt="Profile Photo" class="w-24 h-24 rounded-full object-cover border border-gray-300" id="profile-photo-img">
        <span class="text-sm text-gray-500 mt-2">Current Photo</span>
        <button type="button" @click="$refs.photoInput.click()" class="mt-3 px-4 py-2 bg-[#7AD7C1] text-white rounded shadow hover:bg-[#5ec1a6] transition">Change Photo</button>
    </div>

    <form method="POST" action="{{ route('profile.photo.upload') }}" enctype="multipart/form-data" class="space-y-6">
        @csrf
        <div>
            <label for="photo" class="block text-sm font-medium text-gray-700">Choose a profile picture</label>
            <input id="photo" name="photo" type="file" accept="image/*" class="mt-2 block w-full border border-gray-300 rounded-lg shadow-sm focus:ring focus:ring-[#7AD7C1] focus:border-[#7AD7C1]" required
                x-ref="photoInput"
                style="display:none"
                x-on:change="open = false; $el.form.submit();">
            @error('photo')
                <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
            @enderror
        </div>
        <!-- The upload button is now hidden, upload happens automatically on file select -->
    </form>
</div>
@endsection
