<?php

namespace App\Http\Middleware;

use Closure;
use Exception;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Tymon\JWTAuth\Facades\JWTAuth;

class VerifyJwtSignatureOnly
{
    public function handle(Request $request, Closure $next, string $expectedRole = null)
    {
        try {
            $token = JWTAuth::parseToken();
            $payload = $token->getPayload();

            if ($expectedRole && $payload->get('role') !== $expectedRole) {
                return response()->json([
                    'message' => 'Unauthorized',
                    'error' => 'Role mismatch. Expected: ' . $expectedRole,
                ], Response::HTTP_UNAUTHORIZED);
            }

            $request->attributes->set('jwt_payload', $payload);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Unauthorized',
                'error' => $e->getMessage()
            ], Response::HTTP_UNAUTHORIZED);
        }

        return $next($request);
    }
}
