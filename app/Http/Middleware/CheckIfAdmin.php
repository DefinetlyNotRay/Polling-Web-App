<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckIfAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        
        $user = Auth::user();

        // Check if the user is authenticated and has 'admin' or 'regular' role
        if ($user && $user->role === 'admin') {
            return $next($request);
        }

        // Log out the user if they are not authorized
        Auth::logout();

        // Redirect to the login page or home page
        return redirect('/login')->withErrors('You are not authorized to access this page.');
    }
}