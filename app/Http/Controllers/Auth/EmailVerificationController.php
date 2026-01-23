<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Services\OtpService;
use App\Models\User;
use Illuminate\Http\Request;

class EmailVerificationController extends Controller
{
    public function index()
    {
        $email = session('verify_email');

       
        if (!$email) {
            return redirect()->route('register');
        }

        return view('auth.verify-email', [
            'email' => $email
        ]);
    }

    public function verify(Request $request, OtpService $otpService)
    {
        $request->validate([
            'otp' => 'required|string'
        ]);

        $email = session('verify_email');

        if (!$email) {
    return redirect()->route('register')
        ->with('error', 'Your verification session has expired. Please register again.');
}


        $otp = $otpService->verify(
            $email,
            trim($request->otp),
            'email_verification'
        );

        if (!$otp) {
            return back()->withErrors([
                'otp' => 'Invalid or expired OTP'
            ]);
        }

        $user = User::where('email', $email)->firstOrFail();

        $user->update([
            'email_verified_at' => now()
        ]);

        $otp->delete();
        auth()->login($user);

        session()->forget('verify_email');

        return redirect()->route('dashboard')
    ->with('success', 'Email verified successfully.');

    }
}
