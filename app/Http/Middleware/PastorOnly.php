<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PastorOnly
{
    /**
     * Handle an incoming request - Only allow Pastor role
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        if (!auth()->user()->hasRole('Pastor')) {
            return redirect()->route('login')->with('error', 'Access Denied. Pastor privileges required.');
        }

        return $next($request);
    }
}
