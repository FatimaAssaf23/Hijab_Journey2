<?php
namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->registerPolicies();

        Gate::define('viewEmergencyRequests', function ($user) {
            return in_array($user->role, ['admin', 'teacher']);
        });

        Gate::define('isTeacher', function ($user) {
            \Log::info('Gate isTeacher check', ['user_id' => $user->user_id, 'role' => $user->role]);
            return $user->role === 'teacher';
        });
        Gate::define('isAdmin', function ($user) {
            return $user->role === 'admin';
        });
    }
}
