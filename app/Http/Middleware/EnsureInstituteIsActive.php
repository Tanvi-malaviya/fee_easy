<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EnsureInstituteIsActive
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $institute = null;

        if (Auth::guard('institute')->check()) {
            $institute = Auth::guard('institute')->user();
        } elseif (Auth::guard('sanctum')->check()) {
            $user = Auth::guard('sanctum')->user();
            if ($user instanceof \App\Models\Institute) {
                $institute = $user;
            }
        }

        if ($institute) {
            // Check active session existence for Web Guard specifically (bypassed in unit tests)
            if (Auth::guard('institute')->check() && !$request->routeIs('institute.logout') && !app()->runningUnitTests()) {
                $session = \App\Models\DeviceSession::findSessionForUser($institute, $request);

                if (!$session) {
                    Auth::guard('institute')->logout();
                    $request->session()->invalidate();
                    $request->session()->regenerateToken();

                    return redirect()->route('institute.login')->withErrors([
                        'email' => 'Your session was terminated from another device.',
                    ]);
                } else {
                    $session->update([
                        'last_open' => now()
                    ]);
                }
            }

            if ($institute->status !== 'active') {
                if ($request->expectsJson() || $request->is('api/*')) {
                    // Exclude essential endpoints (logout, profile check, fcm, app-versions) from block
                    $isExcludedPath = $request->is('*/logout') || 
                                       $request->is('*/profile*') || 
                                       $request->is('*/fcm-token') ||
                                       $request->is('*/app-versions');

                    if (!$isExcludedPath) {
                        return response()->json([
                            'status' => $institute->status,
                            'message' => "Your institute account is currently marked as {$institute->status}. Please contact the administrator or support to activate your account."
                        ], 403);
                    }
                } else {
                    if (!$request->routeIs('institute.dashboard') && !$request->routeIs('institute.logout')) {
                        return redirect()->route('institute.dashboard');
                    }
                }
            }
        }

        return $next($request);
    }
}

