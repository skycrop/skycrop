<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Cviebrock\EloquentSluggable\Sluggable;

use App\Traits\QueryBuilderTrait;

class Product extends Model
{
    use SoftDeletes;
    use QueryBuilderTrait;
    use Sluggable;


    protected $fillable = [
        'name',
        'sku',
        'slug',
        'description',
        'images',
        'cover_image',
        'category_id',
        'subcategory_id',
        'brand_id',
        'crop_id',
        'seed_type',
        'suitable_season',
        'suitable_soil_types',
        'suitable_land_types',
        'suitable_water_types',
        'soil_ph_min',
        'soil_ph_max',
        'water_ph_min',
        'water_ph_max',
        'video_urls',
        'is_active',
        'meta_description',
        'tags',
        'country_of_origin',
        'average_rating',
        'total_reviews',
    ];

    protected $casts = [
        'images'                => 'array',
        'suitable_season'       => 'array',
        'suitable_soil_types'   => 'array',
        'suitable_land_types'   => 'array',
        'suitable_water_types'  => 'array',
        'add_description'       => 'array',
        'video_urls'            => 'array',
        'tags'                  => 'array',
        'is_active'             => 'boolean',
        'average_rating'        => 'float',
    ];

    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'name',  // or any attribute you want to create slug from
                'unique' => true,    // ensure slug is unique
            ]
        ];
    }

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function subcategory()
    {
        return $this->belongsTo(Category::class, 'subcategory_id');
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    public function variants()
    {
        return $this->hasMany(ProductVariant::class);
    }

    public function crop()
    {
        return $this->belongsTo(Crop::class);
    }
    
    public function defaultVariant()
    {
        return $this->hasOne(ProductVariant::class)->where('is_default', true);
    }

    // Accessors / Helpers (Optional)

    public function isAvailable()
    {
        return $this->is_active && $this->variants()->where('is_available', true)->exists();
    }

    public function collections()
    {
        return $this->belongsToMany(Collection::class, 'collection_product');
    }
    
    public function tagsData()
    {
        return $this->belongsToMany(Tag::class, 'product_tag');
    }

    public function ratings()
    {
        return $this->hasMany(ProductRating::class);
    }

}
