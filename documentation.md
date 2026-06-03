# ESD System Documentation

# 1. Introduction

Employee Smart Detection (ESD) is a secure attendance and identity verification platform designed to ensure authentic employee attendance through a combination of OTP verification and facial recognition technology.

---

# 2. System Objectives

* Eliminate proxy attendance.
* Improve attendance accuracy.
* Provide secure authentication.
* Centralize employee management.
* Generate attendance analytics.

---

# 3. System Architecture

```text
User
 │
 ▼
Login Page
 │
 ▼
OTP Verification
 │
 ▼
Role Identification
 │
 ▼
Dataset Check
 ├──────────────┐
 │              │
 ▼              ▼
Face Register   Face Verify
 │              │
 └──────┬───────┘
        ▼
Attendance Marking
        ▼
Dashboard
```

---

# 4. Authentication Workflow

## Step 1

User submits mobile number.

## Step 2

System generates OTP.

## Step 3

Twilio sends OTP.

## Step 4

User verifies OTP.

## Step 5

System validates OTP.

## Step 6

Role-based routing occurs.

---

# 5. Face Registration Process

## Purpose

Create employee facial dataset.

### Workflow

1. Open camera.
2. Capture multiple facial samples.
3. Store images.
4. Generate dataset.
5. Train recognizer.

### Output

```text
dataset/
trained_model.yml
```

---

# 6. Face Verification Process

## Purpose

Validate employee identity.

### Workflow

1. Start camera.
2. Detect face.
3. Extract features.
4. Compare with trained model.
5. Determine confidence score.
6. Approve or reject verification.

---

# 7. Attendance Workflow

```text
Verify Identity
      │
      ▼
Check Attendance Status
      │
      ▼
Create Attendance Record
      │
      ▼
Update Dashboard
```

---

# 8. Database Design

## users

| Field          | Type    |
| -------------- | ------- |
| id             | INT     |
| name           | VARCHAR |
| mobile         | VARCHAR |
| role           | VARCHAR |
| dataset_exists | TINYINT |

---

## attendance

| Field     | Type     |
| --------- | -------- |
| id        | INT      |
| user_id   | INT      |
| check_in  | DATETIME |
| check_out | DATETIME |
| status    | VARCHAR  |

---

## otp_logs

| Field      | Type     |
| ---------- | -------- |
| id         | INT      |
| user_id    | INT      |
| otp_hash   | VARCHAR  |
| expires_at | DATETIME |

---

# 9. Core Components

## PHP Layer

Responsibilities:

* Authentication
* Session Management
* Routing
* Database Operations
* Dashboard Rendering

---

## Python Layer

Responsibilities:

* Face Detection
* Face Recognition
* Dataset Training
* Verification Service

---

## MySQL Layer

Responsibilities:

* Persistent Storage
* Attendance Records
* Employee Information
* Audit Data

---

# 10. Security Design

## OTP Security

* OTP Hashing
* Expiration Control
* Verification Limits

## Session Security

* Session Validation
* Session Regeneration
* Access Restrictions

## Database Security

* Prepared Statements
* Input Sanitization
* Parameter Binding

---

# 11. Error Handling

Common Scenarios

### OTP Failure

* Invalid OTP
* Expired OTP
* SMS Delivery Failure

### Face Recognition Failure

* No Face Detected
* Multiple Faces Detected
* Confidence Too Low

### System Failure

* Database Unavailable
* Python Service Offline
* Network Failure

---

# 12. Deployment Guide

## Production Requirements

### Server

* Linux VPS
* Apache/Nginx
* PHP 8+
* Python 3.10+
* MySQL 8+

### SSL

HTTPS is mandatory.

---

# 13. Maintenance

Regular Tasks

* Database Backup
* Model Retraining
* Attendance Archive
* Security Updates
* Log Monitoring

---

# 14. Future Roadmap

Phase 1

* Complete OTP Integration

Phase 2

* Complete Face Recognition

Phase 3

* Attendance Analytics

Phase 4

* Mobile Application

Phase 5

* Cloud Infrastructure

---

# 15. Conclusion

The ESD platform provides a secure, scalable, and reliable employee attendance ecosystem using modern authentication techniques, biometric verification, and centralized management.
