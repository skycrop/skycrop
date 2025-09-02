<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\Product;
use App\Models\Promocode;
use App\Models\ProductVariant;
use Illuminate\Support\Facades\Auth;
use Illuminate\Contracts\Support\Renderable;

class CartController extends Controller
{
    public function index(): Renderable
    {
        $cartItems = Cart::with(['product', 'variant'])->where('user_id', auth()->guard('farmer')->id())->get();

        return view('frontend.pages.cart', [
            'cartItems' => $cartItems,
            'breadcrumbs' => [
                'title' => __('Cart'),                
            ],
        ]);
    }

    public function add(Request $request, $productId)
    {
        try {
            //code...
            $userId = Auth::guard('farmer')->id();        
            
            if(!$userId){
                return response()->json([
                    'message' => 'Please login to add items to the cart.'
                ], 401);
            }
    
            $cartItem = Cart::where('user_id', $userId)
                ->where('product_id', $productId)
                ->where('variant_id', $request->input('variant_id'))
                ->first();
    
            if ($cartItem) {
                $cartItem->increment('quantity', 1);
            } else {
                Cart::create([
                    'user_id' => $userId,
                    'product_id' => $productId,
                    'variant_id' => $request->input('variant_id'),
                    'quantity' => 1
                ]);
            }
    
            
            return response()->json([
                'message' => 'Product added to cart!'
            ]);            
        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'An error occurred while adding the product to the cart.'
            ], 500);
        }
        
    }

    public function count()
    {
        $userId = auth()->guard('farmer')->id();
        $count = Cart::where('user_id', $userId)->sum('quantity');
        return response()->json(['count' => $count]);
    }



    public function remove($id)
    {
        try {    
            $userId = Auth::guard('farmer')->id();        
            
            if(!$userId){
                return response()->json([
                    'message' => 'Please login to update items to the cart.'
                ]);
            }

            $cartItem = Cart::where('user_id', $userId)->findOrFail($id);
            $cartItem->delete();
    
            return response()->json([
                'message' => 'Item removed from cart!'
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'An error occurred while removing the cart item.'
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $userId = Auth::guard('farmer')->id();        
            
            if(!$userId){
                return response()->json([
                    'message' => 'Please login to update items to the cart.'
                ]);
            }

            $cartItem = Cart::where('user_id', $userId)->findOrFail($id);
            $cartItem->update([
                'quantity' => $request->quantity
            ]);

            return response()->json([
                'message' => 'Updated cart items!'
            ]);
            
        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'An error occurred while updating the cart.'
            ], 500);
        }
    }

    public function applyPromo(Request $request)
    {
        $request->validate([
            'code' => 'required|string',
            'subtotal' => 'required|numeric|min:0'
        ]);

        $promocode = Promocode::where('code', $request->code)
            ->where('is_active', 1)
            ->whereDate('start_date', '<=', now())
            ->whereDate('end_date', '>=', now())
            ->first();

        if (!$promocode) {
            return response()->json(['success' => false, 'message' => 'Invalid or expired promo code'], 400);
        }

        $discountAmount = 0;
        if ($promocode->discount_type === 'fixed') {
            $discountAmount = min($promocode->discount_amount, $request->subtotal);
        } elseif ($promocode->discount_type === 'percent') {
            $discountAmount = round(($request->subtotal * $promocode->discount_amount) / 100, 2);
            if ($promocode->max_discount_amount) {
                $discountAmount = min($discountAmount, $promocode->max_discount_amount);
            }
        }

        return response()->json([
            'success' => true,
            'discount_amount' => $discountAmount,
        ]);
    }

}
