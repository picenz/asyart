<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    public function handle(
        Request $request,
        Closure $next,
        string $role
    ): Response {

        if (!$request->user() || $request->user()->role !== $role) {

            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Akses ditolak.'
                ], 403);
            }

            abort(403, 'Kamu tidak memiliki akses ke halaman ini.');
        }

        return $next($request);
    }
}
