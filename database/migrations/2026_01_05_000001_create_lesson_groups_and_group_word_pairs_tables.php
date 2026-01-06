<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {


        Schema::create('group_word_pairs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('lesson_group_id');
            $table->string('word');
            $table->string('definition');
            $table->timestamps();
            $table->foreign('lesson_group_id')->references('id')->on('lesson_groups')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('group_word_pairs');

    }
};
