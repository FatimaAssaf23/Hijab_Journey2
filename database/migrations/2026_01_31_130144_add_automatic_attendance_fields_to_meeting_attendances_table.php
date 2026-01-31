<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('meeting_attendances', function (Blueprint $table) {
            // Add new fields for automatic attendance system
            $table->dateTime('joined_at')->nullable()->after('join_time');
            $table->dateTime('last_confirmed_at')->nullable()->after('joined_at');
        });

        // Modify status enum to include new values
        // Note: We need to use raw SQL to modify enum in MySQL
        DB::statement("ALTER TABLE meeting_attendances MODIFY COLUMN status ENUM('pending', 'present', 'absent', 'on_time', 'late') NULL");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('meeting_attendances', function (Blueprint $table) {
            $table->dropColumn(['joined_at', 'last_confirmed_at']);
        });

        // Revert status enum to original values
        DB::statement("ALTER TABLE meeting_attendances MODIFY COLUMN status ENUM('on_time', 'late') NULL");
    }
};
