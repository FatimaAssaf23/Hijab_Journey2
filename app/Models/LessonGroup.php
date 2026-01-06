<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LessonGroup extends Model
{
    use HasFactory;
    protected $fillable = ['lesson_id', 'name'];

    public function lesson()
    {
        return $this->belongsTo(Lesson::class, 'lesson_id', 'lesson_id');
    }

    public function wordPairs()
    {
        return $this->hasMany(GroupWordPair::class, 'lesson_group_id');
    }
}
