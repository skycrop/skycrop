<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductRating extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'farmer_id',
        'order_id',
        'rating',
        'review'
    ];

    // Relationships
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function farmer()
    {
        return $this->belongsTo(Farmer::class);
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
