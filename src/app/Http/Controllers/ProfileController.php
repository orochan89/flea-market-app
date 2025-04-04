<?php

namespace App\Http\Controllers;

use App\Models\Profile;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function viewProfile(Request $request)
    {
        $user = auth()->user();
        $profile = Profile::firstOrCreate(['user_id' => Auth::id()], ['postcode' => '', 'address' => '', 'building' => '', 'image' => '']);

        $page = $request->query('page', 'sell');
        if ($page === 'sell') {
            $items = auth()->user()->items()->get();
        } elseif ($page === 'buy') {
            $items = auth()->user()->purchases()->with('item')->get()->pluck('item');
        }
        return view('profile', compact('items', 'page', 'user', 'profile'));
    }

    public function changeProfile()
    {
        $user = $userName = Auth::user();

        $profile = Profile::firstOrCreate(['user_id' => Auth::id()], ['postcode' => '', 'address' => '', 'building' => '', 'image' => '']);
        return view('edit_profile', compact('profile', 'user'));
    }


    public function update(Request $request)
    {
        $user = auth()->user();
        $profile = $user->profile;

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('profiles', 'public');
        } else {
            $imagePath = $user->profile->image ?? null;
        }

        $user->update([
            'name' => $request->name
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
