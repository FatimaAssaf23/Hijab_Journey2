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
        @stack('styles')
        
        <!-- 404 Error Debugging Script -->
        <script>
            // Log all 404 errors to console
            window.addEventListener('error', function(e) {
                if (e.target && e.target.tagName) {
                    const tag = e.target.tagName.toLowerCase();
                    const src = e.target.src || e.target.href;
                    if (src && (tag === 'img' || tag === 'script' || tag === 'link')) {
                        console.error('404 Error - Failed to load resource:', {
                            type: tag,
                            url: src,
                            element: e.target
                        });
                    }
                }
            }, true);
            
            // Monitor fetch requests for 404s
            const originalFetch = window.fetch;
            window.fetch = function(...args) {
                return originalFetch.apply(this, args)
                    .then(response => {
                        if (response.status === 404) {
                            console.error('404 Error - API/Resource not found:', {
                                url: args[0],
                                status: response.status,
                                statusText: response.statusText
                            });
                        }
                        return response;
                    })
                    .catch(error => {
                        console.error('Fetch Error:', error);
                        throw error;
                    });
            };
        </script>
    </head>
    <body class="font-sans antialiased">
        @php
            $isTeacherDashboard = request()->routeIs('teacher.dashboard');
        @endphp
        <div class="{{ $isTeacherDashboard ? 'h-screen flex flex-col overflow-hidden' : 'min-h-screen bg-gray-100 dark:bg-gray-900' }}">
            @include('layouts.navigation')

            <!-- Page Heading -->
            @isset($header)
                <header class="bg-white dark:bg-gray-800 shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <!-- Page Content -->
            <main class="{{ $isTeacherDashboard ? 'flex-1 overflow-hidden' : 'py-4' }}">
                @yield('content')
            </main>
        </div>
        <script>
            // Go Back function that checks if there's history, otherwise redirects
            function goBackOrRedirect(fallbackUrl) {
                // Get current origin and full URL for comparison
                const currentOrigin = window.location.origin;
                const currentUrl = window.location.href;
                
                // Check if there's a referrer from the same origin and it's different from current page
                if (document.referrer && 
                    document.referrer.startsWith(currentOrigin) && 
                    document.referrer !== currentUrl) {
                    // We have a valid referrer from the same origin, go back
                    window.history.back();
                } else {
                    // No valid referrer or same page, redirect to fallback URL
                    window.location.href = fallbackUrl;
                }
            }
        </script>
        @stack('scripts')
    </body>
</html>
