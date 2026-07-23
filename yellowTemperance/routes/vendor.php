<?php

use Illuminate\Support\Facades\Route;

use App\Models\User;

use App\Http\Controllers\Vendor\ProductController;
use App\Http\Controllers\Vendor\AuctionController;



Route::middleware(['auth', 'verified', 'role:vendor'])
    ->prefix('vendor')
    ->name('vendor.')
    ->group( function () {

        Route::get('/dashboard', function () {
            $user = User::with('roles')->find(auth()->id());
            return view('vendor.dashboard', compact('user'));
        })->name('dashboard');

        Route::get('/products', [ProductController::class, 'index'])
            ->name('products');

        Route::post('/products', [ProductController::class, 'store'])
            ->name('products.store');

        Route::get('/products/{product}', [ProductController::class, 'show'])
            ->name('products.show');

        //Below this line is the Auction work
        Route::get('/products/{product}/auction/create', [AuctionController::class, 'create'])
            ->name('auctions.create');

        Route::post('/products/{product}/auction', [AuctionController::class, 'store'])
            ->name('auctions.store');
});
