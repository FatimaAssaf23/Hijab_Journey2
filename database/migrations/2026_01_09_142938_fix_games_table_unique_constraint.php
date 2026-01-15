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
        Schema::table('games', function (Blueprint $table) {
            // Drop the unique constraint on lesson_id
            $table->dropUnique(['lesson_id']);
            // Add composite unique constraint on lesson_id and game_type
            $table->unique(['lesson_id', 'game_type'], 'games_lesson_id_game_type_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('games', function (Blueprint $table) {
            // Drop the composite unique constraint
            $table->dropUnique('games_lesson_id_game_type_unique');
            // Restore the unique constraint on lesson_id
            $table->unique('lesson_id');
        });
    }
};
