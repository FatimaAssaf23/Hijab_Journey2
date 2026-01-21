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
        Schema::table('lessons', function (Blueprint $table) {
            $table->unsignedBigInteger('video_size')->nullable()->after('content_url')->comment('Video file size in bytes');
            $table->string('video_format', 50)->nullable()->after('video_size')->comment('Video format (e.g., mp4, mov, avi)');
            $table->unsignedInteger('video_duration_seconds')->nullable()->after('video_format')->comment('Video duration in seconds');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('lessons', function (Blueprint $table) {
            $table->dropColumn(['video_size', 'video_format', 'video_duration_seconds']);
        });
    }
};
