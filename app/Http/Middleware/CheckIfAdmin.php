<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
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

        if ($user->role = "admin") {
          return $next($request);
        }else{
            return redirect('/login')->with('error', 'You are not an Admin please login to an Admin account');

        }

        return $next($request);
    }
}