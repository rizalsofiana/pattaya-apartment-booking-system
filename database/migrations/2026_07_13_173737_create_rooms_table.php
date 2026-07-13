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
        Schema::create('rooms', function (Blueprint $table) {
            $table->id();
            $table->foreignId('property_id')->constrained()->cascadeOnDelete();
            $table->json('name');
            $table->string('slug')->unique();
            $table->json('description')->nullable();
            $table->decimal('base_price', 10, 2);
            $table->integer('capacity_adults');
            $table->integer('capacity_children')->default(0);
            $table->integer('room_size')->nullable(); // Dalam meter persegi
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rooms');
    }
};
