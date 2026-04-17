# Institute API Documentation

Base path: `/api/v1/institute`

Authentication: Bearer token (`Authorization: Bearer {token}`)

---

## Login

**POST** `/api/v1/institute/login`

Request body:

```json
{
  "email": "institute@example.com",
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
    "email": "institute@example.com",
    "institute_name": "Test Institute Academy"
  }
}
```

---

## Profile

**GET** `/api/v1/institute/profile`

Success response:

```json
{
  "status": "success",
  "data": {
    "id": 1,
    "name": "Test Institute",
    "email": "institute@example.com",
    "phone": "9999999999",
    "institute_name": "Test Institute Academy",
    "logo": null,
    "address": null
  }
}
```

---

## Logo Upload

**POST** `/api/v1/institute/logo/upload`

Content type: `multipart/form-data`

Fields:
- `logo`: image file

Success response:

```json
{
  "status": "success",
  "message": "Logo uploaded successfully.",
  "data": {
    "logo": "institutes/logos/abcd1234.png"
  }
}
```

---

## Daily Updates

### Create daily update

**POST** `/api/v1/institute/daily-updates`

Request body:

```json
{
  "batch_id": 1,
  "topic": "Algebra Review",
  "description": "Covered linear equations and problem solving.",
  "date": "2026-04-15"
}
```

Success response:

```json
{
  "status": "success",
  "message": "Daily update created successfully.",
  "data": {
    "id": 1,
    "batch_id": 1,
    "topic": "Algebra Review"
  }
}
```

### List daily updates

**GET** `/api/v1/institute/daily-updates`

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

### Create homework

**POST** `/api/v1/institute/homeworks`

Request body:

```json
{
  "batch_id": 1,
  "title": "Chapter 1 Exercises",
  "description": "Complete problems 1-10.",
  "due_date": "2026-04-18"
}
```

Success response:

```json
{
  "status": "success",
  "message": "Homework created successfully.",
  "data": {
    "id": 1,
    "title": "Chapter 1 Exercises"
  }
}
```

### List homeworks

**GET** `/api/v1/institute/homeworks`

Success response:

```json
{
  "status": "success",
  "data": [
    {
      "id": 1,
      "title": "Chapter 1 Exercises",
      "description": "Complete problems 1-10.",
      "due_date": "2026-04-18"
    }
  ]
}
```

---

## Notifications

### Send notification

**POST** `/api/v1/institute/notifications/send`

Request body:

```json
{
  "title": "Notice",
  "message": "The course schedule has changed.",
  "type": "announcement",
  "target": "students",
  "reference_id": 123
}
```

Success response:

```json
{
  "status": "success",
  "message": "Notification queued successfully.",
  "data": {
    "id": 1,
    "title": "Notice",
    "message": "The course schedule has changed."
  }
}
```

### List notifications

**GET** `/api/v1/institute/notifications`

Success response:

```json
{
  "status": "success",
  "data": [
    {
      "id": 1,
      "title": "Notice",
      "message": "The course schedule has changed."
    }
  ]
}
```

---

## WhatsApp Settings

### Get settings

**GET** `/api/v1/institute/whatsapp-settings`

Success response:

```json
{
  "status": "success",
  "data": {
    "phone_number": "9999999999",
    "access_token": "token-abc",
    "is_active": true
  }
}
```

### Create / Update settings

**POST** `/api/v1/institute/whatsapp-settings`
**PUT** `/api/v1/institute/whatsapp-settings`

Request body:

```json
{
  "phone_number": "9999999999",
  "access_token": "token-abc",
  "phone_number_id": "12345",
  "business_account_id": "67890"
}
```

Success response:

```json
{
  "status": "success",
  "message": "WhatsApp settings saved successfully.",
  "data": {
    "phone_number": "9999999999",
    "is_active": true
  }
}
```

---

## Reports

### Dashboard

**GET** `/api/v1/institute/reports/dashboard`

Success response:

```json
{
  "status": "success",
  "data": {
    "students_count": 10,
    "batches_count": 5,
    "active_subscriptions": 1,
    "trial_subscriptions": 0,
    "total_revenue": 500.00,
    "total_fees": 1000.00,
    "total_due_fees": 600.00
  }
}
```

### Income report

**GET** `/api/v1/institute/reports/income`

Success response:

```json
{
  "status": "success",
  "data": {
    "summary": {
      "count": 1,
      "total_amount": 500.00
    },
    "payments": [
      {
        "id": 1,
        "amount": 500.00,
        "payment_gateway": "stripe"
      }
    ]
  }
}
```

### Fees report

**GET** `/api/v1/institute/reports/fees`

Success response:

```json
{
  "status": "success",
  "data": {
    "summary": {
      "count": 1,
      "total_amount": 1000.00,
      "paid_amount": 400.00,
      "due_amount": 600.00
    },
    "fees": [
      {
        "id": 1,
        "total_amount": 1000.00,
        "paid_amount": 400.00,
        "due_amount": 600.00
      }
    ]
  }
}
```

---

## Subscription

### Get current subscription

**GET** `/api/v1/institute/subscription`

Success response:

```json
{
  "status": "success",
  "data": {
    "id": 1,
    "plan_name": "Starter",
    "amount": 500.00,
    "status": "active"
  }
}
```

### Renew subscription

**POST** `/api/v1/institute/subscription/renew`

Request body:

```json
{
  "amount": 700.00,
  "days": 30,
  "payment_gateway": "razorpay",
  "payment_source": "manual",
  "transaction_id": "txn_renew_1"
}
```

Success response:

```json
{
  "status": "success",
  "message": "Subscription renewed successfully.",
  "data": {
    "id": 1,
    "plan_name": "Subscription Renewal",
    "amount": 700.00,
    "status": "active"
  }
}
```
