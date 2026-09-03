<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\DashboardController;
use App\Http\Controllers\Api\InstituteController;
use App\Http\Controllers\Api\V1\PlanController;
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
use App\Http\Controllers\Api\V1\InstituteExamController;
use App\Http\Controllers\Api\V1\InstituteNotificationController;
use App\Http\Controllers\Api\V1\InstituteWhatsappSettingController;
use App\Http\Controllers\Api\V1\InstituteReportController;
use App\Http\Controllers\Api\V1\InstituteSubscriptionController;
use App\Http\Controllers\Api\V1\StudentExamController;
use App\Http\Controllers\Api\V1\StudentProfileController;
use App\Http\Controllers\Api\V1\StudentDashboardController;
use App\Http\Controllers\Api\V1\StudentFeesController;
use App\Http\Controllers\Api\V1\StudentReceiptsController;
use App\Http\Controllers\Api\V1\StudentAttendanceController;
use App\Http\Controllers\Api\V1\StudentDailyUpdateController;
use App\Http\Controllers\Api\V1\StudentHomeworkController;
use App\Http\Controllers\Api\V1\StudentReportController;
use App\Http\Controllers\Api\V1\StudentNotificationController;
use App\Http\Controllers\Api\V1\StudentInstituteController;
use App\Http\Controllers\Api\V1\StudentFeedbackController;
use App\Http\Controllers\Api\V1\StudentResourceController;
use App\Http\Controllers\Api\V1\ParentDashboardController;
use App\Http\Controllers\Api\V1\ParentFeesController;
use App\Http\Controllers\Api\V1\ParentPaymentController;
use App\Http\Controllers\Api\V1\ParentReceiptsController;
use App\Http\Controllers\Api\V1\ParentAttendanceController;
use App\Http\Controllers\Api\V1\ParentHomeworkController;
use App\Http\Controllers\Api\V1\ParentReportController;
use App\Http\Controllers\Api\V1\ParentNotificationController;
use App\Http\Controllers\Api\V1\ParentInstituteController;
use App\Http\Controllers\Api\V1\NoteController;
use App\Http\Controllers\Api\V1\NoteCategoryController;
use App\Http\Controllers\Api\V1\NoteChecklistController;
use App\Http\Controllers\Api\V1\NoteImageController;
use App\Http\Controllers\Api\V1\InstituteTeacherController;
use App\Http\Controllers\Api\V1\InstituteExpenseController;
use App\Http\Controllers\Api\V1\InstituteProfileController;
use App\Http\Controllers\Api\V1\PublicVerificationController;
use App\Http\Controllers\Api\V1\TeacherAuthController;
use App\Http\Controllers\Api\V1\TeacherProfileController;
use App\Http\Controllers\Api\V1\TeacherBatchController;
use App\Http\Controllers\Api\V1\TeacherStudentController;
use App\Http\Controllers\Api\V1\TeacherAttendanceController;
use App\Http\Controllers\Api\V1\TeacherSelfAttendanceController;
use App\Http\Controllers\Api\V1\TeacherHomeworkController;
use App\Http\Controllers\Api\V1\TeacherExamController;
use App\Http\Controllers\Api\V1\TeacherTimetableController;
use App\Http\Controllers\Api\V1\TeacherFeesController;
use App\Http\Controllers\Api\V1\TeacherSalaryController;

use App\Http\Controllers\Api\DemoRequestController;
use App\Http\Controllers\Api\StaffController;
use App\Http\Controllers\Api\V1\FCMTokenController;

/*
|--------------------------------------------------------------------------
| API Routes - Version 1 (v1)
|--------------------------------------------------------------------------
*/

Route::post('/book-demo', [DemoRequestController::class, 'store']);
Route::get('/public-plans', [PlanController::class, 'index']);

Route::prefix('v1')->group(function () {

    // System Versions
    Route::get('/app-versions', [App\Http\Controllers\Api\V1\SystemVersionController::class, 'index']);

    // Public, unauthenticated branding lookup — the app calls this at launch
    // with its baked-in institute_id, before any user is logged in.
    Route::get('/app-branding', [App\Http\Controllers\Api\V1\AppBrandingController::class, 'show']);

    // Mobile App FCM Device Registration
    Route::middleware('auth:sanctum')->post('/fcm-token', [FCMTokenController::class, 'updateToken']);

    // Admin Auth Routes (Original)
    Route::prefix('auth')->group(function () {
        Route::post('/login', [AuthController::class, 'login']);
        Route::post('/refresh', [\App\Http\Controllers\Api\V1\TokenRefreshController::class, 'refresh']);
        Route::middleware('auth:sanctum')->group(function () {
            Route::post('/logout', [AuthController::class, 'logout']);
            Route::get('/profile', [AuthController::class, 'profile']);
        });
    });

    // Institute Routes
    Route::prefix('institute')->group(function () {
        Route::post('/register', [InstituteAuthController::class, 'register']);
        Route::post('/verify-otp', [InstituteAuthController::class, 'verifyOtp']);
        Route::post('/resend-otp', [InstituteAuthController::class, 'resendOtp']);
        Route::post('/reports/attendance/resend-otp', [InstituteAuthController::class, 'resendOtp']);
        Route::post('/login', [InstituteAuthController::class, 'login']);
        Route::post('/forgot-password', [InstituteAuthController::class, 'sendResetPasswordEmail']);
        Route::post('/reset-password', [InstituteAuthController::class, 'resetPassword']);
        Route::post('/subscription/webhook', [InstituteSubscriptionController::class, 'handleWebhook']);

        Route::middleware(['auth:sanctum,institute', 'active_institute', 'check_subscription'])->group(function () {
            Route::post('/logout', [InstituteAuthController::class, 'logout']);
            Route::get('/profile', [InstituteProfileController::class, 'show']);
            Route::post('/profile/update', [InstituteProfileController::class, 'update']);
            Route::post('/profile/payment/update', [InstituteProfileController::class, 'updatePaymentSettings']);
            Route::post('/profile/change-password', [InstituteProfileController::class, 'changePassword']);
            Route::post('/profile/template/update', [InstituteProfileController::class, 'updateTemplate']);
            Route::delete('/profile/delete', [InstituteProfileController::class, 'destroy']);
            Route::delete('/profile/device-sessions/{id}', [InstituteProfileController::class, 'logoutDeviceSession']);
            Route::post('/logo/upload', [InstituteProfileController::class, 'update']); // Alias to update with logo

            Route::post('/daily-updates', [InstituteDailyUpdateController::class, 'store']);
            Route::get('/daily-updates', [InstituteDailyUpdateController::class, 'index']);

            Route::post('/homeworks', [InstituteHomeworkController::class, 'store']);
            Route::get('/homeworks', [InstituteHomeworkController::class, 'index']);
            Route::get('/homeworks/{id}', [InstituteHomeworkController::class, 'show']);
            Route::post('/homeworks/{id}/score', [InstituteHomeworkController::class, 'updateScore']);
            Route::post('/homeworks/{id}/grades', [InstituteHomeworkController::class, 'updateGrades']);
            Route::post('/homeworks/{id}/reminder', [InstituteHomeworkController::class, 'sendReminder']);

            // Exam Module Routes (Common for Mobile App & Web Panel)
            Route::get('/exams', [InstituteExamController::class, 'index']);
            Route::post('/exams', [InstituteExamController::class, 'store']);
            Route::get('/exams/{id}', [InstituteExamController::class, 'show']);
            Route::put('/exams/{id}', [InstituteExamController::class, 'update']);
            Route::post('/exams/{id}/update', [InstituteExamController::class, 'update']);
            Route::delete('/exams/{id}', [InstituteExamController::class, 'destroy']);
            Route::get('/exams/{id}/marks', [InstituteExamController::class, 'getMarks']);
            Route::post('/exams/{id}/marks', [InstituteExamController::class, 'saveMarks']);

            Route::post('/notifications/send', [InstituteNotificationController::class, 'send']);
            Route::post('/notifications/send-push', [InstituteNotificationController::class, 'sendPush']);
            Route::get('/notifications/recipient-stats', [InstituteNotificationController::class, 'recipientStats']);
            Route::get('/notifications', [InstituteNotificationController::class, 'index']);
            Route::post('/notifications/mark-all-read', [InstituteNotificationController::class, 'markAllRead']);

            // Plan and Subscription routes
            Route::get('/plans', [PlanController::class, 'index']);
            Route::get('/subscriptions/all-data', [InstituteSubscriptionController::class, 'allData']);
            Route::post('/subscription/iap-verify', [InstituteSubscriptionController::class, 'verifyIap']);
            Route::post('/subscriptions/verify-payment', [InstituteSubscriptionController::class, 'verifyPayment']);
            Route::post('/subscriptions/purchase', [InstituteSubscriptionController::class, 'purchase']);
            Route::post('/subscription/create-order', [InstituteSubscriptionController::class, 'createOrder']);
            Route::post('/subscription/verify-payment', [InstituteSubscriptionController::class, 'verifyPaymentAndroid']);
            Route::get('/subscriptions/history', [InstituteSubscriptionController::class, 'history']);

            // White Label add-on
            Route::prefix('whitelabel')->group(function () {
                Route::get('/', [\App\Http\Controllers\Api\V1\InstituteWhiteLabelController::class, 'show']);
                Route::post('/create-order', [\App\Http\Controllers\Api\V1\InstituteWhiteLabelController::class, 'createOrder']);
                Route::post('/verify-payment', [\App\Http\Controllers\Api\V1\InstituteWhiteLabelController::class, 'verifyPayment']);
                Route::post('/branding', [\App\Http\Controllers\Api\V1\InstituteWhiteLabelController::class, 'updateBranding']);
            });

            // Generic add-ons catalog (flag/quota kind add-ons — custom-kind
            // ones like White Label keep using their own dedicated endpoints above)
            Route::prefix('addons')->group(function () {
                Route::get('/', [\App\Http\Controllers\Api\V1\InstituteAddOnController::class, 'index']);
                Route::post('/{addOn}/create-order', [\App\Http\Controllers\Api\V1\InstituteAddOnController::class, 'createOrder']);
                Route::post('/{addOn}/verify-payment', [\App\Http\Controllers\Api\V1\InstituteAddOnController::class, 'verifyPayment']);
            });

            // Clean Rich Notes Module
            Route::prefix('notes')->group(function () {
                Route::get('/', [NoteController::class, 'index']);
                Route::post('/', [NoteController::class, 'store']);
                Route::get('/{id}', [NoteController::class, 'show']);
                Route::put('/{id}', [NoteController::class, 'update']);
                Route::delete('/{id}', [NoteController::class, 'destroy']);

                // Actions
                Route::post('/{id}/bookmark', [NoteController::class, 'bookmark']);
                Route::post('/checklists/{id}/toggle', [NoteController::class, 'toggleChecklist']);
                Route::delete('/checklists/{id}', [NoteController::class, 'destroyChecklist']);
            });

            Route::get('/note-categories', [NoteCategoryController::class, 'index']);
            Route::post('/note-categories', [NoteCategoryController::class, 'store']);

            // Legacy Teacher roster — read-only archive. Teacher management now
            // happens entirely through the Staff CRUD (see the /teacher/* group
            // below and the Staff web screens); create/update/delete/mark-attendance
            // on the old `teachers` table were retired once legacy rows were
            // migrated into `staff` / `staff_attendances`.
            Route::prefix('teachers')->group(function () {
                Route::get('/', [InstituteTeacherController::class, 'index']);
                Route::get('/attendance/report', [InstituteTeacherController::class, 'attendanceReport']);
                Route::get('/attendance', [InstituteTeacherController::class, 'getAttendance']);
            });

            Route::prefix('expenses')->group(function () {
                Route::get('/dashboard', [InstituteExpenseController::class, 'dashboard']);
                Route::get('/export', [InstituteExpenseController::class, 'report']);
                Route::get('/analysis', [InstituteExpenseController::class, 'analysis']);
                Route::get('/report', [InstituteExpenseController::class, 'report']);
                Route::get('/categories', [InstituteExpenseController::class, 'getCategories']);
                Route::post('/categories', [InstituteExpenseController::class, 'storeCategory']);

                Route::get('/', [InstituteExpenseController::class, 'index']);
                Route::post('/', [InstituteExpenseController::class, 'store']);
                Route::put('/{id}', [InstituteExpenseController::class, 'update']);
                Route::delete('/{id}', [InstituteExpenseController::class, 'destroy']);
            });

            // Birthdays
            Route::get('/birthdays', [InstituteStudentController::class, 'birthdays']);

            Route::get('/whatsapp-settings', [InstituteWhatsappSettingController::class, 'show']);
            Route::post('/whatsapp-settings', [InstituteWhatsappSettingController::class, 'store']);
            Route::put('/whatsapp-settings', [InstituteWhatsappSettingController::class, 'update']);

            Route::get('/reports/dashboard', [InstituteReportController::class, 'dashboard']);
            Route::get('/reports/analytics', [InstituteReportController::class, 'analytics']);
            Route::get('/reports/fee', [InstituteReportController::class, 'feeReport']);
            Route::get('/reports/fee/export', [InstituteReportController::class, 'exportFeeReport']);
            Route::get('/reports/attendance', [InstituteReportController::class, 'attendanceReport']);
            Route::get('/reports/attendance/export', [InstituteReportController::class, 'exportAttendanceReport']);
            Route::get('/reports/performance', [InstituteReportController::class, 'performanceReport']);
            Route::get('/reports/performance/export', [InstituteReportController::class, 'exportPerformanceReport']);
            Route::get('/reports/student', [InstituteReportController::class, 'studentReport']);
            Route::get('/reports/student/export', [InstituteReportController::class, 'exportStudentReport']);

            Route::get('/subscription', [InstituteSubscriptionController::class, 'show']);
            Route::post('/subscription/renew', [InstituteSubscriptionController::class, 'renew']);

            // Student Management
            Route::prefix('students')->group(function () {
                Route::post('/bulk-transfer', [InstituteStudentController::class, 'bulkTransfer']);
                Route::post('/import', [InstituteStudentController::class, 'import']);
                Route::get('/', [InstituteStudentController::class, 'index']);
                Route::post('/', [InstituteStudentController::class, 'store']);
                Route::get('/{id}', [InstituteStudentController::class, 'show']);
                Route::put('/{id}', [InstituteStudentController::class, 'update']);
                Route::delete('/{id}', [InstituteStudentController::class, 'destroy']);
                Route::get('/{id}/id-card', [InstituteStudentController::class, 'idCard']);
                Route::post('/{id}/fee-reminder', [InstituteStudentController::class, 'sendFeeReminder']);
                Route::post('/{id}/send-password', [InstituteStudentController::class, 'sendPasswordEmail']);
                Route::post('/{id}/reset-password', [InstituteStudentController::class, 'resetPasswordDirect']);
            });

            // Batch Management
            Route::prefix('batches')->group(function () {
                Route::get('/export', [InstituteBatchController::class, 'export']);
                Route::get('/', [InstituteBatchController::class, 'index']);
                Route::post('/', [InstituteBatchController::class, 'store']);
                Route::get('/{id}', [InstituteBatchController::class, 'show']);
                Route::put('/{id}', [InstituteBatchController::class, 'update']);
                Route::post('/{id}/remove-student', [InstituteBatchController::class, 'removeStudent']);
                Route::post('/{id}/assign-students', [InstituteBatchController::class, 'assignStudents']);
                Route::post('/{id}/close', [InstituteBatchController::class, 'close']);
                Route::post('/{id}/fee-reminders', [InstituteBatchController::class, 'sendFeeReminders']);
                Route::delete('/{id}', [InstituteBatchController::class, 'destroy']);
            });

            // Fees Management
            Route::prefix('fees')->group(function () {
                Route::get('/export', [InstituteFeeController::class, 'export']);
                Route::get('/receipts/{id}', [InstituteFeeController::class, 'showReceipt']);
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

            // Student/Batch Attendance Management (distinct from staff attendance)
            Route::prefix('batch-attendance')->group(function () {
                Route::get('/', [InstituteAttendanceController::class, 'index']);
                Route::post('/', [InstituteAttendanceController::class, 'store']);
            });

            // Resources Management
            Route::prefix('resources')->group(function () {
                Route::get('/', [\App\Http\Controllers\Api\V1\InstituteResourceController::class, 'index']);
                Route::post('/', [\App\Http\Controllers\Api\V1\InstituteResourceController::class, 'store']);
                Route::get('/{id}/download', [\App\Http\Controllers\Api\V1\InstituteResourceController::class, 'download']);
                Route::delete('/{id}', [\App\Http\Controllers\Api\V1\InstituteResourceController::class, 'destroy']);
            });

            // Staff Management Routes
            Route::prefix('staff')->group(function () {
                Route::get('/', [StaffController::class, 'index']);
                Route::post('/', [StaffController::class, 'store']);
                Route::get('/{id}', [StaffController::class, 'show']);
                Route::put('/{id}', [StaffController::class, 'update']);
                Route::delete('/{id}', [StaffController::class, 'destroy']);
            });

            Route::get('/staff-roles', [StaffController::class, 'getRoles']);
            Route::post('/staff-roles', [StaffController::class, 'storeRole']);
            Route::get('/staff-departments', [StaffController::class, 'getDepartments']);
            Route::post('/staff-departments', [StaffController::class, 'storeDepartment']);

            // Staff List for dropdowns


            // Attendance Management
            Route::prefix('attendance')->group(function () {
                Route::get('/', [\App\Http\Controllers\Api\StaffAttendanceController::class, 'index']);
                Route::post('/', [\App\Http\Controllers\Api\StaffAttendanceController::class, 'store']);
                Route::delete('/{id}', [\App\Http\Controllers\Api\StaffAttendanceController::class, 'destroy']);
                Route::get('/export', [\App\Http\Controllers\Api\StaffAttendanceController::class, 'export']);
                Route::get('/{staff_id}', [\App\Http\Controllers\Api\StaffAttendanceController::class, 'showByStaff']);
            });

            // Salary Management
            Route::prefix('salaries')->group(function () {
                Route::get('/', [\App\Http\Controllers\Api\StaffSalaryController::class, 'index']);
                Route::post('/', [\App\Http\Controllers\Api\StaffSalaryController::class, 'store']);
                Route::get('/preview/{staff_id}', [\App\Http\Controllers\Api\StaffSalaryController::class, 'preview']);
                Route::get('/export', [\App\Http\Controllers\Api\StaffSalaryController::class, 'export']);
                Route::get('/{staff_id}', [\App\Http\Controllers\Api\StaffSalaryController::class, 'showByStaff']);
            });

            // Timetable Management
            Route::prefix('timetable')->group(function () {
                Route::get('/', [\App\Http\Controllers\Api\V1\InstituteTimetableController::class, 'index']);
                Route::post('/', [\App\Http\Controllers\Api\V1\InstituteTimetableController::class, 'store']);
                Route::put('/{id}', [\App\Http\Controllers\Api\V1\InstituteTimetableController::class, 'update']);
                Route::post('/{id}/update', [\App\Http\Controllers\Api\V1\InstituteTimetableController::class, 'update']);
                Route::delete('/{id}', [\App\Http\Controllers\Api\V1\InstituteTimetableController::class, 'destroy']);
            });

            Route::prefix('leads')->group(function () {
                Route::get('/', [\App\Http\Controllers\Api\LeadController::class, 'index']);
                Route::post('/', [\App\Http\Controllers\Api\LeadController::class, 'store']);
                Route::get('/{id}', [\App\Http\Controllers\Api\LeadController::class, 'show']);
                Route::put('/{id}', [\App\Http\Controllers\Api\LeadController::class, 'update']);
                Route::put('/{id}/status', [\App\Http\Controllers\Api\LeadController::class, 'updateStatus']);
                Route::post('/{id}/notes', [\App\Http\Controllers\Api\LeadController::class, 'addNote']);
                Route::post('/{id}/convert', [\App\Http\Controllers\Api\LeadController::class, 'convert']);
                Route::delete('/{id}', [\App\Http\Controllers\Api\LeadController::class, 'destroy']);
            });
        });
    });

    // Student Auth Routes
    Route::prefix('student')->group(function () {
        Route::post('/login', [StudentAuthController::class, 'login']);
        Route::post('/forgot-password', [StudentAuthController::class, 'sendResetPasswordEmail']);
        Route::middleware('auth:sanctum')->group(function () {
            Route::post('/logout', [StudentAuthController::class, 'logout']);
            Route::get('/profile', [StudentProfileController::class, 'show']);
            Route::post('/profile/avatar', [StudentProfileController::class, 'updateAvatar']);
            Route::post('/profile/change-password', [StudentProfileController::class, 'changePassword']);
            Route::delete('/profile/delete', [StudentProfileController::class, 'destroy']);
            Route::get('/dashboard', [StudentDashboardController::class, 'index']);
            Route::get('/fees', [StudentFeesController::class, 'index']);
            Route::get('/fees/{id}', [StudentFeesController::class, 'show']);
            Route::get('/fees/{id}/download', [StudentFeesController::class, 'download'])->name('student.fees.download');
            Route::get('/receipts', [StudentReceiptsController::class, 'index']);
            Route::get('/receipts/{id}', [StudentReceiptsController::class, 'show']);
            Route::get('/receipts/{id}/download', [StudentReceiptsController::class, 'download'])->name('student.receipts.download');
            Route::get('/attendance', [StudentAttendanceController::class, 'index']);
            Route::get('/daily-updates', [StudentDailyUpdateController::class, 'index']);
            Route::get('/homeworks', [StudentHomeworkController::class, 'index']);
            Route::get('/homeworks/{id}', [StudentHomeworkController::class, 'show']);
            Route::post('/homeworks/{id}/submit', [StudentHomeworkController::class, 'submit']);
            Route::get('/homeworks/{id}/attachment', [StudentHomeworkController::class, 'attachmentInfo']);
            Route::get('/homeworks/{id}/attachment/download', [StudentHomeworkController::class, 'attachmentDownload']);
            Route::get('/exams', [StudentExamController::class, 'index']);
            Route::get('/exams/{id}', [StudentExamController::class, 'show']);
            Route::get('/report', [StudentReportController::class, 'index']);
            Route::get('/notifications', [StudentNotificationController::class, 'index']);
            Route::get('/notifications/{id}/attachment/download', [StudentNotificationController::class, 'downloadAttachment']);
            Route::post('/notifications/{id}/read', [StudentNotificationController::class, 'markAsRead']);
            Route::post('/notifications/mark-all-read', [StudentNotificationController::class, 'markAllRead']);
            Route::get('/institute', [StudentInstituteController::class, 'show']);
            Route::get('/payment-info', [StudentInstituteController::class, 'paymentInfo']);
            Route::post('/feedback', [StudentFeedbackController::class, 'store']);
            Route::get('/resources', [StudentResourceController::class, 'index']);
            Route::get('/resources/{id}/download', [StudentResourceController::class, 'download']);
            Route::get('/timetable', [\App\Http\Controllers\Api\V1\InstituteTimetableController::class, 'studentSchedule']);
            Route::get('/birthdays', [\App\Http\Controllers\Api\V1\StudentBirthdayController::class, 'index']);
            Route::get('/id-card', [\App\Http\Controllers\Api\V1\StudentIdCardController::class, 'show']);
            Route::get('/notification-settings', [\App\Http\Controllers\Api\V1\NotificationSettingController::class, 'getSettings']);
            Route::post('/notification-settings', [\App\Http\Controllers\Api\V1\NotificationSettingController::class, 'updateSettings']);
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
            Route::get('/homeworks', [ParentHomeworkController::class, 'index']);
            Route::get('/report', [ParentReportController::class, 'index']);
            Route::get('/notifications', [ParentNotificationController::class, 'index']);
            Route::get('/notification-settings', [\App\Http\Controllers\Api\V1\NotificationSettingController::class, 'getSettings']);
            Route::post('/notification-settings', [\App\Http\Controllers\Api\V1\NotificationSettingController::class, 'updateSettings']);
            Route::get('/institute', [ParentInstituteController::class, 'show']);
            Route::get('/payment-info', [ParentInstituteController::class, 'paymentInfo']);
        });
    });

    // Teacher Auth & Self-Service Routes
    Route::prefix('teacher')->group(function () {
        Route::post('/login', [TeacherAuthController::class, 'login']);
        Route::post('/forgot-password', [TeacherAuthController::class, 'sendResetPasswordEmail']);
        Route::post('/reset-password', [TeacherAuthController::class, 'resetPassword']);

        Route::middleware(['auth:sanctum,teacher', 'active_teacher'])->group(function () {
            Route::post('/logout', [TeacherAuthController::class, 'logout']);
            Route::post('/change-password', [TeacherAuthController::class, 'changePassword']);

            Route::get('/profile', [TeacherProfileController::class, 'show']);
            Route::post('/profile/avatar', [TeacherProfileController::class, 'updateAvatar']);

            // Batches assigned to this teacher — no create/delete, ever.
            Route::get('/batches', [TeacherBatchController::class, 'index']);
            Route::get('/batches/{id}', [TeacherBatchController::class, 'show']);

            Route::get('/batches/{batch}/students', [TeacherStudentController::class, 'index']);
            Route::post('/batches/{batch}/students', [TeacherStudentController::class, 'store']);
            Route::post('/batches/{batch}/students/{student}/remove', [TeacherStudentController::class, 'removeStudent']);

            Route::get('/attendance', [TeacherAttendanceController::class, 'index']);
            Route::post('/attendance', [TeacherAttendanceController::class, 'store']);

            Route::get('/self-attendance/today', [TeacherSelfAttendanceController::class, 'today']);
            Route::get('/self-attendance', [TeacherSelfAttendanceController::class, 'index']);
            Route::post('/self-attendance', [TeacherSelfAttendanceController::class, 'store']);

            Route::get('/homeworks', [TeacherHomeworkController::class, 'index']);
            Route::post('/homeworks', [TeacherHomeworkController::class, 'store']);
            Route::get('/homeworks/{id}', [TeacherHomeworkController::class, 'show']);
            Route::put('/homeworks/{id}', [TeacherHomeworkController::class, 'update']);
            Route::post('/homeworks/{id}/grades', [TeacherHomeworkController::class, 'updateGrades']);
            Route::delete('/homeworks/{id}', [TeacherHomeworkController::class, 'destroy']);

            Route::get('/exams', [TeacherExamController::class, 'index']);
            Route::post('/exams', [TeacherExamController::class, 'store']);
            Route::get('/exams/{id}', [TeacherExamController::class, 'show']);
            Route::put('/exams/{id}', [TeacherExamController::class, 'update']);
            Route::delete('/exams/{id}', [TeacherExamController::class, 'destroy']);
            Route::get('/exams/{id}/marks', [TeacherExamController::class, 'getMarks']);
            Route::post('/exams/{id}/marks', [TeacherExamController::class, 'saveMarks']);

            Route::get('/timetable', [TeacherTimetableController::class, 'index']);
            Route::post('/timetable', [TeacherTimetableController::class, 'store']);
            Route::put('/timetable/{id}', [TeacherTimetableController::class, 'update']);
            Route::delete('/timetable/{id}', [TeacherTimetableController::class, 'destroy']);

            // Read-only, gated per-batch by the institute's teacher_can_view_fees toggle.
            Route::get('/fees', [TeacherFeesController::class, 'index']);

            Route::get('/salaries', [TeacherSalaryController::class, 'index']);
            Route::get('/salaries/{id}/download', [TeacherSalaryController::class, 'download']);
        });
    });

    // Protected App Routes (Default Admin/Sanctum)
    Route::middleware('auth:sanctum')->name('api.')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index']);
        Route::apiResource('institutes', InstituteController::class);
        Route::apiResource('plans', PlanController::class);
        Route::apiResource('subscriptions', SubscriptionController::class)->except(['destroy']);
        Route::get('/payments', [PaymentController::class, 'index']);
        Route::post('/notifications', [NotificationController::class, 'store']);

        // Chat Routes
        Route::prefix('chat')->group(function () {
            Route::get('/contacts', [\App\Http\Controllers\Api\V1\ChatController::class, 'contacts']);
            Route::get('/list', [\App\Http\Controllers\Api\V1\ChatController::class, 'list']);
            Route::get('/messages/{user_id}', [\App\Http\Controllers\Api\V1\ChatController::class, 'messages']);
            Route::post('/send', [\App\Http\Controllers\Api\V1\ChatController::class, 'send']);
            Route::post('/mark-read', [\App\Http\Controllers\Api\V1\ChatController::class, 'markAsRead']);
            Route::post('/mark-received', [\App\Http\Controllers\Api\V1\ChatController::class, 'markAsReceived']);
            Route::delete('/conversation', [\App\Http\Controllers\Api\V1\ChatController::class, 'clearConversation']);
        });

        // Community Routes
        Route::prefix('community')->group(function () {
            Route::get('/list', [\App\Http\Controllers\Api\V1\CommunityController::class, 'list']);
            Route::get('/members', [\App\Http\Controllers\Api\V1\CommunityController::class, 'members']);
            Route::get('/messages', [\App\Http\Controllers\Api\V1\CommunityController::class, 'messages']);
            Route::post('/send', [\App\Http\Controllers\Api\V1\CommunityController::class, 'send']);
        });
    });

    // Public Student ID Verification
    Route::post('/public/verify-id', [PublicVerificationController::class, 'verifyID']);
});
