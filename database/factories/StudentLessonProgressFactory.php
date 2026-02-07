<?php

namespace Database\Factories;

use App\Models\StudentLessonProgress;
use Illuminate\Database\Eloquent\Factories\Factory;
use Carbon\Carbon;

class StudentLessonProgressFactory extends Factory
{
    protected $model = StudentLessonProgress::class;

    public function definition(): array
    {
        return [
            'student_id' => null,
            'lesson_id' => null,
            'watched_percentage' => fake()->numberBetween(0, 100),
            'video_completed' => fake()->boolean(),
            'score' => fake()->numberBetween(0, 100),
            'last_activity_at' => Carbon::now()->subDays(fake()->numberBetween(0, 7)),
            'last_watched_at' => Carbon::now()->subDays(fake()->numberBetween(0, 7)),
        ];
    }
}
