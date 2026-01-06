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
            $table->unsignedBigInteger('lesson_group_id')->after('id');
            $table->string('word')->after('lesson_group_id');
            $table->string('definition')->after('word');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('group_word_pairs', function (Blueprint $table) {
            $table->dropColumn(['lesson_group_id', 'word', 'definition']);
        });
    }
};
