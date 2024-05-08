<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class CheckTokenExpiration
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (Auth::check()) {
            $user = Auth::user();

            if (Cache::has('user_token_' . $user->id)) {
                $token = Cache::get('user_token_' . $user->id);

                // Check if token has expired
                if (strtotime(Cache::get('user_token_' . $user->id . '_expires')) < time()) {
                    // Token has expired, log out the user
                    Auth::logout();
                    Cache::forget('user_token_' . $user->id); // Remove token from cache
                    return redirect()->route('login')->with('expired', 'Your session has expired. Please log in again.');
                }
            }
        }

        return $next($request);
    }
}