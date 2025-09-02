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
        Schema::create('products', function (Blueprint $table) {
            $table->id();

            // Basic Info
            $table->string('name');            
            $table->string('sku')->unique()->nullable();
            $table->string('slug')->unique()->nullable();
            
            $table->text('description')->nullable();
            $table->json('images')->nullable(); 
            $table->string('cover_image')->nullable(); 

            // Categorization
            $table->foreignId('category_id')->constrained('categories')->onDelete('cascade');
            $table->foreignId('subcategory_id')->constrained('categories')->onDelete('cascade');
            $table->foreignId('brand_id')->constrained('brands')->nullable()->onDelete('cascade');
            $table->foreignId('crop_id')->constrained('crops')->nullable()->onDelete('cascade');

            // Seed Type
            $table->string('seed_type')->nullable(); 

             // Soil/Land/Water suitability
            $table->json('suitable_season')->nullable();
            $table->json('suitable_soil_types')->nullable();
            $table->json('suitable_land_types')->nullable();  // [Nahri, Birani]
            $table->json('suitable_water_types')->nullable(); // [Canal, Borewell]

             // pH Ranges
            $table->decimal('soil_ph_min', 3, 1)->nullable();   
            $table->decimal('soil_ph_max', 3, 1)->nullable();
            $table->decimal('water_ph_min', 3, 1)->nullable();
            $table->decimal('water_ph_max', 3, 1)->nullable();
            
            $table->json('video_urls')->nullable(); // { "benefit": "url", "result": "url", "testimonial": "url" }

            // Status and SEO
            $table->boolean('is_active')->default(true);
            $table->text('meta_description')->nullable();
            $table->json('tags')->nullable();
            $table->string('country_of_origin')->nullable();

            // Ratings
            $table->decimal('average_rating', 2, 1)->default(0);
            $table->unsignedInteger('total_reviews')->default(0);



            $table->softDeletes();
            $table->timestamps();

            // Indexes
            $table->index(['category_id', 'subcategory_id']);
            $table->index('is_active');
            $table->index('average_rating');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
