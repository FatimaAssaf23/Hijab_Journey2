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
        Schema::create('word_search_games', function (Blueprint $table) {
            $table->increments('word_search_game_id');
            $table->unsignedInteger('game_id')->nullable();
            $table->unsignedInteger('lesson_id');
            $table->json('words'); // Array of words to find
            $table->integer('grid_size')->default(10); // Grid size (e.g., 10x10)
            $table->json('grid_data')->nullable(); // The generated grid with letters
            $table->timestamps();

            // Foreign key to games table
            $table->foreign('game_id')->references('game_id')->on('games')->onDelete('cascade');
            // Foreign key to lessons table
            $table->foreign('lesson_id')->references('lesson_id')->on('lessons')->onDelete('cascade');
            
            // Ensure one word search game per lesson
            $table->unique('lesson_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('word_search_games');
    }
};
