<?php

namespace App\Http\Controllers\Auth;

use App\Enums\ErrorCode;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    public function login(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'email' => 'required|email',
                'password' => 'required|string|min:6',
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'error_code' => ErrorCode::ValidationFailed->value,
                'message' => 'Validation failed',
                'errors' => $e->errors(),
            ], 422);
        }

        $credentials = $request->only(['email', 'password']);

        if (!$token = auth()->attempt($credentials)) {
            return response()->json([
                'error_code' => ErrorCode::Unauthorized->value,
                'message' => 'Invalid credentials',
            ], 401);
        }

        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60,
            'user' => auth()->user(),
        ], 200);
    }

}
