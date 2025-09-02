<?php

declare(strict_types=1);

namespace App\Http\Requests\Product;

use App\Http\Requests\FormRequest;
use Illuminate\Support\Facades\Auth;

class UpdateProductRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->checkAuthorization(Auth::user(), ['product.edit']);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $productId = $this->route('product');

        return ld_apply_filters('product.update.validation.rules', [
            'name' => 'required|string|max:255',
            'sku' => 'required|string|max:255|unique:products,sku,' . $productId,
            'brand_id' => 'required|integer|exists:brands,id',
            'category_id' => 'required|integer|exists:categories,id',
            'subcategory_id' => 'required|integer|exists:categories,id',
            'collection_ids' => 'required|array',            
            'tag_ids' => 'required|array',            
            'crop_id' => 'required|integer|exists:crops,id',

            'seed_type' => 'required|string|max:100',
            'suitable_season' => 'required|string|max:100',
            'suitable_soil_types' => 'required|string|max:255',
            'suitable_land_types' => 'required|string|max:255',
            'suitable_water_types' => 'required|string|max:255',

            'soil_ph_min' => 'required|numeric|min:0|max:14',
            'soil_ph_max' => 'required|numeric|min:0|max:14',
            'water_ph_min' => 'required|numeric|min:0|max:14',
            'water_ph_max' => 'required|numeric|min:0|max:14',

            'video_urls' => 'nullable|array',
            'video_urls.*' => 'nullable|string',

            'is_active' => 'required',
            'country_of_origin' => 'nullable|string|max:255',
            'description' => 'required|string',

            'cover_image' => 'nullable|image|max:2048',
            'images' => 'nullable|array',
            'images.*' => 'image|max:2048',

            // Variants
            'variant_name' => ['required', 'array'],
            'variant_name.*' => ['required', 'string', 'max:255'],

            'variant_sku' => ['nullable', 'array'],
            'variant_sku.*' => ['nullable', 'string', 'max:255'],

            'variant_item_price' => ['required', 'array'],
            'variant_item_price.*' => ['required', 'numeric', 'gte:0'],
            
            'variant_pack_description' => ['required', 'array'],
            'variant_pack_description.*' => ['required', 'string', 'max:255'],

            'variant_selling_price' => ['required', 'array'],
            'variant_selling_price.*' => ['required', 'numeric', 'gte:0'],

            'variant_stock_quantity' => ['nullable', 'array'],
            'variant_stock_quantity.*' => ['nullable', 'integer', 'min:0'],

            'variant_is_available' => ['nullable', 'array'],            
            'variant_is_default' => ['nullable', 'array'],    
            
        ], $productId);
    }

    public function messages(): array
    {
        return [
            'variant_pack_description.required' => 'At least one variant name is required.',
            'variant_pack_description.array'    => 'Variant name must be an array.',
            'variant_pack_description.*.required' => 'Each variant name is required.',
            'variant_pack_description.*.string'   => 'Each variant name must be a valid string.',            

            // Variant Name
            'variant_name.required' => 'At least one variant name is required.',
            'variant_name.array'    => 'Variant name must be an array.',
            'variant_name.*.required' => 'Each variant name is required.',
            'variant_name.*.string'   => 'Each variant name must be a valid string.',            

            // Variant SKU
            'variant_sku.array'    => 'Variant SKU must be an array.',
            'variant_sku.*.string' => 'Each variant SKU must be a valid string.',            

            // Variant Item Price
            'variant_item_price.required' => 'Item price is required for each variant.',
            'variant_item_price.array'    => 'Item price must be an array.',
            'variant_item_price.*.required' => 'Each item price is required.',
            'variant_item_price.*.numeric'  => 'Each item price must be a valid number.',
            'variant_item_price.*.gte'      => 'Each item price must be at least 1.',

            // Variant Selling Price
            'variant_selling_price.required' => 'Selling price is required for each variant.',
            'variant_selling_price.array'    => 'Selling price must be an array.',
            'variant_selling_price.*.required' => 'Each selling price is required.',
            'variant_selling_price.*.numeric'  => 'Each selling price must be a valid number.',
            'variant_selling_price.*.gte'      => 'Each selling price must be at least 1.',

            // Variant Stock Quantity
            'variant_stock_quantity.array'    => 'Stock quantity must be an array.',
            'variant_stock_quantity.*.integer' => 'Each stock quantity must be a valid integer.',
            'variant_stock_quantity.*.min'     => 'Each stock quantity must be at least 1.',

            // Variant Availability
            'variant_is_available.array' => 'Availability must be an array.',

            // Variant Default
            'variant_is_default.array'   => 'Default selection must be an array.',
        ];
    }
}
