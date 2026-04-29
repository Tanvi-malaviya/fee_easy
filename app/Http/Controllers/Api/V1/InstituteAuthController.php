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
        $request->validate([
            'institute_name' => 'required|string|max:255',
            'email' => 'required|email|unique:institutes,email',
            'password' => 'required|string|min:8|max:15',
            'name' => 'nullable|string|max:255',
            'phone' => 'nullable|string',
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
            Mail::to($institute->email)->send(new OtpMail($otp));
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

        $token = $institute->createToken('institute_token')->plainTextToken;

        return response()->json([
            'status' => 'success',
            'message' => 'OTP verified successfully.',
            'data' => [
                'token' => $token
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
            Mail::to($institute->email)->send(new OtpMail($otp));
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
            Mail::to($institute->email)->send(new OtpMail($otp));
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

        $token = $institute->createToken('institute_token')->plainTextToken;

        return response()->json([
            'status' => 'success',
            'message' => 'Logged in successfully',
            'data' => array_merge(
                [
                    'token' => $token,
                    'is_profile_setup' => !empty($institute->institute_name) &&
                        !empty($institute->name) &&
                        !empty($institute->phone) &&
                        !empty($institute->address) &&
                        !empty($institute->city) &&
                        !empty($institute->state) &&
                        !empty($institute->country) &&
                        !empty($institute->pincode) &&
                        !empty($institute->logo),
                ],
                $institute->toArray()
            )
        ]);
    }

    public function logout(Request $request)
    {
        if (!$request->user() || !($request->user() instanceof Institute)) {
            return response()->json(['status' => 'error', 'message' => 'Unauthorized'], 401);
        }

        $request->user()->currentAccessToken()->delete();

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
