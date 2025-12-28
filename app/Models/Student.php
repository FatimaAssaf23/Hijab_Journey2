<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;

    protected $primaryKey = 'student_id';

    protected $fillable = [
        'user_id',
        'class_id',
        'gender',
        'date_of_birth',
        'city',
        'street',
        'language',
        'total_score',
        'plan_type',
        'subscription_status',
        'subscription_expires_at',
    ];

    protected function casts(): array
    {
        return [
            'date_of_birth' => 'date',
            'subscription_expires_at' => 'datetime',
            'total_score' => 'integer',
        ];
    }

    /**
     * Get the user that owns the student.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    /**
     * Get the class that the student belongs to.
     */
    public function studentClass()
    {
        return $this->belongsTo(StudentClass::class, 'class_id', 'class_id');
    }

    /**
     * Get the lesson progresses for the student.
     */
    public function lessonProgresses()
    {
        return $this->hasMany(StudentLessonProgress::class, 'student_id', 'student_id');
    }

    /**
     * Get the assignment submissions for the student.
     */
    public function assignmentSubmissions()
    {
        return $this->hasMany(AssignmentSubmission::class, 'student_id', 'student_id');
    }

    /**
     * Get the quiz attempts for the student.
     */
    public function quizAttempts()
    {
        return $this->hasMany(QuizAttempt::class, 'student_id', 'student_id');
    }

    /**
     * Get the grades for the student.
     */
    public function grades()
    {
        return $this->hasMany(Grade::class, 'student_id', 'student_id');
    }

    /**
     * Get the game progresses for the student.
     */
    public function gameProgresses()
    {
        return $this->hasMany(StudentGameProgress::class, 'student_id', 'student_id');
    }

    /**
     * Get the payments for the student.
     */
    public function payments()
    {
        return $this->hasMany(Payment::class, 'student_id', 'student_id');
    }

}