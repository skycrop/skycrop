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
        Schema::create('crops', function (Blueprint $table) {
            $table->id();
            $table->string('crop_name');
            $table->string('slug')->unique()->nullable();
            $table->json('suitable_season')->nullable();
            $table->json('category')->nullable(); // IDs from category table
            $table->json('suitable_soil_types')->nullable();
            $table->json('suitable_land_types')->nullable();
            $table->json('suitable_water_types')->nullable();

            $table->decimal('soil_ph_min', 3, 1)->nullable();
            $table->decimal('soil_ph_max', 3, 1)->nullable();
            $table->decimal('water_ph_min', 3, 1)->nullable();
            $table->decimal('water_ph_max', 3, 1)->nullable();
            $table->softDeletes(); // Adds deleted_at column
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('crops');
    }
};
