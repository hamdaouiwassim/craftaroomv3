<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     * Checks if the authenticated user has the specified role.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string  $role  The role ID to check (e.g., '3' for Constructor)
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        $user = auth()->user();
        
        if (!$user) {
            abort(401, 'Unauthenticated.');
        }
        
        // Convert role parameter to integer for comparison
        $requiredRole = (int) $role;
        
        // Check if user has the required role
        if ($user->role === $requiredRole) {
            return $next($request);
        }
        
        abort(403, 'Unauthorized action. You do not have the required role.');
    }
}
