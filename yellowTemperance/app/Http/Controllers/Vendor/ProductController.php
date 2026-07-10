<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
Use App\Models\User;
use App\Models\Category;


class ProductController extends Controller
{
    public function index()
    {
        $user = User::with('roles')->find(auth()->id());
        $products = Product::where('vendor_id', auth()->id())
                ->with('vendor', 'category')->get();
        $categories = Category::orderBy('name')->get();

        return view('vendor.product', compact('user', 'products', 'categories'));
    }

    public Function create()
    {
        // IDK What to put here NEED to Findout what to do

    }

    Public function store(Request $request)
    {

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'retail_price' => ['required', 'numeric', 'min:0'],
            'price' => ['required', 'numeric','min:0'],
            'inventory' => ['required', 'integer', 'min:0'],

            'category_id' => ['required','exists:categories,id'],
        ]);
        Product::create([
            'name'          => $validated['name'],
            'category_id'      => $validated['category_id'],
            'description'   => $validated['description'],
            'retail_price'  => $validated['retail_price'],
            'price'         => $validated['price'],
            'inventory'     => $validated['inventory'],

            'vendor_id'     => auth()->id(),
        ]);
        return redirect()->back();
    }

public function show(Product $product)
{
    return view('vendor.productShow', compact('product'));
}
}
