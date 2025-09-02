<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Frontend\TempUserController;
use App\Http\Controllers\Frontend\ProductController;
use App\Http\Controllers\Frontend\CartController;
use App\Http\Controllers\Frontend\CheckoutController;
use App\Http\Controllers\Frontend\FarmerController;
use App\Http\Controllers\Frontend\AddressController;
use App\Http\Controllers\Frontend\OrderController;
use App\Http\Controllers\Frontend\WishlistController;



Route::get('/', 'HomeController@index')->name('index');

Route::post('/temp-user/send-otp', [TempUserController::class, 'sendOtp']);
Route::post('/temp-user/verify-otp', [TempUserController::class, 'verifyOtp']);

Route::get('/collection/{slug}', 'HomeController@getCollectionProduct')->name('collection.product');
Route::get('/product-search', 'HomeController@searchProduct')->name('collection.product.search');

Route::get('/product/{slug}', [ProductController::class, 'productDetail'])->name('product.detail');

// routes/web.php
Route::post('/wishlist/add', [WishlistController::class, 'addToWishlist'])->name('wishlist.add');
Route::get('/wishlist/count', [WishlistController::class, 'count'])->name('wishlist.count');

Route::post('/cart/add/{productId}', [CartController::class, 'add'])->name('cart.add');
Route::get('/cart/count', [CartController::class, 'count'])->name('cart.count');

Route::middleware(['auth:farmer'])->group(function () {
    Route::get('/wishlist', [WishlistController::class, 'index'])->name('wishlist.index');
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/update/{id}', [CartController::class, 'update'])->name('cart.update');
    Route::delete('/cart/remove/{id}', [CartController::class, 'remove'])->name('cart.remove');
    Route::post('/apply-promo', [CartController::class, 'applyPromo'])->name('promocode.apply');
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
});

Route::middleware(['auth:farmer'])->group(function () {
    Route::get('/user/farms/create', [FarmerController::class, 'farmCreate'])->name('user.farms.create');
    Route::post('/user/farms/create', [FarmerController::class, 'farmStore'])->name('user.farms.store');
    Route::get('/order-placed/{order_uid}', [OrderController::class, 'thankYou'])->name('user.order.thankyou');
});

// Farmers Profile Routes
Route::middleware(['auth:farmer'])
    ->prefix('user')
    ->as('user.')
    ->group(function () {

        Route::get('/profile', [FarmerController::class, 'index'])->name('profile');    
        Route::post('/update-profile', [FarmerController::class, 'updateProfile'])->name('profile.update');
        Route::post('/update-password', [FarmerController::class, 'updatePassword'])->name('profile.updatePassword');

        Route::get('/farms', [FarmerController::class, 'farmList'])->name('farms');
        
        Route::resource('addresses', AddressController::class)->except(['show']);
        Route::resource('orders', OrderController::class)->except(['edit', 'update', 'destroy']);        
        Route::post('/orders/capture-payment', [OrderController::class, 'capturePayment'])->name('orders.capturePayment');      
        Route::post('/orders/store-reviews', [OrderController::class, 'storeReview'])->name('orders.storeReview');

    });




