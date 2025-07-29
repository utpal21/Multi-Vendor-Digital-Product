<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, $role)
    {
        if (!$request->user() || !$request->user()->roles()->where('slug', $role)->exists()) {
            return response()->json(['message' => 'Unauthorized - Role required'], 403);
        }

        return $next($request);
    }
}
