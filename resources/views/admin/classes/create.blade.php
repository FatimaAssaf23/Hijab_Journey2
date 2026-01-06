@extends('layouts.admin')

@section('content')
<div class="min-h-screen">
    <!-- Header -->
    <div class="bg-gradient-to-r from-[#FC8EAC] via-[#EC769A] to-[#6EC6C5] shadow-xl">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <h1 class="text-4xl font-extrabold text-white mb-2">+ Add New Class</h1>
            <p class="text-pink-100">Create a new class and assign a teacher</p>
        </div>
    </div>

    <!-- Form -->
    <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="bg-white rounded-2xl p-8 shadow-xl">
            <form method="POST" action="{{ route('admin.classes.store') }}">
                @csrf

                <div class="space-y-6">
                    <!-- Class Name -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Class Name</label>
                        <input type="text" name="name" value="{{ old('name') }}" class="w-full border-2 border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:border-pink-500" placeholder="e.g., Grade 1 - Section A" required>
                        @error('name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <!-- Students -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Class Capacity (Number of Students)</label>
                        <input type="number" name="students" value="{{ old('students', 1) }}" min="1" max="100" class="w-full border-2 border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:border-pink-500" placeholder="e.g., 25" required>
                        @error('students') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <!-- Teacher -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Assign Teacher</label>
                        <select name="teacherId" class="w-full border-2 border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:border-pink-500" required>
                            <option value="">Select a teacher...</option>
                            @foreach($teachers as $teacher)
                                <option value="{{ $teacher['id'] }}">{{ $teacher['name'] }} ({{ $teacher['subject'] }})</option>
                            @endforeach
                        </select>
                        @error('teacherId') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <!-- Class Color -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Class Color</label>
                        <div class="grid grid-cols-6 gap-3">
                            <!-- Row 1 - Beige & Neutral Tones -->
                            <label class="cursor-pointer">
                                <input type="radio" name="color" value="tan" class="sr-only peer" required>
                                <div class="w-full h-14 rounded-lg bg-gradient-to-br from-[#CCB083] to-[#C4A677] border-4 border-transparent peer-checked:border-gray-800 peer-checked:ring-2 peer-checked:ring-offset-2 peer-checked:ring-amber-400 hover:scale-105 transition-all shadow-md"></div>
                                <p class="text-xs text-center mt-1 text-gray-600">Tan</p>
                            </label>
                            <label class="cursor-pointer">
                                <input type="radio" name="color" value="beige" class="sr-only peer">
                                <div class="w-full h-14 rounded-lg bg-gradient-to-br from-[#E4CFB3] to-[#DCC5A5] border-4 border-transparent peer-checked:border-gray-800 peer-checked:ring-2 peer-checked:ring-offset-2 peer-checked:ring-amber-300 hover:scale-105 transition-all shadow-md"></div>
                                <p class="text-xs text-center mt-1 text-gray-600">Beige</p>
                            </label>
                            <label class="cursor-pointer">
                                <input type="radio" name="color" value="ivory" class="sr-only peer">
                                <div class="w-full h-14 rounded-lg bg-gradient-to-br from-[#F4F4DD] to-[#EEEED0] border-4 border-transparent peer-checked:border-gray-800 peer-checked:ring-2 peer-checked:ring-offset-2 peer-checked:ring-yellow-300 hover:scale-105 transition-all shadow-md"></div>
                                <p class="text-xs text-center mt-1 text-gray-600">Ivory</p>
                            </label>
                            <label class="cursor-pointer">
                                <input type="radio" name="color" value="blush" class="sr-only peer">
                                <div class="w-full h-14 rounded-lg bg-gradient-to-br from-[#F8C5C8] to-[#F5B5B9] border-4 border-transparent peer-checked:border-gray-800 peer-checked:ring-2 peer-checked:ring-offset-2 peer-checked:ring-pink-300 hover:scale-105 transition-all shadow-md"></div>
                                <p class="text-xs text-center mt-1 text-gray-600">Blush</p>
                            </label>
                            <label class="cursor-pointer">
                                <input type="radio" name="color" value="coral" class="sr-only peer">
                                <div class="w-full h-14 rounded-lg bg-gradient-to-br from-[#FC8EAC] to-[#FA7A9C] border-4 border-transparent peer-checked:border-gray-800 peer-checked:ring-2 peer-checked:ring-offset-2 peer-checked:ring-pink-400 hover:scale-105 transition-all shadow-md"></div>
                                <p class="text-xs text-center mt-1 text-gray-600">Coral</p>
                            </label>
                            <label class="cursor-pointer">
                                <input type="radio" name="color" value="rose" class="sr-only peer">
                                <div class="w-full h-14 rounded-lg bg-gradient-to-br from-[#EC769A] to-[#E8628A] border-4 border-transparent peer-checked:border-gray-800 peer-checked:ring-2 peer-checked:ring-offset-2 peer-checked:ring-pink-500 hover:scale-105 transition-all shadow-md"></div>
                                <p class="text-xs text-center mt-1 text-gray-600">Rose</p>
                            </label>
                            <!-- Row 2 - Original Colors -->
                            <label class="cursor-pointer">
                                <input type="radio" name="color" value="pink-dark" class="sr-only peer">
                                <div class="w-full h-14 rounded-lg bg-gradient-to-br from-[#E88A93] to-[#F08080] border-4 border-transparent peer-checked:border-gray-800 peer-checked:ring-2 peer-checked:ring-offset-2 peer-checked:ring-pink-400 hover:scale-105 transition-all shadow-md"></div>
                                <p class="text-xs text-center mt-1 text-gray-600">Pink</p>
                            </label>
                            <label class="cursor-pointer">
                                <input type="radio" name="color" value="pink-light" class="sr-only peer">
                                <div class="w-full h-14 rounded-lg bg-gradient-to-br from-[#F2C4C4] to-[#F4B8B8] border-4 border-transparent peer-checked:border-gray-800 peer-checked:ring-2 peer-checked:ring-offset-2 peer-checked:ring-pink-300 hover:scale-105 transition-all shadow-md"></div>
                                <p class="text-xs text-center mt-1 text-gray-600">Peach</p>
                            </label>
                            <label class="cursor-pointer">
                                <input type="radio" name="color" value="cream" class="sr-only peer">
                                <div class="w-full h-14 rounded-lg bg-gradient-to-br from-[#EDE4D8] to-[#E5D9C9] border-4 border-transparent peer-checked:border-gray-800 peer-checked:ring-2 peer-checked:ring-offset-2 peer-checked:ring-amber-300 hover:scale-105 transition-all shadow-md"></div>
                                <p class="text-xs text-center mt-1 text-gray-600">Cream</p>
                            </label>
                            <label class="cursor-pointer">
                                <input type="radio" name="color" value="turquoise" class="sr-only peer">
                                <div class="w-full h-14 rounded-lg bg-gradient-to-br from-[#3DD9C4] to-[#2ED3BC] border-4 border-transparent peer-checked:border-gray-800 peer-checked:ring-2 peer-checked:ring-offset-2 peer-checked:ring-teal-400 hover:scale-105 transition-all shadow-md"></div>
                                <p class="text-xs text-center mt-1 text-gray-600">Turquoise</p>
                            </label>
                            <label class="cursor-pointer">
                                <input type="radio" name="color" value="teal" class="sr-only peer">
                                <div class="w-full h-14 rounded-lg bg-gradient-to-br from-[#2DBCB0] to-[#25A99E] border-4 border-transparent peer-checked:border-gray-800 peer-checked:ring-2 peer-checked:ring-offset-2 peer-checked:ring-teal-500 hover:scale-105 transition-all shadow-md"></div>
                                <p class="text-xs text-center mt-1 text-gray-600">Teal</p>
                            </label>
                        </div>
                        @error('color') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>
                </div>

                <!-- Buttons -->
                <div class="flex gap-3 mt-8">
                    <a href="{{ route('admin.classes') }}" class="flex-1 bg-gray-300 hover:bg-gray-400 text-gray-800 font-semibold py-3 rounded-lg transition-all text-center">
                        Cancel
                    </a>
                    <button type="submit" class="flex-1 bg-gradient-to-r from-pink-500 to-teal-400 hover:shadow-lg text-white font-semibold py-3 rounded-lg transition-all">
                        Create Class
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
