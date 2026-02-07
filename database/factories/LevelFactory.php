<?php

namespace Database\Factories;

use App\Models\Level;
use Illuminate\Database\Eloquent\Factories\Factory;

class LevelFactory extends Factory
{
    protected $model = Level::class;

    public function definition(): array
    {
        return [
            'class_id' => null,
            'level_name' => fake()->words(2, true),
            'level_number' => fake()->numberBetween(1, 10),
            'description' => fake()->sentence(),
        ];
    }
}
