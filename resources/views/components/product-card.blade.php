@props([
    'productMeta' => []
])

@php
    $product = $productMeta;
@endphp

<div x-data="cartComponent()" class="border bg-white group relative flex flex-col p-4 transition hover:shadow-lg cursor-pointer">
    <!-- Product Image with hover icons -->
    <div class="h-50 flex items-center justify-center relative">                    
        <div class="relative w-full h-50 overflow-hidden flex justify-center items-center mb-4 rounded-lg bg-white p-2">
            <img src="{{ asset('storage/'.$product->cover_image) }}"
                alt="{{ $product->name }}"
                class="h-full w-full object-contain transition-transform duration-300 ease-in-out group-hover:scale-105">
        </div>

        <ul class="absolute inset-0 opacity-0 group-hover:opacity-100 
                flex gap-2 items-center justify-center 
                transition bg-white/80 rounded-xl z-10">
            <li>
                <a href="{{ route('product.detail', $product->slug) }}"  title="View"
                    class="p-2 bg-gray-100 hover:bg-green-500 hover:text-white rounded-full shadow flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M1 12s4-7 11-7 11 7 11 7-4 7-11 7-11-7-11-7z"/>
                    <circle cx="12" cy="12" r="3"/>
                    </svg>
                </a>
            </li>
            
            <li>
                <a href="#" title="Wishlist"
                @click.prevent="addToWishlist({{ $product->id }})"                
                class="p-2 bg-red-500 hover:bg-green-500 text-white rounded-full shadow flex items-center justify-center">
                <template x-if="isWLoading">                    
                    <svg class="w-5 h-5 animate-spin text-white" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" 
                                stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" 
                                d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z">
                        </path>
                    </svg>
                </template>  
                <template x-if="!isWLoading">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                        d="M4.318 6.318a4.5 4.5 0 0 1 6.364 0L12 7.636l1.318-1.318
                        a4.5 4.5 0 1 1 6.364 6.364L12 21.364l-7.682-7.682a4.5 4.5
                        0 0 1 0-6.364z" />
                    </svg>
                </template>  
                
                </a>
            </li>
        </ul>
    </div>


    <!-- Product Info -->
    <div class="pt-3 text-left">
        <a href="{{ route('product.detail', $product->slug) }}" class="block font-semibold truncate text-gray-800 hover:text-green-600">
            {{ $product->name }}
        </a>

        <!-- Rating -->
        <div class="flex items-center mt-2 text-yellow-500">
            @for($i = 0; $i < 5; $i++)
            <img src="{{ asset('images/star.svg') }}" alt="Star" class="w-4 h-4 inline-block text-yellow-400">
            @endfor
            <span class="text-sm font-medium text-gray-600">({{ $product->total_reviews }})</span>
        </div>

        <div class="text-gray-500 text-sm mb-1">{{ $product->defaultVariant->pack_description }}</div>

        <div class="flex items-center justify-between">
            <div class="flex justify-center items-center space-x-2 mb-1">
                <span class="text-green-600 font-bold text-lg">₹{{ $product->defaultVariant->selling_price }}</span>
                <span class="text-sm text-gray-400 line-through">₹{{ $product->defaultVariant->mrp }}</span>
            </div>

            <!-- Add Button / Quantity Controls -->
            <div class="flex gap-2">
            <button 
                :disabled="isLoading"                    
                @click="                        
                    addToCart({{ $product->id }}, {{ $product->defaultVariant->id }})
                "
                class="bg-green-500 hover:bg-green-600 text-white rounded-sm px-5 py-1 flex items-center gap-2 shadow">
                <template x-if="isLoading">
                    <svg class="w-5 h-5 animate-spin text-white" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" 
                                stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" 
                                d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z">
                        </path>
                    </svg>
                </template>                            
                Add<span class="text-xl leading-none">+</span>
            </button>
            </div>

            
        </div>
    </div>
</div>


@push('scripts')
<script>
function cartComponent() {
    return {
        qty: 0,
        isLoading: false,
        isWLoading: false,        
        addToCart(productId, variantId) {
            this.isLoading = true;                                
            const csrfToken = document.querySelector('meta[name="csrf-token"]').content;        

            fetch("{{ route('cart.add', '#') }}".replace('#', productId), {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": csrfToken
                },
                body: JSON.stringify({
                    variant_id: variantId,
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
        }
    }
}
</script>
@endpush