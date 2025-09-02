@extends('frontend.layouts.app')

@section('title')
    {{ $breadcrumbs['title'] }} | {{ config('app.name') }}
@endsection

@section('main-content')
@php
    $chemicals = $productDetail->tagsData ? $productDetail->tagsData->pluck('name')->implode(', ') : 'N/A';
    $variants = collect($productDetail->variants);
    $selectedVariant = 'Multi Pack';
    if($variants->contains(function ($variant) {
        return trim($variant->variant_name) === 'Single Pack';
    })){
      $selectedVariant = 'Single Pack';
    }

    $galleryImages = json_decode($productDetail->images ?? '[]', true);
@endphp
{{-- Header Section --}}
<section class="bg-gray-50 h-24 flex items-center">
    <div class="container mx-auto ">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between">
            <!-- Title -->
            <h2 class="text-2xl font-semibold text-gray-800">
                {{ $productDetail->name }}
            </h2>

            <!-- Breadcrumb -->
            <nav class="mt-2 md:mt-0">
                <ol class="flex items-center text-gray-600 text-sm space-x-2">
                    <li>
                      <a href="{{ route('index') }}" class="hover:underline">Home</a>
                    </li>
                    <li><span>/</span></li>
                    <li class="text-gray-800 font-medium">
                        <a href="#" class="hover:underline">{{ $productDetail->category->name }}</a>
                    </li>
                    <li><span>/</span></li>
                    <li class="text-gray-800 font-medium">
                        <a href="#" class="hover:underline">{{ $productDetail->name }}</a>
                    </li>
                </ol>
            </nav>
        </div>
    </div>
</section>


{{-- Product Section --}}
<section class="container mx-auto py-8">
  <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">    
    <!-- Product Info -->
    <div class="lg:col-span-3">    
      <div class="flex flex-col lg:flex-row gap-6">
        <!-- Left: Images -->
        <div class="items-center w-1/2 space-y-4 border p-3 max-h-110">        
            <div class="flex gap-4">
              <!-- Thumbnails -->
              <div class="w-20">
                <div class="swiper thumbsSwiper h-full">
                  <div class="swiper-wrapper flex flex-col">
                    <div class="swiper-slide">
                      <img src="{{ asset('storage/' . $productDetail->cover_image) }}" class="rounded-lg border" />
                    </div>
                    @foreach ($galleryImages as $item)
                    <div class="swiper-slide">
                      <img src="{{ asset('storage/' . $item) }}" class="rounded-lg border" />
                    </div>                      
                    @endforeach                  
                  </div>
                </div>
              </div>

              <!-- Main Image -->
              <div class="w-80">
                <div class="swiper mainSwiper">
                  <div class="swiper-wrapper">
                    <div class="swiper-slide">
                      <img src="{{ asset('storage/' . $productDetail->cover_image) }}" class="w-full" />
                    </div>
                    @foreach ($galleryImages as $item)
                    <div class="swiper-slide">
                      <img src="{{ asset('storage/' . $item) }}" class="w-full" />
                    </div>
                    @endforeach                  
                  </div>
                </div>
              </div>
            </div>        
        </div>
        
        <!-- Right: Main Image + Details -->
        <div x-data="productPage();" class="w-1/2">
          <span class="bg-red-100 text-red-600 text-sm font-semibold px-3 py-1 rounded"><span x-text="discountPercent"></span>% OFF</span>
          <h2 class="text-2xl font-bold text-gray-800 mt-2">{{ $productDetail->name }}</h2>

          <!-- Price -->
          <div class="flex items-center space-x-2 mt-2">
            <span class="text-xl font-bold text-orange-600">₹<span x-text="price"></span></span>
            <span class="text-gray-500 line-through">₹<span x-text="mrp"></span></span>
            <span class="text-sm text-orange-600">(<span x-text="discountPercent"></span>% OFF)</span>
          </div>

          <!-- Rating -->
          <div class="flex items-center mt-2 text-yellow-500">
            @for($i = 0; $i < 5; $i++)
            <img src="{{ asset('images/star.svg') }}" alt="Star" class="w-4 h-4 inline-block text-yellow-400">
            @endfor
            <span class="text-sm font-medium text-gray-600"> {{ $productDetail->average_rating }} ({{ $productDetail->total_reviews }} Reviews)</span>
          </div>

          <!-- Description -->
          <p class="text-gray-600 mt-4">
            Meta Description Meta Description Meta Description Meta Description Meta Description Meta Description Meta Description ...
          </p>

          <!-- Variants -->
          <div class="mt-6">
            <h4 class="font-semibold text-gray-800">Size</h4>
            <div class="flex flex-wrap gap-3 mt-2">                          
              <template x-for="(v, index) in filteredVariants" :key="index"> 
              <button
                @click="selectedVariant = v"
                :class="selectedVariant === v ? 'border-green-500 bg-green-500 text-white' : 'border-gray-200'"
                class="px-3 py-1  border rounded"><span x-text="v.size"></span>
              </button>
              </template>
            </div>
          </div>

          <!-- Add To Cart -->
          <div class="space-y-4 mt-6">
            <!-- Quantity & Add to Cart -->
            <div x-data="{ qty: 0 }" class="flex items-center gap-4">
              {{-- <template x-if="qty === 0"> --}}
                  <button 
                          :disabled="isLoading"
                          @click="isLoading = true; qty = 1; addToCart({{ $productDetail->id }})" 
                          class="flex-1 flex items-center justify-center gap-2 bg-black text-white font-semibold py-3 rounded-md shadow transition duration-200">
                          <template x-if="isLoading">
                              <svg class="w-5 h-5 animate-spin text-white" fill="none" viewBox="0 0 24 24">
                                  <circle class="opacity-25" cx="12" cy="12" r="10" 
                                          stroke="currentColor" stroke-width="4"></circle>
                                  <path class="opacity-75" fill="currentColor" 
                                        d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z">
                                  </path>
                              </svg>
                          </template>
                          <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2 9h14l-2-9M9 21a2 2 0 100-4 2 2 0 000 4zm8 0a2 2 0 100-4 2 2 0 000 4z" />
                          </svg>
                      Add To Cart
                  </button>
              {{-- </template> --}}

              {{-- <template x-if="qty > 0">
                  <div class="flex-1 flex items-center gap-2">
                      <button @click="qty = Math.max(qty-1,0)" 
                              class="w-15 p-2 rounded-sm bg-black hover:bg-white text-white hover:text-black border">−</button>
                      <input type="text" readonly x-model="qty" class="w-20 text-center border border-gray-200 rounded" />
                      <button @click="qty++" 
                              class="w-15 p-2 rounded-sm bg-black hover:bg-white text-white hover:text-black border">+</button>
                  </div>
              </template> --}}

              <button
              :disabled="isWLoading"
              @click="isWLoading = true; qty = 1; addToWishlist({{ $productDetail->id }})" 
              class="flex-1 flex items-center justify-center gap-2 bg-red-500 hover:bg-red-600 text-white font-semibold py-3 rounded-md shadow transition duration-200"
              >
              <template x-if="isWLoading">
                  <svg class="w-5 h-5 animate-spin text-white" fill="none" viewBox="0 0 24 24">
                      <circle class="opacity-25" cx="12" cy="12" r="10" 
                              stroke="currentColor" stroke-width="4"></circle>
                      <path class="opacity-75" fill="currentColor" 
                            d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z">
                      </path>
                  </svg>
              </template>
              <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2"
                  viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round"
                  d="M4.318 6.318a4.5 4.5 0 0 1 6.364 0L12 7.636l1.318-1.318
                  a4.5 4.5 0 1 1 6.364 6.364L12 21.364l-7.682-7.682a4.5 4.5
                  0 0 1 0-6.364z" />
              </svg>
              Add To Wishlist
              </button>
            </div>

            <!-- Stock Progress -->
            <template x-if="stock < 6">
            <div>
              <p class="text-sm font-medium">Please hurry! Only <span x-text="stock"></span> left in stock</p>
              <div class="w-full h-2 bg-gray-200 rounded-full overflow-hidden mt-2">
                <div class="h-2 bg-orange-400 bg-stripes animate-pulse" style="width:50%"></div>
              </div>
            </div>
            </template>

          </div>

          <div class="bg-gray-50 p-4 rounded-lg shadow-sm  mt-6">
            <ul class="space-y-2 text-sm text-gray-700">
              <li class="flex items-center">
                <span class="w-2 h-2 bg-gray-500 rounded-full mr-2"></span>
                <span class="text-gray-500 font-medium">Brand :</span>
                <span class="ml-1">{{ $productDetail->brand->name }}</span>
              </li>
              <li class="flex items-center">
                <span class="w-2 h-2 bg-gray-500 rounded-full mr-2"></span>
                <span class="text-gray-500 font-medium">SKU :</span>
                <span class="ml-1"><span x-text="sku"></span></span>
              </li>              
              <li class="flex items-center">
                <span class="w-2 h-2 bg-gray-500 rounded-full mr-2"></span>
                <span class="text-gray-500 font-medium">Stock :</span>
                <span :class="stock > 0 ? 'text-green-600' : 'text-red-600'" class="ml-1" x-text="stock > 0 ? 'In Stock' : 'Out of Stock'"></span>
              </li>
              <li class="flex items-center">
                <span class="w-2 h-2 bg-gray-500 rounded-full mr-2"></span>
                <span class="text-gray-500 font-medium">Tags :</span>
                <span class="ml-1">{{ $chemicals }}</span>
              </li>
            </ul>
          </div>

          <div class="mt-6">
            <h3 class="text-lg font-semibold mb-3">Guaranteed Safe Checkout</h3>
            <div class="flex space-x-3">
              <div class="bg-gray-50 border rounded-lg p-2">
                <img src="{{ asset('images/frontend/razorpay-logo.png') }}" alt="Razorpay" class="h-8">
              </div>
              <div class="bg-gray-50 border rounded-lg p-2">
                <img src="{{ asset('images/frontend/mastercard-logo.png') }}" alt="Card Payment" class="h-8">
              </div>
              <div class="bg-gray-50 border rounded-lg p-2">
                <img src="{{ asset('images/frontend/visa-logo.png') }}" alt="UPI" class="h-8">
              </div>
              <div class="bg-gray-50 border rounded-lg p-2">
                <img src="{{ asset('images/frontend/upi-logo.png') }}" alt="Cash on Delivery" class="h-8">
              </div>
            </div>
          </div>





        </div>
      </div>
      
      <!-- Description -->
      <div class="bg-white mt-6">
        <div x-data="{ tab: 'desc' }" class="w-full mx-auto mt-8">
          <!-- Tab Navigation -->
          <div class="flex border-b border-gray-200 bg-gray-50 overflow-hidden">
              <button
                  :class="tab === 'desc' 
                      ? 'text-green-600 border-t-3 border-green-500 bg-white shadow-sm' 
                      : 'text-gray-500 hover:text-green-500 border-t-3 border-transparent hover:border-green-300'"
                  class="flex-1 py-3 px-5 text-sm md:text-base font-semibold text-center transition-all duration-200 ease-in-out"
                  @click="tab = 'desc'"
                  type="button">
                  <span class="flex items-center justify-center gap-2">
                      <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 inline-block mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M7 8h10M7 12h6m-6 4h10M13 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2v-7"/>
                      </svg>
                      Description
                  </span>
              </button>

              <button
                  :class="tab === 'info' 
                      ? 'text-green-600 border-t-3 border-green-500 bg-white shadow-sm' 
                      : 'text-gray-500 hover:text-green-500 border-t-3 border-transparent hover:border-green-300'"
                  class="flex-1 py-3 px-5 text-sm md:text-base font-semibold text-center transition-all duration-200 ease-in-out"
                  @click="tab = 'info'"
                  type="button">
                  <span class="flex items-center justify-center gap-2">
                      <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 inline-block mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M20 7V5a2 2 0 00-2-2H6a2 2 0 00-2 2v2M4 7v10a2 2 0 002 2h12a2 2 0 002-2V7M16 12l-4-2-4 2m8 0v6H8v-6"/>
                      </svg>
                      Product Info
                  </span>
              </button>

              <button
                  :class="tab === 'comments' 
                      ? 'text-green-600 border-t-3 border-green-500 bg-white shadow-sm' 
                      : 'text-gray-500 hover:text-green-500 border-t-3 border-transparent hover:border-green-300'"
                  class="flex-1 py-3 px-5 text-sm md:text-base font-semibold text-center transition-all duration-200 ease-in-out"
                  @click="tab = 'comments'"
                  type="button">
                  <span class="flex items-center justify-center gap-2">
                      <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 inline-block mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2m-6 0h2a2 2 0 002-2v-6a2 2 0 00-2-2h-2m-2 8l-4 4v-4H5a2 2 0 01-2-2v-4a2 2 0 012-2h2"/>
                      </svg>
                      Reviews
                  </span>
              </button>
          </div>


          <!-- Tab Contents -->
          <div class="bg-white py-6 ">

              <!-- Product Info Tab -->
              <div x-show="tab === 'info'">
                <table class="min-w-full border border-gray-200 text-gray-800">
                  <tbody>
                      <tr class="border-b">
                          <td class="font-semibold px-4 py-2 w-60 border-r">Product Name:</td>
                          <td class="px-4 py-2">{{ $productDetail->name }}</td>
                      </tr>
                      <tr class="border-b">
                          <td class="font-semibold px-4 py-2 w-60 border-r">Brand:</td>
                          <td class="px-4 py-2">{{ $productDetail->brand->name }}</td>
                      </tr>
                      <tr class="border-b">
                          <td class="font-semibold px-4 py-2 w-60 border-r">Crop Name:</td>
                          <td class="px-4 py-2">{{ $productDetail->crop->crop_name }}</td>
                      </tr>
                      <tr class="border-b">
                          <td class="font-semibold px-4 py-2 w-60 border-r">Chemicals:</td>
                          <td class="px-4 py-2">{{ $chemicals }}</td>
                      </tr>
                      <tr class="border-b">
                          <td class="font-semibold px-4 py-2 w-60 border-r">Benefit Video :</td>
                          <td class="px-4 py-2">
                              <a href="{{ $productDetail->video_urls['benefit'] ?? '#' }}" 
                                target="_blank" 
                                class="text-blue-600 hover:underline">
                                {{ $productDetail->video_urls['benefit'] ?? 'N/A' }}
                              </a>
                          </td>
                      </tr>
                      <tr class="border-b">
                          <td class="font-semibold px-4 py-2 w-40 border-r">Testimonial Video:</td>
                          <td class="px-4 py-2">
                              <a href="{{ $productDetail->video_urls['testimonial'] ?? '#' }}" 
                                target="_blank" 
                                class="text-blue-600 hover:underline">
                                {{ $productDetail->video_urls['testimonial'] ?? 'N/A' }}
                              </a>
                          </td>
                      </tr>
                      <tr>
                          <td class="font-semibold px-4 py-2 w-40 border-r">Result Video:</td>
                          <td class="px-4 py-2">
                              <a href="{{ $productDetail->video_urls['result'] ?? '#' }}" 
                                target="_blank" 
                                class="text-blue-600 hover:underline">
                                {{ $productDetail->video_urls['result'] ?? 'N/A' }}
                              </a>
                          </td>
                      </tr>
                  </tbody>
                </table>
              </div>

              <!-- Description Tab -->
              <div x-show="tab === 'desc'">
                  <h4 class="font-bold mb-2 text-gray-900">Product Description</h4>
                  {!! $productDetail->description !!}
              </div>

              <!-- Comments/Reviews Tab -->
              <div x-show="tab === 'comments'">
                  <h4 class="font-bold mb-2 text-gray-900">Comments & Reviews</h4>
                  {{-- Example static comments, replace with dynamic content --}}
                  <div class="space-y-4">
                      @forelse ($productDetail->ratings as $item)
                      <div class="border-b pb-2">
                          <div class="flex items-center space-x-2 mb-1">
                              <span class="font-semibold text-green-700">{{ $item->farmer->name }}</span>
                              <span class="text-xs text-gray-400">{{ $item->created_at->diffForHumans() }}</span>
                          </div>
                          <p class="text-gray-700 text-sm">{{ $item->review }}</p>
                      </div>
                      @empty
                          <p class="text-gray-500">No reviews yet</p>
                      @endforelse
                  </div>
              </div>
          </div>
        </div>
      </div>

    </div>

    <!-- Vendor Info -->
    <div class="space-y-4">
      <div class="bg-gray-50 p-4 rounded-lg max-h-110">
        <div class="bg-white p-3 flex items-center space-x-3">
          <img src="{{ asset('images/frontend/vendor.png') }}" class="w-16 h-16" alt="">
          <div>
            <h3 class="font-semibold text-gray-800">Noodles Co.</h3>
            <p class="text-yellow-500 flex items-center">
              @for($i = 0; $i < 5; $i++)
              <img src="{{ asset('images/star.svg') }}" alt="Star" class="w-4 h-4 inline-block text-yellow-400">
              @endfor
              <span class="text-gray-600 text-sm ml-1">(36)</span>
            </p>
          </div>
        </div>

        <p class="text-gray-600 mt-4 text-sm">
          Noodles & Company is an American fast-casual restaurant offering international and American noodle dishes.
        </p>

        <div class="mt-4 border-t pt-4">
          <p class="font-semibold text-gray-800"><i class="fa-solid fa-location-dot text-orange-500"></i> Address:</p>
          <p class="text-gray-600">1288 Franklin Avenue</p>
        </div>

        <div class="mt-4">
          <p class="font-semibold text-gray-800"><i class="fa-solid fa-headphones text-orange-500"></i> Contact Seller:</p>
          <p class="text-gray-600">(+1)-123-456-789</p>
        </div>
      </div>
      
      <div class="bg-gray-50 p-4 rounded-lg max-h-130">
        <div class="min-h-[310px] flex flex-col">
            <h3 class="text-lg font-bold text-gray-900 mb-2 flex items-center">
                Trending Products
            </h3>
            <div class="w-1/2 border-b-2 border-green-500"></div>

            <div class="space-y-4 mt-4">
                @foreach ($bestSellingProducts->take(4) as $product)
                <div class="flex items-center mb-3 bg-white p-2">
                    <div class="w-25 h-25 overflow-hidden flex justify-center items-center rounded-lg bg-white p-2">
                        <img src="{{ asset('storage/'.$product->cover_image) }}"
                            alt="{{ $product->name }}"
                            class="object-cover w-full h-full rounded transform transition-transform duration-300 ease-in-out hover:scale-110"
                        />
                    </div>


                    <div class="ml-3 flex-1">
                        <div class="font-medium text-gray-900 text-sm">
                            <a href="{{ route('product.detail', $product->slug) }}" class="block font-semibold truncate text-gray-800 hover:text-green-600">
                                {{ $product->name }}
                            </a>
                        </div>
                        <div class="flex items-center text-xs mt-1">
                        <!-- Stars -->
                        <span class="flex text-yellow-400 mr-1">
                            @for($i = 0; $i < 5; $i++)
                            <img src="{{ asset('images/star.svg') }}" alt="Star" class="w-4 h-4 inline-block text-yellow-400">
                            @endfor                                
                        </span>
                        <span class="text-gray-400 ml-1">({{ $product->total_reviews }})</span>
                        </div>
                        <span class="text-green-600 font-semibold text-xs">₹{{ $product->defaultVariant->selling_price }}</span>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
      </div>

      <div class="bg-gray-50 rounded-lg max-h-130 relative">
        <img src="{{ asset('images/frontend/banner-2.jpg') }}" alt="" class="w-full h-[85%] object-cover filter rounded-lg" loading="lazy" />
        <div class="absolute top-4 left-4 w-3/4">
          <div>
            <h2 class="mt-0 text-red-600 text-3xl font-bold">
                45% <span class="text-2xl font-normal">OFF</span>
            </h2>
            <h3 class="text-green-600 text-xl font-semibold mb-1">Real Refreshment</h3>
            <h5 class="text-gray-600 mb-4">Only this week, Don't miss..</h5>
            <a href="#" class="inline-flex items-center text-yellow-500 hover:text-yellow-600 font-semibold">
                Shop Now
                <i class="fa-solid fa-right-long ml-2"></i>
            </a>
          </div>
        </div>
      </div>
    </div>

    
  </div>
</section>

<!-- Related Products -->
@if (count($relatedProducts))
<div class="max-w-7xl mx-auto py-10">
    <!-- Section Title -->
    <div class="mb-2 inline-block">
        <h2 class="text-3xl font-bold">Related Products</h2>
        <div class="flex items-center gap-6 mx-auto">
        <span class="block flex-grow h-0.5 bg-green-600 rounded"></span>
        <img src="{{ asset('images/frontend/leaf.svg') }}" alt="Leaf Icon" class="w-6 h-6 text-green-600" />
        <span class="block flex-grow h-0.5 bg-green-600 rounded"></span>
        </div>
        <p class="text-gray-600">A virtual assistant collects the products from your list</p>
    </div>

    <!-- Swiper Slider -->
    <div class="swiper related-swiper my-5">
        <div class="swiper-wrapper">
            @foreach ($relatedProducts as $product)
            <div class="swiper-slide">
            <x-product-card :productMeta="$product"  />
            </div>
            @endforeach            
        </div>
        <div class="swiper-pagination"></div>
    </div>
</div>
@endif




@endsection

@php
    $galleryImages = json_decode($productDetail->images ?? '[]', true); // all additional images
    $coverImage = asset('storage/' . $productDetail->cover_image);
    $allImages = array_merge([$coverImage], array_map(fn($img) => asset('storage/' . $img), $galleryImages));    

    $variants = $productDetail->variants->map(function($variant) {
        return [
            'name' => $variant->variant_name,
            'size' => $variant->pack_description,
            'sku' => $variant->sku,
            'stock' => $variant->stock_quantity,
            'price' => $variant->selling_price,            
            'mrp' => $variant->mrp,
            'id' => $variant->id,
        ];
    });    
@endphp

@push('scripts')
  <script>
  function productPage() {    
    return {
      isLoading: false, 
      isWLoading: false,        
      images: @json($allImages),
      selectedImage: "{{ $coverImage }}",        
      currentPack: "{{ $selectedVariant }}",
      variants: @json($variants),
      selectedVariant: null,
      get filteredVariants() {
        return this.variants;
      },
      get sku() {
        return this.selectedVariant.sku || '';
      },
      get stock() {
        return this.selectedVariant.stock || 0;
      },
      get price() {
        return this.selectedVariant.price || 0;
      },
      get mrp() {
        return this.selectedVariant.mrp || 0;
      },
      get size() {
        return this.selectedVariant.size || 0;
      },
      get discountPercent() {        
        if (!this.selectedVariant || !this.selectedVariant.mrp) return 0;

        const discount = ((this.selectedVariant.mrp - this.selectedVariant.price) / this.selectedVariant.mrp) * 100;        
        return Math.round(discount); 
        // return 0; 
      },
      selectVariant(variant) {
        this.selectedVariant = variant;
      },

      addToCart(productId) {
        if (!this.selectedVariant) {
            notyf.error('Please select a variant first!');            
            this.isLoading = false;
            return;
        }

        const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

        fetch("{{ route('cart.add', '#') }}".replace('#', productId), {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": csrfToken
            },
            body: JSON.stringify({
                variant_id: this.selectedVariant.id,
                quantity: 1
            }),
            credentials: "same-origin" 
        })
        .then(response => {
            if (!response.ok) throw response;
            return response.json();
        })
        .then(data => {
            notyf.success('Added to cart!');
            fetch("{{ route('cart.count') }}")
            .then(res => res.json())
            .then(data => {
                document.getElementById('cart-count-badge').textContent = data.count;
            });
        })
        .catch(async (errorResponse) => {
            let errorMsg = 'Could not add to cart!';
            if (errorResponse.json) {
                const errorData = await errorResponse.json();
                errorMsg = errorData.message || errorMsg;
            }
            notyf.error(errorMsg);
        })
        .finally(() => {
          this.isLoading = false; 
        });
      },

      addToWishlist(productId) {    
        this.isWLoading = true;                                        
        const csrfToken = document.querySelector('meta[name="csrf-token"]').content;        

        fetch("{{ route('wishlist.add') }}", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": csrfToken
            },
            body: JSON.stringify({
                product_id: productId
            }),
            credentials: "same-origin" 
        })
        .then(response => {
            if (!response.ok) throw response;                
            return response.json();
        })
        .then(data => {                      
            notyf.success(data.message);      
            fetch("{{ route('wishlist.count') }}")
            .then(res => res.json())
            .then(data => {
                document.getElementById('wishlist-count-badge').textContent = data.count;
            });
        })
        .catch(async (errorResponse) => {
            let errorMsg = 'Could not add to wishlist!';
            if (errorResponse.json) {
                const errorData = await errorResponse.json();
                errorMsg = errorData.message || errorMsg;
            }
            notyf.error(errorMsg);
        })
        .finally(() => {
            this.isWLoading = false; 
        });
      },

      init() {
        this.selectVariant(this.variants[0]);
      }
    }
  }
</script>
@endpush