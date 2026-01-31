<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\StudentLessonProgress;
use App\Models\StudentRiskPrediction;
use App\Models\Student;
use App\Models\Level;
use App\Models\QuizAttempt;
use App\Models\Quiz;
use Carbon\Carbon;

class MLPredictionService
{
    protected $apiUrl;

    public function __construct()
    {
        // Python API URL from config (which reads from .env)
        $this->apiUrl = config('services.ml_api.url', 'http://localhost:5000');
    }

    /**
     * Calculate features for a student
     */
    public function calculateStudentFeatures($studentId)
    {
        // Get current level for student
        $currentLevel = $this->getCurrentLevel($studentId);
        
        if (!$currentLevel) {
            Log::debug("No current level found for student {$studentId}");
            return null;
        }

        // Get all lesson progress for current level
        $progress = StudentLessonProgress::where('student_id', $studentId)
            ->whereHas('lesson', function($query) use ($currentLevel) {
                $query->where('level_id', $currentLevel->level_id);
            })
            ->get();

        if ($progress->isEmpty()) {
            Log::debug("No lesson progress found for student {$studentId} in level {$currentLevel->level_id}");
            return null;
        }

        // Calculate features
        $avgWatchPct = $progress->avg('watched_percentage') ?? 0;
        $completionRate = $progress->where('video_completed', true)->count() / max($progress->count(), 1);
        $daysInactive = 0;
        $lessonsCompleted = $progress->count();

        // Calculate days inactive from last activity
        $lastActivity = $progress->max('last_activity_at');
        if ($lastActivity) {
            $daysInactive = Carbon::parse($lastActivity)->diffInDays(Carbon::now());
        } else {
            // If no last_activity_at, use last_watched_at or created_at
            $lastWatched = $progress->max('last_watched_at');
            if ($lastWatched) {
                $daysInactive = Carbon::parse($lastWatched)->diffInDays(Carbon::now());
            } else {
                $daysInactive = $progress->max('created_at') 
                    ? Carbon::parse($progress->max('created_at'))->diffInDays(Carbon::now())
                    : 999; // Default to high number if no activity found
            }
        }

        // Get average quiz score from quiz attempts for this level
        $avgQuizScore = $this->getAverageQuizScore($studentId, $currentLevel->level_id);

        return [
            'avg_watch_pct' => round($avgWatchPct, 2),
            'completion_rate' => round($completionRate, 2),
            'avg_quiz_score' => round($avgQuizScore, 2),
            'days_inactive' => $daysInactive,
            'lessons_completed' => $lessonsCompleted,
            'current_level_id' => $currentLevel->level_id
        ];
    }

    /**
     * Get average quiz score for student in a level
     */
    protected function getAverageQuizScore($studentId, $levelId)
    {
        // Get all quizzes for this level
        $quizzes = Quiz::where('level_id', $levelId)
            ->where('is_active', true)
            ->pluck('quiz_id');

        if ($quizzes->isEmpty()) {
            // If no quizzes, return a default score based on lesson progress
            // This is a fallback - in reality, students might not have taken quizzes yet
            return 0;
        }

        // Get all quiz attempts for these quizzes by this student
        $attempts = QuizAttempt::where('student_id', $studentId)
            ->whereIn('quiz_id', $quizzes)
            ->whereNotNull('submitted_at')
            ->whereNotNull('score')
            ->get();

        if ($attempts->isEmpty()) {
            return 0;
        }

        // Return average score
        return $attempts->avg('score') ?? 0;
    }

    /**
     * Get prediction from ML API
     */
    public function predictRisk($studentId)
    {
        $features = $this->calculateStudentFeatures($studentId);
        
        if (!$features) {
            return null;
        }

        try {
            // Call Python API
            $response = Http::timeout(10)
                ->post($this->apiUrl . '/predict', [
                    'avg_watch_pct' => $features['avg_watch_pct'],
                    'completion_rate' => $features['completion_rate'],
                    'avg_quiz_score' => $features['avg_quiz_score'],
                    'days_inactive' => $features['days_inactive'],
                    'lessons_completed' => $features['lessons_completed']
                ]);

            if ($response->successful()) {
                $result = $response->json();
                
                if (isset($result['success']) && $result['success']) {
                    // Save prediction to database
                    $this->savePrediction($studentId, $features, $result);
                    
                    return $result;
                } else {
                    Log::error('ML Prediction API returned error for student ' . $studentId . ': ' . ($result['error'] ?? 'Unknown error'));
                    return null;
                }
            } else {
                Log::error('ML Prediction API request failed for student ' . $studentId . ': HTTP ' . $response->status() . ' - ' . $response->body());
                return null;
            }

        } catch (\Illuminate\Http\Client\ConnectionException $e) {
            Log::error('ML Prediction Connection Error for student ' . $studentId . ': Cannot connect to ' . $this->apiUrl . ' - ' . $e->getMessage());
            return null;
        } catch (\Exception $e) {
            Log::error('ML Prediction Error for student ' . $studentId . ': ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Save prediction to database
     */
    protected function savePrediction($studentId, $features, $result)
    {
        StudentRiskPrediction::updateOrCreate(
            [
                'student_id' => $studentId,
                'current_level_id' => $features['current_level_id']
            ],
            [
                'risk_level' => $result['risk_level'],
                'risk_label' => $result['risk_label'],
                'confidence' => $result['confidence'] * 100, // Convert to percentage
                'avg_watch_pct' => $features['avg_watch_pct'],
                'avg_quiz_score' => $features['avg_quiz_score'],
                'days_inactive' => $features['days_inactive'],
                'lessons_completed' => $features['lessons_completed'],
                'predicted_at' => Carbon::now()
            ]
        );
    }

    /**
     * Get current level for student
     * Returns the level where student has lessons in progress but hasn't passed the quiz yet
     */
    protected function getCurrentLevel($studentId)
    {
        $student = Student::find($studentId);
        if (!$student || !$student->class_id) {
            Log::debug("Student {$studentId} not found or has no class_id");
            return null;
        }

        // First, try to find any level where student has progress (regardless of class)
        // This handles cases where student has progress but class-level relationship is unclear
        $progressWithLevel = StudentLessonProgress::where('student_id', $studentId)
            ->whereHas('lesson', function($query) {
                $query->whereNotNull('level_id');
            })
            ->with('lesson.level')
            ->first();

        if ($progressWithLevel && $progressWithLevel->lesson && $progressWithLevel->lesson->level) {
            $level = $progressWithLevel->lesson->level;
            Log::debug("Found level {$level->level_id} from student progress");
            return $level;
        }

        // Get all levels for the student's class, ordered by level_number
        $levels = Level::where('class_id', $student->class_id)
            ->orderBy('level_number', 'asc')
            ->get();

        if ($levels->isEmpty()) {
            Log::debug("No levels found for class {$student->class_id}");
            // Try to find any level where student has any lesson progress
            $anyLevel = StudentLessonProgress::where('student_id', $studentId)
                ->whereHas('lesson.level')
                ->join('lessons', 'student_lesson_progresses.lesson_id', '=', 'lessons.lesson_id')
                ->join('levels', 'lessons.level_id', '=', 'levels.level_id')
                ->select('levels.*')
                ->first();
            
            if ($anyLevel) {
                Log::debug("Found level {$anyLevel->level_id} from any student progress");
                return Level::find($anyLevel->level_id);
            }
            return null;
        }

        // Find the first level where:
        // 1. Student has started lessons but hasn't passed the quiz, OR
        // 2. Student has lessons in progress
        foreach ($levels as $level) {
            // Check if student has any progress in this level
            $hasProgress = StudentLessonProgress::where('student_id', $studentId)
                ->whereHas('lesson', function($query) use ($level) {
                    $query->where('level_id', $level->level_id);
                })
                ->exists();

            if ($hasProgress) {
                // Check if student has passed the quiz for this level
                $quizzes = Quiz::where('level_id', $level->level_id)
                    ->where('is_active', true)
                    ->pluck('quiz_id');

                if ($quizzes->isNotEmpty()) {
                    $passedQuiz = QuizAttempt::where('student_id', $studentId)
                        ->whereIn('quiz_id', $quizzes)
                        ->whereNotNull('submitted_at')
                        ->where('score', '>=', 60) // Passing score
                        ->exists();

                    // If not passed, this is the current level
                    if (!$passedQuiz) {
                        return $level;
                    }
                } else {
                    // No quiz for this level, so if there's progress, it's the current level
                    return $level;
                }
            }
        }

        // If no level found with progress, return the first level of the class
        // This allows predictions even if student hasn't started yet
        $firstLevel = $levels->first();
        if ($firstLevel) {
            Log::debug("Returning first level {$firstLevel->level_id} for class {$student->class_id} (no progress yet)");
        }
        return $firstLevel;
    }

    /**
     * Check if ML API is available
     */
    public function isApiAvailable()
    {
        try {
            $response = Http::timeout(3)->get($this->apiUrl . '/health');
            return $response->successful();
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Batch predict for entire class
     */
    public function predictForClass($classId)
    {
        // Check if API is available first
        if (!$this->isApiAvailable()) {
            Log::warning("ML API is not available at {$this->apiUrl}");
            return [
                'error' => 'api_unavailable',
                'message' => 'ML API is not running. Please start the API server.',
                'predictions' => []
            ];
        }

        $students = Student::where('class_id', $classId)
            ->with('user')
            ->get();

        $predictions = [];
        $errors = [];

        foreach ($students as $student) {
            // Get student name helper
            $studentName = 'Unknown';
            if ($student->user) {
                $studentName = trim(($student->user->first_name ?? '') . ' ' . ($student->user->last_name ?? ''));
                if (empty($studentName)) {
                    $studentName = $student->user->email ?? 'Unknown';
                }
            }
            
            // First check if student has features (data)
            $features = $this->calculateStudentFeatures($student->student_id);
            
            if (!$features) {
                Log::debug("Student {$student->student_id} has no features/data for prediction");
                // Still include student in errors so they appear in the list
                $errors[] = [
                    'student_id' => $student->student_id,
                    'student_name' => $studentName,
                    'reason' => 'insufficient_data',
                    'has_prediction' => false
                ];
                continue;
            }

            // Try to get prediction
            $prediction = $this->predictRisk($student->student_id);
            
            if ($prediction) {
                $predictions[] = [
                    'student_id' => $student->student_id,
                    'student_name' => $studentName,
                    'risk_level' => $prediction['risk_level'],
                    'risk_label' => $prediction['risk_label'],
                    'confidence' => $prediction['confidence'],
                    'has_prediction' => true
                ];
            } else {
                Log::warning("Failed to get prediction for student {$student->student_id}");
                // Include in errors but mark as attempted
                $errors[] = [
                    'student_id' => $student->student_id,
                    'student_name' => $studentName,
                    'reason' => 'prediction_failed',
                    'has_prediction' => false
                ];
            }
        }

        return [
            'predictions' => $predictions,
            'errors' => $errors,
            'api_available' => true
        ];
    }

    /**
     * Get latest prediction for a student
     */
    public function getLatestPrediction($studentId)
    {
        return StudentRiskPrediction::where('student_id', $studentId)
            ->latest('predicted_at')
            ->first();
    }
}
