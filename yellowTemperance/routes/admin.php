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


use App\Http\Controllers\Admin\ProductController        as AdminProdcuctController;



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
