<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;

class Handler extends ExceptionHandler
{
    protected $levels = [];

    protected $dontReport = [];

    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            // Logging 500 dari HttpException
            if ($e instanceof HttpExceptionInterface && $e->getStatusCode() === 500) {
                Log::error('500 Internal Server Error: ' . $e->getMessage(), [
                    'url' => request()->fullUrl(),
                    'ip' => request()->ip(),
                ]);
            }

            // Logging error berat lain (non-HttpException)
            if (!($e instanceof HttpExceptionInterface)) {
                Log::error('Fatal Exception: ' . $e->getMessage(), [
                    'url' => request()->fullUrl(),
                    'ip' => request()->ip(),
                ]);
            }
        });
    }
}
