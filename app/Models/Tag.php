<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Cviebrock\EloquentSluggable\Sluggable;
use App\Traits\QueryBuilderTrait;

class Tag extends Model
{
    use QueryBuilderTrait;
    use SoftDeletes;
    use Sluggable;

    protected $fillable = ['name', 'slug'];

    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'name',  // or any attribute you want to create slug from
                'unique' => true,    // ensure slug is unique
            ]
        ];
    }

    protected function getSearchableColumns(): array
    {
        return ['name'];
    }

    public function products()
    {
        return $this->belongsToMany(Product::class, 'product_tag');
    }
}
