@extends('backend.layouts.app')

@section('title')
    {{ $breadcrumbs['title'] }} | {{ config('app.name') }}
@endsection

@section('admin-content')
<div class="p-4 mx-auto max-w-(--breakpoint-2xl) md:p-6" x-data="{ selectedBrand: [], selectAll: false, bulkDeleteModalOpen: false }">
    <x-breadcrumbs :breadcrumbs="$breadcrumbs" />

    {!! ld_apply_filters('brand_after_breadcrumbs', '') !!}

    <div class="space-y-6">
        <div class="rounded-md border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">            
            


            <div class="space-y-3 border-t border-gray-100 dark:border-gray-800 overflow-x-auto overflow-y-visible pt-4">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 p-4 border-b border-gray-200 dark:border-gray-700">
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
                <table class="w-full text-left border-collapse">
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
                            <tr class="border-b border-gray-200 dark:border-gray-800">
                                <td class="py-2 px-3">{{ $item->product_name }}</td>
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
</div>
@endsection
