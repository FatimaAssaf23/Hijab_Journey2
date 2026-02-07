<?php

namespace Database\Factories;

use App\Models\Meeting;
use Illuminate\Database\Eloquent\Factories\Factory;
use Carbon\Carbon;

class MeetingFactory extends Factory
{
    protected $model = Meeting::class;

    public function definition(): array
    {
        return [
            'teacher_id' => null,
            'class_id' => null,
            'title' => fake()->sentence(3),
            'description' => fake()->paragraph(),
            'scheduled_at' => Carbon::now()->addDays(fake()->numberBetween(1, 30)),
            'duration_minutes' => fake()->numberBetween(30, 120),
            'status' => 'scheduled',
            'verification_code' => strtoupper(fake()->bothify('######')),
        ];
    }
}
