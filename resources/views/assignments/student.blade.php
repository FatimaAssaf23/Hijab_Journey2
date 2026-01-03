@extends('layouts.app')
@section('content')
<div class="max-w-3xl mx-auto py-10">
    <div class="bg-white shadow-2xl rounded-3xl p-10 border border-pink-200">
        <h2 class="text-3xl font-extrabold mb-8 text-pink-600 flex items-center gap-3">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-pink-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m2 0a2 2 0 100-4H7a2 2 0 100 4zm0 0v4a2 2 0 11-4 0v-4" /></svg>
            My Assignments
        </h2>
        @if ($errors->any())
            <div class="bg-red-100 border border-red-300 text-red-700 px-4 py-3 rounded mb-6">
                <ul class="list-disc pl-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        @if (session('error'))
            <div class="bg-red-100 border border-red-300 text-red-700 px-4 py-3 rounded mb-6">
                {{ session('error') }}
            </div>
        @endif
        <ul class="space-y-8">
            @forelse($assignments as $assignment)
                <li x-data="{ showDetails: true }" class="relative flex flex-col md:flex-row md:items-center md:justify-between group overflow-hidden rounded-3xl shadow-2xl border-l-8 border-pink-400 bg-gradient-to-br from-pink-50/80 via-white/90 to-pink-100/80 p-0 mb-8 hover:shadow-pink-300/60 hover:scale-[1.01] transition-all duration-200 ease-in-out">
                    <div class="absolute inset-0 pointer-events-none z-0 bg-gradient-to-br from-pink-100/40 via-white/60 to-pink-200/30"></div>
                    <div class="flex-1 min-w-0 flex flex-col gap-2 z-10 p-8">
                        <div class="flex items-center gap-4 mb-3">
                            <span class="inline-flex items-center justify-center h-12 w-12 rounded-full bg-gradient-to-br from-pink-300 to-pink-100 text-pink-700 shadow-xl border-2 border-white">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
                            </span>
                            <span class="font-black text-2xl text-pink-700 group-hover:text-pink-900 tracking-tight drop-shadow">{{ $assignment->title }}</span>
                            <button @click="showDetails = !showDetails" class="ml-4 px-4 py-2 rounded-xl bg-gradient-to-r from-pink-200 to-pink-300 text-pink-800 font-extrabold text-xs shadow-lg border border-pink-300 hover:from-pink-300 hover:to-pink-400 hover:text-pink-900 transition-all duration-150">
                                <span x-show="showDetails">Hide</span>
                                <span x-show="!showDetails">Show</span>
                            </button>
                        </div>
                    </div>
                    <template x-if="showDetails">
                        <div class="md:mt-0 md:ml-0 flex flex-col gap-1 items-center w-full md:w-2/3 lg:w-1/2 bg-white/95 rounded-3xl p-1 border border-pink-100 shadow-2xl z-10 mx-auto">
                            <div class="text-lg text-gray-700 mb-4 font-bold tracking-tight leading-snug text-center w-full">{{ $assignment->description }}</div>
                            <div class="flex flex-wrap gap-6 text-sm text-gray-500 mb-4 justify-center w-full">
                                @if($assignment->dead_time)
                                    <span class="font-semibold text-red-500 bg-red-50 border border-red-200 rounded px-2 py-1">Lock Time: {{ \Carbon\Carbon::parse($assignment->dead_time)->format('M d, Y H:i') }}</span>
                                @endif
                                @if($assignment->due_date)
                                    <span class="font-semibold text-pink-500">Due: {{ \Carbon\Carbon::parse($assignment->due_date)->format('M d, Y') }}</span>
                                @endif
                                @if($assignment->level_id && isset($assignment->level))
                                    <span>Level: <span class="font-semibold text-pink-500">{{ $assignment->level->level_name ?? '' }}</span></span>
                                @endif
                                @if($assignment->class_id && isset($assignment->class))
                                    <span>Class: <span class="font-semibold text-pink-500">{{ $assignment->class->class_name ?? '' }}</span></span>
                                @endif
                            </div>
                            <a href="{{ asset('storage/' . $assignment->file_path) }}" class="inline-flex items-center gap-2 bg-gradient-to-r from-pink-200 to-pink-400 text-pink-900 px-6 py-3 rounded-2xl hover:from-pink-300 hover:to-pink-500 hover:text-pink-900 border border-pink-300 shadow-xl font-extrabold text-base transition-all duration-150 mb-4" target="_blank">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
                                Download Assignment
                            </a>
                            @php
                                $submission = $assignment->submissions->first();
                                $now = \Carbon\Carbon::now();
                                $lock = $assignment->dead_time ? \Carbon\Carbon::parse($assignment->dead_time) : ($assignment->due_date ? \Carbon\Carbon::parse($assignment->due_date) : null);
                                $canEdit = $lock && $now->lessThanOrEqualTo($lock);
                                $imageExts = ['jpg','jpeg','png','gif','bmp','webp'];
                            @endphp
                            @if($submission)
                                <div x-data="{ showEdit: false }" class="flex flex-col gap-4 w-full mt-2 items-center">
                                    <span class="text-green-700 text-sm font-bold flex items-center gap-2 mb-2">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                                        Uploaded File:
                                    </span>
                                    @php
                                        $fileUrl = asset('storage/' . $submission->submission_file_url);
                                        $fileExt = strtolower(pathinfo($submission->submission_file_url, PATHINFO_EXTENSION));
                                    @endphp
                                    @if(in_array($fileExt, $imageExts))
                                        <a href="{{ $fileUrl }}" target="_blank" class="inline-block group focus:outline-none">
                                            <span class="inline-block rounded-xl overflow-hidden border border-pink-300 shadow transition-transform transform group-hover:scale-105 group-hover:border-pink-500 bg-white">
                                                <img src="{{ $fileUrl }}" alt="Uploaded Image" class="max-h-16 w-auto block mx-auto">
                                            </span>
                                            <span class="block mt-0.5 text-xs text-pink-600 text-center font-semibold group-hover:underline">View Image</span>
                                        </a>
                                    @else
                                        <a href="{{ $fileUrl }}" target="_blank" class="text-pink-600 underline text-sm break-all">{{ basename($submission->submission_file_url) }}</a>
                                    @endif
                                    @if($submission->grade)
                                        <div class="mt-1 p-2 rounded-xl bg-pink-100/95 border border-pink-200 shadow flex flex-col gap-0.5 w-full max-w-xs mx-auto">
                                            <div class="text-sm text-pink-700 font-extrabold flex items-center gap-1">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 text-pink-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                                                Grade: <span class="ml-1">{{ $submission->grade->grade_value }} / {{ $submission->grade->max_grade ?? 100 }}</span>
                                            </div>
                                            @if($submission->grade->feedback)
                                                <div class="mt-0.5 text-xs text-gray-700"><span class="font-bold">Teacher's Comment:</span> {{ $submission->grade->feedback }}</div>
                                            @endif
                                        </div>
                                    @endif
                                    @if($canEdit)
                                        <button type="button" @click="showEdit = !showEdit" class="mt-4 bg-gradient-to-r from-pink-400 to-pink-600 text-white px-6 py-2 rounded-2xl font-extrabold shadow-xl hover:from-pink-500 hover:to-pink-700 transition text-base">Edit Submission</button>
                                        <template x-if="showEdit">
                                            <div class="w-full mt-4">
                                                <form method="POST" action="{{ route('student.assignment.submit') }}" enctype="multipart/form-data" class="flex items-center gap-3 w-full">
                                                    @csrf
                                                    <input type="hidden" name="assignment_id" value="{{ $assignment->assignment_id }}">
                                                    <input type="file" name="submission_file" class="border border-pink-200 rounded px-3 py-2 text-base flex-1">
                                                    <button type="submit" class="bg-pink-500 text-white px-6 py-2 rounded-2xl font-extrabold shadow-xl hover:bg-pink-600 transition text-base">Save</button>
                                                </form>
                                                <form method="POST" action="{{ route('student.assignment.delete', $submission->submission_id) }}" class="mt-3">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="bg-red-100 text-red-700 px-6 py-2 rounded-2xl font-extrabold shadow-xl hover:bg-red-200 transition text-base">Delete Submission</button>
                                                </form>
                                            </div>
                                        </template>
                                    @else
                                        <span class="mt-4 text-base text-gray-400 italic">Deadline passed. No more edits allowed.</span>
                                    @endif
                                </div>
                            @else
                                <form method="POST" action="{{ route('student.assignment.submit') }}" enctype="multipart/form-data" class="flex flex-col gap-2 mt-4 w-full">
                                    @csrf
                                    <input type="hidden" name="assignment_id" value="{{ $assignment->assignment_id }}">
                                    <label class="block font-semibold text-gray-700 mb-1">Select your assignment file to submit:</label>
                                    <input type="file" name="submission_file" class="border border-pink-200 rounded px-3 py-2 text-base w-full focus:ring-2 focus:ring-pink-200" required @if(!$canEdit) disabled @endif>
                                    <button type="submit" class="mt-2 bg-gradient-to-r from-pink-400 to-pink-600 text-white px-6 py-2 rounded-2xl font-extrabold shadow-xl hover:from-pink-500 hover:to-pink-700 transition text-base" @if(!$canEdit) disabled @endif>Upload Submission</button>
                                </form>
                            @endif
                        </div>
                    </template>
                </li>
            @empty
                <li class="py-3 text-gray-400">No assignments available yet.</li>
            @endforelse
        </ul>
    </div>
</div>
@endsection
