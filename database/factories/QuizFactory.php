<?php

namespace Database\Factories;

use App\Models\Quiz;
use Illuminate\Database\Eloquent\Factories\Factory;

class QuizFactory extends Factory
{
    protected $model = Quiz::class;

    public function definition(): array
    {
        return [
            'level_id' => null,
            'title' => fake()->sentence(3),
            'description' => fake()->paragraph(),
            'is_active' => true,
            'passing_score' => 60,
        ];
    }
}
