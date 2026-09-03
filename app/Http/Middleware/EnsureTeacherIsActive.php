<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EnsureTeacherIsActive
{
    /**
     * Blocks an already-logged-in teacher (web session or Sanctum token) on
     * their very next request once an institute flips is_login_blocked —
     * token revocation covers the API path immediately, this covers the
     * web session path which has no per-request DB check otherwise.
     */
    public function handle(Request $request, Closure $next)
    {
        $staff = null;

        if (Auth::guard('teacher')->check()) {
            $staff = Auth::guard('teacher')->user();
        } elseif (Auth::guard('sanctum')->check()) {
            $user = Auth::guard('sanctum')->user();
            if ($user instanceof \App\Models\Staff) {
                $staff = $user;
            }
        }

        if ($staff && $staff->is_login_blocked) {
            if (Auth::guard('teacher')->check()) {
                Auth::guard('teacher')->logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();
            }

            if ($staff->currentAccessToken()) {
                $staff->currentAccessToken()->delete();
            }

            if ($request->expectsJson() || $request->is('api/*')) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Your account login has been blocked. Please contact your institute.',
                ], 403);
            }

            return redirect()->route('teacher.login')->withErrors([
                'email' => 'Your account login has been blocked. Please contact your institute.',
            ]);
        }

        return $next($request);
    }
}
