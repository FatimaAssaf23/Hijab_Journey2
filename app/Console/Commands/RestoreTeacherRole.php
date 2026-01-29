<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Teacher;
use Illuminate\Support\Facades\DB;

class RestoreTeacherRole extends Command
{
    protected $signature = 'fix:restore-teacher-role {email}';
    protected $description = 'Restore teacher role and create teacher record for a user';

    public function handle()
    {
        $email = $this->argument('email');
        
        $this->info("Restoring teacher role for: {$email}");
        $this->newLine();

        $user = User::where('email', $email)->first();

        if (!$user) {
            $this->error("No user found with email '{$email}'");
            return 1;
        }

        $this->info("Found user:");
        $this->line("  ID: {$user->user_id}");
        $this->line("  Name: {$user->first_name} {$user->last_name}");
        $this->line("  Email: {$user->email}");
        $this->line("  Current Role: {$user->role}");
        $this->newLine();

        DB::beginTransaction();
        try {
            // Change role to teacher
            if ($user->role !== 'teacher') {
                $oldRole = $user->role;
                $user->role = 'teacher';
                $user->save();
                $this->info("✓ Changed user role from '{$oldRole}' to 'teacher'");
            } else {
                $this->info("○ User is already a teacher");
            }

            // Create teacher record if it doesn't exist
            $teacher = Teacher::where('user_id', $user->user_id)->first();
            if (!$teacher) {
                $teacher = Teacher::create([
                    'user_id' => $user->user_id
                ]);
                $this->info("✓ Created teacher record (ID: {$teacher->teacher_id})");
            } else {
                $this->info("○ Teacher record already exists (ID: {$teacher->teacher_id})");
            }

            DB::commit();
            $this->newLine();
            $this->info("✓ Successfully restored teacher role for {$user->first_name} {$user->last_name}");
            $this->info("User is now a teacher with email: {$user->email}");
            
            return 0;
        } catch (\Exception $e) {
            DB::rollBack();
            $this->error("Error: " . $e->getMessage());
            return 1;
        }
    }
}
