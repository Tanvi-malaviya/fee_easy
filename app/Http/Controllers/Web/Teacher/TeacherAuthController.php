<?php

namespace App\Http\Controllers\Web\Teacher;

use App\Http\Controllers\Controller;
use App\Mail\ForgotPasswordMail;
use App\Models\Staff;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class TeacherAuthController extends Controller
{
    public function showLogin()
    {
        if (Auth::guard('teacher')->check()) {
            return redirect()->route('teacher.dashboard');
        }

        return view('teacher.auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        $staff = Staff::where('email', $credentials['email'])->first();

        if ($staff && $staff->is_login_blocked) {
            return back()->withErrors([
                'email' => 'Your account login has been blocked. Please contact your institute.',
            ])->onlyInput('email');
        }

        if (Auth::guard('teacher')->attempt($credentials)) {
            $request->session()->regenerate();

            return redirect()->intended(route('teacher.dashboard'));
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::guard('teacher')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('teacher.login');
    }

    public function showChangePassword()
    {
        return view('teacher.auth.change-password');
    }

    public function updatePassword(Request $request)
    {
        $teacher = Auth::guard('teacher')->user();

        $rules = [
            'password' => [
                'required', 'string', 'min:8', 'max:15', 'confirmed',
                'regex:/[a-z]/', 'regex:/[A-Z]/', 'regex:/[0-9]/', 'regex:/[\W_]/',
            ],
        ];
        if (!$teacher->must_change_password) {
            $rules['current_password'] = 'required|string';
        }

        $request->validate($rules, [
            'password.min' => 'Password must be at least 8 characters.',
            'password.max' => 'Password must not exceed 15 characters.',
            'password.confirmed' => 'New password and confirmation do not match.',
            'password.regex' => 'Password must include an uppercase letter, a lowercase letter, a number, and a special character.',
        ]);

        if (!$teacher->must_change_password && !Hash::check($request->current_password, $teacher->password)) {
            return back()->withErrors(['current_password' => 'Current password is incorrect.']);
        }

        $teacher->update([
            'password' => Hash::make($request->password),
            'must_change_password' => false,
        ]);

        return redirect()->route('teacher.dashboard')->with('success', 'Password updated successfully.');
    }

    public function showForgotPassword()
    {
        return view('teacher.auth.forgot-password');
    }

    public function sendResetLink(Request $request)
    {
        $request->validate(['email' => 'required|email|exists:staff,email']);

        $staff = Staff::where('email', $request->email)->first();
        $otp = rand(100000, 999999);
        Cache::put('teacher_reset_otp_' . strtolower($request->email), $otp, now()->addMinutes(15));

        try {
            Mail::to($request->email)->send(new ForgotPasswordMail($otp, $staff->full_name));
            return response()->json(['status' => 'success', 'message' => 'OTP sent to your email.']);
        } catch (\Exception $e) {
            Log::error('Teacher password reset mail error: ' . $e->getMessage());
            return response()->json(['status' => 'error', 'message' => 'Failed to send email.'], 500);
        }
    }

    public function showResetPassword()
    {
        return view('teacher.auth.reset-password');
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:staff,email',
            'otp' => 'required|string|size:6',
            'password' => [
                'required', 'string', 'min:8', 'max:15', 'confirmed',
                'regex:/[a-z]/', 'regex:/[A-Z]/', 'regex:/[0-9]/', 'regex:/[\W_]/',
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

        return response()->json(['status' => 'success', 'message' => 'Password reset successfully.']);
    }
}
