<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Lesson;
use App\Models\Level;
use App\Models\StudentClass;
use Illuminate\Support\Facades\DB;

class LessonSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Disable foreign key checks for SQLite
        DB::statement('PRAGMA foreign_keys = OFF');
        
        // Clear existing lessons
        Lesson::query()->delete();
        Level::query()->delete();

        // Create a default class if none exists
        $class = StudentClass::first();
        if (!$class) {
            $class = StudentClass::create([
                'class_name' => 'Default Math Class',
                'teacher_id' => 1, // We'll use admin user id
                'capacity' => 30,
                'current_enrollment' => 0,
                'status' => 'active',
                'description' => 'Default class for math lessons',
            ]);
        }

        // Create levels
        $levels = [
            ['level_name' => 'Level 1', 'level_number' => 1, 'description' => 'Beginner level', 'class_id' => $class->class_id],
            ['level_name' => 'Level 2', 'level_number' => 2, 'description' => 'Elementary level', 'class_id' => $class->class_id],
            ['level_name' => 'Level 3', 'level_number' => 3, 'description' => 'Intermediate level', 'class_id' => $class->class_id],
            ['level_name' => 'Level 4', 'level_number' => 4, 'description' => 'Advanced level', 'class_id' => $class->class_id],
        ];

        $createdLevels = [];
        foreach ($levels as $level) {
            $createdLevels[] = Level::create($level);
        }

        $lessons = [
            ['level_id' => $createdLevels[0]->level_id, 'title' => 'Addition', 'skills' => 21, 'icon' => '➕', 'description' => 'Learn basic addition with fun exercises', 'duration_minutes' => 30, 'lesson_order' => 1, 'is_visible' => true],
            ['level_id' => $createdLevels[0]->level_id, 'title' => 'Subtraction', 'skills' => 8, 'icon' => '➖', 'description' => 'Master subtraction step by step', 'duration_minutes' => 25, 'lesson_order' => 2, 'is_visible' => true],
            ['level_id' => $createdLevels[1]->level_id, 'title' => 'Multiplication', 'skills' => 15, 'icon' => '✖️', 'description' => 'Explore multiplication tables and tricks', 'duration_minutes' => 35, 'lesson_order' => 1, 'is_visible' => true],
            ['level_id' => $createdLevels[1]->level_id, 'title' => 'Division', 'skills' => 10, 'icon' => '➗', 'description' => 'Understanding division concepts', 'duration_minutes' => 30, 'lesson_order' => 2, 'is_visible' => true],
            ['level_id' => $createdLevels[2]->level_id, 'title' => 'Fractions', 'skills' => 20, 'icon' => '½', 'description' => 'Introduction to fractions and parts', 'duration_minutes' => 40, 'lesson_order' => 1, 'is_visible' => true],
            ['level_id' => $createdLevels[3]->level_id, 'title' => 'Decimals', 'skills' => 18, 'icon' => '🔢', 'description' => 'Working with decimal numbers', 'duration_minutes' => 35, 'lesson_order' => 1, 'is_visible' => true],
            ['level_id' => $createdLevels[3]->level_id, 'title' => 'Percentages', 'skills' => 16, 'icon' => '💯', 'description' => 'Understanding and calculating percentages', 'duration_minutes' => 30, 'lesson_order' => 2, 'is_visible' => true],
            ['level_id' => $createdLevels[3]->level_id, 'title' => 'Algebra Basics', 'skills' => 22, 'icon' => '🔤', 'description' => 'Introduction to algebraic expressions', 'duration_minutes' => 45, 'lesson_order' => 3, 'is_visible' => true],
        ];

        foreach ($lessons as $lesson) {
            Lesson::create($lesson);
        }

        // Re-enable foreign key checks
        DB::statement('PRAGMA foreign_keys = ON');
    }
}
