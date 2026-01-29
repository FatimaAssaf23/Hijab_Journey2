<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class ResetAdminPassword extends Command
{
    protected $signature = 'fix:reset-admin-password {email} {--password=}';
    protected $description = 'Reset admin password. If --password is not provided, will prompt for it.';

    public function handle()
    {
        $email = $this->argument('email');
        $password = $this->option('password');
        
        $user = User::where('email', $email)->first();
        
        if (!$user) {
            $this->error("User with email '{$email}' not found!");
            return 1;
        }

        if ($user->role !== 'admin') {
            $this->warn("Warning: User role is '{$user->role}', not 'admin'");
            if (!$this->confirm('Continue anyway?')) {
                return 0;
            }
        }

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
        $this->info("You can now log in with the new password.");
        
        return 0;
    }
}
