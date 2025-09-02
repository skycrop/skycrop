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
        Schema::create('carts', function (Blueprint $table) {
            $table->id();

            // store which user (or farmer) the cart belongs to
            $table->unsignedBigInteger('user_id')->nullable(); // or farmer_id
            $table->unsignedBigInteger('product_id');

            // NEW: variant column (FK to product_variants table)
            $table->unsignedBigInteger('variant_id')->nullable();

            $table->integer('quantity')->default(1);
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('farmers')->cascadeOnDelete();
            $table->foreign('product_id')->references('id')->on('products')->cascadeOnDelete();
            $table->foreign('variant_id')->references('id')->on('product_variants')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('carts');
    }
};
