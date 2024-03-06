<?php

namespace App\Http\Controllers\Authentication;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Throwable;

class AuthenticationController extends Controller
{
    /**
     * @throws Throwable
     */
    public function store(LoginRequest $request): JsonResponse
    {
        $request->authenticate();

        $user = $request->user();

        throw_if(! $user);

        $user->tokens()->delete();

        $token = $user->createToken(User::LOGIN_TOKEN_NAME);

        return response()->json(['token' => $token->plainTextToken]);
    }

    /**
     * @throws Throwable
     */
    public function destroy(Request $request): Response
    {
        $user = Auth::user();

        throw_if(! $user);

        $user->tokens()->delete();

        return response()->noContent();
    }
}
