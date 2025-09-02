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
        Schema::create('collection_product', function (Blueprint $table) {
            $table->unsignedBigInteger('product_id');
            $table->unsignedBigInteger('collection_id');

            // Set both columns as composite primary key
            $table->primary(['product_id', 'collection_id']);

            // Foreign key constraints (optional but recommended)
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
            $table->foreign('collection_id')->references('id')->on('collections')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('collection_product');
    }
};
