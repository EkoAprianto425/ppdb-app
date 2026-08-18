<?php

use App\Http\Controllers\ProfileController;
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

// Region API for Registration
Route::get('/api/region/provinsi', [\App\Http\Controllers\RegionController::class, 'getProvinsi']);
Route::get('/api/region/kabupaten', [\App\Http\Controllers\RegionController::class, 'getKabupaten']);
Route::get('/api/region/kecamatan', [\App\Http\Controllers\RegionController::class, 'getKecamatan']);
Route::get('/api/region/sekolah', [\App\Http\Controllers\RegionController::class, 'getSekolah']);

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Registration Routes
    Route::get('/formulir', [\App\Http\Controllers\Siswa\RegistrationController::class, 'index'])->name('pendaftaran.index');
    Route::post('/formulir', [\App\Http\Controllers\Siswa\RegistrationController::class, 'store'])->name('pendaftaran.store');
    
    // Routes that are blocked for students with special needs
    Route::middleware([\App\Http\Middleware\CheckKebutuhanKhusus::class])->group(function () {
        // Administrasi / Payment
        Route::get('/administrasi', [\App\Http\Controllers\Siswa\PaymentController::class, 'index'])->name('pendaftaran.financial');
        Route::post('/payment/create-va', [\App\Http\Controllers\Siswa\PaymentController::class, 'createVa'])->name('pendaftaran.payment.create-va');
        Route::post('/payment/create-va-bca', [\App\Http\Controllers\Siswa\PaymentController::class, 'createVaBca'])->name('pendaftaran.payment.create-va-bca');
        Route::post('/payment/check-va', [\App\Http\Controllers\Siswa\PaymentController::class, 'checkVa'])->name('pendaftaran.payment.check-va');
        Route::post('/payment/switch-to-bca', [\App\Http\Controllers\Siswa\PaymentController::class, 'switchToBca'])->name('pendaftaran.payment.switch-to-bca');
        Route::post('/payment/switch-to-btn', [\App\Http\Controllers\Siswa\PaymentController::class, 'switchToBtn'])->name('pendaftaran.payment.switch-to-btn');
        
        // Ujian / Exam
        Route::get('/exam', [\App\Http\Controllers\Siswa\ExamController::class, 'index'])->name('pendaftaran.exam');
        Route::post('/exam/select', [\App\Http\Controllers\Siswa\ExamController::class, 'select'])->name('pendaftaran.exam.select');
        Route::get('/exam/card', [\App\Http\Controllers\Siswa\ExamController::class, 'downloadCard'])->name('pendaftaran.exam-card');
        
        // Pengumuman / Announcement
        Route::get('/pengumuman', [\App\Http\Controllers\Siswa\AnnouncementController::class, 'index'])->name('pendaftaran.announcement');
        Route::get('/pengumuman/skl', [\App\Http\Controllers\Siswa\AnnouncementController::class, 'downloadSKL'])->name('pendaftaran.announcement.skl');
    });

    // Discount
    Route::post('/discount/apply', [\App\Http\Controllers\Siswa\DiscountController::class, 'apply'])->name('discount.apply');

    // Formulir Actions
    Route::get('/pendaftaran/create', [\App\Http\Controllers\Siswa\RegistrationController::class, 'create'])->name('pendaftaran.create');
    Route::get('/pendaftaran/edit', [\App\Http\Controllers\Siswa\RegistrationController::class, 'edit'])->name('pendaftaran.edit');
    Route::put('/pendaftaran', [\App\Http\Controllers\Siswa\RegistrationController::class, 'update'])->name('pendaftaran.update');

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
            
            // Backup & Restore
            Route::get('/backup', [\App\Http\Controllers\Admin\BackupController::class, 'index'])->name('backup.index');
            Route::get('/backup/db', [\App\Http\Controllers\Admin\BackupController::class, 'downloadDatabase'])->name('backup.download-db');
            Route::get('/backup/proofs', [\App\Http\Controllers\Admin\BackupController::class, 'downloadProofs'])->name('backup.download-proofs');
            Route::post('/backup/restore-db', [\App\Http\Controllers\Admin\BackupController::class, 'restoreDatabase'])->name('backup.restore-db');
            Route::post('/backup/restore-proofs', [\App\Http\Controllers\Admin\BackupController::class, 'restoreProofs'])->name('backup.restore-proofs');
            Route::resource('information-sources', \App\Http\Controllers\Admin\InformationSourceController::class);
        });

        // Unit Admin & Super Admin Management
        Route::middleware(['role:admin_smp,admin_sma,admin_smk,super_admin'])->group(function () {
            Route::get('/students/export', [\App\Http\Controllers\Admin\StudentManagementController::class, 'exportExcel'])->name('students.export');
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

            Route::resource('discounts', \App\Http\Controllers\Admin\DiscountController::class);
            Route::get('/discount-applications', [\App\Http\Controllers\Admin\DiscountApplicationController::class, 'index'])->name('discount-applications.index');
            Route::put('/discount-applications/{application}', [\App\Http\Controllers\Admin\DiscountApplicationController::class, 'update'])->name('discount-applications.update');
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
            Route::post('/payments/{payment}/check-va', [\App\Http\Controllers\Admin\FinancialController::class, 'checkVaStatus'])->name('financial.check-va');
            Route::post('/payments/{payment}/record-cash', [\App\Http\Controllers\Admin\FinancialController::class, 'recordCashPayment'])->name('financial.record-cash');
        });
    });
});

require __DIR__.'/auth.php';
