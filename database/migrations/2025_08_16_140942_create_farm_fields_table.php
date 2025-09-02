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
        Schema::create('farm_fields', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('farm_id'); // Relate to farms table
            $table->string('land_area');
            $table->string('land_unit');
            $table->string('land_type');
            $table->string('soil_type');
            $table->decimal('soil_ph', 4, 2)->nullable();
            $table->string('water_type');
            $table->decimal('water_ph', 4, 2)->nullable();
            $table->string('crop_season');
            $table->string('crop_name')->nullable();
            $table->date('sowing_date')->nullable();
            $table->date('harvest_date')->nullable();

            $table->foreign('farm_id')->references('id')->on('farms')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('farm_fields');
    }
};
