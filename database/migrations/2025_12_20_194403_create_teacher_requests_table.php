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
        Schema::create('teacher_requests', function (Blueprint $table) {
            $table->increments('request_id');
            $table->unsignedInteger('user_id')->unique();
            $table->unsignedInteger('approved_by_admin_id')->nullable()->index();
            $table->string('language', 50)->nullable();
            $table->string('specialization', 255);
            $table->integer('experience_years');
            $table->string('university_major', 255);
            $table->text('courses_done')->nullable();
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->dateTime('request_date')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->dateTime('processed_date')->nullable();
            $table->text('rejection_reason')->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('user_id')->on('users');
            $table->foreign('approved_by_admin_id')->references('user_id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('teacher_requests');
    }
};
