<?php

namespace App\Observers;

use App\Models\Meeting;
use App\Services\MeetingEnrollmentService;

class MeetingObserver
{
    protected $enrollmentService;
    
    public function __construct(MeetingEnrollmentService $enrollmentService)
    {
        $this->enrollmentService = $enrollmentService;
    }
    
    /**
     * Handle the Meeting "created" event.
     */
    public function created(Meeting $meeting): void
    {
        // Automatically sync enrollments when a meeting is created
        if ($meeting->class_id) {
            $this->enrollmentService->syncEnrollmentsForMeeting($meeting);
        }
    }

    /**
     * Handle the Meeting "updated" event.
     */
    public function updated(Meeting $meeting): void
    {
        // If class_id was changed, sync enrollments for the new class
        if ($meeting->isDirty('class_id') && $meeting->class_id) {
            // Remove old enrollments if class changed
            if ($meeting->getOriginal('class_id')) {
                \App\Models\MeetingEnrollment::where('meeting_id', $meeting->meeting_id)
                    ->whereHas('student', function($query) use ($meeting) {
                        $query->where('class_id', '!=', $meeting->class_id);
                    })
                    ->delete();
            }
            
            // Sync enrollments for new class
            $this->enrollmentService->syncEnrollmentsForMeeting($meeting);
        }
    }

    /**
     * Handle the Meeting "deleted" event.
     */
    public function deleted(Meeting $meeting): void
    {
        // Enrollments will be automatically deleted via foreign key cascade
        // But we can add cleanup logic here if needed
    }
}
