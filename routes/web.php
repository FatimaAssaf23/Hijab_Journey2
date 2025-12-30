
<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EmergencyRequestController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\TeacherRequestController;
use App\Http\Controllers\TeacherLessonController;

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
    return redirect()->route('login');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

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

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
