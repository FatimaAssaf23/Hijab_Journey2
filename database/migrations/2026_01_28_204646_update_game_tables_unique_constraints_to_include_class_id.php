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
        // Update word_search_games table
        Schema::table('word_search_games', function (Blueprint $table) {
            // Drop the old unique constraint on lesson_id only
            $table->dropUnique('word_search_games_lesson_id_unique');
            // Add new unique constraint on (lesson_id, class_id)
            $table->unique(['lesson_id', 'class_id'], 'word_search_games_lesson_id_class_id_unique');
        });

        // Update matching_pairs_games table
        Schema::table('matching_pairs_games', function (Blueprint $table) {
            // Drop the old unique constraint on lesson_id only
            $table->dropUnique('matching_pairs_games_lesson_id_unique');
            // Add new unique constraint on (lesson_id, class_id)
            $table->unique(['lesson_id', 'class_id'], 'matching_pairs_games_lesson_id_class_id_unique');
        });

        // Update clock_games table
        Schema::table('clock_games', function (Blueprint $table) {
            // Drop the old unique constraint on lesson_id only
            $table->dropUnique('clock_games_lesson_id_unique');
            // Add new unique constraint on (lesson_id, class_id)
            $table->unique(['lesson_id', 'class_id'], 'clock_games_lesson_id_class_id_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert word_search_games table
        Schema::table('word_search_games', function (Blueprint $table) {
            $table->dropUnique('word_search_games_lesson_id_class_id_unique');
            $table->unique('lesson_id', 'word_search_games_lesson_id_unique');
        });

        // Revert matching_pairs_games table
        Schema::table('matching_pairs_games', function (Blueprint $table) {
            $table->dropUnique('matching_pairs_games_lesson_id_class_id_unique');
            $table->unique('lesson_id', 'matching_pairs_games_lesson_id_unique');
        });

        // Revert clock_games table
        Schema::table('clock_games', function (Blueprint $table) {
            $table->dropUnique('clock_games_lesson_id_class_id_unique');
            $table->unique('lesson_id', 'clock_games_lesson_id_unique');
        });
    }
};
