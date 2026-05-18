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
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized or invalid user session.',
            ], 401);
        }

        $user->fcm_token = $request->fcm_token;
        $user->save();

        return response()->json([
            'status' => 'success',
            'message' => 'FCM token updated successfully.',
            'data' => [
                'fcm_token' => $user->fcm_token,
            ],
        ]);
    }
}
