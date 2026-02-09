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
        Schema::create('lessons', function (Blueprint $table) {
            $table->id('lesson_id');
            $table->unsignedBigInteger('level_id');
            $table->unsignedInteger('teacher_id')->nullable();
            $table->unsignedInteger('uploaded_by_admin_id')->nullable();
            $table->string('title', 255);
            $table->text('description')->nullable();
            $table->string('content_url', 500)->nullable();
            $table->integer('duration_minutes')->nullable();
            $table->integer('lesson_order');
            $table->boolean('is_visible')->default(true);
            $table->dateTime('upload_date')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamps();

            $table->foreign('level_id')->references('level_id')->on('levels');
            $table->foreign('teacher_id')->references('user_id')->on('users');
            $table->foreign('uploaded_by_admin_id')->references('user_id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lessons');
    }
};
