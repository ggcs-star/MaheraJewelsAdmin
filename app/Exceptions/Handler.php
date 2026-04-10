<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Exceptions\ThrottleRequestsException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Illuminate\Validation\ValidationException;

class Handler extends ExceptionHandler
{
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            Log::error('Web Exception', [
                'message' => $e->getMessage(),
                'file'    => $e->getFile(),
                'line'    => $e->getLine(),
                'url'     => request()->fullUrl(),
                'user_id' => auth()->id(),
            ]);
        });

        $this->renderable(function (ThrottleRequestsException $e, $request) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Too many attempts. Please wait and try again.'
                ], 429);
            }
            return back()->with('error', 'Too many attempts. Please wait and try again.');
        });

        $this->renderable(function (ModelNotFoundException|NotFoundHttpException $e, $request) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Record not found.'
                ], 404);
            }
            return redirect()
                ->to(url()->previous() ?? url()->current())
                ->with('error', 'Requested record not found.');
        });

        $this->renderable(function (ValidationException $e, $request) {
            if ($request->expectsJson()) {
                $errors = $e->errors();
                $firstError = collect($errors)->first();
                return response()->json([
                    'success' => false,
                    'message' => is_array($firstError) ? $firstError[0] : 'Validation error',
                    'errors' => $errors
                ], 422);
            }
            return redirect()
                ->back()
                ->withErrors($e->errors())
                ->withInput();
        });

        $this->renderable(function (Throwable $e, $request) {
            $errorMessage = $e->getMessage();
            
            if (str_contains($errorMessage, 'Duplicate entry') || 
                str_contains($errorMessage, 'already exists') ||
                str_contains($errorMessage, 'unique')) {
                
                if ($request->expectsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'This email is already registered. Please login instead.'
                    ], 409);
                }
                return redirect()
                    ->back()
                    ->withInput()
                    ->with('error', 'This email is already registered. Please login instead.');
            }
            
            // Other errors
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => $errorMessage ?: 'Something went wrong. Please try again.'
                ], 500);
            }
            
            return redirect()
                ->to(url()->previous() ?? url()->current())
                ->withInput()
                ->with('error', $errorMessage ?: 'Something went wrong. Please try again.');
        });
    }
}