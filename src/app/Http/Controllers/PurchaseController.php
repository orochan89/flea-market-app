<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PurchaseController extends Controller
{
    public function viewPurchase(Request $request, Item $item)
    {
        $user = auth()->user();

        $postcode = $request->postcode ?? $user->profile->address;
        $address = $request->address ?? $user->profile->address;
        $building = $request->building ?? $user->profile->building;
    }

    public function purchase(Request $request, Item $item) {}

    public function viewAddress(Request $request, Item $item)
    {
        return view('change_address');
    }

    public function mailingAddress(Request $request, Item $item) {}
}
