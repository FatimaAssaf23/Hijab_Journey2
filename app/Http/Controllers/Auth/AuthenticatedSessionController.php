<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        // Redirect admin to /admin, teacher to /teacher/dashboard, others to dashboard
        $user = Auth::user();
        if ($user) {
            if ($user->role === 'admin') {
                return redirect()->intended('/admin');
            }
            if ($user->role === 'teacher') {
                return redirect()->intended(route('teacher.dashboard', absolute: false));
            }
        }
        return redirect()->intended(route('student.dashboard', absolute: false));
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        try {
            // Logout the user - this should work for all authenticated users
            Auth::guard('web')->logout();

            // Invalidate the session
            $request->session()->invalidate();

            // Regenerate CSRF token
            $request->session()->regenerateToken();

            return redirect('/')->with('status', 'You have been logged out successfully.');
        } catch (\Exception $e) {
            // If logout fails, still try to clear session and redirect
            \Log::error('Logout error: ' . $e->getMessage());
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            return redirect('/')->with('status', 'You have been logged out.');
        }
    }
}
