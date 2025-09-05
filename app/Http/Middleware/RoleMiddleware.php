<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, ...$roles)
    {
        $user = $request->user();

        if (!$user || !$user->role) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        // Check if user role is in allowed roles
        if (!in_array($user->role->name, $roles)) {
            return response()->json(['message' => 'Forbidden - insufficient role'], 403);
        }

        return $next($request);
    }
}
