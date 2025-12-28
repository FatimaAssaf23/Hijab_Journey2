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
        Schema::create('assignment_submissions', function (Blueprint $table) {
            $table->increments('submission_id');
            $table->unsignedInteger('assignment_id');
            $table->unsignedInteger('student_id');
            $table->string('submission_file_url', 500)->nullable();
            $table->dateTime('submitted_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->enum('status', ['submitted', 'graded', 'late'])->default('submitted');
            $table->boolean('is_late')->default(false);
            $table->timestamps();

            $table->unique(['assignment_id', 'student_id']);
            $table->foreign('assignment_id')->references('assignment_id')->on('assignments');
            $table->foreign('student_id')->references('student_id')->on('students');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('assignment_submissions');
    }
};
