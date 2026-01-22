<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\OtpService;
use App\Mail\OtpMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class RegisterController extends Controller
{
    public function create()
    {
        return view('auth.register');
    }

    public function store(Request $request, OtpService $otpService)
    {
        $data = $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:users',
            'password' => 'required|confirmed|min:8'
        ]);

        DB::transaction(function () use ($data, $otpService) {
            $user = User::create([
                'name' => $data['name'],
                'email' => strtolower($data['email']),
                'password' => Hash::make($data['password'])
            ]);

            $user->assignRole('user');

            $otp = $otpService->generate($user->email, 'email_verification');

            Mail::to($user->email)->send(new OtpMail($otp->code));

            Auth::login($user, true);
        });

        // ✅ IMPORTANT FIX
        session()->put('verify_email', strtolower($data['email']));

        return redirect()->route('verify.email');
    }
}
