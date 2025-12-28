<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentLessonProgress extends Model
{
    use HasFactory;

    protected $primaryKey = 'progress_id';

    protected $fillable = [
        'student_id',
        'lesson_id',
        'status',
        'started_at',
        'completed_at',
        'score',
        'time_spent_minutes',
    ];

    protected function casts(): array
    {
        return [
            'started_at' => 'datetime',
            'completed_at' => 'datetime',
            'score' => 'integer',
            'time_spent_minutes' => 'integer',
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
