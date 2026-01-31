<?php

namespace App\Observers;

use App\Models\QuizAttempt;
use App\Services\MLPredictionService;
use Illuminate\Support\Facades\Log;

class QuizAttemptObserver
{
    protected $mlService;

    public function __construct(MLPredictionService $mlService)
    {
        $this->mlService = $mlService;
    }

    /**
     * Handle the QuizAttempt "updated" event.
     * Trigger prediction when quiz score is submitted/updated
     */
    public function updated(QuizAttempt $attempt)
    {
        // Trigger prediction when quiz is submitted and score is set
        if ($attempt->isDirty('score') && $attempt->score !== null && $attempt->submitted_at) {
            try {
                Log::info("QuizAttemptObserver: Quiz score updated for student {$attempt->student_id}, triggering prediction");
                $this->mlService->predictRisk($attempt->student_id);
            } catch (\Exception $e) {
                Log::error("QuizAttemptObserver: Failed to generate prediction for student {$attempt->student_id}: " . $e->getMessage());
            }
        }
    }

    /**
     * Handle the QuizAttempt "created" event.
     * This is less common since attempts are usually created and then updated with scores
     */
    public function created(QuizAttempt $attempt)
    {
        // Only trigger if score is already set during creation
        if ($attempt->score !== null && $attempt->submitted_at) {
            try {
                Log::info("QuizAttemptObserver: Quiz attempt created with score for student {$attempt->student_id}, triggering prediction");
                $this->mlService->predictRisk($attempt->student_id);
            } catch (\Exception $e) {
                Log::error("QuizAttemptObserver: Failed to generate prediction: " . $e->getMessage());
            }
        }
    }
}
