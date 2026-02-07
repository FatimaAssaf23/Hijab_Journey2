<?php

namespace Database\Factories;

use App\Models\Teacher;
use Illuminate\Database\Eloquent\Factories\Factory;

class TeacherFactory extends Factory
{
    protected $model = Teacher::class;

    public function definition(): array
    {
        return [
            'user_id' => null,
            'specialization' => fake()->words(2, true),
            'experience_years' => fake()->numberBetween(1, 30),
        ];
    }
}
