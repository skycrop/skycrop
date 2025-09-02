@extends('backend.layouts.app')

@section('title')
    {{ $breadcrumbs['title'] }} | {{ config('app.name') }}
@endsection

@section('admin-content')
    <div class="p-4 mx-auto max-w-7xl md:p-6">
        <x-breadcrumbs :breadcrumbs="$breadcrumbs" />

        {!! ld_apply_filters('collection_after_breadcrumbs', '') !!}

        <div class="space-y-6">
            <div class="rounded-md border border-gray-200 bg-white dark:border-gray-800 dark:bg-gray-900">
                <div class="p-5 space-y-6 border-t border-gray-100 dark:border-gray-800 sm:p-6">
                    <form action="{{ route('admin.collection.update', $collection->id) }}" method="POST" class="space-y-6"
                        enctype="multipart/form-data"
                        id="validateCollectionForm">
                        @method('PUT')
                        @csrf
                        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                            <div class="space-y-1">
                                <label for="name"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Collection Name') }} <span class="text-red-500">*</span></label>
                                <input type="text" name="name" id="name" required value="{{ $collection->name }}"
                                    placeholder="{{ __('Enter Collection Name') }}" class="form-control">
                            </div>
                            
                            <div>
                                <x-inputs.combobox name="level" label="{{ __('Collection Level') }}"
                                    placeholder="{{ __('Select Level') }}" :options="collect($level)
                                        ->map(fn($name, $id) => ['value' => $id + 1, 'label' => ucfirst($name)])
                                        ->values()
                                        ->toArray()" :selected="$collection->level"
                                    :searchable="false" required />
                            </div>

                            <div>
                                <x-inputs.combobox name="parent_id" label="{{ __('Parent Collection (if any)') }}" 
                                    placeholder="{{ __('Select Parent Collection') }}" 
                                    :options="collect($collections)->map(function ($cat) {
                                        return [
                                            'value' => $cat->id,
                                            'label' => str_repeat('-- ', $cat->level) . ucfirst($cat->name),
                                        ];
                                    })->values()->toArray()"
                                    :selected="$collection->parent_id"
                                    :searchable="false" />
                            </div>

                            <div>
                                <x-inputs.combobox name="type_id" label="{{ __('Select Type (if any)') }}" 
                                    placeholder="{{ __('Select Type') }}" 
                                    :options="collect($collectionsType)->map(function ($type) {
                                        return [
                                            'value' => $type->id,
                                            'label' => str_repeat('-- ', 0) . ucfirst($type->name),
                                        ];
                                    })->values()->toArray()"
                                    :selected="$collection->type_id"
                                    :searchable="false" />
                            </div>

                            {!! ld_apply_filters('after_collection_field', '', $collection) !!}
                        </div>
                        <div class="mt-6">
                            <x-buttons.submit-buttons cancelUrl="{{ route('admin.collection.index') }}" />
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
