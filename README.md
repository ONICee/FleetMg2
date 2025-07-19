# Fleet Management System (Vanilla PHP)

This repository contains a fully-functional Fleet Management System built with:

* PHP 8 (no frameworks)
* MySQL
* JavaScript (ES6)
* Bootstrap 5.3

---
## 1. Features / Modules

1. **User Authentication & RBAC** – 4 built-in roles (Super Admin, Admin, Data Entry Officer, Guest) with isolated dashboards and permissions.
2. **Vehicle Management** – CRUD for vehicle records with rich metadata and per-vehicle e-file.
3. **Maintenance Tracking** – Scheduled (3-month), Unscheduled and Overhaul (annual) maintenance with reminders.
4. **In-App Notifications** – Real-time (poll-based) alerts for maintenance, vehicle updates, and system events.
5. **Audit Logs** – Every critical action is recorded for traceability.
6. **Settings Module** – Global configuration (maintenance intervals, organisation info, …) – Super Admin only.
7. **Reports & Insights** – Filter/search, export to CSV/PDF, and dashboard KPIs.

---
## 2. Folder Structure

```
assets/        → CSS, JS, images
includes/      → Layout partials (header, sidebar, footer)
auth/          → Login / logout
modules/
  ├─ vehicles/ → Vehicle CRUD & views
  ├─ maintenance/
  ├─ notifications/
  └─ logs/
views/         → Role-based dashboards
settings/      → Global settings page
db/            → SQL schema & connection helper
config/        → Constants & security helpers
index.php      → Entry-point / router
```

---
## 3. Local Installation (XAMPP)

1. **Clone / copy** this repository inside `htdocs` and rename to `fleet` (so it lives at `C:/xampp/htdocs/fleet`).
2. **Create the database**:
   * Start MySQL from XAMPP.
   * Import `db/schema.sql` using phpMyAdmin or `mysql -u root < db/schema.sql`.
3. **Configure credentials** (if different):
   * Edit `config/constants.php` and update `DB_USER`, `DB_PASS`, or `BASE_URL`.
4. **Browse** to `http://localhost/fleet` – you should see the login page.

---
## 4. Default Accounts

| Role          | Username      | Password  |
|---------------|---------------|-----------|
| Super Admin   | `superadmin`  | `admin123`|
| Admin         | `admin`       | `admin123`|
| Data Entry    | `dataentry`   | `admin123`|
| Guest         | `guest`       | `admin123`|

> You can modify or remove these dummy users after your first login.

---
## 5. Testing Guide

* Log in with each role to verify the correct dashboard and permissions.
* Add a new vehicle, then add maintenance records to ensure e-file linkage.
* Change global settings (as Super Admin) and confirm intervals are reflected in reminders.
* Download a CSV report from the Admin dashboard.
* Review **Audit Logs** to validate that actions are captured.

---
## 6. Security Checklist

* All database calls use **prepared statements** (PDO).
* Passwords are hashed with **bcrypt** (`password_hash`).
* Session cookies are `HttpOnly`, `Secure` (when HTTPS), and `SameSite=Strict`.
* Input is sanitised with `htmlspecialchars` to mitigate XSS.

Happy managing! 🚀