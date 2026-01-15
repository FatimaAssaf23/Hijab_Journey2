<?php
namespace App\Http\Controllers;

use App\Models\AdminProfile;
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
use App\Models\Teacher;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
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
        return StudentClass::with(['teacher', 'students'])->get()->map(function ($class) {
            $colorKey = $class->color;
            $colorGradient = self::$classColors[$colorKey] ?? null;
            // Get students for this class, using related User model for name/email
            $studentsList = $class->students->map(function ($student) {
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

        return view('admin.dashboard', [
            'lessonsCount' => Lesson::count(),
            'classesCount' => StudentClass::count(),
            'teacherRequestsCount' => TeacherRequest::where('status', 'pending')->count(),
            'emergencyCasesCount' => TeacherSubstitution::where('status', 'active')->count(),
            'studentsCount' => Student::count(),
            'teachersCount' => User::where('role', 'teacher')->count(),
            'fullClassesCount' => $fullClassesCount,
            'activeClassesCount' => $activeClassesCount,
            'emptyClassesCount' => $emptyClassesCount,
            'unreadRequests' => $unreadRequests,
            'unreadRequestsCount' => $unreadRequests->count(),
            'approvedTeachersCount' => $approvedTeachersCount,
            'rejectedTeachersCount' => $rejectedTeachersCount,
        ]);
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

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'skills' => 'required|integer|min:0',
            'icon' => 'required|string|max:10',
            // Accept either levelId (existing) or new_level_name (new)
            'levelId' => 'nullable|integer',
            'new_level_name' => 'nullable|string|max:255',
            'new_level_number' => 'nullable|integer',
            'new_level_description' => 'nullable|string|max:255',
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

        // Handle file upload (save to public/lessons)
        $contentUrl = null;
        if ($request->hasFile('content_file')) {
            $file = $request->file('content_file');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('lessons'), $filename);
            $contentUrl = '/lessons/' . $filename;
        }

        // Get the next lesson order for this level
        $maxOrder = Lesson::where('level_id', $levelId)->max('lesson_order') ?? 0;

        $lesson = Lesson::create([
            'level_id' => $levelId,
            'title' => $request->title,
            'skills' => (int) $request->skills,
            'icon' => $request->icon,
            'description' => $request->description,
            'content_url' => $contentUrl,
            'duration_minutes' => $request->duration_minutes ? (int) $request->duration_minutes : null,
            'lesson_order' => $maxOrder + 1,
            'is_visible' => true,
            'uploaded_by_admin_id' => auth()->id(),
        ]);

        // Automatically make this lesson visible for all teachers/classes for this level
        $classes = \App\Models\StudentClass::whereHas('levels', function($q) use ($request) {
            $q->where('levels.level_id', (int) $request->levelId);
        })->get();
        $teachers = \App\Models\User::where('role', 'teacher')->get();
        foreach ($classes as $class) {
            foreach ($teachers as $teacher) {
                \App\Models\ClassLessonVisibility::firstOrCreate([
                    'class_id' => $class->class_id,
                    'lesson_id' => $lesson->lesson_id,
                    'teacher_id' => $teacher->user_id,
                ], [
                    'is_visible' => true,
                    'changed_at' => now(),
                ]);
            }
        }

        return redirect()->route('admin.lessons')->with('success', 'Lesson created successfully!');
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
            'content_file' => 'nullable|file|mimes:pdf,mp4,mov,avi|max:51200',
        ]);

        $lesson = Lesson::find($id);
        if (!$lesson) abort(404);

        // Handle file upload (save to public/lessons)
        if ($request->hasFile('content_file')) {
            $file = $request->file('content_file');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('lessons'), $filename);
            $lesson->content_url = '/lessons/' . $filename;
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
            'content_file' => 'nullable|file|mimes:pdf,mp4,mov,avi|max:51200',
        ]);

        // Handle file upload
        if ($request->hasFile('content_file')) {
            // Delete old file if exists
            if ($lesson->content_url && Storage::disk('public')->exists($lesson->content_url)) {
                Storage::disk('public')->delete($lesson->content_url);
            }
            $file = $request->file('content_file');
            $filename = time() . '_' . $file->getClientOriginalName();
            $validated['content_url'] = $file->storeAs('lessons', $filename, 'public');
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
}
