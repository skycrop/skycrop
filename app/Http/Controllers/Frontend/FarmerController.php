<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Farm;
use App\Models\FarmField;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Contracts\Support\Renderable;

class FarmerController extends Controller
{
    public function index(): Renderable
    {
        $farmerDetail = Auth::guard('farmer')->user();
        
        return view('frontend.pages.user.profile', [
            'farmerDetail' => $farmerDetail,            
            'breadcrumbs' => [
                'title' => __('User Profile'),
            ],
        ]);
    }

    public function updateProfile(Request $request)
    {

        $validated = $request->validate([
            'name'  => 'required|string|max:255',
            'photo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);
        
        $farmer = Auth::guard('farmer')->user();        
        $farmer->name = $validated['name'];

        if ($request->hasFile('photo')) {            
            if ($farmer->photo && \Storage::disk('public')->exists($farmer->photo)) {
                \Storage::disk('public')->delete($farmer->photo);
            }
            
            $path = $request->file('photo')->store('farmer_photos', 'public');
            $farmer->photo = $path;
        }

        $farmer->save();

        return redirect()->back()->with('success', 'Profile updated successfully!');
    }

    public function updatePassword(Request $request)
    {        
        $request->validate([
            'current_password'      => 'required',
            'new_password'          => 'required|string|min:8|confirmed',
        ]);

        $user = Auth::guard('farmer')->user();   
        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Your current password is incorrect.']);
        }

        // Prevent setting the same password
        if (Hash::check($request->new_password, $user->password)) {
            return back()->withErrors(['new_password' => 'New password cannot be the same as the current password.']);
        }

        // Update password
        $user->password = Hash::make($request->new_password);
        $user->save();

        return back()->with('success', 'Password updated successfully.');
    }


    public function farmList(): Renderable
    {
        $farmerDetail = Auth::guard('farmer')->user();
        $farmerFarms = $farmerDetail
            ->farms()
            ->paginate(10);

        return view('frontend.pages.farms.index', [
            'farmerDetail' => $farmerDetail,
            'farmerFarms' => $farmerFarms,            
            'breadcrumbs' => [
                'title' => __('User Farms'),
            ],
        ]);
    }

    public function farmCreate(): Renderable
    {
        $farmerDetail = Auth::guard('farmer')->user();

        return view('frontend.pages.farms.create', [
            'farmerDetail' => $farmerDetail,
            'breadcrumbs' => [
                'title' => __('Create Farm'),
            ],
        ]);
    }

    public function farmStore(Request $request)
    {
        DB::beginTransaction();
        try {            
            $farmerId = Auth::guard('farmer')->id();

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
