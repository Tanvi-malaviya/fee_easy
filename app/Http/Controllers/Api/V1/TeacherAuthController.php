<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Mail\ForgotPasswordMail;
use App\Models\Staff;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class TeacherAuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $staff = Staff::where('email', $request->email)->first();

        if (!$staff || !Hash::check($request->password, $staff->password)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Invalid credentials.',
            ], 401);
        }

        if ($staff->is_login_blocked) {
            return response()->json([
                'status' => 'error',
                'message' => 'Your account login has been blocked. Please contact your institute.',
            ], 403);
        }

        $accessToken = $staff->createToken('access_token', ['access-api'], now()->addHour())->plainTextToken;
        $refreshToken = $staff->createToken('refresh_token', ['refresh-token'], now()->addHours(24))->plainTextToken;

        return response()->json([
            'status' => 'success',
            'message' => 'Logged in successfully',
            'data' => array_merge(
                [
                    'token' => $accessToken,
                    'access_token' => $accessToken,
                    'refresh_token' => $refreshToken,
                    'must_change_password' => (bool) $staff->must_change_password,
                ],
                $staff->load(['role', 'department', 'departments', 'institute:id,institute_name,logo'])->toArray()
            ),
        ]);
    }

    public function logout(Request $request)
    {
        $user = $request->user();
        if (!$user) {
            return response()->json(['status' => 'error', 'message' => 'Unauthorized'], 401);
        }

        $user->fcm_token = null;
        $user->save();
        $user->currentAccessToken()->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Logged out successfully',
        ]);
    }

    /**
     * Forced (first-login) or voluntary password change.
     */
    public function changePassword(Request $request)
    {
        $staff = $request->user();
        if (!$staff) {
            return response()->json(['status' => 'error', 'message' => 'Unauthorized'], 401);
        }

        $rules = [
            'password' => [
                'required',
                'string',
                'min:8',
                'max:15',
                'confirmed',
                'regex:/[a-z]/',
                'regex:/[A-Z]/',
                'regex:/[0-9]/',
                'regex:/[\W_]/',
            ],
        ];

        // Only require the current password once the forced first-login change is done.
        if (!$staff->must_change_password) {
            $rules['current_password'] = 'required|string';
        }

        $request->validate($rules, [
            'password.min' => 'Password must be at least 8 characters.',
            'password.max' => 'Password must not exceed 15 characters.',
            'password.confirmed' => 'New password and confirmation do not match.',
            'password.regex' => 'Password must include an uppercase letter, a lowercase letter, a number, and a special character.',
        ]);

        if (!$staff->must_change_password && !Hash::check($request->current_password, $staff->password)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Current password is incorrect.',
            ], 422);
        }

        $staff->update([
            'password' => Hash::make($request->password),
            'must_change_password' => false,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Password changed successfully.',
        ]);
    }

    public function sendResetPasswordEmail(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:staff,email',
        ]);

        $staff = Staff::where('email', $request->email)->first();

        $otp = rand(100000, 999999);
        Cache::put('teacher_reset_otp_' . strtolower($request->email), $otp, now()->addMinutes(15));

        try {
            Mail::to($request->email)->send(new ForgotPasswordMail($otp, $staff->full_name));
        } catch (\Exception $e) {
            Log::error('Teacher forgot password mail error: ' . $e->getMessage());
            return response()->json(['status' => 'error', 'message' => 'Failed to send email.'], 500);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'OTP sent to your email.',
        ]);
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:staff,email',
            'otp' => 'required|string|size:6',
            'password' => [
                'required',
                'string',
                'min:8',
                'max:15',
                'confirmed',
                'regex:/[a-z]/',
                'regex:/[A-Z]/',
                'regex:/[0-9]/',
                'regex:/[\W_]/',
            ],
        ], [
            'password.min' => 'Password must be at least 8 characters.',
            'password.max' => 'Password must not exceed 15 characters.',
            'password.confirmed' => 'New password and confirmation do not match.',
            'password.regex' => 'Password must include an uppercase letter, a lowercase letter, a number, and a special character.',
        ]);

        $cacheKey = 'teacher_reset_otp_' . strtolower($request->email);
        $cachedOtp = Cache::get($cacheKey);

        if (!$cachedOtp || (string) $cachedOtp !== (string) $request->otp) {
            return response()->json(['status' => 'error', 'message' => 'Invalid or expired code.'], 400);
        }

        $staff = Staff::where('email', $request->email)->first();
        $staff->update([
            'password' => Hash::make($request->password),
            'must_change_password' => false,
        ]);

        Cache::forget($cacheKey);

        return response()->json([
            'status' => 'success',
            'message' => 'Password reset successfully.',
        ]);
    }
}
