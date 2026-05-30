<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;


class CommentController extends Controller {
    public function store(Request $request)
        {
            //temporarily moved to the route
            $validated = $request->validate([
                'comment' => ['required', 'string', 'max:1000'],
            ]);

            Comment::create([
                'comment' => $validated['comment'],
                'customer_id' => auth()->id(),
            ]);

            return redirect()->back();
        }
    public function index()
        {
            //show records
        }
    public function show()
        {
            //show 1 comment
        }

    public function create()
        {
            // Show create form
        }


    public function edit(Comment $comment)
        {
            // Show edit form
        }

    public function update(Request $request, Comment $comment)
        {
            // Update product
        }

    public function destroy(Comment $comment)
        {
            // Delete product
        }

}
