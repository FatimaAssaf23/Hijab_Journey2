@extends('layouts.app')
@section('content')
<div class="max-w-5xl mx-auto py-10" x-data="{ showForm: true }">
    <div class="bg-gradient-to-br from-pink-50 via-white to-pink-100 shadow-2xl rounded-3xl p-10 mb-10 border-2 border-pink-200">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-3xl font-extrabold text-pink-600 flex items-center gap-3 drop-shadow">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-9 w-9 text-pink-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
                Upload New Assignment
            </h2>
            <button type="button" @click="showForm = !showForm" class="ml-4 px-4 py-2 rounded-xl bg-gradient-to-r from-pink-200 to-pink-400 text-pink-800 font-extrabold border border-pink-300 shadow hover:from-pink-300 hover:to-pink-500 hover:text-pink-900 transition-all duration-150">
                <span x-show="showForm">Hide</span>
                <span x-show="!showForm">Show</span>
            </button>
        </div>
        <template x-if="showForm">
            <form method="POST" action="{{ route('assignments.store') }}" enctype="multipart/form-data" class="grid grid-cols-1 md:grid-cols-2 gap-6">
                @csrf
                <div>
                    <label class="block font-bold text-pink-700 mb-2">Title</label>
                    <input type="text" name="title" class="border-2 border-pink-200 rounded-xl px-4 py-3 w-full focus:ring-2 focus:ring-pink-200 bg-white" required>
                </div>
                <div>
                    <label class="block font-bold text-pink-700 mb-2">Description</label>
                    <textarea name="description" class="border-2 border-pink-200 rounded-xl px-4 py-3 w-full focus:ring-2 focus:ring-pink-200 bg-white"></textarea>
                </div>
                <div>
                    <label class="block font-bold text-pink-700 mb-2">Level</label>
                    <select name="level_id" class="border-2 border-pink-200 rounded-xl px-4 py-3 w-full focus:ring-2 focus:ring-pink-200 bg-white" required>
                        <option value="">Select Level</option>
                        @foreach($levels as $level)
                            <option value="{{ $level->level_id }}">{{ $level->level_name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block font-bold text-pink-700 mb-2">Class</label>
                    <select name="class_id" class="border-2 border-pink-200 rounded-xl px-4 py-3 w-full focus:ring-2 focus:ring-pink-200 bg-white" required>
                        <option value="">Select Class</option>
                        @foreach($classes as $class)
                            <option value="{{ $class->class_id }}">{{ $class->class_name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block font-bold text-pink-700 mb-2">File</label>
                    <input type="file" name="file" class="border-2 border-pink-200 rounded-xl px-4 py-3 w-full focus:ring-2 focus:ring-pink-200 bg-white" required>
                </div>
                <div>
                    <label class="block font-bold text-pink-700 mb-2">Due Date & Time</label>
                    <input type="datetime-local" name="due_date" class="border-2 border-pink-200 rounded-xl px-4 py-3 w-full focus:ring-2 focus:ring-pink-200 bg-white" required>
                </div>
                <div class="md:col-span-2 flex justify-end">
                    <button type="submit" class="bg-gradient-to-r from-pink-500 to-pink-700 text-white px-10 py-3 rounded-2xl font-extrabold shadow-xl hover:from-pink-600 hover:to-pink-800 transition-all duration-150">Upload Assignment</button>
                </div>
            </form>
        </template>
        @if(session('success'))
            <div class="bg-green-100 text-green-800 px-4 py-2 rounded-xl mb-4 font-bold text-center shadow">{{ session('success') }}</div>
        @endif
    </div>
    <div class="bg-gradient-to-br from-white via-pink-50 to-pink-100 shadow-xl rounded-3xl p-8 border-2 border-pink-100">
        <h3 class="text-2xl font-extrabold mb-8 text-pink-500 flex items-center gap-3 drop-shadow">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7 text-pink-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m2 0a2 2 0 100-4H7a2 2 0 100 4zm0 0v4a2 2 0 11-4 0v-4" /></svg>
            Your Assignments
        </h3>
        <ul class="grid grid-cols-1 md:grid-cols-2 gap-8">
            @forelse($assignments as $assignment)
                <li class="bg-white rounded-3xl shadow-xl border border-pink-100 p-8 flex flex-col gap-6 hover:shadow-pink-200 transition-all duration-150">
                    <div class="flex items-center gap-4 mb-2">
                        <span class="inline-flex items-center justify-center h-14 w-14 rounded-full bg-gradient-to-br from-pink-300 to-pink-500 text-white shadow-lg border-4 border-white">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
                        </span>
                        <span class="font-black text-2xl text-pink-700 tracking-tight drop-shadow">{{ $assignment->title }}</span>
                    </div>
                    <div class="text-lg text-gray-800 font-semibold mb-2">{{ $assignment->description }}</div>
                    <div class="flex flex-wrap gap-4 text-sm mb-4">
                        <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full bg-pink-50 text-pink-600 font-bold border border-pink-200"><svg class="h-4 w-4 text-pink-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg> Level: {{ $levels->where('level_id', $assignment->level_id)->first()?->level_name }}</span>
                        <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full bg-pink-50 text-pink-600 font-bold border border-pink-200"><svg class="h-4 w-4 text-pink-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 01-8 0" /></svg> Class: {{ $classes->where('class_id', $assignment->class_id)->first()?->class_name }}</span>
                        <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full bg-pink-50 text-pink-600 font-bold border border-pink-200"><svg class="h-4 w-4 text-pink-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 4h10" /></svg> Due: {{ \Carbon\Carbon::parse($assignment->due_date)->format('M d, Y H:i') }}</span>
                    </div>
                    <div class="flex flex-col md:flex-row gap-6">
                        <div class="flex-1">
                            <div class="mb-2 font-bold text-green-700 flex items-center gap-2"><svg class="h-4 w-4 text-green-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg> Submitted ({{ $assignment->submitted_students->count() }})</div>
                            <div class="flex flex-wrap gap-2">
                                @foreach($assignment->submitted_students as $student)
                                    <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-green-50 text-green-800 font-semibold border border-green-200">
                                        {{ $student->user->first_name ?? '' }} {{ $student->user->last_name ?? '' }}
                                        @if(isset($assignment->submissions[$student->student_id]))
                                            <a href="{{ route('assignments.submission.view', $assignment->submissions[$student->student_id]->submission_id) }}" target="_blank" class="ml-2 px-2 py-0.5 rounded bg-pink-200 text-pink-800 text-xs font-semibold hover:bg-pink-300 transition">View</a>
                                        @endif
                                    </span>
                                @endforeach
                                @if($assignment->submitted_students->count() == 0)
                                    <span class="text-gray-400 italic">No submissions yet.</span>
                                @endif
                            </div>
                        </div>
                        <div class="flex-1">
                            <div class="mb-2 font-bold text-red-700 flex items-center gap-2"><svg class="h-4 w-4 text-red-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg> Unsubmitted ({{ $assignment->unsubmitted_students->count() }})</div>
                            <div class="flex flex-wrap gap-2">
                                @foreach($assignment->unsubmitted_students as $student)
                                    <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-red-50 text-red-800 font-semibold border border-red-200">
                                        {{ $student->user->first_name ?? '' }} {{ $student->user->last_name ?? '' }}
                                    </span>
                                @endforeach
                                @if($assignment->unsubmitted_students->count() == 0)
                                    <span class="text-gray-400 italic">All students submitted.</span>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="flex justify-end mt-4">
                        <a href="{{ asset('storage/' . $assignment->file_path) }}" class="inline-block bg-gradient-to-r from-pink-400 to-pink-600 text-white px-8 py-3 rounded-2xl font-extrabold shadow-xl hover:from-pink-500 hover:to-pink-700 transition text-lg" target="_blank">View Assignment File</a>
                    </div>
                </li>
            @empty
                <li class="py-3 text-gray-400">No assignments uploaded yet.</li>
            @endforelse
        </ul>
    </div>
</div>
@endsection

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/choices.js/public/assets/styles/choices.min.css">
@endpush
@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/choices.js/public/assets/scripts/choices.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const levelSelect = document.querySelector('select[name="level_id"]');
    if(levelSelect) {
        new Choices(levelSelect, {
            searchEnabled: false,
            shouldSort: false,
            position: 'bottom', // always open downward
        });
    }
});
</script>
@endpush
