<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('assignments', function (Blueprint $table) {
            $table->bigIncrements('assignment_id');
            $table->unsignedBigInteger('level_id');
            $table->unsignedInteger('class_id');
            $table->unsignedInteger('teacher_id');
            $table->unsignedInteger('checked_by_admin_id')->nullable();
        
            $table->string('title', 255);
            $table->text('description')->nullable();
            $table->string('pdf_url', 500);
            $table->dateTime('posted_date')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->dateTime('due_date');
            $table->integer('max_score')->default(100);
            $table->enum('approval_status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->timestamps();
            
            $table->foreign('level_id')->references('level_id')->on('levels');
            $table->foreign('class_id')->references('class_id')->on('student_classes');
            $table->foreign('teacher_id')->references('user_id')->on('users');
            $table->foreign('checked_by_admin_id')->references('user_id')->on('users');
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('assignments');
    }
};
