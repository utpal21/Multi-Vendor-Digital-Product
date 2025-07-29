<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class PermissionMiddleware
{
    public function handle(Request $request, Closure $next, $permission)
    {
        $user = $request->user();

        if (!$user) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $hasPermission = $user->roles()
            ->whereHas('permissions', function ($q) use ($permission) {
                $q->where('slug', $permission);
            })->exists();

        if (!$hasPermission) {
            return response()->json(['message' => 'Unauthorized - Permission required'], 403);
        }

        return $next($request);
    }
}

