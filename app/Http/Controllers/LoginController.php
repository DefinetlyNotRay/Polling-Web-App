<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Cache;   

class LoginController extends Controller
{
    public function index(Request $request)
    {
        $creds = $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
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
            $response = redirect('/')->withCookie(cookie('user_token', $token, 24 * 60))->with('success', 'Login Succeeded.'); // 24 hours
    
            return $response;
        }
    
        return back()->with('error', 'The provided credentials do not match our records.');
    }

    public function indexRegister(Request $request)
{
    $creds = $request->validate([
        'username' => 'required|string|unique:users',
        'password' => 'required|min:6',
        'division' => 'required',
    ]);

    // Check if the username is a string (this is redundant since `string` validation is already done)
    if (!is_string($creds['username'])) {
        return redirect('/register')->with('error', 'Invalid username format.');
    }

    // Check if the username already exists
    if (User::where('username', $request->input('username'))->exists()) {
        return redirect('/register')->with('error', 'Username has already been taken.');
    }

    // Create a new user
    $user = User::create([
        'username' => $creds['username'],
        'password' => bcrypt($creds['password']),
        'division_id' => $creds['division'],
        'role' => "user",
    ]);

    // Attempt to authenticate the user
     if(Auth::login($user)) {
        return redirect('/')->withCookie(cookie('user_token', $user->api_token, 24 * 60))->with('success', 'Auto-Login Succeeded.');
    }
    else {
        // Handle authentication failure
        return redirect('/register')->with('error', 'Authentication failed.');
    }
}
    public function logout(Request $request){
        $user = Auth::user();
        $user->api_token = null;
        $user->token_expiry = null;
        $user->save(); // Save changes to the database
    
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        $dk = [
            "error" => "You have been logged out.",
            "islogout" => true,
        ];
        return redirect('/login')->with($dk);
    }
    public function regisAuth(){
        $user = User::latest()->first();
        $userCred = [
            'username' => $user->username,
            'password' => $user->password,
        ];
        if ($user && Hash::check(request('password'), $user->password)) {
            Auth::login($user);

            $response = redirect('/')->withCookie(cookie('user_token',$user->api_token));
        };
        return $response;
    }

    
    public function indexReg(Request $request) {
        return view('views.register');
    }
}