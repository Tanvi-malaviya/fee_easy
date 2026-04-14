<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\DashboardController;
use App\Http\Controllers\Api\InstituteController;
use App\Http\Controllers\Api\PlanController;
use App\Http\Controllers\Api\SubscriptionController;
use App\Http\Controllers\Api\PaymentController;
use App\Http\Controllers\Api\NotificationController;
use App\Http\Controllers\Api\V1\InstituteAuthController;
use App\Http\Controllers\Api\V1\StudentAuthController;
use App\Http\Controllers\Api\V1\ParentAuthController;
use App\Http\Controllers\Api\V1\InstituteStudentController;

/*
|--------------------------------------------------------------------------
| API Routes - Version 1 (v1)
|--------------------------------------------------------------------------
*/

Route::prefix('v1')->group(function () {

    // Admin Auth Routes (Original)
    Route::prefix('auth')->group(function () {
        Route::post('/login', [AuthController::class, 'login']);
        Route::middleware('auth:sanctum')->group(function () {
            Route::post('/logout', [AuthController::class, 'logout']);
            Route::get('/profile', [AuthController::class, 'profile']);
        });
    });

    // Institute Routes
    Route::prefix('institute')->group(function () {
        Route::post('/login', [InstituteAuthController::class, 'login']);
        
        Route::middleware('auth:sanctum')->group(function () {
            Route::post('/logout', [InstituteAuthController::class, 'logout']);
            Route::get('/profile', [InstituteAuthController::class, 'profile']);
            
            // Student Management for Institute
            Route::prefix('students')->group(function () {
                Route::get('/', [InstituteStudentController::class, 'index']);
                Route::post('/', [InstituteStudentController::class, 'store']);
                Route::get('/{id}', [InstituteStudentController::class, 'show']);
                Route::put('/{id}', [InstituteStudentController::class, 'update']);
                Route::delete('/{id}', [InstituteStudentController::class, 'destroy']);
            });
        });
    });

    // Student Auth Routes
    Route::prefix('student')->group(function () {
        Route::post('/login', [StudentAuthController::class, 'login']);
        Route::middleware('auth:sanctum')->group(function () {
            Route::post('/logout', [StudentAuthController::class, 'logout']);
            Route::get('/profile', [StudentAuthController::class, 'profile']);
        });
    });

    // Parent Auth Routes
    Route::prefix('parent')->group(function () {
        Route::post('/login', [ParentAuthController::class, 'login']);
        Route::middleware('auth:sanctum')->group(function () {
            Route::post('/logout', [ParentAuthController::class, 'logout']);
            Route::get('/profile', [ParentAuthController::class, 'profile']);
        });
    });

    // Protected App Routes (Default Admin/Sanctum)
    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index']);
        Route::apiResource('institutes', InstituteController::class);
        Route::apiResource('plans', PlanController::class);
        Route::apiResource('subscriptions', SubscriptionController::class)->except(['destroy']);
        Route::get('/payments', [PaymentController::class, 'index']);
        Route::post('/notifications', [NotificationController::class, 'store']);
    });

});
