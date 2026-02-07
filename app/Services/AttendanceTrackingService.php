<?php

namespace App\Services;

use App\Models\Meeting;
use App\Models\MeetingEnrollment;
use App\Models\AttendanceConfirmation;
use Carbon\Carbon;

class AttendanceTrackingService
{
    public function createConfirmationSchedule(MeetingEnrollment $enrollment)
    {
        $meeting = $enrollment->meeting;
        $startTime = $enrollment->joined_at ?? $meeting->scheduled_at;
        $intervalMinutes = 10;
        $totalIntervals = ceil($meeting->duration_minutes / $intervalMinutes);
        
        for ($i = 1; $i <= $totalIntervals; $i++) {
            AttendanceConfirmation::create([
                'meeting_enrollment_id' => $enrollment->id,
                'confirmation_number' => $i,
                'prompted_at' => $startTime->copy()->addMinutes($i * $intervalMinutes),
            ]);
        }
    }
    
    public function shouldShowPrompt(MeetingEnrollment $enrollment)
    {
        return $enrollment->confirmations()
            ->where('prompted_at', '<=', now())
            ->whereNull('responded_at')
            ->first();
    }
    
    public function recordConfirmation(AttendanceConfirmation $confirmation, bool $isConfirmed)
    {
        $confirmation->update([
            'responded_at' => now(),
            'is_confirmed' => $isConfirmed,
        ]);
    }
    
    public function finalizeMeetingAttendance(Meeting $meeting)
    {
        foreach ($meeting->enrollments as $enrollment) {
            $enrollment->update([
                'attendance_status' => $enrollment->calculateFinalStatus(),
            ]);
        }
        
        $meeting->update(['status' => 'completed']);
    }
}
