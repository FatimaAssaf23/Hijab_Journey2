@extends('layouts.app')
@section('content')
<div class="relative min-h-screen" style="background: linear-gradient(135deg, #FFF4FA 0%, #FDF2F8 30%, #F0F9FF 70%, #E0F7FA 100%);">
    <!-- Animated Background Elements -->
    <div class="fixed inset-0 overflow-hidden pointer-events-none z-0">
        <div class="absolute top-20 left-10 w-96 h-96 bg-pink-300/20 rounded-full blur-3xl animate-pulse" style="animation-delay: 0s;"></div>
        <div class="absolute top-60 right-20 w-[500px] h-[500px] bg-cyan-300/20 rounded-full blur-3xl animate-pulse" style="animation-delay: 2s;"></div>
        <div class="absolute bottom-20 left-1/4 w-80 h-80 bg-rose-300/20 rounded-full blur-3xl animate-pulse" style="animation-delay: 4s;"></div>
    </div>
    
    <div class="relative z-10 w-full max-w-full mx-auto px-4 sm:px-6 lg:px-8 py-8" x-data="{
    questions: [],
    init() {
        @if(old('questions') && count(old('questions')) > 0)
            const oldQuestions = @json(old('questions'));
            this.questions = oldQuestions.map((q, idx) => {
                // Handle choices - could be array or object
                let choices = [];
                if (Array.isArray(q.choices)) {
                    choices = q.choices.filter(c => c && c.trim() !== '');
                } else if (typeof q.choices === 'object' && q.choices !== null) {
                    choices = Object.values(q.choices).filter(c => c && c.trim() !== '');
                }
                if (choices.length === 0) choices = ['', ''];
                
                return {
                    choices: choices,
                    correctAnswer: parseInt(q.correct_answer) || 0,
                    backgroundColor: q.background_color || '#F8C5C8',
                    question_text: q.question_text || ''
                };
            });
        @else
            this.questions = [{ choices: ['', ''], correctAnswer: 0, backgroundColor: '#F8C5C8', question_text: '' }];
        @endif
    },
    addQuestion() {
        this.questions.push({ choices: ['', ''], correctAnswer: 0, backgroundColor: '#F8C5C8', question_text: '' });
    },
    removeQuestion(index) {
        if (this.questions.length > 1) {
            this.questions.splice(index, 1);
        }
    },
    addChoice(questionIndex) {
        this.questions[questionIndex].choices.push('');
    },
    removeChoice(questionIndex, choiceIndex) {
        if (this.questions[questionIndex].choices.length > 2) {
            this.questions[questionIndex].choices.splice(choiceIndex, 1);
            if (this.questions[questionIndex].correctAnswer >= this.questions[questionIndex].choices.length) {
                this.questions[questionIndex].correctAnswer = this.questions[questionIndex].choices.length - 1;
            }
        }
    }
}">
    <div class="bg-gradient-to-br from-pink-50/90 via-white/90 to-pink-100/90 backdrop-blur-sm shadow-2xl rounded-3xl p-8 lg:p-10 mb-10 border-2 border-pink-200/50">
        <!-- Go Back Button -->
        <div class="mb-6">
            <a href="{{ route('quizzes.index') }}" 
               class="inline-flex items-center gap-2 text-pink-600 hover:text-pink-700 font-semibold transition-colors duration-200 group bg-white/80 backdrop-blur-sm px-4 py-2 rounded-xl border-2 border-pink-300/50 shadow-md hover:shadow-lg">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 group-hover:-translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                <span>Go Back</span>
            </a>
        </div>

        <h2 class="text-3xl font-extrabold mb-8 text-pink-600 flex items-center gap-3 drop-shadow">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-9 w-9 text-pink-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
            </svg>
            Create New MCQ Quiz
        </h2>

        @if($errors->any())
            <div class="bg-red-100 border border-red-300 text-red-700 px-4 py-3 rounded mb-6">
                <strong class="font-bold">Please fix the following errors:</strong>
                <ul class="list-disc pl-5 mt-2">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-100 border border-red-300 text-red-700 px-4 py-3 rounded mb-6">
                {{ session('error') }}
            </div>
        @endif

        <form method="POST" action="{{ route('quizzes.store') }}" enctype="multipart/form-data">
            @csrf

            <!-- Quiz Basic Info -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                <div>
                    <label class="block font-bold text-pink-700 mb-2">Quiz Title</label>
                    <input type="text" name="title" value="{{ old('title') }}" class="border-2 border-pink-200 rounded-xl px-4 py-3 w-full focus:ring-2 focus:ring-pink-200 bg-white" required>
                    @error('title')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="block font-bold text-pink-700 mb-2">Level</label>
                    <select name="level_id" class="border-2 border-pink-200 rounded-xl px-4 py-3 w-full focus:ring-2 focus:ring-pink-200 bg-white" required>
                        <option value="">Select Level</option>
                        @foreach($levels as $level)
                            <option value="{{ $level->level_id }}" {{ old('level_id') == $level->level_id ? 'selected' : '' }}>{{ $level->level_name }}</option>
                        @endforeach
                    </select>
                    @error('level_id')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="block font-bold text-pink-700 mb-2">Class</label>
                    <select name="class_id" class="border-2 border-pink-200 rounded-xl px-4 py-3 w-full focus:ring-2 focus:ring-pink-200 bg-white" required>
                        <option value="">Select Class</option>
                        @foreach($classes as $class)
                            <option value="{{ $class->class_id }}" {{ old('class_id') == $class->class_id ? 'selected' : '' }}>{{ $class->class_name }}</option>
                        @endforeach
                    </select>
                    @error('class_id')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="block font-bold text-pink-700 mb-2">Timer (minutes)</label>
                    <input type="number" name="timer_minutes" value="{{ old('timer_minutes', 30) }}" min="1" class="border-2 border-pink-200 rounded-xl px-4 py-3 w-full focus:ring-2 focus:ring-pink-200 bg-white" required>
                    @error('timer_minutes')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div class="md:col-span-2">
                    <label class="block font-bold text-pink-700 mb-2">Description (optional)</label>
                    <textarea name="description" class="border-2 border-pink-200 rounded-xl px-4 py-3 w-full focus:ring-2 focus:ring-pink-200 bg-white" rows="2">{{ old('description') }}</textarea>
                </div>
            </div>

            <!-- Questions -->
            <div class="mb-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-2xl font-bold text-pink-600">Questions</h3>
                    <button type="button" @click="addQuestion()" class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-xl font-bold transition">
                        + Add Question
                    </button>
                </div>

                <template x-for="(question, qIndex) in questions" :key="qIndex">
                    <div class="bg-white rounded-2xl p-6 mb-6 border-2 border-pink-100 shadow-lg">
                        <div class="flex justify-between items-center mb-4">
                            <h4 class="text-xl font-bold text-pink-700">Question <span x-text="qIndex + 1"></span></h4>
                            <button type="button" @click="removeQuestion(qIndex)" x-show="questions.length > 1" class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded-lg font-bold transition">
                                Remove
                            </button>
                        </div>

                        <div class="space-y-4">
                            <!-- Question Image -->
                            <div>
                                <label class="block font-bold text-pink-700 mb-2">Question Image (optional)</label>
                                <input type="file" x-bind:name="'questions[' + qIndex + '][image]'" accept="image/*" class="border-2 border-pink-200 rounded-xl px-4 py-3 w-full focus:ring-2 focus:ring-pink-200 bg-white">
                            </div>

                            <!-- Question Text -->
                            <div>
                                <label class="block font-bold text-pink-700 mb-2">Question Text</label>
                                <textarea x-bind:name="'questions[' + qIndex + '][question_text]'" x-model="question.question_text" class="border-2 border-pink-200 rounded-xl px-4 py-3 w-full focus:ring-2 focus:ring-pink-200 bg-white" rows="3" required placeholder="Enter your question here..."></textarea>
                            </div>

                            <!-- Question Background Color -->
                            <div>
                                <label class="block font-bold text-pink-700 mb-2">Question Background Color</label>
                                <div class="flex gap-2 items-center">
                                    <input type="color" x-bind:name="'questions[' + qIndex + '][background_color]'" x-model="question.backgroundColor" class="h-10 w-16 border-2 border-pink-200 rounded-xl cursor-pointer">
                                    <div class="flex gap-2 flex-wrap">
                                        <button type="button" @click="question.backgroundColor = '#F8C5C8'" class="w-7 h-7 rounded-full bg-[#F8C5C8] border-2 border-gray-300 shadow hover:scale-110 transition" title="#F8C5C8"></button>
                                        <button type="button" @click="question.backgroundColor = '#EC769A'" class="w-7 h-7 rounded-full bg-[#EC769A] border-2 border-gray-300 shadow hover:scale-110 transition" title="#EC769A"></button>
                                        <button type="button" @click="question.backgroundColor = '#B5D7D5'" class="w-7 h-7 rounded-full bg-[#B5D7D5] border-2 border-gray-300 shadow hover:scale-110 transition" title="#B5D7D5"></button>
                                        <button type="button" @click="question.backgroundColor = '#79BDBC'" class="w-7 h-7 rounded-full bg-[#79BDBC] border-2 border-gray-300 shadow hover:scale-110 transition" title="#79BDBC"></button>
                                        <button type="button" @click="question.backgroundColor = '#FFB9C6'" class="w-7 h-7 rounded-full bg-[#FFB9C6] border-2 border-gray-300 shadow hover:scale-110 transition" title="#FFB9C6"></button>
                                    </div>
                                </div>
                            </div>

                            <!-- Choices -->
                            <div>
                                <div class="flex justify-between items-center mb-2">
                                    <label class="block font-bold text-pink-700">Choices (select the correct answer)</label>
                                    <button type="button" @click="addChoice(qIndex)" class="text-green-600 hover:text-green-700 font-bold text-sm">
                                        + Add Choice
                                    </button>
                                </div>
                                <template x-for="(choice, cIndex) in question.choices" :key="cIndex">
                                    <div class="flex items-center gap-2 mb-2">
                                        <input type="radio" x-bind:name="'questions[' + qIndex + '][correct_answer]'" x-bind:value="cIndex" x-model="question.correctAnswer" class="w-5 h-5 text-pink-600" required>
                                        <input type="text" x-bind:name="'questions[' + qIndex + '][choices][' + cIndex + ']'" x-model="question.choices[cIndex]" class="border-2 border-pink-200 rounded-xl px-4 py-2 flex-1 focus:ring-2 focus:ring-pink-200 bg-white" placeholder="Enter choice text..." required>
                                        <button type="button" @click="removeChoice(qIndex, cIndex)" x-show="question.choices.length > 2" class="text-red-600 hover:text-red-700 font-bold px-2">
                                            ×
                                        </button>
                                    </div>
                                </template>
                            </div>
                        </div>
                    </div>
                </template>
            </div>

            <!-- Submit Button -->
            <div class="flex justify-end gap-4">
                <a href="{{ route('quizzes.index') }}" class="px-6 py-3 rounded-xl font-bold border-2 border-pink-300 text-pink-700 hover:bg-pink-50 transition">
                    Cancel
                </a>
                <button type="submit" class="bg-gradient-to-r from-pink-500 to-pink-700 text-white px-10 py-3 rounded-2xl font-extrabold shadow-xl hover:from-pink-600 hover:to-pink-800 transition-all duration-150">
                    Create Quiz
                </button>
            </div>
        </form>
    </div>
</div>
</div>

@endsection
