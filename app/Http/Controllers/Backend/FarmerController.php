<?php

namespace App\Http\Controllers\Backend;


use Illuminate\Http\Request;
use App\Enums\ActionType;
use App\Http\Controllers\Controller;
use App\Http\Requests\Farmer\StoreFarmerRequest;
use App\Models\Farmer;
use App\Models\Farm;
use App\Models\FarmField;
use App\Services\FarmerService;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class FarmerController extends Controller
{
    public function __construct(
        private readonly FarmerService $farmerService,        
    ) {
    }
    
    /**
     * Display a listing of the resource.
     */
    public function index(): Renderable
    {        
        $this->checkAuthorization(Auth::user(), ['farmer.view']);
        $filters = [
            'search' => request('search'),
            'role' => request('role'),
            'sort_field' => null,
            'sort_direction' => null,
        ];

        return view('backend.pages.farmers.index', [
            'farmers' => $this->farmerService->getUsers($filters),               
            'breadcrumbs' => [
                'title' => __('Farmers'),
            ],
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->checkAuthorization(Auth::user(), ['farmer.create']);       

        ld_do_action('farmer_create_page_before');

        return view('backend.pages.farmers.create', [                        
            'breadcrumbs' => [
                'title' => __('New Farmer'),
                'items' => [
                    [
                        'label' => __('Farmers'),
                        'url' => route('admin.farmer.index'),
                    ],
                ],
            ],
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreFarmerRequest $request)
    {
        $validated = $request->validated();     

        if ($request->hasFile('photo')) {
            $validated['photo'] = $request->file('photo')->store('farmers/photo', 'public');
        }

        $farmer = new Farmer();
        $farmer->name = $request->name;
        $farmer->phone_number = $request->phone_number;
        $farmer->email = $request->email;
        $farmer->password = Hash::make($request->password);
        $farmer->referral_code = $request->referral_code;
        $farmer->city = $request->city;
        $farmer->state = $request->state;
        $farmer->country = $request->country;
        $farmer->address = $request->address;
        $farmer->photo = $validated['photo'] ?? null;

        $farmer = ld_apply_filters('farmer_store_before_save', $farmer, $request);
        $farmer->save();
        /** @var Farmer $farmer */
        $farmer = ld_apply_filters('farmer_store_after_save', $farmer, $request);

        $this->storeActionLog(ActionType::CREATED, ['farmer' => $farmer]);

        session()->flash('success', __('Farmer has been created.'));

        ld_do_action('farmer_store_after', $farmer);

        return redirect()->route('admin.farmer.index');
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
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function farmList($id)
    {
        $this->checkAuthorization(Auth::user(), ['farmer.view']); 
        $farmer = Farmer::findOrFail($id);

        return view('backend.pages.farmers.list-farm', [    
            'farmer' => $farmer,
            'farmerFarms' => $farmer->farms()->paginate(10),
            'breadcrumbs' => [
                'title' => __($farmer->name ." Farm"),
                'items' => [
                    [
                        'label' => __("Farmers"),
                        'url' => route('admin.farmer.index'),
                    ],
                ],
            ],
        ]);
    }


    public function farmCreate($id)
    {
        $this->checkAuthorization(Auth::user(), ['farmer.edit']); 
        $farmer = Farmer::findOrFail($id);

        return view('backend.pages.farmers.add-farm', [    
            'farmerId' => $id,
            'breadcrumbs' => [
                'title' => __('Add Farmer Farm'),
                'items' => [
                    [
                        'label' => __('Farmers'),
                        'url' => route('admin.farmer.index'),
                    ],
                ],
            ],
        ]);
    }

    public function farmStore(Request $request)
    {
        $this->checkAuthorization(Auth::user(), ['farmer.edit']); 
        DB::beginTransaction();
        try {            
            $farmerId = $request->farmer_id;

            // Create Farm
            $farm = Farm::create([
                'farmer_id' => $farmerId,
                'name' => $request->name,
                'location' => $request->location,
            ]);

            // Save each field
            foreach ($request->fields as $fieldData) {
                $farm->fields()->create([
                    'land_area'    => $fieldData['landArea'],
                    'land_unit'    => $fieldData['landUnit'],
                    'land_type'    => $fieldData['landType'],
                    'soil_type'    => $fieldData['soilType'],
                    'soil_ph'      => $fieldData['soilPH'],
                    'water_type'   => $fieldData['waterType'],
                    'water_ph'     => $fieldData['waterPH'],
                    'crop_season'  => $fieldData['cropSeason'],
                    'crop_name'    => $fieldData['cropName'] ?? null,
                    'sowing_date'  => $fieldData['sowingDate'] ?? null,
                    'harvest_date' => $fieldData['harvestDate'] ?? null,
                ]);
            }

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'Farm and fields saved successfully!',
                'farm_id' => $farm->id,
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => 'Something went wrong. ' . $e->getMessage(),
            ], 500);
        }
    }
}
