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
        Schema::create('group_chat_reactions', function (Blueprint $table) {
            $table->increments('reaction_id');
            $table->unsignedInteger('message_id');
            $table->unsignedInteger('user_id');
            $table->string('emoji', 10); // Store emoji as string (e.g., '👍', '❤️', '😂')
            $table->timestamps();

            $table->foreign('message_id')->references('message_id')->on('group_chat_messages')->onDelete('cascade');
            $table->foreign('user_id')->references('user_id')->on('users')->onDelete('cascade');
            // Prevent duplicate reactions from same user on same message
            $table->unique(['message_id', 'user_id', 'emoji']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('group_chat_reactions');
    }
};
