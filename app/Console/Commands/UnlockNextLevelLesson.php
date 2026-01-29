<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Student;
use App\Models\Quiz;
use App\Models\QuizAttempt;
use App\Models\ClassLessonVisibility;
use App\Http\Controllers\QuizController;

class UnlockNextLevelLesson extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'quiz:unlock-next-level {student_id} {quiz_id}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Manually unlock the next level first lesson for a student who passed a quiz';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $studentId = $this->argument('student_id');
        $quizId = $this->argument('quiz_id');
        
        $student = Student::find($studentId);
        if (!$student) {
            $this->error("Student with ID {$studentId} not found.");
            return 1;
        }
        
        $quiz = Quiz::with('level')->find($quizId);
        if (!$quiz) {
            $this->error("Quiz with ID {$quizId} not found.");
            return 1;
        }
        
        // Check if student passed the quiz
        $attempt = QuizAttempt::where('quiz_id', $quizId)
            ->where('student_id', $studentId)
            ->whereNotNull('submitted_at')
            ->latest()
            ->first();
        
        if (!$attempt) {
            $this->error("Student has not taken this quiz yet.");
            return 1;
        }
        
        $score = $attempt->score ?? 0;
        $this->info("Student score: {$score}%");
        
        if ($score < 60) {
            $this->error("Student did not pass the quiz (score: {$score}%, required: 60%).");
            return 1;
        }
        
        if (!$quiz->level) {
            $this->error("Quiz has no level assigned.");
            return 1;
        }
        
        $this->info("Quiz Level: {$quiz->level->level_name} (ID: {$quiz->level->level_id})");
        
        // Use the unlock method from QuizController
        $controller = new QuizController();
        $reflection = new \ReflectionClass($controller);
        $method = $reflection->getMethod('unlockNextLevelFirstLesson');
        $method->setAccessible(true);
        $method->invoke($controller, $studentId, $quiz->level);
        
        $this->info("Unlock process completed. Check logs for details.");
        
        return 0;
    }
}
