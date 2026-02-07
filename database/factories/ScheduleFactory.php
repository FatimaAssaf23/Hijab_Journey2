<?php

namespace Database\Factories;

use App\Models\Schedule;
use Illuminate\Database\Eloquent\Factories\Factory;
use Carbon\Carbon;

class ScheduleFactory extends Factory
{
    protected $model = Schedule::class;

    public function definition(): array
    {
        return [
            'teacher_id' => null,
            'class_id' => null,
            'status' => 'active',
            'started_at' => Carbon::now(),
            'paused_at' => null,
            'completed_at' => null,
        ];
    }
}
