@extends('frontend.layouts.app')

@section('title')
    {{ $breadcrumbs['title'] }} | {{ config('app.name') }}
@endsection

@section('main-content')
@php
    $subtotal = 0;
    $discount = 0;
@endphp

<div x-data="checkout()" class="min-h-screen bg-green-50 py-10 px-4">
<div class="max-w-7xl mx-auto px-4 py-8 grid grid-cols-1 lg:grid-cols-3 gap-8 text-gray-800">
    
    <!-- Left Side: Checkout Forms -->
    <div class="lg:col-span-2 space-y-8">
        
        <!-- Shipping Address Section -->
        <div class="bg-white p-6 rounded-xl shadow-md">
            <h2 class="text-xl font-bold mb-4">Shipping Address</h2>

            <!-- Saved Addresses -->
            @foreach ($addresses as $address)
            <div class="space-y-3 mb-2">
                <label class="flex items-center space-x-3 border p-3 rounded-lg hover:border-green-500 cursor-pointer">
                    <input type="radio" value="{{ $address->id }}" name="address" @if($address->is_default) checked @endif class="text-green-600 focus:ring-green-500">
                    <div>
                        <p class="font-semibold">{{ $address->full_name }}</p>
                        <p class="text-sm text-gray-500">{{ $address->address_line_1 }}, {{ $address->address_line_2 }}</p>
                        <p class="text-sm text-gray-500">{{ $address->city }}, {{ $address->state }} - {{ $address->zipcode }}</p>                        
                        <p class="text-sm text-gray-500">Phone: {{ $address->phone }}</p>
                    </div>
                </label>
            </div>
            @endforeach

            <!-- Add New Address -->
            <div class="mt-6">
                <a href="{{ route('user.addresses.index') }}" class="text-green-600 text-sm font-semibold flex items-center gap-1 hover:underline">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24"
                         stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" 
                              d="M12 4v16m8-8H4" />
                    </svg>
                    Add New Address
                </a>
            </div>
        </div>

        <!-- Delivery Options -->
        <div class="bg-white p-6 rounded-xl shadow-md">
            <h2 class="text-xl font-bold mb-4">Delivery Options</h2>
            <div class="space-y-3">
                <label class="flex items-center justify-between border p-3 rounded-lg hover:border-green-500 cursor-pointer">
                <div class="flex items-center space-x-3">
                    <input type="radio" name="delivery" value="standard" x-model="selectedDelivery" class="text-green-600 focus:ring-green-500">
                    <span>Standard Delivery <span class="text-gray-500 text-sm">(3-5 days)</span></span>
                </div>
                <span :class="selectedDelivery === 'standard' ? 'text-green-600 font-semibold' : 'text-gray-400'">Free</span>
                </label>
                <label class="flex items-center justify-between border p-3 rounded-lg hover:border-green-500 cursor-pointer">
                <div class="flex items-center space-x-3">
                    <input type="radio" name="delivery" value="express" x-model="selectedDelivery" class="text-green-600 focus:ring-green-500">
                    <span>Express Delivery <span class="text-gray-500 text-sm">(1-2 days)</span></span>
                </div>
                <span :class="selectedDelivery === 'express' ? 'text-green-600 font-semibold' : 'text-gray-400'">₹150</span>
                </label>

            </div>
        </div>

        <!-- Payment Method -->
        <div class="bg-white p-6 rounded-xl shadow-md">
            <h2 class="text-xl font-bold mb-4">Payment Method</h2>
            <div class="space-y-3">
                <label class="flex items-center space-x-3 border p-3 rounded-lg hover:border-green-500 cursor-pointer">
                    <input type="radio" name="payment" value="cod" checked class="text-green-600 focus:ring-green-500">
                    <span>Cash on Delivery</span>
                </label>

                <label class="flex items-center space-x-3 border p-3 rounded-lg hover:border-green-500 cursor-pointer">
                    <input type="radio" name="payment" value="razorpay" class="text-green-600 focus:ring-green-500">
                    <span>
                        <span class="flex items-center gap-2">
                            <img src="https://cdn.razorpay.com/logo.svg" alt="Razorpay" class="w-6 h-6"> Razorpay (Card/UPI/Wallet)
                        </span>
                    </span>
                </label>
            </div>
        </div>

    </div>

    <!-- Right Side: Order Summary -->
    <div class="bg-white p-6 rounded-xl shadow-md h-fit sticky top-6">
        <h2 class="text-xl font-bold mb-4">Order Summary</h2>

        <!-- Cart Items -->
        <div class="space-y-4 border-b pb-4 mb-4">
            @foreach($cartItems as $item)
            @php
                // Add to subtotal
                $itemTotal = $item->variant->selling_price * $item->quantity;
                $subtotal += $itemTotal;

                // Add discount (MRP - Selling Price) * qty
                $discount += ($item->variant->mrp - $item->variant->selling_price) * $item->quantity;
            @endphp
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium">{{ $item->product->name }} (x{{ $item->quantity }})</p>
                    <span class="text-gray-500 text-sm">Size: {{ $item->variant->pack_description }}</span>
                </div>
                <span class="font-semibold">₹{{ number_format($itemTotal) }}</span>
            </div>
            @endforeach
        </div>

        <!-- Price Breakdown -->
        <div class="space-y-2 text-sm">
            <div class="flex justify-between"><span>Subtotal</span><span>₹<span x-text="subtotal.toLocaleString()"></span></span></div>
            <div class="flex justify-between"><span>Promo Discount</span><span class="text-green-600">₹<span x-text="promoDiscount"></span></span></div>
            <div class="flex justify-between"><span>Delivery</span><span class="text-green-600">₹<span x-text="deliveryFee.toLocaleString()"></span></span></div>
        </div>
        
        <hr class="my-4">

        <!-- Total -->
        <div class="flex justify-between text-lg font-bold mb-4">
            <span>Total</span>
            <span>₹<span x-text="total.toLocaleString()"></span></span>
        </div>

       <!-- AlpineJS Example (button inside your AlpineJS component) -->
        <button
            type="button"
            class="w-full bg-gradient-to-r from-green-500 to-green-600 text-white py-3 rounded-lg font-medium hover:from-green-600 hover:to-green-700 shadow-lg transition"
            :disabled="loading"
            @click="placeOrder"
        >
            <span x-show="!loading">Place Order</span>
            <span x-show="loading">Processing...</span>
        </button>

    </div>
</div>
</div>

@endsection

@push('scripts')
<script src="https://checkout.razorpay.com/v1/checkout.js"></script>
<script>
    function checkout() {
        return {        
            subtotal: {{ $subtotal }},
            //   discount: {{ $discount }},
            promoDiscount : 0,
            selectedDelivery: 'standard',
            deliveryFees: {
                standard: 0,
                express: 150
            },
            loading: false,
            get deliveryFee() {
                return this.deliveryFees[this.selectedDelivery] || 0;
            },
            get total() {
                return this.subtotal + this.deliveryFee - this.promoDiscount;
            },

            async placeOrder() {
                this.loading = true;
                try {
                    // Collect values from form (address, delivery, payment)
                    let addressId = document.querySelector('input[name="address"]:checked')?.value;
                    let paymentMethod = document.querySelector('input[name="payment"]:checked')?.value;

                    if (!addressId) {
                        notyf.error("Please select a shipping address");
                        this.loading = false;
                        return;
                    }

                    // Prepare order payload
                    let orderData = {
                        address_id: addressId,
                        delivery_method: this.selectedDelivery,
                        payment_method: paymentMethod,
                        subtotal: this.subtotal,
                        delivery_fee: this.deliveryFee,
                        promo_code: this.promoCode,
                        promo_discount: this.promoDiscount,
                        total: this.total,
                        _token: document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    };
                    

                    // Send request
                    const resp = await fetch('{{ route('user.orders.store') }}', {
                        method: 'POST',
                        headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify(orderData)
                    });

                    if (!resp.ok) throw new Error("Failed to place order");

                    const data = await resp.json();
                    if (data.status === 'success' && data.redirect_url) {
                        notyf.success('Order placed successfully!')
                        setTimeout(() => {
                            window.location.href = data.redirect_url;                            
                        }, 1000);
                    } else if (data.status === 'pending') {
                        // ==== Razorpay integration here ====                        
                        const amount = Math.round((data.amount || this.total) * 100);
                        const options = {
                            key: "{{ env('RAZORPAY_API_KEY') }}", 
                            amount: amount,
                            currency: "INR",
                            name: "SKYCROP",
                            description: `Order Payment for : ${data.order_uid}`, 
                            order_id: data.razorpay_order_id,                       
                            handler: function (response){
                                completeRazorpayOrder(data.order_uid, response.razorpay_payment_id);
                            },
                            prefill: {
                                name: data.farmer.name || "Guest User",
                                email: data.farmer.email,
                                contact: data.farmer.contact,
                            },
                            theme: { color: "#24a45e" }
                        };
                        const rzp = new Razorpay(options);
                        rzp.open();
                    } else {
                        notyf.error('Order Failed!')
                    }
                } catch (err) {
                    console.error(err);
                    notyf.error("Something went wrong while placing order");
                } finally {
                    // this.loading = false;
                }
            },

            init() {
                const code = localStorage.getItem('promoCode');
                const discount = localStorage.getItem('promoDiscount');

                if (code && discount) {
                    this.promoCode = code;
                    this.promoDiscount = parseFloat(discount);
                    console.log('discount', discount);
                    
                }
            }
        
        }
    }

    function completeRazorpayOrder(order_uid, payment_id) {
        fetch('{{ route('user.orders.capturePayment') }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                order_uid: order_uid,
                payment_id: payment_id
            })
        })
        .then(resp => resp.json())
        .then(result => {
            if (result.status === 'success' && result.redirect_url) {
                notyf.success('Order placed successfully!')
                localStorage.removeItem('promoCode');
                localStorage.removeItem('promoDiscount');
                window.location.href = result.redirect_url;                
            } else {
                notyf.error(result.message || 'Payment not captured');
            }
        });
    }
</script>
    
@endpush
