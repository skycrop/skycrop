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
        Schema::create('promocodes', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->text('description')->nullable();
            $table->decimal('discount_amount', 8, 2)->nullable();
            $table->decimal('discount_percent', 5, 2)->nullable();

            // New fields
            $table->enum('discount_type', ['fixed', 'percent'])->default('fixed');
            $table->integer('per_person_usage')->default(1);
            $table->decimal('max_discount_amount', 10, 2)->nullable();
            $table->decimal('min_cart_amount', 10, 2)->default(0);
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();

            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('promocodes');
    }
};
