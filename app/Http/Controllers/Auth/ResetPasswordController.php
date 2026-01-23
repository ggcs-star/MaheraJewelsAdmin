<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\OtpService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ResetPasswordController extends Controller
{
    public function index()
    {
        abort_if(!session('reset_email'), 403);
        return view('auth.reset-password');
    }

  public function reset(Request $request, OtpService $otpService)
{
    $request->validate([
        'otp' => 'required|string',
        'password' => 'required|confirmed|min:8'
    ]);

    $email = session('reset_email');

    if (!$email) {
        return redirect()->route('forgot.password')
            ->with('error', 'Your password reset session has expired.');
    }

    $otp = $otpService->verify(
        $email,
        trim($request->otp),
        'password_reset'
    );

    if (!$otp) {
        return back()->with('error', 'Invalid or expired OTP.');
    }

    $user = User::where('email', $email)->firstOrFail();

    $user->update([
        'password' => Hash::make($request->password)
    ]);

    $otp->delete();
    session()->forget('reset_email');

    return redirect()->route('login')
        ->with('success', 'Password reset successfully. Please login.');
}

}
