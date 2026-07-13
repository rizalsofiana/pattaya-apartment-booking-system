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
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->string('booking_code')->unique();
            $table->foreignId('room_id')->constrained()->cascadeOnDelete();
            $table->date('check_in')->index();
            $table->date('check_out')->index();
            $table->string('guest_first_name');
            $table->string('guest_last_name');
            $table->string('guest_email');
            $table->string('guest_phone')->nullable();
            $table->integer('adult_count');
            $table->integer('child_count')->default(0);
            $table->decimal('total_amount', 10, 2);
            $table->foreignId('discount_id')->nullable()->constrained('discounts')->nullOnDelete();
            $table->enum('status', ['pending', 'paid', 'cancelled', 'completed'])->default('pending');
            $table->text('special_requests')->nullable();
            $table->timestamps();

            // Index gabungan untuk performa query ketersediaan kamar
            $table->index(['room_id', 'check_in', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
