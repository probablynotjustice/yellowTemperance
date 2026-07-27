<?php
use App\Http\Controllers\DashboardRedirectController;
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

        Route::get('/', function () {
            $user = User::with('roles')->find(auth()->id());
            return view('admin.dashboard', compact('user'));
        })->name('dashboard');

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

        Route::get('/products', [ProductController::class, 'index'])
            ->name('products.index');

        Route::get('/products/create', function () {
            return view('admin.products.create');
        })->name('products.create');

        Route::prefix('categories')
            ->name('categories.')
            ->group(function() {
                Route::get('/', function() {
                    return view('admin.categories.index');
                })->name('index');
            });
});
//Need a Wallet Controller
//Need a
