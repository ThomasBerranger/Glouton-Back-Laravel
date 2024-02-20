<?php

namespace App\Http\Controllers\Authentication;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\JsonResponse;

class AuthenticationController extends Controller
{
    public function store(LoginRequest $request): JsonResponse
    {
        $request->authenticate();

        $user = $request->user();

        $user->tokens()->delete();

        $token = $user->createToken(User::LOGIN_TOKEN_NAME);

        return response()->json(['token' => $token->plainTextToken]);
    }

    public function destroy(Request $request): Response
    {
        $user = Auth::user();

        $user->tokens()->delete();

        return response()->noContent();
    }
}
