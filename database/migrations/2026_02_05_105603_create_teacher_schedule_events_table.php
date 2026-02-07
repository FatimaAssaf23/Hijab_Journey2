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
        Schema::create('teacher_schedule_events', function (Blueprint $table) {
            $table->id('event_id');
            $table->unsignedInteger('teacher_id');
            $table->string('title');
            $table->text('description')->nullable();
            $table->date('event_date');
            $table->time('event_time')->nullable();
            $table->string('event_type')->default('event'); // event, class, meeting, etc.
            $table->string('color')->default('#F472B6'); // For calendar display
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            
            $table->foreign('teacher_id')->references('user_id')->on('users')->onDelete('cascade');
            $table->index(['teacher_id', 'event_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('teacher_schedule_events');
    }
};
