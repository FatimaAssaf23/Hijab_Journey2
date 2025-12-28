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
        Schema::create('quiz_attempts', function (Blueprint $table) {
            $table->increments('attempt_id');
            $table->unsignedInteger('quiz_id');
            $table->unsignedInteger('student_id');
            $table->dateTime('started_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->dateTime('submitted_at')->nullable();
            $table->integer('time_taken_minutes')->nullable();
            $table->decimal('score', 5, 2)->nullable();
            $table->enum('status', ['in_progress', 'completed', 'time_expired', 'abandoned'])->default('in_progress');
            $table->timestamps();

            $table->foreign('quiz_id')->references('quiz_id')->on('quizzes');
            $table->foreign('student_id')->references('student_id')->on('students');
            $table->index(['quiz_id', 'student_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quiz_attempts');
    }
};
