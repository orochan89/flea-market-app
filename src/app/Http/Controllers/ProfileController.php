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
        $user = Auth::user();

        $page = $request->query('page', 'sell');
        if ($page === 'sell') {
            $items = auth()->user()->items()->get();
        } elseif ($page === 'buy') {
            $items = auth()->user()->purchases()->with('item')->get()->pluck('item');
        }
        return view('profile', compact('items', 'page', 'user'));
    }

    public function changeProfile()
    {
        $user = User::with('profile');
        return view('edit_profile', compact('user'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('profile_images', 'public');
        } else {
            $imagePath = $user->profile->image ?? null;
        }

        if ($user->profile) {
            $user->profile->update([
                'name' => $request->name,
                'postcode' => $request->postcode,
                'address' => $request->address,
                'building' => $request->building,
                'image' => $imagePath,
            ]);
        } else {
            Profile::create([
                'user_id' => $user->id,
                'name' => $request->name,
                'postcode' => $request->postcode,
                'address' => $request->address,
                'building' => $request->building,
                'image' => $imagePath,
            ]);
        }
        return redirect()->route('profile');
    }
}
