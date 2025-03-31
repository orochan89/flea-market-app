<?php

namespace App\Http\Controllers;

use App\Models\Like;
use App\Models\Item;
use Illuminate\Http\Request;

class LikeController extends Controller
{
    public function toggleLike(Request $request, Item $item)
    {
        if (auth()->check()) {
            $user_id = auth()->id();
            $existingLike = Like::where('item_id', $item->id)->where('user_id', $user_id)->first();

            if ($existingLike) {
                $existingLike->delete();
            } else {
                Like::create([
                    'item_id' => $item->id,
                    'user_id' => $user_id,
                ]);
            }
            return redirect()->back();
        }
    }
}
