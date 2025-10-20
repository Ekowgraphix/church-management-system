<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class StaffOnly
{
    /**
     * Handle an incoming request - Allow Admin, Pastor, Ministry Leader, Organization, Volunteer
     * Block only Church Member role
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $user = auth()->user();

        // Allow these roles to access admin dashboard
        $allowedRoles = ['Admin', 'Pastor', 'Ministry Leader', 'Organization', 'Volunteer'];
        
        $hasAccess = false;
        foreach ($allowedRoles as $role) {
            if ($user->hasRole($role)) {
                $hasAccess = true;
                break;
            }
        }

        if (!$hasAccess) {
            // Redirect Church Members to their portal
            if ($user->hasRole('Church Member')) {
                return redirect()->route('portal.index')->with('error', 'Please use the member portal.');
            }
            
            // Block any other role
            return redirect()->route('login')->with('error', 'Access Denied.');
        }

        return $next($request);
    }
}
