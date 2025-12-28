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
        Schema::create('quizzes', function (Blueprint $table) {
            $table->increments('quiz_id');
            $table->unsignedInteger('level_id');
            $table->unsignedInteger('class_id');
            $table->unsignedInteger('teacher_id');
            $table->unsignedInteger('checked_by_admin_id')->nullable();
            $table->string('title', 255);
            $table->text('description')->nullable();
            $table->integer('timer_minutes');
            $table->dateTime('due_date');
            $table->integer('max_score')->default(100);
            $table->integer('passing_score')->default(60);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->foreign('level_id')->references('level_id')->on('levels');
            $table->foreign('class_id')->references('class_id')->on('student_classes');
            $table->foreign('teacher_id')->references('user_id')->on('users');
            $table->foreign('checked_by_admin_id')->references('user_id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quizzes');
    }
};
