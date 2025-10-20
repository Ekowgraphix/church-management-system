<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class OrganizationOnly
{
    /**
     * Handle an incoming request - Only allow Organization role
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        if (!auth()->user()->hasRole('Organization')) {
            return redirect()->route('login')->with('error', 'Access Denied. Organization privileges required.');
        }

        return $next($request);
    }
}
