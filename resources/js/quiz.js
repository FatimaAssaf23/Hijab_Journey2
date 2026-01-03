// resources/js/quiz.js

document.addEventListener('DOMContentLoaded', function() {
    let quizType = 'mcq';
    let quizData = [];
    let currentIndex = 0;
    let answers = [];
    let quizFinished = false;

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
            let score = answers.filter(a => a === true).length;
            let html = `<div class='bg-white shadow rounded p-4 mb-4 text-center'>`;
            html += `<div class='text-2xl font-bold mb-4'>Quiz Complete!</div>`;
            html += `<div class='text-xl mb-2'>Your Score: <span class='text-green-700'>${score}</span> / ${quizData.length}</div>`;
            html += `<button class='restartBtn bg-blue-500 text-white px-4 py-2 rounded mt-4'>Restart Quiz</button>`;
            html += `</div>`;
            document.getElementById('quizArea').innerHTML = html;
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
        fetch(window.quizRoute + '?type=' + type)
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
        fetchQuiz('mcq');
    };
    document.getElementById('scrambleBtn').onclick = function() {
        quizType = 'scramble';
        setActiveTab('scramble');
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
        if (e.target.classList.contains('restartBtn')) {
            answers = Array(quizData.length).fill(null);
            quizFinished = false;
            currentIndex = 0;
            renderCurrentQuestion();
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
                setTimeout(renderCurrentQuestion, 600); // brief delay to show feedback
            }
        }
    });
});
