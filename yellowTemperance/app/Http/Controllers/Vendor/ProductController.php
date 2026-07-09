<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
Use App\Models\User;


class ProductController extends Controller
{
    public function index()
    {
        $user = User::with('roles')->find(auth()->id());
        $products = $products = Product::where('vendor_id', auth()->id())
                ->with('vendor')->get();

        return view('vendor.product', compact('user', 'products'));
    }

    public Function create()
    {
        // IDK What to put here NEED to Findout what to do

    }

    Public function store(Request $request)
    {
        $user = User::with('roles')->find(auth()->id());

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'retail_price' => ['required', 'numeric', 'min:0'],
            'price' => ['required', 'numeric','min:0'],
            'inventory' => ['required', 'integer', 'min:0'],
            //'ticket_cost' =>['required', 'integer', 'min:1']
            'category' => ['required','string'],
        ]);
        Product::create([
            'name'          => $validated['name'],
            'category'      => $validated['category'],
            'description'   => $validated['description'],
            'retail_price'  => $validated['retail_price'],
            'price'         => $validated['price'],
            'inventory'     => $validated['inventory'],
            //'ticket_cost'   => $validated['ticket_cost'],
            'vendor_id'     => auth()->id(),
        ]);
        return redirect()->back();
    }

public function show(Product $product)
{
    return view('vendor.productShow', compact('product'));
}
}
