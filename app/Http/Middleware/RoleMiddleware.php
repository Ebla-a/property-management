<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, ...$roles)
    {
        $user = $request->user();

        // Not logged in
        if (!$user) {
            return response()->json(['message' => 'Unauthenticated'], 401);
        }

        // User does not have required roles
        if (!$user->hasAnyRole($roles)) {
            return response()->json(['message' => 'Forbidden - Access denied'], 403);
        }

        // Allow request
        return $next($request);
    }
}
