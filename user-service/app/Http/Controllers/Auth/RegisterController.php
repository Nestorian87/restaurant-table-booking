<?php

namespace App\Http\Controllers\Auth;

use App\Enums\UserErrorCode;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\RabbitMQPublisher;
use Illuminate\Database\QueryException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class RegisterController extends Controller
{
    public function __construct(protected RabbitMQPublisher $publisher)
    {
    }

    public function register(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:50',
                'surname' => 'required|string|max:50',
                'email' => 'required|email|unique:users,email',
                'password' => 'required|string|min:6',
            ]);
        } catch (ValidationException $e) {
            $errors = $e->errors();
            if (isset($errors['email']) && str_contains(implode(' ', $errors['email']), 'taken')) {
                return response()->json([
                    'error_code' => UserErrorCode::UserAlreadyExists->value,
                    'message' => 'Email already exists.',
                ], 409);
            }

            return response()->json([
                'error_code' => UserErrorCode::ValidationFailed->value,
                'message' => 'Validation failed',
                'errors' => $errors,
            ], 422);
        }

        try {
            $user = User::create([
                'name' => $validated['name'],
                'surname' => $validated['surname'],
                'email' => $validated['email'],
                'password' => bcrypt($validated['password']),
            ]);

            $this->publisher->publishUserEvent('created', [
                'id' => $user->id,
                'name' => $user->name,
                'surname' => $user->surname,
            ]);
        } catch (QueryException $e) {
            return response()->json([
                'error_code' => UserErrorCode::UserAlreadyExists->value,
                'message' => 'Email already exists.',
            ], 409);
        }

        $token = auth()->login($user);

        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60,
            'user' => $user,
        ], 201);
    }

}
