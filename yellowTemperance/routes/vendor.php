<?php

use Illuminate\Support\Facades\Route;

use App\Models\User;

use App\Http\Controllers\Vendor\ProductController;
use App\Http\Controllers\Vendor\AuctionController;



Route::prefix('vendor')->group( function () {
    Route::get('/vashboard', function () {
        $user = User::with('roles')->find(auth()->id());
        return view('vendor.vashboard', compact('user'));
    })->name('vashboard');

    Route::get('/product', [ProductController::class, 'index'])
        ->name('vendor.products');

    Route::post('/product', [ProductController::class, 'store'])
        ->name('vendor.products.store');

    Route::get('/products/{product}', [ProductController::class, 'show'])
        ->name('vendor.products.show');

    //Below this line is the Auction work
    Route::get('/products/{product}/auction/create', [AuctionController::class, 'create'])
        ->name('vendor.auctions.create');

    Route::post('/products/{product}/auction', [AuctionController::class, 'store'])
        ->name('vendor.auctions.store');
});
