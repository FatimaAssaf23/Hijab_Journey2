<?php

namespace App\Jobs;

use App\Models\Meeting;
use App\Services\AttendanceTrackingService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class FinalizeMeetingAttendance implements ShouldQueue
{
    use Queueable;

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $completedMeetings = Meeting::where('status', 'ongoing')
            ->whereRaw('scheduled_at + INTERVAL duration_minutes MINUTE <= NOW()')
            ->get();
        
        $service = new AttendanceTrackingService();
        
        foreach ($completedMeetings as $meeting) {
            $service->finalizeMeetingAttendance($meeting);
        }
    }
}
