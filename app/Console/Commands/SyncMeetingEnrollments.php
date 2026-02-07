<?php

namespace App\Console\Commands;

use App\Services\MeetingEnrollmentService;
use Illuminate\Console\Command;

class SyncMeetingEnrollments extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'meetings:sync-enrollments 
                            {--meeting-id= : Sync enrollments for a specific meeting ID}
                            {--all : Sync enrollments for all meetings}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync meeting enrollments - ensures all students in a class are enrolled in their class meetings';

    /**
     * Execute the console command.
     */
    public function handle(MeetingEnrollmentService $enrollmentService): int
    {
        $meetingId = $this->option('meeting-id');
        $syncAll = $this->option('all');
        
        if ($meetingId) {
            // Sync specific meeting
            $meeting = \App\Models\Meeting::find($meetingId);
            
            if (!$meeting) {
                $this->error("Meeting with ID {$meetingId} not found.");
                return 1;
            }
            
            $this->info("Syncing enrollments for meeting: {$meeting->title} (ID: {$meeting->meeting_id})");
            $result = $enrollmentService->syncEnrollmentsForMeeting($meeting);
            
            $this->info("✓ Created: {$result['created']} enrollments");
            $this->info("✓ Existing: {$result['existing']} enrollments");
            $this->info("✓ Total: {$result['total']} enrollments");
            
            return 0;
        } elseif ($syncAll) {
            // Sync all meetings
            $this->info("Syncing enrollments for all meetings...");
            $result = $enrollmentService->syncAllMeetings();
            
            $this->info("✓ Processed: {$result['meetings_processed']} meetings");
            $this->info("✓ Created: {$result['enrollments_created']} enrollments");
            
            if (!empty($result['errors'])) {
                $this->warn("⚠ Errors encountered:");
                foreach ($result['errors'] as $error) {
                    $this->warn("  - Meeting ID {$error['meeting_id']}: {$error['error']}");
                }
            }
            
            return 0;
        } else {
            $this->error("Please specify either --meeting-id=X or --all");
            $this->info("Usage examples:");
            $this->info("  php artisan meetings:sync-enrollments --meeting-id=13");
            $this->info("  php artisan meetings:sync-enrollments --all");
            return 1;
        }
    }
}
