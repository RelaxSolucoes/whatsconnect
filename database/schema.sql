-- ========================================
-- WhatsConnect Database Schema
-- MySQL 5.7+ / MariaDB 10.2+
-- ========================================

-- Create database
CREATE DATABASE IF NOT EXISTS whatsconnect 
CHARACTER SET utf8mb4 
COLLATE utf8mb4_unicode_ci;

USE whatsconnect;

-- ========================================
-- Registrations Table
-- Stores user registrations
-- ========================================
CREATE TABLE IF NOT EXISTS registrations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    full_name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL,
    whatsapp VARCHAR(20) NOT NULL,
    company_name VARCHAR(255) NOT NULL,
    chatwoot_account_id INT NULL,
    chatwoot_user_id INT NULL,
    evolution_instance VARCHAR(100) NULL,
    status ENUM('active', 'inactive', 'pending') DEFAULT 'active',
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    INDEX idx_email (email),
    INDEX idx_whatsapp (whatsapp),
    INDEX idx_status (status),
    INDEX idx_created_at (created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ========================================
-- Settings Table
-- Stores application settings
-- ========================================
CREATE TABLE IF NOT EXISTS settings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    setting_key VARCHAR(100) NOT NULL UNIQUE,
    setting_value TEXT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    INDEX idx_key (setting_key)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ========================================
-- Message Logs Table
-- Stores sent messages log
-- ========================================
CREATE TABLE IF NOT EXISTS message_logs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    registration_id INT NULL,
    phone_number VARCHAR(20) NOT NULL,
    message_type ENUM('welcome', 'custom', 'test') DEFAULT 'welcome',
    status ENUM('sent', 'failed', 'pending') DEFAULT 'pending',
    error_message TEXT NULL,
    sent_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    
    FOREIGN KEY (registration_id) REFERENCES registrations(id) ON DELETE SET NULL,
    INDEX idx_phone (phone_number),
    INDEX idx_status (status),
    INDEX idx_sent_at (sent_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ========================================
-- Admin Users Table (Optional)
-- For multiple admin users
-- ========================================
CREATE TABLE IF NOT EXISTS admin_users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(255) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    name VARCHAR(255) NOT NULL,
    role ENUM('super_admin', 'admin', 'viewer') DEFAULT 'admin',
    is_active BOOLEAN DEFAULT TRUE,
    last_login DATETIME NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    INDEX idx_email (email),
    INDEX idx_role (role)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ========================================
-- Insert default admin user
-- Password: admin123 (change this!)
-- ========================================
INSERT INTO admin_users (email, password_hash, name, role) VALUES 
('admin@whatsconnect.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Administrador', 'super_admin')
ON DUPLICATE KEY UPDATE email = email;

-- ========================================
-- Insert default settings
-- ========================================
INSERT INTO settings (setting_key, setting_value) VALUES 
('app_name', 'WhatsConnect'),
('app_version', '1.0.0'),
('maintenance_mode', 'false')
ON DUPLICATE KEY UPDATE setting_key = setting_key;
