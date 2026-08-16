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
});
