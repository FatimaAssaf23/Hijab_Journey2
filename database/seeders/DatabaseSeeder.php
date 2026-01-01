<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        // Default admin user (direct Eloquent, not factory)
        try {
            $admin = User::create([
                'first_name' => 'Admin',
                'last_name' => 'User',
                'email' => 'admin@admin.com',
                'password' => \Illuminate\Support\Facades\Hash::make('admin1234'), // Default password
                'role' => 'admin',
                'profile_image_url' => null,
                'bio' => null,
                'phone_number' => null,
                'date_joined' => now(),
            ]);
            echo "Admin user created: ";
            print_r($admin->toArray());
        } catch (\Exception $e) {
            echo "Failed to create admin user: " . $e->getMessage();
        }
    }
}
