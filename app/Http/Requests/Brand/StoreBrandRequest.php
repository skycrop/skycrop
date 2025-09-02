<?php

declare(strict_types=1);

namespace App\Http\Requests\Brand;

use App\Http\Requests\FormRequest;
use Illuminate\Support\Facades\Auth;

class StoreBrandRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->checkAuthorization(Auth::user(), ['brand.create']);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return ld_apply_filters('brand.store.validation.rules', [
            /** @example "John Doe" */
            'name' => 'required|string|max:50|unique:brands,name',
        ]);
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Brand name is required.',            
        ];
    }
}
