<?php

namespace App\Http\Controllers\Web\Institute;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

use App\Models\Institute;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Mail\OtpMail;
use Carbon\Carbon;

class InstituteAuthController extends Controller
{
    /**
     * Show the institute login form.
     */
    public function showLogin()
    {
        if (Auth::guard('institute')->check()) {
            if (Auth::guard('institute')->user()->email_verified_at) {
                return redirect()->route('institute.dashboard');
            }
            return redirect()->route('institute.verify-otp');
        }
        return view('institute.auth.login');
    }

    /**
     * Show the registration form.
     */
    public function showRegister()
    {
        return view('institute.auth.register');
    }

    /**
     * Handle registration.
     */
    public function register(Request $request)
    {
        $request->validate([
            'institute_name' => ['required', 'string', 'max:255'],
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:institutes'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $otp = rand(100000, 999999);

        $institute = Institute::create([
            'institute_name' => $request->institute_name,
            'name' => $request->name,
            'email' => $request->email,
            'phone' => '', // Temporary empty phone
            'password' => Hash::make($request->password),
            'otp' => $otp,
            'otp_expires_at' => Carbon::now()->addMinutes(10),
            'status' => 'active',
        ]);

        Mail::to($institute->email)->send(new OtpMail($otp));

        Auth::guard('institute')->login($institute);

        return redirect()->route('institute.verify-otp');
    }

    /**
     * Show OTP verification form.
     */
    public function showVerifyOtp()
    {
        return view('institute.auth.verify-otp');
    }

    /**
     * Handle OTP verification.
     */
    public function verifyOtp(Request $request)
    {
        $request->validate([
            'otp' => ['required', 'numeric'],
        ]);

        $user = Auth::guard('institute')->user();

        if ($user->otp == $request->otp && $user->otp_expires_at->isFuture()) {
            $user->update([
                'otp' => null,
                'otp_expires_at' => null,
                'email_verified_at' => Carbon::now(),
            ]);

            return redirect()->route('institute.dashboard');
        }

        throw ValidationException::withMessages([
            'otp' => 'The provided OTP is invalid or has expired.',
        ]);
    }

    /**
     * Handle the login request.
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::guard('institute')->attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            return redirect()->intended(route('institute.dashboard'));
        }

        throw ValidationException::withMessages([
            'email' => __('auth.failed'),
        ]);
    }

    /**
     * Log the institute out.
     */
    public function logout(Request $request)
    {
        if (Auth::guard('institute')->check()) {
            Auth::guard('institute')->logout();
        }

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('institute.login');
    }
    /**
     * Update the institute administrator's password.
     */
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => ['required', 'current_password:institute'],
            'password' => ['required', 'confirmed', \Illuminate\Validation\Rules\Password::defaults()],
        ]);

        $user = Auth::guard('institute')->user();
        $user->update([
            'password' => \Illuminate\Support\Facades\Hash::make($request->password),
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Password updated successfully'
        ]);
    }
}
