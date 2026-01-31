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
        Schema::create('student_risk_predictions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('student_id');
            $table->unsignedInteger('current_level_id')->nullable();
            $table->integer('risk_level'); // 0, 1, 2
            $table->string('risk_label', 50); // "Will Pass", "May Struggle", "Needs Help"
            $table->decimal('confidence', 5, 2); // Probability percentage
            $table->decimal('avg_watch_pct', 5, 2)->nullable();
            $table->decimal('avg_quiz_score', 5, 2)->nullable();
            $table->integer('days_inactive')->default(0);
            $table->integer('lessons_completed')->default(0);
            $table->timestamp('predicted_at')->nullable();
            $table->timestamps();

            $table->foreign('student_id')->references('student_id')->on('students')->onDelete('cascade');
            $table->foreign('current_level_id')->references('level_id')->on('levels')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student_risk_predictions');
    }
};
