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
            // Drop the existing unique constraint on (lesson_id, game_type)
            $table->dropUnique('games_lesson_id_game_type_unique');
            // Add new composite unique constraint on (lesson_id, class_id, game_type)
            $table->unique(['lesson_id', 'class_id', 'game_type'], 'games_lesson_id_class_id_game_type_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('games', function (Blueprint $table) {
            // Drop the composite unique constraint with class_id
            $table->dropUnique('games_lesson_id_class_id_game_type_unique');
            // Restore the original unique constraint on (lesson_id, game_type)
            $table->unique(['lesson_id', 'game_type'], 'games_lesson_id_game_type_unique');
        });
    }
};
