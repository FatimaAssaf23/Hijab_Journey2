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
            // Video tracking fields
            $table->integer('watched_seconds')->default(0)->after('time_spent_minutes');
            $table->decimal('watched_percentage', 5, 2)->default(0)->after('watched_seconds');
            $table->decimal('last_position', 10, 2)->nullable()->after('watched_percentage');
            $table->decimal('max_watched_time', 10, 2)->default(0)->after('last_position');
            $table->dateTime('last_watched_at')->nullable()->after('max_watched_time');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('student_lesson_progresses', function (Blueprint $table) {
            $table->dropColumn([
                'watched_seconds',
                'watched_percentage',
                'last_position',
                'max_watched_time',
                'last_watched_at'
            ]);
        });
    }
};
