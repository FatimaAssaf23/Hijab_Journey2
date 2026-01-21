<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Schema\Blueprint;

class FixMissingMigrationColumns extends Command
{
    protected $signature = 'fix:missing-migration-columns';
    protected $description = 'Add missing columns from the last 5 migrations that were marked as run but columns are missing';

    public function handle()
    {
        $this->info('Checking and adding missing columns...');
        $added = 0;
        $skipped = 0;

        // 1. Check and add to quizzes table
        if (!Schema::hasColumn('quizzes', 'background_color')) {
            try {
                DB::statement("ALTER TABLE `quizzes` ADD COLUMN `background_color` VARCHAR(7) NULL AFTER `description`");
                $this->info('✓ Added background_color to quizzes table');
                $added++;
            } catch (\Exception $e) {
                $this->warn('✗ Could not add background_color to quizzes: ' . $e->getMessage());
                $skipped++;
            }
        } else {
            $this->info('○ background_color already exists in quizzes table');
            $skipped++;
        }

        // 2. Check and add to quiz_questions table
        if (!Schema::hasColumn('quiz_questions', 'image_path')) {
            try {
                DB::statement("ALTER TABLE `quiz_questions` ADD COLUMN `image_path` VARCHAR(255) NULL AFTER `question_text`");
                $this->info('✓ Added image_path to quiz_questions table');
                $added++;
            } catch (\Exception $e) {
                $this->warn('✗ Could not add image_path to quiz_questions: ' . $e->getMessage());
                $skipped++;
            }
        } else {
            $this->info('○ image_path already exists in quiz_questions table');
            $skipped++;
        }

        if (!Schema::hasColumn('quiz_questions', 'background_color')) {
            try {
                DB::statement("ALTER TABLE `quiz_questions` ADD COLUMN `background_color` VARCHAR(7) NULL AFTER `image_path`");
                $this->info('✓ Added background_color to quiz_questions table');
                $added++;
            } catch (\Exception $e) {
                $this->warn('✗ Could not add background_color to quiz_questions: ' . $e->getMessage());
                $skipped++;
            }
        } else {
            $this->info('○ background_color already exists in quiz_questions table');
            $skipped++;
        }

        // 3. Check and add video tracking fields to student_lesson_progresses
        $videoTrackingFields = [
            'watched_seconds' => "INT NOT NULL DEFAULT 0",
            'watched_percentage' => "DECIMAL(5,2) NOT NULL DEFAULT 0",
            'last_position' => "DECIMAL(10,2) NULL",
            'max_watched_time' => "DECIMAL(10,2) NOT NULL DEFAULT 0",
            'last_watched_at' => "DATETIME NULL",
        ];

        foreach ($videoTrackingFields as $column => $definition) {
            if (!Schema::hasColumn('student_lesson_progresses', $column)) {
                try {
                    $afterColumn = $this->getAfterColumn($column, array_keys($videoTrackingFields));
                    DB::statement("ALTER TABLE `student_lesson_progresses` ADD COLUMN `{$column}` {$definition} AFTER `{$afterColumn}`");
                    $this->info("✓ Added {$column} to student_lesson_progresses table");
                    $added++;
                } catch (\Exception $e) {
                    $this->warn("✗ Could not add {$column} to student_lesson_progresses: " . $e->getMessage());
                    $skipped++;
                }
            } else {
                $this->info("○ {$column} already exists in student_lesson_progresses table");
                $skipped++;
            }
        }

        // 4. Add video_completed to student_lesson_progresses
        if (!Schema::hasColumn('student_lesson_progresses', 'video_completed')) {
            try {
                DB::statement("ALTER TABLE `student_lesson_progresses` ADD COLUMN `video_completed` TINYINT(1) NOT NULL DEFAULT 0 AFTER `last_watched_at`");
                $this->info('✓ Added video_completed to student_lesson_progresses table');
                $added++;
            } catch (\Exception $e) {
                $this->warn('✗ Could not add video_completed to student_lesson_progresses: ' . $e->getMessage());
                $skipped++;
            }
        } else {
            $this->info('○ video_completed already exists in student_lesson_progresses table');
            $skipped++;
        }

        // 5. Add video_path to lessons table
        if (!Schema::hasColumn('lessons', 'video_path')) {
            try {
                DB::statement("ALTER TABLE `lessons` ADD COLUMN `video_path` VARCHAR(500) NULL AFTER `content_url`");
                $this->info('✓ Added video_path to lessons table');
                $added++;
            } catch (\Exception $e) {
                $this->warn('✗ Could not add video_path to lessons: ' . $e->getMessage());
                $skipped++;
            }
        } else {
            $this->info('○ video_path already exists in lessons table');
            $skipped++;
        }

        $this->newLine();
        $this->info("Summary: {$added} columns added, {$skipped} columns skipped (already exist or errors)");
        
        return 0;
    }

    private function getAfterColumn($currentColumn, $allColumns)
    {
        $order = [
            'time_spent_minutes' => null,
            'watched_seconds' => 'time_spent_minutes',
            'watched_percentage' => 'watched_seconds',
            'last_position' => 'watched_percentage',
            'max_watched_time' => 'last_position',
            'last_watched_at' => 'max_watched_time',
            'video_completed' => 'last_watched_at',
        ];

        return $order[$currentColumn] ?? 'time_spent_minutes';
    }
}
