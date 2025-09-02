<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest:farmer')->except('logout');
    }

    public function showRegistrationForm()
    {
        return view('auth.register', [
            'breadcrumbs' => [
                'title' => __('Register'),                
            ],
            'verifiedPhone' => session('verified_phone')
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'phone_number' => 'required|string|unique:farmers,phone_number',
            'full_name' => 'required|string|max:255',
            'email' => 'required|email|unique:farmers,email',
            'password' => 'required|min:6',
            'photo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'referral_code' => 'nullable|string|exists:users,referral_code',
        ]);

        if ($validator->fails()) {            
            return response()->json([
                'errors' => $validator->errors()
            ], 422);
        }

        $tempUser = \App\Models\TempUser::where('mobile_number', $request->phone_number)
            ->where('is_verified', 1)
            ->first();

        if (!$tempUser) {
            return response()->json(['error' => 'Mobile number not verified'], 422);
        }

        $photoPath = null;
        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('farmers/photo', 'public');
        }

        $farmer = \App\Models\Farmer::create([
            'name' => $request->full_name,
            'email' => $request->email,
            'phone_number' => $request->phone_number,
            'phone_verified_at' => now(),
            'password' => Hash::make($request->password),
            'city' => $request->city,
            'state' => $request->state,
            'country' => $request->country,
            'address' => $request->address,
            'referral_code' => $request->referral_code,
            'photo' => $photoPath,
        ]);

        // Optionally delete temp user entry to clean up
        $tempUser->delete();
        session()->forget('verified_phone');

        return response()->json(['success' => true, 'message' => 'Registration successful please login']);
    }

}
