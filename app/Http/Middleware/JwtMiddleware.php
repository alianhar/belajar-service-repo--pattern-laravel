<?php

namespace App\Http\Middleware;

use Closure;
use Exception;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Http\Middleware\BaseMiddleware;

class JwtMiddleware extends BaseMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();

            if (!$user) {
                return response()->json([
                    'message' => 'User not found'
                ], 404);
            }

        } catch (Exception $e) {
            if ($e instanceof TokenInvalidException) {
                return response()->json([
                    'message' => 'Token is invalid'
                ], 401);
            } else if ($e instanceof TokenExpiredException) {
                return response()->json([
                    'message' => 'Token is expired'
                ], 401);
            } else {
                return response()->json([
                    'message' => 'Authorization token not found'
                ], 401);
            }
        }

        return $next($request);
    }
}
