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
        Schema::create('matching_pairs_games', function (Blueprint $table) {
            $table->increments('matching_pairs_game_id');
            $table->unsignedInteger('game_id')->nullable();
            $table->unsignedBigInteger('lesson_id');
            $table->string('title')->nullable();
            $table->timestamps();

            // Foreign key to games table
            $table->foreign('game_id')->references('game_id')->on('games')->onDelete('cascade');
            // Foreign key to lessons table
            $table->foreign('lesson_id')->references('lesson_id')->on('lessons')->onDelete('cascade');
            
            // Ensure one matching pairs game per lesson
            $table->unique('lesson_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('matching_pairs_games');
    }
};
