<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Comment;
use App\Models\User;
use App\Models\Product;
use App\Models\ActivityLog;

class CommentController extends Controller
{
    /**
     * Display all comments.
     */
    public function index()
    {
        $comments = Comment::with([
            'customer',
            'vendor',
            'product',
        ])
        ->latest()
        ->get();

        return view('admin.comments.index', compact('comments'));
    }

    /**
     * Show the form for creating a comment.
     */
    public function create()
    {
        $customers = User::whereHas('roles', function ($query) {
            $query->where('name', 'customer');
        })
        ->orderBy('name')
        ->get();

        $vendors = User::whereHas('roles', function ($query) {
            $query->where('name', 'vendor');
        })
        ->orderBy('name')
        ->get();

        $products = Product::with('vendor')
            ->orderBy('name')
            ->get();

        return view('admin.comments.create', compact(
            'customers',
            'vendors',
            'products'
        ));
    }

    /**
     * Store a new comment.
     */
    public function store(Request $request)
{
    $validated = $request->validate([
        'customer_id' => [
            'required',
            'exists:users,id',
        ],

        'vendor_id' => [
            'required',
            'exists:users,id',
        ],

        'product_id' => [
            'required',
            'exists:products,id',
        ],

        'summary' => [
            'required',
            'string',
            'max:255',
        ],

        'detail' => [
            'required',
            'string',
        ],
    ]);

    $comment = Comment::create($validated);

    ActivityLog::record(
        auth()->user(),
        $comment,
        'created',
        "Created comment '{$comment->summary}'.",
        null,
        $comment->toArray()
    );

    return redirect()
        ->route('admin.comments.index')
        ->with('success', 'Comment created successfully.');
}

    /**
     * Display a specific comment.
     */
    public function show(Comment $comment)
    {
        $comment->load([
            'customer',
            'vendor',
            'product',
        ]);

        return view(
            'admin.comments.show',
            compact('comment')
        );
    }

    /**
     * Show the edit form.
     */
    public function edit(Comment $comment)
    {
        $customers = User::whereHas('roles', function ($query) {
            $query->where('name', 'customer');
        })
        ->orderBy('name')
        ->get();

        $vendors = User::whereHas('roles', function ($query) {
            $query->where('name', 'vendor');
        })
        ->orderBy('name')
        ->get();

        $products = Product::with('vendor')
            ->orderBy('name')
            ->get();

        return view(
            'admin.comments.edit',
            compact(
                'comment',
                'customers',
                'vendors',
                'products'
            )
        );
    }

    /**
     * Update the comment.
     */
public function update(Request $request, Comment $comment)
{
    $validated = $request->validate([
        'customer_id' => [
            'required',
            'exists:users,id',
        ],

        'vendor_id' => [
            'required',
            'exists:users,id',
        ],

        'product_id' => [
            'required',
            'exists:products,id',
        ],

        'summary' => [
            'required',
            'string',
            'max:255',
        ],

        'detail' => [
            'required',
            'string',
        ],
    ]);

    $oldValues = $comment->toArray();


    $comment->update($validated);

    ActivityLog::record(
        auth()->user(),
        $comment,
        'updated',
        "Updated comment '{$comment->summary}'.",
        $oldValues,
        $comment->fresh()->toArray()
    );

    return redirect()
        ->route('admin.comments.show', $comment)
        ->with('success', 'Comment updated successfully.');
}

    /**
     * Delete the comment.
     */
public function destroy(Comment $comment)
{
    $oldValues = $comment->toArray();

    ActivityLog::record(
        auth()->user(),
        $comment,
        'deleted',
        "Deleted comment '{$comment->summary}'.",
        $oldValues,
        null
    );

    $comment->delete();

    return redirect()
        ->route('admin.comments.index')
        ->with('success', 'Comment deleted successfully.');
}
}
