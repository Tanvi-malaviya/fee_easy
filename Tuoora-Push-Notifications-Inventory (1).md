# 📱 Fee Easy / Tuoora — Push Notifications Inventory

This document maps out every single Firebase Cloud Messaging (FCM) push notification triggered across the entire Fee Easy / Tuoora backend platform. It contains default titles, dynamic body templates, target audiences, exact FCM `data` payloads, and trigger conditions.

> **Copy style applied throughout:** Short, scannable titles (no all-caps, no double emoji). One emoji only where it adds genuine warmth (birthday); none on money/status alerts so they read as trustworthy. Consistent "your" (student) vs "[Student Name]'s" (parent) phrasing. Bodies kept under ~90 characters to avoid truncation in the notification tray.

---

## 🗂️ 1. Notification Categories & Preferences Mapping
Every push notification category resolves to a key in the user's `notification_settings` column (stored in the Student/Parent models) so users can enable/disable them individually:

| Notification Type (`data.type`) | Settings Category Key | Mute/Unmute Controlled By |
| :--- | :--- | :--- |
| `chat` | *Always Enabled* | None (Direct Chat) |
| `fee`, `fee_reminder`, `payment` | `fee_reminders` | Fee Alerts |
| `homework`, `homework_graded`, `assignment`, `homework_reminder` | `assignment_alerts` | Homework Alerts |
| `attendance` | `attendance` | Attendance Alerts |
| `daily_update` | `daily_updates` | Daily Progress Updates |
| `announcement`, `events`, `others`, `subscription_alert`, `birthday` | `events_holidays` | Event / Notice Alerts |

---

## 🚀 2. Comprehensive Push Notification Inventory

### 💬 Chat Module
#### New Direct Message Alert
* **Trigger Location:** `ChatController.php` (line 113)
* **Trigger Condition:** A user sends a text, image, or file message to another user in direct chat.
* **Target:** Student, Parent, or Staff (the message receiver)
* **FCM Title:** `[Sender Name]`
* **FCM Body:** `[Message text snippet]` (or `📷 Sent a photo` / `📎 Sent an attachment`)
* **`data` Payload:**
  ```json
  {
      "type": "chat",
      "sender_id": "[sender_id]",
      "sender_type": "[sender_type_class]"
  }
  ```

---

### 💳 Subscription & Renewal Module
#### 1. Plan Expiring Soon (Warning Alert)
* **Trigger Location:** `CheckSubscriptionExpiry.php` (line 69)
* **Trigger Condition:** Daily Cron Scheduler runs at midnight; triggers warning for subscriptions expiring in 7 days down to 0 days.
* **Target:** Institute Admin
* **FCM Title (0 days left):** `Plan Expiring Today`
* **FCM Body (0 days left):** `Your [Plan Name] plan expires today. Renew now to keep your services active.`
* **FCM Title (1-7 days left):** `Plan Expiring Soon`
* **FCM Body (1-7 days left):** `Your [Plan Name] plan expires in [Days] days on [Date]. Tap to renew.`
* **`data` Payload:**
  ```json
  {
      "type": "subscription_alert",
      "plan_name": "[plan_name]",
      "days_remaining": "[days_remaining_integer_as_string]"
  }
  ```

#### 2. Subscription Renewal Approved
* **Trigger Location:** `SubscriptionController.php` (line 309)
* **Trigger Condition:** Super Admin clicks "APPROVE" on an offline subscription payment screenshot.
* **Target:** Institute Admin
* **FCM Title:** `Renewal Approved`
* **FCM Body:** `Your [Plan Name] plan has been renewed and is now active. Thank you!`
* **`data` Payload:**
  ```json
  {
      "type": "subscription_alert",
      "plan_name": "[plan_name]",
      "status": "approved"
  }
  ```

#### 3. Subscription Renewal Rejected
* **Trigger Location:** `SubscriptionController.php` (line 347)
* **Trigger Condition:** Super Admin clicks "REJECT" on an offline subscription renewal request.
* **Target:** Institute Admin
* **FCM Title:** `Renewal Needs Attention`
* **FCM Body:** `We couldn't verify your payment. Please recheck the details and resubmit, or contact support.`
* **`data` Payload:**
  ```json
  {
      "type": "subscription_alert",
      "status": "rejected"
  }
  ```

#### 4. Plan Activated (New Purchase) — *new*
* **Trigger Location:** *To be implemented (e.g. `SubscriptionController.php` on first activation)*
* **Trigger Condition:** A new subscription plan is purchased/activated for an institute for the first time.
* **Target:** Institute Admin
* **FCM Title:** `Plan Activated`
* **FCM Body:** `Your [Plan Name] plan is now active. Welcome aboard!`
* **`data` Payload:**
  ```json
  {
      "type": "subscription_alert",
      "plan_name": "[plan_name]",
      "status": "activated"
  }
  ```

---

### 📚 Homework & Assignment Module
#### 1. New Homework Assigned
* **Trigger Location:** `InstituteHomeworkController.php` (line 124)
* **Trigger Condition:** Institute staff creates and assigns a new homework task to a batch.
* **Target:** Students in the batch & their Parents
* **FCM Title:** `New Homework Assigned`
* **FCM Body (Student):** `"[Homework Title]" has been assigned to your batch. Due [Due Date].`
* **FCM Body (Parent):** `"[Homework Title]" has been assigned to [Student Name]'s batch. Due [Due Date].`
* **`data` Payload:**
  ```json
  {
      "type": "homework",
      "homework_id": "[homework_id]",
      "batch_id": "[batch_id]"
  }
  ```

#### 2. Homework Graded / Score Updated
* **Trigger Location:** `InstituteHomeworkController.php` (line 335)
* **Trigger Condition:** Staff reviews a homework submission and posts a grade score.
* **Target:** Student & Parent
* **FCM Title:** `Homework Graded`
* **FCM Body (Student):** `Your work on "[Homework Title]" is graded. Score: [Score]/[Max].`
* **FCM Body (Parent):** `[Student Name]'s work on "[Homework Title]" is graded. Score: [Score]/[Max].`
* **`data` Payload:**
  ```json
  {
      "type": "homework_graded",
      "homework_id": "[homework_id]",
      "batch_id": "[batch_id]"
  }
  ```

#### 3. Automated Homework Due Reminders (3, 2, and 1 Days Left)
* **Trigger Location:** `SendHomeworkReminders.php` (line 46)
* **Trigger Condition:** Daily Cron Scheduler runs at 8:00 AM. Triggers reminders for active homework due in 3, 2, or 1 days (auto-skips students who have already submitted).
* **Target:** Students & Parents
* **FCM Title (1 Day Left):** `Homework Due Tomorrow`
* **FCM Title (2/3 Days Left):** `Homework Due in [Days] Days`
* **FCM Body (1 Day - Student):** `"[Homework Title]" is due tomorrow. Don't forget to submit.`
* **FCM Body (1 Day - Parent):** `[Student Name]'s homework "[Homework Title]" is due tomorrow.`
* **FCM Body (2/3 Days - Student):** `"[Homework Title]" is due in [Days] days. Plan ahead!`
* **FCM Body (2/3 Days - Parent):** `[Student Name]'s homework "[Homework Title]" is due in [Days] days.`
* **`data` Payload:**
  ```json
  {
      "type": "homework_reminder",
      "homework_id": "[homework_id]",
      "batch_id": "[batch_id]"
  }
  ```

---

### 📝 Attendance Module
#### Daily Attendance Marked
* **Trigger Location:** `InstituteAttendanceController.php` (line 93)
* **Trigger Condition:** Staff takes and submits daily attendance for a batch.
* **Target:** Parents & Students
* **FCM Title (Present):** `Marked Present`
* **FCM Title (Absent):** `Marked Absent`
* **FCM Body (Present - Student):** `You've been marked present for today.`
* **FCM Body (Present - Parent):** `[Student Name] has been marked present for today.`
* **FCM Body (Absent - Student):** `You've been marked absent today. If this is a mistake, contact your institute.`
* **FCM Body (Absent - Parent):** `[Student Name] has been marked absent today. Please contact the institute if unexpected.`
* **`data` Payload:**
  ```json
  {
      "type": "attendance",
      "status": "[present / absent]",
      "date": "[current_date]"
  }
  ```

---

### 📢 Batch & Announcements Module
#### 1. General Notice / Batch Announcement
* **Trigger Location:** `InstituteBatchController.php` (line 213)
* **Trigger Condition:** Staff posts an announcement card to a specific batch.
* **Target:** Students in the batch & their Parents
* **FCM Title:** `New Announcement · [Batch Name]`
* **FCM Body:** `[Announcement Content Text]`
* **`data` Payload:**
  ```json
  {
      "type": "announcement",
      "batch_id": "[batch_id]"
  }
  ```

#### 2. Class Time / Timing Update
* **Trigger Location:** `InstituteBatchController.php` (line 334)
* **Trigger Condition:** Admin updates class timings for a batch.
* **Target:** Students in the batch & their Parents
* **FCM Title:** `Batch Timings Updated`
* **FCM Body:** `[Batch Name] class timings are now [New Timings].`
* **`data` Payload:**
  ```json
  {
      "type": "announcement",
      "batch_id": "[batch_id]"
  }
  ```

#### 3. Student Batch Changed / Transferred
* **Trigger Location:** `InstituteBatchController.php` (line 403)
* **Trigger Condition:** Admin shifts a student from one batch to a different batch.
* **Target:** Student & Parent
* **FCM Title:** `Batch Updated`
* **FCM Body (Student):** `You've been moved to [New Batch Name].`
* **FCM Body (Parent):** `[Student Name] has been moved to [New Batch Name].`
* **`data` Payload:**
  ```json
  {
      "type": "announcement",
      "batch_id": "[new_batch_id]"
  }
  ```

---

### 📔 Daily Update (Diary) Module
#### Daily Progress Update Added
* **Trigger Location:** `InstituteDailyUpdateController.php` (line 106)
* **Trigger Condition:** Staff posts a daily class progress update / diary entry for a batch.
* **Target:** Students in the batch & their Parents
* **FCM Title:** `Daily Update · [Subject Name]`
* **FCM Body:** `Today's topic: "[Topic Name]". [Short Description]`
  > *Tip: cap `[Short Description]` server-side to ~80 characters so it doesn't truncate mid-word in the tray.*
* **`data` Payload:**
  ```json
  {
      "type": "daily_update",
      "subject": "[subject_name]",
      "batch_id": "[batch_id]"
  }
  ```

---

### 📖 Resource & Material Module
#### New Study Material / Resource Uploaded
* **Trigger Location:** `InstituteResourceController.php` (line 106)
* **Trigger Condition:** Staff uploads new syllabus, notes, or assignment files to a batch folder.
* **Target:** Students in the batch & their Parents
* **FCM Title:** `New Study Material`
* **FCM Body:** `New [Category Name]: "[Title]" is now available.`
* **`data` Payload:**
  ```json
  {
      "type": "resource",
      "resource_id": "[resource_id]",
      "batch_id": "[batch_id]"
  }
  ```

---

### 🎂 Birthday Greetings Module
#### Automated Happy Birthday Greeting
* **Trigger Location:** `SendBirthdayNotifications.php` (line 45)
* **Trigger Condition:** Daily Cron Scheduler runs at 9:00 AM; triggers birthday greeting if student's `dob` matches today's date.
* **Target:** Student & Parent
* **FCM Title:** `Happy Birthday! 🎂`
* **FCM Body (Student):** `Happy Birthday, [Student Name]! Wishing you a wonderful day ahead. 🎉`
* **FCM Body (Parent):** `Wishing [Student Name] a very Happy Birthday! 🎂🎉`
* **`data` Payload:**
  ```json
  {
      "type": "birthday"
  }
  ```

---

### 💰 Fees & Payment Module — *new (fills existing settings gap)*
> The `notification_settings` table already reserves `fee`, `fee_reminder`, and `payment` keys, but no notifications were defined for them. Added below.

#### 1. New Fee Invoice Generated
* **Trigger Location:** *To be implemented (e.g. `InstituteFeeController.php` on invoice create)*
* **Trigger Condition:** Institute generates a new fee invoice for a student.
* **Target:** Student & Parent
* **FCM Title:** `New Fee Invoice`
* **FCM Body (Student):** `A new invoice of [Amount] is due by [Due Date].`
* **FCM Body (Parent):** `A new invoice of [Amount] for [Student Name] is due by [Due Date].`
* **`data` Payload:**
  ```json
  {
      "type": "fee",
      "invoice_id": "[invoice_id]",
      "amount": "[amount]"
  }
  ```

#### 2. Fee Payment Reminder
* **Trigger Location:** *To be implemented (Daily Cron Scheduler)*
* **Trigger Condition:** An unpaid invoice is due tomorrow or in X days.
* **Target:** Student & Parent
* **FCM Title:** `Fee Payment Reminder`
* **FCM Body (Student):** `[Amount] is due [tomorrow / in X days]. Tap to pay.`
* **FCM Body (Parent):** `[Student Name]'s fee of [Amount] is due [tomorrow / in X days].`
* **`data` Payload:**
  ```json
  {
      "type": "fee_reminder",
      "invoice_id": "[invoice_id]",
      "amount": "[amount]"
  }
  ```

#### 3. Payment Received
* **Trigger Location:** *To be implemented (on payment confirmation)*
* **Trigger Condition:** A student's fee payment is recorded/confirmed.
* **Target:** Student & Parent
* **FCM Title:** `Payment Received`
* **FCM Body (Student):** `Your payment of [Amount] has been received. Thank you!`
* **FCM Body (Parent):** `Payment of [Amount] for [Student Name] received. Thank you!`
* **`data` Payload:**
  ```json
  {
      "type": "payment",
      "invoice_id": "[invoice_id]",
      "amount": "[amount]"
  }
  ```

---

### 🎓 Account Module — *new (matches onboarding email)*
#### Student Account Created
* **Trigger Location:** *To be implemented (on student account creation)*
* **Trigger Condition:** Institute adds a new student and the account is created.
* **Target:** Student (& Parent)
* **FCM Title:** `Welcome to Tuoora!`
* **FCM Body:** `Your account is ready. Tap to log in and get started.`
* **`data` Payload:**
  ```json
  {
      "type": "others",
      "action": "account_created"
  }
  ```

---
