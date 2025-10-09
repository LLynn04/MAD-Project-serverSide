<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, $role): Response
    {
        $user = $request->user();
        if ($user && $user->role !== $role) {
            return response()->json([
                'status' => false,
                'message' => 'Unauthorized. Insufficient permissions.'
            ], 403);
        }
        return $next($request);
    }
}
