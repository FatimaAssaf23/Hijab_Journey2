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
        Schema::create('comments', function (Blueprint $table) {
            $table->increments('comment_id');
            $table->unsignedInteger('lesson_id');
            $table->unsignedInteger('user_id');
            $table->unsignedInteger('parent_comment_id')->nullable();
            $table->text('comment_text');
            $table->boolean('is_private')->default(false);
            $table->boolean('is_deleted')->default(false);
            $table->timestamps();

            $table->foreign('lesson_id')->references('lesson_id')->on('lessons');
            $table->foreign('user_id')->references('user_id')->on('users');
            $table->foreign('parent_comment_id')->references('comment_id')->on('comments');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('comments');
    }
};
