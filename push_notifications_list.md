# 📱 Fee Easy / Tuoora — Push Notifications Inventory

This document maps out every single Firebase Cloud Messaging (FCM) push notification triggered across the entire Fee Easy / Tuoora backend platform. It contains default titles, dynamic body templates, target audiences, exact FCM `data` payloads, and trigger conditions.

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
* **FCM Body:** `[Message text snippet]` (or `"Sent an image"` / `"Sent an attachment"`)
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
* **FCM Title:** `⚠️ Plan Expiring Soon!`
* **FCM Body (0 days left):** `Your '[Plan Name]' subscription plan expires today ([Date])! Please renew now to prevent service interruption.`
* **FCM Body (1-7 days left):** `Your '[Plan Name]' subscription plan will expire in [Days] day(s) on [Date]. Please renew your plan.`
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
* **FCM Title:** `✅ Subscription Renewal Approved!`
* **FCM Body:** `Your subscription renewal request for the '[Plan Name]' plan has been approved. Thank you for your payment!`
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
* **FCM Title:** `❌ Subscription Renewal Rejected`
* **FCM Body:** `Your subscription renewal request has been rejected. Please verify your payment screenshot/transaction ID and submit again, or contact support.`
* **`data` Payload:**
  ```json
  {
      "type": "subscription_alert",
      "status": "rejected"
  }
  ```

---

### 📚 Homework & Assignment Module
#### 1. New Homework Assigned
* **Trigger Location:** `InstituteHomeworkController.php` (line 124)
* **Trigger Condition:** Institute staff creates and assigns a new homework task to a batch.
* **Target:** Students in the batch & their Parents
* **FCM Title:** `New Homework: [Homework Title] 📚`
* **FCM Body (Student):** `A new homework "[Homework Title]" has been assigned to your batch. Due on: [Due Date]`
* **FCM Body (Parent):** `A new homework "[Homework Title]" has been assigned to [Student Name]'s batch. Due on: [Due Date]`
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
* **FCM Title:** `Homework Graded: [Homework Title] 📝`
* **FCM Body (Student):** `Your submission for "[Homework Title]" has been graded! Score: [Score]/10`
* **FCM Body (Parent):** `[Student Name]'s submission for "[Homework Title]" has been graded! Score: [Score]/10`
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
* **FCM Title (1 Day Left):** `Homework Due Tomorrow 📚`
* **FCM Title (2/3 Days Left):** `Homework Due in [Days] Days 📚`
* **FCM Body (Student):** `Reminder: "[Homework Title]" is due [tomorrow / in X days]. Submit on time!`
* **FCM Body (Parent):** `[Student Name]'s homework "[Homework Title]" is due [tomorrow / in X days]!`
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
* **FCM Title (Present):** `Attendance Update: Present 🟢`
* **FCM Title (Absent):** `Attendance Alert: Absent 🔴`
* **FCM Body (Present - Student):** `You have been marked Present for today's classes.`
* **FCM Body (Present - Parent):** `[Student Name] has been marked Present for today's classes.`
* **FCM Body (Absent - Student):** `You have been marked Absent for today's classes. (If this is a mistake, contact staff)`
* **FCM Body (Absent - Parent):** `[Student Name] has been marked Absent for today's classes. Please check.`
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
* **FCM Title:** `New Announcement: [Batch Name] 📢`
* **FCM Body:** `Notice: [Announcement Content Text]`
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
* **FCM Title:** `Batch Timings Updated 🕒`
* **FCM Body:** `Class timings for [Batch Name] have been changed to: [New Timings]`
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
* **FCM Title:** `Batch Assignment Update 🏫`
* **FCM Body (Student):** `You have been successfully reassigned to batch: [New Batch Name]`
* **FCM Body (Parent):** `[Student Name] has been successfully reassigned to batch: [New Batch Name]`
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
* **FCM Title:** `Daily Update: [Subject Name] 📔`
* **FCM Body:** `Today's Topic: "[Topic Name]" - [Description]`
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
* **FCM Title:** `New Resource: [File Name] 📖`
* **FCM Body:** `A new resource has been uploaded: "[Title]". Category: [Category Name]`
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
* **FCM Title:** `Happy Birthday! 🎂🎉`
* **FCM Body (Student):** `Happy Birthday [Student Name]! May all your dreams come true! Have a wonderful day!`
* **FCM Body (Parent):** `Wishing your child [Student Name] a very Happy Birthday! 🎂🎉`
* **`data` Payload:**
  ```json
  {
      "type": "birthday"
  }
  ```

---

### 📣 Broadcast Module
#### Custom Notification Blast
* **Trigger Location:** `InstituteNotificationController.php` (line 72)
* **Trigger Condition:** Admin broadcasts a customized announcement or notice to all students / parents in the institute.
* **Target:** Selected Audience (Students, Parents, or both)
* **FCM Title:** `[Notice Title] 📣`
* **FCM Body:** `[Notice Body Content]`
* **`data` Payload:**
  ```json
  {
      "type": "broadcast",
      "notification_id": "[notification_id]"
  }
  ```

---

### 🎓 Student Admission / Profile Module
#### Student Admission Approved
* **Trigger Location:** `InstituteStudentController.php` (line 279)
* **Trigger Condition:** Admin registers and activates a new student profile.
* **Target:** Parent & Student
* **FCM Title:** `Welcome to [Institute Name]! 🎉`
* **FCM Body:** `Student registration for [Student Name] is complete. Welcome to our learning community!`
* **`data` Payload:**
  ```json
  {
      "type": "admission",
      "student_id": "[student_id]"
  }
  ```
