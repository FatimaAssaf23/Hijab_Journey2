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
    // Get real statistics from database
    $totalStudents = \App\Models\Student::count();
    $totalTeachers = \App\Models\User::where('role', 'teacher')->count();
    $totalLessons = \App\Models\Lesson::count();
    
    // Calculate satisfaction/completion rate
    // This represents the average lesson completion rate across all students
    $totalProgressRecords = \App\Models\StudentLessonProgress::count();
    $completedLessons = \App\Models\StudentLessonProgress::where('status', 'completed')->count();
    
    // Calculate satisfaction rate as completion percentage
    // If we have progress records, calculate based on completion rate
    // Otherwise, calculate based on active students (students with any progress)
    if ($totalProgressRecords > 0) {
        $satisfactionRate = round(($completedLessons / $totalProgressRecords) * 100);
    } else {
        // Fallback: percentage of students who have started learning
        $studentsWithProgress = \App\Models\StudentLessonProgress::distinct('student_id')->count();
        $satisfactionRate = $totalStudents > 0 
            ? round(($studentsWithProgress / $totalStudents) * 100) 
            : 0;
    }
    
    // Ensure minimum values for display (at least show 0 if no data)
    $stats = [
        'totalStudents' => $totalStudents,
        'totalTeachers' => $totalTeachers,
        'totalLessons' => $totalLessons,
        'satisfactionRate' => min(100, max(0, $satisfactionRate)), // Clamp between 0 and 100
    ];
    
    return view('welcome', $stats);
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

// Teacher dashboard route (using controller for ML predictions)
// Note: The original dashboard logic is preserved in the controller
Route::get('/teacher/dashboard', [App\Http\Controllers\TeacherDashboardController::class, 'index'])
    ->middleware(['auth', 'verified', 'can:isTeacher'])
    ->name('teacher.dashboard');

// Original dashboard route (commented out - now handled by controller above)
/*
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
*/


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
    
    // Teacher Schedule
    Route::get('/schedule', function () {
        $teacher_id = Auth::id();
        $schedule = \App\Models\Schedule::where('teacher_id', $teacher_id)
            ->where('status', '!=', 'completed')
            ->with([
                'scheduledEvents' => function($query) {
                    $query->orderBy('release_date', 'asc');
                },
                'scheduledEvents.lesson',
                'scheduledEvents.level',
                'scheduledEvents.assignment',
                'scheduledEvents.quiz',
                'studentClass'
            ])
            ->first();
        
        if (!$schedule) {
            return redirect()->route('teacher.lessons.manage')
                ->with('info', 'No active schedule found.');
        }
        
        return view('teacher.schedule.show', compact('schedule'));
    })->name('teacher.schedule.show');
});

// Teacher Classes Management
Route::middleware(['auth', 'verified', 'can:isTeacher'])->prefix('teacher')->group(function () {
    Route::get('/classes', [TeacherClassesController::class, 'index'])->name('teacher.classes');
    Route::get('/grades', [App\Http\Controllers\TeacherGradeController::class, 'index'])->name('teacher.grades');
    Route::get('/progress', [App\Http\Controllers\TeacherProgressController::class, 'index'])->name('teacher.progress');
    
    // ML Prediction routes
    Route::get('/dashboard/ml', [App\Http\Controllers\TeacherDashboardController::class, 'index'])->name('teacher.dashboard.ml');
    Route::get('/student/{id}/risk', [App\Http\Controllers\TeacherDashboardController::class, 'getStudentRisk'])->name('teacher.student.risk');
    Route::post('/class/{id}/refresh-predictions', [App\Http\Controllers\TeacherDashboardController::class, 'refreshPredictions'])->name('teacher.class.refresh');
});

// ML Prediction Test Routes (for testing - remove in production or add authentication)
Route::middleware(['auth'])->group(function () {
    Route::get('/test-ml-api-connection', [App\Http\Controllers\MLPredictionTestController::class, 'testApiConnection'])->name('test.ml.api');
    Route::get('/test-ml-features/{studentId}', [App\Http\Controllers\MLPredictionTestController::class, 'testFeatures'])->name('test.ml.features');
    Route::get('/test-ml-prediction/{studentId}', [App\Http\Controllers\MLPredictionTestController::class, 'testPrediction'])->name('test.ml.prediction');
    Route::get('/ml-diagnose/{classId?}', [App\Http\Controllers\MLDiagnosticController::class, 'diagnose'])->name('ml.diagnose');
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
        \Log::info("DEBUG ROUTE: Processing level {$level->level_id} ({$level->level_name}), Level Number: {$level->level_number}");
        \Log::info("DEBUG ROUTE: Total lessons in level before filtering: " . $level->lessons->count());
        
        // Log all lessons before filtering
        foreach ($level->lessons as $lesson) {
            \Log::info("DEBUG ROUTE: Lesson ID: {$lesson->lesson_id}, Title: '{$lesson->title}', Order: {$lesson->lesson_order}, Level ID: {$lesson->level_id}");
        }
        
        $level->lessons = $level->lessons->filter(function($lesson) use ($studentClassId, $level) {
            $visibility = ClassLessonVisibility::where('lesson_id', $lesson->lesson_id)
                ->where('class_id', $studentClassId)
                ->where('is_visible', true)
                ->first();
            
            $isVisible = $visibility && $visibility->is_visible;
            
            \Log::info("DEBUG ROUTE: Lesson {$lesson->lesson_id} ('{$lesson->title}', Order: {$lesson->lesson_order}) in Level {$level->level_id} - Visibility: " . ($isVisible ? 'VISIBLE' : 'HIDDEN') . " (Class ID: {$studentClassId})");
            
            // Special logging for first lesson of level 2+
            if ($lesson->lesson_order == 1 && $level->level_number > 1) {
                \Log::info("DEBUG ROUTE: ⚠️ FIRST LESSON OF LEVEL {$level->level_number} - Lesson ID: {$lesson->lesson_id}, Visible: " . ($isVisible ? 'YES' : 'NO'));
            }
            
            return $isVisible;
        })->values();
        
        \Log::info("DEBUG ROUTE: Lessons in level {$level->level_id} after filtering: " . $level->lessons->count());
    }
    
    return view('levels', compact('levels', 'student'));
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
    $accuratePercentage = 0; // Initialize to ensure it's always defined
    
    if ($student) {
        // CRITICAL: Always get fresh data from database (bypass any query/model cache)
        // Query directly without any caching to ensure we get the absolute latest progress data
        $progress = \App\Models\StudentLessonProgress::withoutGlobalScopes()
            ->where('student_id', $student->student_id)
            ->where('lesson_id', $lessonId)
            ->first();
        
        // If progress exists, refresh it to get latest database values (bypasses model cache)
        if ($progress) {
            // Refresh from database to get absolute latest values - this forces a fresh DB query
            $progress->refresh();
        }
        
        // Track student activity - update last_activity_at when they access a lesson
        if ($progress) {
            $progress->last_activity_at = now();
            
            // Recalculate accurate percentage from max_watched_time (most reliable)
            // This ensures progress is always up-to-date when returning to the lesson page
            $videoDuration = $lesson->video_duration_seconds ?? 0;
            $maxWatchedTime = $progress->max_watched_time ?? 0;
            $accuratePercentage = 0;
            
            if ($videoDuration > 0 && $maxWatchedTime > 0) {
                $accuratePercentage = round(($maxWatchedTime / $videoDuration) * 100, 2);
            } else {
                $accuratePercentage = $progress->watched_percentage ?? 0;
            }
            
            // Update watched_percentage if it differs from accurate calculation
            if (abs(($progress->watched_percentage ?? 0) - $accuratePercentage) > 0.01) {
                $progress->watched_percentage = $accuratePercentage;
            }
            
            // Ensure video_completed is set correctly based on accurate percentage
            // This fixes the issue where video_completed might not be set even if progress >= 80%
            $shouldBeCompleted = $accuratePercentage >= 80;
            $isCurrentlyCompleted = $progress->video_completed ?? false;
            
            if ($shouldBeCompleted && !$isCurrentlyCompleted) {
                $progress->video_completed = true;
                
                // Use the controller's unlock method to properly unlock all game types
                $progressController = new \App\Http\Controllers\StudentProgressController();
                $progressController->unlockLessonGame($student->student_id, $lessonId);
            }
            
            $progress->save();
            
            // CRITICAL: Refresh the model to get the updated values from database
            $progress->refresh();
        } else {
            // Create progress record if it doesn't exist to track activity
            $progress = \App\Models\StudentLessonProgress::create([
                'student_id' => $student->student_id,
                'lesson_id' => $lessonId,
                'status' => 'not_started',
                'last_activity_at' => now(),
            ]);
        }
        
        // Check if lesson has games (considering class_id if student has one)
        if ($student && $student->class_id) {
            // Auto-initialize games for this student/lesson if they haven't been initialized yet
            // This ensures games are always accessible even if initialization didn't run during registration
            try {
                $progressController = new \App\Http\Controllers\StudentProgressController();
                $progressController->unlockLessonGame($student->student_id, $lessonId);
            } catch (\Exception $e) {
                \Log::warning('Failed to auto-initialize games in lesson view route: ' . $e->getMessage(), [
                    'student_id' => $student->student_id,
                    'lesson_id' => $lessonId
                ]);
            }
            
            // Check all game types with detailed logging
            $gameChecks = [
                'Game' => \App\Models\Game::where('lesson_id', $lessonId)
                    ->where('class_id', $student->class_id)
                    ->exists(),
                'ClockGame' => \App\Models\ClockGame::where('lesson_id', $lessonId)
                    ->where('class_id', $student->class_id)
                    ->exists(),
                'WordSearchGame' => \App\Models\WordSearchGame::where('lesson_id', $lessonId)
                    ->where('class_id', $student->class_id)
                    ->exists(),
                'MatchingPairsGame' => \App\Models\MatchingPairsGame::where('lesson_id', $lessonId)
                    ->where('class_id', $student->class_id)
                    ->exists(),
                'GroupWordPair' => \App\Models\GroupWordPair::where('lesson_id', $lessonId)
                    ->where('class_id', $student->class_id)
                    ->whereNotNull('lesson_id')
                    ->exists(),
            ];
            
            $hasGame = in_array(true, $gameChecks, true);
            
            // Also check if any games exist without class_id filter (for backward compatibility)
            if (!$hasGame) {
                $gameChecks['Game_no_class'] = \App\Models\Game::where('lesson_id', $lessonId)->exists();
                $gameChecks['ClockGame_no_class'] = \App\Models\ClockGame::where('lesson_id', $lessonId)->exists();
                $gameChecks['WordSearchGame_no_class'] = \App\Models\WordSearchGame::where('lesson_id', $lessonId)->exists();
                $gameChecks['MatchingPairsGame_no_class'] = \App\Models\MatchingPairsGame::where('lesson_id', $lessonId)->exists();
                $gameChecks['GroupWordPair_no_class'] = \App\Models\GroupWordPair::where('lesson_id', $lessonId)
                    ->whereNotNull('lesson_id')
                    ->exists();
                
                $hasGame = in_array(true, array_slice($gameChecks, 5), true);
            }
            
            // FALLBACK: If lesson is visible to student, always show game button
            // Let the games page handle showing "no games" if needed
            // This ensures students can always try to access games
            if (!$hasGame) {
                $lessonVisible = \App\Models\ClassLessonVisibility::where('lesson_id', $lessonId)
                    ->where('class_id', $student->class_id)
                    ->where('is_visible', true)
                    ->exists();
                
                if ($lessonVisible) {
                    $hasGame = true; // Show button, games page will handle availability
                    \Log::info('Lesson view - Showing game button as fallback for visible lesson', [
                        'student_id' => $student->student_id,
                        'lesson_id' => $lessonId,
                        'class_id' => $student->class_id
                    ]);
                }
            }
            
            // Log detailed game detection for debugging
            \Log::info('Lesson view - Game detection detailed', [
                'student_id' => $student->student_id,
                'lesson_id' => $lessonId,
                'class_id' => $student->class_id,
                'hasGame' => $hasGame,
                'game_checks' => $gameChecks,
                'game_counts' => [
                    'Game_with_class' => \App\Models\Game::where('lesson_id', $lessonId)->where('class_id', $student->class_id)->count(),
                    'ClockGame_with_class' => \App\Models\ClockGame::where('lesson_id', $lessonId)->where('class_id', $student->class_id)->count(),
                    'WordSearchGame_with_class' => \App\Models\WordSearchGame::where('lesson_id', $lessonId)->where('class_id', $student->class_id)->count(),
                    'MatchingPairsGame_with_class' => \App\Models\MatchingPairsGame::where('lesson_id', $lessonId)->where('class_id', $student->class_id)->count(),
                    'GroupWordPair_with_class' => \App\Models\GroupWordPair::where('lesson_id', $lessonId)->where('class_id', $student->class_id)->whereNotNull('lesson_id')->count(),
                ]
            ]);
        } else {
            $hasGame = \App\Models\Game::where('lesson_id', $lessonId)->exists();
        }
        
        // Recalculate percentage one more time for isVideoCompleted check (using refreshed progress)
        $videoDuration = $lesson->video_duration_seconds ?? 0;
        $maxWatchedTime = $progress->max_watched_time ?? 0;
        $accuratePercentage = 0;
        
        if ($videoDuration > 0 && $maxWatchedTime > 0) {
            $accuratePercentage = round(($maxWatchedTime / $videoDuration) * 100, 2);
        } else {
            $accuratePercentage = $progress->watched_percentage ?? 0;
        }
        
        // Update the progress object's watched_percentage to ensure blade template uses correct value
        $progress->watched_percentage = $accuratePercentage;
        
        // Check video completion based on accurate percentage OR video_completed flag
        $isVideoCompleted = $progress && (($progress->video_completed ?? false) || $accuratePercentage >= 80);
        
        // Check if ANY game for this lesson is completed (considering class_id)
        // CRITICAL: Use fresh queries to ensure we get latest game completion status
        $isGameCompleted = false;
        if ($hasGame && $student && $student->class_id) {
            // Get all Game models for this lesson and class (fresh query, no cache)
            $gameIds = \App\Models\Game::withoutGlobalScopes()
                ->where('lesson_id', $lessonId)
                ->where('class_id', $student->class_id)
                ->pluck('game_id');
            
            // Also check for games that might not have Game model entries yet
            // but have progress records (edge case handling)
            $allGameIds = $gameIds->toArray();
            
            // Check if student has completed any of these games (fresh query)
            if (!empty($allGameIds)) {
                $isGameCompleted = \App\Models\StudentGameProgress::withoutGlobalScopes()
                    ->where('student_id', $student->student_id)
                    ->whereIn('game_id', $allGameIds)
                    ->where('status', 'completed')
                    ->exists();
            }
            
            // If no completion found via Game models, check directly by lesson_id
            // This handles cases where game progress exists but Game model doesn't
            if (!$isGameCompleted) {
                // Get all possible game IDs for this lesson by checking all game types (fresh queries)
                $allPossibleGameIds = collect();
                
                // Check ClockGame -> Game mapping
                $clockGame = \App\Models\ClockGame::withoutGlobalScopes()
                    ->where('lesson_id', $lessonId)
                    ->where('class_id', $student->class_id)
                    ->first();
                if ($clockGame && $clockGame->game_id) {
                    $allPossibleGameIds->push($clockGame->game_id);
                }
                
                // Check WordSearchGame -> Game mapping
                $wordSearchGame = \App\Models\WordSearchGame::withoutGlobalScopes()
                    ->where('lesson_id', $lessonId)
                    ->where('class_id', $student->class_id)
                    ->first();
                if ($wordSearchGame && $wordSearchGame->game_id) {
                    $allPossibleGameIds->push($wordSearchGame->game_id);
                }
                
                // Check MatchingPairsGame -> Game mapping
                $matchingPairsGame = \App\Models\MatchingPairsGame::withoutGlobalScopes()
                    ->where('lesson_id', $lessonId)
                    ->where('class_id', $student->class_id)
                    ->first();
                if ($matchingPairsGame && $matchingPairsGame->game_id) {
                    $allPossibleGameIds->push($matchingPairsGame->game_id);
                }
                
                // Merge with existing game IDs
                $allGameIds = array_unique(array_merge($allGameIds, $allPossibleGameIds->toArray()));
                
                // Final check with all possible game IDs (fresh query)
                if (!empty($allGameIds)) {
                    $isGameCompleted = \App\Models\StudentGameProgress::withoutGlobalScopes()
                        ->where('student_id', $student->student_id)
                        ->whereIn('game_id', $allGameIds)
                        ->where('status', 'completed')
                        ->exists();
                }
            }
        }
    }
    
    // Pass accurate percentage to view to ensure correct display
    $accuratePercentageForView = $accuratePercentage ?? 0;
    
    // Add cache-busting headers to prevent browser from caching this page
    // This ensures fresh data is always loaded when returning from game page
    $response = response()->view('lesson-view', compact('lesson', 'progress', 'hasGame', 'isVideoCompleted', 'isGameCompleted', 'accuratePercentageForView'));
    
    $response->headers->set('Cache-Control', 'no-cache, no-store, must-revalidate, max-age=0');
    $response->headers->set('Pragma', 'no-cache');
    $response->headers->set('Expires', '0');
    
    return $response;
})->middleware(['auth', 'verified'])->name('student.lesson.view');

// Diagnostic route for debugging lesson unlocking (remove after debugging)
Route::get('/debug/lesson-unlock/{lessonId}', function ($lessonId) {
    if (!Auth::check()) {
        return response()->json(['error' => 'Not authenticated'], 401);
    }
    
    $user = Auth::user();
    $student = $user->student;
    
    if (!$student) {
        return response()->json(['error' => 'Not a student'], 403);
    }
    
    $lesson = \App\Models\Lesson::with('level')->findOrFail($lessonId);
    
    // Check visibility
    $isVisible = \App\Models\ClassLessonVisibility::where('class_id', $student->class_id)
        ->where('lesson_id', $lessonId)
        ->where('is_visible', true)
        ->exists();
    
    // Check prerequisites
    $prerequisiteStatus = $lesson->getPrerequisiteStatus($student->student_id, 60);
    
    // Get level info
    $level = $lesson->level;
    $previousLevel = null;
    if ($level) {
        $previousLevel = $level->prerequisiteLevel;
        if (!$previousLevel) {
            $previousLevel = \App\Models\Level::where('class_id', $level->class_id)
                ->where('level_number', '<', $level->level_number)
                ->orderBy('level_number', 'desc')
                ->first();
        }
    }
    
    // Get quiz info
    $previousLevelQuiz = null;
    $quizAttempts = collect();
    if ($previousLevel) {
        $previousLevelQuiz = \App\Models\Quiz::where('level_id', $previousLevel->level_id)
            ->where('is_active', true)
            ->first();
        
        if ($previousLevelQuiz) {
            $quizAttempts = \App\Models\QuizAttempt::where('quiz_id', $previousLevelQuiz->quiz_id)
                ->where('student_id', $student->student_id)
                ->get();
        }
    }
    
    return response()->json([
        'lesson' => [
            'id' => $lesson->lesson_id,
            'title' => $lesson->title,
            'lesson_order' => $lesson->lesson_order,
            'level_id' => $lesson->level_id,
        ],
        'level' => $level ? [
            'id' => $level->level_id,
            'name' => $level->level_name,
            'number' => $level->level_number,
            'class_id' => $level->class_id,
        ] : null,
        'previous_level' => $previousLevel ? [
            'id' => $previousLevel->level_id,
            'name' => $previousLevel->level_name,
            'number' => $previousLevel->level_number,
        ] : null,
        'previous_level_quiz' => $previousLevelQuiz ? [
            'id' => $previousLevelQuiz->quiz_id,
            'title' => $previousLevelQuiz->title,
            'is_active' => $previousLevelQuiz->is_active,
            'level_id' => $previousLevelQuiz->level_id,
        ] : null,
        'quiz_attempts' => $quizAttempts->map(function($attempt) {
            return [
                'id' => $attempt->attempt_id,
                'score' => $attempt->score,
                'submitted_at' => $attempt->submitted_at ? $attempt->submitted_at->toDateTimeString() : null,
                'status' => $attempt->status,
            ];
        }),
        'visibility' => [
            'is_visible' => $isVisible,
            'class_id' => $student->class_id,
        ],
        'prerequisites' => [
            'met' => $prerequisiteStatus['met'],
            'message' => $prerequisiteStatus['message'],
        ],
        'student' => [
            'id' => $student->student_id,
            'class_id' => $student->class_id,
        ],
    ], 200, [], JSON_PRETTY_PRINT);
})->middleware(['auth', 'verified']);

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
    Route::get('/assignments/create', [AssignmentController::class, 'create'])->name('assignments.create');
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
    
    // Automatic attendance system routes (students only)
    Route::post('/meetings/{meeting:meeting_id}/confirm-presence', [MeetingController::class, 'confirmPresence'])->name('meetings.confirm-presence');
    Route::post('/meetings/{meeting:meeting_id}/mark-absent', [MeetingController::class, 'markAbsent'])->name('meetings.mark-absent');
    
    // Teacher attendance management routes
    Route::middleware(['can:isTeacher'])->group(function () {
        Route::post('/meetings/{meeting:meeting_id}/mark-attendance', [MeetingController::class, 'markAttendance'])->name('meetings.mark-attendance');
        Route::get('/meetings/{meeting:meeting_id}/export-attendance', [MeetingController::class, 'exportAttendance'])->name('meetings.export-attendance');
    });
});
