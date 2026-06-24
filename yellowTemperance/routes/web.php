<?php
use App\Models\User;
use App\Models\Role;
use App\Models\Comment;
use App\Models\Wallet;

use Illuminate\Support\Facades\Route;
// use Illuminate\Support\Facades\Request;
use Illuminate\Http\Request;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Base\CommentController;


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

    Route::Get('/comment', function () {

        $comment = Comment::all();
        return view('base.comment', compact('comment'));
    })->name('base.comment');

    Route::post('/comment', function (Request $request)  {
        //Saves Comments
        $validated = $request->validate([
            'summary' => ['required', 'string', 'max:255'],
            'detail' => ['required', 'string'],
    ]);
        Comment::create([
            'summary' => $validated['summary'],
            'detail' => $validated['detail'],
            'customer_id' => auth()->id(),
            //Need to constrain to Vendors that the User has commonality with
            //temporary
            'vendor_id' =>1,
            //Will also need a Purchase_id to link to a receipt and product
        ]);

    return redirect()->back();
    })->name('comment.store');

    Route::get('/ticketAll', function () {

    $user = User::with([
        'roles',
        'wallet',
        'wallet.transactions' => function ($query) {
            $query->latest();
        }
        ])->find(auth()->id());

    return view('base.ticketAll', compact('user'));})->name('ticketAll');

});

Route::post('/wallet/add/custom', function (Request $request) {

    $validated = $request->validate([
        'amount' => ['required', 'numeric', 'min:1', 'max:1000'],
    ]);
    $wallet = auth()->user()->wallet;
    $amount = $validated['amount'];
    $wallet->increment('balance', $amount);
    $wallet->transactions()->create([
        'amount' => $amount,
        'type' => 'funding',
        'description' => "Custom deposit of: $amount",
    ]);
    return redirect()->back();
})->name('wallet.add.custom');

Route::post('wallet/add/{amount}', function ($amount) {
     abort_unless(in_array((int)$amount, [1, 10, 100]), 404);
    $wallet = auth()->user()->wallet;
    $wallet->increment('balance', $amount);
    $wallet->transactions()->create([
        'amount' => $amount,
        'type' => 'funding',
        'description' => 'added {$amount} credit',
    ]);
    return redirect()->back();
})->name('wallet.add');

Route::prefix('vendor')->group( function () {
    Route::get('/vashboard', function () {
        $user = User::with('roles')->find(auth()->id());
        return view('vendor.vashboard', compact('user'));
    })->name('vashboard');
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

