<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Support\Str;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;


use App\Models\Category;
use App\Models\Auction;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
 public function index()
{
    $categories = Category::withCount('products')
        ->orderBy('name')
        ->get();

    return view(
        'admin.categories.index',
        compact('categories')
    );
}

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
         return view('admin.categories.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                'unique:categories,name'],
            'description' => [
                'nullable',
                'string',
            ]
        ]);

        Category::create([
            'name' => $validated['name'],
            'slug' => Str::slug($validated['name']),
            'description' => $validated['description'],
            ]);

        return redirect()
            ->route('admin.categories.index')
            ->with('success', 'Category created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Category $category)
    {
        {
    $category->load([
        'products.auctions',
        'products.vendor',
    ]);

    return view('admin.categories.show', compact('category'));
}
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Category $category)
    {
        return view('admin.categories.edit', compact('category'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Category $category)
    {
    $validated = $request->validate([
        'name' => [
            'required',
            'string',
            'max:255',
            'unique:categories,name,' . $category->id,
        ],
        'description' => [
            'nullable',
            'string'],
    ]);

    $validated['slug'] = Str::slug($validated['name']);

    $category->update($validated);

    return redirect()
        ->route('admin.categories.index')
        ->with('success', 'Category updated successfully.');
    }

    public function auctions(Category $category)
    {
        $category->load([
            'products.auctions.product',
            'products.auctions.bids',
        ]);
        return view('admin.categories.auctions', compact('category'));
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
        if ($category->products()->exists()) {
            return back()->withErrors([
                'category' => 'This category cannot be deleted because it is assigned to one or more products.',
            ]);
        }
        $category->delete();


        return redirect()
            ->route('admin.categories.index')
            ->with('success', 'Category deleted successfully.');
    }
}
