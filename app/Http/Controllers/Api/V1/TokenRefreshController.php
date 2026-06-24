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

        // Find the active device session using the refresh token before revoking it
        $session = null;
        if ($user instanceof \App\Models\Institute) {
            $session = \App\Models\DeviceSession::findSessionForUser($user, $request, $tokenModel);
        }

        // Revoke the used refresh token
        $tokenModel->delete();

        // Generate new access token (1 hour) and refresh token (24 hours)
        $accessTokenResult = $user->createToken('access_token', ['access-api'], now()->addHour());
        $accessToken = $accessTokenResult->plainTextToken;
        $newTokenId = $accessTokenResult->accessToken->id;

        $refreshToken = $user->createToken("refresh_token_for_{$newTokenId}", ['refresh-token'], now()->addHours(24))->plainTextToken;

        // Update the active device session with the new access token ID
        if ($session) {
            $session->update([
                'token_id' => $newTokenId,
                'last_open' => now(),
            ]);
        }

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
