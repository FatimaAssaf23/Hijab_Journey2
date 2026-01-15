<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Quiz extends Model
{
    use HasFactory;

    protected $primaryKey = 'quiz_id';

    protected $fillable = [
        'level_id',
        'class_id',
        'teacher_id',
        'checked_by_admin_id',
        'title',
        'description',
        'background_color',
        'timer_minutes',
        'due_date',
        'max_score',
        'passing_score',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'due_date' => 'datetime',
            'timer_minutes' => 'integer',
            'max_score' => 'integer',
            'passing_score' => 'integer',
            'is_active' => 'boolean',
        ];
    }

    /**
     * Get the level that owns the quiz.
     */
    public function level()
    {
        return $this->belongsTo(Level::class, 'level_id', 'level_id');
    }

    /**
     * Get the student class that owns the quiz.
     */
    public function studentClass()
    {
        return $this->belongsTo(StudentClass::class, 'class_id', 'class_id');
    }

    /**
     * Get the teacher that created the quiz.
     */
    public function teacher()
    {
        return $this->belongsTo(User::class, 'teacher_id', 'user_id');
    }

    /**
     * Get the admin who checked the quiz.
     */
    public function checkedByAdmin()
    {
        return $this->belongsTo(User::class, 'checked_by_admin_id', 'user_id');
    }

    /**
     * Get the questions for the quiz.
     */
    public function questions()
    {
        return $this->hasMany(QuizQuestion::class, 'quiz_id', 'quiz_id');
    }

    /**
     * Get the attempts for the quiz.
     */
    public function attempts()
    {
        return $this->hasMany(QuizAttempt::class, 'quiz_id', 'quiz_id');
    }



}