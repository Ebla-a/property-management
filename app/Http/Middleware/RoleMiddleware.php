<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        $user = $request->user();

        // Check if user is not authenticated
        if (!$user) {
            return response()->json(['message' => 'Unauthenticated'], 401);
        }

        // Check if user does NOT have any of the allowed roles
        // hasAnyRole() is provided by Spatie Permission
        if (!$user->hasAnyRole($roles)) {
            return response()->json(['message' => 'Forbidden - Access denied'], 403);
        }

        // Allow request to continue
        return $next($request);
    }
}
