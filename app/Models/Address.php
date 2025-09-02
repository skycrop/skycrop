<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use App\Traits\QueryBuilderTrait;

class Address extends Model
{
    use SoftDeletes;
    use QueryBuilderTrait;    

    protected $fillable = [
        'full_name',
        'address_line_1',
        'address_line_2',
        'latitude',
        'longitude',
        'city',
        'state',
        'zipcode',
        'phone',
        'farmer_id',
        'is_default',
    ];      

    public function farmer()
    {
        return $this->belongsTo(Farmer::class);
    }
}
