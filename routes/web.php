<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AssessmentController;
use App\Http\Controllers\Teacher\AssessmentConfigController;
use App\Http\Controllers\Teacher\TermAssessmentController;
use Illuminate\Support\Facades\Log; // ← ADD THIS LINE

Route::get('/', function () {
    return view('welcome');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Main dashboard route - redirects based on role
Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

// Admin routes
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');
    
    Route::get('/users', function () {
        return view('admin.users');
    })->name('users');

    // Student management routes
    Route::resource('students', \App\Http\Controllers\Admin\StudentController::class);
    // Teacher management routes
    Route::resource('teachers', \App\Http\Controllers\Admin\TeacherController::class);
    // Class management routes
    Route::resource('class-groups', \App\Http\Controllers\Admin\ClassGroupController::class);
    // Stream management routes
    Route::resource('streams', \App\Http\Controllers\Admin\StreamController::class);

    // Teacher workload route - FIXED: Added full namespace
    Route::get('teachers/{teacher}/workload', [\App\Http\Controllers\Admin\TeacherController::class, 'workload'])->name('teachers.workload');

    // Class subject management routes - FIXED: Added full namespace
    Route::get('class-groups/{classGroup}/manage-subjects', [\App\Http\Controllers\Admin\ClassGroupController::class, 'manageSubjects'])->name('class-groups.manage-subjects');
    Route::post('class-groups/{classGroup}/update-subjects', [\App\Http\Controllers\Admin\ClassGroupController::class, 'updateSubjects'])->name('class-groups.update-subjects');

        // Add these to the admin teacher routes
    Route::get('teachers/{teacher}/manage-assignments', [\App\Http\Controllers\Admin\TeacherController::class, 'manageAssignments'])->name('teachers.manage-assignments');
    Route::post('teachers/{teacher}/update-assignments', [\App\Http\Controllers\Admin\TeacherController::class, 'updateAssignments'])->name('teachers.update-assignments');

    Route::get('teachers/{teacher}/manage-assignments', [\App\Http\Controllers\Admin\TeacherController::class, 'manageAssignments'])->name('teachers.manage-assignments');
    Route::post('teachers/{teacher}/update-assignments', [\App\Http\Controllers\Admin\TeacherController::class, 'updateAssignments'])->name('teachers.update-assignments');
    Route::get('teachers/{teacher}/workload', [\App\Http\Controllers\Admin\TeacherController::class, 'workload'])->name('teachers.workload');

    Route::get('/class-allocations', [\App\Http\Controllers\Admin\ClassAllocationController::class, 'index'])->name('class-allocations.index');
    Route::get('/class-allocations/bulk-assign', [\App\Http\Controllers\Admin\ClassAllocationController::class, 'create'])->name('class-allocations.create');
    Route::post('/class-allocations/bulk-assign', [\App\Http\Controllers\Admin\ClassAllocationController::class, 'store'])->name('class-allocations.store');
    Route::get('/class-allocations/class/{classGroup}', [\App\Http\Controllers\Admin\ClassAllocationController::class, 'show'])->name('class-allocations.show');
    Route::post('/class-allocations/class/{classGroup}/add-student', [\App\Http\Controllers\Admin\ClassAllocationController::class, 'addStudent'])->name('class-allocations.add-student');
    Route::delete('/class-allocations/class/{classGroup}/remove-student/{student}', [\App\Http\Controllers\Admin\ClassAllocationController::class, 'removeStudent'])->name('class-allocations.remove-student');

    // Admin Attendance Routes
    Route::get('/attendance', [\App\Http\Controllers\Admin\AttendanceController::class, 'index'])->name('attendance.index');
    Route::get('/attendance/reports', [\App\Http\Controllers\Admin\AttendanceController::class, 'reports'])->name('attendance.reports');
    Route::get('/attendance/export', [\App\Http\Controllers\Admin\AttendanceController::class, 'export'])->name('attendance.export');

    // AJAX route for class allocation dashboard data
    Route::get('/admin/class-allocations/dashboard-data', function() {
        $gradeLevelsWithClasses = \App\Models\GradeLevel::with(['classes.students', 'classes.teacher.user'])
            ->where('is_active', true)
            ->get();
        $unassignedStudents = \App\Models\Student::whereDoesntHave('classGroups')->get();
        
        return view('admin.students.partials.class-allocation-content', compact(
            'gradeLevelsWithClasses',
            'unassignedStudents'
        ));
    })->name('admin.class-allocations.dashboard-data');
    // AJAX route for class allocation dashboard data
    Route::get('/class-allocations/dashboard-data', [\App\Http\Controllers\Admin\ClassAllocationController::class, 'dashboardData'])->name('class-allocations.dashboard-data');
});

// Teacher routes  
Route::middleware(['auth', 'teacher'])->prefix('teacher')->name('teacher.')->group(function () {
    // Updated to use controller
    Route::get('/dashboard', [\App\Http\Controllers\Teacher\DashboardController::class, 'index'])->name('dashboard');
    
    // Legacy grades routes (keep for backward compatibility)
    Route::get('/grades', [\App\Http\Controllers\Teacher\GradeController::class, 'index'])->name('grades.index');
    Route::get('/grades/create', [\App\Http\Controllers\Teacher\GradeController::class, 'create'])->name('grades.create');
    Route::post('/grades', [\App\Http\Controllers\Teacher\GradeController::class, 'store'])->name('grades.store');
    Route::get('/grades/{grade}', [\App\Http\Controllers\Teacher\GradeController::class, 'show'])->name('grades.show');
    Route::get('/grades/{grade}/edit', [\App\Http\Controllers\Teacher\GradeController::class, 'edit'])->name('grades.edit');
    Route::put('/grades/{grade}', [\App\Http\Controllers\Teacher\GradeController::class, 'update'])->name('grades.update');
    Route::delete('/grades/{grade}', [\App\Http\Controllers\Teacher\GradeController::class, 'destroy'])->name('grades.destroy');
    
    // NEW: Enhanced Grade Management Routes
    Route::get('/grades/class/{classGroup}', [\App\Http\Controllers\Teacher\GradeController::class, 'showClass'])->name('grades.class');
    Route::get('/grades/task/create/{classGroup}', [\App\Http\Controllers\Teacher\GradeController::class, 'createTask'])->name('grades.task.create');
    Route::post('/grades/task/store', [\App\Http\Controllers\Teacher\GradeController::class, 'storeTask'])->name('grades.task.store');
    Route::get('/grades/task/{task}', [\App\Http\Controllers\Teacher\GradeController::class, 'showTask'])->name('grades.task.show');
    Route::put('/grades/task/{task}', [\App\Http\Controllers\Teacher\GradeController::class, 'updateTask'])->name('grades.task.update');
    Route::post('/grades/entry/{task}', [\App\Http\Controllers\Teacher\GradeController::class, 'storeGrade'])->name('grades.entry.store');
    Route::put('/grades/entry/{gradeEntry}', [\App\Http\Controllers\Teacher\GradeController::class, 'updateGrade'])->name('grades.entry.update');
    Route::get('/grades/performance/{classGroup}/{student}', [\App\Http\Controllers\Teacher\GradeController::class, 'getStudentPerformance'])->name('grades.performance.student');
    
    // Class group routes for teachers
    Route::get('/class-groups', [\App\Http\Controllers\Teacher\ClassGroupController::class, 'index'])->name('class-groups.index'); // ADD THIS
    Route::get('/class-groups/{classGroup}', [\App\Http\Controllers\Teacher\ClassGroupController::class, 'show'])->name('class-groups.show');

    // Teacher Attendance Routes
    Route::get('/attendance', [\App\Http\Controllers\Teacher\AttendanceController::class, 'index'])->name('attendance.index');
    Route::get('/attendance/create', [\App\Http\Controllers\Teacher\AttendanceController::class, 'create'])->name('attendance.create');
    Route::post('/attendance', [\App\Http\Controllers\Teacher\AttendanceController::class, 'store'])->name('attendance.store');
    Route::get('/attendance/{classGroup}/{subject}/{date}', [\App\Http\Controllers\Teacher\AttendanceController::class, 'show'])->name('attendance.show');
    Route::get('/attendance/reports', [\App\Http\Controllers\Teacher\AttendanceController::class, 'reports'])->name('attendance.reports');

    // ✅ FIXED: Assessment Routes with proper teacher. prefix
    Route::get('/assessments', [AssessmentController::class, 'index'])->name('assessments');
    Route::get('/assessments/class/{classGroup}', [AssessmentController::class, 'showClassAssessments'])->name('assessments.class');
    
    // Assessment Configuration
    Route::get('/assessments/config/{classGroup}/{subject}/create', [AssessmentConfigController::class, 'create'])->name('assessments.config.create');
    Route::post('/assessments/config', [AssessmentConfigController::class, 'store'])->name('assessments.config.store');
    Route::get('/assessments/config/{config}/edit', [AssessmentConfigController::class, 'edit'])->name('assessments.config.edit');
    Route::put('/assessments/config/{config}', [AssessmentConfigController::class, 'update'])->name('assessments.config.update');

        // Term Assessment Routes
    Route::get('/term-assessments/create/{classGroup}/{subject}', [\App\Http\Controllers\Teacher\TermAssessmentController::class, 'create'])->name('term-assessments.create');
    Route::post('/term-assessments/store', [\App\Http\Controllers\Teacher\TermAssessmentController::class, 'store'])->name('term-assessments.store');
    Route::get('/term-assessments/{termAssessment}', [\App\Http\Controllers\Teacher\TermAssessmentController::class, 'show'])->name('term-assessments.show');

        // NEW: Term Assessment Routes
    Route::get('/term-assessments/create/{classGroup}/{subject}', [\App\Http\Controllers\Teacher\TermAssessmentController::class, 'create'])->name('term-assessments.create');
    Route::post('/term-assessments/store', [\App\Http\Controllers\Teacher\TermAssessmentController::class, 'store'])->name('term-assessments.store');

    // Add this with your other term assessment routes
    Route::get('teacher/term-assessments/{termAssessment}/scores', [\App\Http\Controllers\Teacher\TermAssessmentController::class, 'viewScores'])
    ->name('term-assessments.scores');
    // Add this with your other teacher routes
    Route::get('/assessments/class/{classGroup}/final-report', 
    [TermAssessmentController::class, 'finalClassReport'])
    ->name('assessments.class.final-report');

    // Add with your other term assessment routes
    Route::get('/term-assessments/{termAssessment}/edit', 
        [TermAssessmentController::class, 'edit'])
        ->name('term-assessments.edit');

    Route::put('/term-assessments/{termAssessment}', 
        [TermAssessmentController::class, 'update'])
        ->name('term-assessments.update');

    Route::delete('/term-assessments/{termAssessment}', 
        [TermAssessmentController::class, 'destroy'])
        ->name('term-assessments.destroy');

    // AJAX route for getting students by class
    Route::get('/get-students/{classGroupId}', [\App\Http\Controllers\Teacher\GradeController::class, 'getStudentsByClass'])->name('get-students');
});

// Student routes
Route::middleware(['auth', 'student'])->prefix('student')->name('student.')->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\Student\DashboardController::class, 'index'])->name('dashboard');
    
    // Student Grades routes
    Route::get('/grades', [\App\Http\Controllers\Student\GradeController::class, 'index'])->name('grades');
    Route::get('/grades/subject/{subject}', [\App\Http\Controllers\Student\GradeController::class, 'bySubject'])->name('grades.by-subject');
    Route::get('/grades/class/{classGroup}', [\App\Http\Controllers\Student\GradeController::class, 'byClass'])->name('grades.by-class');
});

// Temporary test route - remove after testing
Route::get('/test-log', function() {
    Log::info('Test log message from test route');
    return response()->json(['message' => 'Check your laravel.log file for the test message']);
});
// Add this temporary debug route
Route::get('/debug-test', function() {
    return view('teacher.grades.check');
});

Route::post('/test-route', function() {
    Log::info('🎯 TEST ROUTE HIT');
    return response()->json(['message' => 'Test route working!', 'time' => now()]);
});

require __DIR__.'/auth.php';