<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Lesson;

class ListLessonsInfo extends Command
{
    protected $signature = 'debug:list-lessons-info';
    protected $description = 'List all lessons with key fields for debugging';

    public function handle()
    {
        $lessons = Lesson::all();
        if ($lessons->isEmpty()) {
            $this->info('No lessons found.');
            return 0;
        }
        $this->table([
            'lesson_id', 'level_id', 'title', 'uploaded_by_admin_id', 'teacher_id', 'is_visible'
        ],
        $lessons->map(function($l) {
            return [
                $l->lesson_id,
                $l->level_id,
                $l->title,
                $l->uploaded_by_admin_id,
                $l->teacher_id,
                $l->is_visible ? 'yes' : 'no',
            ];
        })->toArray());
        return 0;
    }
}
