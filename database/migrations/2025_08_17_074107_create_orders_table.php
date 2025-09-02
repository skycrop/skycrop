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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_number')->unique(); 
            $table->unsignedBigInteger('farmer_id'); 

            $table->decimal('subtotal', 10, 2)->default(0);
            $table->decimal('discount', 10, 2)->default(0);
            $table->decimal('tax', 10, 2)->default(0);
            $table->decimal('shipping_cost', 10, 2)->default(0);
            $table->decimal('grand_total', 10, 2)->default(0);

            $table->enum('payment_method', ['cod', 'card', 'upi', 'wallet', 'razorpay'])->default('cod');
            $table->enum('payment_status', ['pending', 'paid', 'failed', 'refunded'])->default('pending');
            $table->string('payment_id')->nullable();
            
            $table->string('discount_code')->nullable();
            $table->enum('status', [
                'pending', 'processing', 'shipped', 'delivered', 'cancelled', 'returned'
            ])->default('pending');
            
             $table->string('shipping_name');
            $table->string('shipping_phone');
            $table->text('shipping_address');
            $table->string('shipping_city');
            $table->string('shipping_state');
            $table->string('shipping_zip');
            $table->string('shipping_country')->default('India');

            
            $table->timestamps();

            $table->foreign('farmer_id')->references('id')->on('farmers')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
