<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LevelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // First, ensure there's a default student class
        $existingClass = DB::table('student_classes')->first();
        
        if (!$existingClass) {
            // Get any existing user to be the teacher
            $userId = DB::table('users')->value('user_id');
            
            $classId = DB::table('student_classes')->insertGetId([
                'class_name' => 'Default Class',
                'teacher_id' => $userId,
                'capacity' => 30,
                'current_enrollment' => 0,
                'status' => 'active',
                'description' => 'Default class for lessons',
                'created_at' => now(),
                'updated_at' => now(),
            ], 'class_id');
        } else {
            $classId = $existingClass->class_id;
        }

        $levels = [];
        for ($i = 1; $i <= 10; $i++) {
            $levels[] = [
                'class_id' => $classId,
                'level_id' => $i,
                'level_name' => 'Level ' . $i,
                'level_number' => $i,
                'description' => 'Auto-generated level ' . $i,
                'prerequisite_level_id' => null,
                'is_locked_by_default' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }
        DB::table('levels')->insert($levels);
    }
}
