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
            return redirect()->route('institute.dashboard');
        }
        return view('institute.auth.login');
    }

    /**
     * Show the registration form.
     */
    public function showRegister()
    {
        if (Auth::guard('institute')->check()) {
            $user = Auth::guard('institute')->user();
            
            if ($user->email_verified_at && $user->isProfileComplete()) {
                return redirect()->route('institute.dashboard');
            }
            
            // If verified but profile incomplete, stay at Step 3
            if ($user->email_verified_at) {
                return view('institute.auth.register', ['initialStep' => 3]);
            }
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
                'email' => ['required', 'string', 'email', 'max:255', 'unique:institutes'],
                'password' => ['required', 'string', 'min:8', 'confirmed'],
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
                Mail::to($request->email)->send(new OtpMail($otp));
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

            // OTP Valid - Now create the record in database
            $data = Session::get('registration_data');
            
            $institute = Institute::create([
                'institute_name' => $data['institute_name'],
                'name' => $data['name'],
                'email' => $data['email'],
                'phone' => '', 
                'password' => $data['password'],
                'email_verified_at' => now(),
                'status' => 'active',
            ]);

            // Clear session
            Session::forget(['registration_data', 'registration_otp', 'registration_otp_expires_at']);
            Session::save();

            Auth::guard('institute')->login($institute);

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
            Mail::to($email)->send(new OtpMail($otp));
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
                'phone' => 'required|string|max:15',
                'city' => 'required|string|max:255',
                'state' => 'required|string|max:255',
                'pincode' => 'required|string|max:10',
                'address' => 'required|string',
                'logo' => 'nullable|image|max:2048',
            ]);

            $institute = Auth::guard('institute')->user();
            if (!$institute) {
                return response()->json(['status' => 'error', 'message' => 'Unauthorized'], 401);
            }
            
            $data = $request->only(['phone', 'city', 'state', 'pincode', 'address']);
            
            if ($request->hasFile('logo')) {
                $path = $request->file('logo')->store('institute_logos', 'public');
                $data['logo'] = $path;
            }

            $institute->update($data);

            return response()->json([
                'status' => 'success',
                'redirect' => route('institute.dashboard'),
            ]);
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

        if (Auth::guard('institute')->attempt($credentials, $request->remember)) {
            $request->session()->regenerate();
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
        Auth::guard('institute')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('institute.login');
    }
}
