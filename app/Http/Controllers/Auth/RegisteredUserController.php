<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\NewUserRegistrationMail;
use App\Models\AdminSetting;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
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
            'date_of_birth' => [
                'required',
                'date',
                'before_or_equal:' . now()->subYears(8)->format('Y-m-d'), // At least 8 years old
                'after_or_equal:' . now()->subYears(12)->format('Y-m-d'), // At most 12 years old
            ],
            'password' => [
                'required',
                'confirmed',
                'min:8',
                'regex:/[A-Z]/', // at least one uppercase
                'regex:/[0-9]/', // at least one number
                'regex:/[!@#$%^&*()_+\-=\[\]{};\'":\\|,.<>\/?]/', // at least one symbol
            ],
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
        // Try to find a class with available capacity (active status preferred)
        try {
            $studentClass = \App\Models\StudentClass::where('status', 'active')
                ->whereColumn('current_enrollment', '<', 'capacity')
                ->orderBy('class_id')
                ->first();
            
            // If no active class found, try any class with available capacity
            if (!$studentClass) {
                $studentClass = \App\Models\StudentClass::whereColumn('current_enrollment', '<', 'capacity')
                    ->orderBy('class_id')
                    ->first();
            }
            
            // If still no class found, try to find any active class regardless of capacity
            // (in case capacity tracking is off)
            if (!$studentClass) {
                $studentClass = \App\Models\StudentClass::where('status', 'active')
                    ->orderBy('class_id')
                    ->first();
            }
            
            // If still no class, try any class
            if (!$studentClass) {
                $studentClass = \App\Models\StudentClass::orderBy('class_id')->first();
            }
            
            $student = \App\Models\Student::create([
                'user_id' => $user->user_id,
                'class_id' => $studentClass ? $studentClass->class_id : null,
                'date_of_birth' => $request->date_of_birth,
                'is_read' => false, // Mark as unread for admin notification
            ]);
            
            if ($studentClass) {
                // Increment enrollment only if we're tracking capacity
                if ($studentClass->current_enrollment < $studentClass->capacity) {
                    $studentClass->increment('current_enrollment');
                }
                
                // Refresh to get updated enrollment count
                $studentClass->refresh();
                
                // If class is now full, update status
                if ($studentClass->current_enrollment >= $studentClass->capacity) {
                    $studentClass->status = 'full';
                    $studentClass->save();
                } elseif ($studentClass->status === 'full' && $studentClass->current_enrollment < $studentClass->capacity) {
                    // If status was 'full' but now has space, set back to 'active'
                    $studentClass->status = 'active';
                    $studentClass->save();
                }
                
                // Store class info in session for dashboard display
                session(['enrolled_class_id' => $studentClass->class_id]);
                
                // Refresh student to ensure we have the latest data
                $student->refresh();
                
                \Log::info('Student enrolled in class', [
                    'user_id' => $user->user_id,
                    'student_id' => $student->student_id,
                    'class_id' => $studentClass->class_id,
                    'class_name' => $studentClass->class_name ?? 'N/A'
                ]);
                
                // Initialize games for all visible lessons in the class
                // This ensures new students can see all games that were added before their registration
                try {
                    $progressController = new \App\Http\Controllers\StudentProgressController();
                    $progressController->initializeGamesForNewStudent($student->student_id);
                    \Log::info('Games initialized for new student', [
                        'student_id' => $student->student_id,
                        'class_id' => $studentClass->class_id
                    ]);
                } catch (\Exception $e) {
                    // Log error but don't fail registration if game initialization fails
                    // Games will be auto-initialized when student views lessons (fallback mechanism)
                    \Log::error('Failed to initialize games for new student: ' . $e->getMessage(), [
                        'student_id' => $student->student_id,
                        'class_id' => $studentClass->class_id,
                        'trace' => $e->getTraceAsString()
                    ]);
                }
            } else {
                \Log::warning('Student registered but no class available for enrollment', [
                    'user_id' => $user->user_id,
                    'student_id' => $student->student_id
                ]);
            }
        } catch (\Exception $e) {
            // Log the error but still create the student record
            \Log::error('Error during student enrollment: ' . $e->getMessage(), [
                'user_id' => $user->user_id,
                'trace' => $e->getTraceAsString()
            ]);
            
            // Create student without class assignment
            $student = \App\Models\Student::create([
                'user_id' => $user->user_id,
                'class_id' => null,
                'date_of_birth' => $request->date_of_birth,
                'is_read' => false,
            ]);
        }

        // Send email notification to admin
        $notifyAdmin = AdminSetting::get('notify_admin_on_new_registrations', false);
        $emailNotificationsEnabled = AdminSetting::get('email_notifications_enabled', true);
        
        // Primary admin email (always send to this address)
        $primaryAdminEmail = '10121317@mu.edu.lb';
        
        // Send notification if email notifications are enabled
        if ($emailNotificationsEnabled) {
            try {
                $emailsSent = 0;
                
                // Always send to primary admin email
                try {
                    Mail::to($primaryAdminEmail)->send(new NewUserRegistrationMail($user));
                    $emailsSent++;
                    \Log::info('New user registration notification sent to primary admin', [
                        'admin_email' => $primaryAdminEmail,
                        'new_user_id' => $user->user_id,
                        'new_user_email' => $user->email
                    ]);
                } catch (\Exception $e) {
                    \Log::error('Failed to send new user registration notification to primary admin: ' . $e->getMessage(), [
                        'admin_email' => $primaryAdminEmail,
                        'error' => $e->getTraceAsString()
                    ]);
                }
                
                // Also send to all admin users if the setting is enabled
                if ($notifyAdmin) {
                    $adminUsers = User::where('role', 'admin')->get();
                    
                    foreach ($adminUsers as $admin) {
                        // Skip if this admin is the primary admin email (already sent)
                        if (strtolower($admin->email) === strtolower($primaryAdminEmail)) {
                            continue;
                        }
                        
                        try {
                            Mail::to($admin->email)->send(new NewUserRegistrationMail($user));
                            $emailsSent++;
                            \Log::info('New user registration notification sent to admin user', [
                                'admin_email' => $admin->email,
                                'admin_id' => $admin->user_id,
                                'new_user_id' => $user->user_id
                            ]);
                        } catch (\Exception $e) {
                            \Log::error('Failed to send new user registration notification to admin user: ' . $e->getMessage(), [
                                'admin_email' => $admin->email,
                                'admin_id' => $admin->user_id
                            ]);
                        }
                    }
                }
                
                if ($emailsSent === 0) {
                    \Log::warning('No new user registration notifications were sent', [
                        'email_notifications_enabled' => $emailNotificationsEnabled,
                        'notify_admin_setting' => $notifyAdmin,
                        'new_user_id' => $user->user_id
                    ]);
                }
            } catch (\Exception $e) {
                // Log error but don't fail registration if email fails
                \Log::error('Failed to send new user registration notification: ' . $e->getMessage(), [
                    'new_user_id' => $user->user_id,
                    'trace' => $e->getTraceAsString()
                ]);
            }
        } else {
            \Log::warning('Email notifications are disabled, skipping new user registration notification', [
                'new_user_id' => $user->user_id,
                'email_notifications_enabled' => false
            ]);
        }

        event(new Registered($user));

        Auth::login($user);

        return redirect(route('student.dashboard', absolute: false));
    }
}
