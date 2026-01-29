<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Teacher;
use Illuminate\Support\Facades\DB;

class RemoveTeacherByName extends Command
{
    protected $signature = 'fix:remove-teacher-by-name {first_name} {last_name}';
    protected $description = 'Remove teacher record for a user by first and last name';

    public function handle()
    {
        $firstName = $this->argument('first_name');
        $lastName = $this->argument('last_name');
        
        $this->info("Searching for user: {$firstName} {$lastName}");
        $this->newLine();

        $user = User::where('first_name', 'like', "%{$firstName}%")
            ->where('last_name', 'like', "%{$lastName}%")
            ->first();

        if (!$user) {
            $this->error("No user found with name '{$firstName} {$lastName}'");
            return 1;
        }

        $this->info("Found user:");
        $this->line("  ID: {$user->user_id}");
        $this->line("  Name: {$user->first_name} {$user->last_name}");
        $this->line("  Email: {$user->email}");
        $this->line("  Role: {$user->role}");
        $this->newLine();

        DB::beginTransaction();
        try {
            // Remove teacher record
            $teacher = Teacher::where('user_id', $user->user_id)->first();
            if ($teacher) {
                $teacher->delete();
                $this->info("✓ Removed teacher record (ID: {$teacher->teacher_id})");
            } else {
                $this->info("○ No teacher record found");
            }

            // If user role is teacher, change it to student
            if ($user->role === 'teacher') {
                $user->role = 'student';
                $user->save();
                $this->info("✓ Changed user role from 'teacher' to 'student'");
            }

            DB::commit();
            $this->newLine();
            $this->info("✓ Successfully removed teacher record for {$user->first_name} {$user->last_name}");
            
            return 0;
        } catch (\Exception $e) {
            DB::rollBack();
            $this->error("Error: " . $e->getMessage());
            return 1;
        }
    }
}
