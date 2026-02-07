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
        Schema::create('meeting_enrollments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('meeting_id');
            $table->foreignId('student_id')->constrained('users', 'user_id')->onDelete('cascade');
            $table->enum('attendance_status', ['pending', 'present', 'absent'])->default('pending');
            $table->dateTime('joined_at')->nullable();
            $table->dateTime('left_at')->nullable();
            $table->timestamps();
            
            $table->unique(['meeting_id', 'student_id']);
        });
        
        // Add foreign key constraint
        // Note: This assumes meetings table uses 'meeting_id' as primary key (existing structure)
        // If you're using the new structure with 'id', modify this foreign key accordingly
        if (Schema::hasTable('meetings')) {
            Schema::table('meeting_enrollments', function (Blueprint $table) {
                // Try meeting_id first (existing structure)
                if (Schema::hasColumn('meetings', 'meeting_id')) {
                    $table->foreign('meeting_id')->references('meeting_id')->on('meetings')->onDelete('cascade');
                } else {
                    // Fallback to id (new structure)
                    $table->foreign('meeting_id')->references('id')->on('meetings')->onDelete('cascade');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('meeting_enrollments');
    }
};
