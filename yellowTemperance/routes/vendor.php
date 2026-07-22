<?php

use Illuminate\Support\Facades\Route;

use App\Models\User;

use App\Http\Controllers\Vendor\ProductController;
use App\Http\Controllers\Vendor\AuctionController;



Route::prefix('vendor')->name('vendor.')->group( function () {
    Route::get('/dashboard', function () {
        $user = User::with('roles')->find(auth()->id());
        return view('vendor.dashboard', compact('user'));
    })->name('dashboard');

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
