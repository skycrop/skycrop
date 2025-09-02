<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderProduct extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'product_id',
        'variant_id',
        'vendor_id',
        'product_name',
        'variant_name',
        'sku',
        'price',
        'quantity',
        'total',
        'status',
        'referral_code',
        'product_snapshot',
    ];

    protected $casts = [
        'product_snapshot' => 'array',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
