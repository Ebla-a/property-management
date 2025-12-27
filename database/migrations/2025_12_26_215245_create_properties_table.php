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
        Schema::create('properties', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->foreignId('property_type_id')->constrained()->cascadeOnUpdate()
          ->restrictOnDelete();
           $table->string('city');
           $table->string('neighborhood');
           $table->string('address');
            $table->integer('rooms');
            $table->float('area');
            $table->decimal('price', 10, 2);
            $table->enum('status',['available','rented' ,'sold' ,'pending'])->default('available');
            $table->text('description');
            $table->boolean('is_finished');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('properties');
    }
};
