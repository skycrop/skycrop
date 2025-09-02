<?php

namespace App\Http\Controllers\Backend;

use App\Enums\ActionType;
use App\Http\Controllers\Controller;
use App\Http\Requests\Product\StoreProductRequest;
use App\Http\Requests\Product\UpdateProductRequest;
use App\Models\Crop;
use App\Models\Product;
use App\Models\StoreInventory;
use App\Models\ProductVariant;
use App\Models\Tag;
use App\Services\BrandService;
use App\Services\CategoryService;
use App\Services\CollectionService;
use App\Services\ProductService;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
    public function __construct(
        private readonly BrandService $brandService,        
        private readonly CategoryService $categoryService,        
        private readonly CollectionService $collectionService,
        private readonly ProductService $productService,        
    ) {
    }

    /**
     * Display a listing of the resource.
     */
    public function index(): Renderable
    {
        $this->checkAuthorization(Auth::user(), ['product.view']);

        $filters = [
            'search' => request('search'),                        
            'sort_field' => null,
            'sort_direction' => null,
        ];

        $currentInventory = StoreInventory::where('user_id', auth()->id())
            ->pluck('stock_quantity', 'variant_id')
            ->toArray();

        return view('backend.pages.product.index', [
            'products' => $this->productService->getProducts($filters),
            'currentInventory' => $currentInventory,
            'breadcrumbs' => [
                'title' => __('Products'),
            ],
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): Renderable
    {
        $this->checkAuthorization(Auth::user(), ['product.create']);        
        ld_do_action('product_create_page_before');

        $tags = Tag::get();
        $crops = Crop::get();

        return view('backend.pages.product.create', [                        
            'brands' =>  $this->brandService->getBrandsDropdown([]),
            'category' =>  $this->categoryService->getParentCategories(),
            'collections' =>  $this->collectionService->getAllCollections(),
            'tags' =>  $tags,
            'crops' =>  $crops,
            'seed_type' =>  ['Hybrid', 'Desi', 'GM'],            
            'suitable_season' =>  ['Khrif', 'Rabi', 'Jayad'],            
            'suitable_soil_types' =>  ['Alluvial Soil','Black Soil', 'Red Soil', 'Sandy Soil', 'Laterite Soil', 'Saline Soil', 'Peaty Soil', 'Clay Soil'],            
            'suitable_land_types' =>  ['Nahri', 'Birani'],            
            'suitable_water_types' =>  ['Fresh Water', 'Slightly Saline', 'Moderately Saline', 'Highly Saline'],            
            'status' =>  ['In Active', 'Active'],            
            'breadcrumbs' => [
                'title' => __('New Product'),
                'items' => [
                    [
                        'label' => __('Product'),
                        'url' => route('admin.product.index'),
                    ],
                ],
            ],
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProductRequest $request): RedirectResponse
    {        
        $validated = $request->validated();               
        
        
        // Handle cover image
        if ($request->hasFile('cover_image')) {
            $validated['cover_image'] = $request->file('cover_image')->store('products/cover', 'public');
        }

        // Handle multiple images
        $imagePaths = [];
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $imagePaths[] = $image->store('products/images', 'public');
            }
        }

        $product = $this->productService->createProduct($validated, $imagePaths);
        $productVariant = $this->productService->createProductVariant($validated, $product->id);

        if (!empty($validated['collection_ids'])) {
            $product->collections()->sync(array_filter($validated['collection_ids']));
        }
        
        if (!empty($validated['tag_ids'])) {
            $product->tagsData()->sync(array_filter($validated['tag_ids']));
        }
        
        
        $this->storeActionLog(ActionType::CREATED, ['product' => $product]);
        $this->storeActionLog(ActionType::CREATED, ['product_variant' => json_encode($productVariant)]);

        session()->flash('success', __('Product has been created.'));
        ld_do_action('product_store_after', $validated);

        return redirect()->route('admin.product.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $this->checkAuthorization(Auth::user(), ['product.edit']);

        $product = Product::with(['variants'])->findOrFail($id);        
        $tags = Tag::get();      
        $crops = Crop::get();

        ld_do_action('product_edit_page_before');

        $product = ld_apply_filters('product_edit_page_before', $product);        

        return view('backend.pages.product.edit', [
            'brands' =>  $this->brandService->getBrandsDropdown([]),
            'category' =>  $this->categoryService->getParentCategories(),
            'subcategories' =>  $this->categoryService->getSubCategories($product->category_id),        
            'collections' =>  $this->collectionService->getAllCollections(),
            'crops' =>  $crops,
            'tags' =>  $tags,
            'seed_type' =>  ['Hybrid', 'Desi', 'GM'],            
            'suitable_season' =>  ['Khrif', 'Rabi','Jayad'],            
            'suitable_soil_types' =>  ['Alluvial Soil','Black Soil', 'Red Soil', 'Sandy Soil', 'Laterite Soil', 'Saline Soil', 'Peaty Soil', 'Clay Soil'],            
            'suitable_land_types' =>  ['Nahri', 'Birani'],            
            'suitable_water_types' =>  ['Fresh Water', 'Slightly Saline', 'Moderately Saline', 'Highly Saline'],            
            'status' =>  ['In Active', 'Active'],            
            'product' => $product,
            'breadcrumbs' => [
                'title' => __('Edit Product'),
                'items' => [
                    [
                        'label' => __('Product'),
                        'url' => route('admin.product.index'),
                    ],
                ],
            ],
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProductRequest $request, string $id)
    {
        $validated = $request->validated();     
        
        // Handle cover image
        if ($request->hasFile('cover_image')) {
            $validated['cover_image'] = $request->file('cover_image')->store('products/cover', 'public');
        }

        // Handle multiple images
        $imagePaths = [];
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $imagePaths[] = $image->store('products/images', 'public');
            }
        }

        $product = Product::findOrFail($id);        

        $product = $this->productService->updateProduct($product, $validated, $imagePaths);        
                
        $this->storeActionLog(ActionType::UPDATED, ['product' => $product]);        

        session()->flash('success', __('Product has been updated.'));
        ld_do_action('product_update_after', $validated);

        return redirect()->route('admin.product.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    /**
     * Update the inventory of the specified product.
     */
    public function updateInventory(Request $request, string $id)
    {
        $validated = $request->validate([
            'inventory' => 'required|array',
            'inventory.*' => 'nullable|integer|min:0',
        ]);

        foreach ($validated['inventory'] as $variantId => $quantity) {
            $inventory = StoreInventory::firstOrNew([
                'product_id' => $id,
                'variant_id' => $variantId,
                'user_id' => auth()->id(),
            ]);

            // Only update if quantity is provided, else keep existing
            $inventory->stock_quantity += $quantity ?? 0;
            $inventory->save();
        }

        session()->flash('success', __('Product inventory has been updated.'));
        ld_do_action('product_update_inventory_after', $validated);

        return redirect()->route('admin.product.index');
    }



}
