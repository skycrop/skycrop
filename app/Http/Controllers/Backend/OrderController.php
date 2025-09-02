<?php

namespace App\Http\Controllers\Backend;

use App\Enums\ActionType;
use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): Renderable
    {
        $this->checkAuthorization(Auth::user(), ['order.view']);

        $filters = [
            'search' => request('search'),
            'order_number' => request('order_number'),
            'sort_field' => null,
            'sort_direction' => null,
        ];

        
        if((auth()->user()->roles->contains('name', 'Supplier'))){
            $loginUserId = auth()->id();
            $query = Order::whereHas('products', function ($query) use ($loginUserId) {
                        $query->where('vendor_id', $loginUserId);
                    })->applyFilters($filters);
        }else{
            $query = Order::applyFilters($filters);
        }

        $orders = $query->paginateData([
            'per_page' => $filters['per_page'] ?? config('settings.default_pagination') ?? 10,
        ]);

        return view('backend.pages.order.index', [
            'orders' => $orders,            
            'breadcrumbs' => [
                'title' => __('Orders'),
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
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $this->checkAuthorization(Auth::user(), ['order.view']);

        if((auth()->user()->roles->contains('name', 'Supplier'))){
            $loginUserId = auth()->id();
            $order = Order::whereHas('products', function ($query) use ($loginUserId) {
                        $query->where('vendor_id', $loginUserId);
                })->where('order_number', $id)->first();
        }else{
            $order = Order::where('order_number', $id)->first();
        }

        if (! $order) {
            session()->flash('error', __('Order not found.'));
            return back();
        }

        return view('backend.pages.order.show', [
            'order' => $order,            
            'breadcrumbs' => [
                'title' => __('Order Detail'),
                'items' => [
                    [
                        'label' => __('Orders'),
                        'url' => route('admin.orders.index'),
                    ],
                ],
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

    public function bulkUpdate(Request $request) : RedirectResponse
    {        
        $this->checkAuthorization(Auth::user(), ['order.edit']);

        $ids = $request->input('ids', []);        

        if (empty($ids)) {
            return redirect()->route('admin.orders.index')
                ->with('error', __('No orders selected for updation'));
        }
        
        $orders = Order::whereIn('id', $ids)->get();
        $updateCount = 0;

        foreach ($orders as $order) {
            $order->status = $request->input('status');
            $order->save();
            $this->storeActionLog(ActionType::UPDATED, ['order' => $order]);
            $updateCount++;
            
        }

        if ($updateCount > 0) {
            session()->flash('success', __(':count orders updated successfully', ['count' => $updateCount]));
        } else {
            session()->flash('error', __('No orders were updated.'));
        }

        return redirect()->route('admin.orders.index');
    }


}
