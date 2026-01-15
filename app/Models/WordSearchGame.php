<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WordSearchGame extends Model
{
    protected $primaryKey = 'word_search_game_id';

    protected $fillable = [
        'game_id',
        'lesson_id',
        'title',
        'words',
        'grid_size',
        'grid_data',
    ];

    protected $casts = [
        'words' => 'array',
        'grid_data' => 'array',
    ];


    /**
     * Get the game that owns this word search game.
     */
    public function game(): BelongsTo
    {
        return $this->belongsTo(Game::class, 'game_id', 'game_id');
    }

    /**
     * Get the lesson that owns this word search game.
     */
    public function lesson(): BelongsTo
    {
        return $this->belongsTo(Lesson::class, 'lesson_id', 'lesson_id');
    }
}
