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
        if (! $user) {
            // Web
            if (! $request->expectsJson()) {
                return redirect()->route('login');
            }

            // API
            return response()->json(['message' => 'Unauthenticated'], 401);
        }

        // User does not have required roles
        if (! $user->hasAnyRole($roles)) {
            // Web
            if (! $request->expectsJson()) {
                abort(403);
            }

            // API
            return response()->json(['message' => 'Forbidden - Access denied'], 403);
        }

        return $next($request);
    }
}
