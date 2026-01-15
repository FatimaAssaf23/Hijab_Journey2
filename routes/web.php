<?php

use Illuminate\Support\Facades\Route;
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
});


// Profile photo upload
Route::get('/profile/photo', [ProfileController::class, 'showPhotoForm'])->name('profile.photo.form');
Route::post('/profile/photo', [ProfileController::class, 'uploadPhoto'])->name('profile.photo.upload');
// Emergency Absence Request (Teacher)
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/teacher/emergency', [EmergencyRequestController::class, 'create'])->name('teacher.emergency.create')->middleware('can:isTeacher');
    Route::post('/teacher/emergency', [EmergencyRequestController::class, 'store'])->name('teacher.emergency.store')->middleware('can:isTeacher');
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
    if ($user && ($user->role === 'teacher' || $user->role === 'admin')) {
        abort(403, 'Unauthorized');
    }
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('student.dashboard');

// Teacher dashboard route
Route::get('/teacher/dashboard', function () {
    $teacher_id = auth()->id();
    
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
    
    return view('teacher.dashboard', compact('levels', 'levelNames', 'lessonCounts', 'gradeRangeLabels', 'gradeRangeCounts'));
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
    Route::post('/students/{studentId}/change-class', [AdminController::class, 'changeStudentClass'])->name('admin.students.changeClass');
    
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
});

// Teacher Lesson Management
Route::middleware(['auth', 'verified'])->prefix('teacher')->group(function () {
    Route::get('/lessons/manage', [TeacherLessonController::class, 'index'])->name('teacher.lessons.manage');
    Route::post('/lessons/{lesson}/unlock', [TeacherLessonController::class, 'unlock'])->name('teacher.lessons.unlock');
    Route::post('/lessons/{lesson}/lock', [TeacherLessonController::class, 'lock'])->name('teacher.lessons.lock');
    Route::get('/lessons/{lesson}/view', [TeacherLessonController::class, 'view'])->name('teacher.lessons.view');
});

// Teacher Classes Management
Route::middleware(['auth', 'verified', 'can:isTeacher'])->prefix('teacher')->group(function () {
    Route::get('/classes', [TeacherClassesController::class, 'index'])->name('teacher.classes');
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
    return view('lesson-view', compact('lesson'));
})->middleware(['auth', 'verified'])->name('student.lesson.view');

require __DIR__.'/auth.php';

// Games page for teachers
use Illuminate\Support\Facades\Auth;

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

// Student marks lesson as completed
use App\Models\StudentLessonProgress;

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
});
