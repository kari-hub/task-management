<?php

namespace App\Http\Controllers\auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Providers\RouteServiceProvider;
use App\Notifications\SendTwoFactorCode;

class AuthenticateSessionController extends Controller
{
    public function store(Request $request)
    {
        $request->authenticate();
        $request->session()->regenerate();
        $request->user()->generateTwoFactorCode();
        $request->user()->notify(new SendTwoFactorCode());
        
        return redirect()->intended(RouteServiceProvider::HOME);
    }
}
