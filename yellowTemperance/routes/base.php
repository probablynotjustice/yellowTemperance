<?php

use Illuminate\Support\Facades\Route;

use App\Models\User;

use App\Http\Controllers\Base\AuctionController;
use App\Http\Controllers\Base\BidController;
use App\Http\Controllers\Base\CommentController;
use App\Http\Controllers\Base\WalletController;



Route::middleware(['auth', 'verified'])
    ->prefix('base')
    ->name('base.')
    ->group(function () {
        Route::get('/dashboard', function () {

            $user = User::with(['roles', 'wallet'])->find(auth()->id());

           // $wallet = User::with('wallet')->find(auth()->id());

            return view('base.dashboard', compact('user'));
        })->name('dashboard');

        //Auction and bid Functionality

            Route::prefix('auctions')->group(function () {

            Route::get('/', [AuctionController::class, 'index'])
                ->name('auctions.index');

            Route::get('/{auction}', [AuctionController::class, 'show'])
                ->name('auctions.show');

            Route::post('/{auction}/bid', [BidController::class, 'store'])
                ->name('auctions.bid');

        });
});
        //Wallet Functions

Route::prefix('wallet')
    ->name('wallet.')
    ->group(function() {
        Route::get('/', [WalletController::class, 'index'])
            ->name('index');
        Route::post('/wallet/add/custom', [WalletController::class, 'addCustom'])
            ->name('add.custom');
        Route::post('wallet/add/{amount}', [WalletController::class, 'addPreset'])
            ->name('add');
});


Route::prefix('comments')
    ->name('comments.')
    ->group(function () {
        Route::get('/comment', [CommentController::class, 'index'])
            ->name('index');

        Route::post('/comment', [CommentController::class, 'store'])
            ->name('store');


});
        //Route::get('/ticketAll', [WalletController::class, 'index'])
        //    ->name('ticketAll');

