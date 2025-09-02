<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\Product;
use App\Models\Address;
use Illuminate\Support\Facades\Auth;

class CheckoutController extends Controller
{
    public function index()
    {
        $cartItems = Cart::with(['product', 'variant'])->where('user_id', auth()->guard('farmer')->id())->get();
        $addresses = Address::where('farmer_id', auth()->guard('farmer')->id())->orderBy('is_default', 'desc')->limit(3)->get();

        return view('frontend.pages.checkout', [
            'cartItems' => $cartItems,
            'addresses' => $addresses,
            'breadcrumbs' => [
                'title' => __('Checkout'),
            ],
        ]);
    }
}
