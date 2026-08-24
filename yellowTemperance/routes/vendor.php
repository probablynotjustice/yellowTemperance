<?php

use App\Http\Controllers\Base\WalletController;
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


        Route::prefix('products')
            ->name('products.')
            ->group(function () {
                Route::get('/', [ProductController::class, 'index'])
                    ->name('index');
                Route::get('create', [ProductController::class, 'create'])
                    ->name('create');
                Route::post('/', [ProductController::class, 'store'])
                    ->name('store');
                Route::get('/{product}', [ProductController::class, 'show'])
                    ->name('show');
                Route::get('/{product}/edit', [ProductController::class, 'edit'])
                    ->name('edit');
                Route::put('/{product}', [ProductController::class, 'update'])
                    ->name('update');
                Route::delete('/{product}', [ProductController::class, 'destroy'])
                    ->name('destroy');
            });

        Route::prefix('wallets')
            ->name('wallets.')
            ->group(function () {
                Route::get('/', [WalletController::class, 'index'])
                    ->name('index');

            });
        Route::prefix('auctions')
            ->name('auctions.')
            ->group( function () {
                Route::get('/', [AuctionController::class, 'index'])
                    ->name('index');
                Route::get('create', [AuctionController::class, 'create'])
                    ->name('create');
                Route::post('/', [AuctionController::class, 'store'])
                    ->name('store');
                Route::get('/{product}', [AuctionController::class, 'show'])
                    ->name('show');
                Route::get('/{product}/edit', [AuctionController::class, 'edit'])
                    ->name('edit');
                Route::put('/{product}', [AuctionController::class, 'update'])
                    ->name('update');
                Route::delete('/{product}', [AuctionController::class, 'destroy'])
                    ->name('destroy');
            });
});
