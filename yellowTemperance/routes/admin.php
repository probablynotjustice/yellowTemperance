<?php
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\DashboardController;

use App\Models\User;
use App\Models\Role;
use App\Models\Comment;
use App\Models\Wallet;
use App\Models\Product;

use Illuminate\Support\Facades\Route;
// use Illuminate\Support\Facades\Request;
use Illuminate\Http\Request;
use App\Http\Controllers\Admin\ProductController;



Route::middleware(['auth','verified', 'role:admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        Route::get('/', [DashboardController::class, 'index'])
            ->name('dashboard');

        Route::prefix('users')
            ->name('users.')
            ->group(function () {
                Route::get('/', function () {
                    return view('admin.users.index');
                })->name('index');
            });

        Route::prefix('wallets')
            ->name('wallets.')
            ->group(function () {
                Route::get('/', function()  {
                    return view('admin.wallets.index');
                })->name('index');
            });


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

        Route::prefix('categories')
            ->name('categories.')
            ->group(function() {
                Route::get('/', [CategoryController::class, 'index'])
                    ->name('index');
                Route::get('/create', [CategoryController::class, 'create'])
                    ->name('create');
                Route::post('/', [CategoryController::class, 'store'])
                    ->name('store');
                Route::get('/{category}', [CategoryController::class, 'show'])
                    ->name('show');
                Route::get('/{category}/edit', [CategoryController::class, 'edit'])
                    ->name('edit');
                Route::put('/{category}', [CategoryController::class, 'update'])
                    ->name('update');
                Route::delete('/{category}', [CategoryController::class, 'destroy'])
                    ->name('destroy');
        });
});
//Need a Wallet Controller
//Need a
