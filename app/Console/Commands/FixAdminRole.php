<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Teacher;
use App\Models\TeacherRequest;

class FixAdminRole extends Command
{
    protected $signature = 'fix:admin-role {email}';
    protected $description = 'Fix admin user role that was mistakenly changed to teacher. Restores admin role and removes teacher record.';

    public function handle()
    {
        $email = $this->argument('email');
        
        $this->info("Fixing admin role for email: {$email}");
        $this->newLine();

        // Find the user by email
        $user = User::where('email', $email)->first();
        
        if (!$user) {
            $this->error("User with email '{$email}' not found!");
            return 1;
        }

        $this->info("Found user: ID {$user->user_id}, Current role: {$user->role}");
        
        // Check if user is already admin
        if ($user->role === 'admin') {
            $this->info("User is already an admin. Checking for teacher records to remove...");
        } else {
            // Restore admin role
            $user->role = 'admin';
            $user->save();
            $this->info("✓ Restored user role to 'admin'");
        }

        // Remove teacher record if it exists
        $teacher = Teacher::where('user_id', $user->user_id)->first();
        if ($teacher) {
            $teacher->delete();
            $this->info("✓ Removed teacher record (ID: {$teacher->teacher_id})");
        } else {
            $this->info("○ No teacher record found to remove");
        }

        // Find and reject/delete any pending teacher requests for this email
        $teacherRequests = TeacherRequest::where('email', $email)
            ->where('status', 'pending')
            ->get();
        
        if ($teacherRequests->count() > 0) {
            foreach ($teacherRequests as $request) {
                $request->status = 'rejected';
                $request->rejection_reason = 'Admin email cannot be used for teacher requests';
                $request->save();
                $this->info("✓ Rejected teacher request ID: {$request->request_id}");
            }
        } else {
            $this->info("○ No pending teacher requests found for this email");
        }

        $this->newLine();
        $this->info("✓ Admin role fix completed successfully!");
        $this->info("User '{$email}' is now restored as admin.");
        
        return 0;
    }
}
