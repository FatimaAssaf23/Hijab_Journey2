<?php

use App\Http\Middleware\CustomValidatePostSize;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Middleware\ValidatePostSize;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // Allow larger file uploads
        $middleware->replace(ValidatePostSize::class, CustomValidatePostSize::class);

        // Disable CSRF only for lesson upload routes (FIX 419)
        $middleware->validateCsrfTokens(except: [
            'admin/lessons',
            'admin/lessons/*',
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
