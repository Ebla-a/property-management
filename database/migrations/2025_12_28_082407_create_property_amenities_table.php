<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('property_amenities', function (Blueprint $table) {
            $table->foreignId('property_id')->constrained('properties')->cascadeOnDelete();
            $table->foreignId('amenity_id')->constrained('amenities')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('property_amenities');
    }
};
