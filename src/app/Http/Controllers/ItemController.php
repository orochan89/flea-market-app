<?php

namespace App\Http\Controllers;

use App\Http\Requests\ExhibitionRequest;
use App\Models\Category;
use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ItemController extends Controller
{
    public function index(Request $request)
    {
        $tab = $request->query('tab', 'all');
        $keyword = $request->query('search');

        if ($tab === 'mylist') {
            if (auth()->check()) {
                $items = auth()->user()->likes()->with('item')->get()->pluck('item');

                if ($keyword) {
                    $items = $items->filter(function ($item) use ($keyword) {
                        return str_contains(mb_strtolower($item->name), mb_strtolower($keyword));
                    });
                }
            } else {
                $items = collect();
            }
        } else {
            $query = Item::query();
            if (auth()->check()) {
                $query->where('user_id', '!=', auth()->id());
            }
            if ($keyword) {
                $query->where('name', 'like', '%' . $keyword . '%');
            }
            $items = $query->get();
        }
        return view('index', compact('items', 'tab'));
    }

    public function search(Request $request)
    {
        $keyword = $request->input('search');

        $items = Item::with('categories')
            ->search($keyword)
            ->get();

        return view('index', ['items' => $items, '' => 'search']);
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

    public function store(ExhibitionRequest $request)
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
