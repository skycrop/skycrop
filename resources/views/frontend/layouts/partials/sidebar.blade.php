<aside class="w-64 bg-green-500 text-white flex flex-col py-8">
    <div class="flex flex-col items-center text-center px-4">
        <img src="{{ asset('storage/'.$farmerDetail->photo) }}" 
             class="w-24 h-24 rounded-full border-4 border-green-200 shadow-lg" alt="Farmer">
        <h2 class="text-lg font-bold mt-4">{{ $farmerDetail->name }}</h2>        
    </div>

    <nav class="mt-8 flex-1">
        <ul class="space-y-2">
            <li>
                <a href="{{ route('user.profile') }}" 
                   class="block px-6 py-2 rounded-r-full font-medium transition
                   {{ request()->routeIs('user.profile') ? 'bg-green-700 hover:bg-green-800' : 'hover:bg-green-700' }}">
                    My Profile
                </a>
            </li>
            <li>
                <a href="{{ route('user.farms') }}" 
                   class="block px-6 py-2 rounded-r-full font-medium transition
                   {{ request()->routeIs('user.farms', 'user.farms.create') ? 'bg-green-700 hover:bg-green-800' : 'hover:bg-green-700' }}">
                    My Farms
                </a>
            </li>
            <li>
                <a href="{{ route('user.addresses.index') }}" 
                   class="block px-6 py-2 rounded-r-full font-medium transition
                   {{ request()->routeIs('user.addresses.index', 'user.addresses.create', 'user.addresses.edit') ? 'bg-green-700 hover:bg-green-800' : 'hover:bg-green-700' }}">
                    Shipping Address
                </a>
            </li>
            <li>
                <a href="{{ route('user.orders.index') }}" 
                   class="block px-6 py-2 rounded-r-full font-medium transition
                   {{ request()->routeIs('user.orders.index', 'user.orders.show') ? 'bg-green-700 hover:bg-green-800' : 'hover:bg-green-700' }}">
                    My Orders
                </a>
            </li>
        </ul>
    </nav>
</aside>
