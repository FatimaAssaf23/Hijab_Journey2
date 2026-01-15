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
        Schema::create('matching_pairs', function (Blueprint $table) {
            $table->increments('matching_pair_id');
            $table->unsignedInteger('matching_pairs_game_id');
            $table->text('left_item_text')->nullable();
            $table->string('left_item_image')->nullable();
            $table->text('right_item_text')->nullable();
            $table->string('right_item_image')->nullable();
            $table->integer('order')->default(0);
            $table->timestamps();

            // Foreign key to matching_pairs_games table
            $table->foreign('matching_pairs_game_id')->references('matching_pairs_game_id')->on('matching_pairs_games')->onDelete('cascade');
            
            // Index for ordering
            $table->index(['matching_pairs_game_id', 'order']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('matching_pairs');
    }
};
