<?php

namespace Database\Factories;

use App\Models\Attendance;
use Illuminate\Database\Eloquent\Factories\Factory;
use Carbon\Carbon;

class AttendanceFactory extends Factory
{
    protected $model = Attendance::class;

    public function definition(): array
    {
        return [
            'meeting_id' => null,
            'user_id' => null,
            'joined_at' => Carbon::now(),
            'left_at' => Carbon::now()->addMinutes(60),
        ];
    }
}
