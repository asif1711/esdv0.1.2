# ESD - Employee Smart Detection & Attendance Management System

## Overview

ESD (Employee Smart Detection) is a secure attendance and employee verification platform that combines facial recognition, OTP verification, and role-based access control to streamline attendance tracking and workforce management.

The system is designed to prevent proxy attendance, improve security, and provide a centralized dashboard for administrators, managers, and employees.

---

## Key Features

### Authentication & Security

* Mobile Number Verification using OTP
* SMS Delivery via Twilio
* Secure Session Management
* Role-Based Access Control (RBAC)
* Multi-Level Authentication Flow

### Face Recognition

* Employee Face Registration
* Face Dataset Capture
* LBPH Face Recognition
* Real-Time Face Verification
* Secure Attendance Validation

### Attendance Management

* Employee Check-In
* Employee Check-Out
* Attendance History
* Attendance Status Tracking
* Daily Attendance Reports

### Dashboard Modules

* Employee Dashboard
* Manager Dashboard
* Administrator Dashboard
* Attendance Monitoring
* Employee Management

### Data Management

* MySQL Database Integration
* Attendance Records
* Employee Profiles
* Face Dataset Management
* Audit Logging

---

## Technology Stack

### Frontend

* HTML5
* CSS3
* JavaScript
* Bootstrap

### Backend

* PHP
* Python (Face Recognition Service)

### Database

* MySQL

### Authentication

* Twilio OTP Service
* PHP Sessions

### Face Recognition

* OpenCV
* LBPH Face Recognizer
* NumPy

### Development Environment

* XAMPP
* Python 3.x

---

## Project Structure

```text
ESD/
│
├── auth/
│   ├── login.php
│   ├── otp.php
│   ├── verify_otp.php
│   └── auth_router.php
│
├── employee/
│   ├── dashboard.php
│   ├── attendance.php
│   └── profile.php
│
├── admin/
│   ├── dashboard.php
│   ├── users.php
│   └── reports.php
│
├── face_recognition/
│   ├── capture_face.php
│   ├── face_verify.php
│   ├── train_model.py
│   ├── recognize.py
│   └── server.py
│
├── assets/
│   ├── css/
│   ├── js/
│   └── images/
│
├── vendor/
│
├── db.php
├── index.php
└── README.md
```

---

## Installation

### Clone Repository

```bash
git clone https://github.com/your-repository/esd.git
```

### Configure XAMPP

Move project to:

```text
xampp/htdocs/esd
```

### Install PHP Dependencies

```bash
composer install
```

### Install Python Dependencies

```bash
pip install opencv-python
pip install numpy
pip install flask
pip install pillow
```

### Configure Environment

Create:

```text
.env
```

Add:

```env
TWILIO_ACCOUNT_SID=YOUR_SID
TWILIO_AUTH_TOKEN=YOUR_TOKEN
TWILIO_PHONE_NUMBER=YOUR_NUMBER
```

---

## Database Setup

Create database:

```sql
CREATE DATABASE vips;
```

Import:

```text
database/esd.sql
```

Update:

```php
db.php
```

with database credentials.

---

## Running the Application

### Start Apache & MySQL

Using XAMPP Control Panel.

### Start Face Recognition Server

```bash
python server.py
```

### Open Application

```text
http://localhost/esd
```

---

## Authentication Flow

1. User enters mobile number.
2. OTP is sent via Twilio.
3. OTP is verified.
4. User role is identified.
5. Dataset existence is checked.
6. If dataset exists:

   * Redirect to Face Verification.
7. If dataset does not exist:

   * Redirect to Face Registration.
8. Attendance is marked upon successful verification.

---

## User Roles

### Employee

* Check In
* Check Out
* View Attendance
* Manage Profile

### Manager

* Team Monitoring
* Attendance Review
* Team Reports

### Administrator

* User Management
* Attendance Oversight
* System Configuration
* Reporting

---

## Security Features

* OTP Verification
* Session Protection
* Face Recognition Validation
* Role-Based Access Control
* Secure Database Access
* Input Validation
* SQL Injection Protection

---

## Future Enhancements

* Face Anti-Spoofing
* GPS Verification
* Email Notifications
* Mobile Application
* AI Attendance Analytics
* Cloud Deployment

---

## Contributors

ESD Development Team

---

## License

Internal Project / Educational Use
