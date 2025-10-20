<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class MinistryLeaderOnly
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // Check if user is authenticated
        if (!auth()->check()) {
            return redirect()->route('login')->with('error', 'Please login to access this area.');
        }

        // Check if user has Ministry Leader role
        if (!auth()->user()->hasRole('Ministry Leader')) {
            return redirect()->route('login')->with('error', 'Access Denied. You do not have permission to access the Ministry Leader area.');
        }

        return $next($request);
    }
}
