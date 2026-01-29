<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Game extends Model
{
    protected $primaryKey = 'game_id';

    protected $fillable = [
        'lesson_id',
        'class_id',
        'game_type',
        'game_data',
        'created_at',
        'updated_at',
    ];

    protected $casts = [
        'game_data' => 'json',
    ];

    public function lesson(): BelongsTo
    {
        return $this->belongsTo(Lesson::class, 'lesson_id', 'lesson_id');
    }

    public function studentProgresses(): HasMany
    {
        return $this->hasMany(StudentGameProgress::class, 'game_id', 'game_id');
    }

    public function clockGame(): HasMany
    {
        return $this->hasMany(ClockGame::class, 'game_id', 'game_id');
    }

    public function wordSearchGame(): HasMany
    {
        return $this->hasMany(WordSearchGame::class, 'game_id', 'game_id');
    }

    public function matchingPairsGame(): HasMany
    {
        return $this->hasMany(MatchingPairsGame::class, 'game_id', 'game_id');
    }
}
