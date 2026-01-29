<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class ResetUserPassword extends Command
{
    protected $signature = 'fix:reset-user-password {email} {--password=}';
    protected $description = 'Reset password for a user account';

    public function handle()
    {
        $email = $this->argument('email');
        $password = $this->option('password');
        
        $user = User::where('email', $email)->first();
        
        if (!$user) {
            $this->error("User with email '{$email}' not found!");
            return 1;
        }

        $this->info("Found user:");
        $this->line("  ID: {$user->user_id}");
        $this->line("  Name: {$user->first_name} {$user->last_name}");
        $this->line("  Email: {$user->email}");
        $this->line("  Role: {$user->role}");
        $this->newLine();

        if (!$password) {
            $password = $this->secret('Enter new password (min 8 characters):');
            $passwordConfirm = $this->secret('Confirm new password:');
            
            if ($password !== $passwordConfirm) {
                $this->error('Passwords do not match!');
                return 1;
            }
            
            if (strlen($password) < 8) {
                $this->error('Password must be at least 8 characters!');
                return 1;
            }
        }

        $user->password = Hash::make($password);
        $user->save();

        $this->info("✓ Password reset successfully for {$email}");
        if ($password) {
            $this->info("New password: {$password}");
        } else {
            $this->info("You can now log in with the new password you entered.");
        }
        
        return 0;
    }
}
