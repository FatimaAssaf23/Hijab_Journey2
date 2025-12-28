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
        Schema::create('grades', function (Blueprint $table) {
            $table->increments('grade_id');
            $table->unsignedInteger('student_id');
            $table->unsignedInteger('teacher_id');
            $table->unsignedInteger('assignment_submission_id')->nullable();
            $table->unsignedInteger('quiz_attempt_id')->nullable();
            $table->decimal('grade_value', 5, 2);
            $table->decimal('max_grade', 5, 2)->default(100);
            $table->decimal('percentage', 5, 2)->nullable();
            $table->text('feedback')->nullable();
            $table->timestamp('graded_at')->useCurrent();
            $table->timestamps();

            $table->foreign('student_id')->references('student_id')->on('students');
            $table->foreign('teacher_id')->references('user_id')->on('users');
            $table->foreign('assignment_submission_id')->references('submission_id')->on('assignment_submissions');
            $table->foreign('quiz_attempt_id')->references('attempt_id')->on('quiz_attempts');
        });

        // Skip check constraint for SQLite (not supported via ALTER TABLE)
        // Constraint logic should be handled in application layer
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('grades');
    }
};
