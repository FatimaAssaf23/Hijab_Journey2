<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('meeting_enrollments', function (Blueprint $table) {
            $table->engine = 'InnoDB';
        
            $table->increments('id');
        
            $table->unsignedInteger('meeting_id');
            $table->unsignedInteger('student_id'); // MUST match users.user_id (increments)
        
            $table->enum('attendance_status', ['pending', 'present', 'absent'])->default('pending');
            $table->dateTime('joined_at')->nullable();
            $table->dateTime('left_at')->nullable();
            $table->timestamps();
        
            $table->unique(['meeting_id', 'student_id']);
        
            $table->foreign('student_id')
                ->references('user_id')
                ->on('users')
                ->onDelete('cascade');
        
            $table->foreign('meeting_id')
                ->references('meeting_id') 
                ->on('meetings')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('meeting_enrollments');
    }
};