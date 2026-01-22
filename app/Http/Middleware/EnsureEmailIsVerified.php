<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class EnsureEmailIsVerified
{
    public function handle($request, Closure $next)
    {
        // agar login hi nahi hai
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        // agar email verify nahi hai
        if (
            !Auth::user()->email_verified_at &&
            !$request->routeIs('verify.email')
        ) {
            return redirect()->route('verify.email');
        }

        return $next($request);
    }
}
