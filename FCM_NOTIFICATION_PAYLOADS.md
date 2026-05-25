# Push Notification Payload Reference Guide

This document defines the exact JSON structure and key-value fields sent from the Laravel Backend to the client applications (Student/Parent apps) via Firebase Cloud Messaging (FCM) HTTP v1 API.

Use this reference to map and implement the foreground and background message handlers on the mobile app side.

---

## 1. Global JSON Envelope (FCM HTTP v1 Standard)
Every notification dispatched by the backend wraps the payload in this standardized structure:

```json
{
  "message": {
    "token": "RECIPIENT_DEVICE_FCM_TOKEN",
    "notification": {
      "title": "Visible Notification Title",
      "body": "Visible Notification Body/Message text"
    },
    "data": {
      "type": "notification_type_identifier",
      "...": "additional string-cast keys based on type"
    }
  }
}
```

> [!IMPORTANT]
> The Firebase HTTP v1 specification requires **all fields** inside the `"data"` block to be strings. Ensure you parse numeric IDs accordingly (e.g. `int.parse()` in Dart/Flutter or `parseInt()` in JS/React Native).

---

## 2. Notification Schemas by Type

### A. Attendance (`type` = `attendance`)
Sent when a student is marked Present/Absent/Late.

```json
{
  "message": {
    "token": "DEVICE_TOKEN",
    "notification": {
      "title": "Attendance: Present 📝",
      "body": "You have been marked Present for class on 25 May 2026."
    },
    "data": {
      "type": "attendance",
      "date": "2026-05-25",
      "status": "present"
    }
  }
}
```

---

### B. New Homework Assigned (`type` = `homework`)
Sent when a teacher/institute publishes new homework for a batch.

```json
{
  "message": {
    "token": "DEVICE_TOKEN",
    "notification": {
      "title": "New Homework: Mathematics 📝",
      "body": "Complete exercises 1 to 5 on Page 42."
    },
    "data": {
      "type": "homework",
      "homework_id": "24",
      "batch_id": "5"
    }
  }
}
```

---

### C. Homework Graded (`type` = `homework_graded`)
Sent when a student's homework submission is scored and graded.

```json
{
  "message": {
    "token": "DEVICE_TOKEN",
    "notification": {
      "title": "Homework Graded! 🌟",
      "body": "Your homework \"Algebra Intro\" has been graded. Score: 95!"
    },
    "data": {
      "type": "homework_graded",
      "homework_id": "24",
      "batch_id": "5"
    }
  }
}
```

---

### D. Homework Reminder (`type` = `homework_reminder`)
Sent as a reminder for pending homework prior to the due date.

```json
{
  "message": {
    "token": "DEVICE_TOKEN",
    "notification": {
      "title": "Homework Pending! 📝",
      "body": "Reminder: \"Algebra Intro\" is still pending. Please submit it soon!"
    },
    "data": {
      "type": "homework_reminder",
      "homework_id": "24",
      "batch_id": "5"
    }
  }
}
```

---

### E. Daily Update / Diary (`type` = `daily_update`)
Sent when the institute posts a daily diary, circular, or announcement for the batch.

```json
{
  "message": {
    "token": "DEVICE_TOKEN",
    "notification": {
      "title": "New Diary Update: Chapter 2 Finished 📢",
      "body": "Today we completed the second chapter of Calculus. Review formulas."
    },
    "data": {
      "type": "daily_update",
      "update_id": "18",
      "category": "diary"
    }
  }
}
```

---

### F. Chat Messages (`type` = `chat`)
Sent when a user receives a direct real-time message.

```json
{
  "message": {
    "token": "DEVICE_TOKEN",
    "notification": {
      "title": "New message from Rahul Patel",
      "body": "Please review the notes I sent you yesterday."
    },
    "data": {
      "type": "chat",
      "chat_id": "89",
      "sender_id": "14",
      "sender_type": "StudentParent"
    }
  }
}
```

---

### G. Resource Added (`type` = `resource`)
Sent when a learning material, PDF, or video link is uploaded for the batch.

```json
{
  "message": {
    "token": "DEVICE_TOKEN",
    "notification": {
      "title": "New Resource: Science Batch",
      "body": "📄 Physics Lecture Notes.pdf"
    },
    "data": {
      "type": "resource",
      "resource_id": "42",
      "batch_id": "2",
      "file_type": "document"
    }
  }
}
```

---

### H. Batch Assigned (`type` = `batch_assignment`)
Sent when a student is successfully enrolled in a batch.

```json
{
  "message": {
    "token": "DEVICE_TOKEN",
    "notification": {
      "title": "Batch Assigned 📚",
      "body": "You have been assigned to the batch: Morning Super-30"
    },
    "data": {
      "type": "batch_assignment",
      "batch_id": "3"
    }
  }
}
```

---

### I. Batch Removed (`type` = `batch_removal`)
Sent when a student is removed from a batch.

```json
{
  "message": {
    "token": "DEVICE_TOKEN",
    "notification": {
      "title": "Batch Removed 🚫",
      "body": "You have been removed from the batch: Morning Super-30"
    },
    "data": {
      "type": "batch_removal",
      "batch_id": "3"
    }
  }
}
```

---

### J. System Broadcasts (`type` = `announcement` / `event` / `holiday`)
Sent globally to announce events, holidays, or alerts.

```json
{
  "message": {
    "token": "DEVICE_TOKEN",
    "notification": {
      "title": "Summer Vacation Announcement 🌴",
      "body": "The academy will remain closed from June 1 to June 15."
    },
    "data": {
      "type": "holiday",
      "reference_id": "7",
      "image": "https://example.com/storage/announcements/holiday.jpg"
    }
  }
}
```
