<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class AddMissingMigrationEntries extends Command
{
    protected $signature = 'fix:add-migration-entries';
    protected $description = 'Add missing migration entries to migrations table';

    public function handle()
    {
        $migrations = [
            '2026_01_14_002435_add_image_and_color_to_quiz_tables',
            '2026_01_14_004428_add_background_color_to_quiz_questions_table',
            '2026_01_15_223249_add_video_tracking_fields_to_student_lesson_progresses_table',
            '2026_01_15_223643_add_video_completed_to_student_lesson_progresses_table',
            '2026_01_18_202621_add_video_path_to_lessons_table',
        ];

        $maxBatch = DB::table('migrations')->max('batch') ?? 0;
        $batch = $maxBatch;

        $added = 0;
        $alreadyExists = 0;

        foreach ($migrations as $migration) {
            $exists = DB::table('migrations')->where('migration', $migration)->exists();
            
            if (!$exists) {
                DB::table('migrations')->insert([
                    'migration' => $migration,
                    'batch' => $batch,
                ]);
                $this->info("✓ Added migration entry: {$migration}");
                $added++;
            } else {
                $this->info("○ Migration entry already exists: {$migration}");
                $alreadyExists++;
            }
        }

        $this->newLine();
        $this->info("Summary: {$added} migration entries added, {$alreadyExists} already existed");
        
        return 0;
    }
}
