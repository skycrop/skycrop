@extends('backend.layouts.app')

@section('title')
    {{ $breadcrumbs['title'] }} | {{ config('app.name') }}
@endsection

@section('admin-content')
    <div class="p-4 mx-auto max-w-7xl md:p-6">
        <x-breadcrumbs :breadcrumbs="$breadcrumbs" />

        {!! ld_apply_filters('brand_after_breadcrumbs', '') !!}

        <div class="space-y-6">
            <div class="rounded-md border border-gray-200 bg-white dark:border-gray-800 dark:bg-gray-900">
                <div class="p-5 space-y-6 border-t border-gray-100 dark:border-gray-800 sm:p-6">
                    <form action="{{ route('admin.promocode.update', $promocode->id) }}" method="POST" class="space-y-6"
                        id="validatePromoForm"
                        enctype="multipart/form-data">
                        @method('PUT')
                        @csrf
                        
                        <input type="hidden" id="is_edit_mode" value="1">
                        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                            <div class="space-y-1">
                                <label for="code" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Promo Code') }} <span class="text-red-500">*</span></label>
                                <input type="text" name="code" id="code" readonly
                                    value="{{ $promocode->code }}" placeholder="{{ __('Enter Promo Code') }}"
                                    class="form-control">
                            </div>
                            
                            <div class="space-y-1">
                                <label for="per_person_usage" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Per Person Use') }} <span class="text-red-500">*</span></label>
                                <input type="text" name="per_person_usage" id="per_person_usage" autofocus
                                    value="{{ $promocode->per_person_usage }}" placeholder="{{ __('Per Person Use') }}"
                                    class="form-control">
                            </div>

                            <div class="space-y-1">
                                <label for="discount_type" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Discount Type') }} <span class="text-red-500">*</span></label>
                                <select name="discount_type" class="form-control mt-1">                                    
                                    <option value="fixed" @if($promocode->discount_type == 'fixed') selected @endif>Fixed</option>
                                    <option value="percent" @if($promocode->discount_type == 'percent') selected @endif>Percent</option>
                                </select>

                            </div>

                            <div class="space-y-1">
                                <label for="is_active" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Status') }} <span class="text-red-500">*</span></label>
                                <select name="is_active" class="form-control mt-1">            
                                    <option value="1" @if($promocode->is_active == '1') selected @endif>Active</option>
                                    <option value="0" @if($promocode->is_active == '0') selected @endif>In Active</option>                                    
                                </select>
                            </div>

                            <div class="space-y-1">
                                <label for="start_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Start Date') }} <span class="text-red-500">*</span></label>
                                <input name="start_date" value="{{ $promocode->start_date }}" type="text" class="form-control custom-datepicker" />
                            </div>
                            <div class="space-y-1">
                                <label for="end_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('End Date') }} <span class="text-red-500">*</span></label>
                                <input name="end_date" value="{{ $promocode->end_date }}" type="text" class="form-control custom-datepicker" />
                            </div>

                            <div class="space-y-1">
                                <label for="max_discount_amount" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Maximim Discount (Flat price)') }} <span class="text-red-500">*</span></label>
                                <input type="text" name="max_discount_amount" id="max_discount_amount" autofocus
                                    value="{{ $promocode->max_discount_amount }}" placeholder="{{ __('Enter Amount') }}"
                                    class="form-control">
                            </div>
                            <div class="space-y-1">
                                <label for="min_cart_amount" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('​Minimum Cart Amount') }} <span class="text-red-500">*</span></label>
                                <input type="text" name="min_cart_amount" id="min_cart_amount" autofocus
                                    value="{{ $promocode->min_cart_amount }}" placeholder="{{ __('Enter Minimum Cart Amount') }}"
                                    class="form-control">
                            </div>
                            
                            <div class="space-y-1">
                                <label for="discount_amount" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Discount Amount/Precent') }} <span class="text-red-500">*</span></label>
                                <input type="text" name="discount_amount" id="discount_amount" autofocus
                                    value="{{ $promocode->discount_amount }}" placeholder="{{ __('Amount/Precent') }}"
                                    class="form-control">
                            </div>
                            
                            <div class="space-y-1">
                                <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Description') }} <span class="text-red-500">*</span></label>
                                <input type="text" name="description" id="description" autofocus
                                    value="{{ $promocode->description }}" placeholder="{{ __('Amount/Precent') }}"
                                    class="form-control">
                            </div>
                            
                        </div> 

                        

                        <div class="mt-6">
                            <x-buttons.submit-buttons cancelUrl="{{ route('admin.promocode.index') }}" />
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
