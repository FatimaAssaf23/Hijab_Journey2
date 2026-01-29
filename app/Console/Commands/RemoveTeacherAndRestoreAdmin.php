<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Teacher;
use App\Models\TeacherRequest;
use Illuminate\Support\Facades\DB;

class RemoveTeacherAndRestoreAdmin extends Command
{
    protected $signature = 'fix:remove-teacher-restore-admin {email}';
    protected $description = 'Remove teacher record and restore admin role for the specified email';

    public function handle()
    {
        $email = $this->argument('email');
        
        $this->info("Processing email: {$email}");
        $this->newLine();

        DB::beginTransaction();
        try {
            // Find the user by email
            $user = User::where('email', $email)->first();
            
            if (!$user) {
                $this->error("User with email '{$email}' not found!");
                return 1;
            }

            $this->info("Found user: ID {$user->user_id}");
            $this->info("Current role: {$user->role}");
            $this->newLine();

            // Step 1: Remove all teacher records for this user
            $teacherRecords = Teacher::where('user_id', $user->user_id)->get();
            if ($teacherRecords->count() > 0) {
                $this->info("Found {$teacherRecords->count()} teacher record(s) to remove:");
                foreach ($teacherRecords as $teacher) {
                    $this->line("  - Teacher ID: {$teacher->teacher_id}");
                    $teacher->delete();
                }
                $this->info("✓ Removed all teacher records");
            } else {
                $this->info("○ No teacher records found");
            }

            // Step 2: Restore admin role
            if ($user->role !== 'admin') {
                $oldRole = $user->role;
                $user->role = 'admin';
                $user->save();
                $this->info("✓ Changed role from '{$oldRole}' to 'admin'");
            } else {
                $this->info("○ User is already an admin");
            }

            // Step 3: Reject any pending teacher requests for this email
            $pendingRequests = TeacherRequest::where('email', $email)
                ->where('status', 'pending')
                ->get();
            
            if ($pendingRequests->count() > 0) {
                $this->info("Found {$pendingRequests->count()} pending teacher request(s):");
                foreach ($pendingRequests as $request) {
                    $request->status = 'rejected';
                    $request->rejection_reason = 'Admin email cannot be used for teacher requests';
                    $request->save();
                    $this->line("  - Rejected request ID: {$request->request_id}");
                }
                $this->info("✓ Rejected all pending teacher requests");
            } else {
                $this->info("○ No pending teacher requests found");
            }

            // Step 4: Update any approved teacher requests to rejected (if they exist)
            $approvedRequests = TeacherRequest::where('email', $email)
                ->where('status', 'approved')
                ->get();
            
            if ($approvedRequests->count() > 0) {
                $this->info("Found {$approvedRequests->count()} approved teacher request(s) - updating status:");
                foreach ($approvedRequests as $request) {
                    $request->status = 'rejected';
                    $request->rejection_reason = 'Admin email cannot be used for teacher requests - corrected';
                    $request->save();
                    $this->line("  - Updated request ID: {$request->request_id}");
                }
                $this->info("✓ Updated approved teacher requests");
            }

            DB::commit();

            $this->newLine();
            $this->info("✓ All fixes completed successfully!");
            $this->info("User '{$email}' is now restored as admin and can log in.");
            $this->newLine();
            $this->warn("Note: If you still cannot log in, the password may have been changed.");
            $this->warn("You may need to reset the password or contact support.");
            
            return 0;
        } catch (\Exception $e) {
            DB::rollBack();
            $this->error("Error: " . $e->getMessage());
            $this->error("Transaction rolled back.");
            return 1;
        }
    }
}
