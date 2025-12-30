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

        // إذا لم يكن مسجّل دخول
        if (!$user) {
            return response()->json(['message' => 'Unauthenticated'], 401);
        }

        // إذا دوره غير مسموح
        if (!in_array($user->role->name, $roles)) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        return $next($request);
    }
}

