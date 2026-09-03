<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EnsureTeacherPasswordChanged
{
    public function handle(Request $request, Closure $next)
    {
        $teacher = Auth::guard('teacher')->user();

        if ($teacher && $teacher->must_change_password && !$request->routeIs('teacher.password.change', 'teacher.password.change.update', 'teacher.logout')) {
            return redirect()->route('teacher.password.change');
        }

        return $next($request);
    }
}
