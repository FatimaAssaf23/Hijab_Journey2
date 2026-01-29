<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GroupWordPair extends Model
{
    use HasFactory;
    protected $fillable = ['lesson_group_id', 'lesson_id', 'class_id', 'game_type', 'word', 'definition'];

    public function group()
    {
        return $this->belongsTo(LessonGroup::class, 'lesson_group_id');
    }

    public function lesson()
    {
        return $this->belongsTo(Lesson::class, 'lesson_id', 'lesson_id');
    }
}
