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
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')
          ->constrained('users')
          ->cascadeOnDelete();

    $table->foreignId('property_id')
          ->constrained('properties')
          ->cascadeOnDelete();

          $table->foreignId('booking_id')
          ->constrained('bookings')
          ->cascadeOnDelete();

    $table->unsignedTinyInteger('rating');
    $table->text(column: 'comment')->nullable();
            $table->timestamps();

           $table->unique(['user_id', 'property_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reviews');
    }
};
