@extends('frontend.layouts.app')

@section('title')
    {{ $breadcrumbs['title'] }} | {{ config('app.name') }}
@endsection

@section('main-content')


<div class="max-w-[1440px] mx-auto p-6 gap-6">
    <div class="swiper category-swiper my-5">
        <div class="swiper-wrapper"> 
            @foreach ($categories as $item)                
                <div class="swiper-slide">
                    <a href="{{ route('collection.product.search', ['category' => $item->name]) }}"
                    class="category-box block text-center p-6 rounded-xl bg-gray-100 transition duration-300 hover:bg-green-500 group h-full">
                    <div class="flex flex-col items-center justify-between h-full min-h-[110PX]">
                        <img src="{{ asset('storage/'.$item->photo) }}"
                            class="h-12 w-12 mb-3 transition duration-300 group-hover:invert group-hover:brightness-0 group-hover:filter"
                            alt="Category Icon" />
                        <h5 class="text-gray-800 font-medium transition duration-300 group-hover:text-white text-center line-clamp-2">
                        {{ $item->name }}
                        </h5>
                    </div>
                    </a>
                </div>
            @endforeach   
        </div>
    </div>
</div>

<div class="flex max-w-[1440px] mx-auto p-6 gap-6">

    <!-- Sidebar Filters -->
    <aside class="w-72 bg-white border rounded-lg shadow-sm p-4">
        <h2 class="text-xl font-bold mb-4">Filters</h2>

        <!-- Categories -->
        <div class="mb-6">
            <h3 class="font-semibold text-sm text-gray-600 mb-2">CATEGORIES</h3>
            @foreach($categories as $cat)
            <label class="flex items-center gap-2 text-sm text-gray-700">
            <input type="checkbox" value="{{ $cat->id }}" class="accent-green-600">
            {{ $cat->name }}
            </label>
            @endforeach

        </div>

        <!-- Brands -->
        <div class="mb-6">
            <h3 class="font-semibold text-sm text-gray-600 mb-2">BRANDS</h3>
            <!-- <input type="text" placeholder="Search Brands"
                class="w-full border border-gray-300 rounded-md px-3 py-1.5 text-sm mb-2 focus:outline-none focus:ring focus:ring-green-100" /> -->
            <div class="h-32 overflow-y-auto pr-1">
            <!-- Example brands -->
                @foreach($brands as $brand)
                <label class="flex items-center gap-2 text-sm text-gray-700 mb-1">
                    <input type="checkbox" value="{{ $brand->id }}" class="accent-green-600"> {{ $brand->name }}
                </label>            
                @endforeach
            </div>
        </div>

        <!-- Price Range -->
        <div class="mb-6">
            <h3 class="font-semibold text-sm text-gray-600 mb-2">PRICE</h3>
            <input id="priceRange" type="range" min="0" max="20000" step="100" value="0"
                class="w-full accent-green-600 mb-2">
            <div class="flex items-center justify-between text-sm">
            <span id="minPrice">₹0</span>
            <span>To</span>
            <span id="maxPrice">₹20000</span>
            </div>
        </div>

        <!-- Rating -->
        <div class="mb-6">
            <h3 class="font-semibold text-sm text-gray-600 mb-2">RATING</h3>
            @for($i = 4; $i > 1; $i--)
            <label class="flex items-center gap-2 text-sm text-gray-700 mb-1">
                <input type="checkbox" value="{{ $i }}" class="accent-green-600">
                <span class="flex items-center gap-1">
                    {{ $i }} 
                    <img src="{{ asset('images/star.svg') }}" alt="Star" class="w-4 h-4 inline-block text-yellow-400">
                    And Above
                </span>
            </label>            
            @endfor
        </div>
    </aside>

    <!-- Main Content -->
    <main class="flex-1">
        <div class="bg-white rounded-lg shadow-sm border p-4 mb-4 flex flex-wrap justify-between items-center">
            <h2 class="text-base font-medium text-gray-700">Showing Results</h2>
            
            <div class="flex items-center gap-2 text-sm text-gray-600">
                <label for="sort" class="whitespace-nowrap">Sort By:</label>
                <select id="sort"
                        class="border border-gray-300 rounded-md px-3 py-2 text-sm focus:ring-green-200 focus:border-green-500">
                    <option selected>Best Selling</option>
                    <option>Price: Low to High</option>
                    <option>Price: High to Low</option>
                    <option>Newest First</option>
                    <option>Discount</option>
                </select>
            </div>
        </div>

        <!-- Product Grid -->
        @if(count($products) > 0)
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 lg:grid-cols-4 gap-6">      
            <!-- Product Card -->
            @foreach($products as $product)
            <x-product-card :productMeta="$product"  />
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="mt-8">
            {{ $products->links('pagination::tailwind') }}
        </div>
        @else
        <div class="mt-8">
            <div class="bg-white rounded-lg shadow-sm border p-4 mb-4 flex flex-wrap justify-between items-center">      
            No Products Available
            </div>
        </div>
        @endif
    </main>
</div>
   

@endsection


@push('scripts')
<script>
    const priceRange = document.getElementById('priceRange');
    const minPrice = document.getElementById('minPrice');
    const maxPrice = document.getElementById('maxPrice');

    priceRange.addEventListener('input', () => {
        const value = parseInt(priceRange.value);
        minPrice.innerText = `₹0`;
        maxPrice.innerText = `₹${value.toLocaleString()}`;
    });
    
</script>
@endpush