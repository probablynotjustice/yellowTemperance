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
use App\Http\Controllers\Vendor\AuctionController;

Route::get('/', function () {
    return view('Landing');
})->name('home');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');


Route::prefix('admin')->group(function () {

    Route::get('/', function () {
        return view('admin.dashboard');
    })->name('admin.dashboard');

    Route::get('/products', function () {
        return view('admin.products.index');
    })->name('admin.products');

    Route::get('/products/create', function () {
        return view('admin.products.create');
    })->name('admin.products.create');

});

Route::prefix('base')->group(function () {
    Route::get('/dashboard', function () {

        $user = User::with('roles')->find(auth()->id());

        $wallet = User::with('wallet')->find(auth()->id());

        return view('base.dashboard', compact('user', 'wallet'));
    })->name('base.dashboard');
});


Route::get('/products', [ProductController::class, 'index']);
require __DIR__.'/settings.php';

Route::prefix('base')->group(function (){

    Route::Get('/comment', [CommentController::class, 'index'])
        ->name('base.comment');

    Route::post('/comment', [CommentController::class, 'store'])
        ->name('comment.store');

    Route::get('/ticketAll', [WalletController::class, 'index'])
        ->name('ticketAll');

});

Route::post('/wallet/add/custom', [WalletController::class, 'addCustom'])
    ->name('wallet.add.custom');

Route::post('wallet/add/{amount}', [WalletController::class, 'addPreset'])
    ->name('wallet.add');


// Below This line is All Vendor Focus
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
    Route::get(
        '/products/{product}/auction/create',
        [AuctionController::class, 'create']
    )->name('vendor.auctions.create');

    Route::post(
        '/products/{product}/auction',
        [AuctionController::class, 'store']
    )->name('vendor.auctions.store');
});


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

