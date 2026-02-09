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
        Schema::create('student_answers', function (Blueprint $table) {
            $table->id('answer_id');
            $table->unsignedBigInteger('attempt_id');
            $table->unsignedBigInteger('question_id');
            $table->unsignedBigInteger('selected_option_id')->nullable();
            $table->boolean('is_correct')->nullable();
            $table->dateTime('answered_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamps();

            $table->unique(['attempt_id', 'question_id']);
            $table->foreign('attempt_id')->references('attempt_id')->on('quiz_attempts');
            $table->foreign('question_id')->references('question_id')->on('quiz_questions');
            $table->foreign('selected_option_id')->references('option_id')->on('quiz_options');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student_answers');
    }
};
