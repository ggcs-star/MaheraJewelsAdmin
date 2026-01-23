<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;
use Illuminate\Http\Exceptions\ThrottleRequestsException;


class Handler extends ExceptionHandler
{
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    public function register(): void
{
    $this->reportable(function (Throwable $e) {
    });

    $this->renderable(function (Throwable $e, $request) {
        if ($e instanceof ThrottleRequestsException) {
            return back()->with(
                'error',
                'Too many attempts. Please wait a few minutes and try again.'
            );
        }
    });
}

}
