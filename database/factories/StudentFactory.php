<?php

namespace Database\Factories;

use App\Models\Student;
use Illuminate\Database\Eloquent\Factories\Factory;

class StudentFactory extends Factory
{
    protected $model = Student::class;

    public function definition(): array
    {
        return [
            'user_id' => null,
            'class_id' => null,
            'gender' => fake()->randomElement(['male', 'female']),
            'date_of_birth' => fake()->date(),
            'city' => fake()->city(),
            'street' => fake()->streetAddress(),
            'language' => fake()->randomElement(['ar', 'en']),
            'total_score' => fake()->numberBetween(0, 100),
            'plan_type' => fake()->randomElement(['basic', 'premium']),
            'subscription_status' => fake()->randomElement(['active', 'inactive']),
            'subscription_expires_at' => fake()->dateTimeBetween('now', '+1 year'),
            'is_read' => fake()->boolean(),
        ];
    }
}
