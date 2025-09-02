<?php

declare(strict_types=1);

namespace App\Http\Requests\Category;

use App\Http\Requests\FormRequest;
use Illuminate\Support\Facades\Auth;

class UpdateCategoryRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->checkAuthorization(Auth::user(), ['category.edit']);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $categoryId = $this->route('category');

        return ld_apply_filters('category.update.validation.rules', [
            /** @example "Jane Smith" */
            'name' => 'required|max:50',

            /** @example "john.doe@example.com" */
            'level' => 'required|in:0,1,2',

            /** @example "johndoe123" */
            'parent_id' => 'nullable|exists:categories,id',
        ], $categoryId);
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Category name is required.',
            'level.required' => 'Please select a category level.',
            'level.in' => 'Invalid category level selected.',
            'parent_id.exists' => 'The selected parent category does not exist.',
        ];
    }
}
