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
        Schema::create('class_lesson_visibilities', function (Blueprint $table) {
            $table->id('visibility_id');
            $table->unsignedInteger('class_id');
            $table->unsignedBigInteger('lesson_id');
            $table->unsignedInteger('teacher_id');
            $table->boolean('is_visible')->default(true);
            $table->timestamp('changed_at')->useCurrent();
            $table->timestamps();

            $table->foreign('class_id')->references('class_id')->on('student_classes');
            $table->foreign('lesson_id')->references('lesson_id')->on('lessons');
            $table->foreign('teacher_id')->references('user_id')->on('users');
            $table->unique(['class_id', 'lesson_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('class_lesson_visibilities');
    }
};
