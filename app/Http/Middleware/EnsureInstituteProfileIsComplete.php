<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureInstituteProfileIsComplete
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (auth('institute')->check()) {
            $user = auth('institute')->user();
            
            // Skip check if the user is already on the profile page
            if ($request->routeIs('institute.profile.index')) {
                return $next($request);
            }

            if (!$user->isProfileComplete()) {
                return redirect()->route('institute.profile.index')->with('setup_required', true);
            }
        }

        return $next($request);
    }
}
