<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StoreDetail extends Model
{
    use HasFactory;

    protected $table = 'store_details';

    protected $fillable = [
        'user_id',
        'store_name',
        'store_logo',
        'phone',
        'latitude',
        'longitude',
        'store_address',
        'city',
        'state',
        'zipcode',
        'about_store',
        'gst_number',
        'documents',
    ];

    // Relationship to User model
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
