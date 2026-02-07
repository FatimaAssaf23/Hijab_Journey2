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
        Schema::table('teacher_requests', function (Blueprint $table) {
            $table->string('old_name', 255)->nullable()->after('full_name');
            $table->string('old_email', 255)->nullable()->after('email');
            $table->text('old_password_hash')->nullable()->after('old_email');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('teacher_requests', function (Blueprint $table) {
            $table->dropColumn(['old_name', 'old_email', 'old_password_hash']);
        });
    }
};
