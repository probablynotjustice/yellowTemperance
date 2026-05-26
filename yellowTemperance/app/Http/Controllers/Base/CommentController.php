<?php

namespace App\Http\Controllers\Base;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;


class CommentController extends Controller {
public function store(Request $request)
{
    $validated = $request->validate([
        'comment' => ['required', 'string', 'max:1000'],
    ]);

    Comment::create([
        'comment' => $validated['comment'],
        'customer_id' => auth()->id(),
    ]);

    return redirect()->back();
}
}
