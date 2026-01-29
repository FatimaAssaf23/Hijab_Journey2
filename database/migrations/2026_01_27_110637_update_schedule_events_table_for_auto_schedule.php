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
        Schema::table('schedule_events', function (Blueprint $table) {
            // Add new columns for auto-schedule system first
            $table->unsignedBigInteger('schedule_id')->nullable()->after('event_id');
            $table->date('release_date')->nullable()->after('event_date');
            $table->enum('status', ['pending', 'released', 'completed'])->default('pending')->after('event_type');
            $table->unsignedBigInteger('lesson_id')->nullable()->after('status');
            $table->unsignedBigInteger('level_id')->nullable()->after('lesson_id');
            $table->unsignedBigInteger('assignment_id')->nullable()->after('level_id');
            $table->unsignedBigInteger('quiz_id')->nullable()->after('assignment_id');
            
            // Admin editing fields
            $table->boolean('edited_by_admin')->default(false)->after('quiz_id');
            $table->unsignedBigInteger('admin_id')->nullable()->after('edited_by_admin');
            $table->text('admin_notes')->nullable()->after('admin_id');
        });
        
        // Migrate data: copy event_date to release_date
        DB::statement('UPDATE schedule_events SET release_date = event_date WHERE release_date IS NULL');
        
        Schema::table('schedule_events', function (Blueprint $table) {
            // Make release_date required now
            $table->date('release_date')->nullable(false)->change();
            
            // Change event_type to support new types (keep existing values for backward compatibility)
            // Note: MySQL doesn't support changing enum values easily, so we'll use string instead
            $table->string('event_type')->default('task')->change();
            
            // Add foreign keys
            $table->foreign('schedule_id')->references('schedule_id')->on('schedules')->onDelete('cascade');
            $table->foreign('lesson_id')->references('lesson_id')->on('lessons')->onDelete('cascade');
            $table->foreign('level_id')->references('level_id')->on('levels')->onDelete('cascade');
            $table->foreign('assignment_id')->references('assignment_id')->on('assignments')->onDelete('set null');
            $table->foreign('quiz_id')->references('quiz_id')->on('quizzes')->onDelete('set null');
            $table->foreign('admin_id')->references('user_id')->on('users')->onDelete('set null');
            
            // Add indexes
            $table->index(['schedule_id', 'release_date']);
            $table->index(['release_date', 'status']);
            $table->index('event_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('schedule_events', function (Blueprint $table) {
            // Drop foreign keys
            $table->dropForeign(['schedule_id']);
            $table->dropForeign(['lesson_id']);
            $table->dropForeign(['level_id']);
            $table->dropForeign(['assignment_id']);
            $table->dropForeign(['quiz_id']);
            $table->dropForeign(['admin_id']);
            
            // Drop indexes
            $table->dropIndex(['schedule_id', 'release_date']);
            $table->dropIndex(['release_date', 'status']);
            $table->dropIndex(['event_type']);
            
            // Drop columns
            $table->dropColumn([
                'schedule_id',
                'release_date',
                'status',
                'lesson_id',
                'level_id',
                'assignment_id',
                'quiz_id',
                'edited_by_admin',
                'admin_id',
                'admin_notes'
            ]);
            
            // Restore event_type to original
            $table->string('event_type')->default('task')->change();
        });
    }
};
