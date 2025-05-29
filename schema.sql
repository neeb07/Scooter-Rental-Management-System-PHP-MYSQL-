-- Database schema for Scooter Rental Management System
-- Created: [Current Date]
-- Author: [Your Name]

-- Create database
CREATE DATABASE IF NOT EXISTS scooter_rental;
USE scooter_rental;

-- Users table
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    is_admin TINYINT(1) DEFAULT 0,
    INDEX idx_username (username),
    INDEX idx_email (email)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Scooters table
CREATE TABLE IF NOT EXISTS scooters (
    id INT AUTO_INCREMENT PRIMARY KEY,
    model VARCHAR(100) NOT NULL,
    color VARCHAR(50) NOT NULL,
    max_speed INT NOT NULL,
    battery_level INT NOT NULL,
    rental_price DECIMAL(10,2) NOT NULL,
    is_available TINYINT(1) DEFAULT 1,
    --created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_model (model),
    INDEX idx_availability (is_available)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Rentals table
CREATE TABLE IF NOT EXISTS rentals (
    id INT AUTO_INCREMENT PRIMARY KEY,
    scooter_id INT NOT NULL,
    user_id INT NOT NULL,
    customer_name VARCHAR(100) NOT NULL,
    rental_start DATETIME NOT NULL,
    rental_end DATETIME NULL,
    total_cost DECIMAL(10,2) NULL,
    --created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (scooter_id) REFERENCES scooters(id) ON DELETE RESTRICT,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_scooter (scooter_id),
    INDEX idx_user (user_id),
    INDEX idx_rental_dates (rental_start, rental_end)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Sample data for testing
INSERT INTO users (username, password, email, is_admin) VALUES
('admin', '$2y$12$QQr4aROhZ.j4BHvBgV.ExeBt/QyJQ5vUod2PK.LXKQclH6Th40iHS', 'admin@example.com', 1),
('user1', '$2y$12$zzaa57t2ifYDJ8IxhNO9a.QNwEdFXnxhwCjIWUGINH1e.4aOdVo8C', 'user1@example.com', 0),
('user2', '$2y$12$JEG/lQR5sRAIuWwh9Pw8aemRdLyq2WGujHwmi14uBSgNq2XL6QkTe', 'user2@example.com', 0);

INSERT INTO scooters (model, color, max_speed, battery_level, rental_price) VALUES
('Xiaomi Mi Pro 2', 'Black', 25, 90, 4.50),
('Segway Ninebot MAX', 'White', 30, 85, 5.75),
('Razor E300', 'Blue', 24, 75, 3.99);

INSERT INTO rentals (scooter_id, user_id, customer_name, rental_start, rental_end, total_cost) VALUES
(1, 2, 'user1', '2025-05-01 10:00:00', '2025-05-01 12:30:00', 11.25),
(2, 3, 'user2', '2025-05-02 14:00:00', NULL, NULL),
(3, 2, 'user1', '2025-05-03 09:00:00', '2025-05-03 11:00:00', 7.98);

-- Create views for reporting
CREATE VIEW active_rentals AS
SELECT r.id, u.username, s.model, r.rental_start
FROM rentals r
JOIN users u ON r.user_id = u.id
JOIN scooters s ON r.scooter_id = s.id
WHERE r.rental_end IS NULL AND r.is_deleted = 0;

CREATE VIEW scooter_utilization AS
SELECT 
    s.id,
    s.model,
    COUNT(r.id) AS total_rentals,
    SUM(CASE WHEN r.rental_end IS NULL THEN 1 ELSE 0 END) AS active_rentals,
    AVG(r.total_cost) AS avg_earning
FROM scooters s
LEFT JOIN rentals r ON s.id = r.scooter_id
GROUP BY s.id, s.model;

-- Create stored procedure for ending rentals
DELIMITER //
CREATE PROCEDURE end_rental(IN rental_id INT)
BEGIN
    DECLARE scooter_id_val INT;
    DECLARE rental_cost DECIMAL(10,2);
    
    START TRANSACTION;
    
    -- Get scooter ID and calculate cost
    SELECT r.scooter_id, 
           TIMESTAMPDIFF(HOUR, r.rental_start, NOW()) * s.rental_price
    INTO scooter_id_val, rental_cost
    FROM rentals r
    JOIN scooters s ON r.scooter_id = s.id
    WHERE r.id = rental_id AND r.rental_end IS NULL
    FOR UPDATE;
    
    -- Update rental record
    UPDATE rentals
    SET rental_end = NOW(),
        total_cost = rental_cost
    WHERE id = rental_id;
    
    -- Mark scooter as available
    UPDATE scooters
    SET is_available = 1
    WHERE id = scooter_id_val;
    
    COMMIT;
END //
DELIMITER ;