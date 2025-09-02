<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FarmField extends Model
{
    use HasFactory;

    protected $fillable = [
        'farm_id',
        'land_area',
        'land_unit',
        'land_type',
        'soil_type',
        'soil_ph',
        'water_type',
        'water_ph',
        'crop_season',
        'crop_name',
        'sowing_date',
        'harvest_date'
    ];

    // Relationship to Farm
    public function farm()
    {
        return $this->belongsTo(Farm::class);
    }
}
