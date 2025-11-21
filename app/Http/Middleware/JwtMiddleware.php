<?php

namespace App\Http\Middleware;

use Closure;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\Exceptions\JWTException;

class JwtMiddleware
{
    public function handle($request, Closure $next)
    {
        try {
            JWTAuth::parseToken()->authenticate();
        } catch (TokenExpiredException $e) {
            return response()->json([
                'message' => 'The token has expired.',
                'error' => 'token_expired'
            ], 401);
        } catch (TokenInvalidException $e) {
            return response()->json([
                'message' => 'Signature verification failed.',
                'error' => 'invalid_token'
            ], 401);
        } catch (JWTException $e) {
            return response()->json([
                'description' => 'Request does not contain an access token.',
                'error' => 'authorization_required'
            ], 401);
        }

        return $next($request);
    }
}