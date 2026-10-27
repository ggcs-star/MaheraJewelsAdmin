<?php

namespace App\Http\Controllers\Api\Users;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\OtpService;
use App\Mail\OtpMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Throwable;
use App\Services\ActivityLogService;
use App\Traits\ApiResponse;

use Illuminate\Support\Facades\Http;
use UAParser\Parser;

class AuthController extends Controller
{
use ApiResponse;

    private function normalizeEmail(string $email): string
    {
        return strtolower(trim($email));
    }

    private function sendEmailOtp(string $email, OtpService $otpService): void
    {
            Log::info('OTP function triggered for: ' . $email);

        $otp = $otpService->generate($email, 'email_verification');
        Mail::to($email)->send(new OtpMail($otp->code));
    }

    public function register(Request $request, OtpService $otpService)
    {

        Log::info('MOBILE HIT REGISTER', [
            'url' => request()->fullUrl(),
            'ip' => request()->ip(),
            'data' => request()->all()
        ]);
        Log::info('RAW INPUT', [
            'raw' => file_get_contents('php://input'),
            'all' => request()->all(),
            'headers' => request()->headers->all()
        ]);

        $data = $request->validate(
            [
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email',
                'password' => [
                    'required',
                    'confirmed',
                    'min:8',
                    'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*#?&]).+$/'
                ],
            ],
            [
                'password.regex' =>
                    'Password must contain at least 1 uppercase letter, 1 lowercase letter, 1 number, and 1 special symbol.',
            ]
        );

        try {
            DB::beginTransaction();

            $email = $this->normalizeEmail($data['email']);

            $user = User::create([
                'name' => $data['name'],
                'email' => $email,
                'password' => Hash::make($data['password']),
            ]);

            $user->assignRole('user');

            $this->sendEmailOtp($email, $otpService);

            DB::commit();

            return $this->successResponse(
                'Registration successful. OTP has been sent to your email.',
                [
                    'user' => [
                        'id' => $user->id,
                        'name' => $user->name,
                        'email' => $user->email,
                    ],
                    'otp_sent_to' => $email,
                ],
                201
            );

        } catch (Throwable $e) {

            DB::rollBack();

            Log::error('Register API Error', [
                'message' => $e->getMessage(),
                'email' => $data['email'] ?? null,
            ]);

            return $this->errorResponse('Something went wrong', 500);
        }
    }



    public function verifyEmailOtp(Request $request, OtpService $otpService)
    {
        $data = $request->validate([
            'email' => 'required|email',
            'otp' => 'required|string',
        ]);

        try {
            $email = $this->normalizeEmail($data['email']);

            $otp = $otpService->verify($email, $data['otp'], 'email_verification');

            if (!$otp) {
                return $this->errorResponse('Invalid or expired OTP', 422);
            }

            DB::beginTransaction();

            $user = User::where('email', $email)->first();

            if (!$user) {
                return $this->errorResponse('User not found', 404);
            }

            if (!$user->email_verified_at) {
                $user->update([
                    'email_verified_at' => now()
                ]);
            }

            $otp->delete();

            DB::commit();

            return $this->successResponse(
                'Email verified successfully',
                [
                    'email_verified' => true,
                    'email_verified_at' => $user->email_verified_at,
                ]
            );

        } catch (Throwable $e) {

            DB::rollBack();

            Log::error('Verify OTP API Error', [
                'message' => $e->getMessage(),
                'email' => $data['email'],
            ]);

            return $this->errorResponse('Something went wrong', 500);
        }
    }



    public function login(Request $request, OtpService $otpService)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $email = $this->normalizeEmail($request->email);

        $user = User::where('email', $email)->first();

        if (!$user) {
            return $this->errorResponse('You are not registered', 404);
        }

        if (!Auth::attempt(['email' => $email, 'password' => $request->password])) {
            return $this->errorResponse('Invalid email or password', 401);
        }

        if (!$user->email_verified_at) {

            $this->sendEmailOtp($email, $otpService);

            return $this->errorResponse(
                'Please verify your email. OTP has been sent again.',
                403,
                [
                    'email' => $email,
                    'otp_sent' => true,
                ]
            );
        }
$parser = Parser::create();

$result = $parser->parse($request->userAgent());

// Localhost test ke liye
$ip = app()->environment('local')
    ? '8.8.8.8'
    : $request->ip();

$response = Http::timeout(5)->get("https://ipwho.is/{$ip}");

$location = $response->json();

$user->update([
    'last_login_ip' => $request->ip(),
    'country'       => $location['country'] ?? null,
    'state'         => $location['region'] ?? null,
    'city'          => $location['city'] ?? null,
'device'   => $result->device->family,
'browser'  => $result->ua->family,
'platform' => $result->os->family,
    'last_login_at' => now(),
]);
        $token = $user->createToken(
            $user->hasRole('user') ? 'user-token' : 'admin-token'
        )->plainTextToken;
        ActivityLogService::log(
    $user,
    'login'
);

        return $this->successResponse(
            'Login successful',
            [
                'token' => $token,
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'email_verified_at' => $user->email_verified_at,
                ],
            ]
        );
    }
public function verifyResetOtp(Request $request, OtpService $otpService)
{
    $data = $request->validate([
        'email' => 'required|email',
        'otp' => 'required|string'
    ]);

    try {

        $email = $this->normalizeEmail($data['email']);

        $otp = $otpService->verify($email, $data['otp'], 'password_reset');

        if (!$otp) {
            return $this->errorResponse('Invalid or expired OTP', 422);
        }

        return $this->successResponse('OTP verified successfully');

    } catch (Throwable $e) {

        Log::error('Verify Reset OTP Error', [
            'message' => $e->getMessage(),
            'email' => $data['email'],
        ]);

        return $this->errorResponse('Something went wrong', 500);
    }
}
public function resetPassword(Request $request, OtpService $otpService)
{
    $data = $request->validate([
        'email' => 'required|email',
        'otp' => 'required|string',
        'password' => [
            'required',
            'confirmed',
            'min:8',
            'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*#?&]).+$/'
        ],
    ], [
        'password.regex' =>
            'Password must contain at least 1 uppercase letter, 1 lowercase letter, 1 number, and 1 special symbol.',
    ]);

    try {

        $email = $this->normalizeEmail($data['email']);

        $otp = $otpService->verify($email, $data['otp'], 'password_reset');

        if (!$otp) {
            return $this->errorResponse('Invalid or expired OTP', 422);
        }

        DB::beginTransaction();

        $user = User::where('email', $email)->first();

        if (!$user) {
            return $this->errorResponse('User not found', 404);
        }

        $user->update([
            'password' => Hash::make($data['password'])
        ]);

        // 🔥 Important for wallet / MLM apps
        // Revoke all active tokens after password change
$user->tokens()->where('name', 'user-token')->delete();

        $otp->delete();

        DB::commit();

        return $this->successResponse('Password reset successfully');

    } catch (Throwable $e) {

        DB::rollBack();

        Log::error('Reset Password API Error', [
            'message' => $e->getMessage(),
            'email' => $data['email'],
        ]);

        return $this->errorResponse('Something went wrong', 500);
    }
}

    public function resendEmailOtp(Request $request, OtpService $otpService)
    {
        $data = $request->validate([
            'email' => 'required|email'
        ]);

        try {
            $email = strtolower(trim($data['email']));

            $user = User::where('email', $email)->first();

            if (!$user) {
                return $this->errorResponse('User not found', 404);
            }

            if ($user->email_verified_at) {
                return $this->errorResponse('Email is already verified', 422);
            }

            $otp = $otpService->generate($email, 'email_verification');

            Mail::to($email)->send(new OtpMail($otp->code));

            return $this->successResponse(
                'OTP has been resent successfully',
                [
                    'email' => $email,
                    'expires_in' => 600,
                ]
            );

        } catch (Throwable $e) {

            Log::error('Resend OTP API Error', [
                'message' => $e->getMessage(),
                'email' => $data['email'] ?? null,
            ]);

            return $this->errorResponse('Something went wrong', 500);
        }
    }

private function sendResetOtp(string $email, OtpService $otpService): void
{
    Log::info('Password Reset OTP triggered for: ' . $email);

    $otp = $otpService->generate($email, 'password_reset');

    Mail::to($email)->send(new OtpMail($otp->code));
}
public function forgotPassword(Request $request, OtpService $otpService)
{
    $data = $request->validate([
        'email' => 'required|email'
    ]);

    try {

        $email = $this->normalizeEmail($data['email']);
$user = User::where('email', $email)->first();

if ($user) {
    $this->sendResetOtp($email, $otpService);
}

return $this->successResponse(
    'If the email exists, a reset OTP has been sent.',
    ['expires_in' => 600]
);
    

    } catch (Throwable $e) {

        Log::error('Forgot Password API Error', [
            'message' => $e->getMessage(),
            'email' => $data['email'] ?? null,
        ]);

        return $this->errorResponse('Something went wrong', 500);
    }
}

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return $this->successResponse('Logged out successfully');
    }
}
