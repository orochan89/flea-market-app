<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddressRequest;
use App\Http\Requests\PurchaseRequest;
use App\Models\Item;
use App\Models\Purchase;
use App\Models\User;
use GuzzleHttp\Promise\Create;
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

    public function purchase(PurchaseRequest $request, Item $item)
    {
        if ($item->is_sold) {
            return redirect()->back()->with('error', 'この商品は既に売り切れています');
        }

        Purchase::create([
            'user_id' => Auth::id(),
            'item_id' => $item->id,
            'payment' => $request->payment,
            'postcode' => $request->postcode,
            'address' => $request->address,
            'building' => $request->building
        ]);

        $item->is_sold = true;
        $item->save();

        return redirect()->route('mypage', ['page' => 'buy']);
    }

    public function viewAddress(Request $request, Item $item)
    {
        return view('change_address', ['item' => $item]);
    }

    public function mailingAddress(AddressRequest $request, Item $item)
    {
        $user = auth()->user();

        return view('purchase', [
            'item' => $item,
            'postcode' => $request->postcode,
            'address' => $request->address,
            'building' => $request->building,
            'user' => $user
        ]);
    }
}
