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
        Schema::create('class_level', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('class_id');
            $table->unsignedBigInteger('level_id');
            $table->timestamps();

            $table->foreign('class_id')->references('class_id')->on('student_classes')->onDelete('cascade');
            $table->foreign('level_id')->references('level_id')->on('levels')->onDelete('cascade');
            $table->unique(['class_id', 'level_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('class_level');
    }
};
