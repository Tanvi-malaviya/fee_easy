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

Route::get('/super-debug', function () {
    $pdo = \Illuminate\Support\Facades\DB::connection()->getPdo();
    $stmt = $pdo->query('SELECT id, email FROM users WHERE id = 1');
    $rawUser = $stmt->fetch(\PDO::FETCH_ASSOC);

    return response()->json([
        'eloquent_user_1' => \App\Models\User::find(1)->email ?? 'null',
        'raw_db_user_1' => $rawUser['email'] ?? 'null',
        'auth_user' => auth()->check() ? auth()->user()->email : 'Not logged in',
        'auth_id' => auth()->id(),
        'session_id' => session()->getId(),
        'database' => \Illuminate\Support\Facades\DB::connection()->getDatabaseName(),
    ]);
});

// Admin Web Panel Routes (Jetstream/Auth)
Route::middleware(array_filter([
    'auth:web',
    config('jetstream.auth_session'),
    'verified',
]))->group(function () {

    Route::prefix('admin')->group(function () {
        Route::get('/dashboard', [App\Http\Controllers\Web\DashboardController::class, 'index'])->name('dashboard');

        // Institutes Management
        Route::resource('institutes', App\Http\Controllers\Web\InstituteController::class);
        Route::resource('departments', App\Http\Controllers\Web\DepartmentController::class);
        Route::post('institutes/{institute}/status', [App\Http\Controllers\Web\InstituteController::class, 'updateStatus'])->name('institutes.status');
        Route::delete('institutes/{institute}/students/{student}', [App\Http\Controllers\Web\InstituteController::class, 'deleteStudent'])->name('institutes.students.destroy');
        Route::delete('institutes/{institute}/staff/{staff}', [App\Http\Controllers\Web\InstituteController::class, 'deleteStaff'])->name('institutes.staff.destroy');
        Route::delete('institutes/{institute}/batches/{batch}', [App\Http\Controllers\Web\InstituteController::class, 'deleteBatch'])->name('institutes.batches.destroy');
        Route::get('institutes/{institute}/batches/{batch}', [App\Http\Controllers\Web\InstituteController::class, 'showBatch'])->name('institutes.batches.show');

        // Subscription Management
        Route::resource('subscriptions', App\Http\Controllers\Web\SubscriptionController::class);
        Route::patch('subscriptions/{subscription}/extend', [App\Http\Controllers\Web\SubscriptionController::class, 'extend'])->name('subscriptions.extend');
        Route::patch('subscriptions/{subscription}/activate', [App\Http\Controllers\Web\SubscriptionController::class, 'activate'])->name('subscriptions.activate');
        Route::patch('subscriptions/{subscription}/cancel', [App\Http\Controllers\Web\SubscriptionController::class, 'cancel'])->name('subscriptions.cancel');
        Route::patch('subscriptions/{subscription}/change-plan', [App\Http\Controllers\Web\SubscriptionController::class, 'changePlan'])->name('subscriptions.changePlan');
        Route::patch('subscriptions/{subscription}/convert', [App\Http\Controllers\Web\SubscriptionController::class, 'convertToPaid'])->name('subscriptions.convert');
        Route::patch('subscriptions/renewals/{renewal}/approve', [App\Http\Controllers\Web\SubscriptionController::class, 'approveRenewal'])->name('subscriptions.renewals.approve');
        Route::patch('subscriptions/renewals/{renewal}/reject', [App\Http\Controllers\Web\SubscriptionController::class, 'rejectRenewal'])->name('subscriptions.renewals.reject');

        // Plan Management
        Route::resource('plans', App\Http\Controllers\Web\PlanController::class);
        Route::post('plans/{plan}/status', [App\Http\Controllers\Web\PlanController::class, 'updateStatus'])->name('plans.status');

        // Revenue Analysis
        Route::get('revenue', [App\Http\Controllers\Web\RevenueController::class, 'index'])->name('revenue.index');
        Route::post('revenue/manual-payment', [App\Http\Controllers\Web\RevenueController::class, 'storeManualPayment'])->name('revenue.store_manual');

        // Broadcast Center
        Route::get('broadcast', [App\Http\Controllers\Web\BroadcastController::class, 'index'])->name('broadcast.index');
        Route::post('broadcast/send', [App\Http\Controllers\Web\BroadcastController::class, 'send'])->name('broadcast.send');

        // WhatsApp Management
        Route::get('whatsapp', [App\Http\Controllers\Web\WhatsAppController::class, 'index'])->name('whatsapp.index');
        Route::post('whatsapp/{institute}/update', [App\Http\Controllers\Web\WhatsAppController::class, 'update'])->name('whatsapp.update');
        Route::post('whatsapp/{institute}/verify', [App\Http\Controllers\Web\WhatsAppController::class, 'verify'])->name('whatsapp.verify');

        // App Settings
        Route::get('settings', [App\Http\Controllers\Web\SettingController::class, 'index'])->name('settings.index');
        Route::post('settings/update', [App\Http\Controllers\Web\SettingController::class, 'update'])->name('settings.update');

        // Activity Monitoring
        Route::get('activities', [App\Http\Controllers\Web\ActivityController::class, 'index'])->name('activity.index');

        // Profile Management
        Route::get('profile', [App\Http\Controllers\ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('profile', [App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update');
        Route::delete('profile', [App\Http\Controllers\ProfileController::class, 'destroy'])->name('profile.destroy');
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

    // Password Reset Routes
    Route::get('/forgot-password', [App\Http\Controllers\Web\Institute\InstituteAuthController::class, 'showForgotPassword'])->name('password.request');
    Route::post('/forgot-password', [App\Http\Controllers\Web\Institute\InstituteAuthController::class, 'sendResetLink'])->name('password.email');
    Route::get('/reset-password/{token}', [App\Http\Controllers\Web\Institute\InstituteAuthController::class, 'showResetPassword'])->name('password.reset');
    Route::post('/reset-password', [App\Http\Controllers\Web\Institute\InstituteAuthController::class, 'resetPassword'])->name('password.update');

    Route::middleware(['auth:institute', 'active_institute'])->group(function () {
        // Step 3: Setup Profile
        Route::post('/setup-profile', [App\Http\Controllers\Web\Institute\InstituteAuthController::class, 'setupProfile'])->name('setup-profile');

        Route::middleware('verified_institute')->group(function () {
            Route::post('/fcm-token', [App\Http\Controllers\Api\V1\FCMTokenController::class, 'updateToken'])->name('fcm-token.update');

            Route::get('/profile', function () {
                return view('institute.profile.index');
            })->name('profile.index');
            Route::get('/profile/edit', function () {
                return view('institute.profile.edit');
            })->name('profile.edit');
            Route::post('/profile/update', [App\Http\Controllers\Web\Institute\ProfileController::class, 'update'])->name('profile.update');
            Route::post('/profile/password', [App\Http\Controllers\Web\Institute\ProfileController::class, 'updatePassword'])->name('profile.password.update');
            Route::post('/subscription/renew', [App\Http\Controllers\Web\Institute\DashboardController::class, 'submitRenewal'])->name('subscription.renew');

            Route::middleware(['profile_complete', 'check_subscription'])->group(function () {
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
                Route::get('/batches/{batch}/students', [App\Http\Controllers\Web\Institute\BatchController::class, 'students'])->name('batches.students');
                Route::get('/batches/{batch}/homework/{homework}', [App\Http\Controllers\Web\Institute\BatchController::class, 'homeworkShow'])->name('batches.homework.show');
                Route::get('/batches/{batch}/homework', [App\Http\Controllers\Web\Institute\BatchController::class, 'homework'])->name('batches.homework');
                Route::get('/batches/{batch}/attendance', [App\Http\Controllers\Web\Institute\BatchController::class, 'attendance'])->name('batches.attendance');
                Route::get('/batches/{batch}/resources', [App\Http\Controllers\Web\Institute\BatchController::class, 'resources'])->name('batches.resources');
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

                // Subscription Plans
                Route::get('/plans', [App\Http\Controllers\Web\Institute\PlanController::class, 'index'])->name('plans.index');

                // Notifications
                Route::get('/notifications', [App\Http\Controllers\Web\Institute\NotificationController::class, 'index'])->name('notifications.index');
                Route::get('/push-notifications', [App\Http\Controllers\Web\Institute\NotificationController::class, 'compose'])->name('push.compose');

                // Staff Management
                Route::get('/staff', [App\Http\Controllers\Web\Institute\StaffController::class, 'index'])->name('staff.index');
                Route::post('/staff', [App\Http\Controllers\Web\Institute\StaffController::class, 'store'])->name('staff.store');
                Route::get('/staff/{staff}', [App\Http\Controllers\Web\Institute\StaffController::class, 'show'])->name('staff.show');
                Route::get('/staff/{staff}/edit', [App\Http\Controllers\Web\Institute\StaffController::class, 'edit'])->name('staff.edit');
                Route::put('/staff/{staff}', [App\Http\Controllers\Web\Institute\StaffController::class, 'update'])->name('staff.update');
                Route::delete('/staff/{staff}', [App\Http\Controllers\Web\Institute\StaffController::class, 'destroy'])->name('staff.destroy');

                Route::get('/leads', function () {
                    return view('institute.leads.index');
                })->name('leads.index');

                Route::get('/notes', function () {
                    return view('institute.notes.index');
                })->name('notes.index');

                // Expense Management
                Route::get('/expenses', function () {
                    return view('institute.expenses.index');
                })->name('expenses.index');

                // Chat Management
                Route::get('/chats', function () {
                    return view('institute.chats.index');
                })->name('chats.index');
            });
        });
    });
});

Route::prefix('admin')->group(function () {
    require __DIR__ . '/auth.php';
});

// =========================================================================
// TEMPORARY EMAIL PREVIEWS (Safe for Development, preview in browser)
// =========================================================================
Route::get('/mail-preview/otp', function () {
    return new \App\Mail\OtpMail('123456', 'John Doe');
});

Route::get('/mail-preview/account-activated', function () {
    return new \App\Mail\AccountActivatedMail('John Doe');
});

Route::get('/mail-preview/forgot-password', function () {
    return new \App\Mail\ForgotPasswordMail('852963', 'John Doe');
});

Route::get('/mail-preview/subscription-status', function () {
    return new \App\Mail\SubscriptionStatusMail(
        'Noble Academy', 
        'Pro Gold Annual Plan', 
        now()->addYear()->toDateTimeString(), 
        9999, 
        'assigned'
    );
});

Route::get('/mail-preview/student-added', function () {
    return new \App\Mail\StudentAddedMail(
        'Rohan Sharma', 
        'rohan@example.com', 
        'secureP@ss123', 
        'Noble Academy'
    );
});

Route::get('/mail-preview/fee-invoice', function () {
    return new \App\Mail\FeeInvoiceMail(
        'Rohan Sharma', 
        'rohan@example.com', 
        'INV-20260530-0042', 
        now()->format('d M, Y'), 
        now()->addDays(10)->format('d M, Y'), 
        'Unpaid', 
        'Monthly Tuition Fee', 
        1500, 
        'Lab & Library Fee', 
        300, 
        50, 
        1850, 
        '#', 
        'Noble Academy'
    );
});

