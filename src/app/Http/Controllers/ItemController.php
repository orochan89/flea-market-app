<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Item;
use Illuminate\Http\Request;

class ItemController extends Controller
{
    public function index(Request $request)
    {
        $items = Item::all();
        $tab = $request->query('tab');
        return view('index', compact('items', 'tab'));
    }

    public function search(Request $request)
    {
        $query = $request->input('search');

        $results = Item::where('name', 'LIKE', "%{$query}%")->get();
        return view('search', compact('result', 'query'));
    }

    public function detail($id)
    {
        $items = Item::findOrFail($id);
        return view('detail', compact('item'));
    }

    public function sell()
    {
        $categories = Category::all();
        return view('sell', compact('categories'));
    }
}
