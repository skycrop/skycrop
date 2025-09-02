<?php

namespace App\Http\Controllers\Backend;

use App\Enums\ActionType;
use App\Http\Controllers\Controller;
use App\Http\Requests\Crop\StoreCropRequest;
use App\Http\Requests\Crop\UpdateCropRequest;
use App\Models\Crop;
use App\Services\CategoryService;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CropController extends Controller
{
    public function __construct(        
        private readonly CategoryService $categoryService,                
    ) {
    }

    /**
     * Display a listing of the resource.
     */
    public function index(): Renderable
    {
        $this->checkAuthorization(Auth::user(), ['crop.view']);

        $filters = [
            'search' => request('search'),                        
            'sort_field' => null,
            'sort_direction' => null,
        ];

        $query = Crop::applyFilters($filters);
        $crops = $query->paginateData([
            'per_page' => $filters['per_page'] ?? config('settings.default_pagination') ?? 10,
        ]);

        return view('backend.pages.crop.index', [
            'crops' => $crops,            
            'breadcrumbs' => [
                'title' => __('Crops'),
            ],
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): Renderable
    {
        $this->checkAuthorization(Auth::user(), ['crop.create']);        

        ld_do_action('crop_create_page_before');

        return view('backend.pages.crop.create', [            
            'categories' =>  $this->categoryService->getParentCategories(),
            'suitable_season' =>  ['Khrif', 'Rabi', 'Jayad'],            
            'suitable_soil_types' =>  ['Alluvial Soil','Black Soil', 'Red Soil', 'Sandy Soil', 'Laterite Soil', 'Saline Soil', 'Peaty Soil', 'Clay Soil'],            
            'suitable_land_types' =>  ['Nahri', 'Birani'],            
            'suitable_water_types' =>  ['Fresh Water', 'Slightly Saline', 'Moderately Saline', 'Highly Saline'],            
            'breadcrumbs' => [
                'title' => __('New Crop'),
                'items' => [
                    [
                        'label' => __('Crop'),
                        'url' => route('admin.crop.index'),
                    ],
                ],
            ],
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCropRequest $request)
    {        
        $validated = $request->validated();               
        $validated = ld_apply_filters('crop_store_before_save', $validated, $request);

        
        $validated = array_map(function ($value) {
            if (is_array($value)) {                
                return array_filter($value, fn($v) => $v !== null && $v !== '');
            }
            return $value;
        }, $validated);

        /** @var Crop $crop */
        $crop = Crop::create($validated);
        
        $crop = ld_apply_filters('crop_store_after_save', $crop, $request);
        
        $this->storeActionLog(ActionType::CREATED, ['crop' => $crop]);
        
        session()->flash('success', __('Crop has been created.'));
        
        ld_do_action('crop_store_after', $crop);
        
        return redirect()->route('admin.crop.index');
    }


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $this->checkAuthorization(Auth::user(), ['crop.edit']);

        $crop = Crop::findOrFail($id);    
        // dd($crop->category);
        
        ld_do_action('crop_edit_page_before');

        $crop = ld_apply_filters('crop_edit_page_before', $crop);        

        return view('backend.pages.crop.edit', [      
            'crop' => $crop,      
            'categories' =>  $this->categoryService->getParentCategories(),            
            'suitable_season' =>  ['Khrif', 'Rabi', 'Jayad'],            
            'suitable_soil_types' =>  ['Alluvial Soil','Black Soil', 'Red Soil', 'Sandy Soil', 'Laterite Soil', 'Saline Soil', 'Peaty Soil', 'Clay Soil'],            
            'suitable_land_types' =>  ['Nahri', 'Birani'],            
            'suitable_water_types' =>  ['Fresh Water', 'Slightly Saline', 'Moderately Saline', 'Highly Saline'],            
            'breadcrumbs' => [
                'title' => __('Edit Crop'),
                'items' => [
                    [
                        'label' => __('Crop'),
                        'url' => route('admin.crop.index'),
                    ],
                ],
            ],
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCropRequest $request, string $id)
    {
        $validated = $request->validated();
        $validated = array_map(function ($value) {
            if (is_array($value)) {                
                return array_filter($value, fn($v) => $v !== null && $v !== '');
            }
            return $value;
        }, $validated);
        

        $crop = Crop::findOrFail($id);

        $crop->crop_name = $validated['crop_name'];
        $crop->suitable_season = $validated['suitable_season'];
        $crop->category = $validated['category'];
        $crop->suitable_soil_types = $validated['suitable_soil_types'];
        $crop->suitable_land_types = $validated['suitable_land_types'];
        $crop->suitable_water_types = $validated['suitable_water_types'];
        $crop->soil_ph_min = $validated['soil_ph_min'];
        $crop->soil_ph_max = $validated['soil_ph_max'];
        $crop->water_ph_min = $validated['water_ph_min'];
        $crop->water_ph_max = $validated['water_ph_max'];

        $crop = ld_apply_filters('crop_update_before_save', $crop, $validated);
        $crop->save();

        /** @var Crop $crop */
        $crop = ld_apply_filters('crop_update_after_save', $crop, $validated);
        ld_do_action('crop_update_after', $crop);

        $this->storeActionLog(ActionType::UPDATED, ['crop' => $crop]);

        session()->flash('success', __('Crop has been updated.'));

        return redirect()->route('admin.crop.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function details(Crop $crop)
    {
        return response()->json([
            'suitable_season'      => $crop->suitable_season,
            'suitable_soil_types'  => $crop->suitable_soil_types,
            'suitable_land_types'  => $crop->suitable_land_types,
            'suitable_water_types' => $crop->suitable_water_types,
            'soil_ph_min'          => $crop->soil_ph_min,
            'soil_ph_max'          => $crop->soil_ph_max,
            'water_ph_min'         => $crop->water_ph_min,
            'water_ph_max'         => $crop->water_ph_max,
        ]);
    }

}
