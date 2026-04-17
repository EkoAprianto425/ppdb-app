<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RegistrationController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/dashboard', function () {
    if (auth()->user()->isAdmin()) {
        return redirect()->route('admin.dashboard');
    }
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Registration Routes
    Route::get('/formulir', [RegistrationController::class, 'index'])->name('pendaftaran.index');
    Route::post('/formulir', [RegistrationController::class, 'store'])->name('pendaftaran.store');
    Route::get('/administrasi', [RegistrationController::class, 'financialIndex'])->name('pendaftaran.financial');
    
    // Student Payment & Exam
    Route::post('/payment/upload', [RegistrationController::class, 'uploadPayment'])->name('pendaftaran.payment.upload');
    Route::get('/exam', [RegistrationController::class, 'examIndex'])->name('pendaftaran.exam');
    Route::post('/exam/select', [RegistrationController::class, 'selectExam'])->name('pendaftaran.exam.select');
    Route::get('/exam/card', [RegistrationController::class, 'downloadExamCard'])->name('pendaftaran.exam-card');
    
    // Student Announcement
    Route::get('/pengumuman', [RegistrationController::class, 'announcementIndex'])->name('pendaftaran.announcement');
    Route::get('/pengumuman/skl', [RegistrationController::class, 'downloadSKL'])->name('pendaftaran.announcement.skl');

    Route::get('/pendaftaran/create', [\App\Http\Controllers\RegistrationController::class, 'create'])->name('pendaftaran.create');
    Route::get('/pendaftaran/edit', [\App\Http\Controllers\RegistrationController::class, 'edit'])->name('pendaftaran.edit');
    Route::put('/pendaftaran', [\App\Http\Controllers\RegistrationController::class, 'update'])->name('pendaftaran.update');

    // Admin Access
    Route::middleware(['role:admin_smp,admin_sma,admin_smk,admin_administrasi,super_admin'])->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [\App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');
        
        // Super Admin Management
        Route::middleware(['role:super_admin'])->group(function () {
            Route::resource('year', \App\Http\Controllers\Admin\AcademicYearController::class);
            Route::resource('wave', \App\Http\Controllers\Admin\RegistrationWaveController::class);
            Route::resource('levels', \App\Http\Controllers\Admin\LevelController::class);
            Route::resource('users', \App\Http\Controllers\Admin\AdminManagementController::class);
            Route::get('/settings', [\App\Http\Controllers\Admin\SettingController::class, 'index'])->name('settings.index');
            Route::put('/settings', [\App\Http\Controllers\Admin\SettingController::class, 'update'])->name('settings.update');
        });

        // Unit Admin & Super Admin Management
        Route::middleware(['role:admin_smp,admin_sma,admin_smk,super_admin'])->group(function () {
            Route::get('/students', [\App\Http\Controllers\Admin\StudentManagementController::class, 'index'])->name('students.index');
            Route::get('/students/{registration}', [\App\Http\Controllers\Admin\StudentManagementController::class, 'show'])->name('students.show');
            Route::get('/students/{registration}/edit', [\App\Http\Controllers\Admin\StudentManagementController::class, 'edit'])->name('students.edit');
            Route::put('/students/{registration}', [\App\Http\Controllers\Admin\StudentManagementController::class, 'update'])->name('students.update');
            Route::post('/students/{registration}/transfer', [\App\Http\Controllers\Admin\StudentManagementController::class, 'transfer'])->name('students.transfer');
            Route::post('/students/{registration}/status', [\App\Http\Controllers\Admin\StudentManagementController::class, 'updateStatus'])->name('students.update-status');
            
            Route::get('/graduation', [\App\Http\Controllers\Admin\StudentManagementController::class, 'graduationIndex'])->name('graduation.index');
            
            Route::get('/schedules', [\App\Http\Controllers\Admin\ExamScheduleController::class, 'index'])->name('schedules.index');
            Route::post('/schedules', [\App\Http\Controllers\Admin\ExamScheduleController::class, 'store'])->name('schedules.store');
            Route::delete('/schedules/{schedule}', [\App\Http\Controllers\Admin\ExamScheduleController::class, 'destroy'])->name('schedules.destroy');
        });

        // Financial Admin & Super Admin Management
        Route::middleware(['role:admin_administrasi,super_admin'])->group(function () {
            Route::get('/fees', [\App\Http\Controllers\Admin\FinancialController::class, 'index'])->name('financial.fees');
            Route::post('/fees', [\App\Http\Controllers\Admin\FinancialController::class, 'store'])->name('financial.store');
            Route::put('/fees/{fee}', [\App\Http\Controllers\Admin\FinancialController::class, 'update'])->name('financial.update');
            Route::delete('/fees/{fee}', [\App\Http\Controllers\Admin\FinancialController::class, 'destroy'])->name('financial.destroy');
            
            // Payment Verification
            Route::get('/payments', [\App\Http\Controllers\Admin\FinancialController::class, 'indexPayments'])->name('financial.payments');
            Route::post('/payments/{payment}/verify', [\App\Http\Controllers\Admin\FinancialController::class, 'verifyPayment'])->name('financial.verify');
        });
    });
});

require __DIR__.'/auth.php';
