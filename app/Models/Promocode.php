<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\QueryBuilderTrait;

class Promocode extends Model
{
    use QueryBuilderTrait;

    protected $fillable = ['code', 'description', 'discount_amount', 'discount_percent', 'discount_type', 'per_person_usage', 'max_discount_amount', 'min_cart_amount', 'start_date', 'end_date', 'is_active'];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
    ];


    protected function getSearchableColumns(): array
    {
        return ['code'];
    }
}
