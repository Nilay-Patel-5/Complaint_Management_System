CREATE DATABASE IF NOT EXISTS complaint_system;
USE complaint_system;

CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    student_id VARCHAR(50) NOT NULL UNIQUE,
    room_no VARCHAR(50) NOT NULL,
    phone_no VARCHAR(20) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL
);

CREATE TABLE IF NOT EXISTS categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    category_name VARCHAR(100) NOT NULL
);

-- Insert default categories for Hostel Management
INSERT IGNORE INTO categories (id, category_name) VALUES 
(1, 'Electrical (Lights, Fans, Sockets)'), 
(2, 'Plumbing & Water Supply'), 
(3, 'Carpentry (Furniture, Doors, Windows)'), 
(4, 'Cleaning & Housekeeping'),
(5, 'Internet & Wi-Fi'),
(6, 'Mess & Food Services'),
(7, 'Security & Discipline'),
(8, 'Other');

CREATE TABLE IF NOT EXISTS complaints (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    category_id INT,
    title VARCHAR(255) NOT NULL,
    description TEXT NOT NULL,
    status ENUM('Pending', 'In Progress', 'Resolved') DEFAULT 'Pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE CASCADE
);
