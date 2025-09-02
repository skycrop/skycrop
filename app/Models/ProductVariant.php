<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\QueryBuilderTrait;

class ProductVariant extends Model
{
    use SoftDeletes;
    use QueryBuilderTrait;

    protected $fillable = [
        'product_id',
        'variant_name',
        'sku',
        'pack_description',
        'mrp',
        'selling_price',
        'stock_quantity',
        'is_available',
        'is_default',
    ];

    protected $casts = [
        'is_available' => 'boolean',
        'is_default' => 'boolean',
        'mrp' => 'float',
        'selling_price' => 'float',
        'stock_quantity' => 'integer',
    ];

    // Relationships

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    // Accessors (Optional)

    public function getDiscountPercentageAttribute()
    {
        if ($this->mrp > 0 && $this->selling_price < $this->mrp) {
            return round((($this->mrp - $this->selling_price) / $this->mrp) * 100);
        }
        return 0;
    }

    public function getSavingsAmountAttribute()
    {
        return max(0, $this->mrp - $this->selling_price);
    }
}
