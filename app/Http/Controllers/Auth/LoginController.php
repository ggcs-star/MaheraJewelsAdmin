<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function create()
    {
        return view('auth.login');
    }

   public function store(Request $request)
{
    $credentials = $request->validate([
        'email' => 'required|email',
        'password' => 'required'
    ]);

    if (!\App\Models\User::where('email', $credentials['email'])->exists()) {
        return back()->with('error', 'You are not registered. Please register first.');
    }

    if (!Auth::attempt($credentials, true)) {
        return back()->with('error', 'Invalid email or password.');
    }

    $request->session()->regenerate();

    return redirect()->route('dashboard')
        ->with('success', 'Login successful.');
}


    public function logout(Request $request)
{
    Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();

    return redirect()->route('login')
        ->with('success', 'You have been logged out successfully.');
}

}
