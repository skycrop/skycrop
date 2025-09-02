<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Cviebrock\EloquentSluggable\Sluggable;
use App\Traits\QueryBuilderTrait;

class Collection extends Model
{
    use SoftDeletes;
    use QueryBuilderTrait;
    use Sluggable;


    protected $fillable = ['name', 'slug', 'parent_id', 'type_id' ,'level', ];

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

    public function parent()
    {
        return $this->belongsTo(Collection::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(Collection::class, 'parent_id');
    }

    public function childrenType()
    {
        return $this->hasMany(Collection::class, 'parent_id')->where('level', 3);
    }

    public function subCollection()
    {
        return $this->hasMany(Collection::class, 'type_id')->where('level', 2);
    }

    public function products()
    {
        return $this->belongsToMany(Product::class, 'collection_product');
    }
}
