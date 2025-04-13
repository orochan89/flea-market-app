<?php

namespace App\Http\Controllers;

use App\Http\Requests\CommentRequest;
use App\Models\Item;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    public function store(CommentRequest $request, Item $item)
    {
        $comment = new Comment();
        $comment->comment = $request->comment;
        $comment->item_id = $request->item->id;
        $comment->user_id = Auth::user()->id;
        $comment->save();

        return redirect()->back();
    }
}
