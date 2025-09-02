    <header class="w-full bg-white">
        <div class="max-w-7xl mx-auto flex items-center justify-between space-x-4">

        <!-- Left: Logo and Location -->
        <div class="flex items-center space-x-6 my-3 flex-shrink-0">
            <!-- Logo -->
            <a href="{{ route('index') }}">
            <img
                class="dark:hidden max-h-[80px]"
                src="{{ config('settings.site_logo_lite') ?? asset('images/logo/lara-dashboard.png') }}"
                alt="{{ config('app.name') }}"
            />
            </a>
            <!-- Location -->
            <div class="flex items-center space-x-2 px-2">
            <!-- Location Icon -->
            <svg class="w-5 h-5 text-green-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round"
                d="M12 21s-7-7.14-7-12A7 7 0 0 1 12 2a7 7 0 0 1 7 7c0 4.86-7 12-7 12z" />
                <circle cx="12" cy="9" r="2.5" stroke="currentColor" stroke-width="2" fill="#fff" />
            </svg>
            <div class="leading-tight">
                <span class="text-xs text-gray-400 font-semibold block">Your Location</span>
                <span class="text-sm text-gray-800 font-medium block"><span id="user-location">Detecting location...</span></span>
            </div>
            </div>
        </div>

        <!-- Center: Search Bar -->
        <form class="flex flex-1 max-w-lg mx-6" action="{{ route('collection.product.search') }}" method="GET" autocomplete="off">
            <input
            class="flex-1 border border-gray-200 rounded-l-lg px-3 py-2 text-gray-700 bg-gray-50 focus:outline-none focus:ring-2 focus:ring-green-200 placeholder:text-gray-400 text-base"
            name="keyword"
            value="{{ request('keyword') }}"
            type="text"
            placeholder="I'm searching for..." />
            <button class="bg-orange-400 hover:bg-orange-500 rounded-r-lg px-4 grid place-items-center text-white" type="submit">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2"
                viewBox="0 0 24 24">
                <circle cx="11" cy="11" r="7" stroke="currentColor" />
                <path stroke-linecap="round" stroke-linejoin="round"
                d="M21 21l-3.5-3.5" />
            </svg>
            </button>
        </form>

        <!-- Right: Language and Icons -->
        <div class="flex items-center space-x-3 flex-shrink-0">

            <!-- Language Selector -->
            <button
            class="flex items-center bg-green-100 hover:bg-green-200 text-green-900 px-2 py-1 rounded space-x-1 text-sm font-semibold"
            aria-label="Select language"
            >
            <img src="https://flagcdn.com/us.svg" alt="EN" class="w-5 h-4 rounded shadow-inner" />
            <span>English</span>
            <svg class="w-4 h-4 ml-1 text-green-600" fill="none" stroke="currentColor" stroke-width="2"
                viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round"
                d="M19 9l-7 7-7-7" />
            </svg>
            </button>

            <!-- Icons -->
            {{-- <a href="#" class="text-gray-500 hover:text-green-600" aria-label="Search">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <circle cx="11" cy="11" r="7" stroke="currentColor" />
                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-3.5-3.5" />
            </svg>
            </a> --}}

            <a href="#" class="text-gray-500 hover:text-green-600" aria-label="Call Us">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round"
                d="M22 16.92V19a2 2 0 0 1-2.18 2A19.72
                19.72 0 0 1 3 5.18 2 2 0 0 1 5 3h2.09
                a2 2 0 0 1 2 1.72c.13 1.08.21 2.18.21
                3.28a2 2 0 0 1-.47 1.37l-2.22 2.22a16
                16 0 0 0 7.07 7.07l2.22-2.22a2 2 0 0
                1 1.37-.47c1.1 0 2.21.08 3.28.21A2 2
                0 0 1 22 16.92z" />
            </svg>
            </a>

            <a href="#" class="relative text-gray-500 hover:text-green-600" aria-label="Wishlist">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2"
                viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round"
                d="M4.318 6.318a4.5 4.5 0 0 1 6.364 0L12 7.636l1.318-1.318
                a4.5 4.5 0 1 1 6.364 6.364L12 21.364l-7.682-7.682a4.5 4.5
                0 0 1 0-6.364z" />
            </svg>
            <span id="wishlist-count-badge"
                class="absolute -top-1 -right-2 bg-red-500 text-xs text-white rounded-full px-1">
                {{ $wishlistCount ?? 0 }}
            </span>
            </a>

            <a href="{{ route('cart.index') }}" class="relative text-gray-500 hover:text-green-600" aria-label="Shopping Cart">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2"
                    viewBox="0 0 24 24">
                    <circle cx="9" cy="21" r="1" />
                    <circle cx="20" cy="21" r="1" />
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M1 1h4l2.68 13.39a1 1 0 0 0 1 .61h9.72a1 1 0 0 0 1-.78L23 6H6" />
                </svg>
                <span id="cart-count-badge"
                    class="absolute -top-1 -right-2 bg-red-500 text-xs text-white rounded-full px-1">
                    {{ $cartCount ?? 0 }}
                </span>
            </a>

            <!-- User Account Dropdown on Hover -->
            <div class="relative group">
                <!-- User Icon -->
                <button class="flex items-center text-gray-500 hover:text-green-600 focus:outline-none" aria-label="User Account">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M5.121 17.804A8.966 8.966 0 0 1 12 15c2.21
                            0 4.21.837 5.879 2.204M15 10a3 3 0 1 0-6 0
                            3 3 0 0 0 6 0zm5 4.6a9 9 0 1 0-10 8.4" />
                    </svg>
                </button>

                <!-- Dropdown Menu -->
                <div class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg z-50 
                            opacity-0 invisible group-hover:opacity-100 group-hover:visible 
                            transition-all duration-200">
                    
                    @auth('farmer')
                        <a href="{{ route('user.profile') }}" 
                        class="block px-4 py-2 text-gray-700 hover:bg-green-100">
                            My Profile
                        </a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" 
                                    class="w-full text-left px-4 py-2 text-gray-700 hover:bg-green-100">
                                Logout
                            </button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" 
                        class="block px-4 py-2 text-gray-700 hover:bg-green-100">
                            Login
                        </a>
                        <a href="{{ route('register') }}" 
                        class="block px-4 py-2 text-gray-700 hover:bg-green-100">
                            Register
                        </a>
                    @endauth
                </div>
            </div>


        </div>
        </div>
    </header>



    <!-- Topbar with Seeds & Mega Menu as trigger; insert after header -->
    @if (request()->routeIs('user.*'))
        
    @else
    <nav class="bg-white w-full">
    <div class="max-w-7xl mx-auto px-4 flex h-12 items-center relative">
        
        <!-- Left: could be empty or auxiliary items -->
        <div class="flex-1"></div>
        
        <!-- Center: Collection Nav -->
        <div class="flex space-x-6 mx-auto">
        @foreach($collections as $collection)        
       <div class="relative group h-full">
    <button
        class="h-full px-4 text-gray-700 font-semibold hover:text-green-600 focus:outline-none flex items-center">
        <a href="{{ route('collection.product', $collection->slug) }}"
            class="hover:text-green-600">{{ $collection->name }}</a>
        @if($collection->childrenType->count() > 0)
        <svg class="w-4 h-4 ml-1 text-gray-500 group-hover:text-green-500 transition"
            fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 20 20">
            <path stroke-linecap="round" stroke-linejoin="round" d="M6 8l4 4 4-4" />
        </svg>
        @endif
    </button>

    @if($collection->childrenType->count() > 0)
    <!-- Mega menu dropdown -->
    <div
        class="absolute top-full left-1/2 z-30 min-w-[700px] py-6 px-4 bg-white shadow-xl rounded-b-xl 
        opacity-0 translate-y-2 group-hover:opacity-100 group-hover:translate-y-0
        group-hover:pointer-events-auto pointer-events-none
        transition-all duration-300 ease-out transform -translate-x-1/2">
        <div class="flex flex-col md:flex-row gap-8 w-full">
            @foreach($collection->childrenType as $type)
            <div class="flex-1 min-w-[180px]">
                <h3 class="text-base font-bold text-gray-800 mb-2 border-l-4 border-green-500 pl-2">
                    <a href="{{ route('collection.product', $type->slug) }}"
                        class="hover:text-green-600">{{ $type->name }}</a>
                </h3>
                <ul class="space-y-2 text-gray-600">
                    @foreach($type->subCollection as $collection)
                    <li>
                        <a href="{{ route('collection.product', $collection->slug) }}"
                            class="hover:text-green-600">{{ $collection->name }}</a>
                    </li>
                    @endforeach
                </ul>
            </div>
            @endforeach
            <div class="hidden md:block self-stretch"></div>
        </div>
    </div>
    @endif
</div>
   
        @endforeach
        </div>

        <!-- Right: could be empty or auxiliary items -->
        <div class="flex-1"></div>
    </div>
    </nav>

    @endif