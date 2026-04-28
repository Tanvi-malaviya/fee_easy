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

class InstituteAuthController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:institutes,email',
            'phone' => 'required|string|unique:institutes,phone',
            'password' => 'required|string|min:8|confirmed',
            'institute_name' => 'required|string|max:255',
        ]);

        $otp = rand(100000, 999999);

        $institute = Institute::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
            'institute_name' => $request->institute_name,
            'otp' => $otp,
            'otp_expires_at' => Carbon::now()->addMinutes(10),
            'status' => 'pending', // Or active, depends on your flow
        ]);

        // In production, send OTP via SMS/Email here

        return response()->json([
            'status' => 'success',
            'message' => 'Institute registered successfully. Please verify your OTP.',
            'data' => [
                'email' => $institute->email,
                'otp' => $otp, // Returning for testing purposes
            ]
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
            'data' => array_merge(
                ['token' => $token],
                $institute->toArray()
            )
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

        // In production, send OTP via SMS/Email here

        return response()->json([
            'status' => 'success',
            'message' => 'A new OTP has been sent to your email/phone.',
            'data' => [
                'otp' => $otp // Returning for testing purposes
            ]
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
                ['token' => $token],
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
