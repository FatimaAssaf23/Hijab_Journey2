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
        Schema::create('student_lesson_progresses', function (Blueprint $table) {
            $table->id('progress_id'); // BIGINT unsigned auto-increment
            $table->foreignId('student_id')->constrained('students', 'student_id');
            $table->foreignId('lesson_id')->constrained('lessons', 'lesson_id');
            $table->enum('status', ['not_started', 'in_progress', 'completed'])->default('not_started');
            $table->dateTime('started_at')->nullable();
            $table->dateTime('completed_at')->nullable();
            $table->integer('score')->nullable();
            $table->integer('time_spent_minutes')->nullable();
            $table->timestamps();
        
            $table->unique(['student_id', 'lesson_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student_lesson_progresses');
    }
};
