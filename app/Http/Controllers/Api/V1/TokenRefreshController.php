<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Laravel\Sanctum\PersonalAccessToken;

class TokenRefreshController extends Controller
{
    /**
     * Refresh the Sanctum access token using a valid refresh token.
     */
    public function refresh(Request $request)
    {
        $tokenString = $request->input('refresh_token') ?? $request->bearerToken();

        if (empty($tokenString)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Refresh token is required.'
            ], 400);
        }

        $tokenModel = PersonalAccessToken::findToken($tokenString);

        if (!$tokenModel || !$tokenModel->tokenable) {
            return response()->json([
                'status' => 'error',
                'message' => 'Invalid refresh token.'
            ], 401);
        }

        // Check if this token has refresh-token ability
        if (!in_array('refresh-token', $tokenModel->abilities)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Token is not a valid refresh token.'
            ], 401);
        }

        // Check expiration
        if ($tokenModel->expires_at && $tokenModel->expires_at->isPast()) {
            $tokenModel->delete();
            return response()->json([
                'status' => 'error',
                'message' => 'Refresh token has expired. Please login again.'
            ], 401);
        }

        $user = $tokenModel->tokenable;

        // Revoke the used refresh token
        $tokenModel->delete();

        // Generate new access token (1 hour) and refresh token (24 hours)
        $accessToken = $user->createToken('access_token', ['access-api'], now()->addHour())->plainTextToken;
        $refreshToken = $user->createToken('refresh_token', ['refresh-token'], now()->addHours(24))->plainTextToken;

        return response()->json([
            'status' => 'success',
            'message' => 'Token refreshed successfully.',
            'data' => [
                'token' => $accessToken,
                'access_token' => $accessToken,
                'refresh_token' => $refreshToken,
            ]
        ]);
    }
}
