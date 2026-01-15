<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MatchingPairsGame extends Model
{
    protected $primaryKey = 'matching_pairs_game_id';

    protected $fillable = [
        'game_id',
        'lesson_id',
        'title',
    ];

    /**
     * Get the game that owns this matching pairs game.
     */
    public function game(): BelongsTo
    {
        return $this->belongsTo(Game::class, 'game_id', 'game_id');
    }

    /**
     * Get the lesson that owns this matching pairs game.
     */
    public function lesson(): BelongsTo
    {
        return $this->belongsTo(Lesson::class, 'lesson_id', 'lesson_id');
    }

    /**
     * Get the matching pairs for this game.
     */
    public function pairs(): HasMany
    {
        return $this->hasMany(MatchingPair::class, 'matching_pairs_game_id', 'matching_pairs_game_id')->orderBy('order');
    }
}
