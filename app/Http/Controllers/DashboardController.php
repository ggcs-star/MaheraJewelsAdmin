<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Main dashboard - redirects to role-specific dashboard
     */
    public function index()
    {
        abort_if(!Auth::user()->email_verified_at, 403);

        $user = Auth::user();

        if ($user->hasRole('admin')) {
            return redirect(admin_route('dashboard'));
        }

        if ($user->hasRole('user')) {
            return redirect()->route('users.dashboard');
        }

        return redirect()->route('login')->with('error', 'You do not have a valid role assigned.');
    }

    /**
     * Admin Dashboard
     */
    public function admin()
    {
        abort_if(!Auth::user()->hasRole('admin'), 403);
        return view('dashboard.admin');
    }


    /**
     * User Dashboard
     */
    public function user()
    {
        abort_if(!Auth::user()->hasRole('user'), 403);
        return view('dashboard.user');
    }
}
