<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentClass extends Model
{
    use HasFactory;

    protected $primaryKey = 'class_id';

    protected $fillable = [
        'class_name',
        'teacher_id',
        'capacity',
        'current_enrollment',
        'status',
        'description',
    ];

    protected function casts(): array
    {
        return [
            'capacity' => 'integer',
            'current_enrollment' => 'integer',
        ];
    }

    /**
     * Get the teacher that owns the class.
     */
    public function teacher()
    {
        return $this->belongsTo(User::class, 'teacher_id', 'user_id');
    }

    /**
     * Get the students in this class.
     */
    public function students()
    {
        return $this->hasMany(Student::class, 'class_id', 'class_id');
    }

    /**
     * Get the levels for the class.
     */
    public function levels()
    {
        return $this->hasMany(Level::class, 'class_id', 'class_id');
    }

    /**
     * Get the assignments for the class.
     */
    public function assignments()
    {
        return $this->hasMany(Assignment::class, 'class_id', 'class_id');
    }

    /**
     * Get the quizzes for the class.
     */
    public function quizzes()
    {
        return $this->hasMany(Quiz::class, 'class_id', 'class_id');
    }

    /**
     * Get the group chat messages for the class.
     */
    public function groupChatMessages()
    {
        return $this->hasMany(GroupChatMessage::class, 'class_id', 'class_id');
    }

    /**
     * Get the meetings for the class.
     */
    public function meetings()
    {
        return $this->hasMany(Meeting::class, 'class_id', 'class_id');
    }

    /**
     * Get the teacher substitutions for the class.
     */
    public function teacherSubstitutions()
    {
        return $this->hasMany(TeacherSubstitution::class, 'class_id', 'class_id');
    }

    /**
     * Get the class lesson visibilities for the class.
     */
    public function classLessonVisibilities()
    {
        return $this->hasMany(ClassLessonVisibility::class, 'class_id', 'class_id');
    }

}