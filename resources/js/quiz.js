// resources/js/quiz.js

document.addEventListener('DOMContentLoaded', function() {
    let quizType = 'mcq';
    let quizData = [];
    let currentIndex = 0;
    let answers = [];
    let quizFinished = false;
    let mcqCompleted = false;
    let scrambleCompleted = false;
    let mcqScore = null;
    let scrambleScore = null;

    function renderProgress() {
        let html = '';
        for (let i = 0; i < quizData.length; i++) {
            let status = '';
            if (answers[i] === true) status = 'correct';
            else if (answers[i] === false) status = 'wrong';
            let icon = '';
            if (status === 'correct') icon = '✔️';
            else if (status === 'wrong') icon = '❌';
            else icon = (i+1);
            html += `<div class="quiz-progress-btn ${status} ${i === currentIndex ? 'active' : ''}">${icon}</div>`;
        }
        document.getElementById('quizProgress').innerHTML = html;
    }

    function renderCurrentQuestion() {
        if (!quizData.length) return;
        renderProgress();
        if (quizFinished) {
            let correctAnswers = answers.filter(a => a === true).length;
            let totalQuestions = quizData.length;
            let score = totalQuestions > 0 ? Math.round((correctAnswers / totalQuestions) * 100) : 0;
            
            let html = `<div class='bg-white shadow rounded p-4 mb-4 text-center'>`;
            html += `<div class='text-2xl font-bold mb-4'>${quizType === 'mcq' ? 'Multiple Choice' : 'Scrambled Letters'} Complete!</div>`;
            html += `<div class='text-xl mb-2'>Your Score: <span class='text-green-700'>${correctAnswers}</span> / ${totalQuestions} (${score}%)</div>`;
            
            // Store current quiz score
            if (quizType === 'mcq') {
                mcqScore = score;
            } else {
                scrambleScore = score;
            }
            
            // Check if both quiz types are completed
            if (mcqCompleted && scrambleCompleted) {
                // Calculate average score for both quiz types
                let quizAverageScore = 0;
                if (mcqScore !== null && scrambleScore !== null) {
                    quizAverageScore = Math.round((mcqScore + scrambleScore) / 2);
                } else if (mcqScore !== null) {
                    quizAverageScore = mcqScore;
                } else if (scrambleScore !== null) {
                    quizAverageScore = scrambleScore;
                }
                
                // Store quiz score (average of MCQ and Scrambled Letters)
                if (typeof window.gameScores !== 'undefined') {
                    window.gameScores.quiz = quizAverageScore;
                }
                
                html += `<div class='text-lg font-semibold text-pink-600 mt-4 mb-4'>🎉 All Quiz Games Completed!</div>`;
                html += `<div class='text-lg mb-2'>Quiz Average Score: <span class='text-pink-600 font-bold'>${quizAverageScore}%</span></div>`;
                html += `</div>`;
                document.getElementById('quizArea').innerHTML = html;
                // Move to next game after 3 seconds only when BOTH are completed
                if (typeof moveToNextGame === 'function') {
                    setTimeout(() => moveToNextGame(), 3000);
                }
            } else {
                
                // Show option to switch to other quiz type or continue
                if (quizType === 'mcq' && !scrambleCompleted) {
                    html += `<div class='text-lg mb-4'>Try the Scrambled Letters game!</div>`;
                    html += `<button class='switchToScrambleBtn bg-pink-500 text-white px-4 py-2 rounded mt-2'>Play Scrambled Letters</button>`;
                } else if (quizType === 'scramble' && !mcqCompleted) {
                    html += `<div class='text-lg mb-4'>Try the Multiple Choice game!</div>`;
                    html += `<button class='switchToMcqBtn bg-blue-500 text-white px-4 py-2 rounded mt-2'>Play Multiple Choice</button>`;
                }
                html += `</div>`;
                document.getElementById('quizArea').innerHTML = html;
            }
            return;
        }
        let q = quizData[currentIndex];
        let html = '';
        if (quizType === 'mcq') {
            html += `<div class='bg-white shadow rounded p-4 mb-4'>`;
            html += `<div class='mb-2 font-semibold'>Q${currentIndex+1}: ${q.definition}</div>`;
            html += `<div class='flex flex-wrap gap-2'>`;
            q.options.forEach(opt => {
                html += `<button class='optionBtn bg-gray-200 px-3 py-1 rounded' data-answer='${q.answer}' data-opt='${opt}'>${opt}</button>`;
            });
            html += `</div>`;
            html += `<div class='mt-4 flex justify-between'>`;
            html += `<button class='prevBtn bg-gray-300 px-3 py-1 rounded' ${currentIndex === 0 ? 'disabled' : ''}>Previous</button>`;
            html += `<button class='nextBtn bg-blue-500 text-white px-3 py-1 rounded' ${currentIndex === quizData.length-1 ? 'disabled' : ''}>Next</button>`;
            html += `</div>`;
            html += `<span class='mcqResult ml-2'></span>`;
            html += `</div>`;
        } else {
            let letters = Array.from(q.answer);
            let scrambled = [...letters];
            scrambled.sort(() => Math.random() - 0.5);
            html += `<div class='bg-white shadow rounded p-4 mb-4'>`;
            html += `<div class='mb-2 font-semibold'>Q${currentIndex+1}: <span class='text-gray-700'>${q.definition || ''}</span></div>`;
            html += `<div class='flex flex-wrap gap-2 mb-2'>`;
            scrambled.forEach((ltr, i) => {
                html += `<button class='scrambleLetterBtn px-3 py-1 rounded' style='background-color:#FC8EAC;color:white;' data-letter='${ltr}' data-idx='${i}'>${ltr}</button>`;
            });
            html += `</div>`;
            html += `<div class='flex items-center gap-2 mb-2'>`;
            html += `<input type='text' readonly class='scrambleInput border px-2 py-1 rounded w-1/2' data-answer='${q.answer}' placeholder='Build the word...'>`;
            html += `<button class='clearScrambleBtn bg-yellow-500 text-white px-3 py-2 rounded font-bold'>Clear</button>`;
            html += `<button class='checkScrambleBtn bg-green-600 text-white px-4 py-2 rounded font-bold border-2 border-green-800 shadow-lg'>Check</button>`;
            html += `<span class='scrambleResult ml-2'></span>`;
            html += `</div>`;
            html += `<div class='mt-4 flex justify-between'>`;
            html += `<button class='prevBtn bg-gray-300 px-3 py-1 rounded' ${currentIndex === 0 ? 'disabled' : ''}>Previous</button>`;
            html += `<button class='nextBtn bg-blue-500 text-white px-3 py-1 rounded' ${currentIndex === quizData.length-1 ? 'disabled' : ''}>Next</button>`;
            html += `</div>`;
            html += `</div>`;
        }
        document.getElementById('quizArea').innerHTML = html;
    }

    function fetchQuiz(type) {
        // Check if this quiz type is already completed
        if (type === 'mcq' && mcqCompleted) {
            quizFinished = true;
            renderCurrentQuestion();
            return;
        }
        if (type === 'scramble' && scrambleCompleted) {
            quizFinished = true;
            renderCurrentQuestion();
            return;
        }
        
        // Reset answers when starting a new quiz
        answers = [];
        currentIndex = 0;
        quizFinished = false;
        
        // Get lesson_id from URL or data attribute
        const urlParams = new URLSearchParams(window.location.search);
        const lessonId = urlParams.get('lesson_id') || document.getElementById('quizArea')?.dataset.lessonId || '';
        const url = window.quizRoute + '?type=' + type + (lessonId ? '&lesson_id=' + lessonId : '');
        fetch(url)
            .then(res => res.json())
            .then(data => {
                quizData = data.quiz;
                currentIndex = 0;
                answers = Array(quizData.length).fill(null);
                quizFinished = false;
                renderCurrentQuestion();
            });
    }

    window.setActiveTab = function(tab) {
        const mcqBtn = document.getElementById('mcqBtn');
        const scrambleBtn = document.getElementById('scrambleBtn');
        // Remove all custom and Tailwind classes
        mcqBtn.classList.remove('ring-4', 'ring-blue-300', 'bg-blue-700', 'text-gray-700', 'text-white');
        scrambleBtn.classList.remove('ring-4', 'ring-purple-300', 'bg-purple-700', 'ring-rose-300', 'bg-rose-700', 'text-gray-700', 'text-white', 'custom-rose-bg', 'custom-rose-ring');
        if (tab === 'mcq') {
            mcqBtn.classList.add('ring-4', 'text-white');
            mcqBtn.style.backgroundColor = '#F8C5C8'; // light pink (left color)
            mcqBtn.style.boxShadow = '0 0 0 4px #F8C5C8';
            mcqBtn.style.borderColor = '#F8C5C8';
            scrambleBtn.classList.add('text-gray-700');
            scrambleBtn.style.backgroundColor = '';
            scrambleBtn.style.boxShadow = '';
            scrambleBtn.style.borderColor = '';
        } else {
            // Use the right-side color from palette
            scrambleBtn.classList.add('ring-4', 'text-white');
            scrambleBtn.style.backgroundColor = '#FC8EAC'; // right color
            scrambleBtn.style.boxShadow = '0 0 0 4px #FC8EAC'; // ring effect
            scrambleBtn.style.borderColor = '#FC8EAC';
            mcqBtn.classList.add('text-gray-700');
            mcqBtn.style.backgroundColor = '';
            mcqBtn.style.boxShadow = '';
            mcqBtn.style.borderColor = '';
        }
    };

    document.getElementById('mcqBtn').onclick = function() {
        quizType = 'mcq';
        setActiveTab('mcq');
        quizFinished = false;
        fetchQuiz('mcq');
    };
    document.getElementById('scrambleBtn').onclick = function() {
        quizType = 'scramble';
        setActiveTab('scramble');
        quizFinished = false;
        fetchQuiz('scramble');
    };
    setActiveTab('mcq');
    window.quizRoute = document.getElementById('quizArea').dataset.route;
    fetchQuiz('mcq');

    document.getElementById('quizArea').addEventListener('click', function(e) {
        // MCQ logic
        if (e.target.classList.contains('optionBtn')) {
            let correct = e.target.dataset.answer;
            let chosen = e.target.dataset.opt;
            let allBtns = e.target.parentElement.querySelectorAll('.optionBtn');
            allBtns.forEach(btn => {
                btn.disabled = true;
                btn.classList.remove('bg-green-400', 'bg-red-400', 'bg-gray-200');
                if (btn.dataset.opt === correct) {
                    btn.classList.add('bg-green-400');
                } else if (btn.dataset.opt === chosen && chosen !== correct) {
                    btn.classList.add('bg-red-400');
                } else {
                    btn.classList.add('bg-gray-200');
                }
            });
            answers[currentIndex] = (chosen === correct);
            renderProgress();
            if (currentIndex === quizData.length - 1) {
                quizFinished = true;
                mcqCompleted = true;
                renderCurrentQuestion();
            }
            if (chosen === correct) {
                e.target.parentElement.nextElementSibling.querySelector('.mcqResult').textContent = '✅ Correct!';
            } else {
                e.target.parentElement.nextElementSibling.querySelector('.mcqResult').textContent = '❌ Wrong!';
            }
        }
        if (e.target.classList.contains('nextBtn')) {
            if (currentIndex < quizData.length - 1) {
                currentIndex++;
                renderCurrentQuestion();
            }
        }
        if (e.target.classList.contains('prevBtn')) {
            if (currentIndex > 0) {
                currentIndex--;
                renderCurrentQuestion();
            }
        }
        // Handle switching to other quiz type
        if (e.target.classList.contains('switchToScrambleBtn')) {
            quizType = 'scramble';
            setActiveTab('scramble');
            quizFinished = false;
            fetchQuiz('scramble');
        }
        if (e.target.classList.contains('switchToMcqBtn')) {
            quizType = 'mcq';
            setActiveTab('mcq');
            quizFinished = false;
            fetchQuiz('mcq');
        }
        // Scrambled letter logic
        if (e.target.classList.contains('scrambleLetterBtn')) {
            let input = e.target.closest('.bg-white').querySelector('.scrambleInput');
            input.value += e.target.dataset.letter;
            e.target.disabled = true;
        }
        if (e.target.classList.contains('clearScrambleBtn')) {
            let box = e.target.closest('.bg-white');
            let input = box.querySelector('.scrambleInput');
            input.value = '';
            box.querySelectorAll('.scrambleLetterBtn').forEach(btn => btn.disabled = false);
            box.querySelector('.scrambleResult').textContent = '';
        }
        if (e.target.classList.contains('checkScrambleBtn')) {
            let box = e.target.closest('.bg-white');
            let input = box.querySelector('.scrambleInput');
            let result = box.querySelector('.scrambleResult');
            if (input.value.trim() === input.dataset.answer) {
                result.textContent = '✅ Correct!';
                result.className = 'scrambleResult text-green-600 ml-2';
                answers[currentIndex] = true;
                renderProgress();
            } else {
                result.textContent = '❌ Try again!';
                result.className = 'scrambleResult text-red-600 ml-2';
                answers[currentIndex] = false;
                renderProgress();
            }
            // If last question, show score after any check
            if (currentIndex === quizData.length - 1) {
                quizFinished = true;
                scrambleCompleted = true;
                setTimeout(renderCurrentQuestion, 600); // brief delay to show feedback
            }
        }
    });
});
