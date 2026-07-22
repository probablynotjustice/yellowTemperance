<?php
use App\Models\User;
use App\Models\Role;
use App\Models\Comment;
use App\Models\Wallet;
use App\Models\Product;

use Illuminate\Support\Facades\Route;
// use Illuminate\Support\Facades\Request;
use Illuminate\Http\Request;
// use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Base\CommentController;
use App\Http\Controllers\Vendor\ProductController;
use App\Http\Controllers\Base\WalletController;
use App\Http\Controllers\Vendor\AuctionController       as VendorAuctionController;
use App\Http\Controllers\Base\AuctionController         as BaseAuctionController;
use App\Http\Controllers\Base\BidController;

use App\Http\Controllers\Admin\ProductController        as AdminProdcuctController;

Route::get('/', function () {
    return view('Landing');
})->name('home');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');


Route::prefix('admin')
    ->middleware(['auth', 'role:admin'])
    ->name('admin.')
    ->group(function () {

        Route::get('/', function () {
            return view('admin.dashboard');
        })->name('dashboard');

        Route::get('/products', [AdminProdcuctController::class, 'index'])
            ->name('products.index');

        Route::get('/products/create', function () {
            return view('admin.products.create');
        })->name('products.create');

});

// All base Routing has been moved to base.php


Route::get('/products', [ProductController::class, 'index']);
require __DIR__.'/settings.php';



Route::post('/wallet/add/custom', [WalletController::class, 'addCustom'])
    ->name('wallet.add.custom');

Route::post('wallet/add/{amount}', [WalletController::class, 'addPreset'])
    ->name('wallet.add');


// All Vendor routing has been moved to vendor.php

//test Route
Route::get('/testrole', function () {

    $user = auth()->user();

    $user->roles()->attach(1); // assuming role ID 1 exists

    return 'role attached';
});

Route::get('/giverole', function () {

    $user = auth()->user();

    $user->roles()->attach(1); // assuming role id 1 exists

    return 'role assigned';
});

Route::get('/test-transaction', function () {

    $wallet = auth()->user()->wallet;

    $wallet->transactions()->create([
        'amount' => 100.00,
        'type' => 'deposit',
        'description' => 'Test deposit',
    ]);

    return 'Transaction created';
});

Route::get('/fix-wallets', function () {

    User::doesntHave('wallet')->get()->each(function ($user) {

        Wallet::create([
            'user_id' => $user->id,
            'balance' => 0,
        ]);

    });

    return 'Done';
});
//holding here for no
require __DIR__.'/auth.php';
require __DIR__.'/base.php';
require __DIR__.'/vendor.php';
require __DIR__.'/admin.php';
require __DIR__.'/settings.php';
