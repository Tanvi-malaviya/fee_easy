<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

// Admin Web Panel Routes (Jetstream/Auth)
Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\Web\DashboardController::class, 'index'])->name('dashboard');

    Route::prefix('admin')->group(function () {
        // App Settings
        Route::get('settings', [App\Http\Controllers\Web\SettingController::class, 'index'])->name('settings.index');
        Route::post('settings/update', [App\Http\Controllers\Web\SettingController::class, 'update'])->name('settings.update');

        // Activity Monitoring
        Route::get('activities', [App\Http\Controllers\Web\ActivityController::class, 'index'])->name('activity.index');
    });

    Route::middleware('auth')->group(function () {
        Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
        Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    });
});

// Institute Web Panel Routes
Route::prefix('institute')->name('institute.')->group(function () {
    // Guest Routes
    Route::middleware('guest:institute')->group(function () {
        Route::get('/login', [App\Http\Controllers\Web\Institute\InstituteAuthController::class, 'showLogin'])->name('login');
        Route::post('/login', [App\Http\Controllers\Web\Institute\InstituteAuthController::class, 'login']);
    });

    // Unified Registration (Step 1 & Step 2)
    Route::get('/register', [App\Http\Controllers\Web\Institute\InstituteAuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [App\Http\Controllers\Web\Institute\InstituteAuthController::class, 'register']);
    Route::post('/verify-otp', [App\Http\Controllers\Web\Institute\InstituteAuthController::class, 'verifyOtp'])->name('verify-otp');
    Route::post('/resend-otp', [App\Http\Controllers\Web\Institute\InstituteAuthController::class, 'resendOtp'])->name('resend-otp');

    // Authenticated Routes
    Route::post('/logout', [App\Http\Controllers\Web\Institute\InstituteAuthController::class, 'logout'])->name('logout');

    Route::middleware('auth:institute')->group(function () {
        // Step 3: Setup Profile
        Route::post('/setup-profile', [App\Http\Controllers\Web\Institute\InstituteAuthController::class, 'setupProfile'])->name('setup-profile');

        Route::middleware('verified_institute')->group(function () {
            Route::get('/profile', function () {
                return view('institute.profile.index'); })->name('profile.index');
            Route::get('/profile/edit', function () {
                return view('institute.profile.edit'); })->name('profile.edit');

            Route::middleware('profile_complete')->group(function () {
                Route::get('/dashboard', [App\Http\Controllers\Web\Institute\DashboardController::class, 'index'])->name('dashboard');

                // Student Management
                Route::get('/students/export', [App\Http\Controllers\Web\Institute\StudentController::class, 'export'])->name('students.export');
                Route::get('/students/create', [App\Http\Controllers\Web\Institute\StudentController::class, 'create'])->name('students.create');
                Route::get('/students/{student}/edit', [App\Http\Controllers\Web\Institute\StudentController::class, 'edit'])->name('students.edit');
                Route::get('/students/{student}', [App\Http\Controllers\Web\Institute\StudentController::class, 'show'])->name('students.show');
                Route::get('/students', [App\Http\Controllers\Web\Institute\StudentController::class, 'index'])->name('students.index');
                Route::post('/students', [App\Http\Controllers\Web\Institute\StudentController::class, 'store'])->name('students.store');
                Route::put('/students/{student}', [App\Http\Controllers\Web\Institute\StudentController::class, 'update'])->name('students.update');
                Route::delete('/students/{student}', [App\Http\Controllers\Web\Institute\StudentController::class, 'destroy'])->name('students.destroy');

                // Batch Management
                Route::get('/batches/create', [App\Http\Controllers\Web\Institute\BatchController::class, 'create'])->name('batches.create');
                Route::get('/batches/{batch}/edit', [App\Http\Controllers\Web\Institute\BatchController::class, 'edit'])->name('batches.edit');
                Route::get('/batches/{batch}', [App\Http\Controllers\Web\Institute\BatchController::class, 'show'])->name('batches.show');
                Route::get('/batches', [App\Http\Controllers\Web\Institute\BatchController::class, 'index'])->name('batches.index');
                Route::post('/batches', [App\Http\Controllers\Web\Institute\BatchController::class, 'store'])->name('batches.store');
                Route::put('/batches/{batch}', [App\Http\Controllers\Web\Institute\BatchController::class, 'update'])->name('batches.update');
                Route::delete('/batches/{batch}', [App\Http\Controllers\Web\Institute\BatchController::class, 'destroy'])->name('batches.destroy');

                // Attendance Management
                Route::get('/attendance/create', [App\Http\Controllers\Web\Institute\AttendanceController::class, 'create'])->name('attendance.create');
                Route::get('/attendance/{attendance}/edit', [App\Http\Controllers\Web\Institute\AttendanceController::class, 'edit'])->name('attendance.edit');
                Route::get('/attendance/{attendance}', [App\Http\Controllers\Web\Institute\AttendanceController::class, 'show'])->name('attendance.show');
                Route::get('/attendance', [App\Http\Controllers\Web\Institute\AttendanceController::class, 'index'])->name('attendance.index');
                Route::post('/attendance', [App\Http\Controllers\Web\Institute\AttendanceController::class, 'store'])->name('attendance.store');
                Route::put('/attendance/{attendance}', [App\Http\Controllers\Web\Institute\AttendanceController::class, 'update'])->name('attendance.update');
                Route::delete('/attendance/{attendance}', [App\Http\Controllers\Web\Institute\AttendanceController::class, 'destroy'])->name('attendance.destroy');

                // Fee Management
                Route::get('/fees/receipts/{receipt}', [App\Http\Controllers\Web\Institute\FeeController::class, 'showReceipt'])->name('fees.receipts.show');
                Route::get('/fees/collect', [App\Http\Controllers\Web\Institute\FeeController::class, 'collect'])->name('fees.collect');
                Route::get('/fees', [App\Http\Controllers\Web\Institute\FeeController::class, 'index'])->name('fees.index');
                Route::post('/fees/collect', [App\Http\Controllers\Web\Institute\FeeController::class, 'store'])->name('fees.store');

                // Daily Updates
                Route::get('/updates', [App\Http\Controllers\Web\Institute\DailyUpdateController::class, 'index'])->name('updates.index');

                // Reports
                Route::get('/reports', [App\Http\Controllers\Web\Institute\ReportController::class, 'index'])->name('reports.index');

                // Notifications
                Route::get('/notifications', [App\Http\Controllers\Web\Institute\NotificationController::class, 'index'])->name('notifications.index');
            });
        });
    });
});
