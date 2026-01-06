<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        
        <style>
            /* Fallback styles for auth pages */
            body { font-family: 'Figtree', sans-serif; }
            .auth-container {
                min-height: 100vh;
                display: flex;
                flex-direction: column;
                align-items: center;
                justify-content: center;
                padding: 24px 0;
                background-color: #FFF6F9; /* very light pink */
            }
            .auth-card {
                width: 100%;
                max-width: 28rem;
                margin-top: 24px;
                padding: 24px;
                background: white;
                box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
                overflow: hidden;
                border-radius: 8px;
            }
            .auth-input {
                display: block;
                width: 100%;
                margin-top: 4px;
                padding: 8px 12px;
                border: 1px solid #d1d5db;
                border-radius: 6px;
                font-size: 14px;
            }
            .auth-input:focus {
                outline: none;
                border-color: #6366f1;
                box-shadow: 0 0 0 2px rgba(99, 102, 241, 0.2);
            }
            .auth-label {
                display: block;
                font-size: 14px;
                font-weight: 500;
                color: #374151;
            }
            .auth-btn {
                display: inline-flex;
                align-items: center;
                padding: 8px 16px;
                background-color: #1f2937;
                border: none;
                border-radius: 6px;
                font-weight: 600;
                font-size: 12px;
                color: white;
                text-transform: uppercase;
                letter-spacing: 0.05em;
                cursor: pointer;
                transition: background-color 0.15s;
            }
            .auth-btn:hover {
                background-color: #374151;
            }
            .auth-link {
                font-size: 14px;
                color: #4b5563;
                text-decoration: underline;
            }
            .auth-link:hover {
                color: #1f2937;
            }
        </style>
    </head>
    <body class="font-sans text-gray-900 antialiased">
        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gray-100 dark:bg-gray-900 auth-container">
            <!-- Logo removed -->

            <div class="w-full sm:max-w-md mt-6 px-6 py-4 bg-white dark:bg-gray-800 shadow-md overflow-hidden sm:rounded-lg auth-card">
                {{ $slot }}
            </div>
        </div>
    </body>
</html>
