<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class CheckTokenExpiry
{
    public function handle($request, Closure $next)
    {
        // Check if the request method is GET
        if ($request->isMethod('get')) {
            return $next($request);
        }

        $user = Auth::user();

        if ($user && $user->token_expiry && $user->token_expiry->isPast()) {
            // Token has expired, invalidate session and log out
            $user->api_token = null;
            $user->token_expiry = null;
            $user->save(); // Save changes to the database
            Auth::logout();
            $request->session()->invalidate();
            return redirect('/login')->with('error', 'Your session has expired. Please log in again.');
        }

        return $next($request);
    }
}