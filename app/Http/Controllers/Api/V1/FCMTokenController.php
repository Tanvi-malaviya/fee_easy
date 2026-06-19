<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class FCMTokenController extends Controller
{
    /**
     * Store or update the authenticated user's FCM device token
     */
    public function updateToken(Request $request)
    {
        $request->validate([
            'fcm_token' => 'required|string',
        ]);

        $user = $request->user();
        if (!$user) {
            foreach (['institute', 'web', 'api'] as $guard) {
                if (auth()->guard($guard)->check()) {
                    $user = auth()->guard($guard)->user();
                    break;
                }
            }
        }

        if (!$user) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized or invalid user session.',
            ], 401);
        }

        if ($user instanceof \App\Models\Institute) {
            $token = $user->currentAccessToken();
            if ($token) {
                \App\Models\DeviceSession::where('token_id', $token->id)
                    ->update(['fcm_token' => $request->fcm_token]);
            }
            $fcmToken = $request->fcm_token;
        } else {
            $user->fcm_token = $request->fcm_token;
            $user->save();
            $fcmToken = $user->fcm_token;
        }

        return response()->json([
            'status' => 'success',
            'message' => 'FCM token updated successfully.',
            'data' => [
                'fcm_token' => $fcmToken,
            ],
        ]);
    }
}
