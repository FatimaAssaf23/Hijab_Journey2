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

        $levels = [
            [
                'class_id' => $classId,
                'level_name' => 'Level 1',
                'level_number' => 1,
                'description' => 'Beginner level',
                'prerequisite_level_id' => null,
                'is_locked_by_default' => false,
            ],
            [
                'class_id' => $classId,
                'level_name' => 'Level 2',
                'level_number' => 2,
                'description' => 'Intermediate level',
                'prerequisite_level_id' => null,
                'is_locked_by_default' => false,
            ],
            [
                'class_id' => $classId,
                'level_name' => 'Level 3',
                'level_number' => 3,
                'description' => 'Advanced level',
                'prerequisite_level_id' => null,
                'is_locked_by_default' => false,
            ],
            [
                'class_id' => $classId,
                'level_name' => 'Level 4',
                'level_number' => 4,
                'description' => 'Expert level',
                'prerequisite_level_id' => null,
                'is_locked_by_default' => false,
            ],
        ];

        foreach ($levels as $level) {
            DB::table('levels')->updateOrInsert(
                ['level_name' => $level['level_name']],
                array_merge($level, ['created_at' => now(), 'updated_at' => now()])
            );
        }
    }
}
