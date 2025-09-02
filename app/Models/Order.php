<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\QueryBuilderTrait;

class Order extends Model
{
    use HasFactory;
    use QueryBuilderTrait;

    protected $fillable = [
        'order_number',
        'farmer_id',
        'subtotal',
        'discount',
        'tax',
        'shipping_cost',
        'grand_total',
        'payment_method',
        'payment_status',
        'payment_id',
        'discount_code',
        'status',
        'shipping_name',
        'shipping_phone',
        'shipping_address',
        'shipping_city',
        'shipping_state',
        'shipping_zip',
        'shipping_country',

    ];

    protected function getSearchableColumns(): array
    {
        return ['order_number'];
    }

    public function farmer()
    {
        return $this->belongsTo(Farmer::class);
    }

    public function products()
    {
        return $this->hasMany(OrderProduct::class);
    }

    public function ratings()
    {
        return $this->hasMany(ProductRating::class);
    }
}
