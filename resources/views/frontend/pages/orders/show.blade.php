@extends('frontend.layouts.app')

@section('title')
    {{ $breadcrumbs['title'] }} | {{ config('app.name') }}
@endsection

@section('main-content')
<div class="min-h-screen bg-green-50 py-10 px-4"  x-data="{ratingModalOpen: false, orderId: @json($order->id),productSlug : '', productName : ''}">
  <div class="max-w-7xl min-h-150 mx-auto bg-white shadow-xl rounded-2xl overflow-hidden flex">
    
    <!-- Sidebar -->
    @include('frontend.layouts.partials.sidebar')

    <!-- Main Content -->
    <div class="flex-1 p-8">
    <h1 class="text-2xl font-bold text-green-700 mb-6">My Orders</h1>
    <x-messages />

    <div class="mb-6 flex justify-end">
        <a href="{{ route('user.orders.index') }}"
        class="bg-green-500 text-white px-5 py-2 rounded-lg shadow hover:bg-green-600 transition font-medium flex items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline-block" viewBox="0 0 2048 2048"><path fill="currentColor" d="M2048 1088H250l787 787l-90 90L6 1024L947 83l90 90l-787 787h1798z"/></svg>
            Back
        </a>
    </div>

    <div class="space-y-3 border-t border-gray-100 dark:border-gray-800 overflow-x-auto overflow-y-visible pt-4">

        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 pb-4 border-b border-gray-200 dark:border-gray-700">
            <div>
                <h2 class="font-semibold">{{ __('Customer Details') }}</h2>
                <p>{{ $order->farmer->name ?? __('N/A') }}</p>
                <p>{{ $order->farmer->email ?? __('N/A') }}</p>
                <p>{{ $order->farmer->phone_number ?? __('N/A') }}</p>
            </div>
            <div>
                <h2 class="font-semibold">{{ __('Shipping Address') }}</h2>
                <p>{{ $order->shipping_name }}</p>
                <p>{{ $order->shipping_address }}</p>
                <p>
                {{ $order->shipping_city }}, {{ $order->shipping_state }} - {{ $order->shipping_zip }}
                </p>
                <p>Phone: {{ $order->shipping_phone }}</p>
            </div>
            <div>
                <h2 class="font-semibold">{{ __('Order Summary') }}</h2>
                <p>{{ __('Order ID:') }} <span class="capitalize">{{ $order->order_number }}</span></p>
                <p>{{ __('Status:') }} <span class="capitalize">{{ $order->status }}</span></p>
                <p>{{ __('Payment Status:') }} <span class="capitalize">{{ $order->payment_status }}</span></p>
                <p>{{ __('Placed on:') }} {{ $order->created_at->format('F d, Y \a\t g:i A') }}</p>
                <p>{{ __('Total:') }} ₹{{ number_format($order->grand_total, 2) }}</p>
            </div>
        </div>

        {{-- Ordered Items Table --}}
        <table class="w-full text-left border-collapse" >
            <thead>
                <tr class="border-b border-gray-300 dark:border-gray-700">
                    <th class="py-2 px-3">{{ __('Product') }}</th>
                    <th class="py-2 px-3">{{ __('Size/Pack') }}</th>
                    <th class="py-2 px-3">{{ __('Quantity') }}</th>
                    <th class="py-2 px-3">{{ __('Price') }}</th>
                    <th class="py-2 px-3">{{ __('Total') }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($order->products as $item)
                 @php
                    $hasRating = $order->ratings->contains('product_id', $item->product_id);
                @endphp
                    <tr class="border-b border-gray-200 dark:border-gray-800">
                        <td class="py-2 px-3">
                            {{ $item->product_name }}
                            @unless ($hasRating)
                            <br>
                            <span class="text-blue-600 cursor-pointer hover:underline" 
                                @click="
                                productName = '{{ $item->product_name }}';
                                productSlug = '{{ $item->product->slug }}';
                                ratingModalOpen = true;
                            ">
                                Rate this product
                            </span>
                            @endunless

                        </td>
                        <td class="py-2 px-3">
                            @if($item->variant_name)
                                {{ $item->variant_name }}
                            @else
                                {{ __('N/A') }}
                            @endif
                        </td>
                        <td class="py-2 px-3">{{ $item->quantity }}</td>
                        <td class="py-2 px-3">₹{{ number_format($item->price, 2) }}</td>
                        <td class="py-2 px-3">₹{{ number_format($item->price * $item->quantity, 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

    </div>


    </div>

  </div>


<!-- Bulk Delete Confirmation Modal -->
    <div
        x-cloak
        x-show="ratingModalOpen"
        x-transition.opacity.duration.200ms
        x-trap.inert.noscroll="ratingModalOpen"
        x-on:keydown.esc.window="ratingModalOpen = false"
        x-on:click.self="ratingModalOpen = false"
        class="fixed inset-0 z-50 flex items-center justify-center bg-black/20 p-4 backdrop-blur-md"
        role="dialog"
        aria-modal="true"
        aria-labelledby="bulk-delete-modal-title"
    >
            <div
                x-show="ratingModalOpen"
                x-transition:enter="transition ease-out duration-200 delay-100 motion-reduce:transition-opacity"
                x-transition:enter-start="opacity-0 scale-50"
                x-transition:enter-end="opacity-100 scale-100"
                class="flex max-w-md flex-col gap-4 overflow-hidden rounded-md border border-outline border-gray-100 dark:border-gray-800 bg-white text-on-surface dark:border-outline-dark dark:bg-gray-700 dark:text-gray-300 min-w-150"
            >
                <div class="flex items-center justify-between border-b border-gray-100 px-4 py-2 dark:border-gray-800">
                    <div class="flex items-center justify-center rounded-full bg-green-100 text-green-600 dark:bg-green-900/30 dark:text-green-400 p-1">
                        <svg class="w-6 h-6" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 11V6m0 8h.01M19 10a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                        </svg>
                    </div>
                    <h3 id="bulk-delete-modal-title" class="font-semibold tracking-wide text-gray-700 dark:text-white">
                        {{ __('Rate Product') }}
                    </h3>
                    <button
                        x-on:click="ratingModalOpen = false"
                        aria-label="close modal"
                        class="text-gray-400 hover:bg-gray-200 hover:text-gray-700 rounded-md p-1 dark:hover:bg-gray-600 dark:hover:text-white"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" aria-hidden="true" stroke="currentColor" fill="none" stroke-width="1.4" class="w-5 h-5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
                <div class="px-4 text-center">
                    <p class="text-gray-500 dark:text-gray-300">
                        <span x-text="productName"></span>                        
                    </p>
                </div>
                <div class="gap-3 border-t border-gray-100 p-4 dark:border-gray-800">
                    <form id="review-form"
                            action="{{ route('user.orders.storeReview') }}"
                            method="POST"
                            class="space-y-4">
                            @csrf                            

                            <!-- Hidden fields for product data -->
                            <input type="hidden" name="product_slug" x-model="productSlug">
                            <input type="hidden" name="order_id" x-model="orderId">                            

                            <!-- Star Rating -->
                            <div x-data="{ rating: 0 }" class="flex items-center space-x-1">
                                <template x-for="star in 5" :key="star">
                                    <svg @click="rating = star"
                                        :class="{'text-yellow-400': star <= rating, 'text-gray-300': star > rating}"
                                        xmlns="http://www.w3.org/2000/svg"
                                        viewBox="0 0 24 24"
                                        fill="currentColor"
                                        class="w-8 h-8 cursor-pointer transition">
                                        <path d="M12 .587l3.668 7.431L24 9.748l-6 5.847 1.416 8.266L12 19.771l-7.416 4.09L6 15.595 0 9.748l8.332-1.73z"/>
                                    </svg>
                                </template>
                                <input type="hidden" name="rating" x-model="rating">
                            </div>

                            <!-- Comment Box -->
                            <div>
                                <label for="review" class="block text-sm font-medium text-gray-700">Your Review</label>
                                <textarea id="review" name="review" rows="4"
                                        class="w-full mt-2 border border-gray-300 rounded-md shadow-sm focus:ring-green-500 focus:border-green-500 sm:text-sm p-2"
                                        placeholder="Write your review here..."></textarea>
                            </div>

                            <!-- Buttons -->
                            <div class="flex justify-end gap-3">
                                <button type="button"
                                        x-on:click="ratingModalOpen = false"
                                        class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200">
                                    {{ __('Cancel') }}
                                </button>
                                <button type="submit"
                                        class="px-4 py-2 text-sm font-medium text-white bg-green-600 rounded-md hover:bg-green-800 focus:outline-none focus:ring-2 focus:ring-green-300">
                                    {{ __('Save Rating') }}
                                </button>
                            </div>
                        </form>

                </div>
            </div>
    </div>




</div>




@endsection

@push('scripts')

@endpush