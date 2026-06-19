<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Mail;
use App\Mail\ForgotPasswordMail;

class StudentAuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $student = Student::where('email', $request->email)->first();

        if (!$student || !Hash::check($request->password, $student->password)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Invalid credentials.',
            ], 401);
        }

        $accessToken = $student->createToken('access_token', ['access-api'], now()->addHour())->plainTextToken;
        $refreshToken = $student->createToken('refresh_token', ['refresh-token'], now()->addHours(24))->plainTextToken;

        return response()->json([
            'status' => 'success',
            'message' => 'Logged in successfully',
            'data' => array_merge(
                [
                    'token' => $accessToken,
                    'access_token' => $accessToken,
                    'refresh_token' => $refreshToken,
                ],
                $student->toArray()
            )
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

    public function profile(Request $request)
    {
        if (!$request->user()) {
            return response()->json(['status' => 'error', 'message' => 'Unauthorized'], 401);
        }

        return response()->json([
            'status' => 'success',
            'data' => $request->user()->load(['institute', 'batch'])
        ]);
    }

    public function sendResetPasswordEmail(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:students,email',
        ]);

        $student = Student::where('email', $request->email)->first();
        $institute = $student->institute;

        $uppercase = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $lowercase = 'abcdefghijklmnopqrstuvwxyz';
        $numbers = '0123456789';
        $special = '@#$%&*';
        
        $password = $uppercase[rand(0, strlen($uppercase)-1)] . 
                    $lowercase[rand(0, strlen($lowercase)-1)] . 
                    $numbers[rand(0, strlen($numbers)-1)] . 
                    $special[rand(0, strlen($special)-1)] . 
                    \Illuminate\Support\Str::random(4); // Total 8 characters

        $student->update([
            'password' => Hash::make($password),
            'otp' => null,
            'otp_expires_at' => null,
        ]);

        try {
            Mail::to($student->email)->send(new \App\Mail\StudentPasswordSentMail(
                $student->name,
                $student->email,
                $password,
                $institute ? $institute->institute_name : 'Fee Easy',
                $institute ? $institute->logo : null
            ));
        } catch (\Exception $e) {
            \Log::error("Failed to send student forgot password email: " . $e->getMessage());
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Your password has been reset successfully and the new password has been sent to your email.',
        ]);
    }
}
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Your password has been reset successfully and the new password has been sent to your email.',
        ]);
    }
}
