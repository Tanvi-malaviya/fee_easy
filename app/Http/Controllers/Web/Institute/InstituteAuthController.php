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
        if (Auth::guard('institute')->check()) {
            $user = Auth::guard('institute')->user();
            
            if ($user->email_verified_at && $user->isProfileComplete()) {
                return redirect()->route('institute.dashboard');
            }
            
            // Strict Flow: If unverified and page is refreshed, force re-registration
            if (!$user->email_verified_at) {
                Auth::guard('institute')->logout();
                $user->delete(); // Clean up unverified record
                return view('institute.auth.register', ['initialStep' => 1]);
            }
            
            // If verified but profile incomplete, stay at Step 3
            return view('institute.auth.register', ['initialStep' => 3]);
        }
        
        return view('institute.auth.register', ['initialStep' => 1]);
    }

    /**
     * Handle registration (AJAX support).
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

        try {
            Mail::to($institute->email)->send(new OtpMail($otp));
        } catch (\Exception $e) {
            \Log::error('Mail Error: ' . $e->getMessage());
        }

        Auth::guard('institute')->login($institute);

        if ($request->ajax()) {
            return response()->json([
                'status' => 'success',
                'message' => 'Registration successful. OTP sent to email.',
            ]);
        }

        return redirect()->route('institute.register');
    }

    /**
     * Show OTP verification form (Redirect to unified register).
     */
    public function showVerifyOtp()
    {
        return redirect()->route('institute.register');
    }

    /**
     * Handle OTP verification (AJAX support).
     */
    public function verifyOtp(Request $request)
    {
        $request->validate([
            'otp' => ['required', 'numeric'],
        ]);

        $user = Auth::guard('institute')->user();

        if ($user && $user->otp == $request->otp && $user->otp_expires_at->isFuture()) {
            $user->update([
                'otp' => null,
                'otp_expires_at' => null,
                'email_verified_at' => Carbon::now(),
            ]);

            if ($request->ajax()) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'OTP verified successfully.',
                ]);
            }

            return redirect()->route('institute.dashboard');
        }

        if ($request->ajax()) {
            return response()->json([
                'status' => 'error',
                'message' => 'The provided OTP is invalid or has expired.',
            ], 422);
        }

        throw ValidationException::withMessages([
            'otp' => 'The provided OTP is invalid or has expired.',
        ]);
    }

    /**
     * Handle final account setup (Step 3).
     */
    public function setupProfile(Request $request)
    {
        if (!Auth::guard('institute')->check()) {
            return response()->json(['status' => 'error', 'message' => 'Unauthorized'], 401);
        }

        $request->validate([
            'phone' => ['required', 'string', 'max:15'],
            'city' => ['required', 'string', 'max:100'],
            'state' => ['required', 'string', 'max:100'],
            'pincode' => ['required', 'string', 'max:20'],
            'address' => ['required', 'string'],
            'logo' => ['nullable', 'image', 'max:2048'],
        ]);

        $institute = Auth::guard('institute')->user();
        $data = $request->only(['phone', 'city', 'state', 'pincode', 'address']);

        if ($request->hasFile('logo')) {
            $path = $request->file('logo')->store('institutes/logos', 'public');
            $data['logo'] = $path;
        }

        $institute->update($data);

        return response()->json([
            'status' => 'success',
            'message' => 'Profile setup completed successfully.',
            'redirect' => route('institute.dashboard')
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
