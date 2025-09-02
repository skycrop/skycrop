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
        
        Schema::create('addresses', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('farmer_id'); 
            $table->string('full_name');
            $table->string('address_line_1');
            $table->string('address_line_2')->nullable();
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
            $table->string('city');
            $table->string('state');
            $table->string('zipcode');
            $table->string('phone');
            $table->boolean('is_default')->default(false);
            $table->softDeletes(); // Adds deleted_at column
            $table->timestamps();

            $table->foreign('farmer_id')
                ->references('id')->on('farmers')
                ->onDelete('cascade');

        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('addresses');
    }
};
