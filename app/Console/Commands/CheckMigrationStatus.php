<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class CheckMigrationStatus extends Command
{
    protected $signature = 'check:migration-status';
    protected $description = 'Check the status of the last 5 migrations (entries and columns)';

    public function handle()
    {
        $migrations = [
            '2026_01_14_002435_add_image_and_color_to_quiz_tables',
            '2026_01_14_004428_add_background_color_to_quiz_questions_table',
            '2026_01_15_223249_add_video_tracking_fields_to_student_lesson_progresses_table',
            '2026_01_15_223643_add_video_completed_to_student_lesson_progresses_table',
            '2026_01_18_202621_add_video_path_to_lessons_table',
        ];

        $this->info('Checking migration entries in migrations table:');
        $this->newLine();

        foreach ($migrations as $migration) {
            $count = DB::table('migrations')->where('migration', $migration)->count();
            $this->line("  {$migration}: {$count} entry/ies");
        }

        $this->newLine();
        $this->info('Summary:');
        $totalEntries = DB::table('migrations')->whereIn('migration', $migrations)->count();
        $this->line("  Total entries for these 5 migrations: {$totalEntries}");
        $this->line("  Expected: 5 entries");
        
        if ($totalEntries > 5) {
            $this->warn("  ⚠ WARNING: There are duplicate entries!");
        } else if ($totalEntries == 5) {
            $this->info("  ✓ All migration entries exist (no duplicates)");
        } else {
            $this->error("  ✗ Missing migration entries!");
        }

        return 0;
    }
}
