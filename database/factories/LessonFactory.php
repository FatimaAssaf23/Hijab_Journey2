<?php

namespace Database\Factories;

use App\Models\Lesson;
use Illuminate\Database\Eloquent\Factories\Factory;

class LessonFactory extends Factory
{
    protected $model = Lesson::class;

    public function definition(): array
    {
        return [
            'teacher_id' => null,
            'level_id' => null,
            'title' => fake()->sentence(3),
            'description' => fake()->paragraph(),
            'video_url' => fake()->url(),
            'lesson_order' => fake()->numberBetween(1, 20),
            'is_active' => true,
        ];
    }
}
