<?php

declare(strict_types=1);

namespace App\Http\Requests\Crop;

use App\Http\Requests\FormRequest;
use Illuminate\Support\Facades\Auth;

class StoreCropRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->checkAuthorization(Auth::user(), ['crop.create']);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return ld_apply_filters('crop.store.validation.rules', [
            'crop_name' => ['required', 'string', 'max:255'],
            'suitable_season' => ['required', 'array'],
            'category' => ['required', 'array'],
            'suitable_soil_types' => ['required', 'array'],
            'suitable_land_types' => ['required', 'array'],
            'suitable_water_types' => ['required', 'array'],
            'soil_ph_min' => ['required', 'numeric', 'between:0,14'],
            'soil_ph_max' => ['required', 'numeric', 'between:0,14'],
            'water_ph_min' => ['required', 'numeric', 'between:0,14'],
            'water_ph_max' => ['required', 'numeric', 'between:0,14'],
        ]);
    }

    public function messages(): array
    {
        return [
            'crop_name.required' => 'The crop name is required.',
            'crop_name.string' => 'The crop name must be a string.',
            'crop_name.max' => 'The crop name may not be greater than 255 characters.',

            'suitable_season.array' => 'Suitable season must be an array.',
            'category.array' => 'Category must be an array.',
            'suitable_soil_types.array' => 'Suitable soil types must be an array.',
            'suitable_land_types.array' => 'Suitable land types must be an array.',
            'suitable_water_types.array' => 'Suitable water types must be an array.',

            'soil_ph_min.numeric' => 'Soil pH (min) must be a number.',
            'soil_ph_max.numeric' => 'Soil pH (max) must be a number.',
            'water_ph_min.numeric' => 'Water pH (min) must be a number.',
            'water_ph_max.numeric' => 'Water pH (max) must be a number.',

            'soil_ph_min.between' => 'Soil pH (min) must be between 0 and 14.',
            'soil_ph_max.between' => 'Soil pH (max) must be between 0 and 14.',
            'water_ph_min.between' => 'Water pH (min) must be between 0 and 14.',
            'water_ph_max.between' => 'Water pH (max) must be between 0 and 14.',
        ];
    }

}
