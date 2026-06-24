<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    /**
     * Handle mobile login and return Sanctum token.
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'status' => 'error',
                'message' => 'The provided credentials are incorrect.',
            ], 401);
        }

        $accessToken = $user->createToken('access_token', ['access-api'], now()->addHour())->plainTextToken;
        $refreshToken = $user->createToken('refresh_token', ['refresh-token'], now()->addHours(24))->plainTextToken;

        return response()->json([
            'status' => 'success',
            'message' => 'Logged in successfully',
            'data' => [
                'token' => $accessToken,
                'access_token' => $accessToken,
                'refresh_token' => $refreshToken,
                'user' => $user->only(['id', 'name', 'email']),
            ],
        ]);
    }

    /**
     * Revoke the current token.
     */
    public function logout(Request $request)
    {
        $user = $request->user();
        \Log::info('API General AuthController logout called', [
            'user_id' => $user ? $user->id : null,
            'user_class' => $user ? get_class($user) : null,
        ]);
        if ($user) {
            $user->fcm_token = null;
            $user->save();

            // Clear device session if the user is an Institute
            if ($user instanceof \App\Models\Institute) {
                $currentToken = $user->currentAccessToken();
                if ($currentToken) {
                    $session = \App\Models\DeviceSession::findSessionForUser($user, $request, $currentToken);

                    if ($session) {
                        $session->update(['token_id' => null]);
                        $session->delete();
                    }
                }
            }

            $user->currentAccessToken()->delete();
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Logged out successfully',
        ]);
    }

    /**
     * Return the authenticated user profile.
     */
    public function profile(Request $request)
    {
        return response()->json([
            'status' => 'success',
            'data' => [
                'user' => $request->user()->only(['id', 'name', 'email']),
                // You can add more user-related data here (e.g., roles/permissions if using Spatie)
            ],
        ]);
    }
}
