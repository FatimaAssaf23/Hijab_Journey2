<?php

namespace Database\Factories;

use App\Models\StudentRiskPrediction;
use Illuminate\Database\Eloquent\Factories\Factory;
use Carbon\Carbon;

class StudentRiskPredictionFactory extends Factory
{
    protected $model = StudentRiskPrediction::class;

    public function definition(): array
    {
        return [
            'student_id' => null,
            'current_level_id' => null,
            'risk_level' => fake()->numberBetween(0, 3),
            'risk_label' => fake()->randomElement(['Low', 'Medium', 'High']),
            'confidence' => fake()->numberBetween(0, 100),
            'avg_watch_pct' => fake()->numberBetween(0, 100),
            'avg_quiz_score' => fake()->numberBetween(0, 100),
            'days_inactive' => fake()->numberBetween(0, 30),
            'lessons_completed' => fake()->numberBetween(0, 50),
            'predicted_at' => Carbon::now(),
        ];
    }
}
