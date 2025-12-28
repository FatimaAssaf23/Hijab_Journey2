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
}