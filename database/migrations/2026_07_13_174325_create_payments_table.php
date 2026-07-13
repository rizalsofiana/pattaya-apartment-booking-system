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
            $table->id();
            $table->foreignId('booking_id')->constrained()->cascadeOnDelete();
            $table->string('gateway'); // misal: 'omise', 'stripe'
            $table->string('transaction_id')->unique();
            $table->string('payment_method'); // misal: 'credit_card', 'promptpay'
            $table->string('currency')->default('THB');
            $table->decimal('amount', 10, 2);
            $table->enum('status', ['pending', 'success', 'failed', 'refunded'])->default('pending');
            $table->dateTime('paid_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
