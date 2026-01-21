<?php
namespace App\Http\Controllers;

use App\Models\AdminProfile;
use App\Models\AdminSetting;
use Illuminate\Http\Request;
use App\Models\TeacherRequest;
use App\Models\Lesson;
use App\Models\StudentClass;
use App\Models\TeacherSubstitution;
use App\Models\Student;
use App\Models\User;
use App\Models\Level;
use App\Models\Assignment;
use App\Models\Comment;
use App\Models\Grade;
use App\Models\Quiz;
use App\Models\QuizAttempt;
use App\Models\Teacher;
use App\Models\Game;
use App\Models\WordSearchGame;
use App\Models\MatchingPairsGame;
// ClockGame model removed - clock games have been dropped
use App\Models\StudentGameProgress;
use App\Models\ClassLessonVisibility;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Mail\TeacherRejectedMail;
use App\Mail\TeacherApprovedMail;

class AdminController extends Controller
{
        /**
         * Approve an emergency absence request
         *
         * @param int $id
         * @return \Illuminate\Http\RedirectResponse
         */
        public function approveEmergencyRequest($id)
        {
            $request = \App\Models\EmergencyRequest::find($id);
            if (!$request) {
                return redirect()->route('admin.emergency')->with('error', 'Emergency request not found.');
            }
            $request->status = 'approved';
            $request->save();
            return redirect()->route('admin.emergency')->with('success', 'Emergency request approved.');
        }

        /**
         * Reject an emergency absence request
         *
         * @param \Illuminate\Http\Request $request
         * @param int $id
         * @return \Illuminate\Http\RedirectResponse
         */
        public function rejectEmergencyRequest(\Illuminate\Http\Request $httpRequest, $id)
        {
            $request = \App\Models\EmergencyRequest::find($id);
            if (!$request) {
                return redirect()->route('admin.emergency')->with('error', 'Emergency request not found.');
            }
            $httpRequest->validate([
                'rejection_reason' => 'required|string|max:1000',
            ]);
            $request->status = 'rejected';
            $request->rejection_reason = $httpRequest->rejection_reason;
            $request->save();
            return redirect()->route('admin.emergency')->with('success', 'Emergency request rejected.');
        }

        // ...existing code...
    /**
     * Show the admin profile page.
     */
    public function profile()
    {
        $admin = auth()->user();
        if (!$admin || $admin->role !== 'admin') {
            abort(403, 'Unauthorized: Only admins can access this page.');
        }
        $adminProfile = AdminProfile::firstOrCreate(['user_id' => $admin->user_id]);
        return view('admin.profile', compact('admin', 'adminProfile'));
    }

    /**
     * Handle admin profile update (including image upload).
     */
    public function updateProfile(Request $request)
    {
        $admin = auth()->user();
        $request->validate([
            'profile_photo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'bio' => 'nullable|string|max:1000',
        ]);

        $adminProfile = AdminProfile::firstOrCreate(['user_id' => $admin->user_id]);

        if ($request->hasFile('profile_photo')) {
            $file = $request->file('profile_photo');
            $path = $file->store('admin-profile-photos', 'public');
            // Delete old photo if exists
            if ($adminProfile->profile_photo_path) {
                \Storage::disk('public')->delete($adminProfile->profile_photo_path);
            }
            $adminProfile->profile_photo_path = $path;
        }
        if ($request->filled('bio')) {
            $adminProfile->bio = $request->bio;
        }
        $adminProfile->save();
        return redirect()->route('admin.profile')->with('success', 'Profile updated successfully.');
    }

    /**
     * Mark new student registrations as read.
     */
    public function markStudentsAsRead()
    {
        $admin = auth()->user();
        if (!$admin || $admin->role !== 'admin') {
            abort(403, 'Unauthorized: Only admins can access this page.');
        }
        
        Student::where('is_read', false)->update(['is_read' => true]);
        return redirect()->route('admin.dashboard')->with('success', 'New student registrations marked as read.');
    }

    /**
     * Show the admin settings page.
     */
    public function settings()
    {
        $admin = auth()->user();
        if (!$admin || $admin->role !== 'admin') {
            abort(403, 'Unauthorized: Only admins can access this page.');
        }

        $settings = AdminSetting::getByCategory();
        return view('admin.settings', compact('admin', 'settings'));
    }

    /**
     * Handle admin settings update.
     */
    public function updateSettings(Request $request)
    {
        $admin = auth()->user();
        if (!$admin || $admin->role !== 'admin') {
            abort(403, 'Unauthorized: Only admins can access this page.');
        }

        // Get all settings from database
        $allSettings = AdminSetting::all();
        
        // Update each setting that was submitted
        foreach ($allSettings as $setting) {
            $key = $setting->setting_key;
            
            // Check if this setting was submitted
            if ($request->has($key)) {
                $value = $request->input($key);
                
                // Handle boolean checkboxes (they don't send value if unchecked)
                if ($setting->setting_type === 'boolean') {
                    $value = $request->has($key) ? '1' : '0';
                }
                
                $setting->setting_value = (string) $value;
                $setting->save();
            } else {
                // For checkboxes that weren't checked, set to 0
                if ($setting->setting_type === 'boolean') {
                    $setting->setting_value = '0';
                    $setting->save();
                }
            }
        }

        return redirect()->route('admin.settings')->with('success', 'Settings updated successfully.');
    }

    // Class color palette (matches the design)
    private static $classColors = [
        // Updated pink palette from user image
        'pink-dark' => 'from-[#E88A93] to-[#F08080]',
        'pink-light' => 'from-[#F2C4C4] to-[#F4B8B8]',
        'cream' => 'from-[#EDE4D8] to-[#E5D9C9]',
        // Custom turquoise from user image
        'turquoise' => 'from-[#3DD9C4] to-[#2ED3BC]',
        'teal' => 'from-[#2DBCB0] to-[#25A99E]',
        'tan' => 'from-[#CCB083] to-[#C4A677]',
        'beige' => 'from-[#E4CFB3] to-[#DCC5A5]',
        'ivory' => 'from-[#F4F4DD] to-[#EEEED0]',
        'blush' => 'from-[#F8C5C8] to-[#F5B5B9]',
        'coral' => 'from-[#FC8EAC] to-[#FA7A9C]',
        'rose' => 'from-[#EC769A] to-[#E8628A]',
    ];

    private static $levelColors = [
        1 => 'from-emerald-400 to-teal-400',
        2 => 'from-violet-400 to-purple-400',
        3 => 'from-orange-400 to-pink-400',
        4 => 'from-sky-400 to-cyan-400',
        5 => 'from-rose-400 to-red-400',
        6 => 'from-amber-400 to-yellow-400',
    ];

    /**
     * Generate a secure random password
     * 
     * @param int $length The length of the password (default 10, minimum 8)
     * @return string A secure password with uppercase, lowercase, numbers, and special characters
     */
    private function generateSecurePassword($length = 10)
    {
        // Ensure minimum length of 8 for security, but respect maximum of 10
        $length = max(8, min(10, $length));
        
        // Define character sets
        $uppercase = 'ABCDEFGHJKLMNPQRSTUVWXYZ'; // Excluding I and O to avoid confusion
        $lowercase = 'abcdefghjkmnpqrstuvwxyz'; // Excluding i, l, o to avoid confusion
        $numbers = '23456789'; // Excluding 0, 1 to avoid confusion with O, I
        $special = '!@#$%^&*()_+-=[]{}|;:,.<>?'; // Common special characters
        
        // Ensure we have at least one character from each set
        $password = '';
        $password .= $uppercase[random_int(0, strlen($uppercase) - 1)];
        $password .= $lowercase[random_int(0, strlen($lowercase) - 1)];
        $password .= $numbers[random_int(0, strlen($numbers) - 1)];
        $password .= $special[random_int(0, strlen($special) - 1)];
        
        // Combine all character sets
        $allChars = $uppercase . $lowercase . $numbers . $special;
        
        // Fill the rest randomly up to the desired length
        for ($i = strlen($password); $i < $length; $i++) {
            $password .= $allChars[random_int(0, strlen($allChars) - 1)];
        }
        
        // Shuffle the password to randomize character positions
        return str_shuffle($password);
    }


    /**
     * Get teachers from database
     */
    private function getTeachersFromDb()
    {
        return User::where('role', 'teacher')
            ->get()
            ->map(function ($teacher) {
                return [
                    'id' => $teacher->user_id,
                    'name' => $teacher->first_name . ' ' . $teacher->last_name,
                    'email' => $teacher->email,
                    'subject' => $teacher->bio ?? 'General',
                ];
            })->toArray();
    }


    /**
     * Get classes from database
     */
    private function getClassesFromDb()
    {
        return StudentClass::with(['teacher', 'students.user'])->get()->map(function ($class) {
            $colorKey = $class->color;
            $colorGradient = self::$classColors[$colorKey] ?? null;
            // Get students for this class, using related User model for name/email
            // Filter out teachers from the students list
            $studentsList = $class->students->filter(function ($student) use ($class) {
                $user = $student->user;
                // Exclude if user is a teacher or if user_id matches the class teacher_id
                return $user && $user->role !== 'teacher' && $user->user_id !== $class->teacher_id;
            })->map(function ($student) {
                $user = $student->user;
                return [
                    'id' => $student->student_id,
                    'name' => $user ? ($user->first_name . ' ' . $user->last_name) : 'Unknown',
                    'email' => $user ? $user->email : '',
                ];
            })->toArray();
                return [
                    'id' => $class->class_id,
                    'name' => $class->class_name,
                    'teacherId' => $class->teacher_id,
                    'teacherName' => $class->teacher ? $class->teacher->first_name . ' ' . $class->teacher->last_name : 'Unassigned',
                    'grade' => 1,
                    'students' => $class->current_enrollment,
                    'capacity' => $class->capacity,
                    'status' => $class->status,
                    'color' => $colorKey,
                    'color_gradient' => $colorGradient,
                    'studentsList' => $studentsList,
                    'description' => $class->description,
                ];
        })->toArray();
    }

    /**
     * Get teacher requests from database
     */
    private function getTeacherRequestsFromDb($status = null)
    {
        $query = TeacherRequest::with('user');
        
        if ($status) {
            $query->where('status', $status);
        }
        
        return $query->orderBy('request_date', 'desc')->get()->map(function ($request) {
            // Always provide consistent fields for all request statuses
            $name = $request->full_name ?? ($request->user ? trim($request->user->first_name . ' ' . $request->user->last_name) : 'Unknown');
            $email = $request->email ?? ($request->user ? $request->user->email : '');
            $subject = $request->specialization ?? ($request->user ? ($request->user->bio ?? 'General') : 'General');
            return [
                'id' => $request->request_id,
                'name' => $name,
                'email' => $email,
                'phone' => $request->phone,
                'age' => $request->age,
                'subject' => $subject,
                'specialization' => $request->specialization ?? 'General',
                'experience' => ($request->experience_years !== null ? $request->experience_years . ' years' : 'N/A'),
                'experience_years' => $request->experience_years,
                'status' => $request->status,
                'appliedAt' => $request->request_date ? $request->request_date->format('Y-m-d') : '',
                'language' => $request->language,
                'university_major' => $request->university_major,
                'courses_done' => $request->courses_done,
                'rejection_reason' => $request->rejection_reason,
                'is_guest' => is_null($request->user_id),
                'is_read' => $request->is_read,
            ];
        })->toArray();
    }

    /**
     * Get active emergency cases (substitutions) from database
     */
    private function getEmergencyCasesFromDb()
    {
        return TeacherSubstitution::with(['originalTeacher', 'substituteTeacher', 'studentClass'])
            ->where('status', 'active')
            ->orWhere(function($query) {
                $query->where('status', 'pending')
                      ->where('end_date', '>=', now());
            })
            ->get()
            ->groupBy('original_teacher_id')
            ->map(function ($substitutions, $teacherId) {
                $first = $substitutions->first();
                return [
                    'id' => $first->substitution_id,
                    'teacher' => $first->originalTeacher ? $first->originalTeacher->first_name . ' ' . $first->originalTeacher->last_name : 'Unknown',
                    'teacher_id' => $teacherId,
                    'classes' => $substitutions->map(fn($s) => $s->studentClass ? $s->studentClass->class_name : 'Unknown')->toArray(),
                    'reason' => $first->reason,
                    'date' => $first->start_date ? $first->start_date->format('Y-m-d') : '',
                    'end_date' => $first->end_date ? $first->end_date->format('Y-m-d') : '',
                    'substitute' => $first->substituteTeacher ? $first->substituteTeacher->first_name . ' ' . $first->substituteTeacher->last_name : null,
                    'status' => $first->status,
                ];
            })->values()->toArray();
    }

    // Dashboard
    public function index()
    {
        // Get unread teacher requests for notifications
        $unreadRequests = TeacherRequest::where('is_read', false)
            ->where('status', 'pending')
            ->orderBy('created_at', 'desc')
            ->get();

        // Get unread new student registrations
        $unreadNewStudents = Student::with('user')
            ->where('is_read', false)
            ->whereHas('user', function($query) {
                $query->where('role', 'student');
            })
            ->orderBy('created_at', 'desc')
            ->get();

        // Calculate class status counts
        $fullClassesCount = StudentClass::where(function($query) {
            $query->where('status', 'full')
                  ->orWhereColumn('current_enrollment', '>=', 'capacity');
        })->count();
        
        $activeClassesCount = StudentClass::where('status', 'active')
            ->whereColumn('current_enrollment', '<', 'capacity')
            ->where('current_enrollment', '>', 0)
            ->count();
        
        $emptyClassesCount = StudentClass::where('current_enrollment', 0)->count();

        // Get approved and rejected teacher counts
        $approvedTeachersCount = TeacherRequest::where('status', 'approved')->count();
        $rejectedTeachersCount = TeacherRequest::where('status', 'rejected')->count();

        // KPI Cards - Get counts
        $totalTeachers = User::where('role', 'teacher')->count();
        $totalStudents = Student::count();
        $totalAssignments = Assignment::count();
        $totalQuizzes = Quiz::count();
        
        // Count games (regular games + word search + matching pairs)
        $regularGames = Game::whereNotNull('game_type')
            ->where('game_type', '!=', 'clock')
            ->where('game_type', '!=', 'closet_game')
            ->count();
        $wordSearchGames = \App\Models\WordSearchGame::count();
        $matchingPairsGames = \App\Models\MatchingPairsGame::count();
        $totalGames = $regularGames + $wordSearchGames + $matchingPairsGames;

        // Learning Activities Overview
        $activitiesOverview = $this->getActivitiesOverview();
        
        // Engagement & Performance Data (for charts)
        $engagementData = $this->getEngagementData();
        
        // Alerts & Action Needed
        $alerts = $this->getAlerts();

        return view('admin.dashboard', [
            'lessonsCount' => Lesson::count(),
            'classesCount' => StudentClass::count(),
            'teacherRequestsCount' => TeacherRequest::where('status', 'pending')->count(),
            'emergencyCasesCount' => TeacherSubstitution::where('status', 'active')->count(),
            'studentsCount' => $totalStudents,
            'teachersCount' => $totalTeachers,
            'fullClassesCount' => $fullClassesCount,
            'activeClassesCount' => $activeClassesCount,
            'emptyClassesCount' => $emptyClassesCount,
            'unreadRequests' => $unreadRequests,
            'unreadRequestsCount' => $unreadRequests->count(),
            'unreadNewStudents' => $unreadNewStudents,
            'unreadNewStudentsCount' => $unreadNewStudents->count(),
            'approvedTeachersCount' => $approvedTeachersCount,
            'rejectedTeachersCount' => $rejectedTeachersCount,
            // New KPI data
            'totalAssignments' => $totalAssignments,
            'totalQuizzes' => $totalQuizzes,
            'totalGames' => $totalGames,
            // New sections
            'activitiesOverview' => $activitiesOverview,
            'engagementData' => $engagementData,
            'alerts' => $alerts,
        ]);
    }
    
    /**
     * Get Learning Activities Overview statistics
     */
    private function getActivitiesOverview()
    {
        // Assignments overview
        $allAssignments = Assignment::with(['studentClass.students', 'submissions'])->get();
        $totalAssignmentsCount = $allAssignments->count();
        $assignmentSubmissions = \App\Models\AssignmentSubmission::count();
        $totalStudentsForAssignments = 0;
        foreach ($allAssignments as $assignment) {
            if ($assignment->studentClass) {
                $totalStudentsForAssignments += $assignment->studentClass->students()->count();
            }
        }
        $avgAssignmentParticipation = $totalStudentsForAssignments > 0 
            ? round(($assignmentSubmissions / max($totalStudentsForAssignments, 1)) * 100, 1) 
            : 0;
        
        $assignmentGrades = \App\Models\Grade::whereNotNull('assignment_submission_id')->get();
        $avgAssignmentScore = $assignmentGrades->where('percentage', '!=', null)->avg('percentage') ?? 0;
        $assignmentStatus = $this->getUsageStatus($avgAssignmentParticipation, $avgAssignmentScore);
        
        // Quizzes overview
        $allQuizzes = Quiz::with(['studentClass.students', 'attempts'])->get();
        $totalQuizzesCount = $allQuizzes->count();
        $quizAttempts = \App\Models\QuizAttempt::where('status', 'completed')->count();
        $totalStudentsForQuizzes = 0;
        foreach ($allQuizzes as $quiz) {
            if ($quiz->studentClass) {
                $totalStudentsForQuizzes += $quiz->studentClass->students()->count();
            }
        }
        $avgQuizParticipation = $totalStudentsForQuizzes > 0 
            ? round(($quizAttempts / max($totalStudentsForQuizzes, 1)) * 100, 1) 
            : 0;
        
        // Calculate average quiz score from grades
        $quizGrades = \App\Models\Grade::whereNotNull('quiz_attempt_id')->get();
        $quizScorePercentages = [];
        foreach ($allQuizzes as $quiz) {
            $completedAttempts = $quiz->attempts->where('status', 'completed')->where('score', '!=', null);
            foreach ($completedAttempts as $attempt) {
                if ($attempt->grade && $attempt->grade->percentage !== null) {
                    $quizScorePercentages[] = $attempt->grade->percentage;
                } elseif ($quiz->max_score && $quiz->max_score > 0) {
                    $percentage = ($attempt->score / $quiz->max_score) * 100;
                    if ($percentage > 100 && $attempt->score <= 100) {
                        $percentage = $attempt->score;
                    }
                    $quizScorePercentages[] = min($percentage, 100);
                }
            }
        }
        $avgQuizScore = count($quizScorePercentages) > 0 ? array_sum($quizScorePercentages) / count($quizScorePercentages) : 0;
        $quizStatus = $this->getUsageStatus($avgQuizParticipation, $avgQuizScore);
        
        // Games overview
        $regularGames = Game::whereNotNull('game_type')
            ->where('game_type', '!=', 'clock')
            ->where('game_type', '!=', 'closet_game')
            ->get();
        $wordSearchGames = \App\Models\WordSearchGame::all();
        $matchingPairsGames = \App\Models\MatchingPairsGame::all();
        $totalGamesCount = $regularGames->count() + $wordSearchGames->count() + $matchingPairsGames->count();
        
        $gameProgresses = \App\Models\StudentGameProgress::count();
        $totalStudentsForGames = Student::count(); // Approximate
        $avgGameParticipation = $totalStudentsForGames > 0 
            ? round(($gameProgresses / max($totalStudentsForGames * $totalGamesCount, 1)) * 100, 1) 
            : 0;
        
        $avgGameScore = \App\Models\StudentGameProgress::where('score', '!=', null)->avg('score') ?? 0;
        $gameStatus = $this->getUsageStatus($avgGameParticipation, $avgGameScore);
        
        return [
            'assignments' => [
                'total_count' => $totalAssignmentsCount,
                'avg_participation_rate' => $avgAssignmentParticipation,
                'avg_score' => round($avgAssignmentScore, 1),
                'status' => $assignmentStatus,
            ],
            'quizzes' => [
                'total_count' => $totalQuizzesCount,
                'avg_participation_rate' => $avgQuizParticipation,
                'avg_score' => round($avgQuizScore, 1),
                'status' => $quizStatus,
            ],
            'games' => [
                'total_count' => $totalGamesCount,
                'avg_participation_rate' => $avgGameParticipation,
                'avg_score' => round($avgGameScore, 1),
                'status' => $gameStatus,
            ],
        ];
    }
    
    /**
     * Get engagement and performance data for charts
     */
    private function getEngagementData()
    {
        // Assignment submissions over time (last 12 months)
        $assignmentSubmissionsOverTime = [];
        $assignmentLabels = [];
        for ($i = 11; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $monthStart = $date->copy()->startOfMonth();
            $monthEnd = $date->copy()->endOfMonth();
            
            $count = \App\Models\AssignmentSubmission::whereBetween('submitted_at', [$monthStart, $monthEnd])
                ->count();
            
            $assignmentSubmissionsOverTime[] = $count;
            $assignmentLabels[] = $date->format('M Y');
        }
        
        // Quiz attempts and average scores over time (last 12 months)
        $quizAttemptsOverTime = [];
        $quizAvgScoresOverTime = [];
        $quizLabels = [];
        for ($i = 11; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $monthStart = $date->copy()->startOfMonth();
            $monthEnd = $date->copy()->endOfMonth();
            
            $attempts = \App\Models\QuizAttempt::whereBetween('submitted_at', [$monthStart, $monthEnd])
                ->where('status', 'completed')
                ->get();
            
            $attemptsCount = $attempts->count();
            $avgScore = $attempts->where('score', '!=', null)->avg('score') ?? 0;
            
            $quizAttemptsOverTime[] = $attemptsCount;
            $quizAvgScoresOverTime[] = round($avgScore, 1);
            $quizLabels[] = $date->format('M Y');
        }
        
        // Game play counts over time (last 12 months)
        $gamePlayCountsOverTime = [];
        $gameLabels = [];
        for ($i = 11; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $monthStart = $date->copy()->startOfMonth();
            $monthEnd = $date->copy()->endOfMonth();
            
            $count = \App\Models\StudentGameProgress::whereBetween('created_at', [$monthStart, $monthEnd])
                ->count();
            
            $gamePlayCountsOverTime[] = $count;
            $gameLabels[] = $date->format('M Y');
        }
        
        return [
            'assignment_submissions' => [
                'labels' => $assignmentLabels,
                'data' => $assignmentSubmissionsOverTime,
            ],
            'quiz_attempts' => [
                'labels' => $quizLabels,
                'data' => $quizAttemptsOverTime,
            ],
            'quiz_avg_scores' => [
                'labels' => $quizLabels,
                'data' => $quizAvgScoresOverTime,
            ],
            'game_play_counts' => [
                'labels' => $gameLabels,
                'data' => $gamePlayCountsOverTime,
            ],
        ];
    }
    
    /**
     * Get alerts and action needed items
     */
    private function getAlerts()
    {
        $alerts = [];
        
        // Assignments with zero submissions
        $assignmentsWithZeroSubmissions = Assignment::with('submissions')
            ->get()
            ->filter(function($assignment) {
                return $assignment->submissions->count() === 0;
            })
            ->take(10)
            ->map(function($assignment) {
                return [
                    'id' => $assignment->assignment_id,
                    'title' => $assignment->title,
                    'type' => 'assignment',
                    'class' => $assignment->studentClass ? $assignment->studentClass->class_name : 'N/A',
                    'message' => 'No submissions received',
                ];
            })->toArray();
        
        if (count($assignmentsWithZeroSubmissions) > 0) {
            $alerts['assignments_zero_submissions'] = $assignmentsWithZeroSubmissions;
        }
        
        // Quizzes with very low average scores (< 50%)
        $quizzesWithLowScores = Quiz::with(['attempts.grade'])->get()
            ->filter(function($quiz) {
                $completedAttempts = $quiz->attempts->where('status', 'completed')->where('score', '!=', null);
                if ($completedAttempts->count() === 0) return false;
                
                $scorePercentages = [];
                foreach ($completedAttempts as $attempt) {
                    if ($attempt->grade && $attempt->grade->percentage !== null) {
                        $scorePercentages[] = $attempt->grade->percentage;
                    } elseif ($quiz->max_score && $quiz->max_score > 0) {
                        $percentage = ($attempt->score / $quiz->max_score) * 100;
                        if ($percentage > 100 && $attempt->score <= 100) {
                            $percentage = $attempt->score;
                        }
                        $scorePercentages[] = min($percentage, 100);
                    }
                }
                
                $avgScore = count($scorePercentages) > 0 ? array_sum($scorePercentages) / count($scorePercentages) : 100;
                return $avgScore < 50;
            })
            ->take(10)
            ->map(function($quiz) {
                return [
                    'id' => $quiz->quiz_id,
                    'title' => $quiz->title,
                    'type' => 'quiz',
                    'class' => $quiz->studentClass ? $quiz->studentClass->class_name : 'N/A',
                    'message' => 'Average score below 50%',
                ];
            })->toArray();
        
        if (count($quizzesWithLowScores) > 0) {
            $alerts['quizzes_low_scores'] = $quizzesWithLowScores;
        }
        
        // Games that were never played
        $gamesNeverPlayed = Game::with('studentProgresses')
            ->whereNotNull('game_type')
            ->where('game_type', '!=', 'clock')
            ->where('game_type', '!=', 'closet_game')
            ->get()
            ->filter(function($game) {
                return $game->studentProgresses->count() === 0;
            })
            ->take(10)
            ->map(function($game) {
                return [
                    'id' => $game->game_id,
                    'title' => $game->lesson ? $game->lesson->title : 'Unknown Game',
                    'type' => 'game',
                    'class' => 'N/A',
                    'message' => 'Never played by students',
                ];
            })->toArray();
        
        // Check word search games
        $wordSearchGamesNeverPlayed = \App\Models\WordSearchGame::with('game.studentProgresses')
            ->get()
            ->filter(function($game) {
                $progresses = $game->game ? $game->game->studentProgresses : collect();
                return $progresses->count() === 0;
            })
            ->take(10)
            ->map(function($game) {
                return [
                    'id' => $game->game_id ?? null,
                    'title' => $game->lesson ? $game->lesson->title : 'Word Search Game',
                    'type' => 'game',
                    'class' => 'N/A',
                    'message' => 'Never played by students',
                ];
            })->toArray();
        
        $gamesNeverPlayed = array_merge($gamesNeverPlayed, $wordSearchGamesNeverPlayed);
        
        // Check matching pairs games
        $matchingPairsGamesNeverPlayed = \App\Models\MatchingPairsGame::with('game.studentProgresses')
            ->get()
            ->filter(function($game) {
                $progresses = $game->game ? $game->game->studentProgresses : collect();
                return $progresses->count() === 0;
            })
            ->take(10)
            ->map(function($game) {
                return [
                    'id' => $game->game_id ?? null,
                    'title' => $game->lesson ? $game->lesson->title : 'Matching Pairs Game',
                    'type' => 'game',
                    'class' => 'N/A',
                    'message' => 'Never played by students',
                ];
            })->toArray();
        
        $gamesNeverPlayed = array_merge($gamesNeverPlayed, $matchingPairsGamesNeverPlayed);
        
        if (count($gamesNeverPlayed) > 0) {
            $alerts['games_never_played'] = array_slice($gamesNeverPlayed, 0, 10);
        }
        
        // Inactive teachers (teachers with no assignments, quizzes, or games in last 3 months)
        $threeMonthsAgo = now()->subMonths(3);
        $teachersWithRecentActivity = collect();
        
        // Get teachers from assignments
        $recentAssignmentTeachers = Assignment::where('created_at', '>=', $threeMonthsAgo)
            ->pluck('teacher_id');
        $teachersWithRecentActivity = $teachersWithRecentActivity->merge($recentAssignmentTeachers);
        
        // Get teachers from quizzes
        $recentQuizTeachers = Quiz::where('created_at', '>=', $threeMonthsAgo)
            ->pluck('teacher_id');
        $teachersWithRecentActivity = $teachersWithRecentActivity->merge($recentQuizTeachers);
        
        $inactiveTeachers = User::where('role', 'teacher')
            ->whereNotIn('user_id', $teachersWithRecentActivity->unique())
            ->take(10)
            ->get()
            ->map(function($teacher) {
                return [
                    'id' => $teacher->user_id,
                    'title' => $teacher->first_name . ' ' . $teacher->last_name,
                    'type' => 'teacher',
                    'class' => 'N/A',
                    'message' => 'No activity in last 3 months',
                ];
            })->toArray();
        
        if (count($inactiveTeachers) > 0) {
            $alerts['inactive_teachers'] = $inactiveTeachers;
        }
        
        return $alerts;
    }
    
    /**
     * Determine usage status based on participation rate and average score
     */
    private function getUsageStatus($participationRate, $avgScore = null)
    {
        if ($participationRate >= 70 && ($avgScore === null || $avgScore >= 70)) {
            return 'Healthy';
        } elseif ($participationRate >= 50 && ($avgScore === null || $avgScore >= 50)) {
            return 'Needs Review';
        } else {
            return 'Low Usage';
        }
    }

    // LESSONS - Using Database
    private function getLevelsArray()
    {
        $levels = Level::all();
        if ($levels->isEmpty()) {
            // Fallback: create levels 1-10 in-memory if DB is empty
            $fallback = [];
            for ($i = 1; $i <= 10; $i++) {
                $fallback[] = [
                    'id' => $i,
                    'name' => 'Level ' . $i,
                    'color' => self::$levelColors[$i] ?? 'from-gray-400 to-gray-500',
                ];
            }
            return $fallback;
        }
        return $levels->map(function($level, $index) {
            return [
                'id' => $level->level_id,
                'name' => $level->level_name,
                'color' => self::$levelColors[$index + 1] ?? 'from-gray-400 to-gray-500',
            ];
        })->toArray();
    }

    public function lessons()
    {
        $levels = $this->getLevelsArray();
        $lessons = Lesson::all();
        
        // Group by level
        $groupedLessons = [];
        foreach ($levels as $level) {
            $levelLessons = $lessons->where('level_id', $level['id'])->map(function($lesson) {
                return [
                    'id' => $lesson->lesson_id,
                    'levelId' => $lesson->level_id,
                    'title' => $lesson->title,
                    'skills' => $lesson->skills,
                    'icon' => $lesson->icon,
                    'description' => $lesson->description,
                    'content_url' => $lesson->content_url,
                    'duration_minutes' => $lesson->duration_minutes,
                ];
            })->values()->toArray();
            
            $groupedLessons[$level['id']] = [
                'level' => $level,
                'lessons' => $levelLessons
            ];
        }

        return view('admin.lessons.index', compact('groupedLessons', 'levels'));
    }

    public function createLesson()
    {
        return view('admin.lessons.create', ['levels' => $this->getLevelsArray()]);
    }

    public function storeLesson(Request $request)
    {
        // Increase memory limit for file processing (must be set before file handling)
        ini_set('memory_limit', '512M');
        ini_set('max_execution_time', '300'); // 5 minutes for large file uploads
        
        try {
            
            $validated = $request->validate([
                'title' => 'required|string|max:255',
                'skills' => 'required|integer|min:0',
                'icon' => 'nullable|string|max:10', // Made nullable with default fallback
                // Accept either levelId (existing) or new_level_name (new)
                'levelId' => 'nullable|integer',
                'new_level_name' => 'nullable|string|max:255',
                'new_level_number' => 'nullable|integer',
                'new_level_description' => 'nullable|string|max:255',
                'content_file' => 'nullable|file|mimes:pdf,mp4,mov,avi,mkv,wmv,flv,webm|max:51200', // 50MB max
                'duration_minutes' => 'nullable|integer|min:1',
            ], [
                'title.required' => 'Lesson title is required.',
                'skills.required' => 'Number of skills is required.',
                'content_file.mimes' => 'The file must be a PDF or video file (mp4, mov, avi, mkv, wmv, flv, webm).',
                'content_file.max' => 'The file size must not exceed 50MB.',
            ]);

            // Always ensure the selected level exists in the DB (for dropdown 1-10)
        if ($request->filled('levelId')) {
            $levelNumber = (int) $request->levelId;
            $level = \App\Models\Level::firstOrCreate(
                ['level_number' => $levelNumber],
                [
                    'level_name' => 'Level ' . $levelNumber,
                    'description' => 'Auto-created for lesson',
                ]
            );
            $levelId = $level->level_id;
        } else {
            // Fallback for custom/new level (if ever used)
            $level = \App\Models\Level::firstOrCreate(
                ['level_name' => $request->new_level_name],
                [
                    'level_number' => $request->new_level_number ?? 1,
                    'description' => $request->new_level_description ?? '',
                ]
            );
            $levelId = $level->level_id;
        }

        // Handle file upload (save to storage/app/public/lessons)
        $contentUrl = null;
        $videoSize = null;
        $videoFormat = null;
        $videoDurationSeconds = null;

        if ($request->hasFile('content_file')) {
            $file = $request->file('content_file');
            
            // Check file size before processing to avoid memory issues
            $fileSize = $file->getSize();
            if ($fileSize > 52428800) { // 50MB in bytes
                return redirect()->back()
                    ->withInput($request->except(['content_file', '_token']))
                    ->withErrors(['content_file' => 'File size exceeds 50MB limit.']);
            }
            
            // Get file information (without loading entire file into memory)
            $videoSize = $fileSize;
            $extension = strtolower($file->getClientOriginalExtension());
            
            // Determine if it's a video file
            $videoExtensions = ['mp4', 'mov', 'avi', 'mkv', 'wmv', 'flv', 'webm'];
            $isVideo = in_array($extension, $videoExtensions);
            
            if ($isVideo) {
                $videoFormat = $extension;
                
                // Try to get video duration using ffprobe if available
                try {
                    $tempFilePath = $file->getRealPath();
                    if ($tempFilePath && file_exists($tempFilePath)) {
                        $ffprobePath = env('FFPROBE_PATH', 'ffprobe'); // Default to 'ffprobe' in PATH
                        
                        // Check if ffprobe is available (Windows uses 'where', Unix uses 'which')
                        $ffprobeCheck = null;
                        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
                            $ffprobeCheck = @shell_exec("where {$ffprobePath} 2>&1");
                        } else {
                            $ffprobeCheck = @shell_exec("which {$ffprobePath} 2>&1");
                        }
                        
                        if ($ffprobeCheck && !empty(trim($ffprobeCheck)) && strpos($ffprobeCheck, 'not found') === false) {
                            // Get duration using ffprobe
                            $command = escapeshellarg($ffprobePath) . " -v error -show_entries format=duration -of default=noprint_wrappers=1:nokey=1 " . escapeshellarg($tempFilePath);
                            $duration = @shell_exec($command);
                            
                            if ($duration && is_numeric(trim($duration))) {
                                $videoDurationSeconds = (int) round(floatval(trim($duration)));
                            }
                        }
                    }
                } catch (\Exception $e) {
                    // If ffprobe is not available or fails, duration will remain null
                    // This is not critical - the video will still be uploaded
                } catch (\Throwable $e) {
                    // Catch any other errors silently
                }
            }
            
            // Store file in storage/app/public/lessons
            $filename = time() . '_' . uniqid() . '_' . $file->getClientOriginalName();
            $filePath = $file->storeAs('lessons', $filename, 'public');
            $contentUrl = $filePath; // Store only the path, not full URL
        }

        // Get the next lesson order for this level
        $maxOrder = Lesson::where('level_id', $levelId)->max('lesson_order') ?? 0;

        $lesson = Lesson::create([
            'level_id' => $levelId,
            'title' => $request->title,
            'skills' => (int) $request->skills,
            'icon' => $request->filled('icon') ? $request->icon : '📚', // Default icon if not provided
            'description' => $request->description ?? null,
            'content_url' => $contentUrl,
            'video_size' => $videoSize,
            'video_format' => $videoFormat,
            'video_duration_seconds' => $videoDurationSeconds,
            'duration_minutes' => $request->duration_minutes ? (int) $request->duration_minutes : null,
            'lesson_order' => $maxOrder + 1,
            'is_visible' => true,
            'uploaded_by_admin_id' => auth()->id(),
        ]);

        // Automatically make this lesson visible for all teachers/classes for this level
        try {
            $classes = \App\Models\StudentClass::whereHas('levels', function($q) use ($levelId) {
                $q->where('levels.level_id', $levelId);
            })->select('class_id')->get();
            
            // Get first teacher ID as default (since unique constraint is on class_id + lesson_id only)
            $firstTeacher = \App\Models\User::where('role', 'teacher')->first();
            
            if ($classes->isNotEmpty() && $firstTeacher) {
                $bulkData = [];
                foreach ($classes as $class) {
                    // Only one record per class-lesson pair (unique constraint)
                    $bulkData[] = [
                        'class_id' => $class->class_id,
                        'lesson_id' => $lesson->lesson_id,
                        'teacher_id' => $firstTeacher->user_id,
                        'is_visible' => true,
                        'changed_at' => now(),
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }
                
                // Use insertOrIgnore to avoid duplicate key violations
                if (!empty($bulkData)) {
                    \DB::table('class_lesson_visibilities')->insertOrIgnore($bulkData);
                }
            }
        } catch (\Exception $e) {
            // Log but don't fail the lesson creation if visibility setup fails
            \Log::warning('Failed to set lesson visibility: ' . $e->getMessage());
        }

            return redirect()->route('admin.lessons')->with('success', 'Lesson created successfully!');
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Return validation errors with input
            return redirect()->back()
                ->withErrors($e->errors())
                ->withInput($request->except(['content_file', '_token']));
        } catch (\Exception $e) {
            \Log::error('Error creating lesson: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'request' => $request->except(['content_file', '_token']),
                'file_size' => $request->hasFile('content_file') ? $request->file('content_file')->getSize() : null,
                'memory_usage' => memory_get_usage(true),
                'memory_peak' => memory_get_peak_usage(true),
            ]);
            
            $errorMessage = 'An error occurred while creating the lesson.';
            if (strpos($e->getMessage(), 'memory') !== false || strpos($e->getMessage(), 'exhausted') !== false) {
                $errorMessage = 'The file is too large or the server ran out of memory. Please try uploading a smaller file.';
            }
            
            return redirect()->back()
                ->withInput($request->except(['content_file', '_token']))
                ->withErrors(['error' => $errorMessage]);
        }
    }

    public function editLesson($id)
    {
        $lesson = Lesson::find($id);
        if (!$lesson) abort(404);

        // Convert to array format expected by the view
        $lessonData = [
            'id' => $lesson->lesson_id,
            'levelId' => $lesson->level_id,
            'title' => $lesson->title,
            'skills' => $lesson->skills,
            'icon' => $lesson->icon,
            'description' => $lesson->description,
            'content_url' => $lesson->content_url,
            'duration_minutes' => $lesson->duration_minutes,
        ];

        return view('admin.lessons.edit', [
            'lesson' => $lessonData,
            'levels' => $this->getLevelsArray()
        ]);
    }

    public function updateLesson(Request $request, $id)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'skills' => 'required|integer|min:0',
            'icon' => 'required|string|max:10',
            'levelId' => 'required|integer',
            'content_file' => 'nullable|file|mimes:pdf,mp4,mov,avi,mkv,wmv,flv,webm|max:51200',
        ]);

        $lesson = Lesson::find($id);
        if (!$lesson) abort(404);

        // Handle file upload (save to storage/app/public/lessons)
        if ($request->hasFile('content_file')) {
            // Delete old file if exists
            if ($lesson->content_url && Storage::disk('public')->exists($lesson->content_url)) {
                Storage::disk('public')->delete($lesson->content_url);
            }
            
            $file = $request->file('content_file');
            
            // Get file information
            $videoSize = $file->getSize();
            $extension = strtolower($file->getClientOriginalExtension());
            
            // Determine if it's a video file
            $videoExtensions = ['mp4', 'mov', 'avi', 'mkv', 'wmv', 'flv', 'webm'];
            $isVideo = in_array($extension, $videoExtensions);
            
            $videoFormat = null;
            $videoDurationSeconds = null;
            
            if ($isVideo) {
                $videoFormat = $extension;
                
                // Try to get video duration using ffprobe if available
                try {
                    $tempFilePath = $file->getRealPath();
                    if ($tempFilePath && file_exists($tempFilePath)) {
                        $ffprobePath = env('FFPROBE_PATH', 'ffprobe');
                        
                        // Check if ffprobe is available (Windows uses 'where', Unix uses 'which')
                        $ffprobeCheck = null;
                        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
                            $ffprobeCheck = @shell_exec("where {$ffprobePath} 2>&1");
                        } else {
                            $ffprobeCheck = @shell_exec("which {$ffprobePath} 2>&1");
                        }
                        
                        if ($ffprobeCheck && !empty(trim($ffprobeCheck)) && strpos($ffprobeCheck, 'not found') === false) {
                            // Get duration using ffprobe
                            $command = escapeshellarg($ffprobePath) . " -v error -show_entries format=duration -of default=noprint_wrappers=1:nokey=1 " . escapeshellarg($tempFilePath);
                            $duration = @shell_exec($command);
                            
                            if ($duration && is_numeric(trim($duration))) {
                                $videoDurationSeconds = (int) round(floatval(trim($duration)));
                            }
                        }
                    }
                } catch (\Exception $e) {
                    // If ffprobe is not available or fails, duration will remain null
                    // This is not critical - the video will still be uploaded
                } catch (\Throwable $e) {
                    // Catch any other errors silently
                }
            }
            
            // Store file in storage/app/public/lessons
            $filename = time() . '_' . uniqid() . '_' . $file->getClientOriginalName();
            $filePath = $file->storeAs('lessons', $filename, 'public');
            
            $lesson->content_url = $filePath;
            $lesson->video_size = $isVideo ? $videoSize : null;
            $lesson->video_format = $videoFormat;
            $lesson->video_duration_seconds = $videoDurationSeconds;
        } else {
            // If no new file uploaded, clear video metadata if content_url is removed
            if (!$request->filled('keep_content_file')) {
                $lesson->video_size = null;
                $lesson->video_format = null;
                $lesson->video_duration_seconds = null;
            }
        }

        $lesson->title = $request->title;
        $lesson->skills = (int) $request->skills;
        $lesson->icon = $request->icon;
        $lesson->level_id = (int) $request->levelId;
        $lesson->description = $request->description;
        $lesson->duration_minutes = $request->duration_minutes ? (int) $request->duration_minutes : null;
        $lesson->save();

        return redirect()->route('admin.lessons')->with('success', 'Lesson updated successfully!');
    }

    public function deleteLesson($id)
    {
        $lesson = Lesson::find($id);
        if ($lesson) {
            $lesson->delete();
        }

        return redirect()->route('admin.lessons')->with('success', 'Lesson deleted successfully!');
    }

    /**
     * Get unenrolled students from database
     */
    private function getUnenrolledStudents()
    {
        return Student::whereNull('class_id')
            ->with('user')
            ->get()
            ->map(function ($student) {
                $user = $student->user;
                return [
                    'id' => $student->student_id,
                    'name' => $user ? ($user->first_name . ' ' . $user->last_name) : 'Unknown',
                    'email' => $user ? $user->email : '',
                ];
            })->toArray();
    }

    // CLASSES
    public function classes()
    {
        return view('admin.classes.index', [
            'classes' => $this->getClassesFromDb(),
            'teachers' => $this->getTeachersFromDb(),
            'unenrolledStudents' => $this->getUnenrolledStudents(),
        ]);
    }

    public function createClass()
    {
        return view('admin.classes.create', [
            'teachers' => $this->getTeachersFromDb(),
            'colors' => self::$classColors
        ]);
    }

    public function storeClass(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'students' => 'required|integer|min:1',
            'teacherId' => 'nullable|integer',
            'color' => 'required|string|in:pink-dark,pink-light,cream,turquoise,teal,tan,beige,ivory,blush,coral,rose',
        ]);

        // Check if a class with the same name already exists (prevent duplicates)
        $existingClass = StudentClass::where('class_name', $request->name)->first();
        if ($existingClass) {
            return redirect()->route('admin.classes.create')
                ->withInput()
                ->withErrors(['name' => 'A class with this name already exists. Please choose a different name.']);
        }

        StudentClass::create([
            'class_name' => $request->name,
            'teacher_id' => $request->teacherId ?: null,
            'capacity' => (int) $request->students,
            'current_enrollment' => 0,
            'status' => 'active',
            'description' => $request->description ?? null,
            'color' => $request->color,
        ]);

        return redirect()->route('admin.classes')->with('success', 'Class created successfully!');
    }

    public function editClass($id)
    {
        $classModel = StudentClass::with('teacher')->find($id);
        if (!$classModel) abort(404);

        $class = [
            'id' => $classModel->class_id,
            'name' => $classModel->class_name,
            'teacherId' => $classModel->teacher_id,
            'students' => $classModel->capacity,
            'current_enrollment' => $classModel->current_enrollment,
            'status' => $classModel->status,
            'description' => $classModel->description,
            'color' => $classModel->color,
        ];

        return view('admin.classes.edit', [
            'class' => $class,
            'teachers' => $this->getTeachersFromDb(),
            'colors' => self::$classColors
        ]);
    }

    public function updateClass(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'students' => 'required|integer|min:1',
            'teacherId' => 'nullable|integer',
            'color' => 'nullable|string|in:pink-dark,pink-light,cream,turquoise,teal,tan,beige,ivory,blush,coral,rose',
        ]);

        $class = StudentClass::find($id);
        if (!$class) abort(404);

        $class->class_name = $request->name;
        $class->capacity = (int) $request->students;
        $class->teacher_id = $request->teacherId ?: null;
        if ($request->filled('color')) {
            $class->color = $request->color;
        }
        $class->save();

        return redirect()->route('admin.classes')->with('success', 'Class updated successfully!');
    }

    public function deleteClass($id)
    {
        $class = StudentClass::find($id);
        if ($class) {
            $class->delete();
        }

        return redirect()->route('admin.classes')->with('success', 'Class deleted successfully!');
    }

    // TEACHER REQUESTS
    public function teacherRequests()
    {
        // Mark all pending requests as read when admin views the page
        TeacherRequest::where('status', 'pending')
            ->where('is_read', false)
            ->update(['is_read' => true]);

        $pending = $this->getTeacherRequestsFromDb('pending');
        $approved = $this->getTeacherRequestsFromDb('approved');
        $rejected = $this->getTeacherRequestsFromDb('rejected');

        return view('admin.requests.index', compact('pending', 'approved', 'rejected'));
    }

    public function approveRequest($id)
    {
        \Log::info('approveRequest called for id: ' . $id);
        $teacherRequest = TeacherRequest::where('request_id', $id)->first();
        if (!$teacherRequest) {
            \Log::error('TeacherRequest not found for id: ' . $id);
            return redirect()->route('admin.requests')->with('error', 'Request not found!');
        }

        \Illuminate\Support\Facades\DB::beginTransaction();
        try {
            $teacherRequest->status = 'approved';
            $teacherRequest->approved_by_admin_id = Auth::id();
            $teacherRequest->processed_date = now();
            $teacherRequest->is_read = true;
            $teacherRequest->save();
            \Log::info('TeacherRequest status set to approved for id: ' . $id);

            // Generate a secure random password (10 characters max)
            $generatedPassword = $this->generateSecurePassword(10);
            $teacherEmail = $teacherRequest->email;
            $teacherName = $teacherRequest->full_name;

            // Split full name into first and last name (fallback to empty string if not present)
            $nameParts = preg_split('/\s+/', trim($teacherName), 2);
            $firstName = $nameParts[0] ?? '';
            $lastName = isset($nameParts[1]) ? $nameParts[1] : '';

            // Check if this is a guest application (no user_id) or existing user
            if ($teacherRequest->user_id) {
                // Existing user - update their role
                $user = User::find($teacherRequest->user_id);
                if ($user) {
                    $user->role = 'teacher';
                    $user->save();

                    Teacher::firstOrCreate(
                        ['user_id' => $user->user_id],
                        ['user_id' => $user->user_id]
                    );

                    // Use existing email if not set in request
                    $teacherEmail = $teacherRequest->email ?? $user->email;
                    $teacherName = $teacherRequest->full_name ?? $user->name;

                    // Update password for existing user
                    $user->password = Hash::make($generatedPassword);
                    $user->save();
                }
            } else {
                // Guest application - check if user already exists by email
                $user = User::where('email', $teacherEmail)->first();
                if ($user) {
                    // User exists, update role and password
                    $user->role = 'teacher';
                    $user->first_name = $firstName;
                    $user->last_name = $lastName;
                    $user->password = Hash::make($generatedPassword);
                    $user->save();
                } else {
                    // Create new user
                    $user = User::create([
                        'name' => $teacherName,
                        'first_name' => $firstName,
                        'last_name' => $lastName,
                        'email' => $teacherEmail,
                        'password' => Hash::make($generatedPassword),
                        'role' => 'teacher',
                    ]);
                }

                // Link the request to the user
                $teacherRequest->user_id = $user->user_id;
                $teacherRequest->save();

                // Create teacher record if not exists
                Teacher::firstOrCreate([
                    'user_id' => $user->user_id,
                ]);
            }

            // Send approval email with credentials (send immediately, not queued)
            try {
                $mailSent = Mail::to($teacherEmail)->send(new TeacherApprovedMail(
                    $teacherName,
                    $teacherEmail,
                    $generatedPassword
                ));
                \Log::info('Approval email sent to: ' . $teacherEmail . ' with password for request ID: ' . $id);
                \Log::info('Mail configuration - MAIL_MAILER: ' . config('mail.default'));
            } catch (\Exception $mailException) {
                \Log::error('Failed to send approval email to ' . $teacherEmail . ': ' . $mailException->getMessage());
                \Log::error('Email exception details: ' . $mailException->getTraceAsString());
                \Log::error('Mail configuration - MAIL_MAILER: ' . config('mail.default') . ', MAIL_HOST: ' . config('mail.mailers.smtp.host'));
                // Continue even if email fails, but log it
            }

            DB::commit();
            \Log::info('TeacherRequest approval completed for id: ' . $id);
            return redirect()->route('admin.requests')->with('success', 'Teacher request approved successfully! Login credentials have been sent to ' . $teacherEmail);
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Failed to approve request for id: ' . $id . ' - ' . $e->getMessage());
            return redirect()->route('admin.requests')->with('error', 'Failed to approve request: ' . $e->getMessage());
        }
    }

    public function rejectRequest(Request $request, $id)
    {
        $teacherRequest = TeacherRequest::where('request_id', $id)->first();
        if (!$teacherRequest) {
            return redirect()->route('admin.requests')->with('error', 'Request not found!');
        }

        $rejectionReason = $request->input('rejection_reason', 'Application rejected by admin');

        $teacherRequest->status = 'rejected';
        $teacherRequest->approved_by_admin_id = Auth::id();
        $teacherRequest->processed_date = now();
        $teacherRequest->rejection_reason = $rejectionReason;
        $teacherRequest->is_read = true;
        $teacherRequest->save();

        // Get teacher info for email
        $teacherEmail = $teacherRequest->email;
        $teacherName = $teacherRequest->full_name;

        // If this was from an existing user, get their info
        if ($teacherRequest->user_id && !$teacherEmail) {
            $user = User::find($teacherRequest->user_id);
            if ($user) {
                $teacherEmail = $user->email;
                $teacherName = $teacherName ?? $user->name;
            }
        }

        // Send rejection email with reason (send immediately, not queued)
        if ($teacherEmail) {
            try {
                Mail::to($teacherEmail)->send(new TeacherRejectedMail(
                    $teacherName ?? 'Applicant',
                    $rejectionReason
                ));
                \Log::info('Rejection email sent to: ' . $teacherEmail . ' for request ID: ' . $id);
                \Log::info('Mail configuration - MAIL_MAILER: ' . config('mail.default'));
            } catch (\Exception $e) {
                // Log the error but don't fail the rejection
                \Log::error('Failed to send rejection email to ' . $teacherEmail . ': ' . $e->getMessage());
                \Log::error('Email exception details: ' . $e->getTraceAsString());
                \Log::error('Mail configuration - MAIL_MAILER: ' . config('mail.default') . ', MAIL_HOST: ' . config('mail.mailers.smtp.host'));
            }
        } else {
            \Log::warning('Cannot send rejection email: No email address found for teacher request ID: ' . $id);
        }

        return redirect()->route('admin.requests')->with('success', 'Teacher request rejected. Notification email has been sent.');
    }

    // EMERGENCY
    public function emergency()
    {
        return view('admin.emergency.index', [
            'requests' => $this->getEmergencyCasesFromDb(),
            'teachers' => $this->getTeachersFromDb(),
            'classes' => $this->getClassesFromDb(),
        ]);
    }

    public function reassignTeacher(Request $request, $caseId)
    {
        $request->validate([
            'teacherId' => 'required|integer|exists:users,user_id',
            'classId' => 'nullable|integer|exists:student_classes,class_id',
        ]);

        // Find existing substitution or create new one
        $substitution = TeacherSubstitution::find($caseId);
        
        if ($substitution) {
            $substitution->substitute_teacher_id = $request->teacherId;
            $substitution->status = 'active';
            $substitution->save();
        }

        return redirect()->route('admin.emergency')->with('success', 'Teacher reassigned successfully!');
    }

    // ================================================================
    // DATABASE-BACKED ADMIN FUNCTIONS
    // ================================================================

    // --------------------------
    // LESSON MANAGEMENT (Database)
    // --------------------------

    /**
     * Add or create a new lesson
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function addOrCreateLesson(Request $request)
    {
        $validated = $request->validate([
            'level_id' => 'required|integer|exists:levels,level_id',
            'title' => 'required|string|max:255',
            'skills' => 'required|integer|min:0',
            'icon' => 'nullable|string|max:10',
            'description' => 'nullable|string',
            'content_url' => 'nullable|string|max:255',
            'duration_minutes' => 'nullable|integer|min:1',
            'is_visible' => 'nullable|boolean',
            'content_file' => 'nullable|file|mimes:pdf,mp4,mov,avi|max:51200',
        ]);

        // Handle file upload
        $contentUrl = $validated['content_url'] ?? null;
        if ($request->hasFile('content_file')) {
            $file = $request->file('content_file');
            $filename = time() . '_' . $file->getClientOriginalName();
            $contentUrl = $file->storeAs('lessons', $filename, 'public');
        }

        // Get the next lesson order for this level
        $maxOrder = Lesson::where('level_id', $validated['level_id'])->max('lesson_order') ?? 0;

        $lesson = Lesson::create([
            'level_id' => $validated['level_id'],
            'teacher_id' => $request->teacher_id ?? null,
            'uploaded_by_admin_id' => Auth::id(),
            'title' => $validated['title'],
            'skills' => $validated['skills'] ?? 0,
            'icon' => $validated['icon'] ?? '📚',
            'description' => $validated['description'] ?? null,
            'content_url' => $contentUrl,
            'duration_minutes' => $validated['duration_minutes'] ?? null,
            'lesson_order' => $maxOrder + 1,
            'is_visible' => $validated['is_visible'] ?? true,
            'upload_date' => now(),
        ]);

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Lesson created successfully!',
                'lesson' => $lesson
            ], 201);
        }

        return redirect()->route('admin.lessons')->with('success', 'Lesson created successfully!');
    }

    /**
     * Edit/Update an existing lesson
     * 
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function editLessonApi(Request $request, $id)
    {
        $lesson = Lesson::find($id);
        if (!$lesson) {
            if ($request->expectsJson()) {
                return response()->json(['success' => false, 'message' => 'Lesson not found'], 404);
            }
            abort(404);
        }

        $validated = $request->validate([
            'level_id' => 'sometimes|integer|exists:levels,level_id',
            'title' => 'sometimes|string|max:255',
            'skills' => 'nullable|integer|min:0',
            'icon' => 'nullable|string|max:10',
            'description' => 'nullable|string',
            'content_url' => 'nullable|string|max:255',
            'duration_minutes' => 'nullable|integer|min:1',
            'is_visible' => 'nullable|boolean',
            'lesson_order' => 'nullable|integer|min:1',
            'content_file' => 'nullable|file|mimes:pdf,mp4,mov,avi,mkv,wmv,flv,webm|max:51200',
        ]);

        // Handle file upload
        if ($request->hasFile('content_file')) {
            // Delete old file if exists
            if ($lesson->content_url && Storage::disk('public')->exists($lesson->content_url)) {
                Storage::disk('public')->delete($lesson->content_url);
            }
            
            $file = $request->file('content_file');
            
            // Get file information
            $videoSize = $file->getSize();
            $extension = strtolower($file->getClientOriginalExtension());
            
            // Determine if it's a video file
            $videoExtensions = ['mp4', 'mov', 'avi', 'mkv', 'wmv', 'flv', 'webm'];
            $isVideo = in_array($extension, $videoExtensions);
            
            $videoFormat = null;
            $videoDurationSeconds = null;
            
            if ($isVideo) {
                $videoFormat = $extension;
                
                // Try to get video duration using ffprobe if available
                try {
                    $tempFilePath = $file->getRealPath();
                    if ($tempFilePath && file_exists($tempFilePath)) {
                        $ffprobePath = env('FFPROBE_PATH', 'ffprobe');
                        
                        // Check if ffprobe is available (Windows uses 'where', Unix uses 'which')
                        $ffprobeCheck = null;
                        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
                            $ffprobeCheck = @shell_exec("where {$ffprobePath} 2>&1");
                        } else {
                            $ffprobeCheck = @shell_exec("which {$ffprobePath} 2>&1");
                        }
                        
                        if ($ffprobeCheck && !empty(trim($ffprobeCheck)) && strpos($ffprobeCheck, 'not found') === false) {
                            // Get duration using ffprobe
                            $command = escapeshellarg($ffprobePath) . " -v error -show_entries format=duration -of default=noprint_wrappers=1:nokey=1 " . escapeshellarg($tempFilePath);
                            $duration = @shell_exec($command);
                            
                            if ($duration && is_numeric(trim($duration))) {
                                $videoDurationSeconds = (int) round(floatval(trim($duration)));
                            }
                        }
                    }
                } catch (\Exception $e) {
                    // If ffprobe is not available or fails, duration will remain null
                    // This is not critical - the video will still be uploaded
                } catch (\Throwable $e) {
                    // Catch any other errors silently
                }
            }
            
            // Store file in storage/app/public/lessons
            $filename = time() . '_' . uniqid() . '_' . $file->getClientOriginalName();
            $validated['content_url'] = $file->storeAs('lessons', $filename, 'public');
            $validated['video_size'] = $isVideo ? $videoSize : null;
            $validated['video_format'] = $videoFormat;
            $validated['video_duration_seconds'] = $videoDurationSeconds;
        }

        $lesson->update($validated);

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Lesson updated successfully!',
                'lesson' => $lesson->fresh()
            ]);
        }

        return redirect()->route('admin.lessons')->with('success', 'Lesson updated successfully!');
    }

    /**
     * Delete a lesson
     * 
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function deleteLessonApi(Request $request, $id)
    {
        $lesson = Lesson::find($id);
        if (!$lesson) {
            if ($request->expectsJson()) {
                return response()->json(['success' => false, 'message' => 'Lesson not found'], 404);
            }
            abort(404);
        }

        // Delete associated file if exists
        if ($lesson->content_url && Storage::disk('public')->exists($lesson->content_url)) {
            Storage::disk('public')->delete($lesson->content_url);
        }

        $lesson->delete();

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Lesson deleted successfully!'
            ]);
        }

        return redirect()->route('admin.lessons')->with('success', 'Lesson deleted successfully!');
    }

    // --------------------------
    // CLASS MANAGEMENT (Database)
    // --------------------------

    /**
     * Create a new class
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function createNewClass(Request $request)
    {
        $validated = $request->validate([
            'class_name' => 'required|string|max:255',
            'teacher_id' => 'nullable|integer|exists:users,user_id',
            'capacity' => 'required|integer|min:1|max:100',
            'description' => 'nullable|string',
            'status' => 'nullable|string|in:active,inactive,archived',
        ]);

        $class = StudentClass::create([
            'class_name' => $validated['class_name'],
            'teacher_id' => $validated['teacher_id'] ?? null,
            'capacity' => $validated['capacity'],
            'current_enrollment' => 0,
            'status' => $validated['status'] ?? 'active',
            'description' => $validated['description'] ?? null,
        ]);

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Class created successfully!',
                'class' => $class
            ], 201);
        }

        return redirect()->route('admin.classes')->with('success', 'Class created successfully!');
    }

    /**
     * Edit/Update an existing class
     * 
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function editClassApi(Request $request, $id)
    {
        $class = StudentClass::find($id);
        if (!$class) {
            if ($request->expectsJson()) {
                return response()->json(['success' => false, 'message' => 'Class not found'], 404);
            }
            abort(404);
        }

        $validated = $request->validate([
            'class_name' => 'sometimes|string|max:255',
            'teacher_id' => 'nullable|integer|exists:users,user_id',
            'capacity' => 'sometimes|integer|min:1|max:100',
            'description' => 'nullable|string',
            'status' => 'nullable|string|in:active,inactive,archived',
        ]);

        // Ensure capacity is not less than current enrollment
        if (isset($validated['capacity']) && $validated['capacity'] < $class->current_enrollment) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Capacity cannot be less than current enrollment (' . $class->current_enrollment . ')'
                ], 422);
            }
            return back()->withErrors(['capacity' => 'Capacity cannot be less than current enrollment']);
        }

        $class->update($validated);

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Class updated successfully!',
                'class' => $class->fresh()
            ]);
        }

        return redirect()->route('admin.classes')->with('success', 'Class updated successfully!');
    }

    /**
     * Add students to a class
     * 
     * @param Request $request
     * @param int $classId
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function addStudentsToClass(Request $request, $classId)
    {
        $class = StudentClass::find($classId);
        if (!$class) {
            if ($request->expectsJson()) {
                return response()->json(['success' => false, 'message' => 'Class not found'], 404);
            }
            abort(404);
        }

        $validated = $request->validate([
            'student_ids' => 'required|array',
            'student_ids.*' => 'integer|exists:students,student_id',
        ]);

        $studentIds = $validated['student_ids'];
        $addedCount = count($studentIds);

        // Check if adding these students would exceed capacity
        if ($class->current_enrollment + $addedCount > $class->capacity) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cannot add students. Exceeds class capacity. Available spots: ' . ($class->capacity - $class->current_enrollment)
                ], 422);
            }
            return back()->withErrors(['student_ids' => 'Cannot add students. Exceeds class capacity.']);
        }

        // Update students' class assignment (assuming there's a class_id field in students table)
        // If the students table doesn't have a class_id, you might need a pivot table
        DB::table('students')
            ->whereIn('student_id', $studentIds)
            ->update(['class_id' => $classId]);

        // Update enrollment count
        $class->current_enrollment += $addedCount;
        $class->save();

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => $addedCount . ' student(s) added to class successfully!',
                'class' => $class->fresh()
            ]);
        }

        return redirect()->route('admin.classes')->with('success', $addedCount . ' student(s) added to class!');
    }

    /**
     * Remove students from a class
     * 
     * @param Request $request
     * @param int $classId
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function removeStudentsFromClass(Request $request, $classId)
    {
        $class = StudentClass::find($classId);
        if (!$class) {
            if ($request->expectsJson()) {
                return response()->json(['success' => false, 'message' => 'Class not found'], 404);
            }
            abort(404);
        }

        $validated = $request->validate([
            'student_ids' => 'required|array',
            'student_ids.*' => 'integer|exists:students,student_id',
        ]);

        $studentIds = $validated['student_ids'];

        // Count students actually in this class
        $actualRemovalCount = DB::table('students')
            ->whereIn('student_id', $studentIds)
            ->where('class_id', $classId)
            ->count();

        // Remove students from class
        DB::table('students')
            ->whereIn('student_id', $studentIds)
            ->where('class_id', $classId)
            ->update(['class_id' => null]);

        // Update enrollment count
        $class->current_enrollment = max(0, $class->current_enrollment - $actualRemovalCount);
        $class->save();

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => $actualRemovalCount . ' student(s) removed from class successfully!',
                'class' => $class->fresh()
            ]);
        }

        return redirect()->route('admin.classes')->with('success', $actualRemovalCount . ' student(s) removed from class!');
    }

    /**
     * Change a student's class assignment
     * 
     * @param Request $request
     * @param int $studentId
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function changeStudentClass(Request $request, $studentId)
    {
        $student = Student::find($studentId);
        if (!$student) {
            if ($request->expectsJson()) {
                return response()->json(['success' => false, 'message' => 'Student not found'], 404);
            }
            abort(404);
        }

        $validated = $request->validate([
            'new_class_id' => 'required|integer|exists:student_classes,class_id',
        ]);

        $newClass = StudentClass::find($validated['new_class_id']);

        // Check if new class has capacity
        if ($newClass->current_enrollment >= $newClass->capacity) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cannot transfer student. The destination class is full.'
                ], 422);
            }
            return back()->withErrors(['new_class_id' => 'Destination class is full.']);
        }

        // Get the old class (if exists)
        $oldClassId = DB::table('students')->where('student_id', $studentId)->value('class_id');
        if ($oldClassId) {
            $oldClass = StudentClass::find($oldClassId);
            if ($oldClass) {
                $oldClass->current_enrollment = max(0, $oldClass->current_enrollment - 1);
                $oldClass->save();
            }
        }

        // Update student's class
        DB::table('students')
            ->where('student_id', $studentId)
            ->update(['class_id' => $validated['new_class_id']]);

        // Increment new class enrollment
        $newClass->current_enrollment++;
        $newClass->save();

        if ($request->expectsJson()) {
            $user = $student->user;
            return response()->json([
                'success' => true,
                'message' => 'Student transferred to new class successfully!',
                'student' => [
                    'id' => $student->student_id,
                    'name' => $user ? ($user->first_name . ' ' . $user->last_name) : 'Unknown',
                    'email' => $user ? $user->email : '',
                ],
                'new_class' => $newClass->fresh()
            ]);
        }

        return redirect()->route('admin.classes')->with('success', 'Student transferred to new class!');
    }

    /**
     * Assign a teacher to a class
     * 
     * @param Request $request
     * @param int $classId
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function assignTeacher(Request $request, $classId)
    {
        $class = StudentClass::find($classId);
        if (!$class) {
            if ($request->expectsJson()) {
                return response()->json(['success' => false, 'message' => 'Class not found'], 404);
            }
            abort(404);
        }

        $validated = $request->validate([
            'teacher_id' => 'required|integer|exists:users,user_id',
        ]);

        // Verify the user is a teacher
        $teacher = User::where('user_id', $validated['teacher_id'])
            ->where('role', 'teacher')
            ->first();

        if (!$teacher) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'The selected user is not a teacher.'
                ], 422);
            }
            return back()->withErrors(['teacher_id' => 'The selected user is not a teacher.']);
        }

        $class->teacher_id = $validated['teacher_id'];
        $class->save();

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Teacher assigned to class successfully!',
                'class' => $class->fresh()->load('teacher')
            ]);
        }

        return redirect()->route('admin.classes')->with('success', 'Teacher assigned to class!');
    }

    /**
     * Assign/Update class capacity
     * 
     * @param Request $request
     * @param int $classId
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function assignClassCapacity(Request $request, $classId)
    {
        $class = StudentClass::find($classId);
        if (!$class) {
            if ($request->expectsJson()) {
                return response()->json(['success' => false, 'message' => 'Class not found'], 404);
            }
            abort(404);
        }

        $validated = $request->validate([
            'capacity' => 'required|integer|min:1|max:100',
        ]);

        // Ensure capacity is not less than current enrollment
        if ($validated['capacity'] < $class->current_enrollment) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Capacity cannot be less than current enrollment (' . $class->current_enrollment . ')'
                ], 422);
            }
            return back()->withErrors(['capacity' => 'Capacity cannot be less than current enrollment']);
        }

        $class->capacity = $validated['capacity'];
        $class->save();

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Class capacity updated successfully!',
                'class' => $class->fresh()
            ]);
        }

        return redirect()->route('admin.classes')->with('success', 'Class capacity updated!');
    }

    // --------------------------
    // TEACHER REQUEST MANAGEMENT (Database)
    // --------------------------

    /**
     * Approve a teacher request
     * 
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function approveTeacherRequest(Request $request, $id)
    {
        $teacherRequest = TeacherRequest::find($id);
        if (!$teacherRequest) {
            if ($request->expectsJson()) {
                return response()->json(['success' => false, 'message' => 'Teacher request not found'], 404);
            }
            abort(404);
        }

        if ($teacherRequest->status !== 'pending') {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'This request has already been processed.'
                ], 422);
            }
            return back()->withErrors(['status' => 'This request has already been processed.']);
        }

        DB::beginTransaction();
        try {
            // Update the request status
            $teacherRequest->status = 'approved';
            $teacherRequest->approved_by_admin_id = Auth::id();
            $teacherRequest->processed_date = now();
            $teacherRequest->is_read = true;
            $teacherRequest->save();

            // Generate a secure random password (10 characters max)
            $generatedPassword = $this->generateSecurePassword(10);
            $teacherEmail = $teacherRequest->email;
            $teacherName = $teacherRequest->full_name;

            // Split full name into first and last name (fallback to empty string if not present)
            $nameParts = preg_split('/\s+/', trim($teacherName), 2);
            $firstName = $nameParts[0] ?? '';
            $lastName = isset($nameParts[1]) ? $nameParts[1] : '';

            // Check if this is a guest application (no user_id) or existing user
            if ($teacherRequest->user_id) {
                // Existing user - update their role
                $user = User::find($teacherRequest->user_id);
                if ($user) {
                    $user->role = 'teacher';
                    $user->save();

                    Teacher::firstOrCreate(
                        ['user_id' => $user->user_id],
                        ['user_id' => $user->user_id]
                    );

                    // Use existing email if not set in request
                    $teacherEmail = $teacherRequest->email ?? $user->email;
                    $teacherName = $teacherRequest->full_name ?? $user->name;

                    // Update password for existing user
                    $user->password = Hash::make($generatedPassword);
                    $user->save();
                }
            } else {
                // Guest application - check if user already exists by email
                $user = User::where('email', $teacherEmail)->first();
                if ($user) {
                    // User exists, update role and password
                    $user->role = 'teacher';
                    $user->first_name = $firstName;
                    $user->last_name = $lastName;
                    $user->password = Hash::make($generatedPassword);
                    $user->save();
                } else {
                    // Create new user
                    $user = User::create([
                        'name' => $teacherName,
                        'first_name' => $firstName,
                        'last_name' => $lastName,
                        'email' => $teacherEmail,
                        'password' => Hash::make($generatedPassword),
                        'role' => 'teacher',
                    ]);
                }

                // Link the request to the user
                $teacherRequest->user_id = $user->user_id;
                $teacherRequest->save();

                // Create teacher record if not exists
                Teacher::firstOrCreate([
                    'user_id' => $user->user_id,
                ]);
            }

            // Send approval email with credentials (send immediately, not queued)
            try {
                $mailSent = Mail::to($teacherEmail)->send(new TeacherApprovedMail(
                    $teacherName,
                    $teacherEmail,
                    $generatedPassword
                ));
                \Log::info('Approval email sent to: ' . $teacherEmail . ' with password for request ID: ' . $id);
                \Log::info('Mail configuration - MAIL_MAILER: ' . config('mail.default'));
            } catch (\Exception $mailException) {
                \Log::error('Failed to send approval email to ' . $teacherEmail . ': ' . $mailException->getMessage());
                \Log::error('Email exception details: ' . $mailException->getTraceAsString());
                \Log::error('Mail configuration - MAIL_MAILER: ' . config('mail.default') . ', MAIL_HOST: ' . config('mail.mailers.smtp.host'));
                // Continue even if email fails, but log it
            }

            DB::commit();

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Teacher request approved successfully! Login credentials have been sent to ' . $teacherEmail,
                    'request' => $teacherRequest->fresh()
                ]);
            }

            return redirect()->route('admin.requests')->with('success', 'Teacher request approved successfully! Login credentials have been sent to ' . $teacherEmail);
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Failed to approve teacher request: ' . $e->getMessage());
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to approve teacher request: ' . $e->getMessage()
                ], 500);
            }
            return back()->withErrors(['error' => 'Failed to approve teacher request: ' . $e->getMessage()]);
        }
    }

    /**
     * Reject a teacher request
     * 
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function rejectTeacherRequest(Request $request, $id)
    {
        $teacherRequest = TeacherRequest::find($id);
        if (!$teacherRequest) {
            if ($request->expectsJson()) {
                return response()->json(['success' => false, 'message' => 'Teacher request not found'], 404);
            }
            abort(404);
        }

        if ($teacherRequest->status !== 'pending') {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'This request has already been processed.'
                ], 422);
            }
            return back()->withErrors(['status' => 'This request has already been processed.']);
        }

        $validated = $request->validate([
            'rejection_reason' => 'nullable|string|max:500',
        ]);

        $teacherRequest->status = 'rejected';
        $teacherRequest->approved_by_admin_id = Auth::id();
        $teacherRequest->processed_date = now();
        $teacherRequest->rejection_reason = $validated['rejection_reason'] ?? 'Application rejected by admin';
        $teacherRequest->is_read = true;
        $teacherRequest->save();

        // Get teacher info for email
        $teacherEmail = $teacherRequest->email;
        $teacherName = $teacherRequest->full_name;

        // If this was from an existing user, get their info
        if ($teacherRequest->user_id && !$teacherEmail) {
            $user = User::find($teacherRequest->user_id);
            if ($user) {
                $teacherEmail = $user->email;
                $teacherName = $teacherName ?? $user->name;
            }
        }

        // Send rejection email with reason (send immediately, not queued)
        if ($teacherEmail) {
            try {
                Mail::to($teacherEmail)->send(new TeacherRejectedMail(
                    $teacherName ?? 'Applicant',
                    $teacherRequest->rejection_reason
                ));
                \Log::info('Rejection email sent to: ' . $teacherEmail . ' for request ID: ' . $id);
                \Log::info('Mail configuration - MAIL_MAILER: ' . config('mail.default'));
            } catch (\Exception $e) {
                // Log the error but don't fail the rejection
                \Log::error('Failed to send rejection email to ' . $teacherEmail . ': ' . $e->getMessage());
                \Log::error('Email exception details: ' . $e->getTraceAsString());
                \Log::error('Mail configuration - MAIL_MAILER: ' . config('mail.default') . ', MAIL_HOST: ' . config('mail.mailers.smtp.host'));
            }
        } else {
            \Log::warning('Cannot send rejection email: No email address found for teacher request ID: ' . $id);
        }

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Teacher request rejected. Notification email has been sent to ' . $teacherEmail,
                'request' => $teacherRequest->fresh()
            ]);
        }

        return redirect()->route('admin.requests')->with('success', 'Teacher request rejected. Notification email has been sent to ' . ($teacherEmail ?? 'the applicant'));
    }

    // --------------------------
    // EMERGENCY TEACHER REASSIGNMENT (Database)
    // --------------------------

    /**
     * Reassign a substitute teacher for emergency situations
     * 
     * @param Request $request
     * @param int $classId
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function reassignTeacherEmergency(Request $request, $classId)
    {
        $class = StudentClass::find($classId);
        if (!$class) {
            if ($request->expectsJson()) {
                return response()->json(['success' => false, 'message' => 'Class not found'], 404);
            }
            abort(404);
        }

        $validated = $request->validate([
            'substitute_teacher_id' => 'required|integer|exists:users,user_id',
            'reason' => 'required|string|max:500',
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        // Verify the substitute is a teacher
        $substituteTeacher = User::where('user_id', $validated['substitute_teacher_id'])
            ->where('role', 'teacher')
            ->first();

        if (!$substituteTeacher) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'The selected substitute is not a teacher.'
                ], 422);
            }
            return back()->withErrors(['substitute_teacher_id' => 'The selected substitute is not a teacher.']);
        }

        // Create substitution record
        $substitution = TeacherSubstitution::create([
            'class_id' => $classId,
            'original_teacher_id' => $class->teacher_id,
            'substitute_teacher_id' => $validated['substitute_teacher_id'],
            'requested_by_admin_id' => Auth::id(),
            'reason' => $validated['reason'],
            'start_date' => $validated['start_date'],
            'end_date' => $validated['end_date'],
            'status' => 'active',
        ]);

        // Optionally temporarily update the class teacher
        // Uncomment if you want to actually change the class teacher during substitution
        // $class->teacher_id = $validated['substitute_teacher_id'];
        // $class->save();

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Substitute teacher assigned successfully!',
                'substitution' => $substitution->load(['originalTeacher', 'substituteTeacher', 'studentClass'])
            ]);
        }

        return redirect()->route('admin.emergency')->with('success', 'Substitute teacher assigned successfully!');
    }

    /**
     * Get all active teacher substitutions
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getActiveSubstitutions(Request $request)
    {
        $substitutions = TeacherSubstitution::with(['originalTeacher', 'substituteTeacher', 'studentClass'])
            ->where('status', 'active')
            ->where('end_date', '>=', now())
            ->get();

        return response()->json([
            'success' => true,
            'substitutions' => $substitutions
        ]);
    }

    /**
     * End a teacher substitution
     * 
     * @param Request $request
     * @param int $substitutionId
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function endSubstitution(Request $request, $substitutionId)
    {
        $substitution = TeacherSubstitution::find($substitutionId);
        if (!$substitution) {
            if ($request->expectsJson()) {
                return response()->json(['success' => false, 'message' => 'Substitution not found'], 404);
            }
            abort(404);
        }

        $substitution->status = 'completed';
        $substitution->end_date = now();
        $substitution->save();

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Substitution ended successfully!',
                'substitution' => $substitution->fresh()
            ]);
        }

        return redirect()->route('admin.emergency')->with('success', 'Substitution ended!');
    }

    // --------------------------
    // HELPER METHODS
    // --------------------------

    /**
     * Get all teachers for assignment dropdowns
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function getTeachersList()
    {
        $teachers = User::where('role', 'teacher')
            ->select('user_id', 'first_name', 'last_name', 'email')
            ->get()
            ->map(function ($teacher) {
                return [
                    'id' => $teacher->user_id,
                    'name' => $teacher->first_name . ' ' . $teacher->last_name,
                    'email' => $teacher->email,
                ];
            });

        return response()->json([
            'success' => true,
            'teachers' => $teachers
        ]);
    }

    /**
     * Get all classes with their details
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function getClassesList()
    {
        $classes = StudentClass::with('teacher')
            ->get()
            ->map(function ($class) {
                return [
                    'id' => $class->class_id,
                    'name' => $class->class_name,
                    'teacher' => $class->teacher ? $class->teacher->first_name . ' ' . $class->teacher->last_name : 'Unassigned',
                    'capacity' => $class->capacity,
                    'enrollment' => $class->current_enrollment,
                    'available_spots' => $class->capacity - $class->current_enrollment,
                    'status' => $class->status,
                ];
            });

        return response()->json([
            'success' => true,
            'classes' => $classes
        ]);
    }

    /**
     * Get pending teacher requests
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function getPendingTeacherRequests()
    {
        $requests = TeacherRequest::with('user')
            ->where('status', 'pending')
            ->orderBy('request_date', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'requests' => $requests
        ]);
    }

    // --------------------------
    // ADMIN ASSIGNMENTS MANAGEMENT
    // --------------------------

    /**
     * Show all assignments uploaded by teachers (Admin View)
     * 
     * @return \Illuminate\View\View
     */
    public function assignments()
    {
        $assignments = Assignment::with([
            'teacher', 
            'studentClass', 
            'level',
            'submissions.student.user',
            'submissions.grade',
            'checkedByAdmin'
        ])
        ->latest()
        ->get();

        // Calculate statistics for each assignment
        foreach ($assignments as $assignment) {
            $assignment->total_students = $assignment->studentClass ? $assignment->studentClass->students()->count() : 0;
            $assignment->submitted_count = $assignment->submissions->count();
            $assignment->graded_count = $assignment->submissions->filter(function($submission) {
                return $submission->grade !== null;
            })->count();
            $assignment->pending_grading = $assignment->submissions->filter(function($submission) {
                return $submission->grade === null;
            })->count();
        }

        // Calculate overall statistics
        $totalAssignments = $assignments->count();
        $totalSubmissions = $assignments->sum('submitted_count');
        $totalGraded = $assignments->sum('graded_count');
        $totalPending = $assignments->sum('pending_grading');
        $totalStudents = $assignments->sum('total_students');
        
        // Calculate average grades
        $allGrades = \App\Models\Grade::whereHas('assignmentSubmission')
            ->get();
        $averageGrade = $allGrades->where('percentage', '!=', null)->avg('percentage') ?? 0;
        
        // Submission rate
        $submissionRate = $totalStudents > 0 ? round(($totalSubmissions / $totalStudents) * 100, 1) : 0;
        $gradingRate = $totalSubmissions > 0 ? round(($totalGraded / $totalSubmissions) * 100, 1) : 0;

        // Calculate statistics per class
        $classes = \App\Models\StudentClass::with('teacher')->get();
        $classStats = [];
        
        foreach ($classes as $class) {
            $classAssignments = $assignments->where('class_id', $class->class_id);
            $classTotalAssignments = $classAssignments->count();
            $classTotalSubmissions = $classAssignments->sum('submitted_count');
            $classTotalGraded = $classAssignments->sum('graded_count');
            $classTotalPending = $classAssignments->sum('pending_grading');
            $classTotalStudents = $classAssignments->sum('total_students');
            
            // Get grades for this class
            $classSubmissionIds = \App\Models\AssignmentSubmission::whereIn('assignment_id', $classAssignments->pluck('assignment_id'))
                ->pluck('submission_id');
            $classGrades = \App\Models\Grade::whereIn('assignment_submission_id', $classSubmissionIds)->get();
            $classAverageGrade = $classGrades->where('percentage', '!=', null)->avg('percentage') ?? 0;
            
            $classSubmissionRate = $classTotalStudents > 0 ? round(($classTotalSubmissions / $classTotalStudents) * 100, 1) : 0;
            $classGradingRate = $classTotalSubmissions > 0 ? round(($classTotalGraded / $classTotalSubmissions) * 100, 1) : 0;
            
            if ($classTotalAssignments > 0) {
                $classStats[] = [
                    'class_id' => $class->class_id,
                    'class_name' => $class->class_name,
                    'teacher' => $class->teacher ? $class->teacher->first_name . ' ' . $class->teacher->last_name : 'Unassigned',
                    'total_assignments' => $classTotalAssignments,
                    'total_submissions' => $classTotalSubmissions,
                    'total_graded' => $classTotalGraded,
                    'total_pending' => $classTotalPending,
                    'total_students' => $classTotalStudents,
                    'average_grade' => $classAverageGrade,
                    'submission_rate' => $classSubmissionRate,
                    'grading_rate' => $classGradingRate,
                ];
            }
        }

        return view('admin.assignments', compact(
            'assignments',
            'totalAssignments',
            'totalSubmissions',
            'totalGraded',
            'totalPending',
            'totalStudents',
            'averageGrade',
            'submissionRate',
            'gradingRate',
            'classStats'
        ));
    }

    /**
     * Add admin comment to an assignment
     * 
     * @param Request $request
     * @param int $assignmentId
     * @return \Illuminate\Http\RedirectResponse
     */
    public function addAssignmentComment(Request $request, $assignmentId)
    {
        $assignment = Assignment::findOrFail($assignmentId);
        
        $request->validate([
            'admin_comment' => 'required|string|max:5000',
        ]);

        $assignment->admin_comment = $request->admin_comment;
        $assignment->checked_by_admin_id = Auth::id();
        $assignment->save();

        return redirect()->route('admin.assignments')->with('success', 'Comment added successfully!');
    }

    // --------------------------
    // ADMIN QUIZZES MANAGEMENT
    // --------------------------

    /**
     * Show all quizzes created by teachers (Admin View)
     * 
     * @return \Illuminate\View\View
     */
    public function quizzes()
    {
        $quizzes = Quiz::with([
            'teacher', 
            'studentClass', 
            'level',
            'attempts.student.user',
            'attempts.grade',
            'checkedByAdmin',
            'questions.options'
        ])
        ->latest()
        ->get();

        // Calculate statistics for each quiz
        // Always use 60% as the passing score for all quizzes (standardized)
        $passingScorePercent = 60;
        foreach ($quizzes as $quiz) {
            $quiz->total_students = $quiz->studentClass ? $quiz->studentClass->students()->count() : 0;
            $quiz->submissions_count = $quiz->attempts->where('status', 'completed')->count();
            
            $quiz->passed_count = $quiz->attempts->filter(function($attempt) use ($passingScorePercent) {
                return $attempt->status === 'completed' && $attempt->score !== null && $attempt->score >= $passingScorePercent;
            })->count();
            $quiz->failed_count = $quiz->attempts->filter(function($attempt) use ($passingScorePercent) {
                return $attempt->status === 'completed' && $attempt->score !== null && $attempt->score < $passingScorePercent;
            })->count();
            
            // Calculate average score for this quiz (use percentage from grade if available, otherwise calculate)
            $scorePercentages = [];
            $completedAttempts = $quiz->attempts->where('status', 'completed')->where('score', '!=', null);
            foreach ($completedAttempts as $attempt) {
                // Try to use grade percentage first
                if ($attempt->grade && $attempt->grade->percentage !== null) {
                    $scorePercentages[] = $attempt->grade->percentage;
                } elseif ($quiz->max_score && $quiz->max_score > 0) {
                    // Calculate percentage from score and max_score
                    $percentage = ($attempt->score / $quiz->max_score) * 100;
                    // If result is > 100, score might already be a percentage
                    if ($percentage > 100 && $attempt->score <= 100) {
                        $percentage = $attempt->score;
                    }
                    $scorePercentages[] = min($percentage, 100); // Cap at 100%
                }
            }
            $quiz->average_score_percentage = count($scorePercentages) > 0 ? array_sum($scorePercentages) / count($scorePercentages) : 0;
        }

        // Calculate overall statistics
        $totalQuizzes = $quizzes->count();
        $totalSubmissions = $quizzes->sum('submissions_count');
        $totalPassed = $quizzes->sum('passed_count');
        $totalFailed = $quizzes->sum('failed_count');
        $totalStudents = $quizzes->sum('total_students');
        
        // Calculate average score percentage correctly (weighted by actual submissions)
        $allSubmissionsPercentages = [];
        foreach ($quizzes as $quiz) {
            $completedAttempts = $quiz->attempts->where('status', 'completed')->where('score', '!=', null);
            foreach ($completedAttempts as $attempt) {
                $percentage = null;
                
                // Try to use grade percentage first (most reliable)
                if ($attempt->grade && $attempt->grade->percentage !== null) {
                    $percentage = $attempt->grade->percentage;
                } elseif ($quiz->max_score && $quiz->max_score > 0) {
                    // Calculate percentage from score and max_score
                    $calculatedPercentage = ($attempt->score / $quiz->max_score) * 100;
                    // If calculated is > 100 and score is <= 100, score might already be a percentage
                    if ($calculatedPercentage > 100 && $attempt->score <= 100) {
                        $percentage = $attempt->score;
                    } else {
                        $percentage = min($calculatedPercentage, 100); // Cap at 100%
                    }
                }
                
                if ($percentage !== null) {
                    $allSubmissionsPercentages[] = $percentage;
                }
            }
        }
        $averageScorePercentage = count($allSubmissionsPercentages) > 0 ? array_sum($allSubmissionsPercentages) / count($allSubmissionsPercentages) : 0;
        
        // Completion rate (submissions / total students)
        $completionRate = $totalStudents > 0 ? round(($totalSubmissions / $totalStudents) * 100, 1) : 0;
        $passRate = $totalSubmissions > 0 ? round(($totalPassed / $totalSubmissions) * 100, 1) : 0;

        // Calculate statistics per class
        $classes = \App\Models\StudentClass::with('teacher')->get();
        $classStats = [];
        
        foreach ($classes as $class) {
            $classQuizzes = $quizzes->where('class_id', $class->class_id);
            $classTotalQuizzes = $classQuizzes->count();
            $classTotalSubmissions = $classQuizzes->sum('submissions_count');
            $classTotalPassed = $classQuizzes->sum('passed_count');
            $classTotalFailed = $classQuizzes->sum('failed_count');
            $classTotalStudents = $class->students()->count();
            
            // Calculate average percentage for this class correctly (weighted by actual submissions)
            $classSubmissionsPercentages = [];
            foreach ($classQuizzes as $quiz) {
                $completedAttempts = $quiz->attempts->where('status', 'completed')->where('score', '!=', null);
                foreach ($completedAttempts as $attempt) {
                    $percentage = null;
                    
                    // Try to use grade percentage first (most reliable)
                    if ($attempt->grade && $attempt->grade->percentage !== null) {
                        $percentage = $attempt->grade->percentage;
                    } elseif ($quiz->max_score && $quiz->max_score > 0) {
                        // Calculate percentage from score and max_score
                        $calculatedPercentage = ($attempt->score / $quiz->max_score) * 100;
                        // If calculated is > 100 and score is <= 100, score might already be a percentage
                        if ($calculatedPercentage > 100 && $attempt->score <= 100) {
                            $percentage = $attempt->score;
                        } else {
                            $percentage = min($calculatedPercentage, 100); // Cap at 100%
                        }
                    }
                    
                    if ($percentage !== null) {
                        $classSubmissionsPercentages[] = $percentage;
                    }
                }
            }
            $classAvgPercentage = count($classSubmissionsPercentages) > 0 ? array_sum($classSubmissionsPercentages) / count($classSubmissionsPercentages) : 0;
            
            // Calculate completion rate correctly for class with multiple quizzes
            // Average completion rate across all quizzes in the class
            $quizCompletionRates = [];
            foreach ($classQuizzes as $quiz) {
                $quizTotalStudents = $quiz->studentClass ? $quiz->studentClass->students()->count() : 0;
                $quizSubmissions = $quiz->attempts->where('status', 'completed')->count();
                if ($quizTotalStudents > 0) {
                    $quizCompletionRates[] = ($quizSubmissions / $quizTotalStudents) * 100;
                }
            }
            $classCompletionRate = count($quizCompletionRates) > 0 ? round(array_sum($quizCompletionRates) / count($quizCompletionRates), 1) : 0;
            
            $classPassRate = $classTotalSubmissions > 0 ? round(($classTotalPassed / $classTotalSubmissions) * 100, 1) : 0;
            
            if ($classTotalQuizzes > 0) {
                $classStats[] = [
                    'class_id' => $class->class_id,
                    'class_name' => $class->class_name,
                    'teacher' => $class->teacher ? $class->teacher->first_name . ' ' . $class->teacher->last_name : 'Unassigned',
                    'total_quizzes' => $classTotalQuizzes,
                    'total_submissions' => $classTotalSubmissions,
                    'total_passed' => $classTotalPassed,
                    'total_failed' => $classTotalFailed,
                    'total_students' => $classTotalStudents,
                    'average_percentage' => $classAvgPercentage,
                    'completion_rate' => $classCompletionRate,
                    'pass_rate' => $classPassRate,
                ];
            }
        }

        return view('admin.quizzes', compact(
            'quizzes',
            'totalQuizzes',
            'totalSubmissions',
            'totalPassed',
            'totalFailed',
            'totalStudents',
            'averageScorePercentage',
            'completionRate',
            'passRate',
            'classStats'
        ));
    }

    // --------------------------
    // ADMIN GAMES MANAGEMENT
    // --------------------------

    /**
     * Show all games with statistics and filtering (Admin View)
     * 
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\View\View
     */
    public function games(Request $request)
    {
        // Get all classes for filtering
        $allClasses = StudentClass::with('teacher')->get();
        $selectedClassId = $request->input('class_id');
        
        // Get all games with their relationships
        // Exclude 'clock' and 'closet_game' type games as they have been removed/dropped
        $gamesQuery = Game::with(['lesson.level', 'studentProgresses.student.user'])
            ->where('game_type', '!=', 'clock')
            ->where('game_type', '!=', 'closet_game')
            ->whereNotNull('game_type'); // Also exclude null game types
        
        // Filter by class if selected
        if ($selectedClassId) {
            $lessonIds = ClassLessonVisibility::where('class_id', $selectedClassId)
                ->pluck('lesson_id')
                ->toArray();
            $gamesQuery->whereIn('lesson_id', $lessonIds);
        }
        
        $games = $gamesQuery->get();
        
        // Get additional game types - only valid games with data
        $wordSearchGames = WordSearchGame::with(['lesson.level', 'game.studentProgresses'])
            ->whereHas('lesson')
            ->get()
            ->filter(function($game) {
                return $game->words && (is_array($game->words) ? count($game->words) > 0 : true);
            });
        
        $matchingPairsGames = MatchingPairsGame::with(['lesson.level', 'game.studentProgresses', 'pairs'])
            ->whereHas('lesson')
            ->get()
            ->filter(function($game) {
                return $game->pairs && $game->pairs->count() > 0;
            });
        
        // Clock games have been removed/dropped - no longer fetching them
        
        // Filter additional games by class if selected
        if ($selectedClassId) {
            $lessonIds = ClassLessonVisibility::where('class_id', $selectedClassId)
                ->pluck('lesson_id')
                ->toArray();
            $wordSearchGames = $wordSearchGames->whereIn('lesson_id', $lessonIds);
            $matchingPairsGames = $matchingPairsGames->whereIn('lesson_id', $lessonIds);
        }
        
        // Calculate overall statistics
        $totalGames = $games->count() + $wordSearchGames->count() + $matchingPairsGames->count();
        
        // Count by game type
        $gameTypeCounts = [
            'word_search' => $wordSearchGames->count(),
            'matching_pairs' => $matchingPairsGames->count(),
            'word_clock_arrangement' => $games->where('game_type', 'word_clock_arrangement')->count(),
            'scrambled_clocks' => $games->where('game_type', 'scrambled_clocks')->count(),
            'scramble' => $games->where('game_type', 'scramble')->count(),
            'mcq' => $games->where('game_type', 'mcq')->count(),
        ];
        
        // Calculate completion statistics
        $allProgresses = StudentGameProgress::with(['game', 'student.user'])->get();
        $totalProgresses = $allProgresses->count();
        $completedProgresses = $allProgresses->where('status', 'completed')->count();
        $inProgressProgresses = $allProgresses->where('status', 'in_progress')->count();
        $notStartedProgresses = $allProgresses->where('status', 'not_started')->count();
        
        $completionRate = $totalProgresses > 0 ? round(($completedProgresses / $totalProgresses) * 100, 1) : 0;
        $averageScore = $allProgresses->where('score', '!=', null)->avg('score') ?? 0;
        $averageAttempts = $allProgresses->where('attempts', '>', 0)->avg('attempts') ?? 0;
        
        // Calculate statistics per class
        $classStats = [];
        foreach ($allClasses as $class) {
            $classLessonIds = ClassLessonVisibility::where('class_id', $class->class_id)
                ->pluck('lesson_id')
                ->toArray();
            
            $classGames = $games->whereIn('lesson_id', $classLessonIds);
            $classWordSearchGames = $wordSearchGames->whereIn('lesson_id', $classLessonIds);
            $classMatchingPairsGames = $matchingPairsGames->whereIn('lesson_id', $classLessonIds);
            
            $classTotalGames = $classGames->count() + $classWordSearchGames->count() + 
                              $classMatchingPairsGames->count();
            
            // Get student IDs for this class
            $classStudentIds = $class->students()->pluck('student_id')->toArray();
            
            // Get progresses for this class
            $classProgresses = $allProgresses->filter(function($progress) use ($classStudentIds, $classLessonIds) {
                return in_array($progress->student_id, $classStudentIds) && 
                       in_array($progress->game->lesson_id ?? 0, $classLessonIds);
            });
            
            $classTotalProgresses = $classProgresses->count();
            $classCompleted = $classProgresses->where('status', 'completed')->count();
            $classAverageScore = $classProgresses->where('score', '!=', null)->avg('score') ?? 0;
            $classCompletionRate = $classTotalProgresses > 0 ? round(($classCompleted / $classTotalProgresses) * 100, 1) : 0;
            
            if ($classTotalGames > 0) {
                $classStats[] = [
                    'class_id' => $class->class_id,
                    'class_name' => $class->class_name,
                    'teacher' => $class->teacher ? $class->teacher->first_name . ' ' . $class->teacher->last_name : 'Unassigned',
                    'total_games' => $classTotalGames,
                    'total_progresses' => $classTotalProgresses,
                    'completed' => $classCompleted,
                    'average_score' => round($classAverageScore, 1),
                    'completion_rate' => $classCompletionRate,
                ];
            }
        }
        
        // Prepare games data for display
        $gamesData = [];
        
        // Add regular games
        foreach ($games as $game) {
            // Skip if no lesson
            if (!$game->lesson) {
                continue;
            }
            
            // Skip closet_game type as it has been removed/dropped
            if ($game->game_type === 'closet_game') {
                continue;
            }
            
            // Skip word_search and matching_pairs games as they're loaded separately from their own tables
            if ($game->game_type === 'word_search' || $game->game_type === 'matching_pairs') {
                continue;
            }
            
            $progresses = $game->studentProgresses;
            
            // Ensure game_data is properly decoded
            $gameData = $game->game_data;
            if (is_string($gameData)) {
                $gameData = json_decode($gameData, true);
                if (json_last_error() !== JSON_ERROR_NONE) {
                    $gameData = null;
                }
            }
            
            $gamesData[] = [
                'game_id' => $game->game_id,
                'lesson_id' => $game->lesson_id,
                'lesson_title' => $game->lesson->title ?? 'N/A',
                'level_name' => ($game->lesson->level && $game->lesson->level->level_name) ? $game->lesson->level->level_name : 'Uncategorized',
                'game_type' => $game->game_type,
                'game_name' => $this->getGameTypeName($game->game_type),
                'created_at' => $game->created_at,
                'total_attempts' => $progresses->count(),
                'completed' => $progresses->where('status', 'completed')->count(),
                'average_score' => round($progresses->where('score', '!=', null)->avg('score') ?? 0, 1),
                'game_data' => $gameData,
            ];
        }
        
        // Add word search games
        foreach ($wordSearchGames as $wsGame) {
            // Skip if no lesson
            if (!$wsGame->lesson) {
                continue;
            }
            
            $progresses = $wsGame->game ? $wsGame->game->studentProgresses : collect();
            $lesson = $wsGame->lesson;
            $gamesData[] = [
                'game_id' => $wsGame->game_id ?? null,
                'lesson_id' => $wsGame->lesson_id,
                'lesson_title' => $lesson ? $lesson->title : 'N/A',
                'level_name' => ($lesson && $lesson->level && $lesson->level->level_name) ? $lesson->level->level_name : 'Uncategorized',
                'game_type' => 'word_search',
                'game_name' => 'Word Search',
                'created_at' => $wsGame->created_at ?? now(),
                'total_attempts' => $progresses->count(),
                'completed' => $progresses->where('status', 'completed')->count(),
                'average_score' => round($progresses->where('score', '!=', null)->avg('score') ?? 0, 1),
                'game_data' => [
                    'title' => $wsGame->title ?? '',
                    'words' => is_array($wsGame->words) ? $wsGame->words : (is_string($wsGame->words) ? json_decode($wsGame->words, true) : []),
                    'grid_size' => $wsGame->grid_size ?? 10,
                    'grid_data' => is_array($wsGame->grid_data) || is_object($wsGame->grid_data) ? $wsGame->grid_data : (is_string($wsGame->grid_data) ? json_decode($wsGame->grid_data, true) : null),
                ],
            ];
        }
        
        // Add matching pairs games
        foreach ($matchingPairsGames as $mpGame) {
            // Skip if no lesson
            if (!$mpGame->lesson) {
                continue;
            }
            
            $progresses = $mpGame->game ? $mpGame->game->studentProgresses : collect();
            $lesson = $mpGame->lesson;
            $gamesData[] = [
                'game_id' => $mpGame->game_id ?? null,
                'lesson_id' => $mpGame->lesson_id,
                'lesson_title' => $lesson ? $lesson->title : 'N/A',
                'level_name' => ($lesson && $lesson->level && $lesson->level->level_name) ? $lesson->level->level_name : 'Uncategorized',
                'game_type' => 'matching_pairs',
                'game_name' => 'Matching Pairs',
                'created_at' => $mpGame->created_at ?? now(),
                'total_attempts' => $progresses->count(),
                'completed' => $progresses->where('status', 'completed')->count(),
                'average_score' => round($progresses->where('score', '!=', null)->avg('score') ?? 0, 1),
                'game_data' => [
                    'title' => $mpGame->title ?? '',
                    'pairs' => $mpGame->pairs->map(function($pair) {
                        return [
                            'left_item_text' => $pair->left_item_text,
                            'left_item_image' => $pair->left_item_image,
                            'right_item_text' => $pair->right_item_text,
                            'right_item_image' => $pair->right_item_image,
                        ];
                    })->toArray(),
                ],
            ];
        }
        
        // Clock games have been removed/dropped - no longer adding them to gamesData
        
        // Remove duplicates based on game_id and game_type combination
        $uniqueGames = [];
        $seenKeys = [];
        foreach ($gamesData as $game) {
            // Create a unique key based on game_id and game_type, or lesson_id and game_type if game_id is null
            $uniqueKey = ($game['game_id'] ?? 'null') . '_' . $game['game_type'] . '_' . $game['lesson_id'];
            
            // Only add if we haven't seen this combination before
            if (!isset($seenKeys[$uniqueKey])) {
                $seenKeys[$uniqueKey] = true;
                $uniqueGames[] = $game;
            }
        }
        $gamesData = $uniqueGames;
        
        // Organize games by level and lesson
        $organizedGames = [];
        if (!empty($gamesData)) {
            foreach ($gamesData as $game) {
                $levelName = $game['level_name'] ?? 'Uncategorized';
                $lessonId = $game['lesson_id'] ?? null;
                $lessonTitle = $game['lesson_title'] ?? 'Unknown Lesson';
                
                // Skip if no lesson_id
                if (!$lessonId) {
                    continue;
                }
                
                // Initialize level if not exists
                if (!isset($organizedGames[$levelName])) {
                    $organizedGames[$levelName] = [];
                }
                
                // Initialize lesson if not exists
                if (!isset($organizedGames[$levelName][$lessonId])) {
                    $organizedGames[$levelName][$lessonId] = [
                        'lesson_id' => $lessonId,
                        'lesson_title' => $lessonTitle,
                        'level_name' => $levelName,
                        'games' => []
                    ];
                }
                
                // Add game to lesson
                $organizedGames[$levelName][$lessonId]['games'][] = $game;
            }
        }
        
        // Sort games within each lesson by created_at desc
        foreach ($organizedGames as $levelName => $lessons) {
            foreach ($lessons as $lessonId => $lessonData) {
                usort($organizedGames[$levelName][$lessonId]['games'], function($a, $b) {
                    return strtotime($b['created_at']) - strtotime($a['created_at']);
                });
            }
        }
        
        // Sort lessons within each level by lesson title
        foreach ($organizedGames as $levelName => $lessons) {
            uasort($organizedGames[$levelName], function($a, $b) {
                return strcmp($a['lesson_title'], $b['lesson_title']);
            });
        }
        
        // Sort levels alphabetically
        ksort($organizedGames);
        
        // Flatten gamesData for JavaScript (maintain backward compatibility)
        $flatGamesData = [];
        foreach ($organizedGames as $levelName => $lessons) {
            foreach ($lessons as $lessonId => $lessonData) {
                foreach ($lessonData['games'] as $game) {
                    $flatGamesData[] = $game;
                }
            }
        }
        
        return view('admin.games', compact(
            'gamesData',
            'flatGamesData',
            'organizedGames',
            'allClasses',
            'selectedClassId',
            'totalGames',
            'gameTypeCounts',
            'totalProgresses',
            'completedProgresses',
            'inProgressProgresses',
            'notStartedProgresses',
            'completionRate',
            'averageScore',
            'averageAttempts',
            'classStats'
        ));
    }
    
    // --------------------------
    // LIGHTWEIGHT ACTIVITIES SUMMARY PAGES
    // --------------------------
    
    /**
     * Show lightweight assignments summary page (aggregated statistics only)
     */
    public function activitiesAssignments()
    {
        // Get aggregated statistics only - no detailed content
        $totalAssignments = Assignment::count();
        $totalSubmissions = \App\Models\AssignmentSubmission::count();
        $totalGraded = \App\Models\Grade::whereNotNull('assignment_submission_id')->count();
        $totalPending = $totalSubmissions - $totalGraded;
        
        // Calculate average grade
        $assignmentGrades = \App\Models\Grade::whereNotNull('assignment_submission_id')->get();
        $averageGrade = $assignmentGrades->where('percentage', '!=', null)->avg('percentage') ?? 0;
        
        // Calculate statistics per class (aggregated)
        $classes = StudentClass::with('teacher')->get();
        $classStats = [];
        foreach ($classes as $class) {
            $classAssignments = Assignment::where('class_id', $class->class_id)->get();
            $classTotalAssignments = $classAssignments->count();
            
            if ($classTotalAssignments > 0) {
                $assignmentIds = $classAssignments->pluck('assignment_id');
                $classSubmissions = \App\Models\AssignmentSubmission::whereIn('assignment_id', $assignmentIds)->count();
                $classGraded = \App\Models\Grade::whereIn('assignment_submission_id', 
                    \App\Models\AssignmentSubmission::whereIn('assignment_id', $assignmentIds)->pluck('submission_id')
                )->count();
                $classStudents = $class->students()->count();
                
                $classGrades = \App\Models\Grade::whereIn('assignment_submission_id',
                    \App\Models\AssignmentSubmission::whereIn('assignment_id', $assignmentIds)->pluck('submission_id')
                )->get();
                $classAverageGrade = $classGrades->where('percentage', '!=', null)->avg('percentage') ?? 0;
                
                $classStats[] = [
                    'class_name' => $class->class_name,
                    'teacher' => $class->teacher ? $class->teacher->first_name . ' ' . $class->teacher->last_name : 'Unassigned',
                    'total_assignments' => $classTotalAssignments,
                    'total_submissions' => $classSubmissions,
                    'total_graded' => $classGraded,
                    'total_students' => $classStudents,
                    'average_grade' => round($classAverageGrade, 1),
                    'submission_rate' => $classStudents > 0 ? round(($classSubmissions / max($classTotalAssignments * $classStudents, 1)) * 100, 1) : 0,
                ];
            }
        }
        
        return view('admin.activities.assignments', compact(
            'totalAssignments',
            'totalSubmissions',
            'totalGraded',
            'totalPending',
            'averageGrade',
            'classStats'
        ));
    }
    
    /**
     * Show lightweight quizzes summary page (aggregated statistics only)
     */
    public function activitiesQuizzes()
    {
        // Get aggregated statistics only - no questions or student answers
        $totalQuizzes = Quiz::count();
        $totalAttempts = \App\Models\QuizAttempt::where('status', 'completed')->count();
        // Always use 60% as the passing score for all quizzes (standardized)
        $passingScorePercent = 60;
        $totalPassed = Quiz::with('attempts')->get()->sum(function($quiz) use ($passingScorePercent) {
            return $quiz->attempts->filter(function($attempt) use ($passingScorePercent) {
                return $attempt->status === 'completed' && $attempt->score !== null && $attempt->score >= $passingScorePercent;
            })->count();
        });
        $totalFailed = $totalAttempts - $totalPassed;
        
        // Calculate average score (from grades only - no question details)
        $quizGrades = \App\Models\Grade::whereNotNull('quiz_attempt_id')->get();
        $averageScore = $quizGrades->where('percentage', '!=', null)->avg('percentage') ?? 0;
        
        // Calculate statistics per class (aggregated)
        $classes = StudentClass::with('teacher')->get();
        $classStats = [];
        foreach ($classes as $class) {
            $classQuizzes = Quiz::where('class_id', $class->class_id)->get();
            $classTotalQuizzes = $classQuizzes->count();
            
            if ($classTotalQuizzes > 0) {
                $quizIds = $classQuizzes->pluck('quiz_id');
                $classAttempts = \App\Models\QuizAttempt::whereIn('quiz_id', $quizIds)
                    ->where('status', 'completed')
                    ->count();
                // Always use 60% as the passing score for all quizzes (standardized)
                $passingScorePercent = 60;
                $classPassed = \App\Models\QuizAttempt::whereIn('quiz_id', $quizIds)
                    ->where('status', 'completed')
                    ->get()
                    ->filter(function($attempt) use ($passingScorePercent) {
                        return $attempt->score !== null && $attempt->score >= $passingScorePercent;
                    })
                    ->count();
                
                $classGrades = \App\Models\Grade::whereIn('quiz_attempt_id',
                    \App\Models\QuizAttempt::whereIn('quiz_id', $quizIds)->pluck('attempt_id')
                )->get();
                $classAverageScore = $classGrades->where('percentage', '!=', null)->avg('percentage') ?? 0;
                
                $classStats[] = [
                    'class_name' => $class->class_name,
                    'teacher' => $class->teacher ? $class->teacher->first_name . ' ' . $class->teacher->last_name : 'Unassigned',
                    'total_quizzes' => $classTotalQuizzes,
                    'total_attempts' => $classAttempts,
                    'total_passed' => $classPassed,
                    'average_score' => round($classAverageScore, 1),
                    'pass_rate' => $classAttempts > 0 ? round(($classPassed / $classAttempts) * 100, 1) : 0,
                ];
            }
        }
        
        return view('admin.activities.quizzes', compact(
            'totalQuizzes',
            'totalAttempts',
            'totalPassed',
            'totalFailed',
            'averageScore',
            'classStats'
        ));
    }
    
    /**
     * Show lightweight games summary page (aggregated statistics only)
     */
    public function activitiesGames()
    {
        // Get aggregated statistics only - no game data details
        $regularGames = Game::whereNotNull('game_type')
            ->where('game_type', '!=', 'clock')
            ->where('game_type', '!=', 'closet_game')
            ->count();
        $wordSearchGames = WordSearchGame::count();
        $matchingPairsGames = MatchingPairsGame::count();
        $totalGames = $regularGames + $wordSearchGames + $matchingPairsGames;
        
        $totalProgresses = StudentGameProgress::count();
        $completedProgresses = StudentGameProgress::where('status', 'completed')->count();
        $averageScore = StudentGameProgress::where('score', '!=', null)->avg('score') ?? 0;
        $completionRate = $totalProgresses > 0 ? round(($completedProgresses / $totalProgresses) * 100, 1) : 0;
        
        // Count by game type (aggregated)
        $gameTypeCounts = [
            'word_search' => $wordSearchGames,
            'matching_pairs' => $matchingPairsGames,
            'word_clock_arrangement' => Game::where('game_type', 'word_clock_arrangement')->count(),
            'scrambled_clocks' => Game::where('game_type', 'scrambled_clocks')->count(),
            'scramble' => Game::where('game_type', 'scramble')->count(),
            'mcq' => Game::where('game_type', 'mcq')->count(),
        ];
        
        // Calculate statistics per class (aggregated)
        $classes = StudentClass::with('teacher')->get();
        $classStats = [];
        foreach ($classes as $class) {
            $classLessonIds = ClassLessonVisibility::where('class_id', $class->class_id)
                ->pluck('lesson_id')
                ->toArray();
            
            $classGameCount = Game::whereIn('lesson_id', $classLessonIds)
                ->whereNotNull('game_type')
                ->where('game_type', '!=', 'clock')
                ->where('game_type', '!=', 'closet_game')
                ->count();
            
            $classWordSearchCount = WordSearchGame::whereIn('lesson_id', $classLessonIds)->count();
            $classMatchingPairsCount = MatchingPairsGame::whereIn('lesson_id', $classLessonIds)->count();
            $classTotalGames = $classGameCount + $classWordSearchCount + $classMatchingPairsCount;
            
            if ($classTotalGames > 0) {
                $classStudentIds = $class->students()->pluck('student_id')->toArray();
                $classProgresses = StudentGameProgress::whereIn('student_id', $classStudentIds)
                    ->whereHas('game', function($q) use ($classLessonIds) {
                        $q->whereIn('lesson_id', $classLessonIds);
                    })
                    ->get();
                
                $classCompleted = $classProgresses->where('status', 'completed')->count();
                $classAverageScore = $classProgresses->where('score', '!=', null)->avg('score') ?? 0;
                $classCompletionRate = $classProgresses->count() > 0 ? round(($classCompleted / $classProgresses->count()) * 100, 1) : 0;
                
                $classStats[] = [
                    'class_name' => $class->class_name,
                    'teacher' => $class->teacher ? $class->teacher->first_name . ' ' . $class->teacher->last_name : 'Unassigned',
                    'total_games' => $classTotalGames,
                    'total_progresses' => $classProgresses->count(),
                    'completed' => $classCompleted,
                    'average_score' => round($classAverageScore, 1),
                    'completion_rate' => $classCompletionRate,
                ];
            }
        }
        
        return view('admin.activities.games', compact(
            'totalGames',
            'totalProgresses',
            'completedProgresses',
            'averageScore',
            'completionRate',
            'gameTypeCounts',
            'classStats'
        ));
    }
    
    /**
     * Get human-readable game type name
     */
    private function getGameTypeName($gameType)
    {
        $names = [
            'word_search' => 'Word Search',
            'matching_pairs' => 'Matching Pairs',
            'word_clock_arrangement' => 'Word Clock Arrangement',
            'scrambled_clocks' => 'Scrambled Clocks',
            'scramble' => 'Scrambled Letters',
            'mcq' => 'Multiple Choice',
            'clock' => 'Clock Game',
        ];
        
        return $names[$gameType] ?? ucfirst(str_replace('_', ' ', $gameType));
    }
}
