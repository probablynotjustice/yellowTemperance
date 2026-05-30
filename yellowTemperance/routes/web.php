<?php
use App\Models\User;
use App\Models\Role;
use App\Models\Comment;

use Illuminate\Support\Facades\Route;
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



        return view('base.dashboard', compact('user'));
    })->name('base.dashboard');
});


Route::get('/products', [ProductController::class, 'index']);
require __DIR__.'/settings.php';

Route::prefix('base')->group(function (){

    Route::Get('/comment', function () {
        return view('/base/comment');
    })->name('base.comment');

    Route::post('/base/comment', function (Request $request)  {

            $validated = $request->validate([
        'comment' => ['required', 'string', 'max:1000'],
    ]);

    Comment::create([
        'comment' => $validated['comment'],
        'customer_id' => auth()->id(),
    ]);

    return redirect()->back();
    })->name('Comment');

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
