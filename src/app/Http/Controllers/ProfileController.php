<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileRequest;
use App\Models\Profile;
use App\Models\User;
use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function viewProfile(Request $request)
    {
        $user = auth()->user();

        $profile = Profile::firstOrCreate(
            ['user_id' => $user->id],
            ['postcode' => '', 'address' => '', 'building' => '', 'image' => '']
        );

        $tab = $request->query('tab', 'sell');
        $keyword = $request->query('search');

        if ($tab === 'sell') {
            $items = Item::where('user_id', $user->id)
                ->search($keyword)
                ->get();
        } elseif ($tab === 'buy') {
            $purchases = $user->purchases()->with('item')->get();
            $items = $purchases->pluck('item');

            if ($keyword) {
                $lowerKeyword = mb_strtolower($keyword);
                $items = $items->filter(function ($item) use ($lowerKeyword) {
                    return stripos(mb_strtolower($item->name), $lowerKeyword) !== false ||
                        stripos(mb_strtolower($item->brand), $lowerKeyword) !== false ||
                        stripos(mb_strtolower($item->detail), $lowerKeyword) !== false;
                });
            }
        } else {
            $items = collect();
        }

        return view('profile', compact('items', 'tab', 'user', 'profile', 'keyword'));
    }

    public function changeProfile()
    {
        $user = Auth::user();

        $profile = Profile::firstOrCreate(
            ['user_id' => Auth::id()],
            ['postcode' => '', 'address' => '', 'building' => '', 'image' => '']
        );
        return view('edit_profile', compact('profile', 'user'));
    }


    public function update(ProfileRequest $request)
    {
        $user = auth()->user();
        $profile = $user->profile;

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('profiles', 'public');
        } else {
            $imagePath = $user->profile->image ?? null;
        }

        $user->update([
            'name' => $request->name ?: auth()->user()->name,
        ]);

        $profile->update([
            'postcode' => $request->postcode,
            'address' => $request->address,
            'building' => $request->building,
            'image' => $imagePath
        ]);

        return redirect()->route('mypage');
    }
}
