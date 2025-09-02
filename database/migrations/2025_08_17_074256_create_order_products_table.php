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
        Schema::create('order_products', function (Blueprint $table) {            
            $table->id();
            $table->unsignedBigInteger('order_id'); 
            $table->unsignedBigInteger('product_id');
            $table->unsignedBigInteger('variant_id')->nullable();
            $table->unsignedBigInteger('vendor_id')->nullable(); 

            $table->string('product_name');
            $table->string('variant_name')->nullable();
            $table->string('sku')->nullable();
            $table->decimal('price', 10, 2);
            $table->integer('quantity')->default(1);
            $table->decimal('total', 10, 2); // price * qty - discount

            $table->enum('status', [
                'pending', 'processing', 'shipped', 'delivered', 'cancelled', 'returned'
            ])->default('pending');


            $table->string('referral_code')->nullable();             
            
            $table->json('product_snapshot');
            $table->timestamps();

            $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_products');
    }
};
