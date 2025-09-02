@extends('frontend.layouts.app')

@section('main-content')

    <!-- HERO SECTION -->
    <section class="pt-2">
        <div class="container mx-auto px-4">
            <div class="flex flex-wrap -mx-4 gap-y-4">
            <!-- Left Column -->
            <div class="xl:w-9/12 lg:w-8/12 px-4">
                <div class="relative h-full rounded-lg overflow-hidden">
                <img src="{{ asset('images/frontend/banner-1.jpg') }}" alt="" class="w-full h-[85%] object-cover filter rounded-lg" loading="lazy" />
                <div class="absolute top-1/2 left-[5%] transform -translate-y-1/2 pl-4 xl:w-3/4 max-w-[75%]">
                    <div>
                    <h6 class="text-base font-semibold mb-2">Exclusive offer <span class="text-red-600">30% Off</span></h6>
                    <h1 class="w-3/4 uppercase font-bold text-4xl mb-4 leading-tight">
                        Stay home &amp; delivered your <span class="text-green-600 font-extrabold">Daily Needs</span>
                    </h1>
                    <p class="w-7/12 hidden sm:block mb-5 text-gray-700">
                        Many organizations have issued official statements encouraging people to reduce their intake of sugary drinks.
                    </p>
                    <button onclick="location.href='shop-left-sidebar.html';" class="bg-red-400 hover:bg-red-600 transition text-white py-3 px-6 rounded-md font-semibold flex items-center space-x-2 max-w-max">
                        <span>Shop Now</span>
                        <i class="fa-solid fa-right-long"></i>
                    </button>
                    </div>
                </div>
                </div>
            </div>

            <!-- Right Column -->
            <div class="xl:w-3/12 lg:w-4/12 hidden lg:block relative aspect-[156/100] px-4">
                <div class="relative h-full rounded-lg overflow-hidden">
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
        </div>
    </section>
    <!-- Home Section End -->

    <!-- BROWSE BY CATEGORIES -->  
    <div class="max-w-7xl mx-auto px-4">
        <div class="mb-2 inline-block">
            <h2 class="text-3xl font-bold">Browse By Categories</h2>
            <div class="flex items-center gap-4 mx-auto"> <!-- set fixed width -->
                <span class="block flex-grow h-0.5 bg-green-600 rounded"></span>
                <img src="{{ asset('images/frontend/leaf.svg') }}" alt="Leaf Icon" class="w-6 h-6 text-green-600" />
                <span class="block flex-grow h-0.5 bg-green-600 rounded"></span>
            </div>
            <p class="text-gray-600">Top Categories Of The Week</p>
        </div>

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

    <!-- Cashback banner -->    
    @if ($promocode)
    <div class="max-w-7xl mx-auto px-4 mb-10">        
        <div class="justify-center">
            <div class="relative rounded-lg overflow-hidden shadow-lg group">
                <!-- Banner Image -->
                <img src="{{ asset('images/frontend/discount.jpg') }}" 
                    alt="Banner" 
                    class="w-full h-auto object-cover transition-transform duration-500 group-hover:scale-105" />

                <!-- Overlay Text -->
                <div class="absolute inset-0 flex flex-col justify-center items-center text-center text-white p-6">
                    <h3 class="text-xl sm:text-2xl font-bold leading-snug text-white">
                        {{ $promocode->description }}
                    </h3>
                    <h6 class="text-base mt-2 font-medium">
                        Use Code : <span class="font-semibold">{{ $promocode->code }}</span>
                    </h6>
                </div>
            </div>
        </div>
    </div>
    @endif


    <!-- Today's Offers -->
    <div class="max-w-7xl mx-auto px-4 py-10">
        <!-- Section Title -->
        <div class="mb-2 inline-block">
            <h2 class="text-3xl font-bold">Today's Offers</h2>
            <div class="flex items-center gap-6 mx-auto">
            <span class="block flex-grow h-0.5 bg-green-600 rounded"></span>
            <img src="{{ asset('images/frontend/leaf.svg') }}" alt="Leaf Icon" class="w-6 h-6 text-green-600" />
            <span class="block flex-grow h-0.5 bg-green-600 rounded"></span>
            </div>
            <p class="text-gray-600">A virtual assistant collects the products from your list</p>
        </div>

        <!-- Swiper Slider -->
        <div class="swiper product-swiper my-5">
            <div class="swiper-wrapper">
                @foreach ($mostDiscountedProducts as $product)
                    <div class="swiper-slide">
                    <x-product-card :productMeta="$product"  />
                    </div>
                @endforeach    
            </div>
            <div class="swiper-pagination"></div>
        </div>
    </div>


    <!-- Discount Banner -->
    <div class="max-w-7xl mx-auto px-4 py-10">
        <div class="justify-center">
            <div class="relative rounded-lg overflow-hidden shadow-lg group">
                <!-- Banner Image -->
                <img src="{{ asset('images/frontend/discount-1.jpg') }}" 
                    alt="Banner" 
                    class="w-full h-auto object-cover transition-transform duration-500 group-hover:scale-105" />

                <!-- Overlay with Flex Layout -->
                <div class="absolute inset-0 flex items-center justify-center p-6">
                    <div class="flex items-center justify-between w-full max-w-3xl">
                        <!-- Left Text -->
                        <div class="text-left">
                            <h3 class="text-xl sm:text-2xl leading-snug">
                                Special Offers <span class="font-bold  text-red-500">of the week!</span>
                            </h3>
                            <p class="text-base mt-2 text-gray-600">
                                Special offer on this discount, Hurry Up!
                            </p>
                        </div>

                        <!-- Countdown Timer -->
                        <div id="countdown" class="flex justify-center gap-3">
                            <div class="bg-red-500 px-4 py-2 rounded-md text-white text-lg font-bold">00</div>
                            <div class="bg-red-500 px-4 py-2 rounded-md text-white text-lg font-bold">00</div>
                            <div class="bg-red-500 px-4 py-2 rounded-md text-white text-lg font-bold">00</div>
                            <div class="bg-red-500 px-4 py-2 rounded-md text-white text-lg font-bold">00</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Best Selling -->
    <div class="max-w-7xl mx-auto px-4 py-10">
        <!-- Section Title -->
        <div class="mb-2 inline-block">
            <h2 class="text-3xl font-bold">Best Selling</h2>
            <div class="flex items-center gap-6 mx-auto">
            <span class="block flex-grow h-0.5 bg-green-600 rounded"></span>
            <img src="{{ asset('images/frontend/leaf.svg') }}" alt="Leaf Icon" class="w-6 h-6 text-green-600" />
            <span class="block flex-grow h-0.5 bg-green-600 rounded"></span>
            </div>
            <p class="text-gray-600">A virtual assistant collects the products from your list</p>
        </div>

        <!-- Swiper Slider -->
        <div class="swiper product-swiper my-5">
            <div class="swiper-wrapper">
                @foreach ($bestSellingProducts as $product)
                    <div class="swiper-slide">
                    <x-product-card :productMeta="$product"  />
                    </div>
                @endforeach    
            </div>
            <div class="swiper-pagination"></div>
        </div>
    </div>


    <!-- Seeds -->
    <div class="max-w-7xl mx-auto px-4">
        <!-- Section Title -->
        <div class="mb-2 inline-block">
            <h2 class="text-3xl font-bold">Seeds</h2>
            <div class="flex items-center gap-6 mx-auto">
            <span class="block flex-grow h-0.5 bg-green-600 rounded"></span>
            <img src="{{ asset('images/frontend/leaf.svg') }}" alt="Leaf Icon" class="w-6 h-6 text-green-600" />
            <span class="block flex-grow h-0.5 bg-green-600 rounded"></span>
            </div>
            <p class="text-gray-600">A virtual assistant collects the products from your list</p>
        </div>

        <!-- Swiper Slider -->
        <div class="swiper product-swiper my-5">
            <div class="swiper-wrapper">
                @foreach ($seedsProducts as $product)
                    <div class="swiper-slide">
                    <x-product-card :productMeta="$product"  />
                    </div>
                @endforeach  
            </div>
            <div class="swiper-pagination"></div>
        </div>
    </div>

    <!-- Hot Deal & Item Section -->
    {{-- <div class="max-w-7xl mx-auto">
        <div class="flex gap-6 w-full justify-center items-start bg-white">
            @for ($i = 0; $i < 4; $i++)        
                <div class="relative w-72 h-72 rounded-lg overflow-hidden">
                    <div class="h-full">
                        <img src="{{ asset('images/frontend/product-3.jpg') }}" alt="Organic Meat" class="object-contain max-h-full max-w-full mx-auto">
                    </div>
                    <div class="absolute bottom-[1%] left-0 w-full px-2">
                    <div class="inset-0 bg-white bg-opacity-15 rounded-lg p-4 text-gray-800 shadow-sm">
                        <h2 class="text-lg font-bold">Organic Meat Prepared</h2>
                        <p class="text-base text-gray-500">Delivered to Your Home</p>
                        <div class="flex items-center">
                        <span class="text-yellow-400 mr-1">★★★★☆</span>
                        <span class="text-gray-700 text-sm ml-1">(34)</span>
                        </div>
                        <p class="text-sm text-green-500 mb-3">By Nestfood</p>
                        <button class="bg-red-400 hover:bg-red-500 text-white px-4 py-2 rounded-lg text-sm font-bold flex items-center">
                        Shop Now
                        <svg class="w-4 h-4 ml-2" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <path fill-rule="evenodd" clip-rule="evenodd" d="M12.2929 4.29289C12.6834 3.90237 13.3166 3.90237 13.7071 4.29289L20.7071 11.2929C21.0976 11.6834 21.0976 12.3166 20.7071 12.7071L13.7071 19.7071C13.3166 20.0976 12.6834 20.0976 12.2929 19.7071C11.9024 19.3166 11.9024 18.6834 12.2929 18.2929L17.5858 13H4C3.44772 13 3 12.5523 3 12C3 11.4477 3.44772 11 4 11H17.5858L12.2929 5.70711C11.9024 5.31658 11.9024 4.68342 12.2929 4.29289Z" fill="#ffffff"></path> </g></svg>
                        </button>
                    </div>
                    </div>
                </div>
            @endfor
        </div>
    </div> --}}
    
    <!-- Top Products Section with Decorative Leaf Background -->
    <div class="max-w-7xl mx-auto px-4 py-10 relative">
        <div class="relative z-10 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-7">
            <!-- Top Selling -->
            <div class="min-h-[310px] flex flex-col">
                <h3 class="text-lg font-bold text-gray-900 mb-2 flex items-center">
                    Top Selling
                </h3>
                <div class="w-1/2 border-b-2 border-green-500"></div>

                <div class="space-y-4 mt-4">
                    @foreach ($bestSellingProducts->take(4) as $product)
                    <div class="flex items-center mb-3 bg-gray-200 p-2">
                        <div class="w-25 h-25 overflow-hidden flex justify-center items-center rounded-lg bg-white p-2">
                            <img src="{{ asset('storage/'.$product->cover_image) }}"
                                alt="{{ $product->name }}"
                                class="object-cover w-full h-full rounded transform transition-transform duration-300 ease-in-out hover:scale-110"
                            />
                        </div>


                        <div class="ml-3 flex-1">
                            <div class="font-medium text-gray-900 text-sm">
                                <a href="{{ route('product.detail', $product->slug) }}" class="block font-semibold text-gray-800 hover:text-green-600 truncate w-40 whitespace-nowrap overflow-hidden">
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

            <!-- Trending Products -->
            <div class="min-h-[310px] flex flex-col">
                <h3 class="text-lg font-bold text-gray-900 mb-2 flex items-center">
                    Trending Products
                </h3>
                <div class="w-1/2 border-b-2 border-green-500"></div>

                <div class="space-y-4 mt-4">
                    @foreach ($bestSellingProducts->take(4) as $product)
                    <div class="flex items-center mb-3 bg-gray-200 p-2">
                        <div class="w-25 h-25 overflow-hidden flex justify-center items-center rounded-lg bg-white p-2">
                            <img src="{{ asset('storage/'.$product->cover_image) }}"
                                alt="{{ $product->name }}"
                                class="object-cover w-full h-full rounded transform transition-transform duration-300 ease-in-out hover:scale-110"
                            />
                        </div>


                        <div class="ml-3 flex-1">
                            <div class="font-medium text-gray-900 text-sm">
                                <a href="{{ route('product.detail', $product->slug) }}" class="block font-semibold text-gray-800 hover:text-green-600 truncate w-40 whitespace-nowrap overflow-hidden">
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

            <!-- Recently Added -->
            <div class="min-h-[310px] flex flex-col">
                <h3 class="text-lg font-bold text-gray-900 mb-2 flex items-center">
                    Recently Added
                </h3>
                <div class="w-1/2 border-b-2 border-green-500"></div>

                <div class="space-y-4 mt-4">
                    @foreach ($bestSellingProducts->take(4) as $product)
                    <div class="flex items-center mb-3 bg-gray-200 p-2">
                        <div class="w-25 h-25 overflow-hidden flex justify-center items-center rounded-lg bg-white p-2">
                            <img src="{{ asset('storage/'.$product->cover_image) }}"
                                alt="{{ $product->name }}"
                                class="object-cover w-full h-full rounded transform transition-transform duration-300 ease-in-out hover:scale-110"
                            />
                        </div>


                        <div class="ml-3 flex-1">
                            <div class="font-medium text-gray-900 text-sm">
                                <a href="{{ route('product.detail', $product->slug) }}" class="block font-semibold text-gray-800 hover:text-green-600 truncate w-40 whitespace-nowrap overflow-hidden">
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

            <!-- Top Rated -->
            <div class="min-h-[310px] flex flex-col">
                <h3 class="text-lg font-bold text-gray-900 mb-2 flex items-center">
                    Top Rated
                </h3>
                <div class="w-1/2 border-b-2 border-green-500"></div>

                <div class="space-y-4 mt-4">
                    @foreach ($bestSellingProducts->take(4) as $product)
                    <div class="flex items-center mb-3 bg-gray-200 p-2">
                        <div class="w-25 h-25 overflow-hidden flex justify-center items-center rounded-lg bg-white p-2">
                            <img src="{{ asset('storage/'.$product->cover_image) }}"
                                alt="{{ $product->name }}"
                                class="object-cover w-full h-full rounded transform transition-transform duration-300 ease-in-out hover:scale-110"
                            />
                        </div>


                        <div class="ml-3 flex-1">
                            <div class="font-medium text-gray-900 text-sm">
                                <a href="{{ route('product.detail', $product->slug) }}" class="block font-semibold text-gray-800 hover:text-green-600 truncate w-40 whitespace-nowrap overflow-hidden">
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
    </div>

    <!-- Seasonal -->
    <div class="max-w-7xl mx-auto px-4">
        <!-- Section Title -->
        <div class="mb-2 inline-block">
            <h2 class="text-3xl font-bold">Seasonal</h2>
            <div class="flex items-center gap-6 mx-auto">
            <span class="block flex-grow h-0.5 bg-green-600 rounded"></span>
            <img src="{{ asset('images/frontend/leaf.svg') }}" alt="Leaf Icon" class="w-6 h-6 text-green-600" />
            <span class="block flex-grow h-0.5 bg-green-600 rounded"></span>
            </div>
            <p class="text-gray-600">A virtual assistant collects the products from your list</p>
        </div>

        <!-- Swiper Slider -->
        <div class="swiper product-swiper my-5">
            <div class="swiper-wrapper">
                @foreach ($seasonalProducts as $product)
                    <div class="swiper-slide">
                    <x-product-card :productMeta="$product"  />
                    </div>
                @endforeach  
            </div>
            <div class="swiper-pagination"></div>
        </div>
    </div>


    <!-- Featured Blog Section -->
    {{-- <div class="max-w-7xl mx-auto px-4 py-10">
        <!-- Section Title -->
        <div class="mb-2 inline-block">
            <h2 class="text-3xl font-bold">Featured Blog</h2>
            <div class="flex items-center gap-6 mx-auto">
            <span class="block flex-grow h-0.5 bg-green-600 rounded"></span>
            <img src="{{ asset('images/frontend/leaf.svg') }}" alt="Leaf Icon" class="w-6 h-6 text-green-600" />
            <span class="block flex-grow h-0.5 bg-green-600 rounded"></span>
            </div>
            <p class="text-gray-600">A virtual assistant collects the products from your list</p>
        </div>

        <div class="flex gap-8 py-8">
            <!-- Card 1 -->
            <div class="flex flex-col items-start">
                <div class="w-56 h-56 rounded-lg overflow-hidden mb-3">
                <img src="{{ asset('images/frontend/blog-1.jpg') }}" alt="Fresh Meat Sausage"
                    class="object-cover w-full h-full rounded-lg transform transition-transform duration-300 ease-in-out hover:scale-110" />
                </div>
                <span class="text-sm text-gray-500 font-semibold mb-1">Farmart</span>
                <span class="text-lg font-bold text-gray-900">Fresh Meat Sausage</span>
            </div>
            
            <!-- Card 2 -->
            <div class="flex flex-col items-start">
                <div class="w-56 h-56 rounded-lg overflow-hidden mb-3">
                <img src="{{ asset('images/frontend/blog-2.jpg') }}" alt="Soda 500ml - 20% OFF"
                    class="object-cover w-full h-full rounded-lg transform transition-transform duration-300 ease-in-out hover:scale-110" />
                </div>
                <span class="text-sm text-gray-500 font-semibold mb-1">Soda Brand</span>
                <span class="text-lg font-bold text-gray-900">Soda 500ml - 20% OFF</span>
            </div>

            <!-- Card 3 -->
            <div class="flex flex-col items-start">
                <div class="w-56 h-56 rounded-lg overflow-hidden mb-3">
                <img src="{{ asset('images/frontend/blog-3.jpg') }}" alt="Beer Brand"
                    class="object-cover w-full h-full rounded-lg transform transition-transform duration-300 ease-in-out hover:scale-110" />
                </div>
                <span class="text-sm text-gray-500 font-semibold mb-1">Beer Brand</span>
                <span class="text-lg font-bold text-gray-900">Soda 500ml - 20% OFF</span>
            </div>

            <!-- Card 4 -->
            <div class="flex flex-col items-start">
                <div class="w-56 h-56 rounded-lg overflow-hidden mb-3">
                <img src="{{ asset('images/frontend/blog-4.jpg') }}" alt="Fresh Beer -30% OFF"
                    class="object-cover w-full h-full rounded-lg transform transition-transform duration-300 ease-in-out hover:scale-110" />
                </div>
                <span class="text-sm text-gray-500 font-semibold mb-1">Beer Brand</span>
                <span class="text-lg font-bold text-gray-900">Fresh Beer -30% OFF</span>
            </div>

            <!-- Card 5 -->
            <div class="flex flex-col items-start">
                <div class="w-56 h-56 rounded-lg overflow-hidden mb-3">
                <img src="{{ asset('images/frontend/blog-5.jpg') }}" alt="Fresh Milk"
                    class="object-cover w-full h-full rounded-lg transform transition-transform duration-300 ease-in-out hover:scale-110" />
                </div>
                <span class="text-sm text-gray-500 font-semibold mb-1">Milk Brand</span>
                <span class="text-lg font-bold text-gray-900">Fresh Milk</span>
            </div>
        </div>
    </div> --}}


    <!-- Newsletter Section with background image -->
    <div class="max-w-7xl mx-auto px-4 py-10">
        <div class="rounded-2xl overflow-hidden flex items-center" 
            style="background: url({{ asset('images/frontend/newsletter.jpg') }}) center center/cover no-repeat; min-height: 240px;">
            <!-- Left Content -->
            <div class="flex-1 flex flex-col justify-center pl-[200px]">
                <h2 class="text-2xl font-semibold text-white">
                    Join Our Newsletter And Get...
                </h2>
                <h5 class="text-lg font-semibold text-yellow-400 mb-6">$20 discount for your first order</h5>
                <form class="flex w-[500px] max-w-full rounded-lg border-2 border-white overflow-hidden bg-white">
                    <!-- Icon box -->
                    <span class="flex items-center justify-center px-2 w-12 bg-[#dff4f3]">    
                        <svg class="w-5 h-5 text-teal-700" fill="#059669" viewBox="0 0 1920 1920" xmlns="http://www.w3.org/2000/svg"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <path d="M0 1694.235h1920V226H0v1468.235ZM112.941 376.664V338.94H1807.06v37.723L960 1111.233l-847.059-734.57ZM1807.06 526.198v950.513l-351.134-438.89-88.32 70.475 378.353 472.998H174.042l378.353-472.998-88.32-70.475-351.134 438.89V526.198L960 1260.768l847.059-734.57Z" fill-rule="evenodd"></path> </g></svg>
                    </span>
                    <!-- Email input -->
                    <input 
                        type="email" 
                        placeholder="Enter Your Email"
                        class="flex-1 py-3 px-3 border-none outline-none bg-transparent text-teal-700 placeholder:text-teal-700 text-base"
                    />
                    <!-- Subscribe button -->
                    <button 
                        type="submit" 
                        class="bg-[#ff5757] text-white px-7 font-medium transition hover:bg-red-500 flex items-center"
                    >
                        Subscribe        
                    </button>
                </form>
            </div>
            <!-- Right Image/Decoration (Optional, for illustration) -->
            <div class="flex-1 flex justify-center items-center">      
            </div>
        </div>
    </div>


@endsection


@push('scripts')
    
@endpush