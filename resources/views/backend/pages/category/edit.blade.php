@extends('backend.layouts.app')

@section('title')
    {{ $breadcrumbs['title'] }} | {{ config('app.name') }}
@endsection

@section('admin-content')
    <div class="p-4 mx-auto max-w-7xl md:p-6">
        <x-breadcrumbs :breadcrumbs="$breadcrumbs" />

        {!! ld_apply_filters('category_after_breadcrumbs', '') !!}

        <div class="space-y-6">
            <div class="rounded-md border border-gray-200 bg-white dark:border-gray-800 dark:bg-gray-900">
                <div class="p-5 space-y-6 border-t border-gray-100 dark:border-gray-800 sm:p-6">
                    <form action="{{ route('admin.category.update', $category->id) }}" method="POST" class="space-y-6"
                        id="validateCategoryForm"
                        enctype="multipart/form-data">
                        @method('PUT')
                        @csrf
                        <input type="hidden" id="is_edit_mode" value="1">
                        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                            <div class="space-y-1">
                                <label for="name"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Category Name') }} <span class="text-red-500">*</span></label>
                                <input type="text" name="name" id="name" required value="{{ $category->name }}"
                                    placeholder="{{ __('Enter Category Name') }}" class="form-control">
                            </div>
                            
                            <div>
                                <label
                                    for="select-id"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300"
                                >
                                    {{ __('Category Level') }} <span class="text-red-500">*</span>
                                </label>
                                
                                <select name="level" class="form-control mt-1">
                                    <option value="">{{ __('Select Level') }}</option>
                                    @foreach($level as $key => $cat)
                                    <option value="{{ $key }}" {{ (string) $category->level == (string) $key ? 'selected' : '' }}>
                                        {{ ucfirst($cat) }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label
                                    for="select-id"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300"
                                >
                                    {{ __('Parent Category (if any)') }}
                                </label>
                                
                                <select name="parent_id" class="form-control mt-1">
                                    <option value="">{{ __('Select Parent Category') }}</option>
                                    @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}" {{ (string) $category->parent_id == (string) $cat->id ? 'selected' : '' }}>
                                        {{ ucfirst($cat->name) }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <x-inputs.file-input
                                    name="photo"
                                    id="photo"
                                    accept="image/*"
                                    label="{{ __('Thumbnail Image') }}"                                                                                                            
                                    class="mt-1"
                                    :existingAttachment="$category->photo !== '' && !empty($category->photo) ? asset('storage/'.$category->photo) : null"
                                    :existingAltText="''"
                                >                                    
                                </x-inputs.file-input>
                            </div>
                            
                            {!! ld_apply_filters('after_category_field', '', $category) !!}
                        </div>
                        <div class="mt-6">
                            <x-buttons.submit-buttons cancelUrl="{{ route('admin.category.index') }}" />
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {    
        document.querySelector('[name="level"]').addEventListener('change', function (e) {
            const levelValue = e.target.value;
            const parentField = document.querySelector('[name="parent_id"]');
            
            parentField.value = '';
            
        });
    });
</script>
@endpush