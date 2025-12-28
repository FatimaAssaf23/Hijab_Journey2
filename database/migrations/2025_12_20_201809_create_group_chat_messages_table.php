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
        Schema::create('group_chat_messages', function (Blueprint $table) {
            $table->increments('message_id');
            $table->unsignedInteger('class_id');
            $table->unsignedInteger('sender_id');
            $table->text('content');
            $table->dateTime('sent_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->boolean('is_deleted')->default(false);
            $table->timestamps();

            $table->foreign('class_id')->references('class_id')->on('student_classes');
            $table->foreign('sender_id')->references('user_id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('group_chat_messages');
    }
};
