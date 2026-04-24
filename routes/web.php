<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\DashboardController;
use App\Http\Controllers\Web\InstituteController;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Admin UI Routes
    Route::patch('institutes/{institute}/status', [InstituteController::class, 'updateStatus'])->name('institutes.status');
    Route::resource('institutes', InstituteController::class);

    // Phase 1 Routes
    Route::resource('plans', App\Http\Controllers\Web\PlanController::class);
    Route::patch('plans/{plan}/status', [App\Http\Controllers\Web\PlanController::class, 'updateStatus'])->name('plans.status');
    Route::patch('subscriptions/{subscription}/extend', [App\Http\Controllers\Web\SubscriptionController::class, 'extend'])->name('subscriptions.extend');
    Route::patch('subscriptions/{subscription}/cancel', [App\Http\Controllers\Web\SubscriptionController::class, 'cancel'])->name('subscriptions.cancel');
    Route::patch('subscriptions/{subscription}/activate', [App\Http\Controllers\Web\SubscriptionController::class, 'activate'])->name('subscriptions.activate');
    Route::patch('subscriptions/{subscription}/convert', [App\Http\Controllers\Web\SubscriptionController::class, 'convertToPaid'])->name('subscriptions.convert');
    Route::patch('subscriptions/{subscription}/change-plan', [App\Http\Controllers\Web\SubscriptionController::class, 'changePlan'])->name('subscriptions.changePlan');
    Route::resource('subscriptions', App\Http\Controllers\Web\SubscriptionController::class);
    Route::get('revenue', [App\Http\Controllers\Web\RevenueController::class, 'index'])->name('revenue.index');
    Route::post('revenue/record', [App\Http\Controllers\Web\RevenueController::class, 'storeManualPayment'])->name('revenue.store_manual');

    // WhatsApp Management
    Route::get('whatsapp', [App\Http\Controllers\Web\WhatsAppController::class, 'index'])->name('whatsapp.index');
    Route::patch('whatsapp/{institute}/update', [App\Http\Controllers\Web\WhatsAppController::class, 'update'])->name('whatsapp.update');
    Route::post('whatsapp/{institute}/verify', [App\Http\Controllers\Web\WhatsAppController::class, 'verify'])->name('whatsapp.verify');

    // Broadcast Center
    Route::get('broadcast', [App\Http\Controllers\Web\BroadcastController::class, 'index'])->name('broadcast.index');
    Route::post('broadcast/send', [App\Http\Controllers\Web\BroadcastController::class, 'send'])->name('broadcast.send');

    // System Settings
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

// Institute Web Panel Routes
Route::prefix('institute')->name('institute.')->group(function () {
    // Guest Routes
    Route::middleware('guest:institute')->group(function () {
        Route::get('/login', [App\Http\Controllers\Web\Institute\InstituteAuthController::class, 'showLogin'])->name('login');
        Route::post('/login', [App\Http\Controllers\Web\Institute\InstituteAuthController::class, 'login']);
        Route::get('/register', [App\Http\Controllers\Web\Institute\InstituteAuthController::class, 'showRegister'])->name('register');
        Route::post('/register', [App\Http\Controllers\Web\Institute\InstituteAuthController::class, 'register']);
    });

    // Authenticated Routes
    Route::post('/logout', [App\Http\Controllers\Web\Institute\InstituteAuthController::class, 'logout'])->name('logout');

    Route::middleware('auth:institute')->group(function () {
        Route::get('/verify-otp', [App\Http\Controllers\Web\Institute\InstituteAuthController::class, 'showVerifyOtp'])->name('verify-otp');
        Route::post('/verify-otp', [App\Http\Controllers\Web\Institute\InstituteAuthController::class, 'verifyOtp']);

        Route::get('/profile', function() { return view('institute.profile.index'); })->name('profile.index');

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

            Route::get('/teachers', function () { return view('institute.teachers.index'); })->name('teachers.index');


            // Shell Routes for API-Driven Pages (Uses V1 API Controllers)
            Route::get('/batches', function() { return view('institute.batches.index'); })->name('batches.index');
            Route::get('/batches/{id}', function($id) { return view('institute.batches.show', compact('id')); })->name('batches.show');
            Route::get('/attendance', function() { return view('institute.attendance.index'); })->name('attendance.index');
            Route::get('/attendance/mark', function() { return view('institute.attendance.create'); })->name('attendance.create');
            Route::get('/fees', function() { return view('institute.fees.index'); })->name('fees.index');
            Route::get('/reports', function() { return view('institute.reports.index'); })->name('reports.index');
            Route::get('/updates', function() { return view('institute.updates.index'); })->name('updates.index');
            Route::get('/plans', function() { return view('institute.plans.index'); })->name('plans.index');
            Route::get('/whatsapp-settings', function() { return view('institute.whatsapp.index'); })->name('whatsapp.setup');
        });

        Route::post('/profile/password', [App\Http\Controllers\Web\Institute\InstituteAuthController::class, 'updatePassword'])->name('profile.password.update');
    });
});

require __DIR__ . '/auth.php';
