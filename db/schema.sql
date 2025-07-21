-- --------------------------------------------------
-- Database : fleet
-- Run   :   mysql -u root < db/schema.sql
-- --------------------------------------------------

DROP DATABASE IF EXISTS fleet;
CREATE DATABASE fleet CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE fleet;

-- -----------------------
-- ROLES
-- -----------------------
CREATE TABLE roles (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(50) NOT NULL UNIQUE
);
INSERT INTO roles (name) VALUES ('Super Admin'), ('Admin'), ('Data Entry Officer'), ('Guest');

-- -----------------------
-- USERS
-- -----------------------
CREATE TABLE users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(100) NOT NULL UNIQUE,
  password_hash VARCHAR(255) NOT NULL,
  role_id INT NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (role_id) REFERENCES roles(id)
);
-- password for all demo users = admin123 (bcrypt)
INSERT INTO users (username, password_hash, role_id) VALUES
  ('superadmin', '$2y$10$X1YXAoEKYszQyRRpJM51hurAi6Lr1OCtk2eY91qL3VH4QgkWhjwJu', 1),
  ('admin',       '$2y$10$X1YXAoEKYszQyRRpJM51hurAi6Lr1OCtk2eY91qL3VH4QgkWhjwJu', 2),
  ('dataentry',   '$2y$10$X1YXAoEKYszQyRRpJM51hurAi6Lr1OCtk2eY91qL3VH4QgkWhjwJu', 3),
  ('guest',       '$2y$10$X1YXAoEKYszQyRRpJM51hurAi6Lr1OCtk2eY91qL3VH4QgkWhjwJu', 4);

-- -----------------------
-- VEHICLES
-- -----------------------
CREATE TABLE vehicles (
  id INT AUTO_INCREMENT PRIMARY KEY,
  brand VARCHAR(100) NOT NULL,
  serial_number VARCHAR(100) NOT NULL,
  year_allocation YEAR,
  engine_number VARCHAR(100),
  chassis_number VARCHAR(100),
  tracker_number VARCHAR(100),
  tracker_imei VARCHAR(100),
  agency VARCHAR(100),
  location VARCHAR(100),
  tracker_status ENUM('Active','Inactive') DEFAULT 'Active',
  serviceability ENUM('In Use','Off-Road') DEFAULT 'In Use',
  created_by INT,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (created_by) REFERENCES users(id)
);

-- -----------------------
-- MAINTENANCE
-- -----------------------
CREATE TABLE maintenance (
  id INT AUTO_INCREMENT PRIMARY KEY,
  vehicle_id INT NOT NULL,
  type ENUM('Scheduled','Unscheduled','Overhaul') NOT NULL,
  description TEXT,
  maintenance_date DATE NOT NULL,
  next_date DATE,
  created_by INT,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (vehicle_id) REFERENCES vehicles(id) ON DELETE CASCADE,
  FOREIGN KEY (created_by) REFERENCES users(id)
);

-- -----------------------
-- SETTINGS
-- -----------------------
CREATE TABLE settings (
  id INT AUTO_INCREMENT PRIMARY KEY,
  `key` VARCHAR(100) NOT NULL UNIQUE,
  `value` VARCHAR(255) NOT NULL
);
INSERT INTO settings (`key`, `value`) VALUES
 ('scheduled_interval_months', '3'),
 ('overhaul_interval_months', '12');

-- -----------------------
-- NOTIFICATIONS
-- -----------------------
CREATE TABLE notifications (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT NULL,
  message VARCHAR(255) NOT NULL,
  is_read TINYINT(1) DEFAULT 0,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
);

-- -----------------------
-- AUDIT LOGS
-- -----------------------
CREATE TABLE audit_logs (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT NULL,
  action VARCHAR(255) NOT NULL,
  ip_address VARCHAR(45) NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
);

ALTER TABLE notifications ADD COLUMN type VARCHAR(30) NOT NULL DEFAULT 'System';
ALTER TABLE audit_logs ADD COLUMN user_agent VARCHAR(255) NULL, ADD COLUMN request_url VARCHAR(255) NULL, ADD COLUMN request_params TEXT NULL;