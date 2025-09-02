<?php

declare(strict_types=1);

namespace App\Http\Controllers;
use App\Models\Category;
use App\Services\CategoryService;
use App\Services\BrandService;
use App\Services\CollectionService;
use App\Models\Product;
use App\Models\OrderProduct;
use App\Models\Promocode;
use Illuminate\Http\Request;
use Carbon\Carbon;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(
        private readonly CategoryService $categoryService,
        private readonly BrandService $brandService,
        private readonly CollectionService $collectionService
    )
    {
        
    }

    // public function redirectAdmin()
    // {
    //     return redirect()->route('admin.dashboard');
    // }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {  
        $today = Carbon::today();        
        $categories = $this->categoryService->getParentCategories();
        $promocode = Promocode::where('is_active', 1)
            ->whereDate('start_date', '<=', $today)
            ->whereDate('end_date', '>=', $today)
            ->orderBy('created_at', 'desc')
            ->first();

        $mostDiscountedProducts = Product::with('defaultVariant')
            ->whereHas('defaultVariant', function ($query) {
                $query->whereNotNull('selling_price')
                      ->whereColumn('selling_price', '<', 'mrp');
            })
            ->where('is_active', 1)            
            ->take(8)
            ->get();


        $productsIds = OrderProduct::select('product_id')
            ->groupBy('product_id')
            ->orderByRaw('SUM(quantity) DESC')
            ->pluck('product_id')
            ->toArray();

        $bestSellingProducts = Product::where('is_active', 1)            
            ->whereIn('id', $productsIds)
            ->take(8)
            ->get();

        $categorySeeds = Category::where('slug', 'seeds')->first();

        $seedsProducts = Product::with('defaultVariant')
            ->whereHas('category', function ($query) use ($categorySeeds) {
                $query->where('id', $categorySeeds->id);
            })
            ->where('is_active', 1)
            ->take(8)
            ->get();

        $seasonalProducts = Product::with('defaultVariant')
            ->where('is_active', 1)
            ->whereJsonContains('suitable_season', 'Khrif')
            ->take(8)
            ->get();
        

        return view('frontend.index', [            
            'categories' => $categories,
            'promocode' => $promocode,
            'mostDiscountedProducts' => $mostDiscountedProducts,
            'bestSellingProducts' => $bestSellingProducts,
            'seedsProducts' => $seedsProducts,
            'seasonalProducts' => $seasonalProducts,
        ]);
    }


    public function getCollectionProduct($slug)
    {   
        $collection = $this->collectionService->getCollectionBySlug($slug);
        if (!$collection) {
            abort(404);
        }                
        $categories = $this->categoryService->getParentCategories();        
        $brands = $this->brandService->getBrandsDropdown(); 
        
        $collectionProducts = Product::with('defaultVariant')
            ->whereHas('collections', function ($query) use($collection) {
                $query->where('collection_id', $collection->id);
            })
            ->where('is_active', 1)
            ->paginate(12);

        return view('frontend.collection-list', [            
            'products' => $collectionProducts,
            'categories' => $categories,
            'brands' => $brands,
            'breadcrumbs' => [
                'title' => __(':name', ['name' => $collection->name]),
            ],
        ]);
    }
    
    public function searchProduct(Request $request)
    {   
        $keyword = $request->keyword;
        $category = $request->category;
        $categories = $this->categoryService->getParentCategories();        
        $brands = $this->brandService->getBrandsDropdown(); 

        $productsQuery = Product::where('is_active', 1);

        if ($keyword) {
            $productsQuery->where(function ($query) use ($keyword) {
                $query->where('name', 'LIKE', "%{$keyword}%")
                    ->orWhereHas('category', fn($q) => $q->where('name', 'LIKE', "%{$keyword}%"))
                    ->orWhereHas('subcategory', fn($q) => $q->where('name', 'LIKE', "%{$keyword}%"))
                    ->orWhereHas('brand', fn($q) => $q->where('name', 'LIKE', "%{$keyword}%"))
                    ->orWhereHas('collections', fn($q) => $q->where('name', 'LIKE', "%{$keyword}%"))
                    ->orWhereHas('tagsData', fn($q) => $q->where('name', 'LIKE', "%{$keyword}%"));
            });
        } elseif ($category) {
            $productsQuery->whereHas('category', fn($q) => $q->where('name', 'LIKE', "%{$category}%"));
        }

        $products = $productsQuery->paginate(12);

        return view('frontend.collection-list', [            
            'products' => $products,
            'categories' => $categories,
            'brands' => $brands,
            'breadcrumbs' => [
                'title' => __(':name', ['name' => 'Search']),
            ],
        ]);
    }
}
