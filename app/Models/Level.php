<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Level extends Model
{
    use HasFactory;

    protected $primaryKey = 'level_id';

    protected $fillable = [
        'class_id',
        'level_name',
        'level_number',
        'description',
        'prerequisite_level_id',
        'is_locked_by_default',
    ];

    protected function casts(): array
    {
        return [
            'level_number' => 'integer',
            'is_locked_by_default' => 'boolean',
        ];
    }

    /**
     * Get the student class that owns the level.
     */
    public function studentClass()
    {
        return $this->belongsTo(StudentClass::class, 'class_id', 'class_id');
    }

    /**
     * Get the prerequisite level.
     */
    public function prerequisiteLevel()
    {
        return $this->belongsTo(Level::class, 'prerequisite_level_id', 'level_id');
    }

    /**
     * Get the levels that require this level as prerequisite.
     */
    public function dependentLevels()
    {
        return $this->hasMany(Level::class, 'prerequisite_level_id', 'level_id');
    }

    /**
     * Get the lessons for the level.
     */
    public function lessons()
    {
        return $this->hasMany(Lesson::class, 'level_id', 'level_id');
    }

    /**
     * Get the assignments for the level.
     */
    public function assignments()
    {
        return $this->hasMany(Assignment::class, 'level_id', 'level_id');
    }

    /**
     * Get the quizzes for the level.
     */
    public function quizzes()
    {
        return $this->hasMany(Quiz::class, 'level_id', 'level_id');
    }



}