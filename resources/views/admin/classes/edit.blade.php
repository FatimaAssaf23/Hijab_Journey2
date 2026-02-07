@extends('layouts.admin')

@section('content')
<div class="min-h-screen">
    <!-- Header -->
    <div class="bg-gradient-to-r from-pink-200/90 via-rose-100/80 to-cyan-200/90 shadow-2xl border-b-4 border-pink-300/50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
            <div class="flex items-center gap-4 mb-4">
                <a href="{{ route('admin.classes') }}" class="bg-white/90 hover:bg-white text-gray-800 px-6 py-3 rounded-xl font-bold shadow-lg hover:shadow-xl transition-all transform hover:scale-105 flex items-center gap-2 border-2 border-pink-300/50">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Go Back
                </a>
            </div>
            <div>
                <h1 class="text-5xl font-extrabold text-gray-800 mb-3 drop-shadow-lg">
                    <span class="bg-clip-text text-transparent bg-gradient-to-r from-pink-600 via-rose-500 to-cyan-600">✏️ Edit Class</span>
                </h1>
                <p class="text-gray-700 text-lg font-medium">Update class details and teacher assignment</p>
            </div>
        </div>
    </div>

    <!-- Form -->
    <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        <div class="bg-white/90 backdrop-blur-md rounded-3xl p-8 shadow-xl border-2 border-pink-200/50">
            <form method="POST" action="{{ route('admin.classes.update', $class['id']) }}">
                @csrf
                @method('PATCH')

                <div class="space-y-6">
                    <!-- Class Name -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Class Name</label>
                        <input type="text" name="name" value="{{ old('name', $class['name']) }}" class="w-full border-2 border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:border-pink-500" required>
                        @error('name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <!-- Students -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Students</label>
                        <input type="number" name="students" value="{{ old('students', $class['students']) }}" class="w-full border-2 border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:border-pink-500" required>
                        @error('students') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <!-- Teacher -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Assign Teacher</label>
                        <select name="teacherId" class="w-full border-2 border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:border-pink-500" required>
                            @foreach($teachers as $teacher)
                                <option value="{{ $teacher['id'] }}" {{ $class['teacherId'] == $teacher['id'] ? 'selected' : '' }}>{{ $teacher['name'] }} ({{ $teacher['subject'] }})</option>
                            @endforeach
                        </select>
                        @error('teacherId') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                </div>

                <!-- Buttons -->
                <div class="flex gap-3 mt-8">
                    <a href="{{ route('admin.classes') }}" class="flex-1 bg-gradient-to-r from-gray-300 to-gray-400 hover:from-gray-400 hover:to-gray-500 text-gray-800 font-bold py-3 rounded-xl transition-all transform hover:scale-105 shadow-md text-center">
                        Cancel
                    </a>
                    <button type="submit" class="flex-1 bg-gradient-to-r from-pink-400 to-rose-400 hover:from-pink-500 hover:to-rose-500 text-white font-bold py-3 rounded-xl transition-all transform hover:scale-105 shadow-lg border-2 border-pink-300/50">
                        Update Class
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
