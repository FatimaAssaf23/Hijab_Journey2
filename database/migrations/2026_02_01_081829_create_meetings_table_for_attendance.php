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
        // Note: meetings table already exists, so we just ensure it has the necessary columns
        // This migration is mainly for the new enrollment system structure
        // The actual meetings table was created in an earlier migration
        
        // Just ensure the table exists and has required columns for the new system
        // No need to create it since it already exists
        if (Schema::hasTable('meetings')) {
            // Table exists - ensure it has the columns we need
            Schema::table('meetings', function (Blueprint $table) {
                // These columns should already exist, but we check to be safe
                if (!Schema::hasColumn('meetings', 'google_meet_link')) {
                    $table->string('google_meet_link')->nullable()->after('description');
                }
                // Status column should exist but might need updating
                if (!Schema::hasColumn('meetings', 'status')) {
                    $table->enum('status', ['scheduled', 'ongoing', 'completed', 'cancelled', 'in_progress'])->default('scheduled')->after('duration_minutes');
                }
            });
        }
        // If table doesn't exist (shouldn't happen), it will be created by the original migration
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('meetings');
    }
};
