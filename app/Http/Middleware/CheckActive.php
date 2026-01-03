<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckActive
{
    public function handle(Request $request, Closure $next)
    {
        $user = $request->user();

        if ($user && !$user->is_active) {
            return response()->json([
                'message' => 'Your account is disabled by admin.'
            ], 403);
        }

        return $next($request);
    }
}
