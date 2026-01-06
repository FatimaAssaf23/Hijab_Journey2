<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\StudentClass;

class ListClassesInfo extends Command
{
    protected $signature = 'debug:list-classes-info';
    protected $description = 'List all classes with key fields for debugging';

    public function handle()
    {
        $classes = StudentClass::all();
        if ($classes->isEmpty()) {
            $this->info('No classes found.');
            return 0;
        }
        $this->table([
            'class_id', 'class_name', 'level_id', 'teacher_id'
        ],
        $classes->map(function($c) {
            return [
                $c->class_id,
                $c->class_name,
                $c->level_id,
                $c->teacher_id,
            ];
        })->toArray());
        return 0;
    }
}
