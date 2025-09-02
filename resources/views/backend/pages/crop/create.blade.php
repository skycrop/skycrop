@extends('backend.layouts.app')

@section('title')
    {{ $breadcrumbs['title'] }} | {{ config('app.name') }}
@endsection

@section('admin-content')
    <div class="p-4 mx-auto max-w-(--breakpoint-2xl) md:p-6">
        <x-breadcrumbs :breadcrumbs="$breadcrumbs" />

        {!! ld_apply_filters('crop_after_breadcrumbs', '') !!}

        <div class="space-y-6">
            <div class="rounded-md border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
                <div class="p-5 space-y-6 border-t border-gray-100 dark:border-gray-800 sm:p-6">
                    <form action="{{ route('admin.crop.store') }}" method="POST" enctype="multipart/form-data" id="validateCropForm">
                        @csrf
                        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">

                            <div class="space-y-1">
                                <label for="crop_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Crop Name') }} <span class="text-red-500">*</span></label>
                                <input type="text" name="crop_name" id="crop_name" required autofocus
                                    value="{{ old('crop_name') }}" placeholder="{{ __('Enter Crop Name') }}"
                                    class="form-control">
                            </div>                            

                            <div>
                                <x-inputs.combobox name="suitable_season[]" label="{{ __('Suitable Season') }}"
                                    placeholder="{{ __('Select Season') }}" 
                                    :options="collect($suitable_season)
                                    ->map(fn($name, $id) => ['value' => $name, 'label' => ucfirst($name)])"
                                    :searchable="false" required
                                    :multiple="true" />
                            </div>
                            
                            <div>
                                <x-inputs.combobox name="category[]" label="{{ __('Main Category') }}"
                                    placeholder="{{ __('Select Category') }}"  required
                                    :options="collect($categories)
                                        ->map(function ($categories) {
                                            return [
                                                'value' => $categories->id,
                                                'label' => ucfirst($categories->name),
                                            ];
                                        })
                                        ->values()
                                        ->toArray()"
                                    :searchable="false"
                                    :multiple="true" />
                            </div>

                            <div>
                                <x-inputs.combobox name="suitable_soil_types[]" label="{{ __('Suitable Soil Types') }}"
                                    placeholder="{{ __('Select Soil') }}"  required
                                    :options="collect($suitable_soil_types)
                                    ->map(fn($name, $id) => ['value' => $name, 'label' => ucfirst($name)])"
                                    :searchable="false"
                                    :multiple="true" />
                            </div>

                            <div>
                                <x-inputs.combobox name="suitable_land_types[]" label="{{ __('Suitable Land Types') }}"
                                    placeholder="{{ __('Select Land') }}" required
                                    :options="collect($suitable_land_types)
                                    ->map(fn($name, $id) => ['value' => $name, 'label' => ucfirst($name)])"
                                    :searchable="false"
                                    :multiple="true" />
                            </div>

                            <div>
                                <x-inputs.combobox name="suitable_water_types[]" label="{{ __('Suitable Water Types') }}"
                                    placeholder="{{ __('Select Water') }}" required
                                    :options="collect($suitable_water_types)
                                    ->map(fn($name, $id) => ['value' => $name, 'label' => ucfirst($name)])"
                                    :searchable="false"
                                    :multiple="true" />
                            </div>


                            <div class="space-y-1">
                                <label for="soil_ph_min" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Soil pH Min') }} <span class="text-red-500">*</span></label>
                                <input type="number" name="soil_ph_min" id="soil_ph_min" required autofocus
                                    value="{{ old('soil_ph_min') }}" placeholder="{{ __('e.g. 7.5') }}"                                    
                                    step="0.1" 
                                    min="0" 
                                    max="14" 
                                    class="form-control">
                            </div>

                            <div class="space-y-1">
                                <label for="soil_ph_max" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Soil pH Max') }} <span class="text-red-500">*</span></label>
                                <input type="number" name="soil_ph_max" id="soil_ph_max" required autofocus
                                    value="{{ old('soil_ph_max') }}" placeholder="{{ __('e.g. 7.5') }}"                                    
                                    step="0.1" 
                                    min="0" 
                                    max="14" 
                                    class="form-control">
                            </div>

                            <div class="space-y-1">
                                <label for="water_ph_min" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Water pH Min') }} <span class="text-red-500">*</span></label>
                                <input type="number" name="water_ph_min" id="water_ph_min" required autofocus
                                    value="{{ old('water_ph_min') }}" placeholder="{{ __('e.g. 7.5') }}"                                    
                                    step="0.1" 
                                    min="0" 
                                    max="14" 
                                    class="form-control">
                            </div>

                            <div class="space-y-1">
                                <label for="water_ph_max" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Water pH Max') }} <span class="text-red-500">*</span></label>
                                <input type="number" name="water_ph_max" id="water_ph_max" required autofocus
                                    value="{{ old('water_ph_max') }}" placeholder="{{ __('e.g. 7.5') }}"                                    
                                    step="0.1" 
                                    min="0" 
                                    max="14" 
                                    class="form-control">
                            </div>
                        </div>

                        <div class="mt-6">
                            <x-buttons.submit-buttons cancelUrl="{{ route('admin.crop.index') }}" />
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
