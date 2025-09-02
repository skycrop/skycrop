<!-- Top features row above footer -->
    <div
     class="border-t border-b border-gray-100 bg-cover bg-center"
        style="background-image: url({{ asset('images/frontend/footer-shape.png') }});"
    >
    <div class="max-w-7xl mx-auto px-4 flex flex-wrap justify-between gap-6 py-6   text-sm">
        <div class="flex items-center space-x-2">
        <span class="inline-flex justify-center items-center w-9 h-9 rounded-full bg-green-50 text-green-600">
            <!-- Leaf/box icon -->
            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <rect x="3" y="7" width="18" height="13" rx="2" />
            <path d="M16 3v4M8 3v4" />
            </svg>
        </span>
        <span class="font-semibold text-gray-700">Every Fresh Products</span>
        </div>
        <div class="flex items-center space-x-2">
        <span class="inline-flex justify-center items-center w-9 h-9 rounded-full bg-violet-50 text-violet-500">
            <!-- Truck icon -->
            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <rect x="1" y="5" width="15" height="13" rx="2" />
            <path d="M16 10h3l4 4v4a2 2 0 01-2 2H17" />
            <circle cx="6" cy="18" r="2" />
            <circle cx="18" cy="18" r="2" />
            </svg>
        </span>
        <span class="font-semibold text-gray-700">Free Delivery For Order Over $50</span>
        </div>
        <div class="flex items-center space-x-2">
        <span class="inline-flex justify-center items-center w-9 h-9 rounded-full bg-yellow-50 text-yellow-500">
            <!-- Tag/discount icon -->
            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <rect x="4" y="4" width="16" height="16" rx="4" />
            <path d="M8 8h8v8H8z" />
            </svg>
        </span>
        <span class="font-semibold text-gray-700">Daily Mega Discounts</span>
        </div>
        <div class="flex items-center space-x-2">
        <span class="inline-flex justify-center items-center w-9 h-9 rounded-full bg-pink-50 text-pink-500">
            <!-- Tag/dollar icon -->
            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path d="M12 1v22M17 5H7a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2z" />
            <text x="9" y="16" font-size="8" fill="currentColor">$</text>
            </svg>
        </span>
        <span class="font-semibold text-gray-700">Best Price On The Market</span>
        </div>
    </div>
    </div>

    <!-- Footer -->
    <footer 
    class="bg-white border-t border-gray-100  bg-cover"
    style="background-image: url({{ asset('images/frontend/footer-shape.png') }});"
    >
    <div class="max-w-7xl mx-auto px-4 py-12 flex flex-col md:flex-row flex-wrap gap-12   text-sm">
        <!-- Brand/Address -->
        <div class="flex-1 min-w-[220px] mb-7 md:mb-0">
        <div class="flex items-center mb-4">
            <img
                class="dark:hidden max-h-[80px]"
                src="{{ config('settings.site_logo_lite') ?? asset('images/logo/lara-dashboard.png') }}"
                alt="{{ config('app.name') }}"
            />
        </div>
        <p class="mb-4 text-gray-500">We are a friendly bar serving a variety of cocktails, wines and beers. Our bar is a perfect place for a couple.</p>
        <ul class="text-gray-400 text-xs space-y-1 mb-3">
            <li class="flex items-center">
            <svg class="w-5 h-5 mr-1 text-green-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M17.657 16.657L13.414 21H7a2 2 0 0 1-2-2v-6.414l4.343-4.243a8 8 0 1 1 8.314 8.314z"/></svg>
            1418 Riverwood Drive, CA 96052, US
            </li>
            <li class="flex items-center">
            <svg class="w-5 h-5 mr-1 text-green-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M16 12A4 4 0 1 1 12 8a4 4 0 0 1 4 4z"/><path d="M4 12v3.5A1.5 1.5 0 0 0 5.5 17H8"/></svg>
            support@fastkart.com
            </li>
        </ul>
        </div>
        <!-- Categories -->
        <div class="flex-1 min-w-[150px] mb-7 md:mb-0">
        <h4 class="text-base font-bold text-gray-800 mb-4">Categories</h4>
        <ul class="space-y-2 text-gray-600">
            <li><a href="#" class="hover:text-green-500">Vegetables & Fruit</a></li>
            <li><a href="#" class="hover:text-green-500">Beverages</a></li>
            <li><a href="#" class="hover:text-green-500">Meats & Seafood</a></li>
            <li><a href="#" class="hover:text-green-500">Frozen Foods</a></li>
            <li><a href="#" class="hover:text-green-500">Biscuits & Snacks</a></li>
            <li><a href="#" class="hover:text-green-500">Grocery & Staples</a></li>
        </ul>
        </div>
        <!-- Useful Links -->
        <div class="flex-1 min-w-[140px] mb-7 md:mb-0">
        <h4 class="text-base font-bold text-gray-800 mb-4">Useful Links</h4>
        <ul class="space-y-2 text-gray-600">
            <li><a href="#" class="hover:text-green-500">Home</a></li>
            <li><a href="#" class="hover:text-green-500">Shop</a></li>
            <li><a href="#" class="hover:text-green-500">About Us</a></li>
            <li><a href="#" class="hover:text-green-500">Blog</a></li>
            <li><a href="#" class="hover:text-green-500">Contact Us</a></li>
        </ul>
        </div>
        <!-- Help Center -->
        <div class="flex-1 min-w-[140px] mb-7 md:mb-0">
        <h4 class="text-base font-bold text-gray-800 mb-4">Help Center</h4>
        <ul class="space-y-2 text-gray-600">
            <li><a href="#" class="hover:text-green-500">Your Order</a></li>
            <li><a href="#" class="hover:text-green-500">Your Account</a></li>
            <li><a href="#" class="hover:text-green-500">Track Order</a></li>
            <li><a href="#" class="hover:text-green-500">Your Wishlist</a></li>
            <li><a href="#" class="hover:text-green-500">Search</a></li>
            <li><a href="#" class="hover:text-green-500">FAQ</a></li>
        </ul>
        </div>
        <!-- Contact & App Download -->
        <div class="flex-1 min-w-[180px]">
        <h4 class="text-base font-bold text-gray-800 mb-4">Contact Us</h4>
        <div class="mb-3 text-gray-600">
            <div class="flex items-center mb-2">
            <svg class="w-5 h-5 mr-2 text-green-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M22 16.92V19a2 2 0 0 1-2 2h-7a2 2 0 0 1-2-2v-2.08m4-5.84A4 4 0 0 1 6 9V5A2 2 0 0 1 8 3h7a2 2 0 0 1 2 2v4a4 4 0 0 1-4 4z"/></svg>
            Hotline 24/7: 
            <span class="ml-1 text-green-600 font-semibold">+91 888 104 2340</span>
            </div>
            <div class="flex items-center">
            <svg class="w-5 h-5 mr-2 text-green-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M16 12A4 4 0 1 1 12 8a4 4 0 0 1 4 4z"/><path d="M4 12v3.5A1.5 1.5 0 0 0 5.5 17H8"/></svg>
            fastkart@hotmail.com
            </div>
        </div>
        <div class="mb-1 flex space-x-2">
            <!-- Google Play Button -->
            <a href="#" class="block">
            <img src="https://upload.wikimedia.org/wikipedia/commons/7/78/Google_Play_Store_badge_EN.svg" alt="Google Play" class="h-9 rounded" />
            </a>
            <!-- App Store Button -->
            <a href="#" class="block">
            <img src="https://developer.apple.com/assets/elements/badges/download-on-the-app-store.svg" alt="App Store" class="h-9 rounded" />
            </a>
        </div>
        </div>
    </div>
    <!-- Bottom Footer -->
    <div class="max-w-7xl mx-auto px-4 pb-5 border-t border-gray-100 flex flex-col md:flex-row justify-between items-center text-xs text-gray-400">
        <div class="mt-4 md:mt-0">
        © {{ __('Copyright') }} {{ date('Y') }}. {{ __('All rights reserved.') }}
        </div>
        <div class="flex items-center space-x-4 mt-4 md:mt-0">
        <!-- Payment icons (SVG or img, include if needed) -->
        <span class="inline-block"><img src="{{ asset('images/frontend/razorpay-logo.png') }}" alt="razorpay" class="h-6"/></span>
        <span class="inline-block"><img src="{{ asset('images/frontend/mastercard-logo.png') }}" alt="mastercard" class="h-6"/></span>
        <span class="inline-block"><img src="{{ asset('images/frontend/visa-logo.png') }}" alt="visa" class="h-6"/></span>
        <span class="inline-block"><img src="{{ asset('images/frontend/upi-logo.png') }}" alt="upi" class="h-6"/></span>        
        </div>
    </div>
    </footer>