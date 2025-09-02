<?php

namespace App\Providers;

use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use App\Services\CollectionService;
use App\Models\Cart;
use App\Models\Wishlist;

class ViewServiceProvider extends ServiceProvider
{
    public function boot()
    {
        View::composer('frontend.layouts.app', function ($view) {
            $collectionService = app(CollectionService::class);
            $cartCount = Cart::where('user_id', auth()->guard('farmer')->id())->sum('quantity');
            $wishlistCount = Wishlist::where('farmer_id', auth()->guard('farmer')->id())->count();

            $view->with('collections', $collectionService->getParentCollections());
            $view->with('cartCount', $cartCount);
            $view->with('wishlistCount', $wishlistCount);
        });
    }
}
