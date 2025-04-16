<?php

namespace App\Http\Controllers\User;

use App\Enums\UserErrorCode;
use App\Http\Controllers\Controller;
use App\Services\RabbitMQPublisher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class UserProfileController extends Controller
{
    public function __construct(protected RabbitMQPublisher $publisher)
    {
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        try {
            $validated = $request->validate([
                'name' => 'sometimes|string|max:50',
                'surname' => 'sometimes|string|max:50',
                'email' => 'sometimes|email|unique:users,email,' . $user->id,
                'password' => 'sometimes|string|min:6',
            ]);
        } catch (ValidationException $e) {
            $errors = $e->errors();
            if (isset($errors['email']) && str_contains(implode(' ', $errors['email']), 'taken')) {
                return response()->json([
                    'error_code' => UserErrorCode::UserAlreadyExists->value,
                    'message' => 'Email already exists.',
                ], 409);
            }
        }

        if (isset($validated['password'])) {
            $validated['password'] = bcrypt($validated['password']);
        }

        $user->update($validated);

        $this->publisher->publishUserEvent('updated', [
            'id' => $user->id,
            'name' => $user->name,
            'surname' => $user->surname
        ]);

        return response()->json(['message' => 'Profile updated successfully', 'user' => $user]);
    }

    /**
     * Delete the authenticated user's account.
     */
    public
    function destroy()
    {
        $user = Auth::user();

        $userId = $user->id;
        $user->delete();

        $this->publisher->publishUserEvent('deleted', ['id' => $userId]);

        return response()->json(['message' => 'User deleted successfully']);
    }
}
