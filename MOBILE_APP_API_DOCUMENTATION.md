# New APIs Documentation for Mobile App

This document exclusively lists the **NEW APIs & Endpoints** recently developed for the Mobile Application (Institute, Student, and Parent apps).

---

## 1. Plans & White Label Add-On (New / Updated)

### 1.1 Subscription Plans with Dynamic White Label Add-On
Returns active plans along with the dynamic **Mobile App White Label Add-On** configured from Admin Panel.

- **Method**: `GET`
- **Endpoint**: `/api/v1/institute/plans` *(or public: `/api/public-plans`)*
- **Auth**: `Bearer <token>` *(optional for public)*

#### Response Payload:
```json
{
  "status": "success",
  "data": [
    {
      "id": 1,
      "name": "Growth Plan",
      "price": 4999,
      "duration_days": 365,
      "status": 1,
      "formatted_price": "₹4,999",
      "features": [
        "Unlimited Students",
        "Attendance & Fees",
        "WhatsApp Alerts"
      ]
    }
  ],
  "addons": [
    {
      "id": "mobile_app_whitelabel",
      "name": "Mobile App White Label",
      "title": "Mobile App White Label",
      "price": 5000,
      "billing_type": "One Time",
      "type": "one_time",
      "currency": "₹",
      "formatted_price": "₹5,000",
      "description": "Custom branded Android & iOS Mobile Application with your institute logo and name published on Google Play Store & Apple App Store.",
      "features": [
        "Your Logo on Play Store & App Store",
        "Branded Push Notification Channel"
      ],
      "is_active": true
    }
  ],
  "white_label_addon": {
    "id": "mobile_app_whitelabel",
    "name": "Mobile App White Label",
    "title": "Mobile App White Label",
    "price": 5000,
    "billing_type": "One Time",
    "type": "one_time",
    "currency": "₹",
    "formatted_price": "₹5,000",
    "description": "Custom branded Android & iOS Mobile Application with your institute logo and name published on Google Play Store & Apple App Store.",
    "features": [
      "Your Logo on Play Store & App Store",
      "Branded Push Notification Channel"
    ],
    "is_active": true
  }
}
```

---

### 1.2 All Subscription Data (with Dynamic Add-Ons & Payment Settings)
Returns current subscription status, days left, dynamic White Label add-on, and admin UPI/QR collection info.

- **Method**: `GET`
- **Endpoint**: `/api/v1/institute/subscriptions/all-data`
- **Auth**: `Bearer <token>`

#### Response Payload:
```json
{
  "status": "success",
  "data": {
    "subscription": {
      "plan_name": "Premium Growth Plan",
      "price": 7999,
      "status": "active",
      "status_label": "Active",
      "pending_days": 184,
      "expires_at": "2026-02-28",
      "students_enrolled": 45
    },
    "plans": [ ... ],
    "addons": [
      {
        "id": "mobile_app_whitelabel",
        "name": "Mobile App White Label",
        "price": 5000,
        "billing_type": "One Time",
        "formatted_price": "₹5,000",
        "features": [
          "Your Logo on Play Store & App Store",
          "Branded Push Notification Channel"
        ],
        "is_active": true
      }
    ],
    "white_label_addon": { ... },
    "payment_settings": {
      "bank_name": "State Bank of India",
      "bank_account": "123456789012",
      "bank_ifsc": "SBIN0001234",
      "upi_id": "institute@upi",
      "qr_url": "http://127.0.0.1:8000/storage/settings/qr.png"
    }
  }
}
```

---

## 2. Examination & Marks Entry Module (New)

### 2.1 Get Exam List
- **Method**: `GET`
- **Endpoint**: `/api/v1/institute/exams`
- **Auth**: `Bearer <token>`
- **Query Parameters**:
  - `batch_id` (optional, integer): Filter exams by batch.
  - `status` (optional, string): `scheduled` | `ongoing` | `completed`
  - `search` (optional, string): Search title or subject.

#### Response Payload:
```json
{
  "status": "success",
  "data": [
    {
      "id": 2,
      "batch_id": 6,
      "batch_name": "Batch-Physics",
      "title": "Unit -1",
      "subject": "Physics",
      "exam_date": "2026-08-22",
      "formatted_date": "22 Aug, 2026",
      "total_marks": 50,
      "passing_marks": 26,
      "status": "completed",
      "total_students": 2,
      "present_students": 2,
      "absent_students": 0,
      "passed_students": 2,
      "pass_percentage": 100
    }
  ]
}
```

---

### 2.2 Create New Exam
- **Method**: `POST`
- **Endpoint**: `/api/v1/institute/exams`
- **Auth**: `Bearer <token>`

#### Request Body:
```json
{
  "batch_id": 6,
  "title": "Unit - 2 Gravitation",
  "subject": "Physics",
  "exam_date": "2026-09-10",
  "total_marks": 50,
  "passing_marks": 20,
  "description": "Chapters 4 & 5"
}
```

---

### 2.3 Get Exam Marks & Student List
Fetches all enrolled students with their current marks, absent state, and remarks for marks entry.

- **Method**: `GET`
- **Endpoint**: `/api/v1/institute/exams/{exam_id}/marks`
- **Auth**: `Bearer <token>`

#### Response Payload:
```json
{
  "status": "success",
  "data": {
    "exam": {
      "id": 2,
      "title": "Unit -1",
      "subject": "Physics",
      "total_marks": 50,
      "passing_marks": 26,
      "exam_date": "2026-08-22"
    },
    "students": [
      {
        "student_id": 1,
        "student_name": "Tanvi Malaviya",
        "enrollment_id": "202634667100001",
        "phone": "9876543210",
        "profile_image": null,
        "marks_obtained": 30,
        "is_absent": false,
        "remarks": "Good performance",
        "status": "pass",
        "percentage": 60
      },
      {
        "student_id": 2,
        "student_name": "Rahul Sharma",
        "enrollment_id": "202634667100002",
        "phone": "9876543211",
        "profile_image": null,
        "marks_obtained": null,
        "is_absent": true,
        "remarks": "Absent",
        "status": "absent",
        "percentage": null
      }
    ]
  }
}
```

---

### 2.4 Save / Bulk Update Exam Marks
- **Method**: `POST`
- **Endpoint**: `/api/v1/institute/exams/{exam_id}/marks`
- **Auth**: `Bearer <token>`

#### Request Body:
```json
{
  "mark_status_as_completed": true,
  "marks": [
    {
      "student_id": 1,
      "marks_obtained": 30,
      "is_absent": false,
      "remarks": "Good performance"
    },
    {
      "student_id": 2,
      "marks_obtained": null,
      "is_absent": true,
      "remarks": "Absent on medical leave"
    }
  ]
}
```

#### Response:
```json
{
  "status": "success",
  "message": "Exam marks saved and updated successfully."
}
```

---

## 3. Student-Wise Report & Export APIs (New)

### 3.1 Get Single Student Detailed Report Data
Fetches student attendance statistics, fee records, examination performance, and homework submissions in one consolidated endpoint.

- **Method**: `GET`
- **Endpoint**: `/api/v1/institute/reports/student`
- **Auth**: `Bearer <token>`
- **Query Parameters**:
  - `batch_id` (required, integer)
  - `student_id` (required, integer)

#### Response Payload:
```json
{
  "status": "success",
  "data": {
    "student": {
      "id": 1,
      "name": "Tanvi Malaviya",
      "enrollment_id": "202634667100001",
      "phone": "9876543210",
      "batch_name": "Batch-Physics"
    },
    "attendance": {
      "total_classes": 24,
      "present_count": 22,
      "absent_count": 2,
      "attendance_percentage": 91.6
    },
    "fees": {
      "total_fee": 15000,
      "paid_amount": 10000,
      "pending_amount": 5000
    },
    "exams": [
      {
        "exam_title": "Unit -1",
        "total_marks": 50,
        "passing_marks": 26,
        "marks_obtained": 30,
        "is_absent": false,
        "status": "Pass"
      }
    ],
    "homework": [
      {
        "title": "Chapter 1 Questions",
        "due_date": "2026-08-20",
        "status": "Submitted"
      }
    ]
  }
}
```

---

### 3.2 Export Student Report (PDF / Excel)
Downloads a cleanly formatted student report (with pagination separating Examination & Homework sections).

- **Method**: `GET`
- **Endpoint**: `/api/v1/institute/reports/student/export`
- **Auth**: `Bearer <token>`
- **Query Parameters**:
  - `batch_id` (required, integer)
  - `student_id` (required, integer)
  - `export_type` (required, string): `pdf` or `excel`
