<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Collection;

class CollectionService
{
    public function __construct(private readonly PermissionService $permissionService)
    {
    }

    public function getCollections(array $filters = [])
    {
        // Use the QueryBuilderTrait methods directly from the User model
        $query = Collection::applyFilters($filters);

        return $query->paginateData([
            'per_page' => $filters['per_page'] ?? config('settings.default_pagination') ?? 10,
        ]);
    }

    public function getParentCollections()
    {        
        $query = Collection::where('level', 1)->get();

        return $query;
    }
    
    public function getCollectionType()
    {        
        $query = Collection::where('level', 3)->get();

        return $query;
    }

    public function getSubCollections($parentId)
    {        
        $query = Collection::where('level', 2)->where('parent_id',$parentId)->get();

        return $query;
    }

    public function getAllCollections()
    {        
        $query = Collection::get();

        return $query;
    }

    public function getCollectionBySlug(string $slug): ?Collection
    {
        return Collection::where('slug', $slug)->first();
    }
}