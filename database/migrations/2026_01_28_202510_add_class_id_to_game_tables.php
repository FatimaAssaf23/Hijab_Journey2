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
        // Add class_id to games table
        Schema::table('games', function (Blueprint $table) {
            $table->unsignedInteger('class_id')->nullable()->after('lesson_id');
            $table->foreign('class_id')->references('class_id')->on('student_classes')->onDelete('cascade');
        });

        // Add class_id to clock_games table
        Schema::table('clock_games', function (Blueprint $table) {
            $table->unsignedInteger('class_id')->nullable()->after('lesson_id');
            $table->foreign('class_id')->references('class_id')->on('student_classes')->onDelete('cascade');
        });

        // Add class_id to word_search_games table
        Schema::table('word_search_games', function (Blueprint $table) {
            $table->unsignedInteger('class_id')->nullable()->after('lesson_id');
            $table->foreign('class_id')->references('class_id')->on('student_classes')->onDelete('cascade');
        });

        // Add class_id to matching_pairs_games table
        Schema::table('matching_pairs_games', function (Blueprint $table) {
            $table->unsignedInteger('class_id')->nullable()->after('lesson_id');
            $table->foreign('class_id')->references('class_id')->on('student_classes')->onDelete('cascade');
        });

        // Add class_id to group_word_pairs table
        Schema::table('group_word_pairs', function (Blueprint $table) {
            $table->unsignedInteger('class_id')->nullable()->after('lesson_id');
            $table->foreign('class_id')->references('class_id')->on('student_classes')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Remove class_id from group_word_pairs table
        Schema::table('group_word_pairs', function (Blueprint $table) {
            $table->dropForeign(['class_id']);
            $table->dropColumn('class_id');
        });

        // Remove class_id from matching_pairs_games table
        Schema::table('matching_pairs_games', function (Blueprint $table) {
            $table->dropForeign(['class_id']);
            $table->dropColumn('class_id');
        });

        // Remove class_id from word_search_games table
        Schema::table('word_search_games', function (Blueprint $table) {
            $table->dropForeign(['class_id']);
            $table->dropColumn('class_id');
        });

        // Remove class_id from clock_games table
        Schema::table('clock_games', function (Blueprint $table) {
            $table->dropForeign(['class_id']);
            $table->dropColumn('class_id');
        });

        // Remove class_id from games table
        Schema::table('games', function (Blueprint $table) {
            $table->dropForeign(['class_id']);
            $table->dropColumn('class_id');
        });
    }
};
