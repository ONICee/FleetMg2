# Fleet Management System – User Manual
_Version 1.0  ·  21 Jul 2025_

## 1  Login
Browse to **http://localhost/fleet** and enter your credentials.

| Role | Username | Password |
|------|----------|----------|
| Super Admin | `superadmin` | `admin123` |
| Admin | `admin` | `admin123` |
| Data Entry | `dataentry` | `admin123` |
| Guest (read-only) | `guest` | `admin123` |

## 2  Main Navigation
| Menu | Purpose |
|------|---------|
| Dashboard | KPI cards and quick links |
| Vehicles | Register, edit and locate vehicles |
| Maintenance | Log scheduled / unscheduled / overhaul jobs |
| Notifications | Bell icon + full list of alerts |
| Users | (Super Admin) Manage accounts & roles |
| Settings | Global options + Tracking API |
| Audit Logs | Full action history |

## 3  Vehicles
1. **Add** – Sidebar › Vehicles › Add Vehicle.  Brand & Serial No. are required.
2. **Edit / Delete** – Action buttons on the list.
3. **Location** – When tracking is enabled, a red pin opens Google Maps at the last GPS fix.
4. **Export CSV** – Top-right button honours current filters.

## 4  Maintenance
1. Open a vehicle → _Add Maintenance_.
2. Choose **Type** (Scheduled, Unscheduled, Overhaul).
3. Next-Due date auto-calculates from Settings.
4. Pill buttons (All / Scheduled / …) filter the table.
5. CSV export obeys the current type filter.

## 5  Notifications
• Red badge = number of unread personal alerts.  
• Click bell → dropdown opens → badge clears.  
• Full list: Sidebar › Notifications.

Types of alerts:
* **Maintenance** – due/overdue reminders.
* **Fleet** – vehicles added / removed.
* **System** – user or settings changes.

## 6  User Management (Super Admin)
1. Sidebar › Users.
2. _Add User_ → set username, password, role.
3. Edit or Delete via action icons. (Primary superadmin cannot be deleted.)

## 7  Settings
### 7.1  Maintenance Intervals
• Scheduled (months) – default 3  |  Overhaul (months) – default 12.

### 7.2  Vehicle Tracking
| Field | Example |
|-------|---------|
| API Base URL | `https://api.tracker.com` |
| Auth Type | none · api_key · bearer · basic |
| API Key / Token | `abc123` |
| API Secret | `p@ssw0rd` (only for basic) |

Leave **Auth Type = none** until you have credentials; tracking is then disabled.

### 7.3  Applying Changes
Click **Save** → green toast confirms.  Settings are effective instantly.

## 8  Audit Logs
Every action is captured with Date, User, Action, IP, URL and Browser.

Use DataTables search box to find entries quickly.

## 9  FAQ
**Bell shows no number?**  All personal alerts are read.  
**Location column is “—”?**  Tracking not yet enabled or no fix received.  
**How to change theme colour?**  Edit `assets/css/style.css` → `--brand-yellow`.

Enjoy managing your fleet 🚗🚐🚓!