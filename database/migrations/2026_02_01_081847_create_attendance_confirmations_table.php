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
        Schema::create('attendance_confirmations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('meeting_enrollment_id')->constrained()->onDelete('cascade');
            $table->integer('confirmation_number'); // 1-6 for each 10-min interval
            $table->dateTime('prompted_at');
            $table->dateTime('responded_at')->nullable();
            $table->boolean('is_confirmed')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendance_confirmations');
    }
};
