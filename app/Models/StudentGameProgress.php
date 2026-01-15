<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StudentGameProgress extends Model
{
    protected $table = 'student_game_progresses';
    
    protected $primaryKey = 'progress_id';

    protected $fillable = [
        'game_id',
        'student_id',
        'status',
        'score',
        'started_at',
        'completed_at',
        'attempts',
    ];

    protected $casts = [
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    public function game(): BelongsTo
    {
        return $this->belongsTo(Game::class, 'game_id', 'game_id');
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class, 'student_id', 'student_id');
    }
}
