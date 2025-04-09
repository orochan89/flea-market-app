<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PurchaseController extends Controller
{
    public function viewPurchase(Request $request, Item $item)
    {
        $user = auth()->user();
        $profile = $user->profile;

        $postcode = $request->postcode ?? $user->profile->postcode;
        $address = $request->address ?? $user->profile->address;
        $building = $request->building ?? $user->profile->building;

        return view('purchase', compact('item', 'user', 'postcode', 'address', 'building'));
    }

    public function purchase(Request $request, Item $item) {}

    public function viewAddress(Request $request, Item $item)
    {
        $user = User::find(Auth::id());
        return view('change_address');
    }

    public function mailingAddress(Request $request, Item $item)
    {
        $user = auth()->user();

        $postcode = $request->postcode ?? $user->profile->address;
        $address = $request->address ?? $user->profile->address;
        $building = $request->building ?? $user->profile->building;
    }
}
