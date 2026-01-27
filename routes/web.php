<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Http\Controllers\EmergencyRequestController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\TeacherRequestController;
use App\Http\Controllers\TeacherLessonController;
use App\Http\Controllers\TeacherClassesController;
use App\Http\Controllers\LevelController;
use App\Models\Level;
use App\Models\Student;
use App\Models\ClassLessonVisibility;

// Serve storage files through Laravel FIRST (to handle Windows symlink issues)
// This MUST be registered before any other routes to catch storage requests
Route::get('/storage/{path}', function (Request $request, $path) {
    // Handle the full request URI to get the complete path with special characters
    $requestUri = $request->server('REQUEST_URI');
    
    // Extract the path after /storage/
    if (preg_match('#^/storage/(.+)$#', $requestUri, $matches)) {
        $storagePath = urldecode($matches[1]);
    } else {
        // Fallback to path parameter
        $storagePath = urldecode($path);
    }
    
    // Normalize path separators
    $storagePath = str_replace('\\', '/', $storagePath);
    $storagePath = ltrim($storagePath, '/');
    
    // Check if file exists in storage
    if (\Illuminate\Support\Facades\Storage::disk('public')->exists($storagePath)) {
        $file = \Illuminate\Support\Facades\Storage::disk('public')->get($storagePath);
        $mimeType = \Illuminate\Support\Facades\Storage::disk('public')->mimeType($storagePath);
        
        // For PDFs and images, use inline; for others, let browser decide
        $disposition = in_array(strtolower(pathinfo($storagePath, PATHINFO_EXTENSION)), ['pdf', 'jpg', 'jpeg', 'png', 'gif', 'webp']) 
            ? 'inline' 
            : 'attachment';
        
        return response($file, 200)
            ->header('Content-Type', $mimeType ?: 'application/octet-stream')
            ->header('Content-Disposition', $disposition . '; filename="' . basename($storagePath) . '"')
            ->header('Cache-Control', 'public, max-age=31536000');
    }
    
    abort(404, 'File not found: ' . $storagePath);
})->where('path', '.*')->name('storage.serve');

// Set Dead Time for Assignment
Route::middleware(['auth', 'verified', 'can:isTeacher'])->post('/assignments/{assignment}/dead-time', [App\Http\Controllers\AssignmentController::class, 'setDeadTime'])->name('assignments.setDeadTime');
use App\Http\Controllers\GradeController;
// Teacher grades a submission
Route::middleware(['auth', 'verified', 'can:isTeacher'])->post('/assignments/submission/{submission}/grade', [GradeController::class, 'store'])->name('assignments.submission.grade');
// View a specific assignment submission (teacher)
use App\Models\AssignmentSubmission;
Route::middleware(['auth', 'verified', 'can:isTeacher'])->get('/assignments/submission/{submission}', function ($submissionId) {
    $submission = AssignmentSubmission::with('student.user')->findOrFail($submissionId);
    $studentName = $submission->student && $submission->student->user ? $submission->student->user->first_name . ' ' . $submission->student->user->last_name : '';
    $fileName = basename($submission->submission_file_url);
    $isPdf = strtolower(pathinfo($fileName, PATHINFO_EXTENSION)) === 'pdf';
    return view('assignments.submission-view', compact('submission', 'studentName', 'fileName', 'isPdf'));
})->name('assignments.submission.view');
// Student Game Quiz Routes
use App\Http\Controllers\StudentGameController;
Route::middleware(['auth', 'verified'])->prefix('student')->group(function () {
    Route::get('/games', [StudentGameController::class, 'index'])->name('student.games');
    Route::get('/games/quiz', [StudentGameController::class, 'quiz'])->name('student.games.quiz');
    Route::post('/games/save-score', [StudentGameController::class, 'saveScore'])->name('student.games.saveScore');
    Route::get('/grades', [App\Http\Controllers\StudentGradeController::class, 'index'])->name('student.grades');
});

// Student Rewards Route
use App\Http\Controllers\RewardsController;
Route::middleware(['auth', 'verified'])->get('/rewards', [RewardsController::class, 'index'])->name('student.rewards');

// Student Progress Route
use App\Http\Controllers\StudentProgressController;
Route::middleware(['auth', 'verified'])->get('/progress', [StudentProgressController::class, 'index'])->name('student.progress');

// Clock Game Save Route (Teacher)
use App\Http\Controllers\ClockGameController;
Route::middleware(['auth', 'verified', 'can:isTeacher'])->post('/teacher/games/clock', [ClockGameController::class, 'store'])->name('teacher.games.clock.store');

// Scrambled Clocks Game Save Route (Teacher)
use App\Http\Controllers\ScrambledClocksGameController;
Route::middleware(['auth', 'verified', 'can:isTeacher'])->post('/teacher/games/scrambled-clocks', [ScrambledClocksGameController::class, 'store'])->name('teacher.games.scrambled-clocks.store');

// Word Clock Arrangement Game Save Route (Teacher)
use App\Http\Controllers\WordClockArrangementController;
Route::middleware(['auth', 'verified', 'can:isTeacher'])->post('/teacher/games/word-clock-arrangement', [WordClockArrangementController::class, 'store'])->name('teacher.games.word-clock-arrangement.store');

// Word Search Game Save Route (Teacher)
use App\Http\Controllers\WordSearchGameController;
Route::middleware(['auth', 'verified', 'can:isTeacher'])->post('/teacher/games/word-search', [WordSearchGameController::class, 'store'])->name('teacher.games.word-search.store');

// Matching Pairs Game Save Route (Teacher)
use App\Http\Controllers\MatchingPairsGameController;
Route::middleware(['auth', 'verified', 'can:isTeacher'])->post('/teacher/games/matching-pairs', [MatchingPairsGameController::class, 'store'])->name('teacher.games.matching-pairs.store');


// Profile photo upload
Route::get('/profile/photo', [ProfileController::class, 'showPhotoForm'])->name('profile.photo.form');
Route::post('/profile/photo', [ProfileController::class, 'uploadPhoto'])->name('profile.photo.upload');
// Emergency Absence Request (Teacher)
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/teacher/emergency', [EmergencyRequestController::class, 'create'])->name('teacher.emergency.create')->middleware('can:isTeacher');
    Route::post('/teacher/emergency', [EmergencyRequestController::class, 'store'])->name('teacher.emergency.store')->middleware('can:isTeacher');
    Route::get('/teacher/emergency/{id}/edit', [EmergencyRequestController::class, 'edit'])->name('teacher.emergency.edit')->middleware('can:isTeacher');
    Route::put('/teacher/emergency/{id}', [EmergencyRequestController::class, 'update'])->name('teacher.emergency.update')->middleware('can:isTeacher');
});

// Admin/Teacher Emergency Absence Requests Page (avoid route conflict)

Route::middleware(['auth'])->group(function () {
    Route::get('/admin/emergency-requests', [EmergencyRequestController::class, 'adminIndex'])
        ->name('admin.emergency.index')
        ->middleware('can:viewEmergencyRequests');
    Route::post('/admin/emergency-requests/reassign', [EmergencyRequestController::class, 'reassign'])
        ->name('admin.emergency.reassign')
        ->middleware('can:viewEmergencyRequests');
});

Route::get('/', function () {
    return view('welcome');
});


Route::get('/student/dashboard', function () {
    $user = Auth::user();
    if (!$user) {
        abort(403, 'Unauthorized');
    }
    // Only allow students to access student dashboard
    if ($user->role !== 'student') {
        abort(403, 'Unauthorized. Only students can access this page.');
    }
    
    $student = $user->student;
    $class = $student?->studentClass;
    $upcomingAssignments = [];
    $lessonsCompleted = 0;
    
    // Check if student is in top 3 for day or week
    $isInTop3Day = false;
    $isInTop3Week = false;
    $dayRank = null;
    $weekRank = null;
    $top3DayPosition = null;
    $top3WeekPosition = null;
    
    if ($student) {
        $lessonsCompleted = $student->lessonProgresses()->where('status', 'completed')->count();
        
        // Calculate rankings using the same logic as RewardsController
        $rewardsController = new \App\Http\Controllers\RewardsController();
        
        // Daily rankings
        $todayStart = Carbon::today()->startOfDay();
        $todayEnd = Carbon::today()->endOfDay();
        $studentsWithDailyScores = $rewardsController->getStudentsWithScores($todayStart, $todayEnd);
        $top3Day = $studentsWithDailyScores->where('performance_score', '>', 0)->take(3);
        
        // Check if current student is in top 3 of day
        foreach ($top3Day as $index => $topStudent) {
            if ($topStudent->student_id === $student->student_id) {
                $isInTop3Day = true;
                $top3DayPosition = $index + 1;
                break;
            }
        }
        
        // Get daily rank
        $dailyIndex = $studentsWithDailyScores->search(function($s) use ($student) {
            return $s->student_id === $student->student_id;
        });
        if ($dailyIndex !== false) {
            $dayRank = $dailyIndex + 1;
        }
        
        // Weekly rankings
        $weekStart = Carbon::now()->startOfWeek()->startOfDay();
        $weekEnd = Carbon::now()->endOfWeek()->endOfDay();
        $studentsWithWeeklyScores = $rewardsController->getStudentsWithScores($weekStart, $weekEnd);
        $top3Week = $studentsWithWeeklyScores->where('performance_score', '>', 0)->take(3);
        
        // Check if current student is in top 3 of week
        foreach ($top3Week as $index => $topStudent) {
            if ($topStudent->student_id === $student->student_id) {
                $isInTop3Week = true;
                $top3WeekPosition = $index + 1;
                break;
            }
        }
        
        // Get weekly rank
        $weeklyIndex = $studentsWithWeeklyScores->search(function($s) use ($student) {
            return $s->student_id === $student->student_id;
        });
        if ($weeklyIndex !== false) {
            $weekRank = $weeklyIndex + 1;
        }
    }
    
    if ($class) {
        $upcomingAssignments = \App\Models\Assignment::where('class_id', $class->class_id)
            ->whereDate('due_date', '>=', now())
            ->orderBy('due_date')
            ->take(5)
            ->get();
    }
    
    return view('dashboard', compact(
        'isInTop3Day', 
        'isInTop3Week', 
        'dayRank', 
        'weekRank', 
        'top3DayPosition', 
        'top3WeekPosition'
    ));
})->middleware(['auth', 'verified'])->name('student.dashboard');

// Teacher dashboard route
Route::get('/teacher/dashboard', function () {
    $teacher_id = auth()->id();
    $user = Auth::user();
    
    // Get all levels with their lesson counts (same as lesson management)
    $levels = \App\Models\Level::with('lessons')->orderBy('level_id')->get();
    
    // Prepare data for the chart - start with 0, then add levels
    $levelNames = array_merge(['0'], $levels->pluck('level_name')->toArray());
    $lessonCounts = array_merge([0], $levels->map(function($level) {
        return $level->lessons->count();
    })->toArray());
    
    // Calculate grade distribution (0-100) by number of distinct students
    $gradeRanges = [
        '0-20' => 0,
        '21-40' => 0,
        '41-60' => 0,
        '61-80' => 0,
        '81-100' => 0
    ];
    
    // Get all assignment grades for this teacher with their average percentage per student
    $studentAverages = \App\Models\Grade::where('teacher_id', $teacher_id)
        ->whereNotNull('assignment_submission_id')
        ->whereNotNull('percentage')
        ->selectRaw('student_id, AVG(percentage) as avg_percentage')
        ->groupBy('student_id')
        ->get();
    
    // Count distinct students in each grade range based on their average
    foreach ($studentAverages as $avg) {
        $percentage = $avg->avg_percentage;
        if ($percentage >= 0 && $percentage <= 20) {
            $gradeRanges['0-20']++;
        } elseif ($percentage >= 21 && $percentage <= 40) {
            $gradeRanges['21-40']++;
        } elseif ($percentage >= 41 && $percentage <= 60) {
            $gradeRanges['41-60']++;
        } elseif ($percentage >= 61 && $percentage <= 80) {
            $gradeRanges['61-80']++;
        } elseif ($percentage >= 81 && $percentage <= 100) {
            $gradeRanges['81-100']++;
        }
    }
    
    $gradeRangeLabels = array_keys($gradeRanges);
    $gradeRangeCounts = array_values($gradeRanges);
    
    // Get teacher's classes
    $taughtClasses = \App\Models\StudentClass::where('teacher_id', $user->user_id)
        ->with(['students', 'assignments', 'quizzes'])
        ->get();
    
    // Calculate statistics
    $totalClasses = $taughtClasses->count();
    $totalStudents = $taughtClasses->sum(function($class) {
        return $class->students->count();
    });
    $totalAssignments = $taughtClasses->sum(function($class) {
        return $class->assignments->count();
    });
    $totalQuizzes = $taughtClasses->sum(function($class) {
        return $class->quizzes->count();
    });
    
    // Get grades given by this teacher
    $grades = \App\Models\Grade::where('teacher_id', $user->user_id)->get();
    $averageGrade = $grades->where('percentage', '!=', null)->avg('percentage') ?? 0;
    
    // Calculate pending grading (submissions without grades)
    $pendingGrading = 0;
    foreach ($taughtClasses as $class) {
        $classAssignments = \App\Models\Assignment::where('class_id', $class->class_id)->get();
        foreach ($classAssignments as $assignment) {
            $ungradedSubmissions = \App\Models\AssignmentSubmission::where('assignment_id', $assignment->assignment_id)
                ->whereDoesntHave('grade')
                ->count();
            $pendingGrading += $ungradedSubmissions;
        }
    }
    
    // Prepare data for class distribution chart (students by class)
    $classNames = $taughtClasses->pluck('class_name')->toArray();
    $studentCountsByClass = $taughtClasses->map(function($class) {
        return $class->students->count();
    })->toArray();
    
    // Prepare data for assignments/quizzes by class chart
    $assignmentsByClass = $taughtClasses->map(function($class) {
        return $class->assignments->count();
    })->toArray();
    $quizzesByClass = $taughtClasses->map(function($class) {
        return $class->quizzes->count();
    })->toArray();
    
    // Prepare data for upcoming scheduled activities (next 6 months)
    $activityOverTime = [];
    $activityOverTimeLabels = [];
    $upcomingAssignments = [];
    $upcomingQuizzes = [];
    
    // Get assignment IDs for this teacher's classes
    $classIds = $taughtClasses->pluck('class_id')->toArray();
    
    // Show next 6 months of scheduled activities
    for ($i = 1; $i <= 6; $i++) {
        $date = now()->addMonths($i);
        $monthStart = $date->copy()->startOfMonth();
        $monthEnd = $date->copy()->endOfMonth();
        
        // Upcoming assignments (due dates in this month)
        $monthAssignments = \App\Models\Assignment::whereIn('class_id', $classIds)
            ->whereBetween('due_date', [$monthStart, $monthEnd])
            ->count();
        
        // Upcoming quizzes (due dates in this month)
        $monthQuizzes = \App\Models\Quiz::whereIn('class_id', $classIds)
            ->whereBetween('due_date', [$monthStart, $monthEnd])
            ->count();
        
        $activityOverTimeLabels[] = $date->format('M Y');
        $upcomingAssignments[] = $monthAssignments;
        $upcomingQuizzes[] = $monthQuizzes;
    }
    
    // Get upcoming assignments and quizzes for calendar (next 60 days)
    $upcomingAssignmentsList = collect();
    $upcomingQuizzesList = collect();
    
    if (!empty($classIds)) {
        $upcomingAssignmentsList = \App\Models\Assignment::whereIn('class_id', $classIds)
            ->whereDate('due_date', '>=', now())
            ->whereDate('due_date', '<=', now()->addDays(60))
            ->with('studentClass')
            ->orderBy('due_date')
            ->get()
            ->map(function($assignment) {
                return [
                    'id' => $assignment->assignment_id,
                    'title' => $assignment->title,
                    'date' => $assignment->due_date->format('Y-m-d'),
                    'type' => 'assignment',
                    'class' => $assignment->studentClass->class_name ?? 'N/A'
                ];
            });
        
        $upcomingQuizzesList = \App\Models\Quiz::whereIn('class_id', $classIds)
            ->whereDate('due_date', '>=', now())
            ->whereDate('due_date', '<=', now()->addDays(60))
            ->with('studentClass')
            ->orderBy('due_date')
            ->get()
            ->map(function($quiz) {
                return [
                    'id' => $quiz->quiz_id,
                    'title' => $quiz->title,
                    'date' => $quiz->due_date->format('Y-m-d'),
                    'type' => 'quiz',
                    'class' => $quiz->studentClass->class_name ?? 'N/A'
                ];
            });
    }
    
    // Get schedule events (active events for all teachers)
    $scheduleEventsList = \App\Models\ScheduleEvent::active()
        ->whereDate('event_date', '>=', now())
        ->whereDate('event_date', '<=', now()->addDays(60))
        ->orderBy('event_date')
        ->get()
        ->map(function($event) {
            return [
                'id' => $event->event_id,
                'title' => $event->title,
                'date' => $event->event_date->format('Y-m-d'),
                'type' => 'schedule',
                'class' => 'Schedule',
                'color' => $event->color,
                'description' => $event->description,
                'event_time' => $event->event_time ? \Carbon\Carbon::parse($event->event_time)->format('H:i') : null,
            ];
        });
    
    // Combine all events (assignments, quizzes, and schedule events) and group by date for calendar
    $allEvents = $upcomingAssignmentsList->concat($upcomingQuizzesList)->concat($scheduleEventsList);
    $calendarEvents = $allEvents
        ->groupBy('date')
        ->map(function($events, $date) {
            return [
                'date' => $date,
                'count' => $events->count(),
                'assignments' => $events->where('type', 'assignment')->count(),
                'quizzes' => $events->where('type', 'quiz')->count(),
                'schedules' => $events->where('type', 'schedule')->count(),
                'events' => $events->values(), // All events for tooltip display
                'scheduleEvents' => $events->where('type', 'schedule')->values(), // Schedule events with colors
            ];
        });
    
    return view('teacher.dashboard', compact(
        'levels', 'levelNames', 'lessonCounts', 'gradeRangeLabels', 'gradeRangeCounts',
        'totalClasses', 'totalStudents', 'totalAssignments', 'totalQuizzes', 'averageGrade', 'pendingGrading',
        'classNames', 'studentCountsByClass', 'assignmentsByClass', 'quizzesByClass',
        'activityOverTimeLabels', 'upcomingAssignments', 'upcomingQuizzes',
        'calendarEvents', 'upcomingAssignmentsList', 'upcomingQuizzesList', 'scheduleEventsList'
    ));
})->middleware(['auth', 'verified', 'can:isTeacher'])->name('teacher.dashboard');


use App\Http\Controllers\LessonPublicController;
Route::get('/lessons', [LessonPublicController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('lessons');

// Guest Teacher Request Routes (no authentication required)
Route::get('/become-teacher', [TeacherRequestController::class, 'guestCreate'])->name('teacher-request.guest');
Route::post('/become-teacher', [TeacherRequestController::class, 'guestStore'])->name('teacher-request.guest.store');

// Authenticated Teacher Request Routes
Route::middleware(['auth'])->group(function () {
    Route::get('/teacher-request', [TeacherRequestController::class, 'create'])->name('teacher-request.create');
    Route::post('/teacher-request', [TeacherRequestController::class, 'store'])->name('teacher-request.store');
    Route::get('/teacher-request/status', [TeacherRequestController::class, 'status'])->name('teacher-request.status');
});

// Admin Dashboard Routes
Route::prefix('admin')->middleware(['auth', 'can:isAdmin'])->group(function () {
    // Admin profile
    Route::get('/profile', [AdminController::class, 'profile'])->name('admin.profile');
    Route::post('/profile', [AdminController::class, 'updateProfile'])->name('admin.profile.update');
    // Admin settings
    Route::get('/settings', [AdminController::class, 'settings'])->name('admin.settings');
    Route::post('/settings', [AdminController::class, 'updateSettings'])->name('admin.settings.update');
    Route::post('/mark-students-read', [AdminController::class, 'markStudentsAsRead'])->name('admin.mark-students-read');
    Route::get('/', [AdminController::class, 'index'])->name('admin.dashboard');
    // Level name update
    Route::post('/levels/update-name', [LevelController::class, 'updateName'])->name('admin.levels.updateName');
    // Lessons
    Route::get('/lessons', [AdminController::class, 'lessons'])->name('admin.lessons');
    Route::get('/lessons/create', [AdminController::class, 'createLesson'])->name('admin.lessons.create');
    Route::post('/lessons', [AdminController::class, 'storeLesson'])->name('admin.lessons.store');
    Route::get('/lessons/{id}/edit', [AdminController::class, 'editLesson'])->name('admin.lessons.edit');
    Route::patch('/lessons/{id}', [AdminController::class, 'updateLesson'])->name('admin.lessons.update');
    Route::delete('/lessons/{id}', [AdminController::class, 'deleteLesson'])->name('admin.lessons.delete');
    // Lessons API (Database-backed)
    Route::post('/lessons/add', [AdminController::class, 'addOrCreateLesson'])->name('admin.lessons.add');
    Route::put('/lessons/{id}', [AdminController::class, 'editLessonApi'])->name('admin.lessons.editApi');
    Route::delete('/lessons/{id}/remove', [AdminController::class, 'deleteLessonApi'])->name('admin.lessons.deleteApi');
    
    // Classes
    Route::get('/classes', [AdminController::class, 'classes'])->name('admin.classes');
    Route::get('/classes/create', [AdminController::class, 'createClass'])->name('admin.classes.create');
    Route::post('/classes', [AdminController::class, 'storeClass'])->name('admin.classes.store');
    Route::get('/classes/{id}/edit', [AdminController::class, 'editClass'])->name('admin.classes.edit');
    Route::patch('/classes/{id}', [AdminController::class, 'updateClass'])->name('admin.classes.update');
    Route::delete('/classes/{id}', [AdminController::class, 'deleteClass'])->name('admin.classes.delete');
    
    // Classes API (Database-backed)
    Route::post('/classes/new', [AdminController::class, 'createNewClass'])->name('admin.classes.new');
    Route::put('/classes/{id}', [AdminController::class, 'editClassApi'])->name('admin.classes.editApi');
    Route::post('/classes/{classId}/students/add', [AdminController::class, 'addStudentsToClass'])->name('admin.classes.addStudents');
    Route::post('/classes/{classId}/students/remove', [AdminController::class, 'removeStudentsFromClass'])->name('admin.classes.removeStudents');
    Route::post('/classes/{classId}/teacher', [AdminController::class, 'assignTeacher'])->name('admin.classes.assignTeacher');
    Route::post('/classes/{classId}/capacity', [AdminController::class, 'assignClassCapacity'])->name('admin.classes.capacity');
    
    // Student Management
    Route::get('/students', [AdminController::class, 'students'])->name('admin.students.index');
    Route::get('/students/{id}', [AdminController::class, 'showStudent'])->name('admin.students.show');
    Route::get('/students/export', [AdminController::class, 'exportStudents'])->name('admin.students.export');
    Route::post('/students/{studentId}/change-class', [AdminController::class, 'changeStudentClass'])->name('admin.students.changeClass');
    
    // Teacher Management
    Route::get('/teachers', [AdminController::class, 'teachers'])->name('admin.teachers.index');
    Route::get('/teachers/{id}', [AdminController::class, 'showTeacher'])->name('admin.teachers.show');
    Route::get('/teachers/export', [AdminController::class, 'exportTeachers'])->name('admin.teachers.export');
    
    // Teacher Requests
    Route::get('/requests', [AdminController::class, 'teacherRequests'])->name('admin.requests');
    Route::post('/requests/{id}/approve', [AdminController::class, 'approveRequest'])->name('admin.requests.approve');
    Route::post('/requests/{id}/reject', [AdminController::class, 'rejectRequest'])->name('admin.requests.reject');
    
    // Teacher Requests API (Database-backed)
    Route::post('/teacher-requests/{id}/approve', [AdminController::class, 'approveTeacherRequest'])->name('admin.teacherRequests.approve');
    Route::post('/teacher-requests/{id}/reject', [AdminController::class, 'rejectTeacherRequest'])->name('admin.teacherRequests.reject');
    Route::get('/teacher-requests/pending', [AdminController::class, 'getPendingTeacherRequests'])->name('admin.teacherRequests.pending');
    
    // Emergency Reassignment
    Route::get('/emergency', [AdminController::class, 'emergency'])->name('admin.emergency');
    Route::post('/emergency/{caseId}/reassign', [AdminController::class, 'reassignTeacher'])->name('admin.emergency.reassign');
    // Emergency Requests Approval/Rejection
    Route::post('/emergency-requests/{id}/approve', [AdminController::class, 'approveEmergencyRequest'])->name('admin.emergency.approve');
    Route::post('/emergency-requests/{id}/reject', [AdminController::class, 'rejectEmergencyRequest'])->name('admin.emergency.reject');
    
    // Emergency API (Database-backed)
    Route::post('/emergency/classes/{classId}/reassign', [AdminController::class, 'reassignTeacherEmergency'])->name('admin.emergency.reassignTeacher');
    Route::get('/emergency/substitutions', [AdminController::class, 'getActiveSubstitutions'])->name('admin.emergency.substitutions');
    Route::post('/emergency/substitutions/{substitutionId}/end', [AdminController::class, 'endSubstitution'])->name('admin.emergency.endSubstitution');
    
    // Helper API endpoints
    Route::get('/api/teachers', [AdminController::class, 'getTeachersList'])->name('admin.api.teachers');
    Route::get('/api/classes', [AdminController::class, 'getClassesList'])->name('admin.api.classes');
    
    // Assignments
    Route::get('/assignments', [AdminController::class, 'assignments'])->name('admin.assignments');
    Route::post('/assignments/{assignmentId}/comment', [AdminController::class, 'addAssignmentComment'])->name('admin.assignments.comment');
    
    // Quizzes
    Route::get('/quizzes', [AdminController::class, 'quizzes'])->name('admin.quizzes');
    
    // Games
    Route::get('/games', [AdminController::class, 'games'])->name('admin.games');
    
    // Schedule Management
    Route::prefix('schedule')->group(function () {
        Route::get('/', [\App\Http\Controllers\Admin\ScheduleController::class, 'index'])->name('admin.schedule.index');
        Route::post('/', [\App\Http\Controllers\Admin\ScheduleController::class, 'store'])->name('admin.schedule.store');
        Route::put('/{id}', [\App\Http\Controllers\Admin\ScheduleController::class, 'update'])->name('admin.schedule.update');
        Route::delete('/{id}', [\App\Http\Controllers\Admin\ScheduleController::class, 'destroy'])->name('admin.schedule.destroy');
        Route::post('/{id}/toggle-status', [\App\Http\Controllers\Admin\ScheduleController::class, 'toggleStatus'])->name('admin.schedule.toggle-status');
    });
    
    // Lightweight Activities Summary Pages
    Route::prefix('activities')->group(function () {
        Route::get('/assignments', [AdminController::class, 'activitiesAssignments'])->name('admin.activities.assignments');
        Route::get('/quizzes', [AdminController::class, 'activitiesQuizzes'])->name('admin.activities.quizzes');
        Route::get('/games', [AdminController::class, 'activitiesGames'])->name('admin.activities.games');
    });
});

// Teacher Lesson Management
Route::middleware(['auth', 'verified', 'can:isTeacher'])->prefix('teacher')->group(function () {
    Route::get('/lessons/manage', [TeacherLessonController::class, 'index'])->name('teacher.lessons.manage');
    Route::post('/lessons/{lesson}/unlock', [TeacherLessonController::class, 'unlock'])->name('teacher.lessons.unlock');
    Route::post('/lessons/{lesson}/lock', [TeacherLessonController::class, 'lock'])->name('teacher.lessons.lock');
    Route::get('/lessons/{lesson}/view', [TeacherLessonController::class, 'view'])->name('teacher.lessons.view');
});

// Teacher Classes Management
Route::middleware(['auth', 'verified', 'can:isTeacher'])->prefix('teacher')->group(function () {
    Route::get('/classes', [TeacherClassesController::class, 'index'])->name('teacher.classes');
    Route::get('/grades', [App\Http\Controllers\TeacherGradeController::class, 'index'])->name('teacher.grades');
    Route::get('/progress', [App\Http\Controllers\TeacherProgressController::class, 'index'])->name('teacher.progress');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Levels route
Route::get('/levels', function () {
    $user = auth()->user();
    $student = Student::where('user_id', $user->user_id)->first();
    $studentClassId = $student ? $student->class_id : null;
    $levels = Level::with(['lessons'])->get();
    foreach ($levels as $level) {
        $level->lessons = $level->lessons->filter(function($lesson) use ($studentClassId) {
            $visibility = ClassLessonVisibility::where('lesson_id', $lesson->lesson_id)
                ->where('class_id', $studentClassId)
                ->first();
            return $visibility && $visibility->is_visible;
        })->values();
    }
    return view('levels', compact('levels'));
})->middleware(['auth', 'verified'])->name('levels');

// Student Lesson View Route
Route::get('/lessons/{lesson}/view', function ($lessonId) {
    $lesson = \App\Models\Lesson::findOrFail($lessonId);
    $user = Auth::user();
    $student = $user->student;
    
    // Get student progress for this lesson
    $progress = null;
    $hasGame = false;
    $isVideoCompleted = false;
    $isGameCompleted = false;
    
    if ($student) {
        $progress = \App\Models\StudentLessonProgress::where('student_id', $student->student_id)
            ->where('lesson_id', $lessonId)
            ->first();
        
        // Track student activity - update last_activity_at when they access a lesson
        if ($progress) {
            $progress->last_activity_at = now();
            $progress->save();
        } else {
            // Create progress record if it doesn't exist to track activity
            $progress = \App\Models\StudentLessonProgress::create([
                'student_id' => $student->student_id,
                'lesson_id' => $lessonId,
                'status' => 'not_started',
                'last_activity_at' => now(),
            ]);
        }
        
        $hasGame = \App\Models\Game::where('lesson_id', $lessonId)->exists();
        $isVideoCompleted = $progress && ($progress->video_completed ?? false);
        
        if ($hasGame && $student) {
            $game = \App\Models\Game::where('lesson_id', $lessonId)->first();
            $gameProgress = \App\Models\StudentGameProgress::where('student_id', $student->student_id)
                ->where('game_id', $game->game_id)
                ->where('status', 'completed')
                ->first();
            $isGameCompleted = $gameProgress !== null;
        }
    }
    
    return view('lesson-view', compact('lesson', 'progress', 'hasGame', 'isVideoCompleted', 'isGameCompleted'));
})->middleware(['auth', 'verified'])->name('student.lesson.view');

require __DIR__.'/auth.php';

// Games page for teachers
use App\Http\Controllers\GameWordController;
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/games', [GameWordController::class, 'index'])->name('teacher.games');
    Route::post('/games', [GameWordController::class, 'store'])->name('teacher.games.store');
    Route::post('/games/delete/{id}', [GameWordController::class, 'destroy'])->name('teacher.games.delete');
    Route::post('/games/update/{id}', [GameWordController::class, 'update'])->name('teacher.games.update');
});

use App\Http\Controllers\AssignmentController;
use App\Http\Controllers\GroupChatController;
use App\Http\Controllers\MeetingController;

// Teacher assignments (upload & list)
Route::middleware(['auth', 'verified', 'can:isTeacher'])->group(function () {
    Route::get('/assignments', [AssignmentController::class, 'index'])->name('assignments.index');
    Route::post('/assignments', [AssignmentController::class, 'store'])->name('assignments.store');
});

// Group Chat Routes (Students and Teachers)
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/group-chat/{classId?}', [GroupChatController::class, 'index'])->name('group-chat.index');
    Route::post('/group-chat/messages', [GroupChatController::class, 'store'])->name('group-chat.store');
    Route::get('/group-chat/{classId}/messages', [GroupChatController::class, 'getMessages'])->name('group-chat.messages');
    Route::post('/group-chat/messages/{messageId}/reaction', [GroupChatController::class, 'addReaction'])->name('group-chat.reaction');
    Route::delete('/group-chat/messages/{messageId}', [GroupChatController::class, 'deleteMessage'])->name('group-chat.delete');
});

// Student assignments (view & submission)
use App\Http\Controllers\AssignmentSubmissionController;
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/student/assignment', [AssignmentController::class, 'studentIndex'])->name('student.assignments');
    Route::post('/student/assignment/submit', [AssignmentSubmissionController::class, 'store'])->name('student.assignment.submit');
    Route::delete('/student/assignment/delete/{submission}', [AssignmentSubmissionController::class, 'destroy'])->name('student.assignment.delete');
});

// Teacher quizzes
use App\Http\Controllers\QuizController;
Route::middleware(['auth', 'verified', 'can:isTeacher'])->group(function () {
    Route::get('/quizzes', [QuizController::class, 'index'])->name('quizzes.index');
    Route::get('/quizzes/create', [QuizController::class, 'create'])->name('quizzes.create');
    Route::post('/quizzes', [QuizController::class, 'store'])->name('quizzes.store');
    Route::get('/quizzes/{id}', [QuizController::class, 'show'])->name('quizzes.show');
    Route::get('/quizzes/{id}/edit', [QuizController::class, 'edit'])->name('quizzes.edit');
    Route::put('/quizzes/{id}', [QuizController::class, 'update'])->name('quizzes.update');
    Route::delete('/quizzes/{id}', [QuizController::class, 'destroy'])->name('quizzes.destroy');
    Route::put('/quizzes/{quizId}/questions/{questionId}', [QuizController::class, 'updateQuestion'])->name('quizzes.questions.update');
    Route::delete('/quizzes/{quizId}/questions/{questionId}', [QuizController::class, 'deleteQuestion'])->name('quizzes.questions.delete');
});

// Student quizzes
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/student/quizzes', [QuizController::class, 'studentIndex'])->name('student.quizzes');
    Route::get('/student/quizzes/{id}', [QuizController::class, 'studentShow'])->name('student.quizzes.show');
    Route::post('/student/quizzes/{id}/submit', [QuizController::class, 'submit'])->name('student.quizzes.submit');
    Route::get('/student/quizzes/result/{attemptId}', [QuizController::class, 'result'])->name('student.quizzes.result');
});

// Student marks lesson as completed
use App\Models\StudentLessonProgress;

// Student video progress tracking routes
Route::middleware(['auth', 'verified'])->prefix('api/lessons')->group(function () {
    Route::post('/{lessonId}/video/track', [App\Http\Controllers\StudentProgressController::class, 'trackVideoProgress'])->name('api.lessons.video.track');
    Route::get('/{lessonId}/video/progress', [App\Http\Controllers\StudentProgressController::class, 'getVideoProgress'])->name('api.lessons.video.progress');
});

Route::post('/lessons/{lesson}/complete', function ($lessonId) {
    $user = Auth::user();
    if (!$user) abort(403);
    $student = $user->student;
    if (!$student) abort(403);
    $progress = StudentLessonProgress::firstOrNew([
        'student_id' => $student->student_id,
        'lesson_id' => $lessonId,
    ]);
    $progress->status = 'completed';
    $progress->completed_at = now();
    $progress->last_activity_at = now();
    $progress->save();
    return redirect()->back()->with('success', 'Lesson marked as completed!');
})->middleware(['auth', 'verified'])->name('student.lesson.complete');

// Meeting routes
Route::middleware(['auth', 'verified', 'can:isTeacher'])->group(function () {
    Route::get('/meetings/create', [MeetingController::class, 'create'])->name('meetings.create');
    Route::post('/meetings', [MeetingController::class, 'store'])->name('meetings.store');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/meetings', [MeetingController::class, 'index'])->name('meetings.index');
    Route::get('/meetings/{meeting:meeting_id}', [MeetingController::class, 'show'])->name('meetings.show');
    
    // Student attendance routes
    Route::post('/meetings/{meeting:meeting_id}/join', [MeetingController::class, 'join'])->name('meetings.join');
    Route::post('/meetings/{meeting:meeting_id}/leave', [MeetingController::class, 'leave'])->name('meetings.leave');
});
