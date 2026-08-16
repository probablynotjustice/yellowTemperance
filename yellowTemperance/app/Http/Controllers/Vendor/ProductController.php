<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

use App\Models\User;
use App\Models\Category;
use App\Models\ActivityLog;
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

        $categories = Category::orderBy('name')->get();
        return view('vendor.products.index', compact('products', 'categories'));
    }
    public function create()
    {
        $categories = Category::orderBy('name')->get();

        $vendors = User::whereHas('roles', function ($query) {
            $query->where('name', 'vendor');
        })->orderBy('name')->get();

        return view('vendor.products.create', compact(
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
            'inventory' => ['required', 'integer', 'min:0'],
            'vendor_id' => 'required|exists:users,id',
            'retail_price' => ['required', 'numeric', 'min:0'],
            'price' => 'required|numeric|min:0',
        ]);
        $validated['slug'] = Str::slug($validated['name']);

        $product = Product::create([
        'vendor_id' => auth()->id(),
        'category_id' => $validated['category_id'],
        'name' => $validated['name'],
        'description' => $validated['description'],
        'price' => $validated['price'],
    ]);
        ActivityLog::record(
            auth()->user(),
            $product,
            'created',
            "Created product '{$product->name}'.",
            null,
            $product->toArray()
        );
        return redirect()
            ->route('vendor.products.index')
            ->with('success', 'Product created successfully.');
    }
    public function show(Product $product)
    {
        return view('vendor.products.show', compact('product'));
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
        $validated = $request->validate([
            'name' => 'required|max:255',
            'description' => 'required',
            'category_id' => 'required|exists:categories,id',
            'vendor_id' => 'required|exists:users,id',
            'retail_price' => 'required|numeric|min:1',
            'price' => 'required|numeric|min:0',
            'inventory' => 'required|integer|min:0',
            'ticket_cost' => 'nullable|numeric|min:0',
        ]);
        $validated['slug'] = Str::slug($validated['name']);
        $old = $product->toArray();
        $product->update($validated);
        ActivityLog::record(
            auth()->user(),
            $product,
            'updated',
            "Updated product '{$product->name}'.",
            $old,
            $product->fresh()->toArray()
        );

        return redirect()
            ->route('vendor.products.index')
            ->with('success', 'Product updated successfully.');

    }
    public function destroy(Product $product)
    {
        if ($product->products()->exists()) {
            return back()->withErrors([
                'category' => 'This product cannot be deleted because it is assigned to one or more products.',
            ]);
        }
        $old = $product->toArray();

        $product->delete();

        ActivityLog::record(
            auth()->user(),
            $product,
            'deleted',
            "Deleted product '{$old['name']}'.",
            $old,
            null
        );

        return redirect()
            ->route('vendor.categories.index')
            ->with('success', 'Category deleted successfully.');
    }
}
