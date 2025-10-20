<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class VolunteerOnly
{
    /**
     * Handle an incoming request - Only allow Volunteer role
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        if (!auth()->user()->hasRole('Volunteer')) {
            return redirect()->route('login')->with('error', 'Access Denied. Volunteer privileges required.');
        }

        return $next($request);
    }
}
