<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Lesson;
use App\Models\User;

class FixAdminLessonsVisibility extends Command
{
    protected $signature = 'fix:admin-lessons-visibility';
    protected $description = 'Set uploaded_by_admin_id for lessons uploaded by admin (if missing)';

    public function handle()
    {
        $admin = User::first();
        if (!$admin) {
            $this->error('No user found in users table.');
            return 1;
        }
        $count = Lesson::whereNull('uploaded_by_admin_id')->update(['uploaded_by_admin_id' => $admin->user_id]);
        $this->info("Updated $count lessons with user ID {$admin->user_id} as admin.");
        return 0;
    }
}
