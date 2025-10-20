<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class MemberOnly
{
    /**
     * Handle an incoming request - Only allow Church Members
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        if (!auth()->user()->hasRole('Church Member')) {
            return redirect()->route('login')->with('error', 'This area is for church members only. Please login with a church member account.');
        }

        return $next($request);
    }
}
