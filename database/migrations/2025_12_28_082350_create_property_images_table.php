<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('property_images', function (Blueprint $table) {
            $table->id();
            $table->foreignId('property_id')->constrained('properties')->cascadeOnDelete();
            $table->string('path');
            $table->boolean('is_main')->default(false);
            $table->string('alt')->nullable();
            $table->timestamps();
        });

        Schema::table('property_images', function (Blueprint $table) {
            $table->index('is_main');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('property_images');
    }
};
