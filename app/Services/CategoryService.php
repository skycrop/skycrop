<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Category;

class CategoryService
{
   
    public function getCategories(array $filters = [])
    {
        // Use the QueryBuilderTrait methods directly from the User model
        $query = Category::applyFilters($filters);

        return $query->paginateData([
            'per_page' => $filters['per_page'] ?? config('settings.default_pagination') ?? 10,
        ]);
    }

    public function getParentCategories()
    {        
        $query = Category::where('level', 0)->get();

        return $query;
    }
    
    public function getCategoryType()
    {        
        $query = Category::where('level', 2)->get();

        return $query;
    }

    public function getSubCategories($parentId)
    {        
        $query = Category::where('level', 1)->where('parent_id',$parentId)->get();

        return $query;
    }
}