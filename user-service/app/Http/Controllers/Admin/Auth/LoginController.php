<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Enums\ErrorCode;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    public function login(Request $request): JsonResponse
    {
        $credentials = $request->only(['email', 'password']);

        if (!$token = auth('admin')->attempt($credentials)) {
            return response()->json([
                'error_code' => ErrorCode::Unauthorized->value,
                'message' => 'Invalid admin credentials',
            ], 401);
        }

        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth('admin')->factory()->getTTL() * 60,
            'admin' => auth('admin')->user(),
        ], 200);
    }
}
