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
        Schema::create('teacher_substitutions', function (Blueprint $table) {
            $table->increments('substitution_id');
            $table->unsignedInteger('class_id');
            $table->unsignedInteger('original_teacher_id');
            $table->unsignedInteger('substitute_teacher_id');
            $table->unsignedInteger('requested_by_admin_id');
            $table->text('reason');
            $table->timestamp('start_date');
            $table->timestamp('end_date')->nullable();
            $table->enum('status', ['active', 'completed', 'cancelled'])->default('active');
            $table->timestamps();

            $table->foreign('class_id')->references('class_id')->on('student_classes');
            $table->foreign('original_teacher_id')->references('user_id')->on('users');
            $table->foreign('substitute_teacher_id')->references('user_id')->on('users');
            $table->foreign('requested_by_admin_id')->references('user_id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('teacher_substitutions');
    }
};
