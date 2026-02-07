<?php

namespace Database\Factories;

use App\Models\MeetingEnrollment;
use Illuminate\Database\Eloquent\Factories\Factory;
use Carbon\Carbon;

class MeetingEnrollmentFactory extends Factory
{
    protected $model = MeetingEnrollment::class;

    public function definition(): array
    {
        return [
            'meeting_id' => null,
            'student_id' => null,
            'attendance_status' => 'pending',
            'joined_at' => Carbon::now(),
        ];
    }
}
