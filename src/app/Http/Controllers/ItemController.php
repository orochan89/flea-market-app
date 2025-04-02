<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ItemController extends Controller
{
    public function index(Request $request)
    {
        $page = $request->query('page', 'all');

        if ($page === 'mylist') {
            if (auth()->check()) {
                $items = auth()->user()->likes()->with('item')->get()->pluck('item');
            } else {
                $items = collect();
            }
        } else {
            $items = Item::all();
        }
        return view('index', compact('items', 'page'));
    }

    public function search(Request $request)
    {
        $query = $request->input('search');

        $results = Item::where('name', 'LIKE', "%{$query}%")->get();
        return view('search', compact('result', 'query'));
    }

    public function detail(Item $item)
    {
        $item->load(['categories', 'comments', 'likes']);
        $user = auth()->user();
        $userLike = null;
        if ($user) {
            $userLike = $item->likes()->where('user_id', $user->id)->first();
        }
        return view('detail', compact('item', 'userLike'));
    }

    public function sell()
    {
        $categories = Category::all();
        return view('sell', compact('categories'));
    }

    public function store(Request $request)
    {
        $imagePath = $request->file('image')->store('items', 'public');

        $item = Item::create([
            'user_id' => Auth::id(),
            'name' => $request->name,
            'condition' => $request->condition,
            'brand' => $request->brand,
            'detail' => $request->detail,
            'price' => $request->price,
            'image' => $imagePath,
        ]);

        if ($request->has('categories')) {
            $categoryIds = array_map('intval', (array) $request->categories);
            $item->categories()->attach($categoryIds);
        }

        return redirect()->route('mypage');
    }
}
