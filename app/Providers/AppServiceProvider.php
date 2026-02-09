<?php

namespace App\Providers;

use App\Models\Meeting;
use App\Observers\MeetingObserver;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\URL;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Schema::defaultStringLength(191);
        
        // Register Meeting Observer for automatic enrollment sync
        Meeting::observe(MeetingObserver::class);
        
        // Force HTTPS for asset URLs in production when APP_URL uses HTTPS
        if (config('app.env') === 'production' && str_starts_with(config('app.url'), 'https://')) {
            URL::forceScheme('https');
        }
        
        // Also force HTTPS if the current request is secure (HTTPS)
        // Check if request is available to avoid errors during console commands
        if ($this->app->runningInConsole() === false) {
            $request = request();
            if ($request && ($request->secure() || $request->header('X-Forwarded-Proto') === 'https')) {
                URL::forceScheme('https');
            }
        }
    }
}
