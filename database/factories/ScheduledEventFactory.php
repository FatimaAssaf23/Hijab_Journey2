<?php

namespace Database\Factories;

use App\Models\ScheduledEvent;
use Illuminate\Database\Eloquent\Factories\Factory;
use Carbon\Carbon;

class ScheduledEventFactory extends Factory
{
    protected $model = ScheduledEvent::class;

    public function definition(): array
    {
        return [
            'schedule_id' => null,
            'event_type' => fake()->randomElement(['lesson', 'assignment', 'quiz']),
            'release_date' => Carbon::now()->addDays(fake()->numberBetween(1, 30))->toDateString(),
            'status' => 'pending',
            'lesson_id' => null,
            'level_id' => null,
            'assignment_id' => null,
            'quiz_id' => null,
            'edited_by_admin' => false,
            'admin_id' => null,
            'admin_notes' => null,
        ];
    }
}
