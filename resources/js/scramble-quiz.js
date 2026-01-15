// resources/js/scramble-quiz.js - Scrambled Letters Quiz Handler

document.addEventListener('DOMContentLoaded', function() {
    const scrambleQuizArea = document.getElementById('scrambleQuizArea');
    if (!scrambleQuizArea) return;
    
    let quizData = [];
    let currentIndex = 0;
    let answers = [];
    let quizFinished = false;

    function renderProgress() {
        const progressEl = document.getElementById('scrambleQuizProgress');
        if (!progressEl) return;
        
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
        progressEl.innerHTML = html;
    }

    function renderCurrentQuestion() {
        if (!quizData.length) return;
        renderProgress();
        if (quizFinished) {
            let correctAnswers = answers.filter(a => a === true).length;
            let totalQuestions = quizData.length;
            let score = totalQuestions > 0 ? Math.round((correctAnswers / totalQuestions) * 100) : 0;
            
            // Store score for Scrambled Letters game
            if (typeof window.gameScores !== 'undefined') {
                window.gameScores.scramble = score;
            }
            
            // Save score to database
            const lessonId = scrambleQuizArea.dataset.lessonId;
            if (lessonId) {
                // Try to get route from data attribute or use window variable
                const saveScoreRoute = scrambleQuizArea.dataset.saveScoreRoute || 
                    (typeof window !== 'undefined' && window.saveScoreRoute) || 
                    '/student/games/save-score';
                const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
                
                fetch(saveScoreRoute, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken || '',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        lesson_id: parseInt(lessonId),
                        game_type: 'scramble',
                        score: score
                    })
                })
                .then(response => {
                    if (!response.ok) {
                        return response.json().then(err => Promise.reject(err));
                    }
                    return response.json();
                })
                .then(data => {
                    console.log('Scramble score saved successfully:', data);
                })
                .catch(error => {
                    console.error('Error saving Scramble score:', error);
                });
            }
            
            let html = `<div class='bg-white shadow rounded p-4 mb-4 text-center'>`;
            html += `<div class='text-2xl font-bold mb-4'>Scrambled Letters Complete!</div>`;
            html += `<div class='text-xl mb-2'>Your Score: <span class='text-green-700'>${correctAnswers}</span> / ${totalQuestions} (${score}%)</div>`;
            html += `</div>`;
            scrambleQuizArea.innerHTML = html;
            // Move to next game after 3 seconds
            if (typeof moveToNextGame === 'function') {
                setTimeout(() => moveToNextGame(), 3000);
            }
            return;
        }
        let q = quizData[currentIndex];
        let letters = Array.from(q.answer);
        let scrambled = [...letters];
        scrambled.sort(() => Math.random() - 0.5);
        let html = '';
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
        scrambleQuizArea.innerHTML = html;
    }

    function fetchQuiz() {
        const urlParams = new URLSearchParams(window.location.search);
        const lessonId = urlParams.get('lesson_id') || scrambleQuizArea?.dataset.lessonId || '';
        const url = scrambleQuizArea.dataset.route + '?type=scramble' + (lessonId ? '&lesson_id=' + lessonId : '');
        fetch(url)
            .then(res => res.json())
            .then(data => {
                quizData = data.quiz;
                currentIndex = 0;
                answers = Array(quizData.length).fill(null);
                quizFinished = false;
                renderCurrentQuestion();
            })
            .catch(error => {
                console.error('Error fetching quiz:', error);
                scrambleQuizArea.innerHTML = '<div class="text-red-600">Error loading quiz. Please try again.</div>';
            });
    }

    scrambleQuizArea.addEventListener('click', function(e) {
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
    });

    // Initialize
    if (scrambleQuizArea) {
        fetchQuiz();
    }
});
