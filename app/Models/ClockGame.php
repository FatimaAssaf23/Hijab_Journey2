<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ClockGame extends Model
{
    protected $primaryKey = 'clock_game_id';

    protected $fillable = [
        'game_id',
        'lesson_id',
        'words',
    ];

    protected $casts = [
        'words' => 'array', // Automatically convert JSON to array
    ];

    /**
     * Get the game that owns this clock game.
     */
    public function game(): BelongsTo
    {
        return $this->belongsTo(Game::class, 'game_id', 'game_id');
    }

    /**
     * Get the lesson that owns this clock game.
     */
    public function lesson(): BelongsTo
    {
        return $this->belongsTo(Lesson::class, 'lesson_id', 'lesson_id');
    }
}
