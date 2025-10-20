<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminOnly
{
    /**
     * Handle an incoming request - ONLY allow Admin role
     * Block ALL other roles (Pastor, Ministry Leader, Volunteer, Organization, Church Member)
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        // ONLY Admin role is allowed
        if (!auth()->user()->hasRole('Admin')) {
            // Redirect Church Members to their portal
            if (auth()->user()->hasRole('Church Member')) {
                return redirect()->route('portal.index')->with('error', 'Access Denied. Admin privileges required.');
            }
            
            // Block all other roles from admin areas
            return redirect()->route('login')->with('error', 'Access Denied. You do not have permission to access this area.');
        }

        return $next($request);
    }
}
