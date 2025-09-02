<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\TempUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Carbon\Carbon;

class TempUserController extends Controller
{
    // Store mobile number and send OTP
    public function sendOtp(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'mobile_number' => 'required|string|unique:farmers,phone_number',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // $otp = rand(100000, 999999); // generate a 6-digit OTP
        $otp = 123456; // generate a 6-digit OTP


        $tempUser = TempUser::updateOrCreate(
            ['mobile_number' => $request->mobile_number],
            [
                'otp' => $otp,
                'otp_sent_at' => Carbon::now(),
                'is_verified' => false,
            ]
        );

        // Send OTP via SMS here (integrate your SMS service)

        return response()->json(['message' => 'OTP sent successfully']);
    }

    // Verify OTP
    public function verifyOtp(Request $request)
    {
        $request->validate([
            'mobile_number' => 'required|string',
            'otp' => 'required|string',
        ]);

        $tempUser = TempUser::where('mobile_number', $request->mobile_number)->first();

        if (!$tempUser) {
            return response()->json(['error' => 'Mobile number not found'], 404);
        }

        if ($tempUser->otp !== $request->otp) {
            return response()->json(['error' => 'Invalid OTP'], 422);
        }

        // Optionally check if OTP expired
        if ($tempUser->otp_sent_at->diffInMinutes(now()) > 10) {
            return response()->json(['error' => 'OTP expired'], 422);
        }

        $tempUser->is_verified = true;
        $tempUser->save();

        session(['verified_phone' => $tempUser->mobile_number]);

        return response()->json([
            'message' => 'OTP verified successfully',            
        ]);
    }
}
