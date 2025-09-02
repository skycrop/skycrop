<?php

namespace App\Http\Controllers\Frontend;


use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\OrderProduct;
use App\Services\BrandService;
use App\Services\CategoryService;
use App\Services\CollectionService;
use App\Services\ProductService;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;

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
    public function productDetail($slug): Renderable
    {
        $productDetail = Product::with(['variants', 'category', 'brand', 'collections'])->where('slug', $slug)->firstOrFail();
        $productVariants = ProductVariant::where('product_id', $productDetail->id)->get();
        $relatedProducts = Product::where('category_id', $productDetail->category_id)->where('id', '!=', $productDetail->id)->get();

        $productsIds = OrderProduct::select('product_id')
            ->groupBy('product_id')
            ->orderByRaw('SUM(quantity) DESC')
            ->pluck('product_id')
            ->toArray();

        $bestSellingProducts = Product::where('is_active', 1)            
            ->whereIn('id', $productsIds)
            ->take(8)
            ->get();

        return view('frontend.pages.product-detail', [
            'productDetail' => $productDetail,
            'productVariants' => $productVariants,
            'relatedProducts' => $relatedProducts,
            'bestSellingProducts' => $bestSellingProducts,
            'breadcrumbs' => [
                'title' => $productDetail->name,
                'items' => [
                    [
                        'label' => __('Category'),
                        'url' => route('admin.category.index'),
                    ],
                ],
            ],
        ]);
    }

    
}
