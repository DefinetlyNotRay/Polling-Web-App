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
    
            // Set expiry time
            $expiryTime = now()->addHours(24); // Expiry time: 24 hours
    
            // Update user's API token
            $user->api_token = $token;
            $user->token_expiry = $expiryTime; // Update token expiry time
    
            // Save changes to the database
            $user->save();
    
            // Store token in cookie
            $response = redirect('/')->withCookie(cookie('user_token', $token, 24 * 60)); // 24 hours
    
            return $response;
        }
    
        return back()->withErrors([
            'username' => 'The provided credentials do not match our records.',
        ]);
    }
    public function indexRegister(Request $request){
        $creds = $request->validate([
            'username' => 'required',
            'password' => 'required',
            'devision' => 'required',
        ]);
    }
    public function logout(Request $request){
        $user = Auth::user();
        $user->api_token = null;
        $user->token_expiry = null;
        $user->save(); // Save changes to the database
    
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }
<<<<<<< Updated upstream
=======
    public function indexRegister(Request $request){
        return view('views.register');
       
    }
>>>>>>> Stashed changes
    
}