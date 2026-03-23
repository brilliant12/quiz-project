<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

class UserAuthMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        try {
            // Parse token and authenticate user via user_api guard
            $user = auth('user_api')->user();

            if (! $user) {
                return response()->json(['error' => 'Unauthorized'], 401);
            }

            // Optionally check role claim
            // $payload = JWTAuth::parseToken()->getPayload();
            // if ($payload->get('role') !== 'user') {
            //     return response()->json(['error' => 'Forbidden'], 403);
            // }

        } catch (JWTException $e) {
            return response()->json(['error' => 'Token error: '.$e->getMessage()], 401);
        }

        return $next($request);
    }
}
