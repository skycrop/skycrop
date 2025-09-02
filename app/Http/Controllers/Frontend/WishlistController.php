<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Wishlist;
use Illuminate\Support\Facades\Auth;
use Illuminate\Contracts\Support\Renderable;

class WishlistController extends Controller
{
    public function index(): Renderable
    {
        $wishlistItems = Wishlist::with(['product'])->where('farmer_id', auth()->guard('farmer')->id())->get();

        return view('frontend.pages.wishlist', [
            'wishlistItems' => $wishlistItems,
            'breadcrumbs' => [
                'title' => __('My Wishlist'),                
            ],
        ]);
    }

    public function addToWishlist(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
        ]);

        $farmerId = Auth::guard('farmer')->id();
        if(!$farmerId){
            return response()->json([
                'message' => 'Please login to add items to the wishlist.'
            ], 401);
        }

        $deleted = Wishlist::where('farmer_id', $farmerId)
            ->where('product_id', $request->product_id)
            ->delete();

        if ($deleted) {
            return response()->json(['message' => 'Product removed from wishlist'], 200);
        }


        // Add to wishlist
        Wishlist::create([
            'farmer_id' => $farmerId,
            'product_id' => $request->product_id,
        ]);

        return response()->json(['message' => 'Product added to wishlist'], 201);
    }

    public function count()
    {
        $userId = auth()->guard('farmer')->id();
        $count = Wishlist::where('farmer_id', $userId)->count();
        return response()->json(['count' => $count]);
    }
}
