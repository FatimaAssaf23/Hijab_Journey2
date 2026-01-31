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
        Schema::create('attendance_check_responses', function (Blueprint $table) {
            $table->increments('response_id');
            $table->unsignedInteger('attendance_id');
            $table->unsignedInteger('check_number'); // 1st check, 2nd check, etc.
            $table->enum('response', ['present', 'absent', 'no_response'])->default('no_response');
            $table->dateTime('checked_at');
            $table->timestamps();

            $table->foreign('attendance_id')->references('attendance_id')->on('meeting_attendances')->onDelete('cascade');
            $table->index(['attendance_id', 'check_number']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendance_check_responses');
    }
};
