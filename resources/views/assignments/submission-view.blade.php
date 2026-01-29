@extends('layouts.app')
@section('content')
<div class="w-full max-w-full mx-auto py-12 px-4 sm:px-6 lg:px-8">
    <div class="bg-white shadow-2xl rounded-3xl p-10 border border-pink-200">
        <h2 class="text-3xl font-extrabold mb-8 text-pink-600 flex items-center gap-3">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-pink-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
            Assignment Submission
        </h2>
        <div class="mb-6 flex flex-col gap-2">
            <div class="flex items-center gap-2">
                <span class="font-semibold text-gray-700">Student:</span>
                <span class="text-base text-pink-700 font-bold">{{ $studentName ?? '' }}</span>
            </div>
            <div class="flex items-center gap-2">
                <span class="font-semibold text-gray-700">File:</span>
                @php
                    $fileUrl = asset('storage/' . $submission->submission_file_url);
                @endphp
                <a href="{{ $fileUrl }}" target="_blank" rel="noopener" class="inline-flex items-center gap-2 bg-gradient-to-r from-pink-400 to-pink-600 text-white px-4 py-2 rounded-xl font-semibold shadow hover:from-pink-500 hover:to-pink-700 transition" style="min-width: 120px; width: fit-content;">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    View / Download
                </a>
            </div>
            <span class="text-xs text-gray-500 ml-1">Note: The file will be downloaded if it cannot be viewed in your browser.</span>
        </div>

        <div class="mt-10">
            <h3 class="text-xl font-bold mb-4 text-pink-600 flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-pink-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                Grade / Mark
            </h3>
            @if(session('success'))
                <div class="bg-green-100 text-green-800 px-4 py-2 rounded mb-4">{{ session('success') }}</div>
            @endif
            <form method="POST" action="{{ route('assignments.submission.grade', $submission->submission_id) }}" class="space-y-4">
                @csrf
                <div>
                    <label class="block font-semibold text-gray-700 mb-1">Grade</label>
                    <input type="number" name="grade_value" step="0.01" min="0" class="border border-pink-200 rounded px-3 py-2 w-full focus:ring-2 focus:ring-pink-200" value="{{ $submission->grade->grade_value ?? '' }}" required>
                </div>
                <div>
                    <label class="block font-semibold text-gray-700 mb-1">Max Grade</label>
                    <input type="number" name="max_grade" step="0.01" min="1" class="border border-pink-200 rounded px-3 py-2 w-full focus:ring-2 focus:ring-pink-200" value="{{ $submission->grade->max_grade ?? 100 }}" required>
                </div>
                <div>
                    <label class="block font-semibold text-gray-700 mb-1">Feedback (optional)</label>
                    <textarea name="feedback" class="border border-pink-200 rounded px-3 py-2 w-full focus:ring-2 focus:ring-pink-200">{{ $submission->grade->feedback ?? '' }}</textarea>
                </div>
                <button type="submit" class="bg-gradient-to-r from-pink-400 to-pink-600 text-white px-6 py-2 rounded-xl font-semibold shadow hover:from-pink-500 hover:to-pink-700 transition">Save Grade</button>
            </form>
            @if($submission->grade)
                <div class="mt-4 text-sm text-gray-700">
                    <span class="font-semibold">Last graded at:</span> {{ $submission->grade->graded_at }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
