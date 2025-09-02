<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Order;
use App\Models\OrderProduct;
use App\Models\Product;
use App\Models\ProductRating;
use App\Models\Address;
use App\Models\Cart;
use App\Models\StoreDetail;
use App\Models\StoreInventory;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Razorpay\Api\Api;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $farmerDetail = Auth::guard('farmer')->user();
        $orders = Order::where('farmer_id', $farmerDetail->id)->orderBy('id', 'desc')->paginate(5);        

        return view('frontend.pages.orders.index', [
            'farmerDetail' => $farmerDetail,
            'orders' => $orders,
            'breadcrumbs' => [
                'title' => __('My Orders'),
            ],
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $farmerId = Auth::guard('farmer')->id();
        $orderUid = 'ORD-' . date('Ymd') . '-' . strtoupper(Str::random(6));
        $shippingAddress = Address::where('farmer_id', $farmerId)->find($request->address_id);
        $farmerCart = Cart::with('product','variant')->where('user_id', $farmerId)->get();

        $nearByVendors = $this->getNearbyVendors($shippingAddress->latitude, $shippingAddress->longitude);
        $availableVendors = $this->filterVendorsWithStock($nearByVendors, $farmerCart);        

        $encryptedUid = Crypt::encryptString($orderUid);

        DB::beginTransaction();
        try {
            $order = Order::create([
                'order_number' => $orderUid,
                'farmer_id' => $farmerId,
                'subtotal' => $request->subtotal,
                'discount' => $request->promo_discount,
                'shipping_cost' => $request->delivery_fee,
                'grand_total' => $request->total,
                'payment_method' => $request->payment_method,                
                'discount_code' => $request->promo_code,                
                'shipping_name' => $shippingAddress->full_name,
                'shipping_phone' => $shippingAddress->phone,
                'shipping_address' => $shippingAddress->address_line_1 . ', ' . $shippingAddress->address_line_2,
                'shipping_city' => $shippingAddress->city,
                'shipping_state' => $shippingAddress->state,
                'shipping_zip' => $shippingAddress->zipcode,                
            ]);

            foreach ($farmerCart as $item) {



                $snapshot = [
                    'product' => $item->product->toArray(),
                    'variant' => $item->variant->toArray(),
                ];

                $order->products()->create([
                    'product_id' => $item->product_id,
                    'variant_id' => $item->variant_id ?? null,
                    'vendor_id' => $availableVendors->first(),
                    'product_name'=> $item->product->name,
                    'variant_name' => $item->variant->pack_description ?? null,
                    'price' => $item->variant->selling_price,
                    'quantity' => $item->quantity,
                    'total' => $item->variant->selling_price * $item->quantity,
                    'product_snapshot' => json_encode($snapshot),
                ]);
            }

            // If COD, complete order now and show Thank You page
            if ($request->payment_method === 'cod') {
                DB::commit();
                // You can redirect or return success JSON:
                // return redirect()->route('order.thankyou', $order->order_uid);
                return response()->json([
                    'status' => 'success',
                    'message' => 'Order placed successfully.',
                    'order_uid' => $order->order_number,
                    'redirect_url' => route('user.order.thankyou', $encryptedUid),
                ]);
            }

            // If Razorpay, return order details for the frontend to process Razorpay payment
            
            $api = new Api(env('RAZORPAY_API_KEY'), env('RAZORPAY_API_SECRET'));            

            $razorpayOrder = $api->order->create([
                'receipt'         => $order->order_number,
                'amount'          => (int) round($order->grand_total * 100),
                'currency'        => 'INR',
                'payment_capture' => 1,
            ]);

            DB::commit();
            return response()->json([
                'status'    => 'pending',
                'message'   => 'Proceed to payment',
                'order_uid' => $order->order_number,
                'order_id'  => $order->id,
                'amount'    => $order->grand_total,
                'razorpay_order_id' => $razorpayOrder['id'],
                'farmer'    => [
                    'name'    => $order->farmer->name,
                    'email'   => $order->farmer->email,
                    'contact' => $order->farmer->phone_number,
                ],
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => 'Order creation failed: ' . $e->getMessage()
            ], 500);
        }
    }


    public function capturePayment(Request $request)
    {
        $request->validate([
            'order_uid' => 'required|exists:orders,order_number',
            'payment_id' => 'required|string',
        ]);

        $order = Order::where('order_number', $request->order_uid)->firstOrFail();
        $encryptedUid = Crypt::encryptString($order->order_number);

        $order->payment_id = $request->payment_id;
        $order->payment_status = 'paid';
        $order->save();

        return response()->json([
            'status' => 'success',
            'redirect_url' => route('user.order.thankyou', $encryptedUid)
        ]);
    }

    public function thankYou($order_number)
    {  
        $farmerId = Auth::guard('farmer')->id();
        $orderUid = Crypt::decryptString($order_number);
        Cart::where('user_id', $farmerId)->delete();

        $order = Order::where('order_number', $orderUid)->where('farmer_id', $farmerId)->firstOrFail();
        return view('frontend.pages.thankyou', [
            'order_number' => $orderUid,
            'breadcrumbs' => [
                'title' => __('Thank You'),
            ],
        ]);
    }



    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $farmerDetail = Auth::guard('farmer')->user();
        $order = Order::where('order_number', $id)->where('farmer_id', $farmerDetail->id)->first();        

        return view('frontend.pages.orders.show', [
            'farmerDetail' => $farmerDetail,
            'order' => $order,
            'breadcrumbs' => [
                'title' => __('Order Details'),
            ],
        ]);
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function storeReview(Request $request)
    {
        $validated = $request->validate([            
            'order_id' => 'required|exists:orders,id',
            'rating' => 'required|integer|min:0|max:5',
            'review' => 'nullable|string',
        ]);

        $farmerId = Auth::guard('farmer')->id();

        $product = Product::where('slug', $request->input('product_slug'))->first();        

        $review = ProductRating::create([
            'product_id' => $product->id,
            'farmer_id' => $farmerId,
            'order_id' => $validated['order_id'],
            'rating' => $validated['rating'],
            'review' => $validated['review'],
        ]);

        $product->total_reviews += 1;        
        $product->average_rating = ProductRating::where('product_id', $product->id)->avg('rating');
        $product->save();

        return redirect()->back()->with('success', 'Thank you for your review!');
    }


    public function getNearbyVendors($latitude, $longitude)
    {
        $radius = 10; // 10 KM radius

        $vendors = StoreDetail::select(
                'store_details.*',
                DB::raw("(
                    6371 * acos(
                        cos(radians($latitude)) *
                        cos(radians(latitude)) *
                        cos(radians(longitude) - radians($longitude)) +
                        sin(radians($latitude)) *
                        sin(radians(latitude))
                    )
                ) AS distance")
            )
            ->having('distance', '<=', $radius)
            ->orderBy('distance', 'asc')
            ->get();

        return $vendors;
    }

    public function filterVendorsWithStock($nearByVendors, $farmerCart)
    {
        $vendorIds = $nearByVendors->pluck('user_id');

        // Loop through each vendor and check stock
        $availableVendors = $vendorIds->filter(function($vendorId) use ($farmerCart) {
            foreach ($farmerCart as $cartItem) {
                $hasStock = StoreInventory::where('user_id', $vendorId)
                    ->where('product_id', $cartItem->product_id)
                    ->where('variant_id', $cartItem->variant_id)
                    ->where('stock_quantity', '>=', $cartItem->quantity)
                    ->exists();

                if (!$hasStock) {
                    return false; // if one product is missing, skip this vendor
                }
            }
            return true;
        });

        return $availableVendors;
    }
}
