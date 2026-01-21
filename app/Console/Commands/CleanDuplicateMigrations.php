<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class CleanDuplicateMigrations extends Command
{
    protected $signature = 'fix:clean-duplicate-migrations';
    protected $description = 'Remove duplicate migration entries, keeping only one per migration';

    public function handle()
    {
        $migrations = [
            '2026_01_14_002435_add_image_and_color_to_quiz_tables',
            '2026_01_14_004428_add_background_color_to_quiz_questions_table',
            '2026_01_15_223249_add_video_tracking_fields_to_student_lesson_progresses_table',
            '2026_01_15_223643_add_video_completed_to_student_lesson_progresses_table',
            '2026_01_18_202621_add_video_path_to_lessons_table',
        ];

        $this->info('Cleaning up duplicate migration entries...');
        $this->newLine();

        $totalRemoved = 0;

        foreach ($migrations as $migration) {
            $entries = DB::table('migrations')
                ->where('migration', $migration)
                ->orderBy('id')
                ->get();

            if ($entries->count() > 1) {
                // Keep the first one, delete the rest
                $keepId = $entries->first()->id;
                $deleteIds = $entries->skip(1)->pluck('id')->toArray();
                
                DB::table('migrations')->whereIn('id', $deleteIds)->delete();
                
                $removed = count($deleteIds);
                $totalRemoved += $removed;
                $this->info("✓ Cleaned {$migration}: removed {$removed} duplicate(s), kept 1 entry");
            } else {
                $this->line("○ {$migration}: no duplicates (already 1 entry)");
            }
        }

        $this->newLine();
        $this->info("Summary: {$totalRemoved} duplicate entries removed");
        
        return 0;
    }
}
