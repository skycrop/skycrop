<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Brand;
use Illuminate\Support\Facades\Hash;

class BrandService
{
    /**
     * Get users with filters
     *
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function getBrands(array $filters = [])
    {
        // Use the QueryBuilderTrait methods directly from the Brand model
        $query = Brand::applyFilters($filters);

        return $query->paginateData([
            'per_page' => $filters['per_page'] ?? config('settings.default_pagination') ?? 10,
        ]);
    }    


    public function getBrandsDropdown()
    {        
        return Brand::get();
        
    }    


    public function getBrandById(int $id): ?Brand
    {
        return Brand::findOrFail($id);
    }
    
}
