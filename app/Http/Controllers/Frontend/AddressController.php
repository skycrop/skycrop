<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Address;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AddressController extends Controller
{
    public function index()
    {
        $farmerDetail = Auth::guard('farmer')->user();
        $addresses = $farmerDetail->addresses()->orderBy('is_default', 'desc')->paginate(5);

        return view('frontend.pages.address.index', [
            'farmerDetail' => $farmerDetail,
            'addresses' => $addresses,
            'breadcrumbs' => [
                'title' => __('User Shipping Address'),
            ],
        ]);
    }

    // Show form to create address
    public function create()
    {
        $farmerDetail = Auth::guard('farmer')->user();

         return view('frontend.pages.address.create', [            
            'farmerDetail' => $farmerDetail,
            'breadcrumbs' => [
                'title' => __('User Shipping Address'),
            ],
        ]);        
    }

    // Store new address
    public function store(Request $request)
    {
        $userId = Auth::guard('farmer')->id();
        $request->validate([
            'full_name' => 'required',
            'address_line_1' => 'required',
            'city' => 'required',
            'state' => 'required',
            'zipcode' => 'required',
            'phone' => 'required',
        ]);

        $isDefault = $request->has('is_default') && $request->is_default ? true : false;
        if ($isDefault) {            
            Address::where('farmer_id', $userId)->update(['is_default' => false]);
        }

        Address::create([
            'farmer_id' => $userId,
            'full_name' => $request->full_name,
            'address_line_1' => $request->address_line_1,
            'address_line_2' => $request->address_line_2,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'city' => $request->city,
            'state' => $request->state,
            'zipcode' => $request->zipcode,
            'phone' => $request->phone,
            'is_default' => $isDefault,
        ]);

        session()->flash('success', __('Address added successfully!'));

        return redirect()->route('user.addresses.index');
    }

    // Show form to edit address
    public function edit(Address $address)
    {
        $this->authorize('update', $address);
        return view('farmer.addresses.edit', compact('address'));
    }

    // Update address
    public function update(Request $request, Address $address)
    {
        $this->authorize('update', $address);

        $request->validate([
            'full_name' => 'required',
            'address_line_1' => 'required',
            'city' => 'required',
            'state' => 'required',
            'zipcode' => 'required',
            'phone' => 'required',
        ]);

        $address->update($request->all());

        return redirect()->route('addresses.index')->with('success', 'Address updated successfully!');
    }

    // Delete address
    public function destroy(Address $address)
    {
        $this->authorize('delete', $address);
        $address->delete();

        return redirect()->route('addresses.index')->with('success', 'Address deleted.');
    }
}
