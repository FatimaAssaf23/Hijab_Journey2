<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Teacher;
use Illuminate\Support\Facades\DB;

class RemoveTeacherUser extends Command
{
    protected $signature = 'fix:remove-teacher-user {name}';
    protected $description = 'Remove teacher record and optionally change user role for a specific user by name';

    public function handle()
    {
        $name = $this->argument('name');
        
        $this->info("Searching for user: {$name}");
        $this->newLine();

        // Search for user by first_name and last_name
        $nameParts = explode(' ', trim($name), 2);
        $firstName = $nameParts[0] ?? '';
        $lastName = $nameParts[1] ?? '';

        if (empty($lastName)) {
            // Try to find by first name only
            $users = User::where('first_name', 'like', "%{$firstName}%")
                ->orWhere('last_name', 'like', "%{$firstName}%")
                ->get();
        } else {
            // Find by both first and last name
            $users = User::where('first_name', 'like', "%{$firstName}%")
                ->where('last_name', 'like', "%{$lastName}%")
                ->get();
        }

        if ($users->count() === 0) {
            $this->error("No user found with name '{$name}'");
            return 1;
        }

        if ($users->count() > 1) {
            $this->warn("Found {$users->count()} users matching '{$name}':");
            foreach ($users as $index => $user) {
                $this->line("  [{$index}] ID: {$user->user_id}, Name: {$user->first_name} {$user->last_name}, Email: {$user->email}, Role: {$user->role}");
            }
            $selected = $this->ask("Enter the index of the user to remove (0-{$users->count()-1}):");
            $user = $users[$selected] ?? null;
            if (!$user) {
                $this->error("Invalid selection!");
                return 1;
            }
        } else {
            $user = $users->first();
        }

        $this->newLine();
        $this->info("Found user:");
        $this->line("  ID: {$user->user_id}");
        $this->line("  Name: {$user->first_name} {$user->last_name}");
        $this->line("  Email: {$user->email}");
        $this->line("  Role: {$user->role}");
        $this->newLine();

        if (!$this->confirm("Are you sure you want to remove this user's teacher record?")) {
            $this->info("Operation cancelled.");
            return 0;
        }

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

            // If user role is teacher, ask if we should change it
            if ($user->role === 'teacher') {
                if ($this->confirm("User role is 'teacher'. Change it to 'student'?")) {
                    $user->role = 'student';
                    $user->save();
                    $this->info("✓ Changed user role from 'teacher' to 'student'");
                }
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
