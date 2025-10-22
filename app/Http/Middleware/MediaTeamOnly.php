<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class MediaTeamOnly
{
    /**
     * Handle an incoming request - Only allow Media Team members
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        if (!auth()->user()->hasRole('Media Team')) {
            abort(403, 'Access denied. Media Team role required.');
        }

        return $next($request);
    }
}
