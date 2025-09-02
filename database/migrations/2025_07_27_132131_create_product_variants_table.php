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
        Schema::create('product_variants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained('products')->onDelete('cascade');

            // Variant Info
            $table->string('variant_name');           // e.g. Single Pack, Multi Pack            
            $table->string('sku')->unique()->nullable();

            // Size & Quantity            
            $table->string('pack_description')->nullable(); // e.g., 3500 seeds × 2

            // Pricing
            $table->decimal('mrp', 10, 2);
            $table->decimal('selling_price', 10, 2);

            // Stock
            $table->unsignedInteger('stock_quantity')->default(0);
            $table->boolean('is_available')->default(true);
            $table->boolean('is_default')->default(false);
            
            $table->softDeletes();
            $table->timestamps();

            // Indexes
            $table->index('product_id');
            $table->index('is_available');
            $table->index('is_default');
            $table->index('selling_price');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_variants');
    }
};
