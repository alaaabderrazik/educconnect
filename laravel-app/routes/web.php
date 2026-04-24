<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PublicController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\ProfessorController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\OnlineCourseController;
use App\Http\Controllers\StudentProfileController;
use App\Http\Controllers\ProfessorProfileController;
use App\Http\Controllers\ScheduleController;

// Public Website Routes
Route::get('/', [PublicController::class, 'home'])->name('public.home');
Route::get('/about', [PublicController::class, 'about'])->name('public.about');
Route::get('/services', [PublicController::class, 'services'])->name('public.services');
Route::get('/contact', [PublicController::class, 'contact'])->name('public.contact');

// Dashboard Routes
Route::middleware(['auth'])->prefix('dashboard')->group(function () {
    
    // Redirect to role dashboard index
    Route::get('/', function () {
        $user = auth()->user();
        if ($user->hasRole('admin')) return redirect()->route('dashboard.admin');
        if ($user->hasRole('professor')) return redirect()->route('dashboard.professor');
        if ($user->hasRole('parent')) return redirect()->route('dashboard.parent');
        return redirect()->route('dashboard.student');
    })->name('dashboard');

    // Unified Messaging Routes
    Route::prefix('messages')->name('messages.')->group(function () {
        Route::get('/', [\App\Http\Controllers\MessageController::class, 'index'])->name('index');
        Route::post('/private', [\App\Http\Controllers\MessageController::class, 'startPrivate'])->name('startPrivate');
        Route::post('/group', [\App\Http\Controllers\MessageController::class, 'startGroup'])->name('startGroup');
        Route::get('/search-users', [\App\Http\Controllers\MessageController::class, 'searchUsers'])->name('searchUsers');
        Route::get('/download/{message}', [\App\Http\Controllers\MessageController::class, 'downloadFile'])->name('download');
        Route::get('/show/{id}', [\App\Http\Controllers\MessageController::class, 'show'])->name('show');
        Route::post('/show/{id}', [\App\Http\Controllers\MessageController::class, 'store'])->name('store');
    });

    // Student Routes
    Route::middleware(['role:student', 'profile.complete'])->prefix('student')->group(function () {
        Route::get('/', [StudentController::class, 'index'])->name('dashboard.student');
        Route::get('/courses', [StudentController::class, 'courses'])->name('dashboard.student.courses');
        Route::get('/schedule', [StudentController::class, 'schedule'])->name('dashboard.student.schedule');
        Route::get('/grades', [StudentController::class, 'grades'])->name('dashboard.student.grades');
        Route::get('/history', [StudentController::class, 'history'])->name('dashboard.student.history');
        Route::get('/profile', [StudentController::class, 'profile'])->name('dashboard.student.profile');
        Route::put('/profile', [StudentController::class, 'updateProfile'])->name('dashboard.student.profile.update');
        Route::get('/notifications', [StudentController::class, 'notifications'])->name('dashboard.student.notifications');
        Route::get('/online-courses', [StudentController::class, 'onlineCourses'])->name('dashboard.student.online-courses');
        Route::get('/assignments', [StudentController::class, 'assignments'])->name('dashboard.student.assignments');
        Route::post('/assignments/{id}/submit', [StudentController::class, 'submitAssignment'])->name('dashboard.student.assignments.submit');
        Route::get('/payments', [StudentController::class, 'payments'])->name('dashboard.student.payments');
    });

    // Profile Completion (Outside the 'profile.complete' middleware to prevent loop, but still protected by auth and role)
    Route::middleware(['auth', 'role:student'])->prefix('student/complete-profile')->group(function () {
        Route::get('/', [StudentProfileController::class, 'showCompleteProfile'])->name('student.complete_profile');
        Route::post('/', [StudentProfileController::class, 'updateProfile'])->name('student.complete_profile.update');
    });

    // Professor Routes
    Route::prefix('professor')->middleware(['role:professor', 'profile.complete'])->group(function () {
        Route::get('/', [ProfessorController::class, 'index'])->name('dashboard.professor');
        Route::get('/classes', [ProfessorController::class, 'classes'])->name('dashboard.professor.classes');
        Route::post('/classes/store', [ProfessorController::class, 'storeClass'])->name('dashboard.professor.classes.store');
        Route::get('/courses', [ProfessorController::class, 'courses'])->name('dashboard.professor.courses');
        Route::post('/courses/material', [ProfessorController::class, 'storeCourseMaterial'])->name('dashboard.professor.courses.materials.store');
        Route::get('/students', [ProfessorController::class, 'students'])->name('dashboard.professor.students');
        Route::get('/assignments', [ProfessorController::class, 'assignments'])->name('dashboard.professor.assignments');
        Route::post('/assignments/store', [ProfessorController::class, 'storeAssignment'])->name('dashboard.professor.assignments.store');
        Route::get('/assignments/{assignment}/submissions', [ProfessorController::class, 'showSubmissions'])->name('dashboard.professor.submissions');
        Route::get('/submissions', [ProfessorController::class, 'allSubmissions'])->name('dashboard.professor.all-submissions');
        Route::post('/submissions/{submission}/grade', [ProfessorController::class, 'gradeSubmission'])->name('dashboard.professor.grade');
        Route::get('/attendance', [ProfessorController::class, 'attendance'])->name('dashboard.professor.attendance');
        Route::get('/attendance/students', [ProfessorController::class, 'getStudentsForAttendance'])->name('dashboard.professor.attendance.students');
        Route::post('/attendance/save', [ProfessorController::class, 'saveAttendance'])->name('dashboard.professor.attendance.save');
        Route::get('/schedule', [ProfessorController::class, 'schedule'])->name('dashboard.professor.schedule');
        Route::get('/statistics', [ProfessorController::class, 'statistics'])->name('dashboard.professor.statistics');
        Route::get('/online-courses', [ProfessorController::class, 'onlineCourses'])->name('dashboard.professor.online-courses');
        Route::get('/notifications', [ProfessorController::class, 'notifications'])->name('dashboard.professor.notifications');
        Route::get('/profile', [ProfessorController::class, 'profile'])->name('dashboard.professor.profile');
        Route::post('/profile/update', [ProfessorController::class, 'updateProfile'])->name('dashboard.professor.profile.update');
    });

    // Parent Routes
    Route::middleware(['role:parent'])->prefix('parent')->group(function () {
        Route::get('/', [\App\Http\Controllers\ParentController::class, 'index'])->name('dashboard.parent');
    });

    // Separate group for Professor Profile Completion (to avoid middleware loop)
    Route::middleware(['auth', 'role:professor'])->prefix('professor/complete-profile')->name('dashboard.professor.complete_profile')->group(function () {
        Route::get('/', [ProfessorProfileController::class, 'showCompleteProfile']);
        Route::post('/', [ProfessorProfileController::class, 'updateProfile'])->name('.submit');
    });

    // Admin Routes
    Route::middleware('role:admin')->prefix('admin')->group(function () {
        Route::get('/', [AdminController::class, 'index'])->name('dashboard.admin');
        
        // Admins
        Route::get('/admins', [AdminController::class, 'admins'])->name('dashboard.admin.admins');
        Route::post('/admins', [AdminController::class, 'storeAdmin'])->name('dashboard.admin.admins.store');
        Route::put('/admins/{id}', [AdminController::class, 'updateAdmin'])->name('dashboard.admin.admins.update');
        
        // Students
        Route::get('/students', [AdminController::class, 'students'])->name('dashboard.admin.students');
        Route::post('/students', [AdminController::class, 'storeStudent'])->name('dashboard.admin.students.store');
        Route::put('/students/{id}', [AdminController::class, 'updateStudent'])->name('dashboard.admin.students.update');
        
        // Professors
        Route::get('/professors', [AdminController::class, 'professors'])->name('dashboard.admin.professors');
        Route::post('/professors', [AdminController::class, 'storeProfessor'])->name('dashboard.admin.professors.store');
        Route::put('/professors/{id}', [AdminController::class, 'updateProfessor'])->name('dashboard.admin.professors.update');
        
        // Academic
        Route::get('/levels', [AdminController::class, 'levels'])->name('dashboard.admin.levels');
        Route::post('/levels', [AdminController::class, 'storeLevel'])->name('dashboard.admin.levels.store');
        
        Route::get('/subjects', [AdminController::class, 'subjects'])->name('dashboard.admin.subjects');
        Route::post('/subjects', [AdminController::class, 'storeSubject'])->name('dashboard.admin.subjects.store');
        
        Route::get('/years', [AdminController::class, 'years'])->name('dashboard.admin.years');
        Route::post('/years', [AdminController::class, 'storeYear'])->name('dashboard.admin.years.store');
        Route::post('/years/{id}/activate', [AdminController::class, 'activateYear'])->name('dashboard.admin.years.activate');
        Route::post('/years/{id}/close', [AdminController::class, 'closeYear'])->name('dashboard.admin.years.close');
        
        // Classes
        Route::get('/classes', [AdminController::class, 'classes'])->name('dashboard.admin.classes');
        Route::post('/classes', [AdminController::class, 'storeClass'])->name('dashboard.admin.classes.store');
        
        // Site Management
        Route::get('/services', [AdminController::class, 'services'])->name('dashboard.admin.services');
        Route::post('/services', [AdminController::class, 'storeService'])->name('dashboard.admin.services.store');
        Route::put('/services/{id}', [AdminController::class, 'updateService'])->name('dashboard.admin.services.update');
        Route::get('/pages', [AdminController::class, 'pages'])->name('dashboard.admin.pages');
        Route::get('/pages/home', [AdminController::class, 'editPageHome'])->name('dashboard.admin.pages.home');
        Route::get('/pages/about', [AdminController::class, 'editPageAbout'])->name('dashboard.admin.pages.about');
        Route::get('/pages/services', [AdminController::class, 'editPageServices'])->name('dashboard.admin.pages.services');
        Route::get('/pages/contact', [AdminController::class, 'editPageContact'])->name('dashboard.admin.pages.contact');
        Route::post('/pages/update', [AdminController::class, 'updatePage'])->name('dashboard.admin.pages.update');
        Route::post('/pages/image/upload', [AdminController::class, 'uploadPageImage'])->name('dashboard.admin.pages.image.upload');
        Route::delete('/pages/image/delete', [AdminController::class, 'deletePageImage'])->name('dashboard.admin.pages.image.delete');
        Route::post('/pages/section/update', [AdminController::class, 'updatePageSection'])->name('dashboard.admin.pages.section.update');
        
        Route::get('/settings', [AdminController::class, 'settings'])->name('dashboard.admin.settings');
        Route::post('/settings', [AdminController::class, 'updateSettings'])->name('dashboard.admin.settings.update');

        // Parent Management
        Route::get('/parents/create', [\App\Http\Controllers\AdminParentController::class, 'create'])->name('dashboard.admin.parents.create');
        Route::post('/parents', [\App\Http\Controllers\AdminParentController::class, 'store'])->name('dashboard.admin.parents.store');
        Route::get('/parents/search-students', [\App\Http\Controllers\AdminParentController::class, 'searchStudents'])->name('dashboard.admin.parents.search_students');

        Route::get('/team', [AdminController::class, 'team'])->name('dashboard.admin.team');
        Route::post('/team', [AdminController::class, 'storeTeamMember'])->name('dashboard.admin.team.store');
        Route::put('/team/{id}', [AdminController::class, 'updateTeamMember'])->name('dashboard.admin.team.update');

        Route::get('/faqs', [AdminController::class, 'faqs'])->name('dashboard.admin.faqs');
        Route::post('/faqs', [AdminController::class, 'storeFAQ'])->name('dashboard.admin.faqs.store');
        Route::put('/faqs/{id}', [AdminController::class, 'updateFAQ'])->name('dashboard.admin.faqs.update');

        Route::get('/testimonials', [AdminController::class, 'testimonials'])->name('dashboard.admin.testimonials');
        Route::post('/testimonials', [AdminController::class, 'storeTestimonial'])->name('dashboard.admin.testimonials.store');
        Route::put('/testimonials/{id}', [AdminController::class, 'updateTestimonial'])->name('dashboard.admin.testimonials.update');

        // Admins
        Route::get('/admins', [AdminController::class, 'admins'])->name('dashboard.admin.admins');
        Route::post('/admins', [AdminController::class, 'storeAdmin'])->name('dashboard.admin.admins.store');

        Route::get('/roles', [AdminController::class, 'roles'])->name('dashboard.admin.roles');
        Route::put('/roles/{role}/permissions', [AdminController::class, 'updateRolePermissions'])->name('dashboard.admin.roles.permissions');
        Route::get('/notifications', [AdminController::class, 'notifications'])->name('dashboard.admin.notifications');
        Route::get('/logs', [AdminController::class, 'logs'])->name('dashboard.admin.logs');

        // Online Courses
        Route::resource('/online-courses', OnlineCourseController::class)->names([
            'index' => 'dashboard.admin.online-courses',
            'store' => 'dashboard.admin.online-courses.store',
            'destroy' => 'dashboard.admin.online-courses.destroy',
        ])->only(['index', 'store', 'destroy']);
        
        // Schedules
        Route::get('/schedules', [ScheduleController::class, 'index'])->name('dashboard.admin.schedules');
        Route::post('/schedules', [ScheduleController::class, 'store'])->name('dashboard.admin.schedules.store');
        Route::put('/schedules/{id}', [ScheduleController::class, 'update'])->name('dashboard.admin.schedules.update');
        Route::delete('/schedules/{id}', [ScheduleController::class, 'destroy'])->name('dashboard.admin.schedules.destroy');

        // Billing
        Route::get('/billing', [\App\Http\Controllers\BillingController::class, 'index'])->name('dashboard.admin.billing');
        Route::post('/billing/pay-month/{id}', [\App\Http\Controllers\BillingController::class, 'payCurrentMonth'])->name('dashboard.admin.billing.pay_month');
        Route::post('/billing/pay-month-specific/{id}', [\App\Http\Controllers\BillingController::class, 'paySpecificMonth'])->name('dashboard.admin.billing.pay_specific_month');
        Route::post('/billing/revoke-month/{id}', [\App\Http\Controllers\BillingController::class, 'revokeSpecificMonth'])->name('dashboard.admin.billing.revoke_month');

        // Global Delete
        Route::delete('/destroy/{type}/{id}', [AdminController::class, 'destroy'])->name('dashboard.admin.destroy');
    });
});

// Authentication Routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
    Route::get('/register', [AuthController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.submit');
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');
