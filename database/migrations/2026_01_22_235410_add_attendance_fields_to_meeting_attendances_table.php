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
        Schema::table('meeting_attendances', function (Blueprint $table) {
            // Drop the default id column if it exists
            if (Schema::hasColumn('meeting_attendances', 'id')) {
                $table->dropColumn('id');
            }
        });

        Schema::table('meeting_attendances', function (Blueprint $table) {
            // Add attendance_id as primary key
            $table->increments('attendance_id')->first();
            
            // Add foreign key columns
            $table->unsignedInteger('meeting_id')->after('attendance_id');
            $table->unsignedInteger('student_id')->after('meeting_id');
            
            // Add attendance tracking columns
            $table->dateTime('join_time')->after('student_id');
            $table->dateTime('leave_time')->nullable()->after('join_time');
            $table->enum('status', ['on_time', 'late'])->nullable()->after('leave_time');
            $table->integer('duration_minutes')->nullable()->after('status');
        });

        // Add foreign keys and unique constraint
        Schema::table('meeting_attendances', function (Blueprint $table) {
            $table->foreign('meeting_id')->references('meeting_id')->on('meetings')->onDelete('cascade');
            $table->foreign('student_id')->references('student_id')->on('students')->onDelete('cascade');
            $table->unique(['meeting_id', 'student_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('meeting_attendances', function (Blueprint $table) {
            // Drop foreign keys and unique constraint
            $table->dropForeign(['meeting_id']);
            $table->dropForeign(['student_id']);
            $table->dropUnique(['meeting_id', 'student_id']);
        });

        Schema::table('meeting_attendances', function (Blueprint $table) {
            // Drop columns
            $table->dropColumn([
                'attendance_id',
                'meeting_id',
                'student_id',
                'join_time',
                'leave_time',
                'status',
                'duration_minutes'
            ]);
        });

        Schema::table('meeting_attendances', function (Blueprint $table) {
            // Restore default id column
            $table->id()->first();
        });
    }
};
