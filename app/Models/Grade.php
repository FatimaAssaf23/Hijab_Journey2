<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Grade extends Model
{
    use HasFactory;

    protected $primaryKey = 'grade_id';

    protected $fillable = [
        'student_id',
        'teacher_id',
        'assignment_submission_id',
        'quiz_attempt_id',
        'grade_value',
        'max_grade',
        'percentage',
        'feedback',
        'graded_at',
    ];

    protected function casts(): array
    {
        return [
            'grade_value' => 'decimal:2',
            'max_grade' => 'decimal:2',
            'percentage' => 'decimal:2',
            'graded_at' => 'datetime',
        ];
    }

    /**
     * Get the student that owns the grade.
     */
    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id', 'student_id');
    }

    /**
     * Get the teacher that owns the grade.
     */
    public function teacher()
    {
        return $this->belongsTo(User::class, 'teacher_id', 'user_id');
    }

    /**
     * Get the assignment submission that owns the grade.
     */
    public function assignmentSubmission()
    {
        return $this->belongsTo(AssignmentSubmission::class, 'assignment_submission_id', 'submission_id');
    }

    /**
     * Get the quiz attempt that owns the grade.
     */
    public function quizAttempt()
    {
        return $this->belongsTo(QuizAttempt::class, 'quiz_attempt_id', 'attempt_id');
    }
}
