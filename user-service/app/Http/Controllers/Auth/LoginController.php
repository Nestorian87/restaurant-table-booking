<?php

namespace App\Http\Controllers\Auth;

use App\Enums\UserErrorCode;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    public function login(Request $request): JsonResponse
    {
        $credentials = $request->only(['email', 'password']);

        if (!$token = auth()->attempt($credentials)) {
            return response()->json([
                'error_code' => UserErrorCode::Unauthorized->value,
                'message' => 'Invalid credentials',
            ], 401);
        }

        error_log("Token: $token");

        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60,
            'user' => auth()->user(),
        ], 200);
    }

}
