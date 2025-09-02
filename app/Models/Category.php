<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Cviebrock\EloquentSluggable\Sluggable;
use App\Traits\QueryBuilderTrait;

class Category extends Model
{
    use SoftDeletes;
    use QueryBuilderTrait;
    use Sluggable;


    protected $fillable = ['name', 'slug', 'parent_id', 'level', 'photo'];

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
        return $this->belongsTo(Category::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(Category::class, 'parent_id');
    }
}
