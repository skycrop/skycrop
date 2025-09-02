@extends('frontend.layouts.app')

@section('title')
    {{ $breadcrumbs['title'] }} | {{ config('app.name') }}
@endsection

@section('main-content')

@if(count($wishlistItems))
<div 
    x-data="cartPage()" 
    class="min-h-screen bg-green-50 py-10 px-4"
>
    <div class="max-w-7xl mx-auto px-4">
        
        <!-- Title -->
        <h1 class="text-3xl font-bold text-gray-800 mb-8">My Wishlist</h1>

        
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            <!-- CART ITEMS -->
            <div class="lg:col-span-2 space-y-6 bg-white rounded-xl shadow-md p-5">
                
                <template x-for="(item, index) in wishlistItems" :key="item.id">
                    <div class="flex flex-col sm:flex-row items-center bg-white border border-gray-300 transition p-5">
                        
                        <!-- IMAGE -->
                        <img :src="item.image" :alt="item.name"
                             class="w-28 h-28 rounded-lg object-cover border">

                        <!-- DETAILS -->
                        <div class="flex-1 sm:ml-6 mt-4 sm:mt-0">
                            <h2 class="text-lg font-semibold text-gray-900" x-text="item.name"></h2>                            
                        </div>
                    </div>
                </template>

            </div>

            <!-- ORDER SUMMARY -->
            <div class="bg-white rounded-xl shadow-md p-6 h-fit sticky top-6">
                
            </div>
        </div>
        
    </div>
</div>

@else  

<div class="max-w-[1440px] mx-auto px-6 py-8 gap-6 text-gray-800">
    <div 
        class="min-h-[300px] flex flex-col items-center justify-center space-y-8 bg-white p-10 rounded-xl shadow-lg max-w-6xl mx-auto"
        x-data 
        x-init="$el.classList.add('opacity-100', 'translate-y-0')"
        class="opacity-0 translate-y-6 transition-all duration-700 ease-out"
    >
        <!-- Animated Icon / SVG or Image -->
        <div class="flex space-x-6">
            <img src="https://images.unsplash.com/photo-1747227825543-5da2c9f03222?q=80&w=180" alt="Sample Product 1" 
                class="rounded-lg shadow-lg transform hover:scale-105 transition-transform duration-300" />
            <img src="https://images.unsplash.com/photo-1747738307140-103b6c352499?q=80&w=180" alt="Sample Product 2" 
                class="rounded-lg shadow-lg transform hover:scale-105 transition-transform duration-300" />
            <img src="https://images.unsplash.com/photo-1753973975182-6f15378aac7e?q=80&w=180" alt="Sample Product 3" 
                class="rounded-lg shadow-lg transform hover:scale-105 transition-transform duration-300" />
        </div>

        <!-- Message -->
        <h2 class="text-2xl font-bold text-gray-800">Your favorite items list is empty</h2>
        <p class="text-gray-500 max-w-md text-center">Looks like you haven't added any products yet. Start shopping to find your favorite items!</p>

        <!-- Shop Now Button -->
        <a href="{{ route('index') }}" 
        class="inline-block bg-green-600 text-white font-semibold px-8 py-3 rounded-lg shadow-lg hover:bg-green-700 transition duration-300 transform hover:-translate-y-1"
        aria-label="Start Shopping"
        >
            Start Shopping
        </a>
    </div>

</div>  


@endif



@endsection

@php    
    $items = $wishlistItems->map(function($item) {
        return [
            'id' => $item->id,
            'name' => $item->product->name,            
            'image' => asset('storage/' . $item->product->cover_image),            
        ];
    });    
@endphp


@push('scripts')
<script>
function cartPage() {
    return {        
        wishlistItems: @json($items),
    }
}
</script>
@endpush