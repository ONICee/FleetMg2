# Fleet Management System – Technical Reference
_Last updated: 21 Jul 2025_

## 1  Technology Stack
| Layer | Tool / Version |
|-------|----------------|
| Backend | PHP 8.x (Vanilla – no framework) |
| Database | MySQL 8 (InnoDB) |
| Front-end | Bootstrap 5.3 · Font-Awesome 6 |
| Charts / Tables | Chart.js 4 · DataTables 1.13 |
| Auth / Security | PHP sessions, bcrypt, PDO prepared |

## 2  Repository Layout (top-level)
```
assets/      → css, js, images (logo)
auth/        → login / logout
config/      → constants.php, security.php
cron/        → fetch_tracking.php
 db/         → connection helper + schema
 docs/       → **<– you are here**
includes/    → reusable html partials
modules/
  ├─ vehicles/      Vehicle CRUD & export
  ├─ maintenance/   Maintenance CRUD & export
  ├─ notifications/ Tabbed in-app feed
  ├─ users/         User & role admin
  ├─ logs/          Audit-trail viewer
settings/    → general.php (global options)
views/       → role-based dashboards
```

## 3  Database Extras (tracking integration)
```sql
-- 3 live-tracking columns (nullable)
ALTER TABLE vehicles
  ADD last_lat DECIMAL(10,6),
  ADD last_lng DECIMAL(10,6),
  ADD last_gps DATETIME;

-- 4 settings keys for the tracking API
INSERT IGNORE INTO settings (`key`,`value`) VALUES
 ('track_api_base', ''),
 ('track_auth_type','none'),
 ('track_api_key',  ''),
 ('track_api_secret','');
```

## 4  Tracking Fetcher (cron)
File: `cron/fetch_tracking.php`
```php
$base   = get_setting($pdo,'track_api_base');
$type   = get_setting($pdo,'track_auth_type');
$key    = get_setting($pdo,'track_api_key');
$secret = get_setting($pdo,'track_api_secret');
if (!$base || $type==='none') exit; // disabled
...
```

Typical cron entry (Linux):
```
*/10 * * * * php /var/www/fleet/cron/fetch_tracking.php
```
On Windows + XAMPP use Task Scheduler.

## 5  Coding Guidelines
* Always route pages through `config/security.php` for session & RBAC.
* Build new modules under `modules/…` and set `$breadcrumbs` before `include header.php`.
* Use DataTables by adding the class `datatable` to `<table>`.
* Record every significant action:
  ```php
  log_action($pdo,$_SESSION['user']['id'],'Your action');
  ```

## 6  Security Notes
* Passwords hashed with `password_hash()` (bcrypt).
* All DB access via PDO prepared statements.
* Session cookie: `SameSite=Strict`, `HttpOnly`, and `Secure` when HTTPS.

## 7  Theming
All colours, radius and shadows live in `assets/css/style.css` under the `:root` block.
Change `--brand-yellow` or other CSS vars to re-skin the whole UI.