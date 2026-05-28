<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EnsureHasActiveSubscription
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
            if (!$institute->hasActiveSubscription()) {
                $routeName = $request->route() ? $request->route()->getName() : '';
                $isPost = $request->isMethod('post');

                // Check if the current request is trying to add/create a new resource
                $isBlockedRoute = str_ends_with($routeName, '.store') || 
                                  str_ends_with($routeName, '.create');

                // Exclude system critical routes (auth, profile, fcm, subscriptions renewal, plans)
                $isExcludedPath = $request->is('*/logout') || 
                                   $request->is('*/profile*') || 
                                   $request->is('*/fcm-token') || 
                                   $request->is('*/subscription*') || 
                                   $request->is('*/plans*');

                // Block if it's a creation route, or a non-excluded POST request for data entry
                if (($isBlockedRoute || ($isPost && !$isExcludedPath))) {
                    if ($request->expectsJson()) {
                        return response()->json([
                            'status' => 'error',
                            'message' => 'Your subscription has expired or is cancelled. Please renew your subscription to add new records.'
                        ], 403);
                    }

                    return redirect()->back()->with('error', 'Your subscription has expired or is cancelled. Please renew your subscription to add new records.');
                }
            }
        }

        return $next($request);
    }
}
