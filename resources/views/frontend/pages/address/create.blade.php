@extends('frontend.layouts.app')

@section('title')
    {{ $breadcrumbs['title'] }} | {{ config('app.name') }}
@endsection

@section('main-content')
<div class="min-h-screen bg-green-50 py-10 px-4">
  <div class="max-w-7xl min-h-150 mx-auto bg-white shadow-xl rounded-2xl overflow-hidden flex">
        
        <!-- Sidebar -->
        @include('frontend.layouts.partials.sidebar')

        <!-- Main Content -->
        <div class="flex-1 p-8">
            <h1 class="text-2xl font-bold text-green-700 mb-6">Shipping Addresses</h1>

            <div class="flex">
                <!-- Left Section -->
                <div class="w-1/2 p-4">
                    <form method="POST" action="{{ route('user.addresses.store') }}" class="space-y-6" id="validateAddressForm">
                        @csrf

                        <input type="hidden" name="latitude" id="latitude" value="">
                        <input type="hidden" name="longitude" id="longitude" value="">
                        <div>
                            <label for="full_name" class="block text-sm font-medium text-gray-700">Full Name</label>
                            <input type="text" name="full_name" id="full_name" 
                                value="{{ old('full_name') }}" required
                                class="mt-1 w-full rounded-lg border border-gray-300 p-2 shadow-sm focus:border-green-500 focus:ring-green-500">
                            @error('full_name')
                            <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="address_line_1" class="block text-sm font-medium text-gray-700">Address Line 1</label>
                            <input type="text" name="address_line_1" id="address_line_1" 
                                value="{{ old('address_line_1') }}" required
                                class="mt-1 w-full rounded-lg border border-gray-300 p-2 shadow-sm focus:border-green-500 focus:ring-green-500">
                            @error('address_line_1')
                            <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="address_line_2" class="block text-sm font-medium text-gray-700">Address Line 2</label>
                            <input type="text" name="address_line_2" id="address_line_2" 
                                value="{{ old('address_line_2') }}"
                                class="mt-1 w-full rounded-lg border border-gray-300 p-2 shadow-sm focus:border-green-500 focus:ring-green-500">
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-1 gap-4">
                            <div>
                                <label for="city" class="block text-sm font-medium text-gray-700">City</label>
                                <input type="text" name="city" id="city" 
                                    value="{{ old('city') }}" required
                                    class="mt-1 w-full rounded-lg border border-gray-300 p-2 shadow-sm focus:border-green-500 focus:ring-green-500">
                                @error('city')
                                <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="state" class="block text-sm font-medium text-gray-700">State</label>
                                <input type="text" name="state" id="state" 
                                    value="{{ old('state') }}" required
                                    class="mt-1 w-full rounded-lg border border-gray-300 p-2 shadow-sm focus:border-green-500 focus:ring-green-500">
                                @error('state')
                                <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="zipcode" class="block text-sm font-medium text-gray-700">Zip Code</label>
                                <input type="text" name="zipcode" id="zipcode" 
                                    value="{{ old('zipcode') }}" required
                                    class="mt-1 w-full rounded-lg border border-gray-300 p-2 shadow-sm focus:border-green-500 focus:ring-green-500">
                                @error('zipcode')
                                <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div>
                            <label for="phone" class="block text-sm font-medium text-gray-700">Phone Number</label>
                            <input type="tel" name="phone" id="phone" 
                                value="{{ old('phone') }}" required
                                class="mt-1 w-full rounded-lg border border-gray-300 p-2 shadow-sm focus:border-green-500 focus:ring-green-500">
                            @error('phone')
                            <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex items-center space-x-3">
                            <input type="checkbox" name="is_default" id="is_default" class="h-4 w-4 text-green-600 border-gray-300 rounded">
                            <label for="is_default" class="text-sm font-medium text-gray-700">Set as default address</label>
                        </div>

                        <button type="submit" 
                                class="w-full bg-green-600 text-white rounded-lg py-3 font-semibold hover:bg-green-700 transition">
                            Save Address
                        </button>
                    </form>
                </div>

                <!-- Right Section -->
                <div class="w-1/2 p-4">
                    <div class="space-y-1">
                        <div id="map" style="height: 650px; width: 100%;" class="rounded border mt-4"></div>
                    </div>
                </div>
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
                    console.log(components);
                    

                    // Helper to get component by type
                    const getComponent = (type) => {
                        let comp = components.find(c => c.types.includes(type));
                        return comp ? comp.long_name : '';
                    };

                    // Set values
                    document.getElementById('address_line_1').value = [
                        getComponent('premise'),
                        getComponent('sublocality_level_3'),
                        getComponent('sublocality_level_2'),
                        getComponent('sublocality_level_1'),
                    ].filter(Boolean).join(', ');

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