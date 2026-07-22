<?php

use Illuminate\Support\Facades\Route;

use App\Models\User;

use App\Http\Controllers\Base\AuctionController;
use App\Http\Controllers\Base\BidController;
use App\Http\Controllers\Base\CommentController;
use App\Http\Controllers\Base\WalletController;



Route::prefix('base')->group(function () {
    Route::get('/dashboard', function () {

        $user = User::with('roles')->find(auth()->id());

        $wallet = User::with('wallet')->find(auth()->id());

        return view('base.dashboard', compact('user', 'wallet'));
    })->name('base.dashboard');


    Route::get('/comment', [CommentController::class, 'index'])
        ->name('base.comment');

    Route::post('/comment', [CommentController::class, 'store'])
        ->name('comment.store');

    Route::get('/ticketAll', [WalletController::class, 'index'])
        ->name('ticketAll');
    //Wallet Functions
        Route::post('/wallet/add/custom', [WalletController::class, 'addCustom'])
        ->name('wallet.add.custom');
        Route::post('wallet/add/{amount}', [WalletController::class, 'addPreset'])
        ->name('wallet.add');




    //Auction and bid Functionality

        Route::prefix('auctions')->group(function () {

        Route::get('/', [AuctionController::class, 'index'])
            ->name('base.auctions.index');

        Route::get('/{auction}', [AuctionController::class, 'show'])
            ->name('base.auctions.show');

        Route::post('/{auction}/bid', [BidController::class, 'store'])
           ->name('base.auctions.bid');

    });
});
