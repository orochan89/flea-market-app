<?php

namespace App\Actions\Fortify;

use Laravel\Fortify\Contracts\LoginResponse as LoginResponseContract;
use Illuminate\Http\Request;

class CustomLoginResponse implements LoginResponseContract
{
    public function toResponse($request)
    {
        $user = auth()->user();

        if (!$user->profile || !$user->profile->postcode || !$user->profile->address) {
            return redirect()->route('changeProfile');
        }

        return redirect()->route('home');
    }
}
