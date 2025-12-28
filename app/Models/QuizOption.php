<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuizOption extends Model
{
    use HasFactory;

    protected $primaryKey = 'option_id';

    protected $fillable = [
        'question_id',
        'option_text',
        'option_order',
        'is_correct',
    ];

    protected function casts(): array
    {
        return [
            'option_order' => 'integer',
            'is_correct' => 'boolean',
        ];
    }

    /**
     * Get the question that owns the option.
     */
    public function question()
    {
        return $this->belongsTo(QuizQuestion::class, 'question_id', 'question_id');
    }

    /**
     * Get the answers that selected this option.
     */
    public function selectedAnswers()
    {
        return $this->hasMany(StudentAnswer::class, 'selected_option_id', 'option_id');
    }


}