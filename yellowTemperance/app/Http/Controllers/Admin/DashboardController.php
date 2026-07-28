<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Auction;
use App\Models\Product;
use App\Models\User;
use App\Models\Wallet;
use App\Models\Category;

class DashboardController extends Controller
    {
        public function index()
        {
            $stats = [
                'users'      => User::count(),
                'vendors'    => User::whereHas('roles', function ($query) {
                    $query->where('name', 'vendor');
                })->count(),

                'customers'  => User::whereHas('roles', function ($query) {
                    $query->where('name', 'customer');
                })->count(),

                'products'   => Product::count(),
                'auctions'   => Auction::count(),
                'categories' => Category::count(),

                'wallets'    => Wallet::count(),
            ];

            $recentUsers = User::latest()
                ->take(5)
                ->get();

            $recentProducts = Product::with('vendor')
                ->latest()
                ->take(5)
                ->get();

            $recentAuctions = Auction::with('product')
                ->latest()
                ->take(5)
                ->get();

            return view('admin.dashboard', compact(
                'stats',
                'recentUsers',
                'recentProducts',
                'recentAuctions'
            ));
        }
    }
