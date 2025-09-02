<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Cviebrock\EloquentSluggable\Sluggable;

use App\Traits\QueryBuilderTrait;

class Crop extends Model
{
    use SoftDeletes;
    use QueryBuilderTrait;
    use Sluggable;

    protected $fillable = [
        'crop_name',
        'slug',
        'suitable_season',
        'category',
        'suitable_soil_types',
        'suitable_land_types',
        'suitable_water_types',
        'soil_ph_min',
        'soil_ph_max',
        'water_ph_min',
        'water_ph_max',
    ];

    protected $casts = [        
        'suitable_season'       => 'array',
        'category'              => 'array',
        'suitable_soil_types'   => 'array',
        'suitable_land_types'   => 'array',
        'suitable_water_types'  => 'array',        
    ];

    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'crop_name',  // or any attribute you want to create slug from
                'unique' => true,    // ensure slug is unique
            ]
        ];
    }
}
