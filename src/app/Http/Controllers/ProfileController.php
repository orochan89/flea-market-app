<?php

namespace App\Http\Controllers;

use App\Models\Profile;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function edit()
    {
        $user = Auth::user();
        $profile = $user->profile ?? new Profile();
        return view('profile', compact('user', 'profile'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('profile_images', 'public');
        } else {
            $imagePath = $user->profile->image ?? null;
        }

        $user->profile()->updateOrCreate(
            [
                'user_id' => $user->id
            ],
            [
                'name' => $request->name,
                'postcode' => $request->postcode,
                'address' => $request->address,
                'building' => $request->building,
                'image' => $imagePath
            ]
        );
        return redirect()->route('profile');
    }
}
