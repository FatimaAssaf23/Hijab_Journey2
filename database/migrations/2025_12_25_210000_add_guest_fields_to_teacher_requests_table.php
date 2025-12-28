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
            // Make user_id nullable for guest submissions
            $table->unsignedInteger('user_id')->nullable()->change();
            
            // Add fields for guest submissions
            $table->string('full_name', 255)->nullable()->after('user_id');
            $table->string('email', 255)->nullable()->after('full_name');
            $table->string('phone', 20)->nullable()->after('email');
            
            // Add is_read flag for admin notifications
            $table->boolean('is_read')->default(false)->after('rejection_reason');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('teacher_requests', function (Blueprint $table) {
            $table->dropColumn(['full_name', 'email', 'phone', 'is_read']);
        });
    }
};
