<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AdminUserSeeder extends Seeder
{
    public function run()
    {
        // Default admin user
        User::updateOrCreate(
            ['email' => 'admin@admin.com'],
            [
                'first_name' => 'Admin',
                'last_name' => 'Admin',
                'password' => Hash::make('admin1234'),
                'role' => 'admin',
                'date_joined' => now(),
            ]
        );

        // New admin user with specified credentials
        User::updateOrCreate(
            ['email' => '10121317@mu.edu.lb'],
            [
                'first_name' => 'Admin',
                'last_name' => 'User',
                'password' => Hash::make('Admin1234/'),
                'role' => 'admin',
                'date_joined' => now(),
            ]
        );
    }
}
