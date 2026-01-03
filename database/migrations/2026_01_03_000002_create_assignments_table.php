<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() {
        // Schema::create('assignments', function (Blueprint $table) {
        //     $table->id();
        //     $table->unsignedBigInteger('teacher_id');
        //     $table->unsignedBigInteger('level_id');
        //     $table->string('title');
        //     $table->text('description')->nullable();
        //     $table->string('file_path');
        //     $table->timestamps();
        //     $table->foreign('teacher_id')->references('user_id')->on('users')->onDelete('cascade');
        // });
    }
    public function down() {
        // Schema::dropIfExists('assignments');
    }
};
