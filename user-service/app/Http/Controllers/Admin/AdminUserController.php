<?php

namespace App\Http\Controllers\Admin;

use App\Enums\UserErrorCode;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AdminUserController extends Controller
{
    public function count(): JsonResponse
    {
        $count = User::count();

        return response()->json(['count' => $count]);
    }
}
