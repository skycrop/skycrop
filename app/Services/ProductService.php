<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Support\Facades\DB;

class ProductService
{
    public function __construct(private readonly PermissionService $permissionService)
    {
    }

    public function getProducts(array $filters = [])
    {
        // Use the QueryBuilderTrait methods directly from the Product model
        $query = Product::applyFilters($filters);

        return $query->paginateData([
            'per_page' => $filters['per_page'] ?? config('settings.default_pagination') ?? 10,
        ]);
    }

    public function createProduct(array $data, $imagePaths): Product
    {
        $product = Product::create([
            'name' => $data['name'],
            'sku' => $data['sku'],
            'brand_id' => $data['brand_id'],
            'crop_id' => $data['crop_id'],
            'category_id' => $data['category_id'],
            'subcategory_id' => $data['subcategory_id'] ?? null,
            'seed_type' => $data['seed_type'] ?? null,
            'suitable_season' => $data['suitable_season'] ?? null,
            'suitable_soil_types' => $data['suitable_soil_types'] ?? null,
            'suitable_land_types' => $data['suitable_land_types'] ?? null,
            'suitable_water_types' => $data['suitable_water_types'] ?? null,
            'soil_ph_min' => $data['soil_ph_min'] ?? null,
            'soil_ph_max' => $data['soil_ph_max'] ?? null,
            'water_ph_min' => $data['water_ph_min'] ?? null,
            'water_ph_max' => $data['water_ph_max'] ?? null,
            'video_urls' => json_encode($data['video_urls'] ?? []),
            'is_active' => $data['is_active'] ?? false,
            'country_of_origin' => $data['country_of_origin'] ?? null,
            'description' => $data['description'] ?? null,
            'cover_image' => $data['cover_image'] ?? null,
            'images' => json_encode($imagePaths),
        ]);

        return $product;
    }

    public function createProductVariant(array $data, $productId): array
    {
        $variantCount = count($data['variant_name']);

        for ($i = 0; $i < $variantCount; $i++) {
            $productVariant[$i] = ProductVariant::create([
                'product_id' => $productId,
                'variant_name' => $data['variant_name'][$i],
                'sku' => $data['variant_sku'][$i] ?? null,
                'pack_description' => $data['variant_pack_description'][$i] ?? null, // You can enhance this if dynamic
                'mrp' => $data['variant_item_price'][$i],
                'selling_price' => $data['variant_selling_price'][$i],
                'stock_quantity' => $data['variant_stock_quantity'][$i] ?? 0,
                'is_available' => isset($data['variant_is_available'][$i]) ? (bool)$data['variant_is_available'][$i] : true,
                'is_default' => isset($data['variant_is_default'][$i]) ? (bool)$data['variant_is_default'][$i] : false,
            ]);
        }

        return $productVariant;
    }


    public function updateProduct(Product $product,array $data, $imagePaths): Product
    {        
        
        return DB::transaction(function () use ($product, $data, $imagePaths) {
        
            // Main product fields
            $updateData = [
                'name'                 => $data['name'],
                'sku'                  => $data['sku'],
                'brand_id'             => $data['brand_id'],
                'category_id'          => $data['category_id'],
                'subcategory_id'       => $data['subcategory_id'],
                'crop_id'              => $data['crop_id'] ?? null,
                'seed_type'            => $data['seed_type'] ?? null,
                'suitable_season'      => $data['suitable_season'] ?? null,
                'suitable_soil_types'  => $data['suitable_soil_types'] ?? null,
                'suitable_land_types'  => $data['suitable_land_types'] ?? null,
                'suitable_water_types' => $data['suitable_water_types'] ?? null,
                'soil_ph_min'          => $data['soil_ph_min'] ?? null,
                'soil_ph_max'          => $data['soil_ph_max'] ?? null,
                'water_ph_min'         => $data['water_ph_min'] ?? null,
                'water_ph_max'         => $data['water_ph_max'] ?? null,
                'video_urls'           => json_encode($data['video_urls'] ?? []),
                'is_active'            => filter_var($data['is_active'], FILTER_VALIDATE_BOOLEAN),
                'description'          => $data['description'] ?? null,
            ];

            // Image update (if new images given)
            if (!empty($imagePaths)) {
                $updateData['images'] = json_encode($imagePaths);
            }
            
            // Cover Image update (if new cover image given)
            if (isset($data['cover_image']) && $data['cover_image']) {
                $updateData['cover_image'] = $data['cover_image'];
            }

            // Update product
            $product->update($updateData);

            // Sync collections (Many-to-Many)
            if (isset($data['collection_ids'])) {
                $product->collections()->sync(array_map('intval', array_filter($data['collection_ids'])));
            }

            // Sync tags (Many-to-Many)
            if (isset($data['tag_ids'])) {
                $product->tagsData()->sync(array_map('intval', array_filter($data['tag_ids'])));
            }

            // Update Variants
            if (isset($data['variant_name']) && is_array($data['variant_name'])) {
                $product->variants()->forceDelete(); // permanently deletes records

                $this->createProductVariant($data, $product->id);
            }

            return $product->refresh();
        });
    }
}