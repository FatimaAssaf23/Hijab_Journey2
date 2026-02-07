<?php

namespace Database\Factories;

use App\Models\AttendanceConfirmation;
use Illuminate\Database\Eloquent\Factories\Factory;
use Carbon\Carbon;

class AttendanceConfirmationFactory extends Factory
{
    protected $model = AttendanceConfirmation::class;

    public function definition(): array
    {
        return [
            'meeting_enrollment_id' => null,
            'confirmation_number' => fake()->numberBetween(1, 10),
            'prompted_at' => Carbon::now(),
            'responded_at' => null,
            'is_confirmed' => null,
        ];
    }
}
