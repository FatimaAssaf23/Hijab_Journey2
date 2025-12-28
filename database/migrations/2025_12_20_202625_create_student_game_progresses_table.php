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
        Schema::create('student_game_progresses', function (Blueprint $table) {
            $table->id('progress_id');
            $table->unsignedInteger('game_id');
            $table->unsignedInteger('student_id');
            $table->enum('status', ['not_started', 'in_progress', 'completed'])->default('not_started');
            $table->integer('score')->default(0);
            $table->timestamp('started_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->integer('attempts')->default(0);
            $table->timestamps();

            $table->foreign('game_id')->references('game_id')->on('games');
            $table->foreign('student_id')->references('student_id')->on('students');
            $table->unique(['game_id', 'student_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student_game_progresses');
    }
};
