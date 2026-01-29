<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lesson extends Model
{
    use HasFactory;

    protected $primaryKey = 'lesson_id';

    protected $fillable = [
        'level_id',
        'teacher_id',
        'uploaded_by_admin_id',
        'title',
        'skills',
        'icon',
        'description',
        'content_url',
        'video_size',
        'video_format',
        'video_duration_seconds',
        'duration_minutes',
        'lesson_order',
        'is_visible',
        'upload_date',
    ];

    protected function casts(): array
    {
        return [
            'skills' => 'integer',
            'duration_minutes' => 'integer',
            'lesson_order' => 'integer',
            'is_visible' => 'boolean',
            'upload_date' => 'datetime',
            'video_size' => 'integer',
            'video_duration_seconds' => 'integer',
        ];
    }

    /**
     * Get the level that owns the lesson.
     */
    public function level()
    {
        return $this->belongsTo(Level::class, 'level_id', 'level_id');
    }

    /**
     * Get the teacher that manages the lesson.
     */
    public function teacher()
    {
        return $this->belongsTo(User::class, 'teacher_id', 'user_id');
    }

    /**
     * Get the admin who uploaded the lesson.
     */
    public function uploadedByAdmin()
    {
        return $this->belongsTo(User::class, 'uploaded_by_admin_id', 'user_id');
    }

    /**
     * Get the student progresses for the lesson.
     */
    public function studentProgresses()
    {
        return $this->hasMany(StudentLessonProgress::class, 'lesson_id', 'lesson_id');
    }

    /**
     * Get the comments for the lesson.
     */
    public function comments()
    {
        return $this->hasMany(Comment::class, 'lesson_id', 'lesson_id');
    }

    /**
     * Get the game for the lesson.
     */
    public function game()
    {
        return $this->hasOne(Game::class, 'lesson_id', 'lesson_id');
    }

    /**
     * Get the class lesson visibilities for the lesson.
     */
    public function classLessonVisibilities()
    {
        return $this->hasMany(ClassLessonVisibility::class, 'lesson_id', 'lesson_id');
    }

    /**
     * Get the previous lesson in the same level.
     */
    public function previousLesson()
    {
        return static::where('level_id', $this->level_id)
            ->where('lesson_order', '<', $this->lesson_order)
            ->orderBy('lesson_order', 'desc')
            ->first();
    }

    /**
     * Check if prerequisites are met for a student.
     * Returns true if there's no previous lesson or if the student scored 60+ in the previous lesson's game.
     * 
     * @param int $studentId
     * @param int $minimumScore Minimum score required (default: 60)
     * @return bool
     */
    public function prerequisitesMet($studentId, $minimumScore = 60)
    {
        $previousLesson = $this->previousLesson();
        
        // If there's no previous lesson, prerequisites are automatically met
        if (!$previousLesson) {
            return true;
        }

        // Collect ALL games for the previous lesson (there may be multiple types)
        $previousGameIds = Game::where('lesson_id', $previousLesson->lesson_id)
            ->pluck('game_id');

        if ($previousGameIds->isEmpty()) {
            // If previous lesson has no games configured, allow access
            return true;
        }

        // Check if the student has COMPLETED any of these games with a passing score
        $passed = StudentGameProgress::where('student_id', $studentId)
            ->whereIn('game_id', $previousGameIds)
            ->where('status', 'completed')
            ->where('score', '>=', $minimumScore)
            ->exists();

        return $passed;
    }

    /**
     * Get the prerequisite status message for a student.
     * 
     * Rules:
     * 1. First lesson in the first level should be opened automatically (no prerequisites)
     * 2. Lessons after the 1st lesson in the same level require 60 score on the game to unlock
     * 3. First lesson in level 2+ requires passing the quiz (60+) from the previous level
     * 
     * @param int $studentId
     * @param int $minimumScore Minimum score required (default: 60)
     * @return array ['met' => bool, 'message' => string, 'previous_lesson' => Lesson|null]
     */
    public function getPrerequisiteStatus($studentId, $minimumScore = 60)
    {
        // Load the level relationship if not already loaded
        if (!$this->relationLoaded('level')) {
            $this->load('level');
        }
        
        // Get student's class_id to check visibility
        $student = \App\Models\Student::find($studentId);
        $studentClassId = $student ? $student->class_id : null;
        
        // Check if this is the first VISIBLE lesson in its level (first one unlocked/shown by teacher)
        // This is more important than lesson_order - we check which visible lesson has the lowest order
        $isFirstVisibleLessonInLevel = false;
        if ($studentClassId) {
            // Get all visible lesson IDs in this level for this class
            $visibleLessonIds = \App\Models\ClassLessonVisibility::where('class_id', $studentClassId)
                ->where('is_visible', true)
                ->whereHas('lesson', function($query) {
                    $query->where('level_id', $this->level_id);
                })
                ->pluck('lesson_id');
            
            if ($visibleLessonIds->isNotEmpty()) {
                // Get the visible lesson with the lowest lesson_order
                $firstVisibleLesson = static::whereIn('lesson_id', $visibleLessonIds)
                    ->where('level_id', $this->level_id)
                    ->orderBy('lesson_order', 'asc')
                    ->first();
                
                if ($firstVisibleLesson) {
                    $isFirstVisibleLessonInLevel = ($firstVisibleLesson->lesson_id == $this->lesson_id);
                    \Log::info("DEBUG: Checking if lesson {$this->lesson_id} is first visible. First visible lesson ID: {$firstVisibleLesson->lesson_id} (Order: {$firstVisibleLesson->lesson_order}), Total visible: {$visibleLessonIds->count()}, Is First: " . ($isFirstVisibleLessonInLevel ? 'YES' : 'NO'));
                }
            } else {
                \Log::info("DEBUG: No visible lessons found in level {$this->level_id} for class {$studentClassId}");
            }
        }
        
        // Fallback: Check if this is the first lesson by lesson_order (if no visibility check possible)
        $isFirstLessonByOrder = $this->lesson_order == 1 || 
            !static::where('level_id', $this->level_id)
                ->where('lesson_order', '<', $this->lesson_order)
                ->exists();
        
        // Use first visible lesson check if available, otherwise use order check
        $isFirstLessonInLevel = $isFirstVisibleLessonInLevel || ($isFirstLessonByOrder && !$studentClassId);
        
        \Log::info("DEBUG: Lesson {$this->lesson_id} ('{$this->title}') - First visible: " . ($isFirstVisibleLessonInLevel ? 'YES' : 'NO') . ", First by order: " . ($isFirstLessonByOrder ? 'YES' : 'NO') . ", Final decision: " . ($isFirstLessonInLevel ? 'FIRST LESSON' : 'NOT FIRST'));
        
        // RULE 1: First lesson in the first level should be opened automatically
        if ($isFirstLessonInLevel && $this->level) {
            // Check if this is level 1 (first level)
            $isLevelOne = $this->level->level_number == 1;
            
            if ($isLevelOne) {
                // First lesson of level 1 - always unlocked, no prerequisites
                return [
                    'met' => true,
                    'message' => null,
                    'previous_lesson' => null
                ];
            }
            
            // RULE 3: First lesson in level 2+ - check if student passed quiz from previous level
            \Log::info("DEBUG: Checking prerequisites for first lesson of level. Lesson ID: {$this->lesson_id}, Lesson Title: '{$this->title}', Level ID: {$this->level_id}, Level Number: {$this->level->level_number}, Student ID: {$studentId}");
            
            // Get the previous level
            $previousLevel = $this->level->prerequisiteLevel;
            \Log::info("DEBUG: Previous level from prerequisiteLevel relationship: " . ($previousLevel ? "Level ID {$previousLevel->level_id}, Level Number {$previousLevel->level_number}, Level Name '{$previousLevel->level_name}'" : "NULL"));
            
            if (!$previousLevel) {
                // Try to find previous level by level_number
                $previousLevel = \App\Models\Level::where('class_id', $this->level->class_id)
                    ->where('level_number', '<', $this->level->level_number)
                    ->orderBy('level_number', 'desc')
                    ->first();
                \Log::info("DEBUG: Previous level from level_number query: " . ($previousLevel ? "Level ID {$previousLevel->level_id}, Level Number {$previousLevel->level_number}, Level Name '{$previousLevel->level_name}'" : "NULL"));
            }
            
            // If there's a previous level, check if student passed its quiz
            if ($previousLevel) {
                \Log::info("DEBUG: Found previous level. Searching for quiz in level ID: {$previousLevel->level_id}, Class ID: {$previousLevel->class_id}");
                
                // Get student's class_id for matching
                $student = \App\Models\Student::find($studentId);
                $studentClassId = $student ? $student->class_id : null;
                \Log::info("DEBUG: Student Class ID: " . ($studentClassId ?? 'NULL'));
                
                // Try to find quiz matching level_id and optionally class_id
                $previousLevelQuiz = \App\Models\Quiz::where('level_id', $previousLevel->level_id)
                    ->where('is_active', true)
                    ->when($studentClassId, function($query) use ($studentClassId) {
                        return $query->where('class_id', $studentClassId);
                    })
                    ->first();
                
                // If not found with class_id, try without class_id filter
                if (!$previousLevelQuiz) {
                    \Log::info("DEBUG: Quiz not found with class_id filter, trying without class_id...");
                    $previousLevelQuiz = \App\Models\Quiz::where('level_id', $previousLevel->level_id)
                        ->where('is_active', true)
                        ->first();
                }
                
                if ($previousLevelQuiz) {
                    \Log::info("DEBUG: Found quiz for previous level. Quiz ID: {$previousLevelQuiz->quiz_id}, Quiz Title: '{$previousLevelQuiz->title}', Level ID: {$previousLevelQuiz->level_id}, Class ID: {$previousLevelQuiz->class_id}, Student Class ID: " . ($studentClassId ?? 'NULL') . ", Is Active: " . ($previousLevelQuiz->is_active ? 'true' : 'false'));
                    
                    // Get all quiz attempts for debugging
                    $allAttempts = \App\Models\QuizAttempt::where('quiz_id', $previousLevelQuiz->quiz_id)
                        ->where('student_id', $studentId)
                        ->get();
                    
                    \Log::info("DEBUG: Found " . $allAttempts->count() . " quiz attempt(s) for student {$studentId} and quiz {$previousLevelQuiz->quiz_id}");
                    foreach ($allAttempts as $attempt) {
                        \Log::info("DEBUG: Attempt ID: {$attempt->attempt_id}, Score: {$attempt->score}, Submitted At: " . ($attempt->submitted_at ? $attempt->submitted_at->toDateTimeString() : 'NULL') . ", Status: {$attempt->status}");
                    }
                    
                    $passedQuiz = \App\Models\QuizAttempt::where('quiz_id', $previousLevelQuiz->quiz_id)
                        ->where('student_id', $studentId)
                        ->whereNotNull('submitted_at')
                        ->where('score', '>=', $minimumScore)
                        ->exists();
                    
                    \Log::info("DEBUG: Quiz passed check result: " . ($passedQuiz ? 'PASSED' : 'FAILED') . " (minimum score: {$minimumScore})");
                    
                    if (!$passedQuiz) {
                        // Get the best attempt for better error message
                        $bestAttempt = \App\Models\QuizAttempt::where('quiz_id', $previousLevelQuiz->quiz_id)
                            ->where('student_id', $studentId)
                            ->whereNotNull('submitted_at')
                            ->orderBy('score', 'desc')
                            ->first();
                        
                        $bestScore = $bestAttempt ? $bestAttempt->score : 'N/A';
                        \Log::warning("DEBUG: Student {$studentId} has NOT passed quiz. Best score: {$bestScore}, Required: {$minimumScore}");
                        
                        return [
                            'met' => false,
                            'message' => "You must pass the quiz for '{$previousLevel->level_name}' (score of {$minimumScore}% or higher) to access this lesson. Note: Your teacher must also unlock this lesson for it to be visible.",
                            'previous_lesson' => null
                        ];
                    }
                    
                    // Student passed the quiz - prerequisites are met
                    \Log::info("DEBUG: ✅ Student {$studentId} has passed quiz for level '{$previousLevel->level_name}' (Quiz ID: {$previousLevelQuiz->quiz_id}). Prerequisites met for first lesson of level '{$this->level->level_name}' (Lesson ID: {$this->lesson_id}).");
                } else {
                    // No quiz for previous level - allow access
                    \Log::warning("DEBUG: No quiz found for previous level '{$previousLevel->level_name}' (Level ID: {$previousLevel->level_id}). Checking all quizzes in this level...");
                    $allQuizzesInLevel = \App\Models\Quiz::where('level_id', $previousLevel->level_id)->get();
                    \Log::info("DEBUG: Found " . $allQuizzesInLevel->count() . " quiz(es) in level {$previousLevel->level_id}:");
                    foreach ($allQuizzesInLevel as $quiz) {
                        \Log::info("DEBUG:   - Quiz ID: {$quiz->quiz_id}, Title: '{$quiz->title}', Class ID: {$quiz->class_id}, Is Active: " . ($quiz->is_active ? 'true' : 'false'));
                    }
                    \Log::info("DEBUG: Allowing access to first lesson of level '{$this->level->level_name}' (Lesson ID: {$this->lesson_id}) because no active quiz found for previous level.");
                }
            } else {
                \Log::warning("DEBUG: No previous level found for level '{$this->level->level_name}' (Level ID: {$this->level_id}, Level Number: {$this->level->level_number}). This might be the first level or levels are not properly linked.");
            }
            
            // Prerequisites are met for first lesson of level (quiz passed or no previous level)
            return [
                'met' => true,
                'message' => null,
                'previous_lesson' => null
            ];
        }
        
        // RULE 2: Not the first lesson - check previous lesson in same level
        // Lessons after the 1st lesson in the same level require 60 score on the game
        $previousLesson = $this->previousLesson();
        if (!$previousLesson) {
            // No previous lesson in same level - should not happen, but allow access
            return [
                'met' => true,
                'message' => null,
                'previous_lesson' => null
            ];
        }

        // Collect ALL games for the previous lesson (there may be multiple types)
        $previousGameIds = Game::where('lesson_id', $previousLesson->lesson_id)
            ->pluck('game_id');

        if ($previousGameIds->isEmpty()) {
            // If previous lesson has no games, allow access
            return [
                'met' => true,
                'message' => null,
                'previous_lesson' => $previousLesson
            ];
        }

        // Fetch all completed progresses for these games
        $progresses = StudentGameProgress::where('student_id', $studentId)
            ->whereIn('game_id', $previousGameIds)
            ->where('status', 'completed')
            ->get();

        if ($progresses->isEmpty()) {
            return [
                'met' => false,
                'message' => "You must complete at least one game in '{$previousLesson->title}' with a score of {$minimumScore} or higher to unlock this lesson.",
                'previous_lesson' => $previousLesson,
                'current_score' => null
            ];
        }

        $bestScore = $progresses->max('score');

        if ($bestScore < $minimumScore) {
            return [
                'met' => false,
                'message' => "Your highest score in '{$previousLesson->title}' is {$bestScore}. You need a score of {$minimumScore} or higher to unlock this lesson.",
                'previous_lesson' => $previousLesson,
                'current_score' => $bestScore
            ];
        }

        // Student has achieved the required score - prerequisites are met
        return [
            'met' => true,
            'message' => null,
            'previous_lesson' => $previousLesson
        ];
    }
}