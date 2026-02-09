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
        Schema::create('payments', function (Blueprint $table) {
            $table->id('payment_id');
            $table->unsignedBigInteger('student_id');
            $table->string('transaction_reference', 191)->unique();
            $table->enum('payment_method', ['paypal', 'credit_card']);
            $table->enum('payment_status', ['pending', 'successful', 'rejected', 'refunded'])->default('pending');
            $table->decimal('amount', 10, 2);
            $table->string('currency', 3)->default('USD');
            $table->enum('plan_type', ['premium_monthly', 'premium_yearly', 'premium_lifetime'])->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->timestamps();

            $table->foreign('student_id')->references('student_id')->on('students');
        });
        // Force utf8mb4 charset and collation for this table
        \DB::statement("ALTER TABLE payments CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
