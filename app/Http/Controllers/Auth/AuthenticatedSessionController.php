<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class AuthenticatedSessionController extends Controller
{
    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): string
    {
        $request->authenticate();

//        $request->session()->regenerate();

        $user = $request->user();

        $user->tokens()->delete();

        $token = $user->createToken('login');

        return json_encode(['token' => $token->plainTextToken]);
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): Response
    {
        $user = Auth::user();

        $user->tokens()->delete();

//        Auth::guard('web')->logout();

//        $request->session()->invalidate();

//        $request->session()->regenerateToken();

        return response()->noContent();
    }
}
