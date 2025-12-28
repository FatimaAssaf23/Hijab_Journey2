<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuizQuestion extends Model
{
    use HasFactory;

    protected $primaryKey = 'question_id';

    protected $fillable = [
        'quiz_id',
        'question_text',
        'question_order',
        'points',
    ];

    protected function casts(): array
    {
        return [
            'question_order' => 'integer',
            'points' => 'integer',
        ];
    }

    /**
     * Get the quiz that owns the question.
     */
    public function quiz()
    {
        return $this->belongsTo(Quiz::class, 'quiz_id', 'quiz_id');
    }

    /**
     * Get the options for the question.
     */
    public function options()
    {
        return $this->hasMany(QuizOption::class, 'question_id', 'question_id');
    }

    /**
     * Get the answers for the question.
     */
    public function answers()
    {
        return $this->hasMany(StudentAnswer::class, 'question_id', 'question_id');
    }


}