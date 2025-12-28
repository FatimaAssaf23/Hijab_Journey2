<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ClassLessonVisibility extends Model
{
    protected $primaryKey = 'visibility_id';

    protected $fillable = [
        'class_id',
        'lesson_id',
        'teacher_id',
        'is_visible',
        'changed_at',
    ];

    protected $casts = [
        'is_visible' => 'boolean',
        'changed_at' => 'datetime',
    ];

    public function studentClass(): BelongsTo
    {
        return $this->belongsTo(StudentClass::class, 'class_id', 'class_id');
    }

    public function lesson(): BelongsTo
    {
        return $this->belongsTo(Lesson::class, 'lesson_id', 'lesson_id');
    }

    public function teacher(): BelongsTo
    {
        return $this->belongsTo(User::class, 'teacher_id', 'user_id');
    }
}
