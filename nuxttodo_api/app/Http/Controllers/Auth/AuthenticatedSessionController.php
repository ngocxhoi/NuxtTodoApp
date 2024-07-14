<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class AuthenticatedSessionController extends Controller
{
    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): JsonResponse
    {
        $request->validated();

        $request->authenticate();

        $request->session()->regenerate();

        /**
         * @var \App\Models\User $user
         */

        $user = Auth::user();

        return response()->json([
            'user' => $user,
            'token' => $user->createToken($user->email)->plainTextToken,
        ]);
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): JsonResponse
    {
        try {
            Auth::guard('web')->logout();

            $request->session()->invalidate();

            // Auth::user()->tokens->delete();

            $request->session()->regenerateToken();

            return response()->json([
                'message' => 'Logged out',
            ], 200);

        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'Failed to log out',
            ], 500);
        }

    }
}
