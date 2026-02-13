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
            return back()->with('error', 'Too many attempts. Please wait and try again.');
        });
        $this->renderable(function (ModelNotFoundException|NotFoundHttpException $e, $request) {

            return redirect()
                ->to(url()->previous() ?? url()->current())
                ->with('error', 'Requested record not found.');
        });
        $this->renderable(function (ValidationException $e, $request) {
            return null;
        });

        $this->renderable(function (Throwable $e, $request) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Something went wrong.'
                ], 500);
            }

            return redirect()
                ->to(url()->previous() ?? url()->current())
                ->withInput()
                ->with('error', 'Something went wrong. Please try again.');
        });
    }
}
