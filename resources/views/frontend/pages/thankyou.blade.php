@extends('frontend.layouts.app')

@section('title')
    {{ $breadcrumbs['title'] }} | {{ config('app.name') }}
@endsection

@section('main-content')
<div class="max-w-xl mx-auto my-20 p-6 bg-white rounded-lg shadow text-center">
    <h1 class="text-3xl font-bold text-green-700 mb-6">Thank You for Your Order!</h1>
    
    <p class="mb-4 text-lg">
        Your order <strong>#{{ $order_number }}</strong> has been placed successfully.
    </p>
    
    <a href="{{ route('user.orders.index') }}" 
       class="inline-block mt-6 px-8 py-3 bg-green-600 text-white rounded-lg font-semibold hover:bg-green-700 transition">
        Go to My Orders
    </a>
</div>

@endsection
