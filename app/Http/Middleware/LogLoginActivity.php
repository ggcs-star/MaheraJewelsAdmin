<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use App\Services\LoginActivityService;

class LogLoginActivity
{
    public function handle($request, Closure $next)
    {
        $response = $next($request);

        if (Auth::check() && $request->routeIs('dashboard')) {
            app(LoginActivityService::class)->store(Auth::user(), $request);
        }

        return $response;
    }
}
