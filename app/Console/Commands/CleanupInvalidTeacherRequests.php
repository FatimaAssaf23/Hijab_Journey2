<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\TeacherRequest;
use Illuminate\Support\Facades\DB;

class CleanupInvalidTeacherRequests extends Command
{
    protected $signature = 'fix:cleanup-invalid-teacher-requests {email}';
    protected $description = 'Remove or fix teacher requests that were incorrectly created with admin email';

    public function handle()
    {
        $email = $this->argument('email');
        
        $this->info("Cleaning up invalid teacher requests for: {$email}");
        $this->newLine();

        // Find all teacher requests with this email
        $requests = TeacherRequest::where('email', $email)->get();
        
        if ($requests->count() === 0) {
            $this->info("No teacher requests found for this email.");
            return 0;
        }

        $this->info("Found {$requests->count()} teacher request(s):");
        $this->newLine();

        DB::beginTransaction();
        try {
            foreach ($requests as $request) {
                $this->line("  Request ID: {$request->request_id}");
                $this->line("    Status: {$request->status}");
                $this->line("    Name: {$request->full_name}");
                $this->line("    Email: {$request->email}");
                if ($request->rejection_reason) {
                    $this->line("    Rejection Reason: {$request->rejection_reason}");
                }
                
                // Delete the request since it was a mistake
                $request->delete();
                $this->info("    ✓ Deleted");
                $this->newLine();
            }

            DB::commit();
            $this->info("✓ Successfully cleaned up all invalid teacher requests!");
            $this->info("These requests have been completely removed from the system.");
            
            return 0;
        } catch (\Exception $e) {
            DB::rollBack();
            $this->error("Error: " . $e->getMessage());
            return 1;
        }
    }
}
