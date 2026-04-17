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
use App\Http\Controllers\Api\V1\InstituteBatchController;
use App\Http\Controllers\Api\V1\InstituteFeeController;
use App\Http\Controllers\Api\V1\InstitutePaymentController;
use App\Http\Controllers\Api\V1\InstituteReceiptController;
use App\Http\Controllers\Api\V1\InstituteAttendanceController;
use App\Http\Controllers\Api\V1\InstituteDailyUpdateController;
use App\Http\Controllers\Api\V1\InstituteHomeworkController;
use App\Http\Controllers\Api\V1\InstituteNotificationController;
use App\Http\Controllers\Api\V1\InstituteWhatsappSettingController;
use App\Http\Controllers\Api\V1\InstituteReportController;
use App\Http\Controllers\Api\V1\InstituteSubscriptionController;
use App\Http\Controllers\Api\V1\StudentProfileController;
use App\Http\Controllers\Api\V1\StudentDashboardController;
use App\Http\Controllers\Api\V1\StudentFeesController;
use App\Http\Controllers\Api\V1\StudentReceiptsController;
use App\Http\Controllers\Api\V1\StudentAttendanceController;
use App\Http\Controllers\Api\V1\StudentDailyUpdateController;
use App\Http\Controllers\Api\V1\StudentHomeworkController;
use App\Http\Controllers\Api\V1\StudentReportController;
use App\Http\Controllers\Api\V1\StudentNotificationController;
use App\Http\Controllers\Api\V1\ParentDashboardController;
use App\Http\Controllers\Api\V1\ParentFeesController;
use App\Http\Controllers\Api\V1\ParentPaymentController;
use App\Http\Controllers\Api\V1\ParentReceiptsController;
use App\Http\Controllers\Api\V1\ParentAttendanceController;
use App\Http\Controllers\Api\V1\ParentDailyUpdateController;
use App\Http\Controllers\Api\V1\ParentHomeworkController;
use App\Http\Controllers\Api\V1\ParentReportController;
use App\Http\Controllers\Api\V1\ParentNotificationController;

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
            Route::post('/logo/upload', [InstituteAuthController::class, 'uploadLogo']);

            Route::post('/daily-updates', [InstituteDailyUpdateController::class, 'store']);
            Route::get('/daily-updates', [InstituteDailyUpdateController::class, 'index']);

            Route::post('/homeworks', [InstituteHomeworkController::class, 'store']);
            Route::get('/homeworks', [InstituteHomeworkController::class, 'index']);

            Route::post('/notifications/send', [InstituteNotificationController::class, 'send']);
            Route::get('/notifications', [InstituteNotificationController::class, 'index']);

            Route::get('/whatsapp-settings', [InstituteWhatsappSettingController::class, 'show']);
            Route::post('/whatsapp-settings', [InstituteWhatsappSettingController::class, 'store']);
            Route::put('/whatsapp-settings', [InstituteWhatsappSettingController::class, 'update']);

            Route::get('/reports/dashboard', [InstituteReportController::class, 'dashboard']);
            Route::get('/reports/income', [InstituteReportController::class, 'income']);
            Route::get('/reports/fees', [InstituteReportController::class, 'fees']);

            Route::get('/subscription', [InstituteSubscriptionController::class, 'show']);
            Route::post('/subscription/renew', [InstituteSubscriptionController::class, 'renew']);

            // Student Management
            Route::prefix('students')->group(function () {
                Route::get('/', [InstituteStudentController::class, 'index']);
                Route::post('/', [InstituteStudentController::class, 'store']);
                Route::get('/{id}', [InstituteStudentController::class, 'show']);
                Route::put('/{id}', [InstituteStudentController::class, 'update']);
                Route::delete('/{id}', [InstituteStudentController::class, 'destroy']);
            });

            // Batch Management
            Route::prefix('batches')->group(function () {
                Route::get('/', [InstituteBatchController::class, 'index']);
                Route::post('/', [InstituteBatchController::class, 'store']);
                Route::put('/{id}', [InstituteBatchController::class, 'update']);
                Route::delete('/{id}', [InstituteBatchController::class, 'destroy']);
            });

            // Fees Management
            Route::prefix('fees')->group(function () {
                Route::get('/', [InstituteFeeController::class, 'index']);
                Route::post('/', [InstituteFeeController::class, 'store']);
                Route::get('/{student_id}', [InstituteFeeController::class, 'getStudentFees']);
            });

            // Payments Management
            Route::prefix('payments')->group(function () {
                Route::post('/', [InstitutePaymentController::class, 'store']);
                Route::get('/{student_id}', [InstitutePaymentController::class, 'getStudentPayments']);
            });

            // Receipts Management
            Route::get('/receipts/{student_id}', [InstituteReceiptController::class, 'getStudentReceipts']);
            Route::get('/receipt/{id}/download', [InstituteReceiptController::class, 'downloadReceipt']);

            // Attendance Management
            Route::prefix('attendance')->group(function () {
                Route::get('/', [InstituteAttendanceController::class, 'index']);
                Route::post('/', [InstituteAttendanceController::class, 'store']);
            });
        });
    });

    // Student Auth Routes
    Route::prefix('student')->group(function () {
        Route::post('/login', [StudentAuthController::class, 'login']);
        Route::middleware('auth:sanctum')->group(function () {
            Route::post('/logout', [StudentAuthController::class, 'logout']);
            Route::get('/profile', [StudentProfileController::class, 'show']);
            Route::get('/dashboard', [StudentDashboardController::class, 'index']);
            Route::get('/fees', [StudentFeesController::class, 'index']);
            Route::get('/receipts', [StudentReceiptsController::class, 'index']);
            Route::get('/attendance', [StudentAttendanceController::class, 'index']);
            Route::get('/daily-updates', [StudentDailyUpdateController::class, 'index']);
            Route::get('/homeworks', [StudentHomeworkController::class, 'index']);
            Route::get('/report', [StudentReportController::class, 'index']);
            Route::get('/notifications', [StudentNotificationController::class, 'index']);
        });
    });

    // Parent Auth Routes
    Route::prefix('parent')->group(function () {
        Route::post('/login', [ParentAuthController::class, 'login']);
        Route::middleware('auth:sanctum')->group(function () {
            Route::post('/logout', [ParentAuthController::class, 'logout']);
            Route::get('/profile', [ParentAuthController::class, 'profile']);
            Route::get('/dashboard', [ParentDashboardController::class, 'index']);
            Route::get('/fees', [ParentFeesController::class, 'index']);
            Route::post('/pay-fee', [ParentPaymentController::class, 'store']);
            Route::get('/receipts', [ParentReceiptsController::class, 'index']);
            Route::get('/attendance', [ParentAttendanceController::class, 'index']);
            Route::get('/daily-updates', [ParentDailyUpdateController::class, 'index']);
            Route::get('/homeworks', [ParentHomeworkController::class, 'index']);
            Route::get('/report', [ParentReportController::class, 'index']);
            Route::get('/notifications', [ParentNotificationController::class, 'index']);
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

        // Chat Routes
        Route::prefix('chat')->group(function () {
            Route::get('/list', [\App\Http\Controllers\Api\V1\ChatController::class, 'list']);
            Route::get('/messages/{user_id}', [\App\Http\Controllers\Api\V1\ChatController::class, 'messages']);
            Route::post('/send', [\App\Http\Controllers\Api\V1\ChatController::class, 'send']);
            Route::delete('/{id}', [\App\Http\Controllers\Api\V1\ChatController::class, 'destroy']);
        });

        // Community Routes
        Route::prefix('community')->group(function () {
            Route::get('/list', [\App\Http\Controllers\Api\V1\CommunityController::class, 'list']);
            Route::get('/members', [\App\Http\Controllers\Api\V1\CommunityController::class, 'members']);
            Route::get('/messages', [\App\Http\Controllers\Api\V1\CommunityController::class, 'messages']);
            Route::post('/send', [\App\Http\Controllers\Api\V1\CommunityController::class, 'send']);
        });
    });

});
