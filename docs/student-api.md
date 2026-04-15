# Student API Documentation

Base path: `/api/v1/student`

Authentication: Bearer token (`Authorization: Bearer {token}`)

---

## Login

**POST** `/api/v1/student/login`

Request body:

```json
{
  "email": "student@example.com",
  "password": "password"
}
```

Success response:

```json
{
  "status": "success",
  "message": "Logged in successfully",
  "data": {
    "token": "...",
    "id": 1,
    "name": "John Doe",
    "email": "student@example.com",
    "batch_id": 1
  }
}
```

---

## Profile

**GET** `/api/v1/student/profile`

Success response:

```json
{
  "status": "success",
  "data": {
    "id": 1,
    "name": "John Doe",
    "email": "student@example.com",
    "phone": "9999999999",
    "batch_id": 1,
    "standard": "10th",
    "school_name": "XYZ School",
    "status": "active"
  }
}
```

---

## Dashboard

**GET** `/api/v1/student/dashboard`

Success response:

```json
{
  "status": "success",
  "data": {
    "student_name": "John Doe",
    "batch_id": 1,
    "batch_name": "Math Batch",
    "attendance_rate": 85.5,
    "total_fees": 5000.00,
    "paid_fees": 3000.00,
    "due_fees": 2000.00
  }
}
```

---

## Fees

**GET** `/api/v1/student/fees`

Success response:

```json
{
  "status": "success",
  "data": {
    "summary": {
      "total_fees": 5000.00,
      "paid_fees": 3000.00,
      "due_fees": 2000.00
    },
    "fees": [
      {
        "id": 1,
        "student_id": 1,
        "total_amount": "1000.00",
        "paid_amount": "1000.00",
        "due_amount": "0.00",
        "status": "paid",
        "month": "4",
        "year": 2026
      },
      {
        "id": 2,
        "student_id": 1,
        "total_amount": "1000.00",
        "paid_amount": "0.00",
        "due_amount": "1000.00",
        "status": "pending",
        "month": "5",
        "year": 2026
      }
    ]
  }
}
```

---

## Receipts

**GET** `/api/v1/student/receipts`

Success response:

```json
{
  "status": "success",
  "data": [
    {
      "id": 1,
      "student_id": 1,
      "receipt_number": "RCP-001",
      "amount": "1000.00",
      "payment_date": "2026-04-10",
      "created_at": "2026-04-10T12:00:00"
    }
  ]
}
```

---

## Attendance

**GET** `/api/v1/student/attendance`

Success response:

```json
{
  "status": "success",
  "data": {
    "summary": {
      "total_days": 20,
      "present": 17,
      "absent": 2,
      "leave": 1,
      "attendance_rate": 85.0
    },
    "records": [
      {
        "id": 1,
        "student_id": 1,
        "batch_id": 1,
        "date": "2026-04-15",
        "status": "present"
      },
      {
        "id": 2,
        "student_id": 1,
        "batch_id": 1,
        "date": "2026-04-14",
        "status": "present"
      }
    ]
  }
}
```

---

## Daily Updates

**GET** `/api/v1/student/daily-updates`

Success response:

```json
{
  "status": "success",
  "data": [
    {
      "id": 1,
      "batch_id": 1,
      "topic": "Algebra Review",
      "description": "Covered linear equations and problem solving.",
      "date": "2026-04-15"
    },
    {
      "id": 2,
      "batch_id": 1,
      "topic": "Quadratic Equations",
      "description": "Solved sample problems on quadratic equations.",
      "date": "2026-04-14"
    }
  ]
}
```

---

## Homeworks

**GET** `/api/v1/student/homeworks`

Success response:

```json
{
  "status": "success",
  "data": [
    {
      "id": 1,
      "title": "Chapter 1 Exercises",
      "description": "Complete problems 1-10 from chapter 1.",
      "due_date": "2026-04-18",
      "submission_status": "submitted",
      "submission_date": "2026-04-17T10:30:00"
    },
    {
      "id": 2,
      "title": "Chapter 2 Exercises",
      "description": "Complete problems 1-15 from chapter 2.",
      "due_date": "2026-04-25",
      "submission_status": "not_submitted",
      "submission_date": null
    }
  ]
}
```

---

## Report

**GET** `/api/v1/student/report`

Success response:

```json
{
  "status": "success",
  "data": {
    "student_name": "John Doe",
    "standard": "10th",
    "school_name": "XYZ School",
    "batch_name": "Math Batch",
    "fees": {
      "total": 5000.00,
      "paid": 3000.00,
      "due": 2000.00
    },
    "attendance": {
      "total_days": 20,
      "present_days": 17,
      "attendance_rate": 85.0
    }
  }
}
```

---

## Notifications

**GET** `/api/v1/student/notifications`

Success response:

```json
{
  "status": "success",
  "data": [
    {
      "id": 1,
      "user_type": "student",
      "user_id": 1,
      "title": "Fee Payment Reminder",
      "message": "Your fee payment is due on 2026-04-25",
      "type": "reminder",
      "is_read": false,
      "created_at": "2026-04-15T10:00:00"
    },
    {
      "id": 2,
      "user_type": "student",
      "user_id": 1,
      "title": "Attendance Alert",
      "message": "Your attendance is below 75%. Please attend classes.",
      "type": "alert",
      "is_read": true,
      "created_at": "2026-04-14T09:30:00"
    }
  ]
}
```

---

## Logout

**POST** `/api/v1/student/logout`

Success response:

```json
{
  "status": "success",
  "message": "Logged out successfully"
}
```
