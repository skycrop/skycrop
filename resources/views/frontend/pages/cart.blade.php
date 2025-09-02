@extends('frontend.layouts.app')

@section('title')
    {{ $breadcrumbs['title'] }} | {{ config('app.name') }}
@endsection

@section('main-content')

@if(count($cartItems))
<div 
    x-data="cartPage()" 
    class="min-h-screen bg-green-50 py-10 px-4"
>
    <div class="max-w-7xl mx-auto px-4">
        
        <!-- Title -->
        <h1 class="text-3xl font-bold text-gray-800 mb-8">Your Cart</h1>

        
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            <!-- CART ITEMS -->
            <div class="lg:col-span-2 space-y-6 bg-white rounded-xl shadow-md p-5">
                
                <template x-for="(item, index) in cartItems" :key="item.id">
                    <div class="flex flex-col sm:flex-row items-center bg-white border border-gray-300 transition p-5">
                        
                        <!-- IMAGE -->
                        <img :src="item.image" :alt="item.name"
                             class="w-28 h-28 rounded-lg object-cover border">

                        <!-- DETAILS -->
                        <div class="flex-1 sm:ml-6 mt-4 sm:mt-0">
                            <h2 class="text-lg font-semibold text-gray-900" x-text="item.name"></h2>
                            <p class="text-sm text-gray-500" x-text="'Size: ' + item.size"></p>
                            <div class="flex items-center gap-2 mt-2">
                                <span class="text-green-600 font-bold text-lg"
                                      x-text="'₹' + (item.price * item.qty)"></span>
                                <span class="text-gray-400 line-through"
                                      x-text="'₹' + (item.mrp * item.qty)"></span>
                            </div>
                        </div>

                        <!-- QTY CONTROLS -->
                        <div class="flex items-center mt-4 sm:mt-0 border rounded-lg overflow-hidden shadow-sm">
                            <button @click="decreaseQty(index)" 
                                    class="px-3 py-1 bg-gray-100 hover:bg-gray-200 transition">–</button>
                            <input type="text" x-model.number="item.qty"
                                   class="w-12 text-center border-x border-gray-200 outline-none">
                            <button @click="increaseQty(index)" 
                                    class="px-3 py-1 bg-gray-100 hover:bg-gray-200 transition">+</button>
                        </div>

                        <!-- REMOVE -->
                        <button @click="removeItem(index)" 
                                class="ml-4 text-red-500 hover:text-red-700 transition">✕</button>
                    </div>
                </template>

            </div>

            <!-- ORDER SUMMARY -->
            <div class="bg-white rounded-xl shadow-md p-6 h-fit sticky top-6">
                <h2 class="text-xl font-bold text-gray-800 mb-4">Order Summary</h2>

                <div class="flex mb-5">
                    <input type="text" id="promo-code" placeholder="Enter discount code"
                           class="flex-1 px-4 py-2 border border-gray-300 rounded-l-lg focus:ring-2 focus:ring-green-500 outline-none">
                    <button @click="applyPromo()" class="bg-green-600 text-white px-4 rounded-r-lg hover:bg-green-700 transition">
                        Apply
                    </button>
                </div>

                <div class="flex justify-between text-sm mb-2">
                    <span>Subtotal</span>
                    <span x-text="'₹' + subtotal"></span>
                </div>
                <div class="flex justify-between text-sm mb-2">
                    <span>Discount</span>
                    <span class="text-green-600" x-text="'₹' + discount"></span>
                </div>

                <template x-if="promoDiscount > 0">
                <div class="flex justify-between text-sm mb-2">
                    <span>Promo Discount</span>
                    <span class="text-green-600" x-text="'₹' + promoDiscount"></span>
                </div>
                </template>

                <div class="flex justify-between text-sm mb-4">
                    <span>Delivery</span>
                    <span class="text-green-600">Free</span>
                </div>

                <hr class="my-4">

                <div class="flex justify-between text-base font-bold">
                    <span>Total</span>
                    <span x-text="'₹' + total"></span>
                </div>

                <button onclick="window.location.href='{{ route('checkout.index') }}'" class="mt-6 w-full bg-gradient-to-r from-green-500 to-green-600 text-white py-3 rounded-lg font-medium hover:from-green-600 hover:to-green-700 shadow-lg transition">
                    Proceed to Checkout
                </button>
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
        <h2 class="text-2xl font-bold text-gray-800">Your cart is empty</h2>
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
    $items = $cartItems->map(function($item) {
        return [
            'id' => $item->id,
            'name' => $item->product->name,
            'size' => $item->variant->pack_description,
            'price' => $item->variant->selling_price,            
            'mrp' => $item->variant->mrp,            
            'qty' => $item->quantity,            
            'image' => asset('storage/' . $item->product->cover_image),            
        ];
    });    
@endphp

@push('scripts')
<script>
function cartPage() {
    return {        
        cartItems: @json($items),
        promoDiscount: 0,

        get subtotal() {
            return this.cartItems.reduce((sum, item) => sum + (item.price * item.qty), 0);
        },
        get discount() {
            return this.cartItems.reduce((sum, item) => sum + ((item.mrp - item.price) * item.qty), 0);
        },
        get total() {
            return this.subtotal - this.promoDiscount;
        },

        async applyPromo() {
            let code = document.getElementById('promo-code').value.trim();
            if (!code) {
                notyf.error("Please enter a promo code");
                return;
            }

            try {
                let response = await fetch("{{ route('promocode.apply') }}", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": "{{ csrf_token() }}"
                    },
                    body: JSON.stringify({
                        code: code,
                        subtotal: this.subtotal
                    })
                });

                let data = await response.json();

                if (response.ok && data.success) {                    
                    this.promoCode = code;
                    this.promoDiscount = parseFloat(data.discount_amount);
                    notyf.success("Promo applied successfully!");

                    localStorage.setItem('promoCode', code);
                    localStorage.setItem('promoDiscount', data.discount_amount);

                } else {
                    this.promoCode = '';
                    this.promoDiscount = 0;
                    localStorage.removeItem('promoCode');
                    localStorage.removeItem('promoDiscount');
                    notyf.error(data.message || "Invalid promo code");
                }
            } catch (err) {
                console.error(err);
                notyf.error("Something went wrong. Try again.");
            }
        },

        increaseQty(index) {
            this.cartItems[index].qty++;
            this.updateCartOnServer(this.cartItems[index]);
        },

        decreaseQty(index) {
            if (this.cartItems[index].qty > 1) {
                this.cartItems[index].qty--;
                this.updateCartOnServer(this.cartItems[index]);
            }
        },

        removeItem(index) {
            const item = this.cartItems[index];
            fetch(`/cart/remove/${item.id}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Content-Type': 'application/json'
                }
            })
            .then(res => {
                if (!res.ok) throw new Error("Failed to remove item");                
                this.cartItems.splice(index, 1);
                notyf.success('Item removed from cart!');
                fetch("{{ route('cart.count') }}")
                .then(res => res.json())
                .then(data => {
                    document.getElementById('cart-count-badge').textContent = data.count;
                    if(data.count == 0){
                        window.location.reload();
                    }
                });
            })
            .catch(err => {
                console.error(err);
                notyf.error(err);
            });
        },

        updateCartOnServer(item) {
            fetch(`/cart/update/${item.id}`, {
                method: 'POST', // or PUT if you like REST style
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    quantity: item.qty
                })
            })
            .then(res => {
                if (!res.ok) throw new Error("Failed to update cart");
                return res.json();
            })
            .then(data => {                
                notyf.success('Cart updated');
                fetch("{{ route('cart.count') }}")
                .then(res => res.json())
                .then(data => {
                    document.getElementById('cart-count-badge').textContent = data.count;
                });
            })
            .catch(err => {
                console.error(err);
                notyf.error('Could not update cart');
            });
        }
    }
}
</script>
@endpush