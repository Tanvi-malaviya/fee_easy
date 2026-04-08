<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\DashboardController;
use App\Http\Controllers\Web\InstituteController;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Admin UI Routes
    Route::patch('institutes/{institute}/status', [InstituteController::class, 'updateStatus'])->name('institutes.status');
    Route::resource('institutes', InstituteController::class);
    
    // Phase 1 Routes
    Route::resource('plans', App\Http\Controllers\Web\PlanController::class);
    Route::patch('subscriptions/{subscription}/extend', [App\Http\Controllers\Web\SubscriptionController::class, 'extend'])->name('subscriptions.extend');
    Route::patch('subscriptions/{subscription}/cancel', [App\Http\Controllers\Web\SubscriptionController::class, 'cancel'])->name('subscriptions.cancel');
    Route::patch('subscriptions/{subscription}/activate', [App\Http\Controllers\Web\SubscriptionController::class, 'activate'])->name('subscriptions.activate');
    Route::patch('subscriptions/{subscription}/convert', [App\Http\Controllers\Web\SubscriptionController::class, 'convertToPaid'])->name('subscriptions.convert');
    Route::resource('subscriptions', App\Http\Controllers\Web\SubscriptionController::class);
    Route::get('revenue', [App\Http\Controllers\Web\RevenueController::class, 'index'])->name('revenue.index');

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

require __DIR__.'/auth.php';
