# Parent API Documentation

Base path: `/api/v1/parent`

Authentication: Bearer token (`Authorization: Bearer {token}`)

---

## Login

**POST** `/api/v1/parent/login`

Request body:

```json
{
  "email": "parent@example.com",
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
    "name": "Parent Name",
    "email": "parent@example.com",
    "phone": "9999999999",
    "relation": "father",
    "status": "active"
  }
}
```

---

## Profile

**GET** `/api/v1/parent/profile`

**GET** `/api/v1/parent/profil`

Success response:

```json
{
  "status": "success",
  "data": {
    "id": 1,
    "name": "Parent Name",
    "email": "parent@example.com",
    "phone": "9999999999",
    "relation": "father",
    "status": "active",
    "students": [
      {
        "id": 1,
        "name": "John Doe",
        "batch_id": 1,
        "standard": "10th"
      }
    ]
  }
}
```

---

## Logout

**POST** `/api/v1/parent/logout`

Success response:

```json
{
  "status": "success",
  "message": "Logged out successfully"
}
```

---

## Dashboard

**GET** `/api/v1/parent/dashboard`

Success response:

```json
{
  "status": "success",
  "data": {
    "parent_name": "Parent Name",
    "children_count": 1,
    "children": [
      {
        "id": 1,
        "name": "John Doe",
        "batch_id": 1,
        "batch_name": "Math Batch",
        "standard": "10th"
      }
    ],
    "attendance_rate": 85.0,
    "total_fees": 5000.00,
    "paid_fees": 3000.00,
    "due_fees": 2000.00
  }
}
```

---

## Fees

**GET** `/api/v1/parent/fees`

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
        "year": 2026,
        "student": {
          "id": 1,
          "name": "John Doe"
        }
      }
    ]
  }
}
```

---

## Pay Fee

**POST** `/api/v1/parent/pay-fee`

Request body:

```json
{
  "fee_id": 1,
  "amount": 1000.00,
  "payment_method": "bank_transfer",
  "transaction_id": "TXN12345",
  "paid_at": "2026-04-16T10:00:00"
}
```

Success response:

```json
{
  "status": "success",
  "message": "Payment recorded successfully",
  "data": {
    "payment_id": 1,
    "fee_id": 1,
    "amount": 1000.00,
    "payment_method": "bank_transfer",
    "transaction_id": "TXN12345",
    "paid_at": "2026-04-16T10:00:00",
    "total_fee": 1000.00,
    "paid_so_far": 1000.00,
    "remaining_due": 0.00,
    "fee_status": "Paid"
  }
}
```

---

## Receipts

**GET** `/api/v1/parent/receipts`

Success response:

```json
{
  "status": "success",
  "data": [
    {
      "id": 1,
      "payment_id": 1,
      "receipt_number": "RE-ABC123-0001",
      "file_url": null,
      "created_at": "2026-04-16T10:00:00",
      "payment": {
        "id": 1,
        "student_id": 1,
        "amount": "1000.00",
        "payment_method": "bank_transfer",
        "transaction_id": "TXN12345",
        "paid_at": "2026-04-16T10:00:00"
      }
    }
  ]
}
```

---

## Attendance

**GET** `/api/v1/parent/attendance`

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
        "status": "present",
        "student": {
          "id": 1,
          "name": "John Doe"
        }
      }
    ]
  }
}
```

---

## Daily Updates

**GET** `/api/v1/parent/daily-updates`

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
    }
  ]
}
```

---

## Homeworks

**GET** `/api/v1/parent/homeworks`

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
      "submissions": [
        {
          "student_id": 1,
          "student_name": "John Doe",
          "submission_status": "submitted",
          "submission_date": "2026-04-17T10:30:00"
        }
      ]
    }
  ]
}
```

---

## Report

**GET** `/api/v1/parent/report`

Success response:

```json
{
  "status": "success",
  "data": {
    "parent_name": "Parent Name",
    "children_count": 1,
    "children": [
      {
        "id": 1,
        "name": "John Doe",
        "standard": "10th",
        "school_name": "XYZ School",
        "batch_name": "Math Batch"
      }
    ],
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

**GET** `/api/v1/parent/notifications`

Success response:

```json
{
  "status": "success",
  "data": [
    {
      "id": 1,
      "user_type": "parent",
      "user_id": 1,
      "title": "Fee Reminder",
      "message": "Your child's fees are due on 2026-04-25",
      "type": "reminder",
      "is_read": false,
      "created_at": "2026-04-16T10:00:00"
    }
  ]
}
```
