<?php

declare(strict_types=1);

namespace App\Http\Requests\Brand;

use App\Http\Requests\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class UpdateBrandRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->checkAuthorization(Auth::user(), ['brand.edit']);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $brandId = $this->route('brand');

        return ld_apply_filters('brand.update.validation.rules', [
            /** @example "Jane Smith" */
            'name' => [
                'required',
                'max:50',
                Rule::unique('brands', 'name')->ignore($brandId),
            ],
        ], $brandId);
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Brand name is required.',            
        ];
    }
}
