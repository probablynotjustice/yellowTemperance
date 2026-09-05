<?php

use Illuminate\Support\Facades\Route;

use App\Models\User;

use App\Http\Controllers\Base\AuctionController;
use App\Http\Controllers\Base\BidController;
use App\Http\Controllers\Base\CommentController;
use App\Http\Controllers\Base\WalletController;
use App\Http\Controllers\Base\InvoiceController;




Route::middleware(['auth', 'verified'])
    ->prefix('base')
    ->name('base.')
    ->group(function () {
        Route::get('/dashboard', function () {

            $user = User::with(['roles', 'wallet'])->find(auth()->id());

           // $wallet = User::with('wallet')->find(auth()->id());

            return view('base.dashboard', compact('user'));
        })->name('dashboard');


        Route::prefix('auctions')
            ->name('auctions.')
            ->group( function () {
                Route::get('/', [AuctionController::class, 'index'])
                    ->name('index');
                Route::get('/participating', [AuctionController::class, 'participating'])
                    ->name('participating');
                Route::get('/{auction}', [AuctionController::class, 'show'])
                    ->name('show');

                Route::post('/{auction}/bid', [BidController::class, 'store'])
                ->name('bid');
        });

        Route::prefix('wallet')
            ->name('wallets.')
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
            ->group( function() {
                Route::get('/', [CommentController::class, 'index'])
                    ->name('index');
                Route::post('/comment', [CommentController::class, 'store'])
                    ->name('store');
        });

        Route::prefix('invoices')
            ->name('invoices.')
            ->group(function () {

                Route::get('/', [InvoiceController::class, 'index'])
                    ->name('index');

                Route::get('/{invoice}', [InvoiceController::class, 'show'])
                    ->name('show');

        });
});


    Route::get('/ticketAll', [WalletController::class, 'index'])
           ->name('ticketAll');


