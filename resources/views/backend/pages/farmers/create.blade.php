@extends('backend.layouts.app')

@section('title')
    {{ $breadcrumbs['title'] }} | {{ config('app.name') }}
@endsection

@section('admin-content')
    <div class="p-4 mx-auto max-w-(--breakpoint-2xl) md:p-6">
        <x-breadcrumbs :breadcrumbs="$breadcrumbs" />

        {!! ld_apply_filters('farmer_after_breadcrumbs', '') !!}

        <div class="space-y-6">
            <div class="rounded-md border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
                <div class="p-5 space-y-6 border-t border-gray-100 dark:border-gray-800 sm:p-6">
                    <form action="{{ route('admin.farmer.store') }}" method="POST" enctype="multipart/form-data" id="validateFarmerForm">
                        @csrf
                        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                            <div class="space-y-1">
                                <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Name') }} <span class="text-red-500">*</span></label>
                                <input type="text" name="name" id="name" required autofocus
                                    value="{{ old('name') }}" placeholder="{{ __('Enter Name') }}"
                                    class="form-control">
                            </div>

                            <div class="space-y-1">
                                <label for="phone_number" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Phone Number') }} <span class="text-red-500">*</span></label>
                                <input type="text" name="phone_number" id="phone_number" required autofocus
                                    value="{{ old('phone_number') }}" placeholder="{{ __('Enter Phone Number') }}"
                                    class="form-control">
                            </div>

                            <div class="space-y-1">
                                <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Email') }} <span class="text-red-500">*</span></label>
                                <input type="text" name="email" id="email" required autofocus
                                    value="{{ old('email') }}" placeholder="{{ __('Enter Email') }}"
                                    class="form-control">
                            </div>

                            <div class="space-y-1">
                                <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Password') }} <span class="text-red-500">*</span></label>
                                <input type="text" name="password" id="password" required autofocus
                                    value="{{ old('password') }}" placeholder="{{ __('Enter Password') }}"
                                    class="form-control">
                            </div>

                            <div class="space-y-1">
                                <label for="referral_code" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Referral Code') }}</label>
                                <input type="text" name="referral_code" id="referral_code" autofocus
                                    value="{{ old('referral_code') }}" placeholder="{{ __('Enter Referral Code') }}"
                                    class="form-control">
                            </div>

                            <div class="space-y-1">
                                <label for="city" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('City Name') }} <span class="text-red-500">*</span></label>
                                <input type="text" name="city" id="city" required autofocus
                                    value="{{ old('city') }}" placeholder="{{ __('Enter City Name') }}"
                                    class="form-control">
                            </div>

                            <div class="space-y-1">
                                <label for="state" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('State Name') }} <span class="text-red-500">*</span></label>
                                <input type="text" name="state" id="state" required autofocus
                                    value="{{ old('state') }}" placeholder="{{ __('Enter State Name') }}"
                                    class="form-control">
                            </div>

                            <div class="space-y-1">
                                <label for="country" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Country Name') }} <span class="text-red-500">*</span></label>
                                <input type="text" name="country" id="country" required autofocus
                                    value="{{ old('country') }}" placeholder="{{ __('Enter Country Name') }}"
                                    class="form-control">
                            </div>

                            <div class="space-y-1">
                                <label for="address" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Complete Address') }} <span class="text-red-500">*</span></label>
                                <input type="text" name="address" id="address" required autofocus
                                    value="{{ old('address') }}" placeholder="{{ __('Enter Complete Address') }}"
                                    class="form-control">
                            </div>

                            <div class="space-y-1">
                                <div class="space-y-1">
                                    <x-inputs.file-input
                                        name="photo"
                                        id="photo"
                                        accept="image/*"
                                        label="{{ __('Profile Image') }}"                                                                                                            
                                        class="mt-1"
                                    >                                    
                                    </x-inputs.file-input>
                                </div>
                            </div>
                        </div>

                        <div class="mt-6">
                            <x-buttons.submit-buttons cancelUrl="{{ route('admin.farmer.index') }}" />
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
