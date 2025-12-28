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
        Schema::create('quiz_questions', function (Blueprint $table) {
            $table->increments('question_id');
            $table->unsignedInteger('quiz_id');
            $table->text('question_text');
            $table->integer('question_order');
            $table->integer('points')->default(1);
            $table->timestamps();

            $table->foreign('quiz_id')->references('quiz_id')->on('quizzes');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quiz_questions');
    }
};
