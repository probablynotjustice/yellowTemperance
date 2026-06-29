<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Product;

class ProductController extends Controller
{
    public function index()
    {
    $products =     $products = Product::where('vendor_id', auth()->id())
            ->with('vendor')->get();

    return view('vendor.productManagement', compact('user', 'products'));
    }
    }
