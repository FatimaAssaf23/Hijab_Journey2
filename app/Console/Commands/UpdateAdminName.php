<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;

class UpdateAdminName extends Command
{
    protected $signature = 'fix:update-admin-name {email} {first_name} {last_name}';
    protected $description = 'Update admin user first name and last name';

    public function handle()
    {
        $email = $this->argument('email');
        $firstName = $this->argument('first_name');
        $lastName = $this->argument('last_name');
        
        $user = User::where('email', $email)->first();
        
        if (!$user) {
            $this->error("User with email '{$email}' not found!");
            return 1;
        }

        $oldFirstName = $user->first_name;
        $oldLastName = $user->last_name;
        
        $user->first_name = $firstName;
        $user->last_name = $lastName;
        $user->save();

        $this->info("✓ Updated user name successfully!");
        $this->info("  Old: {$oldFirstName} {$oldLastName}");
        $this->info("  New: {$firstName} {$lastName}");
        
        return 0;
    }
}
