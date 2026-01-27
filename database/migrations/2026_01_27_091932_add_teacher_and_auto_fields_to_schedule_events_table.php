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
        Schema::table('schedule_events', function (Blueprint $table) {
            $table->unsignedBigInteger('teacher_id')->nullable()->after('event_id');
            $table->unsignedBigInteger('class_id')->nullable()->after('teacher_id');
            $table->boolean('is_auto_generated')->default(false)->after('is_active');
            $table->time('shift_from')->nullable()->after('event_time');
            $table->time('shift_to')->nullable()->after('shift_from');
            
            // Add foreign key constraints
            $table->foreign('teacher_id')->references('user_id')->on('users')->onDelete('cascade');
            $table->foreign('class_id')->references('class_id')->on('student_classes')->onDelete('cascade');
            
            // Add index for better query performance
            $table->index('teacher_id');
            $table->index('class_id');
            $table->index('is_auto_generated');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('schedule_events', function (Blueprint $table) {
            // Drop foreign keys first (required before dropping columns)
            if (Schema::hasColumn('schedule_events', 'teacher_id')) {
                $table->dropForeign(['teacher_id']);
            }
            if (Schema::hasColumn('schedule_events', 'class_id')) {
                $table->dropForeign(['class_id']);
            }
            
            // Drop columns (indexes will be automatically dropped)
            $table->dropColumn(['teacher_id', 'class_id', 'is_auto_generated', 'shift_from', 'shift_to']);
        });
    }
};
