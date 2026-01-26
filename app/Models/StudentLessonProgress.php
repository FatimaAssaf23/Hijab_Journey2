<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentLessonProgress extends Model
{
    use HasFactory;

    protected $table = 'student_lesson_progresses';
    protected $primaryKey = 'progress_id';

    protected $fillable = [
        'student_id',
        'lesson_id',
        'status',
        'started_at',
        'completed_at',
        'score',
        'time_spent_minutes',
        'watched_seconds',
        'watched_percentage',
        'last_position',
        'max_watched_time',
        'last_watched_at',
        'video_completed',
        'last_activity_at',
    ];

    protected function casts(): array
    {
        return [
            'started_at' => 'datetime',
            'completed_at' => 'datetime',
            'last_watched_at' => 'datetime',
            'last_activity_at' => 'datetime',
            'score' => 'integer',
            'time_spent_minutes' => 'integer',
            'watched_seconds' => 'integer',
            'watched_percentage' => 'decimal:2',
            'last_position' => 'decimal:2',
            'max_watched_time' => 'decimal:2',
            'video_completed' => 'boolean',
        ];
    }

    /**
     * Get the student that owns the progress.
     */
    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id', 'student_id');
    }

    /**
     * Get the lesson that owns the progress.
     */
    public function lesson()
    {
        return $this->belongsTo(Lesson::class, 'lesson_id', 'lesson_id');
    }
}
