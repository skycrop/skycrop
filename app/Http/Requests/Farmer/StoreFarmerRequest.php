<?php
namespace App\Http\Requests\Farmer;

use App\Http\Requests\FormRequest;
use Illuminate\Support\Facades\Auth;


class StoreFarmerRequest extends FormRequest
{
    public function authorize()
    {
        return $this->checkAuthorization(Auth::user(), ['farmer.create']);
    }

    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'phone_number' => 'required|string|max:20',
            'email' => 'required|email|max:255|unique:farmers,email',
            'password' => 'required|string|min:8|max:50',
            'referral_code' => 'nullable|string|max:100',
            'city' => 'required|string|max:100',
            'state' => 'required|string|max:100',
            'country' => 'required|string|max:100',
            'address' => 'required|string|max:255',
            'photo' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048', // Adjust types/max as needed
        ];
    }

    public function messages()
    {
        return [
            'photo.mimes' => 'The photo must be a file of type: jpg, jpeg, png, or pdf.',
            'photo.max' => 'The file must not be larger than 2MB.',
        ];
    }
}
