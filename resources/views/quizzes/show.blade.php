@extends('layouts.app')
@section('content')
<div class="max-w-5xl mx-auto py-10">
    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
            {{ session('error') }}
        </div>
    @endif

    <div class="bg-gradient-to-br from-pink-50 via-white to-pink-100 shadow-2xl rounded-3xl p-10 mb-10 border-2 border-pink-200">
        <div class="flex justify-between items-center mb-6">
            <div class="flex-1">
                <h2 class="text-3xl font-extrabold text-pink-600 flex items-center gap-3 drop-shadow mb-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-9 w-9 text-pink-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    {{ $quiz->title }}
                </h2>
                @if($quiz->description)
                    <p class="text-gray-600 mt-2">{{ $quiz->description }}</p>
                @endif
                <div class="mt-4 flex flex-wrap gap-2">
                    <span class="px-3 py-1 rounded-full bg-pink-50 text-pink-600 font-bold border border-pink-200">
                        {{ $quiz->questions->count() }} Questions
                    </span>
                    <span class="px-3 py-1 rounded-full bg-pink-50 text-pink-600 font-bold border border-pink-200">
                        {{ $quiz->timer_minutes }} minutes
                    </span>
                    <span class="px-3 py-1 rounded-full {{ $quiz->is_active ? 'bg-green-50 text-green-600 border-green-200' : 'bg-gray-50 text-gray-600 border-gray-200' }} font-bold border">
                        {{ $quiz->is_active ? 'Active' : 'Inactive' }}
                    </span>
                </div>
            </div>
        </div>
        
        <div class="flex gap-4">
            <a href="{{ route('quizzes.index') }}" class="bg-gray-300 text-gray-700 px-6 py-3 rounded-xl font-bold hover:bg-gray-400 transition">
                ← Back to Quizzes
            </a>
            <a href="{{ route('quizzes.edit', $quiz->quiz_id) }}" class="bg-blue-500 text-white px-6 py-3 rounded-xl font-bold hover:bg-blue-600 transition">
                Edit Quiz
            </a>
            <button onclick="if(confirm('Are you sure you want to delete this quiz? This action cannot be undone.')) { document.getElementById('deleteForm').submit(); }" class="bg-red-500 text-white px-6 py-3 rounded-xl font-bold hover:bg-red-600 transition">
                Delete Quiz
            </button>
        </div>
        
        <form id="deleteForm" action="{{ route('quizzes.destroy', $quiz->quiz_id) }}" method="POST" class="hidden">
            @csrf
            @method('DELETE')
        </form>
    </div>

    <div class="space-y-6">
        @foreach($quiz->questions->sortBy('question_order') as $index => $question)
            <div class="bg-white rounded-3xl shadow-xl border border-pink-100 p-6 relative" style="background-color: {{ $question->background_color ?? '#FFFFFF' }}">
                <div class="absolute top-4 right-4 flex gap-2">
                    <button onclick="openEditModal({{ $question->question_id }})" class="bg-blue-500 text-white px-4 py-2 rounded-lg font-bold hover:bg-blue-600 transition text-sm">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                        Edit
                    </button>
                    <form action="{{ route('quizzes.questions.delete', ['quizId' => $quiz->quiz_id, 'questionId' => $question->question_id]) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this question? This action cannot be undone.');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded-lg font-bold hover:bg-red-600 transition text-sm">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                            Delete
                        </button>
                    </form>
                </div>

                <div class="mb-4 pr-32">
                    <div class="flex items-start justify-between mb-4">
                        <h3 class="text-xl font-bold text-pink-700 flex items-start gap-3 flex-1">
                            <span class="bg-pink-500 text-white rounded-full w-8 h-8 flex items-center justify-center text-sm font-extrabold flex-shrink-0">
                                {{ $index + 1 }}
                            </span>
                            <span class="flex-1">{{ $question->question_text }}</span>
                        </h3>
                    </div>
                    
                    @if($question->image_path)
                        <div class="mb-4 flex justify-center">
                            <img src="{{ asset('storage/' . $question->image_path) }}" alt="Question Image" class="max-w-md max-h-[300px] w-auto h-auto rounded-lg shadow-md object-contain">
                        </div>
                    @endif
                </div>

                <div class="space-y-3">
                    @foreach($question->options->sortBy('option_order') as $option)
                        <div class="flex items-center p-4 rounded-xl border-2 {{ $option->is_correct ? 'border-green-500 bg-green-50' : 'border-gray-200' }}">
                            <div class="flex items-center flex-1">
                                @if($option->is_correct)
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-green-600 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                    </svg>
                                @endif
                                <span class="text-gray-700 font-medium flex-1">{{ $option->option_text }}</span>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endforeach
    </div>
</div>

<!-- Edit Question Modal -->
<div id="editQuestionModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-3xl shadow-2xl max-w-3xl w-full max-h-[90vh] overflow-y-auto">
        <div class="p-6">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-2xl font-bold text-pink-600">Edit Question</h3>
                <button onclick="closeEditModal()" class="text-gray-500 hover:text-gray-700">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <form id="editQuestionForm" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                
                <div class="space-y-6">
                    <div>
                        <label class="block text-gray-700 font-bold mb-2">Question Text</label>
                        <textarea name="question_text" id="edit_question_text" rows="3" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-pink-500 focus:border-transparent" required></textarea>
                    </div>

                    <div>
                        <label class="block text-gray-700 font-bold mb-2">Background Color</label>
                        <input type="color" name="background_color" id="edit_background_color" value="#F8C5C8" class="w-20 h-10 rounded border border-gray-300">
                    </div>

                    <div>
                        <label class="block text-gray-700 font-bold mb-2">Question Image (optional)</label>
                        <input type="file" name="image" id="edit_question_image" accept="image/*" class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                        <div id="edit_existing_image_preview" class="mt-2"></div>
                    </div>

                    <div>
                        <label class="block text-gray-700 font-bold mb-2">Choices</label>
                        <div id="edit_choices_container" class="space-y-3">
                            <!-- Choices will be dynamically added here -->
                        </div>
                        <button type="button" onclick="addEditChoice()" class="mt-3 bg-green-500 text-white px-4 py-2 rounded-lg font-bold hover:bg-green-600 transition">
                            + Add Choice
                        </button>
                    </div>

                    <div>
                        <label class="block text-gray-700 font-bold mb-2">Correct Answer</label>
                        <select name="correct_answer" id="edit_correct_answer" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-pink-500 focus:border-transparent" required>
                            <!-- Options will be dynamically added here -->
                        </select>
                    </div>

                    <div class="flex gap-4 pt-4">
                        <button type="submit" class="flex-1 bg-blue-500 text-white px-6 py-3 rounded-xl font-bold hover:bg-blue-600 transition">
                            Update Question
                        </button>
                        <button type="button" onclick="closeEditModal()" class="flex-1 bg-gray-300 text-gray-700 px-6 py-3 rounded-xl font-bold hover:bg-gray-400 transition">
                            Cancel
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

@php
    $questionsData = [];
    foreach ($quiz->questions->sortBy('question_order') as $q) {
        $options = [];
        foreach ($q->options->sortBy('option_order') as $opt) {
            $options[] = [
                'id' => $opt->option_id,
                'text' => $opt->option_text,
                'is_correct' => (bool)$opt->is_correct
            ];
        }
        
        $questionsData[] = [
            'id' => $q->question_id,
            'text' => $q->question_text,
            'background_color' => $q->background_color ?? '#F8C5C8',
            'image_path' => $q->image_path ? asset('storage/' . $q->image_path) : null,
            'options' => $options
        ];
    }
@endphp

<script>
const questionData = @json($questionsData);
console.log('Question Data Loaded:', questionData);

let currentQuestionId = null;
let editChoicesCount = 0;

function openEditModal(questionId) {
    console.log('openEditModal called with questionId:', questionId);
    console.log('questionData:', questionData);
    
    // Debug: Check if questionData is loaded
    if (!questionData || questionData.length === 0) {
        alert('No questions data loaded. Please refresh the page.');
        console.error('questionData is empty or undefined:', questionData);
        return;
    }
    
    // Convert questionId to number for comparison
    const questionIdNum = parseInt(questionId);
    const question = questionData.find(q => parseInt(q.id) === questionIdNum);
    
    console.log('Looking for question ID:', questionIdNum);
    console.log('Found question:', question);
    
    if (!question) {
        const availableIds = questionData.map(q => q.id).join(', ');
        alert('Question not found. ID: ' + questionId + ', Available IDs: ' + availableIds);
        console.error('Question not found. Looking for:', questionIdNum, 'Available:', questionData);
        return;
    }

    if (!question.options || question.options.length === 0) {
        alert('Question has no options. Please refresh the page.');
        console.error('Question has no options:', question);
        return;
    }
    
    console.log('Question found, opening modal with options:', question.options);

    currentQuestionId = questionId;
    document.getElementById('editQuestionForm').action = `/quizzes/{{ $quiz->quiz_id }}/questions/${questionId}`;
    document.getElementById('edit_question_text').value = question.text || '';
    document.getElementById('edit_background_color').value = question.background_color || '#F8C5C8';
    
    // Clear existing choices
    document.getElementById('edit_choices_container').innerHTML = '';
    editChoicesCount = 0;
    
    let correctAnswerIndex = -1;
    
    // Add choices
    question.options.forEach((option, index) => {
        if (option && option.text) {
            addEditChoice(option.text, option.id);
            if (option.is_correct) {
                correctAnswerIndex = editChoicesCount - 1; // -1 because addEditChoice increments it
            }
        }
    });
    
    // Set correct answer after all choices are added
    if (correctAnswerIndex >= 0) {
        document.getElementById('edit_correct_answer').value = correctAnswerIndex;
    }
    
    // Update correct answer select
    updateCorrectAnswerSelect();
    
    // Show existing image if any
    const imagePreview = document.getElementById('edit_existing_image_preview');
    if (question.image_path) {
        imagePreview.innerHTML = `
            <p class="text-sm text-gray-600 mb-2 text-center">Current Image:</p>
            <div class="flex justify-center">
                <img src="${question.image_path}" alt="Current Image" class="max-w-md max-h-[300px] w-auto h-auto rounded-lg shadow-md object-contain">
            </div>
        `;
    } else {
        imagePreview.innerHTML = '';
    }
    
    // Show the modal
    const modal = document.getElementById('editQuestionModal');
    if (modal) {
        modal.classList.remove('hidden');
        console.log('Modal should now be visible');
    } else {
        console.error('Modal element not found!');
        alert('Modal element not found. Please refresh the page.');
    }
}

function closeEditModal() {
    document.getElementById('editQuestionModal').classList.add('hidden');
    currentQuestionId = null;
}

function addEditChoice(text = '', optionId = null) {
    const container = document.getElementById('edit_choices_container');
    if (!container) {
        console.error('edit_choices_container not found');
        return;
    }
    
    const index = editChoicesCount;
    
    // Create elements instead of using innerHTML for better security
    const choiceDiv = document.createElement('div');
    choiceDiv.className = 'flex items-center gap-2';
    
    const textInput = document.createElement('input');
    textInput.type = 'text';
    textInput.name = 'choices[]';
    textInput.value = text || '';
    textInput.placeholder = 'Enter choice text';
    textInput.className = 'flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-pink-500 focus:border-transparent';
    textInput.required = true;
    
    const hiddenInput = document.createElement('input');
    hiddenInput.type = 'hidden';
    hiddenInput.name = 'option_ids[]';
    hiddenInput.value = optionId || '';
    
    choiceDiv.appendChild(textInput);
    choiceDiv.appendChild(hiddenInput);
    
    if (editChoicesCount >= 2) {
        const removeBtn = document.createElement('button');
        removeBtn.type = 'button';
        removeBtn.onclick = function() { removeEditChoice(this); };
        removeBtn.className = 'bg-red-500 text-white px-3 py-2 rounded-lg hover:bg-red-600 transition';
        removeBtn.textContent = 'Remove';
        choiceDiv.appendChild(removeBtn);
    }
    
    container.appendChild(choiceDiv);
    editChoicesCount++;
    updateCorrectAnswerSelect();
    
    console.log('Added choice:', text, 'Total choices:', editChoicesCount);
}

function removeEditChoice(button) {
    if (editChoicesCount <= 2) return;
    button.parentElement.remove();
    editChoicesCount--;
    updateCorrectAnswerSelect();
}

function updateCorrectAnswerSelect() {
    const select = document.getElementById('edit_correct_answer');
    const currentValue = select.value;
    const choices = document.querySelectorAll('#edit_choices_container input[name="choices[]"]');
    
    select.innerHTML = '';
    choices.forEach((choice, index) => {
        const option = document.createElement('option');
        option.value = index;
        option.textContent = `Choice ${index + 1}`;
        if (index == currentValue) {
            option.selected = true;
        }
        select.appendChild(option);
    });
}

// Close modal when clicking outside
document.getElementById('editQuestionModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeEditModal();
    }
});
</script>
@endsection
