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
        Schema::table('student_lesson_progresses', function (Blueprint $table) {
            $table->dateTime('last_activity_at')->nullable()->after('last_watched_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('student_lesson_progresses', function (Blueprint $table) {
            $table->dropColumn('last_activity_at');
        });
    }
};
