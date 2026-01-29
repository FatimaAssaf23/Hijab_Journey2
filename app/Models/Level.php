<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Level extends Model
{
    use HasFactory;

    protected $primaryKey = 'level_id';

    protected $fillable = [
        'class_id',
        'level_name',
        'level_number',
        'description',
        'prerequisite_level_id',
        'is_locked_by_default',
    ];

    protected function casts(): array
    {
        return [
            'level_number' => 'integer',
            'is_locked_by_default' => 'boolean',
        ];
    }

    /**
     * The classes that belong to the level.
     */
    public function classes()
    {
        return $this->belongsToMany(StudentClass::class, 'class_level', 'level_id', 'class_id');
    }

    /**
     * Get the student class that owns the level.
     */
    public function studentClass()
    {
        return $this->belongsTo(StudentClass::class, 'class_id', 'class_id');
    }

    /**
     * Get the prerequisite level.
     */
    public function prerequisiteLevel()
    {
        return $this->belongsTo(Level::class, 'prerequisite_level_id', 'level_id');
    }

    /**
     * Get the levels that require this level as prerequisite.
     */
    public function dependentLevels()
    {
        return $this->hasMany(Level::class, 'prerequisite_level_id', 'level_id');
    }

    /**
     * Get the lessons for the level.
     */
    public function lessons()
    {
        return $this->hasMany(Lesson::class, 'level_id', 'level_id');
    }

    /**
     * Get the assignments for the level.
     */
    public function assignments()
    {
        return $this->hasMany(Assignment::class, 'level_id', 'level_id');
    }

    /**
     * Get the quizzes for the level.
     */
    public function quizzes()
    {
        return $this->hasMany(Quiz::class, 'level_id', 'level_id');
    }

    /**
     * Check if all lessons in this level are completed by a student.
     * 
     * @param int $studentId
     * @return bool
     */
    public function allLessonsCompleted($studentId)
    {
        $lessons = $this->lessons;
        
        if ($lessons->isEmpty()) {
            // If there are no lessons, consider it "completed" (edge case)
            return true;
        }
        
        // Get all lesson IDs for this level
        $lessonIds = $lessons->pluck('lesson_id');
        
        // Count completed lessons for this student
        $completedCount = \App\Models\StudentLessonProgress::where('student_id', $studentId)
            ->whereIn('lesson_id', $lessonIds)
            ->where('status', 'completed')
            ->count();
        
        // All lessons must be completed
        return $completedCount === $lessons->count();
    }

    /**
     * Get the next level (by level_number).
     * 
     * @return Level|null
     */
    public function nextLevel()
    {
        return static::where('class_id', $this->class_id)
            ->where('level_number', '>', $this->level_number)
            ->orderBy('level_number', 'asc')
            ->first();
    }

    /**
     * Get the first lesson in this level (by lesson_order).
     * 
     * @return Lesson|null
     */
    public function firstLesson()
    {
        return $this->lessons()
            ->orderBy('lesson_order', 'asc')
            ->first();
    }

}