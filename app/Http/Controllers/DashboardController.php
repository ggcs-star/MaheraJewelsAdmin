<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        abort_if(!Auth::user()->email_verified_at,403);

        if (Auth::user()->hasRole('admin')) {
            return view('dashboard.admin');
        }

        return view('dashboard.user');
    }
}
