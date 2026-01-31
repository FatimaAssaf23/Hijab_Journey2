<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentRiskPrediction extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'current_level_id',
        'risk_level',
        'risk_label',
        'confidence',
        'avg_watch_pct',
        'avg_quiz_score',
        'days_inactive',
        'lessons_completed',
        'predicted_at',
    ];

    protected function casts(): array
    {
        return [
            'risk_level' => 'integer',
            'confidence' => 'decimal:2',
            'avg_watch_pct' => 'decimal:2',
            'avg_quiz_score' => 'decimal:2',
            'days_inactive' => 'integer',
            'lessons_completed' => 'integer',
            'predicted_at' => 'datetime',
        ];
    }

    /**
     * Get the student that owns the prediction.
     */
    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id', 'student_id');
    }

    /**
     * Get the level for this prediction
     */
    public function level()
    {
        return $this->belongsTo(Level::class, 'current_level_id', 'level_id');
    }

    /**
     * Get risk level badge color
     */
    public function getRiskBadgeColorAttribute()
    {
        return match($this->risk_level) {
            0 => 'success', // Will Pass - green
            1 => 'warning', // May Struggle - yellow
            2 => 'danger',  // Needs Help - red
            default => 'secondary'
        };
    }
}
