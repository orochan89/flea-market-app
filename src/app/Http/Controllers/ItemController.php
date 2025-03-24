<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Item;
use Illuminate\Http\Request;

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
        return view('detail', compact('item'));
    }

    public function sell()
    {
        $categories = Category::all();
        return view('sell', compact('categories'));
    }

    public function store() {}
}
