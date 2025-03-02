<?php

namespace App\Http\Controllers;

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
}
