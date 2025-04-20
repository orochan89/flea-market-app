<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckProfileCompletion
{
    public function handle($request, Closure $next)
    {
        if (auth()->check() && !auth()->user()->hasCompletedProfile()) {
            if (!$request->is('mypage/profile')) {
                return redirect()->route('changeProfile');
            }
        }
        return $next($request);
    }
}
