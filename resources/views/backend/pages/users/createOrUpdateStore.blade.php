@extends('backend.layouts.app')

@section('title')
    {{ $breadcrumbs['title'] }} | {{ config('app.name') }}
@endsection

@section('admin-content')
    <div class="p-4 mx-auto max-w-(--breakpoint-2xl) md:p-6">
        <x-breadcrumbs :breadcrumbs="$breadcrumbs" />

        {!! ld_apply_filters('users_after_breadcrumbs', '') !!}

        <div class="space-y-6">
            <div class="rounded-md border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
                <div class="p-5 space-y-6 border-t border-gray-100 dark:border-gray-800 sm:p-6">
                    <form action="{{ route('admin.users.save-store') }}" method="POST" enctype="multipart/form-data" >
                        @csrf
                        <input type="hidden" name="latitude" id="latitude" value="{{ $user->storeDetail->latitude ?? '' }}">
                        <input type="hidden" name="longitude" id="longitude" value="{{ $user->storeDetail->longitude ?? '' }}">
                        <input type="hidden" name="user_id" value="{{ $user->id }}">


                        <div class="grid grid-cols-1 gap-6 sm:grid-cols-1">
                            <div class="space-y-1">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                    Select Store Location on Map <span class="text-red-500">*</span>
                                </label>
                                <div id="map" style="height: 400px; width: 100%;" class="rounded border mt-4"></div>
                            </div>

                            <div class="space-y-1">
                                <label for="about_store"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Store Description') }} <span class="text-red-500">*</span></label>
                                <textarea  name="about_store" rows="6"
                                        class="form-control" autofocus
                                        placeholder="Short detail about store...">{{ $user->storeDetail->about_store ?? '' }}</textarea>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 mt-6">
                            <div class="space-y-1">
                                <label for="store_name"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Store Name') }} <span class="text-red-500">*</span></label>
                                <input type="text" name="store_name" id="store_name" required 
                                    value="{{ $user->storeDetail->store_name ?? '' }}" placeholder="{{ __('Store Name') }}"
                                    class="form-control">
                            </div>

                            <div class="space-y-1">
                                <label for="phone"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Store Contact') }} <span class="text-red-500">*</span></label>
                                <input type="text" name="phone" required 
                                    value="{{ $user->storeDetail->phone ?? '' }}" placeholder="{{ __('Store Contact') }}"
                                    class="form-control">
                            </div>

                            <div class="space-y-1">
                                <label for="store_address"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Store Address') }} <span class="text-red-500">*</span></label>
                                <input type="text" name="store_address" id="store_address" required 
                                    value="{{ $user->storeDetail->store_address ?? '' }}" placeholder="{{ __('Store Address') }}"
                                    class="form-control">
                            </div>

                            <div class="space-y-1">
                                <label for="city"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('City') }} <span class="text-red-500">*</span></label>
                                <input type="text" name="city" id="city" required 
                                    value="{{ $user->storeDetail->city ?? '' }}" placeholder="{{ __('City') }}"
                                    class="form-control">
                            </div>

                            <div class="space-y-1">
                                <label for="state"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('State') }} <span class="text-red-500">*</span></label>
                                <input type="text" name="state" id="state" required 
                                    value="{{ $user->storeDetail->state ?? '' }}" placeholder="{{ __('State') }}"
                                    class="form-control">
                            </div>

                            <div class="space-y-1">
                                <label for="zipcode"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Zip Code') }} <span class="text-red-500">*</span></label>
                                <input type="text" name="zipcode" id="zipcode" required 
                                    value="{{ $user->storeDetail->zipcode ?? '' }}" placeholder="{{ __('Zip Code') }}"
                                    class="form-control">
                            </div>

                            <div class="space-y-1">
                                <x-inputs.file-input
                                    name="store_logo"
                                    id="store_logo"
                                    accept="image/*"
                                    label="{{ __('Store Logo') }}"                                                                                                            
                                    class="mt-1"
                                    :existingAttachment="$user->storeDetail->store_logo ?? '' && !empty($user->storeDetail->store_logo) ? asset('storage/'.$user->storeDetail->store_logo) : null"
                                >                                    
                                </x-inputs.file-input>
                            </div>
                            
                            
                            {!! ld_apply_filters('after_username_field', '', null) !!}
                        </div>
                        

                        <div class="mt-6">
                            <x-buttons.submit-buttons cancelUrl="{{ route('admin.users.index') }}" />
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script src="https://maps.googleapis.com/maps/api/js?key={{env('YOUR_GOOGLE_MAPS_API_KEY')}}&libraries=marker"></script>
<script>
    function initMap() {
        // Default location (India)
        var defaultLocation = {
             lat: {{ $user->storeDetail->latitude ?? '26.8970526' }},
             lng: {{ $user->storeDetail->longitude ?? '75.755405' }}
        };

        // Create map centered on default location
        var map = new google.maps.Map(document.getElementById('map'), {
            zoom: 13,
            center: defaultLocation,
            mapId: "{{ env('YOUR_MAP_ID')}}"
        });

        let advancedMarker;
        var geocoder = new google.maps.Geocoder();

        new google.maps.marker.AdvancedMarkerElement({
            position: defaultLocation,
            map: map,
            title: "{{ $user->storeDetail->store_name ?? '' }}"
        });

        map.addListener('click', (event) => {           
            
            const lat = event.latLng.lat();
            const lng = event.latLng.lng();

            // Set hidden input values
            document.getElementById('latitude').value = lat;
            document.getElementById('longitude').value = lng;

            // Remove existing marker if any
            if (advancedMarker) {
                advancedMarker.setMap(null);
            }

            // Use AdvancedMarkerElement from the marker library
            advancedMarker = new google.maps.marker.AdvancedMarkerElement({
                map: map,
                position: { lat: lat, lng: lng }
            });

            var latlng = { lat: lat, lng: lng };
            geocoder.geocode({ location: latlng }, function (results, status) {
                if (status === "OK" && results[0]) {
                    let components = results[0].address_components;

                    // Helper to get component by type
                    const getComponent = (type) => {
                        let comp = components.find(c => c.types.includes(type));
                        return comp ? comp.long_name : '';
                    };

                    // Set values
                    document.getElementById('store_address').value = results[0].formatted_address;
                    document.getElementById('city').value = getComponent('locality');
                    document.getElementById('state').value = getComponent('administrative_area_level_1');
                    document.getElementById('zipcode').value = getComponent('postal_code');
                } else {
                    console.error("Geocoder failed:", status);
                }
            });

        });
    }

    window.onload = initMap;
</script>


@endpush