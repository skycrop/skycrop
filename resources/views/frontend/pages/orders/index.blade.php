@extends('frontend.layouts.app')

@section('title')
    {{ $breadcrumbs['title'] }} | {{ config('app.name') }}
@endsection

@section('main-content')
<div class="min-h-screen bg-green-50 py-10 px-4">
  <div class="max-w-7xl min-h-150 mx-auto bg-white shadow-xl rounded-2xl overflow-hidden flex">
    
    <!-- Sidebar -->
    @include('frontend.layouts.partials.sidebar')

    <!-- Main Content -->
    <div class="flex-1 p-8">
    <h1 class="text-2xl font-bold text-green-700 mb-6">My Orders</h1>
    <x-messages />

    <div class="space-y-3 border-t border-gray-100 dark:border-gray-800 overflow-x-auto overflow-y-visible">
        <table id="dataTable" class="w-full dark:text-gray-300">
            <thead class="bg-light text-capitalize">
                <tr class="border-b border-gray-100 dark:border-gray-800">
                    <th width="5%" class="p-2 bg-gray-50 dark:bg-gray-800 dark:text-white text-left px-5 sm:px-6">
                        <div class="flex items-center">
                            #
                        </div>
                    </th>
                    <th width="15%" class="p-2 bg-gray-50 dark:bg-gray-800 dark:text-white text-left px-5">
                        <div class="flex items-center">
                            {{ __('Order ID') }}                            
                        </div>
                    </th> 
                    <th width="15%" class="p-2 bg-gray-50 dark:bg-gray-800 dark:text-white text-left px-5">
                        <div class="flex items-center">
                            {{ __('Total Amount') }}                            
                        </div>
                    </th>                                              
                    <th width="15%" class="p-2 bg-gray-50 dark:bg-gray-800 dark:text-white text-left px-5">
                        <div class="flex items-center">
                            {{ __('Order Status') }}                            
                        </div>
                    </th>                                              
                    <th width="15%" class="p-2 bg-gray-50 dark:bg-gray-800 dark:text-white text-left px-5">
                        <div class="flex items-center">
                            {{ __('Payment Status') }}                            
                        </div>
                    </th>                                              
                    <th width="15%" class="p-2 bg-gray-50 dark:bg-gray-800 dark:text-white text-left px-5">
                        <div class="flex items-center">
                            {{ __('Placed on') }}                            
                        </div>
                    </th>                                              
                    <th width="12%" class="p-2 bg-gray-50 dark:bg-gray-800 dark:text-white">{{ __('Action') }}</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($orders as $order)
                    <tr class="{{ $loop->index + 1 != count($orders) ?  'border-b border-gray-100 dark:border-gray-800' : '' }}">
                        <td class="px-5 py-4 sm:px-6">
                            {{ $loop->index + 1 }}
                        </td>
                        <td class="px-5 py-4 sm:px-6">
                            {{ $order->order_number }}
                        </td>
                        <td class="px-5 py-4 sm:px-6">
                            ₹{{ $order->grand_total }}
                        </td>                                
                        <td class="px-5 py-4 sm:px-6">
                            {{ ucfirst($order->status) }}
                        </td>                                
                        <td class="px-5 py-4 sm:px-6">
                            {{ ucfirst($order->payment_status) }}
                        </td>                                
                        <td class="px-5 py-4 sm:px-6">
                            {{ $order->created_at->format('F d, Y \a\t g:i A') }}
                        </td>                                
                        <td class="px-5 py-4 sm:px-6 flex justify-center">
                            <x-buttons.action-item                                
                                :href="route('user.orders.show', $order->order_number)"
                                icon="mdi:eye"
                                :label="__('View')"
                            />
                        </td>
                    
                    </tr>
                @empty
                    <tr class="border-b border-gray-100 dark:border-gray-800">
                        <td colspan="5" class="px-5 py-4 sm:px-6 text-center">
                            <span class="text-gray-500 dark:text-gray-300">{{ __('No Orders found') }}</span>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="my-4 px-4 sm:px-6">
            {{ $orders->links() }}
        </div>
    </div>
    </div>

  </div>
</div>



@endsection

@push('scripts')

@endpush