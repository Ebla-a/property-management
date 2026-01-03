<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('properties', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('title');
            $table->foreignId('property_type_id')->nullable()->constrained('property_types')->cascadeOnDelete();
            $table->string('city')->nullable();
            $table->string('neighborhood')->nullable();
            $table->string('address')->nullable();
            $table->unsignedInteger('rooms')->default(1);
            $table->decimal('area', 10, 2)->nullable()->comment('Area in square meters (or units)');
            $table->decimal('price', 12, 2)->default(0);
            $table->enum('status', ['available', 'booked', 'rented', 'hidden'])->default('available');
            $table->text('description')->nullable();
            $table->boolean('is_furnished')->default(false);
            $table->timestamps();
            $table->unsignedInteger('rating_count')->default(0);
            $table->unsignedInteger('rating_sum')->default(0);
            $table->decimal('rating_avg', 3, 2)->default(0);

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('properties');
    }
};
