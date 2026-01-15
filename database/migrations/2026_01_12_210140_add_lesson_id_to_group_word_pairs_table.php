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
        Schema::table('group_word_pairs', function (Blueprint $table) {
            // Make lesson_group_id nullable
            $table->unsignedBigInteger('lesson_group_id')->nullable()->change();
            // Add lesson_id column
            $table->unsignedBigInteger('lesson_id')->nullable()->after('id');
            // Add foreign key for lesson_id
            $table->foreign('lesson_id')->references('lesson_id')->on('lessons')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('group_word_pairs', function (Blueprint $table) {
            // Drop foreign key
            $table->dropForeign(['lesson_id']);
            // Drop lesson_id column
            $table->dropColumn('lesson_id');
            // Make lesson_group_id not nullable again
            $table->unsignedBigInteger('lesson_group_id')->nullable(false)->change();
        });
    }
};
