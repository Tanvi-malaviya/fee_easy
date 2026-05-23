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
        if (Auth::guard('institute')->check()) {
            $institute = Auth::guard('institute')->user();

            if (in_array($institute->status, ['suspended', 'blocked'])) {
                Auth::guard('institute')->logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                return redirect()->route('institute.login')->withErrors([
                    'email' => "Your account is currently {$institute->status}. Please contact support.",
                ]);
            }
        }

        return $next($request);
    }
}
