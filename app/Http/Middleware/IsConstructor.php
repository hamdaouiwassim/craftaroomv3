<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IsConstructor
{
    /**
     * Handle an incoming request.
     * Checks if the authenticated user is a Constructor (role 3).
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->user();
        
        if (!$user) {
            abort(401, 'Unauthenticated.');
        }
        
        // Check if user is a Constructor (role 3)
        if ($user->role === 3) {
            return $next($request);
        }
        
        abort(403, 'Unauthorized action. This route is only accessible to Constructors.');
    }
}
