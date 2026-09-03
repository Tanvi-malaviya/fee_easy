# Fee Easy / Tuoora — Module Inventory & Technical Specification

**Stack**: Laravel 10 (PHP 8.1+), Laravel Sanctum (mobile/API auth), Jetstream-style session auth (admin web), Spatie Laravel-Permission, Razorpay PHP SDK, Pusher + BeyondCode Laravel-WebSockets, Barryvdh Laravel-DomPDF, Khanamiryan QR Code Detector/Decoder, Google Auth (Firebase service-account JWT for FCM HTTP v1), Guzzle.

**Actors**: Super-admin (platform, `web` guard / `User` model), Institute admin (`institute` guard / `Institute` model), Staff/Teacher (`Staff`/`Teacher` models, no dedicated guard — managed by Institute), Student (`student` guard / `Student` model), Parent (`StudentParent` model, `parents` table).

---

## 1. Module Overview Table

| Module Name | Core Description | Associated Controllers | Main DB Tables / Models |
|---|---|---|---|
| Platform Authentication & Access Control | Login/session/token issuance and guard middleware for all four actor types (admin, institute, student, parent), OTP verification, password reset, device-session tracking, token refresh | `Auth/*` (8 controllers), `Api/AuthController`, `Api/V1/InstituteAuthController`, `Web/Institute/InstituteAuthController`, `Api/V1/StudentAuthController`, `Api/V1/ParentAuthController`, `Api/V1/TokenRefreshController` | `User`, `Institute`, `Student`, `StudentParent`, `DeviceSession`, `personal_access_tokens`, `permission_tables` (Spatie) |
| Institute Onboarding & Profile | Institute self-registration (OTP-verified), profile/branding setup, custom SMTP config, payment (UPI/Razorpay) settings, public website CMS | `InstituteController` (Api & Web), `Api/V1/InstituteProfileController`, `Web/Institute/ProfileController`, `Web/Institute/WebsiteManageController`, `Web/WebsiteController` | `Institute`, `InstituteWebsiteContent` |
| Subscription & Plans | Plan catalog, subscription lifecycle (purchase, renew, cancel, IAP/Razorpay verification), offline renewal approval workflow, expiry automation | `PlanController` (Api/Api-V1/Web), `SubscriptionController` (Api/Web), `Api/V1/InstituteSubscriptionController`, `Web/Institute/DashboardController` (renewal UI), `CheckSubscriptionExpiry` command | `Plan`, `Subscription`, `SubscriptionPayment`, `SubscriptionRenewal` |
| Lead & Demo Management | Public "book a demo" capture, internal sales-pipeline lead tracking (per super-admin and per-institute) with notes/status | `Api/DemoRequestController`, `Api/LeadController`, `Api/V1/InstituteLeadController` | `DemoRequest`, `Lead`, `LeadNote` |
| Staff / Teacher Management | Staff/teacher CRUD, roles & departments, timetable scheduling, salary computation & payslip email | `Api/StaffController`, `Web/Institute/StaffController`, `Api/V1/InstituteTeacherController`, `Api/StaffSalaryController`, `Api/V1/InstituteTimetableController`, `Web/Institute/TimetableController`, `Web/DepartmentController` | `Staff`, `StaffRole`, `StaffDepartment`, `StaffSalary`, `Teacher`, `Timetable` |
| Student Management & Batches | Student roster CRUD/import/export/bulk-transfer, ID card generation, batch (class/section) management, parent linkage | `Api/V1/InstituteStudentController`, `Web/Institute/StudentController`, `Api/V1/InstituteBatchController`, `Web/Institute/BatchController`, `Api/InstituteController` (admin-side student/batch deletion), `StudentImportService` | `Student`, `StudentParent`, `Batch` |
| Attendance | Daily attendance capture for students/batches, staff, and teachers, with reporting for each role | `Web/Institute/AttendanceController`, `Api/V1/InstituteAttendanceController`, `Api/StaffAttendanceController`, `Api/V1/InstituteTeacherController` (teacher attendance endpoints), `Api/V1/StudentAttendanceController`, `Api/V1/ParentAttendanceController` | `Attendance`, `StaffAttendance`, `TeacherAttendance` |
| Fee & Payment Management | Fee-structure creation, payment collection (cash/UPI/Razorpay), auto-generated receipts, PDF invoices, fee reminders | `Web/Institute/FeeController`, `Api/V1/InstituteFeeController`, `Api/V1/InstitutePaymentController`, `Api/V1/InstituteReceiptController`, `Api/PaymentController`, `Api/V1/StudentFeesController`, `Api/V1/ParentFeesController`, `Api/V1/ParentPaymentController`, `Api/V1/StudentReceiptsController`, `Api/V1/ParentReceiptsController` | `Fee`, `Payment`, `Receipt` |
| Exams & Homework | Exam creation, mark entry/analytics, homework assignment, submission & grading, reminders | `Api/V1/InstituteExamController`, `Api/V1/StudentExamController`, `Api/V1/InstituteHomeworkController`, `Api/V1/StudentHomeworkController`, `Api/V1/ParentHomeworkController`, `Web/Institute/BatchController` (exam/homework views) | `Exam`, `ExamMark`, `Homework`, `HomeworkSubmission` |
| Daily Updates / Notes / Notices | Teacher-to-student/parent daily progress updates; internal rich-text staff notes with categories, checklists & images | `Api/V1/InstituteDailyUpdateController`, `Api/V1/StudentDailyUpdateController`, `Web/Institute/DailyUpdateController`, `Api/V1/NoteController`, `Api/V1/InstituteNoteController`, `Api/V1/NoteCategoryController`, `Api/V1/NoteChecklistController` | `DailyUpdate`, `Note`, `NoteCategory`, `NoteChecklist`, `NoteImage` |
| Notifications (In-App & Push/FCM) | Institute-authored broadcast notifications, per-user read state, FCM device-token registry, per-category notification preferences, admin broadcast center | `Api/NotificationController`, `Api/V1/InstituteNotificationController`, `Web/Institute/NotificationController`, `Web/BroadcastController`, `Api/V1/StudentNotificationController`, `Api/V1/ParentNotificationController`, `Api/V1/FCMTokenController`, `Api/V1/NotificationSettingController`, `FCMService` | `Notification`, `notification_settings` (JSON column on `Student`/`StudentParent`), device tokens (FCM token columns on `User`/`Institute`/`Student`/`StudentParent`) |
| Chat / Community | 1:1 real-time chat between institute members (staff/student/parent), institute-wide community/broadcast messaging channel | `Api/V1/ChatController`, `Api/V1/CommunityController` | `ChatMessage`, `CommunityMessage` |
| Reports & Dashboards | Role-specific dashboards (admin/institute/student/parent) and exportable reports (fee, attendance, performance, student, revenue) | `Api/DashboardController`, `Web/DashboardController`, `Web/Institute/DashboardController`, `Api/V1/InstituteReportController`, `Web/Institute/ReportController`, `Api/V1/StudentDashboardController`, `Api/V1/StudentReportController`, `Api/V1/ParentDashboardController`, `Api/V1/ParentReportController`, `Web/RevenueController` | Aggregates across `Fee`, `Payment`, `Attendance`, `ExamMark`, `Subscription`, etc. (no dedicated table) |
| WhatsApp Integration | Per-institute WhatsApp Business sender configuration and message logging (fee reminders, notices, etc.) | `Web/WhatsAppController`, `WhatsappSettingController`, `Api/V1/InstituteWhatsappSettingController` | `WhatsappSetting`, `InstituteWhatsappSetting`, `WhatsappLog` |
| Expenses | Institute operating-expense tracking with categories, dashboards and analysis reports | `Api/V1/InstituteExpenseController` | `Expense`, `ExpenseCategory` |
| Resources / Learning Materials | Upload & download of study material/resources scoped to institute/batch, with student access & FCM notify | `Api/V1/InstituteResourceController`, `Api/V1/StudentResourceController` | `Resource` |
| QR / Public Verification | QR-code driven app-download attribution/analytics (web/android/ios) and public student-ID verification | `Web/QrController`, `Api/V1/PublicVerificationController` | `QrScan` |
| System Settings / Versioning | Platform-wide settings (mail, Razorpay keys, misc config), mobile app version/force-update gate | `Web/SettingController`, `Api/V1/SystemVersionController` | `SystemSetting` |
| Activity Logging | Audit trail of super-admin/system actions for compliance/monitoring | `ActivityController`, `Web/ActivityController` | `Activity` |
| Feedback | Student-submitted feedback/suggestions to the institute | `Api/V1/StudentFeedbackController` | `Feedback` |

---

## 2. Detailed Module Specifications

### 2.1 Platform Authentication & Access Control

**Business Description**: Provides identity and session management for all four actor types on the platform. The Super-admin uses classic session-based Laravel auth (Breeze/Jetstream style scaffolding under `routes/auth.php`) protected by the `web` guard. Institutes, Students and Parents use Sanctum-issued bearer tokens for the mobile app / Api\V1 surface, plus a parallel session-based flow for the institute's own web panel. Registration for institutes is OTP-verified by email; students/parents are provisioned by the institute (no self-registration) and receive credentials by email. Device-session tracking lets an institute see/revoke logged-in devices. Role/permission scaffolding (Spatie `laravel-permission`) underlies the admin panel's access control.

**Backend Components**:
- Controllers: `app/Http/Controllers/Auth/*` (AuthenticatedSessionController, RegisteredUserController, PasswordResetLinkController, NewPasswordController, PasswordController, ConfirmablePasswordController, EmailVerificationPromptController, EmailVerificationNotificationController, VerifyEmailController) — super-admin web auth; `Api/AuthController` (admin API login/logout/profile); `Api/V1/InstituteAuthController` (register, verifyOtp, resendOtp, login, forgot/reset password, logout, profile) — mobile; `Web/Institute/InstituteAuthController` — institute web panel mirror (login, register, verify-otp, setup-profile, password reset); `Api/V1/StudentAuthController`, `Api/V1/ParentAuthController` (login/logout/profile, student also has forgot-password); `Api/V1/TokenRefreshController` (`refresh`).
- Models / Tables: `User` (super-admin), `Institute` (has OTP fields, `remember_token`), `Student`, `StudentParent` (`parents` table), `DeviceSession` (`device_sessions` — tracks/terminates logins), Sanctum's `personal_access_tokens`, Spatie's permission tables (`create_permission_tables` migration).
- Services / Mailers: `App\Mail\OtpMail` (registration/login OTP), `App\Mail\ForgotPasswordMail` (OTP-based reset code), `App\Mail\AccountActivatedMail` (sent when super-admin approves an institute).
- Middleware: `EnsureInstituteIsActive` (`active_institute`), `EnsureInstituteIsVerified` (`verified_institute`), `EnsureInstituteProfileIsComplete` (`profile_complete`), `EnsureHasActiveSubscription` (`check_subscription`) — gate institute API/web access at different onboarding stages.

**API Routes / Endpoints**:
- Admin web (session): `GET/POST /admin/login`, `/admin/register`, `/admin/forgot-password`, `/admin/reset-password/{token}`, `/admin/logout` (`routes/auth.php`, mounted under `/admin`).
- Admin API: `POST /api/v1/auth/login`, `POST /api/v1/auth/refresh`, `POST /api/v1/auth/logout`, `GET /api/v1/auth/profile` (Sanctum).
- Institute (mobile): `POST /api/v1/institute/register|verify-otp|resend-otp|login|forgot-password|reset-password`, `POST /api/v1/institute/logout` (Sanctum + `active_institute`+`check_subscription`).
- Institute (web): `GET/POST /institute/login|register|verify-otp|resend-otp|forgot-password|reset-password/{token}`, `POST /institute/setup-profile`.
- Student: `POST /api/v1/student/login|forgot-password`, `POST /api/v1/student/logout` (Sanctum).
- Parent: `POST /api/v1/parent/login`, `POST /api/v1/parent/logout` (Sanctum).

**Third-Party Integrations**: None directly (email delivery routed via `InstituteMailService`/SMTP — see §2.2); Sanctum for token issuance; Spatie Permission for RBAC scaffolding.

---

### 2.2 Institute Onboarding & Profile

**Business Description**: Covers the full institute lifecycle after signup: completing the business profile (logo, address, payment/UPI QR, template selection), configuring a custom outbound SMTP mailer, and managing the institute's public marketing website (a CMS-like content builder for hero slides, pillars, achievements, gallery, events, social links) that is served on a public subdomain/slug route. Serves the Institute admin actor.

**Backend Components**:
- Controllers: `Api/InstituteController` & `Web/InstituteController` (super-admin CRUD over institutes, status toggle, SMTP test, cascading deletes of students/staff/batches); `Api/V1/InstituteProfileController` (mobile: show/update profile, payment settings, template, change password, device-session logout); `Web/Institute/ProfileController` (web panel equivalent, incl. `updateSmtp`/`testSmtp`); `Web/Institute/WebsiteManageController` (CMS: hero, pillars, achievements, gallery, events, social links, image upload); `Web/WebsiteController` (public rendering + template preview).
- Models / Tables: `Institute` (core profile, SMTP creds, UPI/payment fields, OTP fields, subscription relations), `InstituteWebsiteContent` (`institute_website_contents`).
- Services: `InstituteMailService::send()` — dual-mode mailer: uses institute's custom SMTP (`EsmtpTransport`) if `hasCustomSmtp()`, else falls back to platform default SMTP (`noreply@tuoora.com`) on failure or absence.

**API Routes / Endpoints**:
- Mobile: `GET/POST /api/v1/institute/profile`, `/profile/update`, `/profile/payment/update`, `/profile/change-password`, `/profile/template/update`, `DELETE /profile/delete`, `DELETE /profile/device-sessions/{id}`.
- Web panel: `GET /institute/profile`, `/profile/edit`, `/profile/payment-settings`, `POST /profile/update`, `/profile/password`, `/profile/template/update`, `/profile/smtp`, `/profile/smtp/test`, `DELETE /profile/device-sessions/{id}`; Website CMS under `/institute/profile/website*`.
- Public: `GET /{instituteCode}/{nameSlug}` (public institute microsite), `GET /institute/templates/{id}` (template preview).
- Admin: `Route::resource('institutes', ...)` under `/admin`, plus `institutes/{institute}/status`, `test-smtp`, and cascading-delete endpoints for students/staff/batches.

**Third-Party Integrations**: Symfony Mailer (`EsmtpTransport`) for per-institute SMTP; file storage for logo/website image uploads.

---

### 2.3 Subscription & Plans

**Business Description**: Monetization layer. Super-admin defines `Plan`s (with add-ons); institutes purchase/renew a `Subscription` either via in-app Razorpay checkout, Android/iOS in-app purchase (IAP) receipt verification, or an offline/manual payment flow requiring super-admin approval. A daily scheduled command auto-expires lapsed subscriptions and warns institutes as expiry approaches.

**Backend Components**:
- Controllers: `Api/PlanController`, `Api/V1/PlanController`, `Web/PlanController`, `Web/Institute/PlanController` (plan listing/CRUD/status); `Api/SubscriptionController`, `Web/SubscriptionController` (admin CRUD, `extend`, `activate`, `cancel`, `changePlan`, `approveRenewal`, `rejectRenewal`); `Api/V1/InstituteSubscriptionController` (mobile: `show`, `renew`, `purchase`, `verifyPayment`, `createOrder`, `verifyPaymentAndroid`, `verifyIap`, `history`, `allData`, `handleWebhook`); `Web/Institute/DashboardController` (`showSubscriptionPage`, `showRenewalForm`, `submitRenewal`).
- Models / Tables: `Plan`, `Subscription` (status/effective-status accessors, IAP columns), `SubscriptionPayment` (`payment_source` column — razorpay/iap/manual), `SubscriptionRenewal` (offline renewal requests pending approval).
- Console Commands: `CheckSubscriptionExpiry` (`subscription:check-expiry`) — daily job expiring lapsed subscriptions and firing "plan expiring soon" push notifications at 7→0 days.
- Mailers: `SubscriptionStatusMail` (assigned/extended/upgraded/approved/rejected status emails to institute owner).

**API Routes / Endpoints**:
- Mobile: `GET /api/v1/institute/plans`, `/subscription`, `/subscriptions/all-data`, `/subscriptions/history`; `POST /subscription/renew`, `/subscription/iap-verify`, `/subscriptions/verify-payment`, `/subscriptions/purchase`, `/subscription/create-order`, `/subscription/verify-payment` (Android); `POST /api/v1/institute/subscription/webhook` (Razorpay webhook, unauthenticated).
- Admin web: `Route::resource('subscriptions', ...)`, `PATCH subscriptions/{subscription}/extend|activate|cancel|change-plan`, `PATCH subscriptions/renewals/{renewal}/approve|reject`; `Route::resource('plans', ...)`, `POST plans/{plan}/status`, `POST plans/addon/update`.
- Institute web: `GET /institute/subscription`, `/subscription/renew`, `POST /subscription/renew`.

**Third-Party Integrations**: **Razorpay** (`razorpay/razorpay` SDK) for order creation/payment verification/webhooks; Google Play / Apple App Store IAP receipt verification (`verifyIap`, `verifyPaymentAndroid`).

---

### 2.4 Lead & Demo Management

**Business Description**: Two related sales pipelines. (1) Public "Book a Demo" form on the marketing site feeds `DemoRequest` records to the platform sales team. (2) A CRM-style `Lead`/`LeadNote` pipeline used both by the platform's own sales team (prospective institutes) and, separately, by institutes themselves to track their own admissions leads (prospective students), with status updates and note threads.

**Backend Components**:
- Controllers: `Api/DemoRequestController` (`store`, public); `Api/LeadController` (platform-level: index/store/show/update/updateStatus/addNote/destroy); `Api/V1/InstituteLeadController` (institute-scoped CRUD).
- Models / Tables: `DemoRequest`, `Lead` (has `notes()` relation, `referer` field), `LeadNote`.
- Mailers: `DemoBookedMail` (confirmation to prospective lead on demo booking).

**API Routes / Endpoints**:
- Public: `POST /api/book-demo`.
- Platform admin (Sanctum): `GET/POST /api/v1/institute/leads`, `/leads/{id}`, `PUT /leads/{id}`, `PUT /leads/{id}/status`, `POST /leads/{id}/notes`, `DELETE /leads/{id}` (registered twice in `api.php` — once via `Api\LeadController` for the platform, once via `Api\V1\InstituteLeadController` for institute-scoped leads, both under the `institute` middleware group).
- Institute web: `GET /institute/leads` (SPA view).

**Third-Party Integrations**: None beyond email (`DemoBookedMail`).

---

### 2.5 Staff / Teacher Management

**Business Description**: Institute admin manages employees — both generic `Staff` (with configurable `StaffRole`/`StaffDepartment`) and a distinct `Teacher` entity used for the class-timetable/faculty-scheduling flows. Includes salary computation with a payslip PDF/email, and timetable/schedule assignment per batch and staff member.

**Backend Components**:
- Controllers: `Api/StaffController` (CRUD, `getRoles`, `storeRole`, `getDepartments`, `storeDepartment`); `Web/Institute/StaffController` (web CRUD); `Web/DepartmentController` (admin-level, global departments); `Api/V1/InstituteTeacherController` (CRUD + `getAttendance`/`markAttendance`/`attendanceReport`); `Api/StaffAttendanceController`; `Api/StaffSalaryController` (`index`, `store`, `preview`, `export`, `showByStaff`); `Api/V1/InstituteTimetableController` (`index`, `store`, `studentSchedule`); `Web/Institute/TimetableController` (CRUD).
- Models / Tables: `Staff` (`staff` table, `department_staff` pivot), `StaffRole`, `StaffDepartment`, `StaffAttendance`, `StaffSalary`, `Teacher`, `TeacherAttendance`, `Timetable`.
- Mailers: `StaffAddedMail` (welcome/credentials on staff creation), `SalarySlipMail` (monthly payslip PDF).

**API Routes / Endpoints**:
- Mobile (`/api/v1/institute/*`): `staff` (CRUD), `staff-roles`, `staff-departments`, `teachers` (+ `/teachers/attendance`, `/teachers/attendance/report`), `attendance` (staff attendance: index/store/destroy/export/{staff_id}), `salaries` (index/store/preview/{staff_id}/export/{staff_id}), `timetable` (index/store).
- Web (`/institute/*`): `staff` (index/store/show/{staff}/edit/update/destroy), `timetable` (index/store/{timetable}/update/destroy).
- Admin: `Route::resource('departments', ...)` under `/admin`.

**Third-Party Integrations**: `barryvdh/laravel-dompdf` (salary slip PDF generation, likely also used for report/receipt PDFs elsewhere).

---

### 2.6 Student Management & Batches

**Business Description**: Core roster management for the institute — enrolling students (manual, Excel import, bulk transfer between batches), organizing them into `Batch`es (classes/sections/cohorts), generating printable ID cards, and linking each student to a `StudentParent` account. Serves Institute admin; consumed read-side by Student/Parent apps via other modules.

**Backend Components**:
- Controllers: `Api/V1/InstituteStudentController` (index/store/show/update/destroy, `birthdays`, `idCard`, `sendFeeReminder`, `sendPasswordEmail`, `resetPasswordDirect`, `import`, `bulkTransfer`); `Web/Institute/StudentController` (mirrors web CRUD + `export`, `importSample`); `Api/V1/InstituteBatchController` / `Web/Institute/BatchController` (CRUD, `close`, `removeStudent`, `assignStudents`, `sendFeeReminders`, `export`, plus batch sub-views: students/homework/exams/attendance/resources/timetable).
- Models / Tables: `Student` (Authenticatable, OTP/device/DOB/id-hash fields), `StudentParent` (`parents` table, Authenticatable), `Batch`.
- Services: `StudentImportService` (Excel/CSV bulk student import).
- Mailers: `StudentAddedMail` (credentials on creation), `StudentPasswordSentMail` (password resend/reset).

**API Routes / Endpoints**:
- Mobile: `/api/v1/institute/students` (CRUD), `/students/bulk-transfer`, `/students/import`, `/students/{id}/id-card`, `/students/{id}/fee-reminder`, `/students/{id}/send-password`, `/students/{id}/reset-password`; `/api/v1/institute/batches` (CRUD + `/export`, `/{id}/remove-student`, `/{id}/assign-students`, `/{id}/close`, `/{id}/fee-reminders`).
- Web: `/institute/students*` (full resourceful set + `import`, `import-sample`, `export`, `bulk-transfer`); `/institute/batches*` (full resourceful set + sub-resource views).
- Admin: `DELETE /admin/institutes/{institute}/students/{student}`, `/batches/{batch}` (super-admin cascading delete/view).

**Third-Party Integrations**: PDF generation (DomPDF) for ID cards; Excel/CSV parsing (via `StudentImportService`, likely Laravel Excel or native CSV).

---

### 2.7 Attendance

**Business Description**: Daily presence tracking across three populations — students (per batch), staff, and teachers — each with its own capture UI and reporting/export. Students/Parents view attendance read-only in their apps.

**Backend Components**:
- Controllers: `Web/Institute/AttendanceController` (web: index/create/show/edit/store/update/destroy — student/batch attendance); `Api/V1/InstituteAttendanceController` (mobile: index/store — batch attendance, routed as `batch-attendance`); `Api/StaffAttendanceController` (index/store/destroy/export/showByStaff); `Api/V1/InstituteTeacherController` (`getAttendance`, `markAttendance`, `attendanceReport`); `Api/V1/StudentAttendanceController` (read-only, student self-view); `Api/V1/ParentAttendanceController` (read-only, parent view of child).
- Models / Tables: `Attendance` (`attendance` table — student/batch), `StaffAttendance`, `TeacherAttendance`.

**API Routes / Endpoints**:
- Mobile: `GET/POST /api/v1/institute/batch-attendance`; `GET/POST/DELETE /api/v1/institute/attendance*` (staff); `GET/POST /api/v1/institute/teachers/attendance`, `/teachers/attendance/report`; `GET /api/v1/student/attendance`; `GET /api/v1/parent/attendance`.
- Web: `/institute/attendance*` (full resourceful CRUD).

**Third-Party Integrations**: Export (CSV/Excel) on `StaffAttendanceController::export` and `InstituteReportController::exportAttendanceReport`.

---

### 2.8 Fee & Payment Management

**Business Description**: Defines fee structures per student, collects payments (cash, UPI, or online via Razorpay), auto-generates receipts and PDF invoices, and sends fee-due reminders (email/push/WhatsApp). The most revenue-critical module for the Institute admin actor; Student/Parent apps consume it read-only plus an online "pay now" flow for parents.

**Backend Components**:
- Controllers: `Web/Institute/FeeController` (index/collect/store, `showReceipt`/`downloadReceipt`); `Api/V1/InstituteFeeController` (index/store/getStudentFees/export/showReceipt); `Api/V1/InstitutePaymentController` (store/getStudentPayments); `Api/V1/InstituteReceiptController` (getStudentReceipts/downloadReceipt); `Api/PaymentController` (admin-level payments listing); `Api/V1/StudentFeesController`, `Api/V1/StudentReceiptsController` (read-only + PDF download); `Api/V1/ParentFeesController`, `Api/V1/ParentPaymentController` (`store` = pay-fee), `Api/V1/ParentReceiptsController`.
- Models / Tables: `Fee`, `Payment` (belongs to `Fee`/`Student`, has one `Receipt`), `Receipt`.
- Mailers: `FeeInvoiceMail` (invoice with due date, breakdown, PDF).

**API Routes / Endpoints**:
- Mobile: `/api/v1/institute/fees` (index/store/export/{student_id}), `/fees/receipts/{id}`; `/institute/payments` (store/{student_id}); `/institute/receipts/{student_id}`, `/receipt/{id}/download`; `GET/GET /student/fees`, `/fees/{id}`, `/fees/{id}/download`, `/receipts`, `/receipts/{id}`, `/receipts/{id}/download`; `GET /parent/fees`, `POST /parent/pay-fee`, `GET /parent/receipts`.
- Web: `/institute/fees`, `/fees/collect` (GET+POST), `/fees/receipts/{receipt}`, `/fees/receipts/{receipt}/download`.

**Third-Party Integrations**: **Razorpay** (parent online fee payment, institute subscription payment reuses the same SDK); `barryvdh/laravel-dompdf` for receipt/invoice PDFs; `khanamiryan/qrcode-detector-decoder` likely used for UPI-QR generation/decoding on payment screens.

---

### 2.9 Exams & Homework

**Business Description**: Academic assessment workflows — institute staff create exams and record marks (with pass/fail and grade computation), and assign homework/assignments to batches with due dates, scoring, grading, and automated reminder pushes. Students submit homework and view exam results; parents view both read-only.

**Backend Components**:
- Controllers: `Api/V1/InstituteExamController` (index/store/show/update/destroy, `getMarks`/`saveMarks`); `Api/V1/StudentExamController` (index/show); `Api/V1/InstituteHomeworkController` (index/store/show, `updateScore`, `updateGrades`, `sendReminder`); `Api/V1/StudentHomeworkController` (index/show/submit, attachment info/download); `Api/V1/ParentHomeworkController` (index); `Web/Institute/BatchController` (`exams`, `examShow`, `homework`, `homeworkShow` views).
- Models / Tables: `Exam` (formatted-date/stats accessors), `ExamMark` (percentage/pass/grade accessors), `Homework` (`homeworks` table, attachment column), `HomeworkSubmission` (score column).
- Console Commands: `SendHomeworkReminders` (`homework:send-reminders`) — daily push reminder 1 day before due date.

**API Routes / Endpoints**:
- Mobile: `/api/v1/institute/exams` (CRUD), `/exams/{id}/marks` (GET/POST); `/institute/homeworks` (index/store/show, `/{id}/score`, `/{id}/grades`, `/{id}/reminder`); `GET /student/exams`, `/exams/{id}`; `GET /student/homeworks`, `/homeworks/{id}`, `POST /homeworks/{id}/submit`, `/homeworks/{id}/attachment[/download]`; `GET /parent/homeworks`.

**Third-Party Integrations**: FCM push (homework assigned/reminder, exam-mark notifications) via `FCMService`.

---

### 2.10 Daily Updates / Notes / Notices

**Business Description**: Two distinct sub-features. (a) **Daily Updates**: short progress notes teachers post about a student/batch, visible to students/parents. (b) **Notes**: an internal rich-text note-taking system for institute staff (categories, checklists, bookmarking, image attachments) — effectively a lightweight workspace/notice tool, unrelated to student-facing content.

**Backend Components**:
- Controllers: `Api/V1/InstituteDailyUpdateController` (index/store); `Api/V1/StudentDailyUpdateController` (index); `Web/Institute/DailyUpdateController` (index); `Api/V1/NoteController` (index/store/show/update/destroy, `bookmark`, `toggleChecklist`, `destroyChecklist`); `Api/V1/InstituteNoteController` (index/store/update/archive/destroy — appears to be an alternate/older Notes surface); `Api/V1/NoteCategoryController` (index/store); `Api/V1/NoteChecklistController` (full CRUD, standalone).
- Models / Tables: `DailyUpdate`, `Note` (polymorphic `notable()`, belongs to `User`/`Institute`/`NoteCategory`), `NoteCategory`, `NoteChecklist`, `NoteImage`.

**API Routes / Endpoints**:
- Mobile: `POST/GET /api/v1/institute/daily-updates`; `GET /student/daily-updates`; `/institute/notes` (full CRUD + `/{id}/bookmark`, `/checklists/{id}/toggle`, `DELETE /checklists/{id}`); `GET/POST /institute/note-categories`.
- Web: `GET /institute/updates`, `GET /institute/notes` (SPA view).

**Third-Party Integrations**: File storage for `NoteImage` attachments.

---

### 2.11 Notifications (In-App & Push/FCM)

**Business Description**: The cross-cutting alerting layer. Institute admins compose broadcast notifications (targeted by audience: all students, all parents, a specific batch, etc.) delivered both as in-app `Notification` records and as FCM push. A large catalog of *automatic* system-triggered pushes also exists (birthday greetings, homework reminders, subscription expiry warnings, fee reminders, chat messages, new homework/exam/daily-update alerts). Each push category maps to a togglable key in the recipient's `notification_settings` JSON column, enforced inside `FCMService::send()`.

**Backend Components**:
- Controllers: `Api/NotificationController` (admin store); `Api/V1/InstituteNotificationController` (`index`, `markAllRead`, `sendPush`, `recipientStats`, `send`); `Web/Institute/NotificationController` (`index`, `compose`); `Web/BroadcastController` (super-admin broadcast center: `index`, `send`); `Api/V1/StudentNotificationController` / `Api/V1/ParentNotificationController` (index, mark read/all-read, attachment download); `Api/V1/FCMTokenController` (`updateToken` — device registration, used by institute web/mobile and student/parent apps); `Api/V1/NotificationSettingController` (`getSettings`, `updateSettings`).
- Models / Tables: `Notification` (`target`, `image` columns), FCM tokens stored directly on `User`/`Institute`/`Student`/`StudentParent` rows (not a separate table), `notification_settings` JSON column on `Student`/`StudentParent`.
- Services: `FCMService` — builds OAuth2 access tokens from a Firebase service-account JSON via `google/auth`, calls the FCM HTTP v1 API, and gates delivery per-recipient by category preference.
- Console Commands: `SendBirthdayNotifications` (`birthday:send-notifications`), `SendHomeworkReminders`, `TestFCMNotification` (`fcm:test`), `TestResourceNotification` (`fcm:test-resource`); `CheckSubscriptionExpiry` also emits pushes.

**API Routes / Endpoints**:
- `POST /api/v1/fcm-token` (generic, Sanctum), `POST /institute/fcm-token` (web, verified institute); `POST /api/v1/institute/notifications/send`, `/send-push`, `GET /recipient-stats`, `GET /notifications`, `POST /mark-all-read`; `GET/POST /api/v1/student|parent/notification-settings`; `GET /student/notifications`, `/notifications/{id}/attachment/download`, `POST /notifications/{id}/read`, `/mark-all-read`; `GET /parent/notifications`.
- Admin web: `GET /admin/broadcast`, `POST /admin/broadcast/send`.

**Third-Party Integrations**: **Firebase Cloud Messaging** (HTTP v1 API) authenticated via **Google Auth** service-account credentials (`google/auth` package).

---

### 2.12 Chat / Community

**Business Description**: Real-time messaging inside the institute — direct 1:1 chat between any two members (staff/student/parent) and a broadcast-style "community" channel for institute-wide announcements/discussion. Backed by Laravel Broadcasting events for live delivery (read receipts, delivery receipts, deletion sync) alongside FCM push for offline recipients.

**Backend Components**:
- Controllers: `Api/V1/ChatController` (`send`, `list`, `messages`, `clearConversation`, `markAsRead`, `markAsReceived`, `contacts`); `Api/V1/CommunityController` (`list`, `members`, `messages`, `send`).
- Models / Tables: `ChatMessage` (sender/receiver, `deleted` flags, `received_at`), `CommunityMessage` (sender only — broadcast to institute).
- Events: `MessageSent`, `MessageReceived`, `MessageRead`, `ChatDeleted` — all `ShouldBroadcastNow`.
- Broadcast Channels (`routes/channels.php`): `App.Models.User.{id}`, `chat.{type}.{id}`, `chat.{id}` — private channels authorizing only the matching user id.

**API Routes / Endpoints**: `/api/chat/{contacts,list,messages/{user_id},send,mark-read,mark-received}`, `DELETE /api/chat/conversation`; `/api/community/{list,members,messages,send}` — all under Sanctum `auth:sanctum`.

**Third-Party Integrations**: **Pusher** (`pusher/pusher-php-server`) as the broadcast driver, with **BeyondCode Laravel-WebSockets** available as a self-hosted alternative (both present in `composer.json`; `config/broadcasting.php` defaults `BROADCAST_DRIVER` to `null`, switchable to `pusher`).

---

### 2.13 Reports & Dashboards

**Business Description**: Aggregated, role-scoped views summarizing platform/institute health: super-admin dashboard (revenue, institute counts), institute dashboard (fees collected, attendance %, active students), student/parent dashboards (personal fee/attendance/exam snapshot), plus deep-dive exportable reports (fee collection, attendance, academic performance, student roster, revenue).

**Backend Components**:
- Controllers: `Api/DashboardController`, `Web/DashboardController` (super-admin); `Web/Institute/DashboardController` (institute — also handles subscription page/renewal, see §2.3); `Api/V1/InstituteReportController` (`dashboard`, `feeReport`(+export), `attendanceReport`(+export), `performanceReport`(+export), `studentReport`(+export), `emailStudentReport`); `Web/Institute/ReportController` (SPA shell); `Api/V1/StudentDashboardController`, `Api/V1/StudentReportController`; `Api/V1/ParentDashboardController`, `Api/V1/ParentReportController`; `Web/RevenueController` (`index`, `storeManualPayment` — admin revenue analysis + manual/offline payment entry).
- Mailers: `StudentReportMail` (emails a student's PDF performance report to parent/student).

**API Routes / Endpoints**: `/api/v1/institute/reports/{dashboard,fee,fee/export,attendance,attendance/export,performance,performance/export,student,student/export}`, `POST /reports/student/email`; `GET /student/report`; `GET /parent/report`; `GET /admin/revenue`, `POST /admin/revenue/manual-payment`.

**Third-Party Integrations**: DomPDF (report/PDF export), CSV/Excel export on the `*/export` endpoints.

---

### 2.14 WhatsApp Integration

**Business Description**: Lets an institute connect a WhatsApp Business sending number/API credentials so fee reminders, notices, and other alerts can also be delivered via WhatsApp (in addition to email/push). Super-admin can view/verify/update each institute's WhatsApp configuration; a delivery log records outbound messages.

**Backend Components**:
- Controllers: `Web/WhatsAppController` (super-admin: `index`, `update`, `verify`); `WhatsappSettingController` (legacy resourceful CRUD, top-level); `Api/V1/InstituteWhatsappSettingController` (mobile: `show`, `store`, `update`).
- Models / Tables: `WhatsappSetting` (global/legacy), `InstituteWhatsappSetting` (current per-institute config), `WhatsappLog` (belongs to institute/student/parent — delivery audit trail).

**API Routes / Endpoints**: `GET/POST/PUT /api/v1/institute/whatsapp-settings`; `GET /admin/whatsapp`, `POST /admin/whatsapp/{institute}/update`, `/verify`.

**Third-Party Integrations**: WhatsApp Business API (credentials stored on `InstituteWhatsappSetting`; no dedicated SDK in `composer.json` — likely called via Guzzle HTTP directly).

---

### 2.15 Expenses

**Business Description**: Lets an institute log and categorize its operating expenses (rent, utilities, salaries paid out-of-band, supplies, etc.) with dashboard summaries and analysis/export reports — a basic institute-side bookkeeping feature for the Institute admin actor.

**Backend Components**:
- Controllers: `Api/V1/InstituteExpenseController` (`getCategories`, `storeCategory`, index/store/update/destroy, `dashboard`, `report`, `analysis`).
- Models / Tables: `Expense` (belongs to `ExpenseCategory`/`Institute`, `payment_method` column), `ExpenseCategory`.

**API Routes / Endpoints**: `/api/v1/institute/expenses` (CRUD), `/expenses/categories` (GET/POST), `/expenses/dashboard`, `/expenses/report`, `/expenses/analysis`, `/expenses/export`.

**Third-Party Integrations**: CSV/Excel export (`report`/`export` endpoints).

---

### 2.16 Resources / Learning Materials

**Business Description**: Institute staff upload downloadable study material (PDFs, docs, etc.) scoped to the institute or a specific batch; students can browse and download resources for their batch, with a push notification on new uploads.

**Backend Components**:
- Controllers: `Api/V1/InstituteResourceController` (index/store/destroy/download); `Api/V1/StudentResourceController` (index/download).
- Models / Tables: `Resource` (belongs to `Institute`/`Batch`, `getFileUrlAttribute`/`getDownloadUrlAttribute`).
- Console Commands: `TestResourceNotification` (`fcm:test-resource`) confirms resources trigger FCM push on upload.

**API Routes / Endpoints**: `/api/v1/institute/resources` (index/store), `DELETE /{id}`, `GET /{id}/download`; `GET /student/resources`, `/resources/{id}/download`.

**Third-Party Integrations**: File storage; FCM push via `FCMService`.

---

### 2.17 QR / Public Verification

**Business Description**: Two related, unauthenticated-facing features. (1) **QR attribution tracking**: marketing QR codes (printed materials, posters) redirect through `/qr/{web|android|ios}` so scans can be logged for analytics (device/platform, optional GPS) before bouncing to the App/Play Store or web app — visible to super-admin via a QR analytics dashboard. (2) **Public ID verification**: a public endpoint lets anyone verify a scanned student ID card is authentic against the institute's records.

**Backend Components**:
- Controllers: `Web/QrController` (`track`, `adminIndex`, `export`); `Api/V1/PublicVerificationController` (`verifyID`).
- Models / Tables: `QrScan` (`qr_type` label/badge accessors, `hasGps()`).

**API Routes / Endpoints**: `GET|POST /qr/web`, `/qr/android`, `/qr/ios` (public, unauthenticated); `GET /admin/qr-analytics`, `/qr-analytics/export`; `POST /api/public/verify-id` (public).

**Third-Party Integrations**: `khanamiryan/qrcode-detector-decoder` (QR generation/decoding, likely reused for the student ID card / UPI payment QR as well as scan tracking).

---

### 2.18 System Settings / Versioning

**Business Description**: Platform-level configuration super-admins maintain (default mail/SMTP gateway, Razorpay API keys, other global toggles) and a mobile app version-gate endpoint so the apps can prompt users to update / block outdated builds.

**Backend Components**:
- Controllers: `Web/SettingController` (`index`, `update`, `razorpayIndex`/`razorpayUpdate`, `mailIndex`/`mailUpdate`/`testMail`); `Api/V1/SystemVersionController` (`index`).
- Models / Tables: `SystemSetting` (key/value style settings; seeded via `seed_mail_system_settings` migration).

**API Routes / Endpoints**: `GET /admin/settings`, `POST /settings/update`; `GET /admin/settings/razorpay`, `POST /razorpay/update`; `GET /admin/settings/mail`, `POST /mail/update`, `POST /mail/test`; `GET /api/v1/app-versions` (public).

**Third-Party Integrations**: Razorpay key management (platform-level default keys, separate from any per-institute keys); default SMTP gateway config.

---

### 2.19 Activity Logging

**Business Description**: An audit/activity feed of significant platform actions (e.g., admin logins, institute status changes) for super-admin oversight and troubleshooting.

**Backend Components**:
- Controllers: `ActivityController` (full resourceful CRUD — index/create/store/show/edit/update/destroy), `Web/ActivityController` (`index` — likely the active production route).
- Models / Tables: `Activity` (belongs to `User`).

**API Routes / Endpoints**: `GET /admin/activities` (`Web/ActivityController@index`).

**Third-Party Integrations**: None.

---

### 2.20 Feedback

**Business Description**: Lets students submit free-text feedback/suggestions to their institute — a simple satisfaction/complaint channel for the Student actor.

**Backend Components**:
- Controllers: `Api/V1/StudentFeedbackController` (`store`).
- Models / Tables: `Feedback` (`feedback` table).

**API Routes / Endpoints**: `POST /api/v1/student/feedback`.

**Third-Party Integrations**: None.

---

## Appendix: Cross-Cutting Notes

- **Guard/middleware map**: `auth:sanctum` (generic API), `auth:sanctum,institute` + `active_institute` + `check_subscription` (institute mobile API), `auth:institute` + `active_institute` (+`verified_institute`, `profile_complete`, `check_subscription` layered progressively) for the institute web panel, `auth:web` + `verified` (Jetstream) for the super-admin panel. Session guards are configured per actor (`institute`, `student`, implied `parent`) alongside the default `web`.
- **Email delivery** (10 templates) always resolves through `InstituteMailService` for institute-scoped mail (student/staff/fee/report emails) with automatic fallback to the platform's `noreply@tuoora.com` gateway; purely platform-level mail (OTP, forgot-password, account-activated, subscription-status, demo-booked) always uses the default gateway directly. `SalarySlipMail` is an 11th mailer, used by the Staff module.
- **Push notification catalog** enumerates ~20+ discrete push events across Chat, Subscription, Homework, Fees, Attendance, Daily Updates, Birthdays, and Resources — all funneled through the single `FCMService::send()` gate that checks the `notification_settings` JSON preference on the recipient.
- **PDF generation** (`barryvdh/laravel-dompdf`) underlies: fee receipts/invoices, student ID cards, salary slips, and student performance reports.

*Generated by reviewing the actual source: `routes/api.php`, `routes/web.php`, `routes/auth.php`, `routes/channels.php`, all controllers in `app/Http/Controllers/**`, all models in `app/Models/`, `app/Services/`, `app/Mail/`, `app/Console/Commands/`, `app/Events/`, and `composer.json`.*
