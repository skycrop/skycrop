@extends('backend.layouts.app')

@section('title')
    {{ $breadcrumbs['title'] }} | {{ config('app.name') }}
@endsection

@section('admin-content')
    <div class="p-4 mx-auto max-w-(--breakpoint-2xl) md:p-6">
        <x-breadcrumbs :breadcrumbs="$breadcrumbs" />

        {!! ld_apply_filters('product_after_breadcrumbs', '') !!}

        <div class="space-y-6"> 
            <div class="rounded-md border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
                <div class="px-5 py-4 sm:px-6 sm:py-5">

                    <form action="{{ route('admin.product.update', $product->id) }}" method="POST" 
                        enctype="multipart/form-data"
                        id="validateProductForm">
                        @method('PUT')
                        @csrf
                        <input type="hidden" id="is_edit_mode" value="1">
                        <div class="space-y-6">
                            <div class="rounded-md border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
                                <div class="px-5 py-4 sm:px-6 sm:py-5">
                                    <h3 class="text-base font-medium text-gray-700 dark:text-white/90">
                                        {{ __('Product Information') }}
                                    </h3>
                                </div>
                                
                                <div class="p-5 space-y-6 border-t border-gray-100 dark:border-gray-800 sm:p-6">                    
                                    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                                        <div x-data="slugGenerator('{{ $product->name }}', '{{ old('slug') }}')">
                                            <div class="space-y-1">
                                                <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Product Name') }} <span class="text-red-500">*</span></label>
                                                <input type="text" name="name" id="name" x-model="title" required autofocus
                                                    value="{{ $product->name }}" placeholder="{{ __('Enter Product Name') }}"
                                                    class="form-control">
                                            </div>                                            
                                        </div>

                                        <div class="space-y-1">
                                            <label for="sku" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Product SKU') }} <span class="text-red-500">*</span></label>
                                            <input type="text" name="sku" id="sku" required
                                                value="{{ $product->sku }}" placeholder="{{ __('Enter Product SKU') }}"
                                                class="form-control">
                                        </div>

                                        <div>
                                            <x-inputs.combobox name="brand_id" label="{{ __('Brand') }}"
                                                required
                                                placeholder="{{ __('Select Brand') }}" 
                                                :options="collect($brands)->map(function ($brand) {
                                                    return [
                                                        'value' => $brand->id,
                                                        'label' => ucfirst($brand->name),
                                                    ];
                                                })->values()->toArray()" 
                                                :selected="$product->brand_id"                                  
                                                :searchable="false" />
                                        </div>

                                        <div>
                                            <x-inputs.combobox name="collection_ids[]" label="{{ __('Collection') }}"
                                                required
                                                placeholder="{{ __('Select Collection') }}" :options="collect($collections)
                                                    ->map(function ($brand) {
                                                        return [
                                                            'value' => $brand->id,
                                                            'label' => ucfirst($brand->name),
                                                        ];
                                                    })
                                                    ->values()
                                                    ->toArray()" :selected="$product->collections->pluck('id')->toArray()"
                                                :multiple="true" :searchable="false" />
                                        </div>

                                        <div>
                                            <x-inputs.combobox name="tag_ids[]" label="{{ __('Chemicals') }}"
                                                required
                                                placeholder="{{ __('Select Chemicals') }}" :options="collect($tags)
                                                    ->map(function ($tag) {
                                                        return [
                                                            'value' => $tag->id,
                                                            'label' => ucfirst($tag->name),
                                                        ];
                                                    })
                                                    ->values()
                                                    ->toArray()" :selected="$product->tagsData->pluck('id')->toArray()"
                                                :multiple="true" :searchable="false" />
                                        </div>

                                        <div class="space-y-1">
                                            <x-inputs.combobox name="is_active" label="{{ __('Status') }}"
                                                placeholder="{{ __('Select Status') }}" 
                                                required
                                                :options="collect($status)
                                                ->map(fn($name, $id) => ['value' => $id, 'label' => ucfirst($name)])"
                                                :selected="$product->is_active"
                                                :searchable="false" />
                                        </div>

                                    </div>

                                    <div class="grid grid-cols-1 gap-6 sm:grid-cols-1 mb-6">
                                        <div class="space-y-1 mb-12">
                                            <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Description') }}<span class="text-red-500">*</span></label>
                                            <textarea name="description" id="content" rows="4">{!! $product->description !!}</textarea>                                
                                        </div>
                                    </div>                    
                                </div>                

                            </div>
                        </div>

                        <div class="space-y-6 mt-3">
                            <div class="rounded-md border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
                                <div class="px-5 py-4 sm:px-6 sm:py-5">
                                    <h3 class="text-base font-medium text-gray-700 dark:text-white/90">
                                        {{ __('Categorization') }}
                                    </h3>
                                </div>
                                
                                <div class="p-5 space-y-6 border-t border-gray-100 dark:border-gray-800 sm:p-6">                                                                
                                    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                                        <div>
                                            <label
                                                for="scategory_id"
                                                class="block text-sm font-medium text-gray-700 dark:text-gray-300"
                                            >
                                                {{ __('Category') }} <span class="text-red-500">*</span>
                                            </label>
                                            <select id="category-select"  name="category_id" class="form-control">
                                                <option value="">{{ __('Select Category') }}</option>
                                                @foreach($category as $cat)
                                                    <option value="{{ $cat->id }}" {{ $product->category_id == $cat->id ? 'selected' : '' }}>
                                                        {{ ucfirst($cat->name) }}
                                                    </option>
                                                @endforeach
                                            </select>                                
                                        </div>

                                        <div>
                                            <label
                                                for="subcategory_id"
                                                class="block text-sm font-medium text-gray-700 dark:text-gray-300"
                                            >
                                                {{ __('Sub Category') }} <span class="text-red-500">*</span>
                                            </label>
                                            <select id="subcategory-select" name="subcategory_id" class="form-control">
                                                <option value="">{{ __('Select Sub Category') }}</option>                                    
                                                @foreach($subcategories as $cat)
                                                    <option value="{{ $cat->id }}" {{ $product->subcategory_id == $cat->id ? 'selected' : '' }}>
                                                        {{ ucfirst($cat->name) }}
                                                    </option>
                                                @endforeach
                                            </select>                                
                                        </div>

                                    </div>  
                                </div>                

                            </div>
                        </div>

                        <div class="space-y-6 mt-3">
                            <div class="rounded-md border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
                                <div class="px-5 py-4 sm:px-6 sm:py-5">
                                    <h3 class="text-base font-medium text-gray-700 dark:text-white/90">
                                        {{ __('Images') }}
                                    </h3>
                                </div>
                                
                                <div class="p-5 space-y-6 border-t border-gray-100 dark:border-gray-800 sm:p-6">                                                                
                                    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                                        <div class="space-y-1">
                                            <x-inputs.file-input
                                                name="cover_image"
                                                id="cover_image"
                                                accept="image/*"
                                                label="{{ __('Cover Image') }}"                                                                                                            
                                                class="mt-1"
                                                :existingAttachment="$product->cover_image !== '' && !empty($product->cover_image) ? asset('storage/'.$product->cover_image) : null"
                                                :existingAltText="''"
                                            >                                    
                                            </x-inputs.file-input>
                                            <!-- :existingAttachment="$product->cover_image !== '' && !empty($product->cover_image) ? asset('storage/'.$product->cover_image) : null" -->
                                        </div>

                                        <div class="space-y-1">
                                            <x-inputs.file-input
                                                name="images[]"
                                                id="images"                                    
                                                accept="image/*"
                                                label="{{ __('Other Images') }}"                                                                                                            
                                                class="mt-1"
                                                multiple
                                                :existingAttachments="$product->images !== '' && !empty($product->images) ? json_decode($product->images) : []"
                                            >                                    
                                            </x-inputs.file-input>
                                        </div>

                                    </div>  
                                </div>                

                            </div>
                        </div>

                        <div class="space-y-6 mt-3">
                            <div class="rounded-md border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
                                <div class="px-5 py-4 sm:px-6 sm:py-5">
                                    <h3 class="text-base font-medium text-gray-700 dark:text-white/90">
                                        {{ __('Suitability') }}
                                    </h3>
                                </div>
                                
                                <div class="p-5 space-y-6 border-t border-gray-100 dark:border-gray-800 sm:p-6">                                                                
                                    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                                        <div>
                                            <label
                                                for="seed_type"
                                                class="block text-sm font-medium text-gray-700 dark:text-gray-300"
                                            >
                                                {{ __('Seed Type') }} <span class="text-red-500">*</span>
                                            </label>
                                            <select name="seed_type" class="form-control mt-1">
                                                <option value="">{{ __('Select Seed Type') }}</option>
                                                @foreach($seed_type as $seed)
                                                <option value="{{ $seed }}" {{ $product->seed_type == $seed ? 'selected' : '' }}>{{ ucfirst($seed) }}</option>
                                                @endforeach
                                            </select>  
                                        </div>

                                        <div>
                                            <label
                                                for="crop_id"
                                                class="block text-sm font-medium text-gray-700 dark:text-gray-300"
                                            >
                                                {{ __('Crop Name') }} <span class="text-red-500">*</span>
                                            </label>
                                            <select id="crop-select"  name="crop_id" class="form-control mt-1">
                                                <option value="">{{ __('Select Crop') }}</option>
                                                @foreach($crops as $crop)
                                                <option value="{{ $crop->id }}" {{ $product->crop_id == $crop->id ? 'selected' : '' }}>{{ ucfirst($crop->crop_name) }}</option>
                                                @endforeach
                                            </select>                                            
                                        </div>

                                        <div>
                                            <input type="hidden" name="suitable_season"      id="suitable_season" value="{{ $product->suitable_season }}">
                                            <input type="hidden" name="suitable_soil_types"  id="suitable_soil_types"  value="{{ $product->suitable_soil_types }}" >
                                            <input type="hidden" name="suitable_land_types"  id="suitable_land_types" value="{{ $product->suitable_land_types }}" >
                                            <input type="hidden" name="suitable_water_types" id="suitable_water_types" value="{{ $product->suitable_water_types }}" >
                                            <input type="hidden" name="soil_ph_min"          id="soil_ph_min" value="{{ $product->soil_ph_min }}" >
                                            <input type="hidden" name="soil_ph_max"          id="soil_ph_max" value="{{ $product->soil_ph_max }}" >
                                            <input type="hidden" name="water_ph_min"         id="water_ph_min" value="{{ $product->water_ph_min }}" >
                                            <input type="hidden" name="water_ph_max"         id="water_ph_max" value="{{ $product->water_ph_max }}" >                                       
                                        </div>
                                    </div>
                                </div>                

                            </div>
                        </div>

                        <div class="space-y-6 mt-3">
                            <div class="rounded-md border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
                                <div class="px-5 py-4 sm:px-6 sm:py-5">
                                    <h3 class="text-base font-medium text-gray-700 dark:text-white/90">
                                        {{ __('Video Urls') }}
                                    </h3>
                                </div>
                                
                                <div class="p-5 space-y-6 border-t border-gray-100 dark:border-gray-800 sm:p-6">                                                                
                                    <div class="grid grid-cols-1 gap-6 sm:grid-cols-3">
                                        <div class="space-y-1">
                                            <label for="video_urls_benefit" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Benefit Video URL') }}</label>
                                            <input type="text" name="video_urls[benefit]" id="video_urls_benefit" required autofocus
                                                value="{{ json_decode($product->video_urls, true)['benefit'] ?? '' }}" placeholder="{{ __('Enter Benefit Video URL') }}"                                                                        
                                                class="form-control">
                                        </div>

                                        <div class="space-y-1">
                                            <label for="video_urls_testimonial" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Testimonial  Video URL') }}</label>
                                            <input type="text" name="video_urls[testimonial]" id="video_urls_testimonial" required autofocus
                                                value="{{ json_decode($product->video_urls, true)['testimonial'] ?? '' }}" placeholder="{{ __('Enter Testimonial Video URL') }}"                                                                        
                                                class="form-control">
                                        </div>

                                        <div class="space-y-1">
                                            <label for="video_urls_result" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Result Video URL') }}</label>
                                            <input type="text" name="video_urls[result]" id="video_urls_result" required autofocus
                                                value="{{ json_decode($product->video_urls, true)['result'] ?? '' }}" placeholder="{{ __('Enter Result Video URL') }}"                                                                        
                                                class="form-control">
                                        </div>
                                    </div>
                                </div>                

                            </div>
                        </div>

                        <div class="space-y-6 mt-3">
                            <x-variant-fields :post-meta="isset($product) ? $product->variants : []" />
                        </div>

                        <div class="mt-6">
                            <x-buttons.submit-buttons cancelUrl="{{ route('admin.product.index') }}" />
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <x-quill-editor :editor-id="'content'" />
    <script>
    document.addEventListener('DOMContentLoaded', function () {
        const categorySelect = document.getElementById('category-select');        
        const subcategorySelect = document.getElementById('subcategory-select');

        categorySelect.addEventListener('change', function () {
            const categoryId = this.value;

            // Clear existing subcategories
            subcategorySelect.innerHTML = '<option value="">{{ __('Select Sub Category') }}</option>';

            if (categoryId) {
                    fetch(`/admin/category/${categoryId}/subcategories`)
                    .then(response => response.json())
                    .then(data => {
                        data.forEach(function (sub) {
                            const option = document.createElement('option');
                            option.value = sub.id;
                            option.textContent = sub.name;
                            subcategorySelect.appendChild(option);
                        });
                    })
                    .catch(error => {
                        console.error('Error loading subcategories:', error);
                    });
            }
        });
    });
    </script>

    @endpush
@endsection
