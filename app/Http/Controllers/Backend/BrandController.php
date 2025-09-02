<?php

declare(strict_types=1);

namespace App\Http\Controllers\Backend;

use App\Enums\ActionType;
use App\Http\Controllers\Controller;
use App\Http\Requests\Brand\StoreBrandRequest;
use App\Http\Requests\Brand\UpdateBrandRequest;
use App\Models\Brand;
use App\Services\BrandService;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BrandController extends Controller
{
    public function __construct(
        private readonly BrandService $brandService,        
    ) {
    }

    /**
     * Display a listing of the resource.
     */
    public function index(): Renderable
    {
        $this->checkAuthorization(Auth::user(), ['brand.view']);

        $filters = [
            'search' => request('search'),
            'role' => request('role'),
            'sort_field' => null,
            'sort_direction' => null,
        ];

        return view('backend.pages.brand.index', [
            'brands' => $this->brandService->getBrands($filters),            
            'breadcrumbs' => [
                'title' => __('Brands'),
            ],
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): Renderable
    {
        $this->checkAuthorization(Auth::user(), ['brand.create']);       

        ld_do_action('brand_create_page_before');

        return view('backend.pages.brand.create', [                        
            'breadcrumbs' => [
                'title' => __('New Brand'),
                'items' => [
                    [
                        'label' => __('Brands'),
                        'url' => route('admin.brand.index'),
                    ],
                ],
            ],
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreBrandRequest $request): RedirectResponse
    {
        
        $brand = new Brand();
        $brand->name = $request->name;
        
        $brand = ld_apply_filters('brand_store_before_save', $brand, $request);
        $brand->save();
        /** @var Brand $brand */
        $brand = ld_apply_filters('brand_store_after_save', $brand, $request);

        $this->storeActionLog(ActionType::CREATED, ['brand' => $brand]);

        session()->flash('success', __('Brand has been created.'));

        ld_do_action('brand_store_after', $brand);

        return redirect()->route('admin.brand.index');
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
    public function edit(int $id): Renderable
    {
        $this->checkAuthorization(Auth::user(), ['brand.edit']);

        $brand = Brand::findOrFail($id);

        ld_do_action('brand_edit_page_before');

        $brand = ld_apply_filters('brand_edit_page_before', $brand);

        return view('backend.pages.brand.edit', [            
            'brand' => $brand,
            'breadcrumbs' => [
                'title' => __('Edit Brand'),
                'items' => [
                    [
                        'label' => __('Brands'),
                        'url' => route('admin.brand.index'),
                    ],
                ],
            ],
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateBrandRequest $request, int $id): RedirectResponse
    {
        $brand = Brand::findOrFail($id);

        $brand->name = $request->name;        
        
        $brand = ld_apply_filters('brand_update_before_save', $brand, $request);
        $brand->save();

        /** @var Brand $brand */
        $brand = ld_apply_filters('brand_update_after_save', $brand, $request);
        ld_do_action('brand_update_after', $brand);

        $this->storeActionLog(ActionType::UPDATED, ['brand' => $brand]);

        session()->flash('success', __('Brand has been updated.'));
        
        return back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}