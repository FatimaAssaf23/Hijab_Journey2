<?php

namespace Database\Factories;

use App\Models\QuizAttempt;
use Illuminate\Database\Eloquent\Factories\Factory;
use Carbon\Carbon;

class QuizAttemptFactory extends Factory
{
    protected $model = QuizAttempt::class;

    public function definition(): array
    {
        return [
            'student_id' => null,
            'quiz_id' => null,
            'score' => fake()->numberBetween(0, 100),
            'submitted_at' => Carbon::now(),
            'started_at' => Carbon::now()->subMinutes(30),
        ];
    }
}
