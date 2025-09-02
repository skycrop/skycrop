@extends('backend.layouts.app')

@section('title')
    {{ $breadcrumbs['title'] }} | {{ config('app.name') }}
@endsection

@section('admin-content')
    <div class="p-4 mx-auto max-w-(--breakpoint-2xl) md:p-6">
        <x-breadcrumbs :breadcrumbs="$breadcrumbs" />

        {!! ld_apply_filters('brand_after_breadcrumbs', '') !!}

        <div class="space-y-6">
            <div class="rounded-md border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
                <div class="p-5 space-y-6 border-t border-gray-100 dark:border-gray-800 sm:p-6">
                    <form action="{{ route('admin.brand.store') }}" method="POST" enctype="multipart/form-data" id="validateBrandForm">
                        @csrf
                        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">

                            <div class="space-y-1">
                                <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Brand Name') }} <span class="text-red-500">*</span></label>
                                <input type="text" name="name" id="name" autofocus
                                    value="{{ old('name') }}" placeholder="{{ __('Enter Brand Name') }}"
                                    class="form-control">
                            </div>
                        </div>

                        <div class="mt-6">
                            <x-buttons.submit-buttons cancelUrl="{{ route('admin.brand.index') }}" />
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection