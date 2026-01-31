<?php

namespace App\Observers;

use App\Models\StudentLessonProgress;
use App\Services\MLPredictionService;
use Illuminate\Support\Facades\Log;

class StudentLessonProgressObserver
{
    protected $mlService;

    public function __construct(MLPredictionService $mlService)
    {
        $this->mlService = $mlService;
    }

    /**
     * Handle the StudentLessonProgress "updated" event.
     */
    public function updated(StudentLessonProgress $progress)
    {
        // Trigger prediction when lesson video is completed
        if ($progress->isDirty('video_completed') && $progress->video_completed) {
            try {
                Log::info("StudentLessonProgressObserver: Video completed for student {$progress->student_id}, triggering prediction");
                $this->mlService->predictRisk($progress->student_id);
            } catch (\Exception $e) {
                Log::error("StudentLessonProgressObserver: Failed to generate prediction for student {$progress->student_id}: " . $e->getMessage());
            }
        }

        // Also trigger when quiz score is updated (if applicable)
        if ($progress->isDirty('score') && $progress->score !== null) {
            try {
                Log::info("StudentLessonProgressObserver: Score updated for student {$progress->student_id}, triggering prediction");
                $this->mlService->predictRisk($progress->student_id);
            } catch (\Exception $e) {
                Log::error("StudentLessonProgressObserver: Failed to generate prediction for student {$progress->student_id}: " . $e->getMessage());
            }
        }
    }

    /**
     * Handle the StudentLessonProgress "created" event.
     * Trigger prediction when a new lesson progress is created (student starts a lesson)
     */
    public function created(StudentLessonProgress $progress)
    {
        // Optional: Trigger prediction when student starts a new lesson
        // This can help track early engagement patterns
        // Uncomment if you want predictions on lesson start
        /*
        try {
            Log::info("StudentLessonProgressObserver: New lesson progress created for student {$progress->student_id}");
            // Only trigger if student has enough data
            $features = $this->mlService->calculateStudentFeatures($progress->student_id);
            if ($features) {
                $this->mlService->predictRisk($progress->student_id);
            }
        } catch (\Exception $e) {
            Log::error("StudentLessonProgressObserver: Failed to generate prediction: " . $e->getMessage());
        }
        */
    }
}
