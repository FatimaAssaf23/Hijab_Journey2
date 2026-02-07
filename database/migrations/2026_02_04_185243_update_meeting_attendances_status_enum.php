<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Update the status enum to include 'present', 'absent', and 'pending'
        // MySQL doesn't support direct enum modification, so we use raw SQL
        \DB::statement("ALTER TABLE `meeting_attendances` MODIFY COLUMN `status` ENUM('on_time', 'late', 'present', 'absent', 'pending') NULL");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert to original enum values
        \DB::statement("ALTER TABLE `meeting_attendances` MODIFY COLUMN `status` ENUM('on_time', 'late') NULL");
    }
};
