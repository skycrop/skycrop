<?php

declare(strict_types=1);

namespace App\Http\Requests\Collection;

use App\Http\Requests\FormRequest;
use Illuminate\Support\Facades\Auth;

class StoreCollectionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->checkAuthorization(Auth::user(), ['category.create']);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return ld_apply_filters('collection.store.validation.rules', [
            /** @example "John Doe" */
            'name' => 'required|string|max:50',

            /** @example "john.doe@example.com" */
            'level' => 'required|in:1,2,3',

            /** @example "johndoe123" */
            'parent_id' => 'nullable|exists:collections,id',
        ]);
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Collection name is required.',
            'level.required' => 'Please select a collection level.',
            'level.in' => 'Invalid collection level selected.',
            'parent_id.exists' => 'The selected parent collection does not exist.',
        ];
    }
}
