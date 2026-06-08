<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Institute;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Mail;
use App\Mail\OtpMail;

class InstituteAuthController extends Controller
{
    public function register(Request $request)
    {
        // Delete pending, unverified registration with the same email if it exists
        if ($request->filled('email')) {
            \App\Models\Institute::where('email', $request->email)
                ->where('status', 'pending')
                ->delete();
        }

        $request->validate([
            'institute_name' => 'required|string|max:255',
            'email' => 'required|email:rfc|unique:institutes,email',
            'password' => 'required|string|min:8|max:15',
            'name' => 'nullable|string|max:255',
            'phone' => 'nullable|digits:10',
        ]);

        $otp = rand(100000, 999999);

        $institute = Institute::create([
            'name' => $request->name ?: $request->institute_name,
            'email' => $request->email,
            'phone' => $request->phone ?: '',
            'password' => Hash::make($request->password),
            'institute_name' => $request->institute_name,
            'otp' => $otp,
            'otp_expires_at' => Carbon::now()->addMinutes(10),
            'status' => 'pending',
        ]);

        try {
            Mail::to($institute->email)->send(new OtpMail($otp, $institute->name));
        } catch (\Exception $e) {
            // Log or ignore gracefully depending on testing configs
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Institute registered successfully. Please verify your OTP.',
        ], 201);
    }

    public function verifyOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:institutes,email',
            'otp' => 'required|string|size:6',
        ]);

        $institute = Institute::where('email', $request->email)->first();

        if ($institute->otp !== $request->otp) {
            return response()->json([
                'status' => 'error',
                'message' => 'Invalid OTP.',
            ], 422);
        }

        if ($institute->otp_expires_at->isPast()) {
            return response()->json([
                'status' => 'error',
                'message' => 'OTP has expired.',
            ], 422);
        }

        $institute->update([
            'otp' => null,
            'otp_expires_at' => null,
            'email_verified_at' => Carbon::now(),
            'status' => 'active',
        ]);

        // Assign Free Plan subscription (1 month = 30 days)
        $hasActiveSub = $institute->subscriptions()->whereIn('status', ['active', 'trial'])->exists();
        if (!$hasActiveSub) {
            \App\Models\Subscription::create([
                'institute_id' => $institute->id,
                'plan_name' => 'Free Plan',
                'amount' => 0,
                'start_date' => Carbon::now(),
                'end_date' => Carbon::now()->addDays(30),
                'status' => 'active',
            ]);
        }

        $accessToken = $institute->createToken('access_token', ['access-api'], now()->addHour())->plainTextToken;
        $refreshToken = $institute->createToken('refresh_token', ['refresh-token'], now()->addHours(24))->plainTextToken;

        try {
            Mail::to($institute->email)->send(new \App\Mail\AccountActivatedMail($institute->name));
        } catch (\Exception $e) {
            // Log or ignore gracefully
        }

        return response()->json([
            'status' => 'success',
            'message' => 'OTP verified successfully.',
            'data' => [
                'token' => $accessToken,
                'access_token' => $accessToken,
                'refresh_token' => $refreshToken,
            ]
        ]);
    }

    public function resendOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:institutes,email',
        ]);

        $institute = Institute::where('email', $request->email)->first();

        $otp = rand(100000, 999999);
        $institute->update([
            'otp' => $otp,
            'otp_expires_at' => Carbon::now()->addMinutes(10),
        ]);

        try {
            Mail::to($institute->email)->send(new OtpMail($otp, $institute->name));
        } catch (\Exception $e) {
            // Log or ignore gracefully depending on testing configs
        }

        return response()->json([
            'status' => 'success',
            'message' => 'A new OTP has been successfully sent to your email address.',

        ]);
    }
    public function sendResetPasswordEmail(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:institutes,email',
        ]);

        $institute = Institute::where('email', $request->email)->first();

        $otp = rand(100000, 999999);
        $institute->update([
            'otp' => $otp,
            'otp_expires_at' => Carbon::now()->addMinutes(10),
        ]);

        try {
            Mail::to($institute->email)->send(new \App\Mail\ForgotPasswordMail($otp, $institute->name));
        } catch (\Exception $e) {
            // Log gracefully
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Reset password OTP has been sent successfully to your email.',
        ]);
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:institutes,email',
            'otp' => 'required|string|size:6',
            'password' => 'required|string|min:6',
        ]);

        $newPassword = $request->password;
        $errors = [];

        if (strlen($newPassword) < 8 || strlen($newPassword) > 15) {
            $errors[] = 'Password must be between 8 and 15 characters long.';
        }
        if (!preg_match('/[a-z]/i', $newPassword)) {
            $errors[] = 'Password must contain at least 1 standard letter.';
        }
        if (!preg_match('/[A-Z]/', $newPassword)) {
            $errors[] = 'Password must contain at least 1 capital letter.';
        }
        if (!preg_match('/\d/', $newPassword)) {
            $errors[] = 'Password must contain at least 1 number.';
        }
        if (!preg_match('/[\W_]/', $newPassword)) {
            $errors[] = 'Password must contain at least 1 special character.';
        }

        if (!empty($errors)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation errors.',
                'errors' => $errors
            ], 422);
        }

        $institute = Institute::where('email', $request->email)->first();

        if ($institute->otp !== $request->otp) {
            return response()->json([
                'status' => 'error',
                'message' => 'Invalid OTP.',
            ], 400);
        }

        if ($institute->otp_expires_at && $institute->otp_expires_at->isPast()) {
            return response()->json([
                'status' => 'error',
                'message' => 'OTP has expired.',
            ], 400);
        }

        $institute->update([
            'password' => Hash::make($request->password),
            'otp' => null,
            'otp_expires_at' => null,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Password reset successfully.',
        ]);
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $institute = Institute::where('email', $request->email)->first();

        if (!$institute || !Hash::check($request->password, $institute->password)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Invalid credentials.',
            ], 401);
        }

        if ($institute->status === 'blocked') {
            return response()->json([
                'status' => 'blocked',
                'message' => "Your institute account is currently marked as blocked. Please contact the administrator or support to activate your account."
            ], 403);
        }

        $accessToken = $institute->createToken('access_token', ['access-api'], now()->addHour())->plainTextToken;
        $refreshToken = $institute->createToken('refresh_token', ['refresh-token'], now()->addHours(24))->plainTextToken;
        $subscription = $institute->subscriptions()->latest()->first();

        return response()->json([
            'status' => 'success',
            'message' => 'Logged in successfully',
            'data' => array_merge(
                [
                    'token' => $accessToken,
                    'access_token' => $accessToken,
                    'refresh_token' => $refreshToken,
                    'is_profile_setup' => $institute->isProfileComplete(),
                ],
                $institute->toArray()
            ),
            'subscription' => $subscription
        ]);
    }

    public function logout(Request $request)
    {
        $user = $request->user();
        if (!$user || !($user instanceof Institute)) {
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

    public function profile(Request $request)
    {
        if (!$request->user() || !($request->user() instanceof Institute)) {
            return response()->json(['status' => 'error', 'message' => 'Unauthorized'], 401);
        }

        return response()->json([
            'status' => 'success',
            'data' => $request->user()
        ]);
    }

    public function uploadLogo(Request $request)
    {
        if (!$request->user() || !($request->user() instanceof Institute)) {
            return response()->json(['status' => 'error', 'message' => 'Unauthorized'], 401);
        }

        $request->validate([
            'logo' => 'required|image|max:2048',
        ]);

        $institute = $request->user();

        if ($request->hasFile('logo')) {
            if ($institute->logo && Storage::disk('public')->exists($institute->logo)) {
                Storage::disk('public')->delete($institute->logo);
            }

            $path = $request->file('logo')->store('institutes/logos', 'public');
            $institute->update(['logo' => $path]);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Logo uploaded successfully.',
            'data' => ['logo' => $institute->logo],
        ]);
    }
}
