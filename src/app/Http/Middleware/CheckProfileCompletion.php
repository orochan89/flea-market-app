<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckProfileCompletion
{
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();

        if ($user) {
            $profile = $user->profile;

            if (empty($profile->postcode) || empty($profile->address)) {
                return redirect()->route('changeProfile');
            }
        }

        return $next($request);
    }
}
