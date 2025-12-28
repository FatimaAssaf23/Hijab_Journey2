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
        Schema::create('student_classes', function (Blueprint $table) {
            $table->increments('class_id');
            $table->string('class_name', 255);
            $table->unsignedInteger('teacher_id');
            $table->integer('capacity')->default(10);
            $table->integer('current_enrollment')->default(0);
            $table->enum('status', ['active', 'full', 'closed'])->default('active');
            $table->text('description')->nullable();
            $table->timestamps();

            $table->foreign('teacher_id')->references('user_id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student_classes');
    }
};
