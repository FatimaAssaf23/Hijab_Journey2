<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Exceptions\PostTooLargeException;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CustomValidatePostSize
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Allow up to 100MB for file uploads
        $max = 100 * 1024 * 1024; // 100MB in bytes
        $contentLength = $request->server('CONTENT_LENGTH');

        if ($contentLength && $contentLength > $max) {
            throw new PostTooLargeException('The POST data is too large. Maximum allowed size is 100MB.');
        }

        return $next($request);
    }
}
