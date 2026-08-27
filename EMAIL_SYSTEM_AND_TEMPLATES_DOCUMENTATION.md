# Tuoora Email Notification System & Mail Templates Documentation

This document provides a comprehensive technical overview and operational guide for the Email Notification System, SMTP Gateway Configuration, and all 10 Email Templates in the **Tuoora** platform.

---

## 1. System Architecture Overview

The email dispatch architecture operates in **Dual Mode**:
1. **Institute Custom SMTP Mode**: If an Institute configures and enables their own SMTP server (e.g. their custom domain or Gmail), all emails sent to their students, staff, and parents are dispatched directly through their mail server with their sender name.
2. **Platform Fallback Mode (`noreply@tuoora.com`)**: If an Institute has not configured custom SMTP, or if their mail server is unreachable, the system automatically falls back to Tuoora's verified default SMTP gateway (`noreply@tuoora.com`). Platform security and billing notifications always use this gateway.

```
                         Trigger Email Event
                                 │
                   Is it an Institute-Specific Email?
                                 │
                 ┌───────────────┴───────────────┐
                 ▼                               ▼
               [YES]                            [NO] (Platform / Admin)
                 │                               │
        Has Custom SMTP Enabled?                 │
        ┌────────┴────────┐                      │
        ▼                 ▼                      │
      [YES]              [NO]                    │
        │                 │                      │
   Send via Institute     │                      │
     Custom SMTP          │                      │
        │ (On Error)      │                      │
        └───────┬─────────┘                      │
                ▼                                ▼
      Send via Default Tuoora SMTP (`noreply@tuoora.com`)
```

---

## 2. Complete Inventory of All 10 Outbound Emails

| # | Email Purpose | Trigger Event | Recipient | Mailable Class | Template Blade File | Gateway Used |
|---|---|---|---|---|---|---|
| **1** | **Student Welcome & Credentials** | Student created (Manual / Excel) | Student | `App\Mail\StudentAddedMail` | `resources/views/emails/student_added.blade.php` | Institute SMTP / Tuoora Fallback |
| **2** | **Student Password Reset** | "Forgot Password" or "Resend Password" clicked | Student | `App\Mail\StudentPasswordSentMail` | `resources/views/emails/student_password_sent.blade.php` | Institute SMTP / Tuoora Fallback |
| **3** | **Student Academic PDF Report** | Reports download or "Email Report" clicked | Student / Parent | `App\Mail\StudentReportMail` | `resources/views/emails/student_report.blade.php` | Institute SMTP / Tuoora Fallback |
| **4** | **Staff Welcome & Credentials** | New Staff/Teacher created | Staff Member | `App\Mail\StaffAddedMail` | `resources/views/emails/staff_added.blade.php` | Institute SMTP / Tuoora Fallback |
| **5** | **Fee Invoice & Payment Receipt** | Fee record created / collected | Student | `App\Mail\FeeInvoiceMail` | `resources/views/emails/fee_invoice.blade.php` | Institute SMTP / Tuoora Fallback |
| **6** | **Registration & Login OTP** | Institute Registration & Login verification | Institute Owner | `App\Mail\OtpMail` | `resources/views/emails/otp.blade.php` | Default Tuoora Gateway |
| **7** | **Institute Forgot Password OTP** | Institute password reset request | Institute Owner | `App\Mail\ForgotPasswordMail` | `resources/views/emails/forgot_password.blade.php` | Default Tuoora Gateway |
| **8** | **Account Activated Notification** | Super Admin approves institute account | Institute Owner | `App\Mail\AccountActivatedMail` | `resources/views/emails/account_activated.blade.php` | Default Tuoora Gateway |
| **9** | **Subscription Status Update** | Plan assigned, extended, upgraded, or paid | Institute Owner | `App\Mail\SubscriptionStatusMail` | `resources/views/emails/subscription_status.blade.php` | Default Tuoora Gateway |
| **10** | **Demo Booking Confirmation** | Landing page demo request submitted | Prospective Lead | `App\Mail\DemoBookedMail` | `resources/views/emails/demo_booked.blade.php` | Default Tuoora Gateway |

---

## 3. Template-by-Template Specification & Dynamic Variables

### Template 1: Student Welcome & Credentials
- **File**: `resources/views/emails/student_added.blade.php`
- **Mailable**: `App\Mail\StudentAddedMail`
- **Default Subject**: `Student Account Credentials - Tuoora`
- **Variables**:
  - `$studentName` (string): Full name of the student.
  - `$studentEmail` (string): Registered login email.
  - `$tempPassword` (string): Initial auto-generated login password.
  - `$instituteName` (string): Name of the institute.
  - `$instituteLogoUrl` (string|null): Logo image URL of the institute.
  - `$studentAppUrl` (string): Portal / Mobile app login URL.
- **Visual Features**: Branded gradient banner, prominent username & password credentials box, copy button helper, Play Store / App Store download buttons.

---

### Template 2: Student Password Reset / Resend
- **File**: `resources/views/emails/student_password_sent.blade.php`
- **Mailable**: `App\Mail\StudentPasswordSentMail`
- **Default Subject**: `Your Student Account Password - [Institute Name]`
- **Variables**:
  - `$studentName` (string): Full name of the student.
  - `$studentEmail` (string): Registered email.
  - `$password` (string): Newly generated password.
  - `$instituteName` (string): Institute name.
  - `$instituteLogoUrl` (string|null): Institute logo.
- **Visual Features**: Amber security alert banner, clear password display box, login button, and recommended security instructions.

---

### Template 3: Student Academic & Performance PDF Report
- **File**: `resources/views/emails/student_report.blade.php`
- **Mailable**: `App\Mail\StudentReportMail`
- **Default Subject**: `Student Academic & Comprehensive Performance Report - [Institute Name]`
- **Attachment**: Attached 4-page PDF document (`Student_Report_[Name]_[ID].pdf`).
- **Variables**:
  - `$studentName` (string): Student name.
  - `$studentEmail` (string): Student email.
  - `$enrollmentId` (string): Enrollment code (e.g. `202634667100002`).
  - `$instituteName` (string): Institute name.
  - `$instituteLogoUrl` (string|null): Institute logo.
  - `$generatedAt` (string): Generation timestamp.
- **Visual Features**: High-contrast navy and orange header, student summary badge, PDF attachment indicator, and download link.

---

### Template 4: Staff Welcome & Credentials
- **File**: `resources/views/emails/staff_added.blade.php`
- **Mailable**: `App\Mail\StaffAddedMail`
- **Default Subject**: `Welcome to [Institute Name] - Staff Profile Created`
- **Variables**:
  - `$staffName` (string): Full name of staff member.
  - `$staffEmail` (string): Email address.
  - `$employeeId` (string): Employee code.
  - `$roleName` (string): Assigned role (e.g. Teacher, Admin, Accountant).
  - `$departmentName` (string): Department (e.g. Science, Mathematics).
  - `$instituteName` (string): Institute name.
  - `$instituteLogoUrl` (string|null): Institute logo.
- **Visual Features**: Corporate welcome banner, 2-column employee credentials card, and direct staff portal access button.

---

### Template 5: Fee Invoice & Payment Receipt
- **File**: `resources/views/emails/fee_invoice.blade.php`
- **Mailable**: `App\Mail\FeeInvoiceMail`
- **Default Subject**: `Fee Invoice - [Invoice No] - [Institute Name]`
- **Variables**:
  - `$studentName` (string): Student name.
  - `$invoiceNo` (string): Unique invoice number (e.g. `INV-20260826-0001`).
  - `$invoiceDate` (string): Issue date.
  - `$dueDate` (string): Due date.
  - `$status` (string): Payment status (`paid`, `partial`, `pending`).
  - `$particulars` (string): Fee description.
  - `$totalAmount` (float): Total amount in INR (`Rs.`).
  - `$paymentUrl` (string): Web link to view / download digital receipt.
  - `$instituteName` (string): Institute name.
- **Visual Features**: Dynamic status tag (Green `PAID`, Amber `PARTIAL`, Red `PENDING`), itemized fee table with `Rs.` formatting, and instant payment receipt button.

---

### Template 6: Registration & Login Verification OTP
- **File**: `resources/views/emails/otp.blade.php`
- **Mailable**: `App\Mail\OtpMail`
- **Default Subject**: `Your Verification Code - Tuoora`
- **Variables**:
  - `$otp` (string): 6-digit numeric OTP code.
  - `$userName` (string): Institute owner name.
- **Visual Features**: Large letter-spaced OTP code badge, 10-minute expiry reminder, and fraud prevention warning.

---

### Template 7: Institute Forgot Password OTP
- **File**: `resources/views/emails/forgot_password.blade.php`
- **Mailable**: `App\Mail\ForgotPasswordMail`
- **Default Subject**: `Password Reset Code - Tuoora`
- **Variables**:
  - `$otp` (string): 6-digit password reset code.
  - `$userName` (string): Institute owner name.
- **Visual Features**: Orange alert badge, prominent OTP container, security notice to ignore if not requested.

---

### Template 8: Institute Account Activated Notification
- **File**: `resources/views/emails/account_activated.blade.php`
- **Mailable**: `App\Mail\AccountActivatedMail`
- **Default Subject**: `Account Activated successfully - Tuoora`
- **Variables**:
  - `$userName` (string): Institute name / owner.
  - `$loginUrl` (string): Portal login URL (`/institute/login`).
- **Visual Features**: Celebration icon, welcome message, and "Login to Your Dashboard" call-to-action button.

---

### Template 9: Subscription Plan Status Update
- **File**: `resources/views/emails/subscription_status.blade.php`
- **Mailable**: `App\Mail\SubscriptionStatusMail`
- **Default Subjects**:
  - `🎉 New Subscription Plan Assigned! - Tuoora` (New Plan)
  - `📅 Subscription Validity Extended! - Tuoora` (Extended)
  - `🔄 Subscription Plan Upgraded! - Tuoora` (Plan Change)
  - `✅ Subscription Renewal Approved! - Tuoora` (Renewal)
  - `💳 Payment Successful & Subscription Active! - Tuoora` (Online Paid)
- **Variables**:
  - `$instituteName` (string): Institute name.
  - `$planName` (string): Name of the plan (e.g. Pro Enterprise Plan).
  - `$expiresAt` (string): Expiration date.
  - `$amount` (float): Amount paid in INR.
  - `$type` (string): Event type.
- **Visual Features**: Event-specific color accent, subscription duration & expiration date grid, and subscription invoice link.

---

### Template 10: Demo Booking Request
- **File**: `resources/views/emails/demo_booked.blade.php`
- **Mailable**: `App\Mail\DemoBookedMail`
- **Default Subject**: `Software Demo Request Received - Tuoora`
- **Variables**:
  - `$data['name']`: Lead full name.
  - `$data['email']`: Lead email address.
  - `$data['phone']`: Lead phone number.
  - `$data['institute_name']`: Institute / Coaching name.
  - `$data['preferred_time']`: Preferred demo slot.
- **Visual Features**: Lead confirmation banner, summary of requested software demo, and calendar invite reminder.

---

## 4. SMTP Configuration & Troubleshooting

### System Default Gateway (`noreply@tuoora.com`):
Configured dynamically in `system_settings` table and `.env`:
- **Mailer**: `smtp`
- **Host**: `mail.tuoora.com`
- **Port**: `587`
- **Encryption**: `tls` (STARTTLS)
- **Username**: `noreply`
- **Password**: `N9UKLSZfivjDdYzg`
- **From Address**: `noreply@tuoora.com`
- **From Name**: `Tuoora`

### How an Institute Configures Their Own Custom SMTP:
In **Admin Panel** (`/admin/institutes/{id}/edit`) or **Institute Profile Settings**:
1. Toggle **"Enable Custom SMTP"** to **ON**.
2. **For Gmail**:
   - Host: `smtp.gmail.com`
   - Port: `465` (SSL) or `587` (TLS)
   - Username: `user@gmail.com`
   - Password: **16-digit Google App Password**
   - From Email: `user@gmail.com`
3. **For Custom Domain (cPanel / Webmail)**:
   - Host: `mail.yourdomain.com`
   - Port: `587` (TLS) or `465` (SSL)
   - Username: `info@yourdomain.com`
   - Password: Email password
   - From Email: `info@yourdomain.com`
4. Click **"⚡ TEST SMTP CONNECTION"** to verify delivery before saving.
