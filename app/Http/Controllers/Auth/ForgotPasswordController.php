<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\OtpService;
use App\Mail\OtpMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ForgotPasswordController extends Controller
{
    public function create()
    {
        return view('auth.forgot-password');
    }

    public function send(Request $request, OtpService $otpService)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email'
        ]);

        $email = strtolower($request->email);

        $user = User::where('email', $email)->first();

        $otp = $otpService->generate($email, 'password_reset');

        Mail::to($email)->send(new OtpMail($otp->code));

        session()->put('reset_email', $email);

        return redirect()->route('password.verify')
    ->with('success', 'OTP has been sent to your registered email.');

    }
}
