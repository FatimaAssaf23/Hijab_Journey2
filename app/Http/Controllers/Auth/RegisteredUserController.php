<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'first_name' => ['required', 'string', 'max:100'],
            'last_name' => ['required', 'string', 'max:100'],
            'country' => ['required', 'string', 'max:100'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'country' => $request->country,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'student', // Default role for registration
        ]);

        // Always create a student record for the new user
        $studentClass = \App\Models\StudentClass::whereRaw('capacity > current_enrollment')->orderBy('class_id')->first();
        $student = \App\Models\Student::create([
            'user_id' => $user->user_id,
            'class_id' => $studentClass ? $studentClass->class_id : null,
        ]);
        if ($studentClass) {
            $studentClass->increment('current_enrollment');
            // If class is now full, update status
            if ($studentClass->current_enrollment >= $studentClass->capacity) {
                $studentClass->status = 'full';
                $studentClass->save();
            }
            // Store class info in session for dashboard display
            session(['enrolled_class_id' => $studentClass->class_id]);
        }

        event(new Registered($user));

        Auth::login($user);

        return redirect(route('student.dashboard', absolute: false));
    }
}
