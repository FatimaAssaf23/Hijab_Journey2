// resources/js/mcq-quiz.js - Multiple Choice Quiz Handler

document.addEventListener('DOMContentLoaded', function() {
    const mcqQuizArea = document.getElementById('mcqQuizArea');
    if (!mcqQuizArea) return;
    
    let quizData = [];
    let currentIndex = 0;
    let answers = [];
    let quizFinished = false;

    function renderProgress() {
        const progressEl = document.getElementById('mcqQuizProgress');
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
            
            // Store score for MCQ game
            if (typeof window.gameScores !== 'undefined') {
                window.gameScores.mcq = score;
            }
            
            // Save score to database
            const lessonId = mcqQuizArea.dataset.lessonId;
            if (lessonId) {
                // Try to get route from data attribute or use window variable
                const saveScoreRoute = mcqQuizArea.dataset.saveScoreRoute || 
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
                        game_type: 'mcq',
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
                    console.log('MCQ score saved successfully:', data);
                })
                .catch(error => {
                    console.error('Error saving MCQ score:', error);
                });
            }
            
            let html = `<div class='bg-white shadow rounded p-4 mb-4 text-center'>`;
            html += `<div class='text-2xl font-bold mb-4'>Multiple Choice Complete!</div>`;
            html += `<div class='text-xl mb-2'>Your Score: <span class='text-green-700'>${correctAnswers}</span> / ${totalQuestions} (${score}%)</div>`;
            html += `</div>`;
            mcqQuizArea.innerHTML = html;
            // Move to next game after 3 seconds
            if (typeof moveToNextGame === 'function') {
                setTimeout(() => moveToNextGame(), 3000);
            }
            return;
        }
        let q = quizData[currentIndex];
        let html = '';
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
        mcqQuizArea.innerHTML = html;
    }

    function fetchQuiz() {
        const urlParams = new URLSearchParams(window.location.search);
        const lessonId = urlParams.get('lesson_id') || mcqQuizArea?.dataset.lessonId || '';
        const url = mcqQuizArea.dataset.route + '?type=mcq' + (lessonId ? '&lesson_id=' + lessonId : '');
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
                mcqQuizArea.innerHTML = '<div class="text-red-600">Error loading quiz. Please try again.</div>';
            });
    }

    mcqQuizArea.addEventListener('click', function(e) {
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
    });

    // Initialize
    if (mcqQuizArea) {
        fetchQuiz();
    }
});
