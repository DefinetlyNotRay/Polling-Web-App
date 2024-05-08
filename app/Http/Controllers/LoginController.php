<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class LoginController extends Controller
{
    public function index(Request $request)
    {
        $creds = $request->validate([
            'username' => 'required',
            'password' => 'required',
        ]);

        if (Auth::attempt($creds)) {
            // Authentication was successful
            $user = Auth::user();

            // Generate a random token
            $token = Str::random(60);

            // Store token in cookie
            $response = redirect('/')->withCookie(cookie('user_token', $token, 1)); // 24 hours

            return $response;
        }

        return back()->withErrors([
            'username' => 'The provided credentials do not match our records.',
        ]);
    }
    public function logout(Request $request){
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }
}