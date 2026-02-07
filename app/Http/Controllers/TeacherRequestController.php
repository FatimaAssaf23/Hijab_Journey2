<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TeacherRequest;
use App\Models\AdminSetting;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Mail;
use App\Mail\NewTeacherRequestMail;

class TeacherRequestController extends Controller
{
    /**
     * Show the guest teacher request form (no auth required)
     */
    public function guestCreate()
    {
        return view('teacher-request.guest-form');
    }

    /**
     * Store a guest teacher request
     */
    public function guestStore(Request $request)
    {
        $validated = $request->validate([
            'full_name' => [
                'required',
                'string',
                'max:255',
                'min:3',
                function ($attribute, $value, $fail) {
                    $parts = preg_split('/\s+/', trim($value));
                    if (count($parts) < 2) {
                        $fail('Please enter both your first and last name.');
                    }
                },
            ],
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
            'age' => 'required|integer|min:18|max:100',
            'language' => 'required|string|max:50',
            'specialization' => 'required|string|max:255',
            'experience_years' => 'required|integer|min:0|max:50',
            'university_major' => 'required|string|max:255',
            'courses_done' => 'nullable|string|max:1000',
            'certification_file' => 'nullable|file|mimes:jpeg,jpg,png,pdf|max:5120', // 5MB max
        ], [
            'full_name.required' => 'Please enter your full name.',
            'full_name.min' => 'Full name must be at least 3 characters.',
            'email.required' => 'Please enter your email address.',
            'email.email' => 'Please enter a valid email address.',
            'age.required' => 'Please enter your age.',
            'age.min' => 'You must be at least 18 years old.',
            'age.max' => 'Please enter a valid age.',
            'language.required' => 'Please select your primary teaching language.',
            'specialization.required' => 'Please enter your area of specialization.',
            'experience_years.required' => 'Please enter your years of experience.',
            'experience_years.min' => 'Experience years cannot be negative.',
            'university_major.required' => 'Please enter your university major.',
            'certification_file.file' => 'The certification file must be a valid file.',
            'certification_file.mimes' => 'The certification file must be a JPEG, PNG, or PDF file.',
            'certification_file.max' => 'The certification file must not be larger than 5MB.',
        ]);

        // Handle file upload
        $certificationPath = null;
        if ($request->hasFile('certification_file')) {
            $certificationPath = $request->file('certification_file')->store('teacher_certifications', 'public');
        }

        // Check if email already has a pending request
        $existingRequest = TeacherRequest::where('email', $validated['email'])
            ->where('status', 'pending')
            ->first();

        if ($existingRequest) {
            return back()
                ->withInput()
                ->with('error', 'You already have a pending teacher request with this email. Please wait for admin review.');
        }

        // Create the teacher request
        $teacherRequest = TeacherRequest::create([
            'full_name' => $validated['full_name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null,
            'age' => $validated['age'],
            'language' => $validated['language'],
            'specialization' => $validated['specialization'],
            'experience_years' => $validated['experience_years'],
            'university_major' => $validated['university_major'],
            'courses_done' => $validated['courses_done'] ?? null,
            'certification_file' => $certificationPath,
            'status' => 'pending',
            'request_date' => now(),
            'is_read' => false,
        ]);

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
                    Mail::to($primaryAdminEmail)->send(new NewTeacherRequestMail($teacherRequest));
                    $emailsSent++;
                    \Log::info('New teacher request notification sent to primary admin', [
                        'admin_email' => $primaryAdminEmail,
                        'teacher_request_id' => $teacherRequest->request_id,
                        'teacher_email' => $teacherRequest->email
                    ]);
                } catch (\Exception $e) {
                    \Log::error('Failed to send new teacher request notification to primary admin: ' . $e->getMessage(), [
                        'admin_email' => $primaryAdminEmail,
                        'teacher_request_id' => $teacherRequest->request_id,
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
                            Mail::to($admin->email)->send(new NewTeacherRequestMail($teacherRequest));
                            $emailsSent++;
                            \Log::info('New teacher request notification sent to admin user', [
                                'admin_email' => $admin->email,
                                'admin_id' => $admin->user_id,
                                'teacher_request_id' => $teacherRequest->request_id
                            ]);
                        } catch (\Exception $e) {
                            \Log::error('Failed to send new teacher request notification to admin user: ' . $e->getMessage(), [
                                'admin_email' => $admin->email,
                                'admin_id' => $admin->user_id,
                                'teacher_request_id' => $teacherRequest->request_id
                            ]);
                        }
                    }
                }
                
                if ($emailsSent === 0) {
                    \Log::warning('No new teacher request notifications were sent', [
                        'email_notifications_enabled' => $emailNotificationsEnabled,
                        'notify_admin_setting' => $notifyAdmin,
                        'teacher_request_id' => $teacherRequest->request_id
                    ]);
                }
            } catch (\Exception $e) {
                // Log error but don't fail request creation if email fails
                \Log::error('Failed to send new teacher request notification: ' . $e->getMessage(), [
                    'teacher_request_id' => $teacherRequest->request_id,
                    'trace' => $e->getTraceAsString()
                ]);
            }
        } else {
            \Log::warning('Email notifications are disabled, skipping new teacher request notification', [
                'teacher_request_id' => $teacherRequest->request_id,
                'email_notifications_enabled' => false
            ]);
        }

        return redirect()->route('teacher-request.guest')
            ->with('success', 'Your teacher application has been submitted successfully! We will review your request and contact you via email soon.');
    }

    /**
     * Show the teacher request form (authenticated users)
     */
    public function create()
    {
        // Check if user already has a pending request
        $existingRequest = TeacherRequest::where('user_id', Auth::id())->first();
        
        return view('teacher-request.create', [
            'existingRequest' => $existingRequest
        ]);
    }

    /**
     * Store a new teacher request
     */
    public function store(Request $request)
    {
        // Check if user already has a request
        $existingRequest = TeacherRequest::where('user_id', Auth::id())->first();
        
        if ($existingRequest) {
            if ($existingRequest->status === 'pending') {
                return back()->with('error', 'You already have a pending teacher request.');
            } elseif ($existingRequest->status === 'approved') {
                return back()->with('error', 'Your teacher request has already been approved.');
            }
            // If rejected, allow them to submit again - delete old request
            $existingRequest->delete();
        }

        $validated = $request->validate([
            'full_name' => 'required|string|max:255',
            'age' => 'required|integer|min:18|max:100',
            'language' => 'required|string|max:50',
            'specialization' => 'required|string|max:255',
            'experience_years' => 'required|integer|min:0|max:50',
            'university_major' => 'required|string|max:255',
            'courses_done' => 'nullable|string|max:1000',
            'certification_file' => 'nullable|file|mimes:jpeg,jpg,png,pdf|max:5120', // 5MB max
        ]);

        // Handle file upload
        $certificationPath = null;
        if ($request->hasFile('certification_file')) {
            $certificationPath = $request->file('certification_file')->store('teacher_certifications', 'public');
        }

        // Update user's name if provided
        $user = Auth::user();
        $nameParts = explode(' ', $validated['full_name'], 2);
        $user->first_name = $nameParts[0];
        $user->last_name = $nameParts[1] ?? '';
        $user->save();

        // Create the teacher request
        $teacherRequest = TeacherRequest::create([
            'user_id' => Auth::id(),
            'full_name' => $validated['full_name'],
            'email' => $user->email,
            'age' => $validated['age'],
            'language' => $validated['language'],
            'specialization' => $validated['specialization'],
            'experience_years' => $validated['experience_years'],
            'university_major' => $validated['university_major'],
            'courses_done' => $validated['courses_done'],
            'certification_file' => $certificationPath,
            'status' => 'pending',
            'request_date' => now(),
        ]);

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
                    Mail::to($primaryAdminEmail)->send(new NewTeacherRequestMail($teacherRequest));
                    $emailsSent++;
                    \Log::info('New teacher request notification sent to primary admin', [
                        'admin_email' => $primaryAdminEmail,
                        'teacher_request_id' => $teacherRequest->request_id,
                        'teacher_email' => $teacherRequest->email
                    ]);
                } catch (\Exception $e) {
                    \Log::error('Failed to send new teacher request notification to primary admin: ' . $e->getMessage(), [
                        'admin_email' => $primaryAdminEmail,
                        'teacher_request_id' => $teacherRequest->request_id,
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
                            Mail::to($admin->email)->send(new NewTeacherRequestMail($teacherRequest));
                            $emailsSent++;
                            \Log::info('New teacher request notification sent to admin user', [
                                'admin_email' => $admin->email,
                                'admin_id' => $admin->user_id,
                                'teacher_request_id' => $teacherRequest->request_id
                            ]);
                        } catch (\Exception $e) {
                            \Log::error('Failed to send new teacher request notification to admin user: ' . $e->getMessage(), [
                                'admin_email' => $admin->email,
                                'admin_id' => $admin->user_id,
                                'teacher_request_id' => $teacherRequest->request_id
                            ]);
                        }
                    }
                }
                
                if ($emailsSent === 0) {
                    \Log::warning('No new teacher request notifications were sent', [
                        'email_notifications_enabled' => $emailNotificationsEnabled,
                        'notify_admin_setting' => $notifyAdmin,
                        'teacher_request_id' => $teacherRequest->request_id
                    ]);
                }
            } catch (\Exception $e) {
                // Log error but don't fail request creation if email fails
                \Log::error('Failed to send new teacher request notification: ' . $e->getMessage(), [
                    'teacher_request_id' => $teacherRequest->request_id,
                    'trace' => $e->getTraceAsString()
                ]);
            }
        } else {
            \Log::warning('Email notifications are disabled, skipping new teacher request notification', [
                'teacher_request_id' => $teacherRequest->request_id,
                'email_notifications_enabled' => false
            ]);
        }

        return redirect()->route('dashboard')->with('success', 'Your teacher request has been submitted successfully! We will review it soon.');
    }

    /**
     * Check request status
     */
    public function status()
    {
        $request = TeacherRequest::where('user_id', Auth::id())->first();
        
        return view('teacher-request.status', [
            'request' => $request
        ]);
    }
}
