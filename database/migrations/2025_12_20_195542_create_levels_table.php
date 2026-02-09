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
        Schema::create('levels', function (Blueprint $table) {
            $table->id('level_id');
            $table->unsignedInteger('class_id');
            $table->string('level_name', 255);
            $table->integer('level_number');
            $table->text('description')->nullable();
            $table->unsignedBigInteger('prerequisite_level_id')->nullable();
            $table->boolean('is_locked_by_default')->default(true);
            $table->timestamps();

            $table->foreign('class_id')->references('class_id')->on('student_classes');
            $table->foreign('prerequisite_level_id')->references('level_id')->on('levels');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('levels');
    }
};
