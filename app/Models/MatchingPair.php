<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MatchingPair extends Model
{
    protected $primaryKey = 'matching_pair_id';

    protected $fillable = [
        'matching_pairs_game_id',
        'left_item_text',
        'left_item_image',
        'right_item_text',
        'right_item_image',
        'order',
    ];

    /**
     * Get the matching pairs game that owns this pair.
     */
    public function matchingPairsGame(): BelongsTo
    {
        return $this->belongsTo(MatchingPairsGame::class, 'matching_pairs_game_id', 'matching_pairs_game_id');
    }
}
