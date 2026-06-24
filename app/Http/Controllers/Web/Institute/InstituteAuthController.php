<?php

namespace App\Http\Controllers\Web\Institute;

use App\Http\Controllers\Controller;
use App\Models\Institute;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\OtpMail;
use Carbon\Carbon;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;

class InstituteAuthController extends Controller
{
    /**
     * Show the login form.
     */
    public function showLogin()
    {
        if (Auth::guard('institute')->check()) {
            $user = Auth::guard('institute')->user();
            if ($user->email_verified_at && $user->isProfileComplete()) {
                return redirect()->route('institute.dashboard');
            }
        }

        $email = request()->cookie('institute_email');
        $password = request()->cookie('institute_password');
        $remember = $email ? true : false;

        return view('institute.auth.login', compact('email', 'password', 'remember'));
    }

    /**
     * Show the registration form.
     */
    public function showRegister()
    {
        if (Auth::guard('institute')->check()) {
            $user = Auth::guard('institute')->user();

            // If already complete, go to dashboard
            if ($user->email_verified_at && $user->isProfileComplete()) {
                return redirect()->route('institute.dashboard');
            }

            // If verified but profile incomplete, show Step 3 (Setup)
            if ($user->email_verified_at) {
                return view('institute.auth.register', ['initialStep' => 3]);
            }

            // If logged in but NOT verified (e.g. from a manual DB entry or interrupted flow)
            if (!Session::has('registration_data')) {
                Session::put('registration_data', [
                    'institute_name' => $user->institute_name ?? 'Institute',
                    'name' => $user->name,
                    'email' => $user->email,
                    'password' => $user->password,
                ]);
                $otp = rand(100000, 999999);
                Session::put('registration_otp', $otp);
                Session::put('registration_otp_expires_at', Carbon::now()->addMinutes(10));
                Session::save();
                try {
                    Mail::to($user->email)->send(new OtpMail($otp, $user->name));
                } catch (\Exception $e) {
                    Log::error('Registration Mail Error: ' . $e->getMessage());
                }
            }

            return view('institute.auth.register', ['initialStep' => 2]);
        }

        // Force reset registration flow on manual page refresh (GET request)
        Session::forget(['registration_data', 'registration_otp', 'registration_otp_expires_at']);

        return view('institute.auth.register', ['initialStep' => 1]);
    }

    /**
     * Handle registration (AJAX support).
     */
    public function register(Request $request)
    {
        try {
            $request->validate([
                'institute_name' => ['required', 'string', 'max:255'],
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'string', 'email:rfc', 'max:255', 'unique:institutes'],
                'password' => [
                    'required',
                    'string',
                    'min:8',
                    'max:15',
                    'regex:/[a-z]/',      // at least one lowercase
                    'regex:/[A-Z]/',      // at least one uppercase
                    'regex:/[0-9]/',      // at least one number
                    'regex:/[\W_]/',      // at least one special character
                ],
            ], [
                'password.min' => 'Password must be at least 8 characters.',
                'password.max' => 'Password must not exceed 15 characters.',
                'password.regex' => 'Password must include an uppercase letter, a lowercase letter, a number, and a special character.',
            ]);

            $otp = rand(100000, 999999);

            // Store registration data in session instead of database
            Session::put('registration_data', [
                'institute_name' => $request->institute_name,
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);
            Session::put('registration_otp', $otp);
            Session::put('registration_otp_expires_at', Carbon::now()->addMinutes(10));
            Session::save(); // Explicitly save session for AJAX reliability

            // Send OTP email
            try {
                Mail::to($request->email)->send(new OtpMail($otp, $request->name));
            } catch (\Exception $e) {
                Log::error('Registration Mail Error: ' . $e->getMessage());
                // We still proceed so the user can try to resend or see the error
            }

            return response()->json([
                'status' => 'success',
                'message' => 'OTP sent to your email.',
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'status' => 'error',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('Registration Error: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Something went wrong. Please try again.'
            ], 500);
        }
    }

    /**
     * Verify OTP and create the record (AJAX support).
     */
    public function verifyOtp(Request $request)
    {
        try {
            $request->validate([
                'otp' => 'required|string|size:6',
            ]);

            if (!Session::has('registration_data') || !Session::has('registration_otp')) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Registration session expired. Please register again.'
                ], 400);
            }

            $sessionOtp = Session::get('registration_otp');
            $expiresAt = Session::get('registration_otp_expires_at');

            if (Carbon::now()->greaterThan($expiresAt)) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'OTP expired. Please resend.'
                ], 400);
            }

            if ($request->otp != $sessionOtp) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Invalid verification code.'
                ], 400);
            }

            // OTP Valid - Now create or update the record in database
            $data = Session::get('registration_data');

            $institute = Institute::where('email', $data['email'])->first();
            if ($institute) {
                $institute->update([
                    'email_verified_at' => now(),
                    'status' => 'active',
                    'register_source' => 'web',
                ]);
            } else {
                $institute = Institute::create([
                    'institute_name' => $data['institute_name'],
                    'name' => $data['name'],
                    'email' => $data['email'],
                    'phone' => '',
                    'password' => $data['password'],
                    'email_verified_at' => now(),
                    'status' => 'active',
                    'register_source' => 'web',
                ]);
            }

            // Assign Free Plan subscription (1 month = 30 days)
            $hasActiveSub = $institute->subscriptions()->whereIn('status', ['active'])->exists();
            if (!$hasActiveSub) {
                \App\Models\Subscription::create([
                    'institute_id' => $institute->id,
                    'plan_name' => 'Free Plan',
                    'amount' => 0,
                    'start_date' => now(),
                    'end_date' => now()->addDays(30),
                    'status' => 'active',
                ]);
            }

            // Clear session
            Session::forget(['registration_data', 'registration_otp', 'registration_otp_expires_at']);
            Session::save();

            Auth::guard('institute')->login($institute);
            // Register/reactivate web session in device_sessions
            $detection = \App\Models\DeviceSession::detect($request);
            $device = $detection['device'];
            $os = $detection['os'];
            $sessionId = $detection['session_id'];

            $existingSession = null;
            if (!empty($sessionId)) {
                $existingSession = $institute->deviceSessions()
                    ->withTrashed()
                    ->where('session_id', $sessionId)
                    ->first();
            } else {
                if ($device !== 'Unknown Device' && $os !== 'Unknown OS') {
                    $existingSession = $institute->deviceSessions()
                        ->withTrashed()
                        ->where('device', $device)
                        ->where('os', $os)
                        ->whereNull('session_id')
                        ->first();
                }
            }

            if ($existingSession) {
                if ($existingSession->trashed()) {
                    $existingSession->restore();
                }
                $existingSession->update([
                    'token_id' => null,
                    'session_id' => $sessionId,
                    'last_login' => now(),
                    'last_open' => now(),
                ]);
            } else {
                \App\Models\DeviceSession::create([
                    'institute_id' => $institute->id,
                    'token_id' => null,
                    'session_id' => $sessionId,
                    'device' => $device,
                    'os' => $os,
                    'last_login' => now(),
                    'last_open' => now(),
                ]);
            }
            try {
                Mail::to($institute->email)->send(new \App\Mail\AccountActivatedMail($institute->name, route('institute.login')));
            } catch (\Exception $e) {
                Log::error('Activation Mail Error: ' . $e->getMessage());
            }

            return response()->json([
                'status' => 'success',
                'message' => 'Email verified successfully.',
            ]);

        } catch (\Exception $e) {
            Log::error('OTP Verification Error: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Something went wrong.'
            ], 500);
        }
    }

    /**
     * Resend OTP.
     */
    public function resendOtp(Request $request)
    {
        if (!Session::has('registration_data')) {
            return response()->json(['status' => 'error', 'message' => 'Session expired.'], 400);
        }

        $email = Session::get('registration_data')['email'];
        $otp = rand(100000, 999999);

        Session::put('registration_otp', $otp);
        Session::put('registration_otp_expires_at', Carbon::now()->addMinutes(10));
        Session::save();

        try {
            $name = Session::get('registration_data')['name'] ?? 'User';
            Mail::to($email)->send(new OtpMail($otp, $name));
            return response()->json(['status' => 'success', 'message' => 'New OTP sent.']);
        } catch (\Exception $e) {
            Log::error('Resend OTP Error: ' . $e->getMessage());
            return response()->json(['status' => 'error', 'message' => 'Failed to send mail.'], 500);
        }
    }

    /**
     * Setup profile (Step 3).
     */
    public function setupProfile(Request $request)
    {
        try {
            $request->validate([
                'phone' => 'required|digits:10',
                'city' => 'required|string|max:255',
                'state' => 'required|string|max:255',
                'pincode' => 'required|string|max:10',
                'address' => 'required|string',
                'address_line_2' => 'nullable|string|max:255',
                'country' => 'required|string|max:255',
                'logo' => 'nullable|image|max:2048',
            ]);

            $institute = Auth::guard('institute')->user();
            if (!$institute) {
                return response()->json(['status' => 'error', 'message' => 'Unauthorized'], 401);
            }

            $data = $request->only(['phone', 'city', 'state', 'pincode', 'address', 'address_line_2', 'country']);

            if ($request->hasFile('logo')) {
                $path = $request->file('logo')->store('institute_logos', 'public');
                $data['logo'] = $path;
            }

            $institute->update($data);

            return response()->json([
                'status' => 'success',
                'redirect' => route('institute.dashboard'),
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'status' => 'error',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('Profile Setup Error: ' . $e->getMessage());
            return response()->json(['status' => 'error', 'message' => 'Something went wrong.'], 500);
        }
    }

    /**
     * Handle login.
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        $remember = $request->boolean('remember');

        // Check institute status is handled by middleware after login

        if (Auth::guard('institute')->attempt($credentials, $remember)) {
            $request->session()->regenerate();

            $institute = Auth::guard('institute')->user();

            // Detect device & OS
            $detection = \App\Models\DeviceSession::detect($request);
            $device = $detection['device'];
            $os = $detection['os'];
            $sessionId = $detection['session_id'];

            // Look up existing session (including soft-deleted ones)
            $existingSession = null;
            if (!empty($sessionId)) {
                $existingSession = $institute->deviceSessions()
                    ->withTrashed()
                    ->where('session_id', $sessionId)
                    ->first();
            } else {
                if ($device !== 'Unknown Device' && $os !== 'Unknown OS') {
                    $existingSession = $institute->deviceSessions()
                        ->withTrashed()
                        ->where('device', $device)
                        ->where('os', $os)
                        ->whereNull('session_id')
                        ->first();
                }
            }

            $isNewOrLoggedOutDevice = !$existingSession || $existingSession->trashed();

            if ($isNewOrLoggedOutDevice && $institute->deviceSessions()->count() >= 5) {
                Auth::guard('institute')->logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                return back()->withErrors([
                    'email' => 'Maximum device limit reached (5 devices). Please log out of another device first.',
                ])->onlyInput('email');
            }

            // Create or reactivate session (token_id is null for web panel)
            if ($existingSession) {
                if ($existingSession->trashed()) {
                    $existingSession->restore();
                }
                $existingSession->update([
                    'token_id' => null,
                    'session_id' => $sessionId,
                    'last_login' => now(),
                    'last_open' => now(),
                ]);
            } else {
                \App\Models\DeviceSession::create([
                    'institute_id' => $institute->id,
                    'token_id' => null,
                    'session_id' => $sessionId,
                    'device' => $device,
                    'os' => $os,
                    'last_login' => now(),
                    'last_open' => now(),
                ]);
            }

            if ($remember) {
                \Illuminate\Support\Facades\Cookie::queue('institute_email', $request->email, 43200);
                \Illuminate\Support\Facades\Cookie::queue('institute_password', $request->password, 43200);
            } else {
                \Illuminate\Support\Facades\Cookie::queue(\Illuminate\Support\Facades\Cookie::forget('institute_email'));
                \Illuminate\Support\Facades\Cookie::queue(\Illuminate\Support\Facades\Cookie::forget('institute_password'));
            }

            return redirect()->intended(route('institute.dashboard'));
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    /**
     * Handle logout.
     */
    public function logout(Request $request)
    {
        $institute = Auth::guard('institute')->user();
        if ($institute) {
            $session = \App\Models\DeviceSession::findSessionForUser($institute, $request);

            if ($session) {
                $session->update(['token_id' => null]);
                $session->delete();
            }
        }

        Auth::guard('institute')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('institute.login');
    }

    /**
     * Show Forgot Password Form
     */
    public function showForgotPassword()
    {
        return view('institute.auth.forgot-password');
    }

    /**
     * Send Reset Link (Using OTP for simplicity like registration)
     */
    public function sendResetLink(Request $request)
    {
        $request->validate(['email' => 'required|email|exists:institutes,email']);

        $otp = rand(100000, 999999);
        Session::put('reset_email', $request->email);
        Session::put('reset_otp', $otp);
        Session::put('reset_otp_expires_at', Carbon::now()->addMinutes(15));
        Session::save();

        try {
            $institute = Institute::where('email', $request->email)->first();
            $name = $institute ? $institute->name : 'User';
            Mail::to($request->email)->send(new \App\Mail\ForgotPasswordMail($otp, $name));
            return response()->json(['status' => 'success', 'message' => 'OTP sent to your email.']);
        } catch (\Exception $e) {
            Log::error('Password Reset Mail Error: ' . $e->getMessage());
            return response()->json(['status' => 'error', 'message' => 'Failed to send email.'], 500);
        }
    }

    /**
     * Show Reset Password Form
     */
    public function showResetPassword(Request $request, $token = null)
    {
        return view('institute.auth.reset-password');
    }

    /**
     * Handle Password Reset
     */
    public function resetPassword(Request $request)
    {
        $request->validate([
            'otp' => 'required|string|size:6',
            'password' => [
                'required',
                'string',
                'min:8',
                'max:15',
                'confirmed',
                'regex:/[a-z]/',      // at least one lowercase
                'regex:/[A-Z]/',      // at least one uppercase
                'regex:/[0-9]/',      // at least one number
                'regex:/[\W_]/',      // at least one special character
            ],
        ], [
            'password.min' => 'Password must be at least 8 characters.',
            'password.max' => 'Password must not exceed 15 characters.',
            'password.confirmed' => 'New password and confirmation do not match.',
            'password.regex' => 'Password must include an uppercase letter, a lowercase letter, a number, and a special character.',
        ]);

        if (!Session::has('reset_email') || !Session::has('reset_otp')) {
            return response()->json(['status' => 'error', 'message' => 'Session expired.'], 400);
        }

        if (Carbon::now()->greaterThan(Session::get('reset_otp_expires_at'))) {
            return response()->json(['status' => 'error', 'message' => 'OTP expired.'], 400);
        }

        if ($request->otp != Session::get('reset_otp')) {
            return response()->json(['status' => 'error', 'message' => 'Invalid code.'], 400);
        }

        $institute = Institute::where('email', Session::get('reset_email'))->first();
        if ($institute) {
            $institute->update(['password' => Hash::make($request->password)]);
            Session::forget(['reset_email', 'reset_otp', 'reset_otp_expires_at']);
            Session::save();
            return response()->json(['status' => 'success', 'message' => 'Password reset successfully.']);
        }

        return response()->json(['status' => 'error', 'message' => 'User not found.'], 404);
    }
}
