@extends('layouts.app')
@section('content')
<div class="max-w-5xl mx-auto py-10" x-data="quizTimer({{ ($quiz->timer_minutes ?? 10) * 60 }})">
    <div class="bg-gradient-to-br from-pink-50 via-white to-pink-100 shadow-2xl rounded-3xl p-10 mb-10 border-2 border-pink-200">
        <div class="flex justify-between items-center mb-6">
            <div>
                <h2 class="text-3xl font-extrabold text-pink-600 flex items-center gap-3 drop-shadow mb-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-9 w-9 text-pink-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    {{ $quiz->title }}
                </h2>
                @if($quiz->description)
                    <p class="text-gray-600 mt-2">{{ $quiz->description }}</p>
                @endif
            </div>
            <div class="text-right">
                <div class="bg-white rounded-xl shadow-lg p-4 border-2 border-pink-300" id="timer-border">
                    <div class="text-sm text-gray-600 mb-1">Time Remaining</div>
                    <div class="text-3xl font-bold text-pink-600" 
                         x-bind:class="isTimeOut ? 'text-red-600' : (timeRemaining < 60 ? 'text-red-600' : 'text-pink-600')"
                         id="timer-display"
                         style="min-height: 40px; display: flex; align-items: center; justify-content: center;">
                         @php
                             $minutes = $quiz->timer_minutes ?? 10;
                             $mins = str_pad($minutes, 2, '0', STR_PAD_LEFT);
                             $initialTime = $mins . ':00';
                         @endphp
                         <span id="timer-value" 
                               style="display: inline-block; min-width: 60px; text-align: center;">
                             {{ $initialTime }}
                         </span>
                    </div>
                    <div x-show="isTimeOut" class="text-xs text-red-600 font-bold mt-1" x-cloak>TIME EXPIRED</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Time Out Message Modal -->
    <div x-show="showTimeOutMessage" 
         x-cloak
         class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50"
         style="display: none;">
        <div class="bg-white rounded-3xl shadow-2xl p-8 max-w-md mx-4 border-4 border-red-500">
            <div class="text-center">
                <div class="mb-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-20 w-20 text-red-500 mx-auto" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <h3 class="text-3xl font-extrabold text-red-600 mb-4">Time is Out!</h3>
                <p class="text-gray-700 text-lg mb-6">Your time has expired. The quiz will be submitted automatically with your current answers and you will be redirected to your grades page.</p>
                <button @click="submitQuiz()" 
                        id="timeOutOkBtn"
                        class="bg-gradient-to-r from-red-500 to-red-700 text-white px-8 py-3 rounded-xl font-extrabold shadow-xl hover:from-red-600 hover:to-red-800 transition-all duration-150">
                    OK, Submit & View Grades
                </button>
            </div>
        </div>
    </div>

    <form id="quizForm" action="{{ route('student.quizzes.submit', $quiz->quiz_id) }}" method="POST" x-bind:class="{ 'pointer-events-none opacity-50': isTimeOut }">
        @csrf
        <input type="hidden" name="_token" value="{{ csrf_token() }}" id="csrf-token-input">
        <div class="space-y-6">
            @foreach($quiz->questions->sortBy('question_order') as $index => $question)
                <div class="bg-white rounded-3xl shadow-xl border border-pink-100 p-6" style="background-color: {{ $question->background_color ?? '#FFFFFF' }}">
                    <div class="mb-4">
                        <h3 class="text-xl font-bold text-pink-700 mb-4 flex items-start gap-3">
                            <span class="bg-pink-500 text-white rounded-full w-8 h-8 flex items-center justify-center text-sm font-extrabold flex-shrink-0">
                                {{ $index + 1 }}
                            </span>
                            <span class="flex-1">{{ $question->question_text }}</span>
                        </h3>
                        
                        @if($question->image_path)
                            <div class="mb-4 flex justify-center">
                                <img src="{{ asset('storage/' . $question->image_path) }}" alt="Question Image" class="max-w-md max-h-[300px] w-auto h-auto rounded-lg shadow-md object-contain">
                            </div>
                        @endif
                    </div>

                    <div class="space-y-3">
                        @foreach($question->options->sortBy('option_order') as $option)
                            <label class="flex items-center p-4 rounded-xl border-2 cursor-pointer transition-all duration-150 hover:shadow-md"
                                   x-bind:class="answers['{{ $question->question_id }}'] == {{ $option->option_id }} ? 'border-pink-500 bg-pink-50' : 'border-gray-200 hover:border-pink-300'">
                                <input type="radio" 
                                       name="answers[{{ $question->question_id }}]" 
                                       value="{{ $option->option_id }}"
                                       x-model="answers['{{ $question->question_id }}']"
                                       x-bind:disabled="isTimeOut"
                                       class="w-5 h-5 text-pink-600 focus:ring-pink-500 focus:ring-2"
                                       x-bind:required="!isTimeOut">
                                <span class="ml-4 text-gray-700 font-medium flex-1">{{ $option->option_text }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-8 flex justify-between items-center">
            <a href="{{ route('student.quizzes') }}" class="bg-gray-300 text-gray-700 px-6 py-3 rounded-xl font-bold hover:bg-gray-400 transition">
                Cancel
            </a>
            <button type="submit" 
                    x-bind:disabled="isTimeOut"
                    @click="handleSubmit($event)"
                    class="bg-gradient-to-r from-pink-500 to-pink-700 text-white px-8 py-3 rounded-xl font-extrabold shadow-xl hover:from-pink-600 hover:to-pink-800 transition-all duration-150 disabled:opacity-50 disabled:cursor-not-allowed">
                Submit Quiz
            </button>
        </div>
    </form>
</div>

@push('scripts')
<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('quizTimer', (initialTime) => ({
        timeRemaining: initialTime,
        answers: {},
        timeOut: false,
        showTimeOutMessage: false,
        timerInterval: null,
        get isTimeOut() { return this.timeOut === true; },
        init() {
            if (!this.timeRemaining || this.timeRemaining <= 0) {
                this.timeRemaining = 600;
            }
            this.timeRemaining = parseInt(this.timeRemaining) || 600;
            
            const formatTime = (seconds) => {
                const secs = Math.max(0, Math.floor(parseInt(seconds) || 0));
                const mins = Math.floor(secs / 60);
                const remainingSecs = secs % 60;
                return `${mins.toString().padStart(2, "0")}:${remainingSecs.toString().padStart(2, "0")}`;
            };
            
            const self = this;
            
            const updateDisplay = () => {
                const timerValue = document.getElementById("timer-value");
                if (timerValue) {
                    timerValue.textContent = formatTime(self.timeRemaining);
                }
                
                const timerDisplay = document.getElementById("timer-display");
                const timerBorder = document.getElementById("timer-border");
                
                if (timerDisplay) {
                    if (self.timeRemaining <= 0 || self.timeOut) {
                        timerDisplay.classList.remove("text-pink-600");
                        timerDisplay.classList.add("text-red-600");
                    } else if (self.timeRemaining < 60) {
                        timerDisplay.classList.remove("text-pink-600");
                        timerDisplay.classList.add("text-red-600");
                    } else {
                        timerDisplay.classList.remove("text-red-600");
                        timerDisplay.classList.add("text-pink-600");
                    }
                }
                
                if (timerBorder) {
                    if (self.timeRemaining <= 0 || self.timeOut) {
                        timerBorder.classList.remove("border-pink-300", "border-red-300");
                        timerBorder.classList.add("border-red-500");
                    } else if (self.timeRemaining < 60) {
                        timerBorder.classList.remove("border-pink-300");
                        timerBorder.classList.add("border-red-300");
                    } else {
                        timerBorder.classList.remove("border-red-300", "border-red-500");
                        timerBorder.classList.add("border-pink-300");
                    }
                }
            };
            
            const startCountdown = () => {
                updateDisplay();
                
                if (self.timerInterval) {
                    clearInterval(self.timerInterval);
                    self.timerInterval = null;
                }
                
                self.timerInterval = setInterval(() => {
                    if (self.timeOut) {
                        if (self.timerInterval) {
                            clearInterval(self.timerInterval);
                            self.timerInterval = null;
                        }
                        return;
                    }
                    
                    if (self.timeRemaining > 0) {
                        self.timeRemaining = self.timeRemaining - 1;
                        updateDisplay();
                    }
                    
                    if (self.timeRemaining <= 0) {
                        if (self.timerInterval) {
                            clearInterval(self.timerInterval);
                            self.timerInterval = null;
                        }
                        updateDisplay();
                        self.timeOut = true;
                        self.showTimeOutMessage = true;
                        setTimeout(() => {
                            self.handleTimeOut();
                        }, 50);
                    }
                }, 1000);
            };
            
            if (document.readyState === "loading") {
                document.addEventListener("DOMContentLoaded", () => {
                    setTimeout(startCountdown, 100);
                });
            } else {
                setTimeout(startCountdown, 100);
            }
            
            this.refreshCsrfToken();
        },
        refreshCsrfToken() {
            setInterval(async () => {
                try {
                    const response = await fetch(window.location.href, {
                        method: 'GET',
                        headers: {
                            "X-Requested-With": "XMLHttpRequest",
                            "Accept": "text/html",
                        },
                        credentials: 'same-origin'
                    });
                    
                    if (response.ok) {
                        const html = await response.text();
                        const parser = new DOMParser();
                        const doc = parser.parseFromString(html, "text/html");
                        const metaToken = doc.querySelector("meta[name=\"csrf-token\"]");
                        
                        if (metaToken) {
                            const newToken = metaToken.getAttribute("content");
                            const csrfInput = document.getElementById("csrf-token-input") || document.querySelector("input[name=\"_token\"]");
                            if (csrfInput && csrfInput.value !== newToken) {
                                csrfInput.value = newToken;
                            }
                            const existingMeta = document.querySelector("meta[name=\"csrf-token\"]");
                            if (existingMeta) {
                                existingMeta.setAttribute("content", newToken);
                            }
                        }
                    }
                } catch (error) {
                    // Silent error handling
                }
            }, 90 * 1000);
        },
        handleTimeOut() {
            const self = this;
            
            if (this.timerInterval) {
                clearInterval(this.timerInterval);
                this.timerInterval = null;
            }
            
            Object.assign(this, {
                timeOut: true,
                timeRemaining: 0,
                showTimeOutMessage: true
            });
            
            const timerValue = document.getElementById("timer-value");
            if (timerValue) {
                timerValue.textContent = "00:00";
            }
            
            const timerDisplay = document.getElementById("timer-display");
            const timerBorder = document.getElementById("timer-border");
            if (timerDisplay) {
                timerDisplay.classList.remove("text-pink-600");
                timerDisplay.classList.add("text-red-600");
            }
            if (timerBorder) {
                timerBorder.classList.remove("border-pink-300", "border-red-300");
                timerBorder.classList.add("border-red-500");
            }
            
            this.showTimeOutMessage = true;
            
            const allInputs = document.querySelectorAll("input[type=\"radio\"], input[type=\"text\"], button, textarea, select");
            allInputs.forEach(el => {
                if (el.id !== "timeOutOkBtn") {
                    el.disabled = true;
                    el.style.cursor = "not-allowed";
                }
            });
            
            const form = document.getElementById("quizForm");
            if (form) {
                const submitBtn = form.querySelector("button[type=\"submit\"]");
                if (submitBtn) {
                    submitBtn.disabled = true;
                    submitBtn.style.opacity = "0.5";
                    submitBtn.style.cursor = "not-allowed";
                }
            }
            
            setTimeout(function() {
                if (self.showTimeOutMessage) {
                    self.submitQuiz();
                }
            }, 3000);
        },
        formatTime(seconds) {
            const secs = Math.max(0, Math.floor(parseInt(seconds) || 0));
            const mins = Math.floor(secs / 60);
            const remainingSecs = secs % 60;
            return `${mins.toString().padStart(2, "0")}:${remainingSecs.toString().padStart(2, "0")}`;
        },
        async submitQuiz() {
            if (this.timerInterval) {
                clearInterval(this.timerInterval);
                this.timerInterval = null;
            }
            
            this.showTimeOutMessage = false;
            
            try {
                await this.refreshTokenNow();
            } catch (error) {
                // Silent error handling
            }
            
            const form = document.getElementById("quizForm");
            if (!form) {
                return;
            }
            
            if (this.timeOut) {
                let timeExpiredInput = form.querySelector("input[name=\"time_expired\"]");
                if (!timeExpiredInput) {
                    timeExpiredInput = document.createElement('input');
                    timeExpiredInput.type = "hidden";
                    timeExpiredInput.name = "time_expired";
                    form.appendChild(timeExpiredInput);
                }
                timeExpiredInput.value = "1";
            }
            
            form.submit();
        },
        async refreshTokenNow() {
            try {
                const response = await fetch(window.location.href, {
                    method: 'GET',
                    headers: {
                        "X-Requested-With": "XMLHttpRequest",
                        "Accept": "text/html",
                    },
                    credentials: 'same-origin'
                });
                
                if (response.ok) {
                    const html = await response.text();
                    const parser = new DOMParser();
                    const doc = parser.parseFromString(html, "text/html");
                    const metaToken = doc.querySelector("meta[name=\"csrf-token\"]");
                    
                    if (metaToken) {
                        const newToken = metaToken.getAttribute("content");
                        const csrfInput = document.getElementById("csrf-token-input") || document.querySelector("input[name=\"_token\"]");
                        if (csrfInput) {
                            csrfInput.value = newToken;
                        }
                        const existingMeta = document.querySelector("meta[name=\"csrf-token\"]");
                        if (existingMeta) {
                            existingMeta.setAttribute("content", newToken);
                        }
                    }
                }
            } catch (error) {
                // Silent error handling
            }
        },
        handleSubmit(event) {
            this.refreshTokenNow().then(() => {
                // Token refreshed
            }).catch(() => {
                // Error handled silently
            });
        }
    }));
});
</script>
@endpush
@endsection
