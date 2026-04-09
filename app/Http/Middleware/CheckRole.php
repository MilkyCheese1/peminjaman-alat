<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $user = $request->user();

        // Allow multiple roles: pass as comma-separated string or multiple parameters
        $allowedRoles = [];
        foreach ($roles as $role) {
            // Split if comma-separated like "admin,owner"
            $allowedRoles = array_merge($allowedRoles, explode(',', $role));
        }
        
        $allowedRoles = array_map('trim', $allowedRoles);

        if (!$user || !in_array($user->role, $allowedRoles)) {
            return response()->json([
                'error' => 'Unauthorized - Required role: ' . implode(', ', $allowedRoles)
            ], 403);
        }

        return $next($request);
    }
}
