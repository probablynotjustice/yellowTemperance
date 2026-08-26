<?php

use App\Http\Controllers\Base\WalletController;
use Illuminate\Support\Facades\Route;

use App\Models\User;

use App\Http\Controllers\Vendor\ProductController;
use App\Http\Controllers\Vendor\AuctionController;
use App\Http\Controllers\Base\AuctionController as Participant;



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
                Route::get('participate',[Participant::class, 'index'])
                    ->name('participate');
                Route::get('create', [AuctionController::class, 'create'])
                    ->name('create');
                Route::post('/', [AuctionController::class, 'store'])
                    ->name('store');
                Route::get('/{auction}', [AuctionController::class, 'show'])
                    ->name('show');
                Route::get('/{auction}/edit', [AuctionController::class, 'edit'])
                    ->name('edit');
                Route::put('/{auction}', [AuctionController::class, 'update'])
                    ->name('update');
                Route::delete('/{auction}', [AuctionController::class, 'destroy'])
                    ->name('destroy');
            });
});
