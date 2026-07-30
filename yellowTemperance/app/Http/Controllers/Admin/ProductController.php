<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

use App\Models\User;
use App\Models\Category;

use App\Models\Product;

class ProductController extends Controller
{
    //
    public function index()
    {
        $products = Product::with([
            'vendor',
            'category',
        ])->latest()->get();

        return view('admin.products.index', compact('products'));
    }
    public function create()
    {
        $categories = Category::orderBy('name')->get();

        $vendors = User::whereHas('roles', function ($query) {
            $query->where('name', 'vendor');
        })->orderBy('name')->get();

        return view('admin.products.create', compact(
            'categories',
            'vendors'
        ));
    }
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|max:255',
            'description' => 'required',
            'category_id' => 'required|exists:categories,id',
            'vendor_id' => 'required|exists:users,id',
            'price' => 'required|numeric|min:0',
        ]);
        $validated['slug'] = Str::slug($validated['name']);

        Product::create($validated);
    }
    public function show()
    {
        return view(
            'admin.products.show', compact('product')
    );
    }
    public function edit(Product $product)
    {
        $categories = Category::orderBy('name')->get();

        $vendors = User::whereHas('roles', function ($query) {
            $query->where('name', 'vendor');
        })->get();

        return view(
            'admin.products.edit',
            compact(
                'product',
                'categories',
                'vendors'
            )
        );
    }
    public function update(Request $request, Product $product)
    {
        $validated = $request->validated([
            'name' => 'required|max: 225',
            'description' => 'required',
            'category_id' => 'required|exist:categories,id',
            'vendor_id' => 'required|exist:user,id',
            'price' => 'required|numberic|min:0,'

        ]);
        $validated['slug'] = Str::slug($validated['name']);
        return redirect()
            ->route('admin.prosucts.index')
            ->with('success', 'Product updated successfully.');

    }
    public function destroy(Product $product)
    {
        if ($product->products()->exists()) {
            return back()->withErrors([
                'category' => 'This product cannot be deleted because it is assigned to one or more products.',
            ]);
        }
        $product->delete();


        return redirect()
            ->route('admin.categories.index')
            ->with('success', 'Category deleted successfully.');
    }
}
