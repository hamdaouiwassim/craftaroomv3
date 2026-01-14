<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CanManageProducts
{
    /**
     * Handle an incoming request.
     * Allows Admin (role 0), Designer (role 1), and Constructor (role 3)
     * Blocks Client (role 2)
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->user();
        
        // Allow Admin (0), Designer (1), and Constructor (3)
        // Block Client (2)
        if ($user && in_array($user->role, [0, 1, 3])) {
            return $next($request);
        }
        
        abort(403, 'Unauthorized action. Only admins, designers, and constructors can manage products.');
    }
}
