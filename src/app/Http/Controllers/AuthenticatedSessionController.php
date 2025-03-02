<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AuthenticatedSessionController extends Controller
{
    public function redirectPath()
    {
        return view('index');
    }
}
